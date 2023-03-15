<tr>
    <td>{{ $file->pdf_file_name }}</td>
    <td>{{ \Carbon\Carbon::parse($file->created_at)->format('d.m.Y H:i') }}</td>
    <td>
        <a href="{{ route('download', ['type' => 'pdf_file', 'file' => $file->pdf_generated_name]) }}" download="{{ $file->pdf_file_name }}">
            <i class="fa fa-solid fa-file-pdf"></i>
        </a>
    </td>
    <td>
        <a href="{{ route('download', ['type' => 'pdf_file_signed', 'file' => 'signed_'.$file->pdf_generated_name]) }}" download="{{ $file->pdf_file_name }}">
            <i class="fa fa-solid fa-file-pdf"></i>
        </a>
    </td>
</tr>
