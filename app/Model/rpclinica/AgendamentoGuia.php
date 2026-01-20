<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model; 

class AgendamentoGuia extends Model
{ 
    protected $table = 'agendamento_guias';
    protected $primaryKey = 'cd_agendamento_guia';

    protected $fillable = [
        'cd_agendamento_guia',
        'cd_agendamento',
        'dt_solicitacao', 
        'nr_guia', 
        'senha', 
        'tp_guia', 
        'situacao',
        'cd_usuario', 
        'created_at',
        'updated_at', 
    ];
 
    public function agendamento() {
        return $this->hasMany(Agendamento::class, "cd_agendamento", "cd_agendamento");
    }
    public function itens() {
        return $this->hasMany(AgendamentoItens::class, "cd_agendamento_guia", "cd_agendamento_guia");
    }
    public function usuario() {
        return $this->hasOne(Usuario::class, 'cd_usuario', 'cd_usuario');
    }
    public function situacao_guia() {
        return $this->hasOne(GuiaSituacao::class, 'cd_situacao_guia', 'situacao');
    }
}
