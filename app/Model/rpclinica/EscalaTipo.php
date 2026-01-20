<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EscalaTipo extends Model
{
    use SoftDeletes;

    protected $table = 'escala_tipo';
    protected $primaryKey = 'cd_escala_tipo';

    protected $fillable = [
        'cd_escala_tipo',
        'nm_tipo_escala',
        'sn_ativo', 
        'cd_usuario', 
        'created_at',
        'updated_at',
        'deleted_at'
    ];
 
}
