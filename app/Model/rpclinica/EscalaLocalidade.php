<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EscalaLocalidade extends Model
{
    use SoftDeletes;

    protected $table = 'escala_localidade';
    protected $primaryKey = 'cd_escala_localidade';

    protected $fillable = [
        'cd_escala_localidade',
        'nm_localidade',
        'sn_ativo',
        'ds_cidade',
        'cd_usuario',
        'cd_uf',
        'cep',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
 
}
