@extends('layouts.app')

@section('title', 'Exportar Auditoria')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-file-export"></i> Exportar Relatório de Auditoria
                    </h5>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('export.audit') }}" method="POST" id="exportForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="table_name" class="form-label">Tabela</label>
                                    <select name="table_name" id="table_name" class="form-select">
                                        <option value="">Todas as Tabelas</option>
                                        @foreach($tables as $key => $value)
                                            <option value="{{ $key }}" 
                                                {{ old('table_name') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="action" class="form-label">Tipo de Ação</label>
                                    <select name="action" id="action" class="form-select">
                                        <option value="">Todas as Ações</option>
                                        @foreach($actions as $key => $value)
                                            <option value="{{ $key }}"
                                                {{ old('action') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Data Início</label>
                                    <input type="date" name="start_date" id="start_date" 
                                           class="form-control" value="{{ old('start_date') }}">
                                    @error('start_date')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">Data Fim</label>
                                    <input type="date" name="end_date" id="end_date" 
                                           class="form-control" value="{{ old('end_date') }}">
                                    @error('end_date')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Tipo de Exportação</label>
                                    <div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="export_type" 
                                                   id="immediate" value="immediate" 
                                                   {{ old('export_type', 'immediate') == 'immediate' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="immediate">
                                                <i class="fas fa-bolt text-warning"></i> Exportação Imediata
                                                <small class="text-muted d-block">(Recomendado para até 1.000 registros)</small>
                                            </label>
                                        </div>
                                        
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="export_type" 
                                                   id="queue" value="queue"
                                                   {{ old('export_type') == 'queue' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="queue">
                                                <i class="fas fa-tasks text-info"></i> Exportação em Background
                                                <small class="text-muted d-block">(Para grandes volumes de dados)</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </a>
                            <button type="submit" class="btn btn-primary" id="exportButton">
                                <i class="fas fa-file-excel"></i> Gerar Relatório Excel
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card-footer bg-light">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> 
                        O relatório incluirá todos os registros de auditoria conforme os filtros aplicados.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('exportForm').addEventListener('submit', function() {
        const button = document.getElementById('exportButton');
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processando...';
    });

    // Validação de datas
    document.getElementById('start_date').addEventListener('change', function() {
        const endDate = document.getElementById('end_date');
        if (this.value && endDate.value && this.value > endDate.value) {
            endDate.value = '';
        }
    });
</script>
@endsection