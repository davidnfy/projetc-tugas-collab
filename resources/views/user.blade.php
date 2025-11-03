@extends('layout.app')
@section('title', 'User Todo List')

@section('content')
    {{-- Include partial untuk tampilan todo dari kategori user --}}
    @include('partials.user', ['category' => $category, 'todos' => $todos])
@endsection
