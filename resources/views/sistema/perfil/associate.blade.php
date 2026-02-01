@extends('layouts.adminlte-with-language')

@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('title', __('labels.access_level.associate.title'))

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.access_level.associate.title') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('sistema.perfil.index') }}">{{ __('labels.access_level.title') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.access_level.associate.title') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-users"></i>
                            {{ __('labels.access_level.associate.profile') }}: <strong>{{ $perfil->descricao }}</strong>
                        </h3>
                        <div class="card-tools">
                            <a href="{{ URL::signedRoute('sistema.perfil.index') }}" class="btn btn-tool btn-sm">
                                <i class="fas fa-arrow-left"></i> {{ __('labels.button.back') }}
                            </a>
                        </div>
                    </div>

                    <form action="{{ URL::signedRoute('sistema.perfil.associate.update', $perfil) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Usuários Disponíveis -->
                            <div class="row">
                                <div class="col-md-6">
                                    <h5><i class="fas fa-user-plus text-success"></i> {{ __('labels.access_level.associate.available_users') }}</h5>
                                    <div class="form-group">
                                        @if($usuariosDisponiveis->count() > 0)
                                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('labels.access_level.associate.name') }}</th>
                                                            <th>{{ __('labels.access_level.associate.email') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($usuariosDisponiveis as $usuario)
                                                            <tr>
                                                                <td colspan="2">
                                                                    <div class="form-check">
                                                                        <input type="checkbox" name="usuarios[]" value="{{ $usuario->id }}" class="form-check-input" id="user_{{ $usuario->id }}">
                                                                        <label class="form-check-label d-flex align-items-center" for="user_{{ $usuario->id }}">
                                                                            <strong>{{ $usuario->name }}</strong>
                                                                            <span class="ml-2 text-muted">({{ $usuario->email }})</span>
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle"></i> {{ __('labels.access_level.associate.no_available_users') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Usuários Já Associados -->
                                <div class="col-md-6">
                                    <h5><i class="fas fa-user-check text-primary"></i> {{ __('labels.access_level.associate.associated_users') }}</h5>
                                    <div class="form-group">
                                        @if($usuariosAssociados->count() > 0)
                                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('labels.access_level.associate.name') }}</th>
                                                            <th>{{ __('labels.access_level.associate.email') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($usuariosAssociados as $usuario)
                                                            <tr>
                                                                <td colspan="2">
                                                                    <div class="form-check">
                                                                        <input type="checkbox" name="usuarios[]" value="{{ $usuario->id }}" class="form-check-input" id="user_assoc_{{ $usuario->id }}" checked>
                                                                        <label class="form-check-label d-flex align-items-center" for="user_assoc_{{ $usuario->id }}">
                                                                            <strong>{{ $usuario->name }}</strong>
                                                                            <span class="ml-2 text-muted">({{ $usuario->email }})</span>
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle"></i> {{ __('labels.access_level.associate.no_associated_users') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> {{ __('labels.access_level.associate.save') }}
                                </button>

                                <a href="{{ URL::signedRoute('sistema.perfil.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> {{ __('labels.access_level.back') }}</a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('js')
<script>
// Script simplificado - não há necessidade de controle de select all
</script>
@endpush
