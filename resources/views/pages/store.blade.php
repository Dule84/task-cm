@extends('welcome')

@section('content')
    <form enctype="multipart/form-data">
        <div class="form-group mt-3">
            <label for="pdf_file"></label>
            <input type="file" class="form-control-file" id="pdf_file" name="pdf_file">
            <span class="text-danger">{!! $errors->first('pdf_file', ':message') !!} </span>
        </div>
        <div id="signature-pad" class="m-signature-pad mt-3">
            <div class="m-signature-pad--body">
                <canvas style="border: 2px dashed #ccc"></canvas>
            </div>
        </div>
        <button type="button" class="btn btn-primary mt-3" id="sign">Potpisi</button>
    </form>
@endsection

@push('scripts')
    <script src="{{ asset('scripts/app.js')}}"></script>
@endpush
