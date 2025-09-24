<?php

namespace App\Http\Livewire;

use App\Models\Unidade;
use App\Models\Bandeira;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Unidades extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Campos do formulário
    public $nome_fantasia;
    public $razao_social;
    public $cnpj;
    public $bandeira_id;
    public $editId = null;

    protected $rules = [
        'nome_fantasia' => 'required|min:2|max:255',
        'razao_social' => 'required|min:2|max:255',
        'cnpj' => 'required|digits:14|unique:unidades,cnpj',
        'bandeira_id' => 'required|exists:bandeiras,id',
    ];

    protected $messages = [
        '*.required' => 'Este campo é obrigatório.',
        'cnpj.digits' => 'O CNPJ deve ter exatamente 14 dígitos.',
        'cnpj.unique' => 'Este CNPJ já está cadastrado.',
        'bandeira_id.exists' => 'A bandeira selecionada é inválida.',
    ];

    public function render()
    {
        $unidades = Unidade::with('bandeira')->orderBy('updated_at', 'desc')->paginate(10);
        $bandeiras = Bandeira::all();
        return view('livewire.unidades', compact('unidades', 'bandeiras'));
    }

    public function salvar()
    {

        $cnpjLimpo = preg_replace('/[^0-9]/', '', $this->cnpj);
        $this->cnpj = $cnpjLimpo; // Atualiza o valor para a validação

        $this->validate();

        // Remove mascara do CNPJ antes de salvar
        $dados = $this->only(['nome_fantasia', 'razao_social', 'bandeira_id']);
        $dados['cnpj'] = $cnpjLimpo; // Usa o valor já limpo

        if ($this->editId) {
            $unidade = Unidade::find($this->editId);
            $unidade->update($dados);
            session()->flash('message', 'Unidade atualizada com sucesso!');
        } else {
            Unidade::create($dados);
            session()->flash('message', 'Unidade criada com sucesso!');
    }

        $this->limparFormulario();
    }

    public function editar($id)
    {
        $unidade = Unidade::find($id);
        $this->editId = $unidade->id;
        $this->nome_fantasia = $unidade->nome_fantasia;
        $this->razao_social = $unidade->razao_social;
        $this->cnpj = $unidade->cnpj;
        $this->bandeira_id = $unidade->bandeira_id;
    }

    public function deletar($id)
    {
        Unidade::find($id)->delete();
        session()->flash('message', 'Unidade deletada com sucesso!');
    }

    public function limparFormulario()
    {
        $this->reset(['nome_fantasia', 'razao_social', 'cnpj', 'bandeira_id', 'editId']);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    // Método para formatar o CNPJ enquanto o usuário digita
    public function updatedCnpj($value)
    {
         // Remove tudo que não é número
    $cnpj = preg_replace('/[^0-9]/', '', $value);
    
    // Limita a 14 caracteres
    $cnpj = substr($cnpj, 0, 14);
    $this->cnpj = $cnpj;
    
    // Aplica a máscara apenas para exibição
    if (strlen($cnpj) === 14) {
        $this->cnpj = substr($cnpj, 0, 2) . '.' . 
                     substr($cnpj, 2, 3) . '.' . 
                     substr($cnpj, 5, 3) . '/' . 
                     substr($cnpj, 8, 4) . '-' . 
                     substr($cnpj, 12, 2);
    }
    }
}