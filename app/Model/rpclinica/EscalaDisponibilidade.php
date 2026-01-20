<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EscalaDisponibilidade extends Model
{
    use SoftDeletes;

    protected $table = 'escala_disponibilidade';
    protected $primaryKey = 'cd_disponibilidade';

    protected $fillable = [
        'cd_disponibilidade',
        'cd_profissional',
        'dt_disponibilidade', 
        'cd_usuario', 
        'created_at',
        'updated_at',
        'deleted_at'
    ];
 
}
