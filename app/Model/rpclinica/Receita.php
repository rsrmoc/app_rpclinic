<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class Receita extends Model
{
    protected $fillable = [
        'id_doctor', 'formulario_id', 'medicaments', 'content', 'pdf'
    ];
}
