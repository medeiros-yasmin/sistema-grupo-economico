@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0"><i class="bi bi-clock-history"></i> Log de Auditoria</h4>
                </div>

                <div class="card-body">
                    <!-- Filtros -->
                    <form method="GET" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Tabela:</label>
                                <select name="table_name" class="form-select">
                                    <option value="">Todas</option>
                                    @foreach($tables as $key => $value)
                                        <option value="{{ $key }}" {{ request('table_name') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Ação:</label>
                                <select name="action" class="form-select">
                                    <option value="">Todas</option>
                                    <option value="CREATE" {{ request('action') == 'CREATE' ? 'selected' : '' }}>Criação</option>
                                    <option value="UPDATE" {{ request('action') == 'UPDATE' ? 'selected' : '' }}>Atualização</option>
                                    <option value="DELETE" {{ request('action') == 'DELETE' ? 'selected' : '' }}>Exclusão</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Data Início:</label>
                                <input type="date" name="date_start" class="form-control" value="{{ request('date_start') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Data Fim:</label>
                                <input type="date" name="date_end" class="form-control" value="{{ request('date_end') }}">
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                                <a href="{{ route('audit.index') }}" class="btn btn-secondary">Limpar</a>
                            </div>
                        </div>
                    </form>

                    <!-- Tabela de Logs -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Data/Hora</th>
                                    <th>Usuário</th>
                                    <th>Ação</th>
                                    <th>Tabela</th>
                                    <th>Registro ID</th>
                                    <th>Detalhes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($auditLogs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ $log->user->name ?? 'Sistema' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $log->action == 'CREATE' ? 'success' : ($log->action == 'UPDATE' ? 'warning' : 'danger') }}">
                                            {{ $log->action_description }}
                                        </span>
                                    </td>
                                    <td>{{ $tables[$log->table_name] ?? $log->table_name }}</td>
                                    <td>{{ $log->record_id }}</td>
                                    <td>
                                        <a href="{{ route('audit.show', $log) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Ver
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Nenhum registro de auditoria encontrado.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="d-flex justify-content-center">
                        {{ $auditLogs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection