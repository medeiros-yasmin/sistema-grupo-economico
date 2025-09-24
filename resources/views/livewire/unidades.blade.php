<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="align-center">Gestão de Unidades</h1>
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
            <h5 class="mb-0">{{ $editId ? 'Editar' : 'Cadastrar Nova' }} Unidade</h5>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="salvar">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nome_fantasia" class="form-label">Nome Fantasia <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nome_fantasia') is-invalid @enderror" 
                               id="nome_fantasia" wire:model="nome_fantasia">
                        @error('nome_fantasia') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="razao_social" class="form-label">Razão Social <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('razao_social') is-invalid @enderror" 
                               id="razao_social" wire:model="razao_social">
                        @error('razao_social') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="cnpj" class="form-label">CNPJ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('cnpj') is-invalid @enderror" 
                               id="cnpj" wire:model="cnpj" placeholder="00.000.000/0000-00"
                               x-mask="99.999.999/9999-99">
                        @error('cnpj') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="bandeira_id" class="form-label">Bandeira <span class="text-danger">*</span></label>
                        <select class="form-select @error('bandeira_id') is-invalid @enderror" 
                                id="bandeira_id" wire:model="bandeira_id">
                            <option value="">Selecione uma Bandeira...</option>
                            @foreach($bandeiras as $bandeira)
                                <option value="{{ $bandeira->id }}">{{ $bandeira->nome }} - {{ $bandeira->grupoEconomico->nome }}</option>
                            @endforeach
                        </select>
                        @error('bandeira_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
            <h5 class="mb-0">Lista de Unidades Cadastradas</h5>
        </div>
        <div class="card-body">
            @if($unidades->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>#ID</th>
                            <th class="px-2 text-center">Nome Fantasia</th>
                            <th class="px-2 text-center">Razão Social</th>
                            <th class="px-2 text-center">CNPJ</th>
                            <th class="px-2 text-center">Bandeira</th>
                            <th class="px-2 text-center">Grupo Econ.</th>
                            <th class="px-2 text-center">Criação</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($unidades as $unidade)
                            <tr>
                                <td>{{ $unidade->id }}</td>
                                <td class="px-2 text-center">{{ $unidade->nome_fantasia }}</td>
                                <td class="px-2 text-center">{{ $unidade->razao_social }}</td>
                                <td class="px-2 text-center">{{ $unidade->cnpj }}</td>
                                <td class="px-2 text-center">{{ $unidade->bandeira->nome }}</td>
                                <td class="px-2 text-center">{{ $unidade->bandeira->grupoEconomico->nome }}</td>
                                <td class="px-2 text-center">{{ $unidade->created_at->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button wire:click="editar({{ $unidade->id }})" class="btn btn-sm btn-warning">
                                            Editar
                                        </button>
                                        <button wire:click="deletar({{ $unidade->id }})" class="btn btn-sm btn-danger"
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
            {{ $unidades->links('pagination::bootstrap-5') }}
            @else
                <div class="text-center py-5">
                    <h5 class="text-muted">Nenhuma unidade cadastrada.</h5>
                    <p class="text-muted">Cadastre uma Bandeira primeiro para vincular unidades.</p>
                </div>
            @endif
        </div>
    </div>
</div>