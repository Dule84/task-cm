@extends('welcome')

@section('content')

    @include('_includes.alert')

    <a href="{{ route('store') }}" class="btn btn-success mt-3">Potpisi PDF</a>

    <table class="table mt-3">
        <thead>
        <tr>
            <th scope="col">Naziv originalnog fajla</th>
            <th scope="col">Uploadovano</th>
            <th scope="col">Prikazi originalni fajl</th>
            <th scope="col">Prikazi potpisani fajl</th>
        </tr>
        </thead>
        <tbody>
        @foreach($files as $file)
            <x-table :file="$file"/>
        @endforeach
        </tbody>
    </table>
@endsection
