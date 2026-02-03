@extends('layouts.adminlte-with-language')

@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush

@section('js')
    <script>
        window.dashboardData = @json($charts);
    </script>
    @vite(['resources/js/dashboard.js'])
@endsection

@section('title', 'Dashboard')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <!-- Card Produtos -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $stats['products']['total'] }}</h3>
                            <p>Produtos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            Ver detalhes <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <!-- Card Clientes -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $stats['customers']['total'] }}</h3>
                            <p>Clientes</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            Ver detalhes <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <!-- Card Orçamentos -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $stats['quotes']['total'] }}</h3>
                            <p>Orçamentos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            Ver detalhes <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <!-- Card Pedidos -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $stats['orders']['total'] }}</h3>
                            <p>Pedidos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            Ver detalhes <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Gráfico de Receita Mensal -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
                                Receita Mensal: Orçamentos vs Pedidos
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="revenueChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráfico de Categorias de Produtos -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
                                Categorias de Produtos
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="categoryChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Gráfico de Segmentos de Clientes -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
                                Segmentos de Clientes
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="customerSegmentChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráfico de Status de Pedidos -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
                                Status dos Pedidos
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="orderStatusChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Card Detalhes Produtos -->
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Detalhes Produtos</h3>
                        </div>
                        <div class="card-body">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-box"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Ativos</span>
                                    <span class="info-box-number">{{ $stats['products']['active'] }}</span>
                                </div>
                            </div>
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-box"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Inativos</span>
                                    <span class="info-box-number">{{ $stats['products']['inactive'] }}</span>
                                </div>
                            </div>
                            <div class="progress-group">
                                <span class="progress-text">Crescimento</span>
                                <span class="float-right"><b>{{ $stats['products']['growth'] }}%</b></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-info" style="width: {{ abs($stats['products']['growth']) }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Detalhes Clientes -->
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Detalhes Clientes</h3>
                        </div>
                        <div class="card-body">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Ativos</span>
                                    <span class="info-box-number">{{ $stats['customers']['active'] }}</span>
                                </div>
                            </div>
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-user-plus"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Novos este mês</span>
                                    <span class="info-box-number">{{ $stats['customers']['new_this_month'] }}</span>
                                </div>
                            </div>
                            <div class="progress-group">
                                <span class="progress-text">Crescimento</span>
                                <span class="float-right"><b>{{ $stats['customers']['growth'] }}%</b></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success" style="width: {{ abs($stats['customers']['growth']) }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Detalhes Orçamentos -->
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Detalhes Orçamentos</h3>
                        </div>
                        <div class="card-body">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pendentes</span>
                                    <span class="info-box-number">{{ $stats['quotes']['pending'] }}</span>
                                </div>
                            </div>
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Aprovados</span>
                                    <span class="info-box-number">{{ $stats['quotes']['approved'] }}</span>
                                </div>
                            </div>
                            <div class="progress-group">
                                <span class="progress-text">Crescimento</span>
                                <span class="float-right"><b>{{ $stats['quotes']['growth'] }}%</b></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar {{ $stats['quotes']['growth'] >= 0 ? 'bg-success' : 'bg-danger' }}" style="width: {{ abs($stats['quotes']['growth']) }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Detalhes Pedidos -->
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Detalhes Pedidos</h3>
                        </div>
                        <div class="card-body">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pendentes</span>
                                    <span class="info-box-number">{{ $stats['orders']['pending'] }}</span>
                                </div>
                            </div>
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-cog"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Processando</span>
                                    <span class="info-box-number">{{ $stats['orders']['processing'] }}</span>
                                </div>
                            </div>
                            <div class="progress-group">
                                <span class="progress-text">Crescimento</span>
                                <span class="float-right"><b>{{ $stats['orders']['growth'] }}%</b></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success" style="width: {{ abs($stats['orders']['growth']) }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
