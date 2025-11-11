@php
    $section = app('section');
@endphp

@extends('whitepage.layouts.app')

@section('content')
    @include('whitepage.components.title')
    @include('whitepage.components.form', ['section' => $section->getName()])
@endsection
