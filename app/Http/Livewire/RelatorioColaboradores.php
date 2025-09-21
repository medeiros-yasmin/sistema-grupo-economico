<?php

namespace App\Http\Livewire;

use App\Models\Colaborador;
use App\Models\Unidade;
use App\Models\Bandeira;
use App\Models\GrupoEconomico;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Exports\ColaboradoresExport;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layouts.app')]
class RelatorioColaboradores extends Component
{
    use WithPagination;

    // Filtros
    public $search_nome;
    public $search_cpf;
    public $search_email;
    public $unidade_id;
    public $bandeira_id;
    public $grupo_economico_id;

    public function render()
    {
        $query = Colaborador::with(['unidade.bandeira.grupoEconomico']);

        // Aplicar filtros
        if (!empty($this->search_nome)) {
            $query->where('nome', 'like', '%' . $this->search_nome . '%');
        }

        if (!empty($this->search_cpf)) {
            $cpf = preg_replace('/[^0-9]/', '', $this->search_cpf);
            $query->where('cpf', 'like', '%' . $cpf . '%');
        }

        if (!empty($this->search_email)) {
            $query->where('email', 'like', '%' . $this->search_email . '%');
        }

        if (!empty($this->unidade_id)) {
            $query->where('unidade_id', $this->unidade_id);
        }

        if (!empty($this->bandeira_id)) {
            $query->whereHas('unidade', function ($q) {
                $q->where('bandeira_id', $this->bandeira_id);
            });
        }

        if (!empty($this->grupo_economico_id)) {
            $query->whereHas('unidade.bandeira', function ($q) {
                $q->where('grupo_economico_id', $this->grupo_economico_id);
            });
        }

        $colaboradores = $query->orderBy('nome')->paginate(20);

        $unidades = Unidade::all();
        $bandeiras = Bandeira::all();
        $gruposEconomicos = GrupoEconomico::all();

        return view('livewire.relatorio-colaboradores', compact('colaboradores', 'unidades', 'bandeiras', 'gruposEconomicos'));
    }

    public function limparFiltros()
    {
        $this->reset([
            'search_nome', 
            'search_cpf', 
            'search_email', 
            'unidade_id', 
            'bandeira_id', 
            'grupo_economico_id'
        ]);
        $this->resetPage();
    }

    public function exportarCsv()
    {
        $query = Colaborador::with(['unidade.bandeira.grupoEconomico']);

    // Aplica os mesmos filtros da pesquisa
    if (!empty($this->search_nome)) {
        $query->where('nome', 'like', '%' . $this->search_nome . '%');
    }

    if (!empty($this->search_cpf)) {
        $cpf = preg_replace('/[^0-9]/', '', $this->search_cpf);
        $query->where('cpf', 'like', '%' . $cpf . '%');
    }

    if (!empty($this->search_email)) {
        $query->where('email', 'like', '%' . $this->search_email . '%');
    }

    if (!empty($this->unidade_id)) {
        $query->where('unidade_id', $this->unidade_id);
    }

    if (!empty($this->bandeira_id)) {
        $query->whereHas('unidade', function ($q) {
            $q->where('bandeira_id', $this->bandeira_id);
        });
    }

    if (!empty($this->grupo_economico_id)) {
        $query->whereHas('unidade.bandeira', function ($q) {
            $q->where('grupo_economico_id', $this->grupo_economico_id);
        });
    }

    $colaboradores = $query->orderBy('nome')->get();

    $fileName = 'colaboradores_' . date('Y-m-d_H-i-s') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv; charset=utf-8',
        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        'Pragma' => 'no-cache',
        'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        'Expires' => '0'
    ];

    $callback = function() use ($colaboradores) {
        $file = fopen('php://output', 'w');
        
        // BOM para UTF-8 (resolve problemas de acentuação no Excel)
        fwrite($file, "\xEF\xBB\xBF");
        
        // Cabeçalho do CSV
        fputcsv($file, [
            'ID',
            'Nome',
            'CPF',
            'E-mail',
            'Unidade',
            'Bandeira',
            'Grupo Econômico',
            'Data de Criação'
        ], ';');

        // Dados
        foreach ($colaboradores as $colaborador) {
            fputcsv($file, [
                $colaborador->id,
                $colaborador->nome,
                $colaborador->cpf,
                $colaborador->email,
                $colaborador->unidade->nome_fantasia,
                $colaborador->unidade->bandeira->nome,
                $colaborador->unidade->bandeira->grupoEconomico->nome,
                $colaborador->created_at->format('d/m/Y H:i:s')
            ], ';');
        }

        fclose($file);
    };

    return Response::stream($callback, 200, $headers);
    }

    public function exportarExcel()
    {
    // Coleta todos os filtros atuais
    $filters = [
        'search_nome' => $this->search_nome,
        'search_cpf' => $this->search_cpf,
        'search_email' => $this->search_email,
        'unidade_id' => $this->unidade_id,
        'bandeira_id' => $this->bandeira_id,
        'grupo_economico_id' => $this->grupo_economico_id
    ];

    $fileName = 'colaboradores_' . date('Y-m-d_H-i-s') . '.xlsx';

    return Excel::download(new ColaboradoresExport($filters), $fileName);
    }
}