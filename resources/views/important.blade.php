@extends('layout.app') 
@section('title', 'Important Tasks')

@section('content')
    @include('partials.important', ['todos' => $todos])
@endsection
