<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Relatório de Colaboradores</h1>
        <div class="btn-group">
            <button class="btn btn-success" wire:click="exportarExcel">
                <i class="bi bi-file-earmark-excel"></i> Exportar Excel
            </button>
            <button class="btn btn-secondary" wire:click="exportarCsv">
                <i class="bi bi-file-earmark-text"></i> Exportar CSV
            </button>
        </div>
    </div>

    <!-- Card de Filtros -->
    <div class="card shadow mb-4">
        <div class="card-header bg-light py-3">
            <h5 class="mb-0">Filtros</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nome</label>
                    <input type="text" class="form-control" wire:model.lazy="search_nome" 
                           placeholder="Buscar por nome...">
                </div>

                <div class="col-md-4">
                    <label class="form-label">CPF</label>
                    <input type="text" class="form-control" wire:model.lazy="search_cpf" 
                           placeholder="000.000.000-00" x-mask="999.999.999-99">
                </div>

                <div class="col-md-4">
                    <label class="form-label">E-mail</label>
                    <input type="text" class="form-control" wire:model.lazy="search_email" 
                           placeholder="Buscar por e-mail...">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Unidade</label>
                    <select class="form-select" wire:model="unidade_id">
                        <option value="">Todas as Unidades</option>
                        @foreach($unidades as $unidade)
                            <option value="{{ $unidade->id }}">
                                {{ $unidade->nome_fantasia }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Bandeira</label>
                    <select class="form-select" wire:model="bandeira_id">
                        <option value="">Todas as Bandeiras</option>
                        @foreach($bandeiras as $bandeira)
                            <option value="{{ $bandeira->id }}">
                                {{ $bandeira->nome }} ({{ $bandeira->grupoEconomico->nome }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Grupo Econômico</label>
                    <select class="form-select" wire:model="grupo_economico_id">
                        <option value="">Todos os Grupos</option>
                        @foreach($gruposEconomicos as $grupo)
                            <option value="{{ $grupo->id }}">{{ $grupo->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12 text-end">
                    <button class="btn btn-outline-secondary" wire:click="limparFiltros">
                        <i class="bi bi-x-circle"></i> Limpar Filtros
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Card de Resultados -->
    <div class="card shadow">
        <div class="card-header bg-light py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Resultados</h5>
                <span class="badge bg-primary">
                    {{ $colaboradores->total() }} registro(s) encontrado(s)
                </span>
            </div>
        </div>
        <div class="card-body">
            @if($colaboradores->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>E-mail</th>
                            <th>Unidade</th>
                            <th>Bandeira</th>
                            <th>Grupo Econômico</th>
                            <th>Data Cadastro</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($colaboradores as $colaborador)
                            <tr>
                                <td>{{ $colaborador->id }}</td>
                                <td>{{ $colaborador->nome }}</td>
                                <td>{{ $colaborador->cpf }}</td>
                                <td>{{ $colaborador->email }}</td>
                                <td>{{ $colaborador->unidade->nome_fantasia }}</td>
                                <td>{{ $colaborador->unidade->bandeira->nome }}</td>
                                <td>{{ $colaborador->unidade->bandeira->grupoEconomico->nome }}</td>
                                <td>{{ $colaborador->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>Mostrando {{ $colaboradores->firstItem() }} a {{ $colaboradores->lastItem() }} de {{ $colaboradores->total() }} registros</div>
                <div>
                    {{ $colaboradores->links() }}
                </div>
            </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-search display-1 text-muted"></i>
                    <h5 class="text-muted">Nenhum colaborador encontrado</h5>
                    <p class="text-muted">Tente ajustar os filtros de pesquisa.</p>
                </div>
            @endif
        </div>
    </div>
</div>