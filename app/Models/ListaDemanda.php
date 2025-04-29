<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListaDemanda extends Model
{
    protected $fillable = ['demandas_id', 'fornecedors_id', 'ultima_posicao'];
}
