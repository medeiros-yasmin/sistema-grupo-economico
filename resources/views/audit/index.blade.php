@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Registros de Auditoria</h1>
        <a href="{{ route('export.audit.form') }}" class="btn btn-success" id="exportBtn">
            <i class="bi bi-file-earmark-excel"></i> Exportar Excel
        </a>
    </div>

    <!-- Card de Filtros -->
    <div class="card shadow mb-4">
        <div class="card-header bg-light py-3">
            <h5 class="mb-0">Filtros</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('audit.index') }}" method="GET" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tabela</label>
                        <select name="table_name" class="form-select">
                            <option value="">Todas as Tabelas</option>
                            <option value="grupo_economicos" {{ request('table_name') == 'grupo_economicos' ? 'selected' : '' }}>Grupos Econômicos</option>
                            <option value="bandeiras" {{ request('table_name') == 'bandeiras' ? 'selected' : '' }}>Bandeiras</option>
                            <option value="unidades" {{ request('table_name') == 'unidades' ? 'selected' : '' }}>Unidades</option>
                            <option value="colaboradores" {{ request('table_name') == 'colaboradores' ? 'selected' : '' }}>Colaboradores</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Ação</label>
                        <select name="action" class="form-select">
                            <option value="">Todas as Ações</option>
                            <option value="CREATE" {{ request('action') == 'CREATE' ? 'selected' : '' }}>Criação</option>
                            <option value="UPDATE" {{ request('action') == 'UPDATE' ? 'selected' : '' }}>Atualização</option>
                            <option value="DELETE" {{ request('action') == 'DELETE' ? 'selected' : '' }}>Exclusão</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Usuário</label>
                        <select name="user_id" class="form-select">
                            <option value="">Todos os Usuários</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Data Início</label>
                        <input type="date" name="start_date" class="form-control" 
                               value="{{ request('start_date') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Data Fim</label>
                        <input type="date" name="end_date" class="form-control" 
                               value="{{ request('end_date') }}">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 text-end">
                        <a href="{{ route('audit.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Limpar Filtros
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Aplicar Filtros
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Card de Resultados -->
    <div class="card shadow">
        <div class="card-header bg-light py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Registros de Auditoria</h5>
                <span class="badge bg-primary">
                    {{ $auditLogs->total() }} registro(s) encontrado(s)
                </span>
            </div>
        </div>
        <div class="card-body">
            @if($auditLogs->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Data/Hora</th>
                            <th>Ação</th>
                            <th>Tabela</th>
                            <th>Registro ID</th>
                            <th>Usuário</th>
                            <th>IP</th>
                            <th>Detalhes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($auditLogs as $log)
                            <tr>
                                <td>{{ $log->id }}</td>
                                <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>
                                    <span class="badge 
                                        @if($log->action == 'CREATE') bg-success
                                        @elseif($log->action == 'UPDATE') bg-warning
                                        @else bg-danger @endif">
                                        {{ $log->action_description }}
                                    </span>
                                </td>
                                <td>{{ $log->table_name }}</td>
                                <td>{{ $log->record_id }}</td>
                                <td>{{ $log->user ? $log->user->name : 'Sistema' }}</td>
                                <td>{{ $log->ip_address }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#detailsModal{{ $log->id }}">
                                        <i class="bi bi-eye"></i> Ver
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal para Detalhes -->
<div class="modal fade" id="detailsModal{{ $log->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalhes da Auditoria #{{ $log->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Data/Hora:</strong> {{ $log->created_at->format('d/m/Y H:i:s') }}<br>
                        <strong>Ação:</strong> {{ $log->action_description }}<br>
                        <strong>Tabela:</strong> {{ $log->table_name }}<br>
                        <strong>Registro ID:</strong> {{ $log->record_id }}
                    </div>
                    <div class="col-md-6">
                        <strong>Usuário:</strong> {{ $log->user ? $log->user->name : 'Sistema' }}<br>
                        <strong>IP:</strong> {{ $log->ip_address }}<br>
                        <strong>User Agent:</strong> <small>{{ $log->user_agent }}</small>
                    </div>
                </div>
                <hr>
                <div class="row">
                    @php
                        // Decodificar os valores JSON para array
                        $oldValues = $log->old_values ? json_decode($log->old_values, true) : null;
                        $newValues = $log->new_values ? json_decode($log->new_values, true) : null;
                    @endphp
                    
                    @if($oldValues && is_array($oldValues))
                    <div class="col-md-6">
                        <h6>Valores Antigos:</h6>
                        <div class="bg-light p-2 small">
                            @foreach($oldValues as $key => $value)
                                <div><strong>{{ $key }}:</strong> 
                                    @if(is_array($value) || is_object($value))
                                        {{ json_encode($value) }}
                                    @else
                                        {{ $value ?? 'Nulo' }}
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    @if($newValues && is_array($newValues))
                    <div class="col-md-6">
                        <h6>Valores Novos:</h6>
                        <div class="bg-light p-2 small">
                            @foreach($newValues as $key => $value)
                                <div><strong>{{ $key }}:</strong> 
                                    @if(is_array($value) || is_object($value))
                                        {{ json_encode($value) }}
                                    @else
                                        {{ $value ?? 'Nulo' }}
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>Mostrando {{ $auditLogs->firstItem() }} a {{ $auditLogs->lastItem() }} de {{ $auditLogs->total() }} registros</div>
                <div>
                    {{ $auditLogs->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-search display-1 text-muted"></i>
                    <h5 class="text-muted">Nenhum registro de auditoria encontrado</h5>
                    <p class="text-muted">Tente ajustar os filtros de pesquisa.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validação de datas
    const startDate = document.querySelector('input[name="start_date"]');
    const endDate = document.querySelector('input[name="end_date"]');
    
    if (startDate && endDate) {
        startDate.addEventListener('change', function() {
            if (this.value && endDate.value && this.value > endDate.value) {
                endDate.value = this.value;
            }
        });
        
        endDate.addEventListener('change', function() {
            if (this.value && startDate.value && this.value < startDate.value) {
                startDate.value = this.value;
            }
        });
    }

    // Loading no botão de exportação
    const exportBtn = document.getElementById('exportBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function(e) {
            const btn = this;
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Preparando...';
            btn.disabled = true;
            
            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }, 3000);
        });
    }
});
</script>
@endsection