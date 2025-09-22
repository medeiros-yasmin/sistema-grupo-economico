@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0"><i class="bi bi-clock-history"></i> Detalhes da Auditoria</h4>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>Data/Hora:</strong> {{ $auditLog->created_at->format('d/m/Y H:i:s') }}<br>
                            <strong>Usuário:</strong> {{ $auditLog->user->name ?? 'Sistema' }}<br>
                            <strong>IP:</strong> {{ $auditLog->ip_address }}
                        </div>
                        <div class="col-md-6">
                            <strong>Ação:</strong> 
                            <span class="badge bg-{{ $auditLog->action == 'CREATE' ? 'success' : ($auditLog->action == 'UPDATE' ? 'warning' : 'danger') }}">
                                {{ $auditLog->action_description }}
                            </span><br>
                            <strong>Tabela:</strong> {{ $auditLog->table_name }}<br>
                            <strong>Registro ID:</strong> {{ $auditLog->record_id }}
                        </div>
                    </div>

                    @if($auditLog->action == 'UPDATE')
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Valores Antigos:</h5>
                            <div class="card bg-light">
                                <div class="card-body">
                                    @if($auditLog->old_values)
                                        <pre class="mb-0">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT) }}</pre>
                                    @else
                                        <p class="text-muted mb-0">Nenhum valor anterior</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Valores Novos:</h5>
                            <div class="card bg-light">
                                <div class="card-body">
                                    @if($auditLog->new_values)
                                        <pre class="mb-0">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT) }}</pre>
                                    @else
                                        <p class="text-muted mb-0">Nenhum valor novo</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @elseif($auditLog->action == 'CREATE')
                    <h5>Valores Criados:</h5>
                    <div class="card bg-light">
                        <div class="card-body">
                            <pre class="mb-0">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                    @elseif($auditLog->action == 'DELETE')
                    <h5>Valores Excluídos:</h5>
                    <div class="card bg-light">
                        <div class="card-body">
                            <pre class="mb-0">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('audit.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection