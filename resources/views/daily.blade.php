@extends('layout.app') 
@section('title', 'Daily Tasks')

@section('content')
    @include('partials.daily', ['todos' => $todos])
@endsection
