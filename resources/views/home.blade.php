@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Dashboard - Sistema Grupo Econômico</h4>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <h3>Bem-vindo, {{ Auth::user()->name }}! 👋</h3>
                        <p class="text-muted">O que você gostaria de gerenciar hoje?</p>
                    </div>

                    <div class="row">
                        <!-- Grupo Econômico -->
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="bi bi-building display-4 text-primary mb-3"></i>
                                    <h5 class="card-title">Grupos Econômicos</h5>
                                    <p class="card-text">Gerencie os grupos econômicos do sistema</p>
                                    <a href="{{ route('grupos-economicos') }}" class="btn btn-primary">Acessar</a>
                                </div>
                            </div>
                        </div>

                        <!-- Bandeiras -->
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="bi bi-flag display-4 text-success mb-3"></i>
                                    <h5 class="card-title">Bandeiras</h5>
                                    <p class="card-text">Gerencie as bandeiras de cada grupo</p>
                                    <a href="{{ route('bandeiras') }}" class="btn btn-success">Acessar</a>
                                </div>
                            </div>
                        </div>

                        <!-- Unidades -->
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="bi bi-shop display-4 text-warning mb-3"></i>
                                    <h5 class="card-title">Unidades</h5>
                                    <p class="card-text">Gerencie as unidades de cada bandeira</p>
                                    <a href="{{ route('unidades') }}" class="btn btn-warning">Acessar</a>
                                </div>
                            </div>
                        </div>

                        <!-- Colaboradores -->
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="bi bi-people display-4 text-info mb-3"></i>
                                    <h5 class="card-title">Colaboradores</h5>
                                    <p class="card-text">Gerencie os colaboradores do sistema</p>
                                    <a href="{{ route('colaboradores') }}" class="btn btn-info">Acessar</a>
                                </div>
                            </div>
                        </div>

                        <!-- Relatórios -->
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="bi bi-graph-up display-4 text-danger mb-3"></i>
                                    <h5 class="card-title">Relatórios</h5>
                                    <p class="card-text">Relatório completo de colaboradores</p>
                                    <a href="{{ route('relatorio-colaboradores') }}" class="btn btn-danger">Acessar</a>
                                </div>
                            </div>
                        </div>

                        <!-- Sair -->
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="bi bi-box-arrow-right display-4 text-secondary mb-3"></i>
                                    <h5 class="card-title">Sair</h5>
                                    <p class="card-text">Encerrar sua sessão no sistema</p>
                                    <a href="{{ route('logout') }}" class="btn btn-secondary"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Sair
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection