@extends('whitepage.layouts.app')

@section('content')
    @include('whitepage.components.title')
    @include('whitepage.components.form', ['section' => 'project'])
@endsection
