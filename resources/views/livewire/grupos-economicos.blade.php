<div>
    <!-- Título da Página -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestão de Grupos Econômicos</h1>
    </div>

    <!-- Mensagem de Feedback -->
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Formulário -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0">{{ $editId ? 'Editar' : 'Cadastrar Novo' }} Grupo Econômico</h5>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="salvar">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome do Grupo Econômico <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome"
                        wire:model="nome" placeholder="Ex: Grupo ABC, Holding XYZ..." aria-describedby="nomeHelp">
                    @error('nome')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div id="nomeHelp" class="form-text">O nome deve ser único e ter entre 3 e 255 caracteres.</div>
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
            <h5 class="mb-0">Lista de Grupos Econômicos Cadastrados</h5>
        </div>
        <div class="card-body">
            @if($gruposEconomicos->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#ID</th>
                            <th scope="col" class="px-4">Nome</th>
                            <th scope="col" class="px-4">Data de Criação</th>
                            <th scope="col" class="px-4">Última Atualização</th>
                            <th scope="col" class="px-4 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gruposEconomicos as $grupo)
                            <tr>
                                <th scope="row">{{ $grupo->id }}</th>
                                <td class="px-4">{{ $grupo->nome }}</td>
                                <td class="px-4">{{ $grupo->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4">{{ $grupo->updated_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <button wire:click="editar({{ $grupo->id }})" class="btn btn-sm btn-warning" title="Editar">
                                            <i class="bi bi-pencil-fill"></i> Editar
                                        </button>
                                        <button wire:click="deletar({{ $grupo->id }})" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Tem certeza que deseja deletar o grupo \\'{{ $grupo->nome }}\\'? Esta ação não pode ser desfeita.')"
                                            title="Deletar">
                                            <i class="bi bi-trash-fill"></i> Deletar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>Mostrando {{ $gruposEconomicos->firstItem() }} a {{ $gruposEconomicos->lastItem() }} de {{ $gruposEconomicos->total() }} registros</div>
                <div>
                    {{ $gruposEconomicos->links() }}
                </div>
            </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <h5 class="text-muted">Nenhum grupo econômico cadastrado.</h5>
                    <p class="text-muted">Use o formulário acima para realizar o primeiro cadastro.</p>
                </div>
            @endif
        </div>
    </div>
</div>