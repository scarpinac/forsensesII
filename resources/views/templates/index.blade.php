@extends('adminlte::page')

@section('title', '{{ $title }}')

@section('content_header')
    <h1 class="m-0">{{ $title }}</h1>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lista de {{ $title }}</h3>
                    <div class="card-tools">
                        @canAccess('{{ $permissions.create }}')
                            <a href="{{ route('{{ $routes.create }}') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Novo
                            </a>
                        @endcanAccess
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(${{ $variablePlural }} as ${{ $variable }})
                                <tr>
                                    <td>{{ ${{ $variable }}->id }}</td>
                                    <td>{{ ${{ $variable }}->nome ?? '-' }}</td>
                                    <td>
                                        @canAccess('{{ $permissions.show }}')
                                            <a href="{{ route('{{ $routes.show }}', ${{ $variable }}) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endcanAccess
                                        @canAccess('{{ $permissions.edit }}')
                                            <a href="{{ route('{{ $routes.edit }}', ${{ $variable }}) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcanAccess
                                        @canAccess('{{ $permissions.destroy }}')
                                            <a href="{{ route('{{ $routes.destroy }}', ${{ $variable }}) }}" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        @endcanAccess
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ ${{ $variablePlural }}->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
