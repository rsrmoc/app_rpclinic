<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profissional extends Model {
    use SoftDeletes;

    protected $table = 'profissional';
    protected $primaryKey = 'cd_profissional';

    protected $fillable = [
        'nm_profissional',
        'doc',
        'nasc',
        'contato',
        'end',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'sn_ativo',
        'local',
        'nm_contato',
        'crm',
        'sms',
        'whatsapp',
        'email',
        'cd_usuario',
        'up_usuario',
        'tp_assinatura',
        'assinatura',
        'pfx',
        'pfx_validade',
        'pfx_razao',
        'pfx_nome',
        'pfx_cpf',
        'pfx_hash',
        'pfx_emissor',
        'pfx_situacao',
        'pfx_email',
        'rg',
        'sexo',
        'conselho',
        'nm_guerra', 
        'tp_profissional',
        'sn_escala_medica',
        'obs_escala_medica'
    ];
    

    public function procedimentos() {
        return $this->hasMany(ProfissionalProcedimento::class, 'cd_profissional', 'cd_profissional')->with('procedimento', 'convenio');
    }

    public function especialidades() {
        return $this->hasMany(ProfissionalEspecialidade::class, 'cd_profissional', 'cd_profissional');
    }

    public function usuario() {
        return $this->hasOne(Usuario::class, 'cd_profissional', 'cd_profissional')
        ->where('sn_ativo','S');
    }
}
