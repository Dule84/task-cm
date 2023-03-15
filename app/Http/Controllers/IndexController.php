<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadFilesRequest;
use App\Models\File;
use App\Services\FileMerger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\Filter\FilterException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use setasign\Fpdi\PdfReader\PdfReaderException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class IndexController extends Controller
{
    /**
     * Show all files
     *
     * @return View
     */
    public function index(): View
    {
        $files = File::query()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.index', compact('files'));
    }

    /**
     * Show form for uploading files
     *
     * @return View
     */
    public function store(): View
    {
        return view('pages.store');
    }

    /**
     * Upload PDF file and signature
     *
     * @param UploadFilesRequest $request
     * @return JsonResponse
     *
     * @throws CrossReferenceException
     * @throws FilterException
     * @throws PdfParserException
     * @throws PdfReaderException
     * @throws PdfTypeException
     */
    public function create(UploadFilesRequest $request): JsonResponse
    {
        $pdfFile = $request->file('pdf_file')->getClientOriginalName();

        if (!Storage::disk('local')->exists('public/pdf_files/')) {
            Storage::disk('local')->makeDirectory('public/pdf_files/');
        }

        $destinationPath = sprintf('%s/app/public/pdf_files/', storage_path());
        $pdfName = time().'.pdf';

        $request->file('pdf_file')->move(
            $destinationPath, $pdfName
        );

        unset($destinationPath);

        if (!Storage::disk('local')->exists('public/signatures/')) {
            Storage::disk('local')->makeDirectory('public/signatures/');
        }

        $image = explode(";base64,", $request->signature);
        $imageBase64 = base64_decode($image[0]);
        $signatureFile = uniqid() . '.png';

        Storage::disk('local')->put('public/signatures/'.$signatureFile, $imageBase64);

        unset($image, $imageBase64);

        File::query()
            ->create([
                'pdf_file_name'      => $pdfFile,
                'pdf_generated_name' => $pdfName
            ]);

        unset($pdfFile);

        (new FileMerger())->signPDFFile($pdfName, $signatureFile);

        unset($pdfName, $signatureFile);

        $request->session()->flash('message', 'Files are uploaded successfully.');
        $request->session()->flash('message-type', 'success');

        return response()->json([
           'message' => 'Files are saved successfully!'
        ]);
    }

    /**
     * Download original and signed PDFs
     *
     * @param string $type
     * @param string $file
     * @return BinaryFileResponse
     */
    public function download(string $type, string $file): BinaryFileResponse
    {
        $url = match ($type) {
            'pdf_file' => '/app/public/pdf_files/',
            'pdf_file_signed' => '/app/public/signed_pdf_files/',
            default => '/app/public/pdf_files/',
        };

        return response()->download(storage_path().$url.$file);
    }
}
