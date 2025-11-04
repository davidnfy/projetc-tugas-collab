@extends('layout.app')
@section('title', 'User Todo List')

@section('content')
    @include('partials.user', ['category' => $category, 'todos' => $todos])
@endsection
