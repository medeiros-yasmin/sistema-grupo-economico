<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestão de Colaboradores</h1>
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
            <h5 class="mb-0">{{ $editId ? 'Editar' : 'Cadastrar Novo' }} Colaborador</h5>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="salvar">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nome" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nome') is-invalid @enderror" 
                               id="nome" wire:model="nome">
                        @error('nome') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">E-mail <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" wire:model="email">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="cpf" class="form-label">CPF <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('cpf') is-invalid @enderror" 
                               id="cpf" wire:model="cpf" placeholder="000.000.000-00"
                               x-mask="999.999.999-99">
                        @error('cpf') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="unidade_id" class="form-label">Unidade <span class="text-danger">*</span></label>
                        <select class="form-select @error('unidade_id') is-invalid @enderror" 
                                id="unidade_id" wire:model="unidade_id">
                            <option value="">Selecione uma Unidade...</option>
                            @foreach($unidades as $unidade)
                                <option value="{{ $unidade->id }}">
                                    {{ $unidade->nome_fantasia }} - 
                                    {{ $unidade->bandeira->nome }} - 
                                    {{ $unidade->bandeira->grupoEconomico->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('unidade_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary me-md-2">
                        {{ $editId ? 'Atualizar' : 'Salvar' }}
                    </button>
                    @if($editId)
                        <button type="button" wire:click="limparFormulario" class="btn btn-secondary">
                            Cancelar Edição
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de Listagem -->
    <div class="card shadow">
        <div class="card-header bg-light py-3">
            <h5 class="mb-0">Lista de Colaboradores Cadastrados</h5>
        </div>
        <div class="card-body">
            @if($colaboradores->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>#ID</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>CPF</th>
                            <th>Unidade</th>
                            <th>Bandeira</th>
                            <th>Grupo Econ.</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($colaboradores as $colaborador)
                            <tr>
                                <td>{{ $colaborador->id }}</td>
                                <td>{{ $colaborador->nome }}</td>
                                <td>{{ $colaborador->email }}</td>
                                <td>{{ $colaborador->cpf }}</td>
                                <td>{{ $colaborador->unidade->nome_fantasia }}</td>
                                <td>{{ $colaborador->unidade->bandeira->nome }}</td>
                                <td>{{ $colaborador->unidade->bandeira->grupoEconomico->nome }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button wire:click="editar({{ $colaborador->id }})" class="btn btn-sm btn-warning">
                                            Editar
                                        </button>
                                        <button wire:click="deletar({{ $colaborador->id }})" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Tem certeza?')">
                                            Deletar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $colaboradores->links('pagination::bootstrap-5') }}
            @else
                <div class="text-center py-5">
                    <h5 class="text-muted">Nenhum colaborador cadastrado.</h5>
                    <p class="text-muted">Cadastre uma Unidade primeiro para vincular colaboradores.</p>
                </div>
            @endif
        </div>
    </div>
</div>