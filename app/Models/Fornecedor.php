<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    protected $fillable = ['razao_social', 'cep'];

    public function demandas()
    {
        return $this->belongsToMany(Demanda::class, 'demanda_fornecedor')
                    ->withPivot('round_robin_index')
                    ->withTimestamps();
    }

    public function listas()
    {
        return $this->hasMany(ListaDemanda::class, 'fornecedors_id');
    }
}
