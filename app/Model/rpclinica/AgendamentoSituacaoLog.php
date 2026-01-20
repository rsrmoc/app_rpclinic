<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model; 

class AgendamentoSituacaoLog extends Model
{ 
    protected $table = 'agendamento_situacao_log';
    protected $primaryKey = 'cd_agendamento_situacao_log';

    protected $fillable = [
        'cd_agendamento_situacao_log',
        'cd_agendamento',
        'situacao',  
        'cd_usuario', 
        'created_at',
        'updated_at', 
    ];
 
 
    public function tab_situacao() {
        return $this->hasOne(AgendamentoSituacao::class, "cd_situacao", "situacao");
    }
    public function tab_agendamento() {
        return $this->hasOne(Agendamento::class, 'cd_agendamento', 'cd_agendamento');
    }
    public function tab_usuario() {
        return $this->hasOne(Usuario::class, 'cd_usuario', 'cd_usuario');
    }
}
