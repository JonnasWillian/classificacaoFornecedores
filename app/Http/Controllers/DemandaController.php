<?php

namespace App\Http\Controllers;

use App\Models\Demanda;
use App\Models\Fornecedor;
use App\Models\ListaDemanda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class DemandaController extends Controller
{
    public function index()
    {
        return Demanda::all();
    }

    public function demandasAssociadas(Fornecedor $fornecedor)
    {
        return ListaDemanda::where('fornecedors_id', $fornecedor->id)->count();
    }

    public function store(Request $request)
    {
        return Demanda::create($request->all());
    }

    public function show(Demanda $demanda)
    {
        return $demanda->load('fornecedores');
    }

    public function update(Request $request, Demanda $demanda)
    {
        $demanda->update($request->all());
        return $demanda;
    }

    public function destroy(Demanda $demanda)
    {
        $demanda->delete();
        return response()->noContent();
    }

    public function adicionarFornecedor(Demanda $demanda, Fornecedor $fornecedor)
    {
        $demanda->fornecedores()->syncWithoutDetaching([$fornecedor->id]);
        return $demanda->load('fornecedores');
    }

    public function removerFornecedor(Demanda $demanda, Fornecedor $fornecedor)
    {
        $demanda->fornecedores()->detach($fornecedor->id);
        return $demanda->load('fornecedores');
    }

    public function solicitarDemanda(Demanda $demanda, Request $request)
    {
        $cepExecucao = $request->input('cep_execucao');
        $coordenadasExecucao = $this->getCoordenadasFromCEP($cepExecucao);

        $fornecedores = Fornecedor::all();
        
        if ($fornecedores->isEmpty()) {
            return response()->json([
                'message' => 'Não há fornecedores cadastrados no sistema.',
                'demanda' => $demanda
            ], 200);
        }

        $fornecedoresComDistancia = collect();
        
        foreach ($fornecedores as $fornecedor) {
            $coordenadasFornecedor = $this->getCoordenadasFromCEP($fornecedor->cep);
            $distancia = $this->calcularDistancia(
                $coordenadasExecucao['lat'],
                $coordenadasExecucao['lng'],
                $coordenadasFornecedor['lat'],
                $coordenadasFornecedor['lng']
            );

            $usoRecente = ListaDemanda::where('fornecedors_id', $fornecedor->id)
                ->orderBy('updated_at', 'desc')
                ->first();
                
            $indexRoundRobin = $usoRecente ? $usoRecente->ultima_posicao : 0;
            
            $fornecedoresComDistancia->push([
                'fornecedor' => $fornecedor,
                'distancia' => $distancia,
                'round_robin_index' => $indexRoundRobin
            ]);
        }

        $fornecedoresOrdenados = $fornecedoresComDistancia->sortBy([
            ['distancia', 'asc'],
            ['round_robin_index', 'asc']
        ])->values();

        $fornecedorSelecionado = $fornecedoresOrdenados->first();

        $listaDemanda = ListaDemanda::where('demandas_id', $demanda->id)
            ->where('fornecedors_id', $fornecedorSelecionado['fornecedor']->id)
            ->first();
        
        if (!$listaDemanda) {
            ListaDemanda::create([
                'demandas_id' => $demanda->id,
                'fornecedors_id' => $fornecedorSelecionado['fornecedor']->id,
                'ultima_posicao' => 1
            ]);
        } else {
            $listaDemanda->ultima_posicao = $fornecedorSelecionado['round_robin_index'] + 1;
            $listaDemanda->save();
        }

        $listasDemanda = ListaDemanda::where('demandas_id', $demanda->id)->get();
        $fornecedoresAssociados = collect();
        
        foreach ($listasDemanda as $listaDemanda) {
            $fornecedor = Fornecedor::find($listaDemanda->fornecedors_id);
            if ($fornecedor) {
                $fornecedoresAssociados->push([
                    'lista_demanda' => $listaDemanda,
                    'fornecedor' => $fornecedor
                ]);
            }
        }
        
        return response()->json([
            'demanda' => $demanda,
            'cep_execucao' => $cepExecucao,
            'fornecedores_ordenados' => $fornecedoresOrdenados->take(10)->all(),
            'fornecedores_associados' => $fornecedoresAssociados
        ]);
    }

    private function getCoordenadasFromCEP($cep)
    {
        return [
            'lat' => rand(-23, -24) + (rand(0, 999999) / 1000000),
            'lng' => rand(-46, -47) + (rand(0, 999999) / 1000000)
        ];
    }

    private function calcularDistancia($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + 
                cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        return $dist * 60 * 1.853159616;
    }
}