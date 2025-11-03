@extends('layout.app') {{-- pastikan nama folder dan file sesuai layout kamu --}}
@section('title', 'Daily Tasks')

@section('content')
    {{-- Ini isi dari partial --}}
    @include('partials.daily', ['todos' => $todos])
@endsection
