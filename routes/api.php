<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\DemandaController;

Route::apiResource('fornecedores', FornecedorController::class);
Route::apiResource('demandas', DemandaController::class);

Route::get('demandasAssociadasFornecedor/{id}', [DemandaController::class, 'demandasAssociadas']);

Route::post('demandas/{demanda}/fornecedores/{fornecedor}', [DemandaController::class, 'adicionarFornecedor']);
Route::delete('demandas/{demanda}/fornecedores/{fornecedor}', [DemandaController::class, 'removerFornecedor']);
Route::post('demandas/{demanda}/solicitar', [DemandaController::class, 'solicitarDemanda']);