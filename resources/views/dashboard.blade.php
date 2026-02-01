@extends('layouts.adminlte-with-language')


@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Bem-vindo ao painel administrativo!</p>
@stop
