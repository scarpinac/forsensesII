@extends('adminlte::page')

@section('title', 'Novo {{ $title }}')

@section('content_header')
    <h1 class="m-0">Novo {{ $title }}</h1>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Cadastrar {{ $title }}</h3>
                </div>
                <div class="card-body">
                    @include('{{ $formView }}')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
