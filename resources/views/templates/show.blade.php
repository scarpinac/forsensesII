@extends('adminlte::page')

@section('title', '{{ $action }} {{ $title }}')

@section('content_header')
    <h1 class="m-0">{{ $action }} {{ $title }}</h1>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p>View {{ $action }} - Em construção</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
