<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestão de Bandeiras</h1>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Formulário -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0">{{ $editId ? 'Editar' : 'Cadastrar Nova' }} Bandeira</h5>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="salvar">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nome" class="form-label">Nome da Bandeira <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nome') is-invalid @enderror" 
                               id="nome" wire:model="nome" placeholder="Ex: Hipermercado, Farmácia, Posto...">
                        @error('nome') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="grupo_economico_id" class="form-label">Grupo Econômico <span class="text-danger">*</span></label>
                        <select class="form-select @error('grupo_economico_id') is-invalid @enderror" 
                                id="grupo_economico_id" wire:model="grupo_economico_id">
                            <option value="">Selecione um Grupo Econômico...</option>
                            @foreach($gruposEconomicos as $grupo)
                                <option value="{{ $grupo->id }}">{{ $grupo->nome }}</option>
                            @endforeach
                        </select>
                        @error('grupo_economico_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary me-md-2">
                        <i class="bi bi-check-circle-fill me-1"></i> {{ $editId ? 'Atualizar' : 'Salvar' }}
                    </button>
                    @if($editId)
                        <button type="button" wire:click="limparFormulario" class="btn btn-secondary">
                            <i class="bi bi-x-circle-fill me-1"></i> Cancelar Edição
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de Listagem -->
    <div class="card shadow">
        <div class="card-header bg-light py-3">
            <h5 class="mb-0">Lista de Bandeiras Cadastradas</h5>
        </div>
        <div class="card-body">
            @if($bandeiras->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#ID</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Grupo Econômico</th>
                            <th scope="col">Data de Criação</th>
                            <th scope="col">Última Atualização</th>
                            <th scope="col" class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bandeiras as $bandeira)
                            <tr>
                                <th scope="row">{{ $bandeira->id }}</th>
                                <td>{{ $bandeira->nome }}</td>
                                <td>{{ $bandeira->grupoEconomico->nome }}</td> <!-- Acessa o relacionamento -->
                                <td>{{ $bandeira->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $bandeira->updated_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <button wire:click="editar({{ $bandeira->id }})" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil-fill"></i> Editar
                                        </button>
                                        <button wire:click="deletar({{ $bandeira->id }})" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Tem certeza?')">
                                            <i class="bi bi-trash-fill"></i> Deletar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $bandeiras->links('pagination::bootstrap-5') }}
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <h5 class="text-muted">Nenhuma bandeira cadastrada.</h5>
                    <p class="text-muted">Cadastre um Grupo Econômico primeiro para vincular bandeiras.</p>
                </div>
            @endif
        </div>
    </div>
</div>