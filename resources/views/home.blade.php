@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    Você está logado!
                    
                    <div class="mt-4">
                        <h5>Menu do Sistema:</h5>
                        <div class="d-grid gap-2">
                            <a href="{{ route('grupos-economicos') }}" class="btn btn-primary">Gestão de Grupos Econômicos</a>
                            <a href="{{ route('bandeiras') }}" class="btn btn-primary">Gestão de Bandeiras</a>
                            <a href="{{ route('unidades') }}" class="btn btn-primary">Gestão de Unidades</a>
                            <a href="{{ route('colaboradores') }}" class="btn btn-primary">Gestão de Colaboradores</a>
                            <a href="{{ route('relatorio-colaboradores') }}" class="btn btn-info">Relatório de Colaboradores</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection