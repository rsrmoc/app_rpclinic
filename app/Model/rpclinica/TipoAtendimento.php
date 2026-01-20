<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoAtendimento extends Model
{
    use SoftDeletes;

    protected $table = 'tipo_atendimento';
    protected $primaryKey = 'cd_tipo_atendimento';
    protected $keyType = "string";

    protected $fillable = [
        'cd_tipo_atendimento',
        'nm_tipo_atendimento',
        'tempo',
        'sn_ativo',
        'cor',
        'sn_sessao',
        'sn_cirurgia',
        'sn_retorno',
        'sn_consulta',
        'sn_conta',
        'sn_exame',
        'sn_telemedicina',
        'qtde_sessao',
        'cd_usuario',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
