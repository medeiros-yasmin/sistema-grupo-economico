<?php

namespace App\Http\Livewire;

use App\Models\GrupoEconomico;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class GruposEconomicos extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Campos do formulário
    public $nome;
    public $editId = null;

    // Regras de validação
    protected $rules = [
        'nome' => 'required|min:3|max:255|unique:grupo_economicos,nome',
    ];

    // Mensagens de validação customizadas
    protected $messages = [
        'nome.required' => 'O campo nome é obrigatório.',
        'nome.min' => 'O nome deve ter pelo menos 3 caracteres.',
        'nome.unique' => 'Este grupo econômico já existe.',
    ];

    // Método para renderizar a view
    public function render()
    {
        // Busca todos os grupos, ordenados pela data de atualização e paginados
        $gruposEconomicos = GrupoEconomico::orderBy('updated_at', 'desc')->paginate(10);
        return view('livewire.grupos-economicos', compact('gruposEconomicos'))
            ->layout('layouts.app');
    }

    // Método para salvar (cria ou atualiza)
    public function salvar()
    {
        // Se estiver editando, ignora a regra 'unique' para o próprio registro
        if ($this->editId) {
            $this->rules['nome'] = 'required|min:3|max:255|unique:grupo_economicos,nome,' . $this->editId;
        }

        // Valida os dados baseado nas regras e mensagens definidas acima
        $validatedData = $this->validate();

        // Usa uma transação para garantir a integridade dos dados
        DB::transaction(function () use ($validatedData) {
            if ($this->editId) {
                // Modo Edição: Atualiza o registro existente
                $grupo = GrupoEconomico::findOrFail($this->editId);
                $grupo->update($validatedData);
                session()->flash('message', 'Grupo econômico atualizado com sucesso!');
            } else {
                // Modo Criação: Cria um novo registro
                GrupoEconomico::create($validatedData);
                session()->flash('message', 'Grupo econômico criado com sucesso!');
            }
        });

        // Limpa os campos do formulário e sai do modo edição
        $this->limparFormulario();
    }

    // Método para editar um grupo
    public function editar($id)
    {
        $grupo = GrupoEconomico::findOrFail($id);
        $this->editId = $id;
        $this->nome = $grupo->nome;
    }

    // Método para deletar um grupo
    public function deletar($id)
    {
        // Encontra o grupo e deleta. Se houver bandeiras vinculadas, o 'onDelete(cascade)' que definimos na migration irá agir.
        $grupo = GrupoEconomico::findOrFail($id);
        $grupo->delete();
        session()->flash('message', 'Grupo econômico deletado com sucesso!');
    }

    // Método para limpar os campos do formulário
    public function limparFormulario()
    {
        $this->reset(['nome', 'editId']);
        // Opcional: Limpa os erros de validação
        $this->resetErrorBag();
        $this->resetValidation();
    }
}