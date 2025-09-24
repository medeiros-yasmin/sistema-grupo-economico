<?php

namespace App\Http\Livewire;

use App\Models\Colaborador;
use App\Models\Unidade;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Colaboradores extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Campos do formulário
    public $nome;
    public $email;
    public $cpf;
    public $unidade_id;
    public $editId = null;

    protected $rules = [
        'nome' => 'required|min:2|max:255',
        'email' => 'required|email|unique:colaboradores,email',
        'cpf' => 'required|size:11|unique:colaboradores,cpf',
        'unidade_id' => 'required|exists:unidades,id',
    ];

    protected $messages = [
        '*.required' => 'Este campo é obrigatório.',
        'email.email' => 'Digite um e-mail válido.',
        'email.unique' => 'Este e-mail já está cadastrado.',
        'cpf.size' => 'O CPF deve ter exatamente 11 dígitos.',
        'cpf.unique' => 'Este CPF já está cadastrado.',
        'unidade_id.exists' => 'A unidade selecionada é inválida.',
    ];

    public function render()
    {
        $colaboradores = Colaborador::with('unidade')->orderBy('updated_at', 'desc')->paginate(10);
        $unidades = Unidade::with('bandeira')->get();
        return view('livewire.colaboradores', compact('colaboradores', 'unidades'));
    }

    public function salvar()
    {
        // Remove mascara do CPF antes de validar
        $cpfLimpo = preg_replace('/[^0-9]/', '', $this->cpf);
        $this->cpf = $cpfLimpo;

        $this->validate();

        $dados = $this->only(['nome', 'email', 'unidade_id']);
        $dados['cpf'] = $cpfLimpo;

        if ($this->editId) {
            $colaborador = Colaborador::find($this->editId);
            $colaborador->update($dados);
            session()->flash('message', 'Colaborador atualizado com sucesso!');
        } else {
            Colaborador::create($dados);
            session()->flash('message', 'Colaborador criado com sucesso!');
        }

        $this->limparFormulario();
    }

    public function editar($id)
    {
        $colaborador = Colaborador::find($id);
        $this->editId = $colaborador->id;
        $this->nome = $colaborador->nome;
        $this->email = $colaborador->email;
        $this->cpf = $colaborador->cpf;
        $this->unidade_id = $colaborador->unidade_id;
    }

    public function deletar($id)
    {
        Colaborador::find($id)->delete();
        session()->flash('message', 'Colaborador deletado com sucesso!');
    }

    public function limparFormulario()
    {
        $this->reset(['nome', 'email', 'cpf', 'unidade_id', 'editId']);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    // Método para formatar o CPF enquanto o usuário digita
    public function updatedCpf($value)
    {
        // Remove tudo que não é número
        $cpf = preg_replace('/[^0-9]/', '', $value);
        
        // Limita a 11 caracteres
        $cpf = substr($cpf, 0, 11);
        $this->cpf = $cpf;
        
        // Aplica a máscara apenas para exibição (000.000.000-00)
        if (strlen($cpf) === 11) {
            $this->cpf = substr($cpf, 0, 3) . '.' . 
                         substr($cpf, 3, 3) . '.' . 
                         substr($cpf, 6, 3) . '-' . 
                         substr($cpf, 9, 2);
        }
    }
}