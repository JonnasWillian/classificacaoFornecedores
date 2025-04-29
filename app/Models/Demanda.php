<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demanda extends Model
{
    protected $fillable = ['descricao'];

    public function listas()
    {
        return $this->hasMany(ListaDemanda::class, 'demandas_id');
    }

    public function listasDemanda()
    {
        return $this->hasMany(ListaDemanda::class, 'demandas_id');
    }

    public function fornecedores()
    {
        return $this->hasManyThrough(
            Fornecedor::class,
            ListaDemanda::class,
            'demandas_id', // Chave na tabela intermediária
            'id',  // Chave na tabela de fornecedores
            'id',  // Chave local na tabela de demandas
            'fornecedors_id' // Chave na tabela intermediária
        );
    }
}
