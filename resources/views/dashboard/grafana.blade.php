@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h3>Dashboard Analytics</h3>
        </div>
    </div>
    
    <div class="row">
        <!-- Pedidos Criados -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Pedidos Criados</h5>
                </div>
                <div class="card-body p-0">
                    <iframe 
                        src="http://localhost:3000/d-solo/dashboard-id/dashboard-name?orgId=1&panelId=1&from=now-7d&to=now&theme=light"
                        width="100%" 
                        height="300" 
                        frameborder="0">
                    </iframe>
                </div>
            </div>
        </div>
        
        <!-- Orçamentos Criados -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Orçamentos Criados</h5>
                </div>
                <div class="card-body p-0">
                    <iframe 
                        src="http://localhost:3000/d-solo/dashboard-id/dashboard-name?orgId=1&panelId=2&from=now-7d&to=now&theme=light"
                        width="100%" 
                        height="300" 
                        frameborder="0">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Valor Total Pedidos -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Valor Total Pedidos</h5>
                </div>
                <div class="card-body p-0">
                    <iframe 
                        src="http://localhost:3000/d-solo/dashboard-id/dashboard-name?orgId=1&panelId=3&from=now-7d&to=now&theme=light"
                        width="100%" 
                        height="300" 
                        frameborder="0">
                    </iframe>
                </div>
            </div>
        </div>
        
        <!-- Taxa de Conversão -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Taxa de Conversão</h5>
                </div>
                <div class="card-body p-0">
                    <iframe 
                        src="http://localhost:3000/d-solo/dashboard-id/dashboard-name?orgId=1&panelId=4&from=now-7d&to=now&theme=light"
                        width="100%" 
                        height="300" 
                        frameborder="0">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
