<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class WhastSend extends Model
{
    protected $table = 'whast_send';
    protected $primaryKey = 'cd_whast_send';
 
    protected $fillable = [
        'cd_whast_send ',
        'status', 
        'cd_agendamento',
        'cd_paciente',
        'cd_agendamento_item',
        'id', 
        'url',
        'key_laudo',
        'nr_send', 
        'retorno', 
        'dt_envio', 
        'from_me',  
        'cd_usuario',
        'conteudo',
        'updated_at', 
        'created_at',  
        'tipo'
    ];

    public function agendamento() {
        return $this->hasOne(Agendamento::class, 'cd_agendamento', 'cd_agendamento');
    }
     
    public function tipo() {
        return $this->hasOne(WhastRotina::class, 'tipo', 'tipo');
    }

    public function tab_paciente() {
        return $this->hasOne(Paciente::class, 'cd_paciente', 'cd_paciente');
    }
    
}