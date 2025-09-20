<?php

namespace App\Http\Livewire;

use App\Models\Bandeira;
use App\Models\GrupoEconomico;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Bandeiras extends Component
{
    use WithPagination;

    // Campos do formulário
    public $nome;
    public $grupo_economico_id;
    public $editId = null;

    protected $rules = [
        'nome' => 'required|min:2|max:255',
        'grupo_economico_id' => 'required|exists:grupo_economicos,id',
    ];

    protected $messages = [
        'nome.required' => 'O campo nome é obrigatório.',
        'grupo_economico_id.required' => 'A seleção de um Grupo Econômico é obrigatória.',
        'grupo_economico_id.exists' => 'O Grupo Econômico selecionado é inválido.',
    ];

    public function render()
    {
        $bandeiras = Bandeira::with('grupoEconomico')->orderBy('updated_at', 'desc')->paginate(10);
        $gruposEconomicos = GrupoEconomico::all(); // Para popular o dropdown
        return view('livewire.bandeiras', compact('bandeiras', 'gruposEconomicos'));
    }

    public function salvar()
    {
        $this->validate();

        if ($this->editId) {
            $bandeira = Bandeira::find($this->editId);
            $bandeira->update($this->only(['nome', 'grupo_economico_id']));
            session()->flash('message', 'Bandeira atualizada com sucesso!');
        } else {
            Bandeira::create($this->only(['nome', 'grupo_economico_id']));
            session()->flash('message', 'Bandeira criada com sucesso!');
        }

        $this->limparFormulario();
    }

    public function editar($id)
    {
        $bandeira = Bandeira::find($id);
        $this->editId = $bandeira->id;
        $this->nome = $bandeira->nome;
        $this->grupo_economico_id = $bandeira->grupo_economico_id;
    }

    public function deletar($id)
    {
        Bandeira::find($id)->delete();
        session()->flash('message', 'Bandeira deletada com sucesso!');
    }

    public function limparFormulario()
    {
        $this->reset(['nome', 'grupo_economico_id', 'editId']);
        $this->resetErrorBag();
        $this->resetValidation();
    }
}