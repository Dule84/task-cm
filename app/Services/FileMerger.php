<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\Filter\FilterException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use setasign\Fpdi\PdfReader\PdfReaderException;

class FileMerger
{
    /**
     * Merge PDF file with signature png file
     *
     * @param string $pdfFile
     * @param string $signatureFile
     *
     * @return string
     *
     * @throws CrossReferenceException
     * @throws FilterException
     * @throws PdfParserException
     * @throws PdfReaderException
     * @throws PdfTypeException
     */
    public function signPDFFile(string $pdfFile, string $signatureFile): string
    {
        if (!Storage::disk('local')->exists('public/signed_pdf_files/')) {
            Storage::disk('local')->makeDirectory('public/signed_pdf_files/');
        }

        $destinationPath = sprintf('%s/app/public', storage_path());
        $filePath = $destinationPath.'/pdf_files/'.$pdfFile;
        $outputFilePath = $destinationPath.'/signed_pdf_files/signed_'.$pdfFile;

        $fpdi = new FPDI;

        $fpdi->setSourceFile($filePath);

        $template = $fpdi->importPage(1);
        $size = $fpdi->getTemplateSize($template);
        $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
        $fpdi->useTemplate($template);

        unset($template, $size);

        $fpdi->SetFont("helvetica", "", 15);
        $fpdi->SetTextColor(153,0,153);

        $destinationPath = sprintf('%s/app/public', storage_path());
        $filePath = $destinationPath.'/signatures/'.$signatureFile;

        unset($destinationPath);

        $fpdi->Image($filePath, 110, 220);

        unset($filePath);

        return $fpdi->Output($outputFilePath, 'F');
    }
}
