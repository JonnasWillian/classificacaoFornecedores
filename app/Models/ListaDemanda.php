<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListaDemanda extends Model
{
    protected $fillable = ['demandas_id', 'fornecedors_id', 'ultima_posicao'];

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedors_id');
    }

    public function demanda()
    {
        return $this->belongsTo(Demanda::class, 'demandas_id');
    }
}