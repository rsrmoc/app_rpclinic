<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class AgendamentoAnotacao extends Model
{
    protected $table = 'agendamento_anotacao';
    protected $primaryKey = 'cd_anotacao';

    protected $fillable = [
        'cd_anotacao',
        'cd_agendamento',
        'cd_paciente',
        'conteudo',
        'caminho_arquivo',
        'extension',
        'cd_usuario',
        'created_at',
        'updated_at',
        'tp_file',
        'tp_store'
    ];
 
    public function tab_agendamento() {
        return $this->hasOne(Agendamento::class, 'cd_agendamento', 'cd_agendamento');
    }

    public function tab_paciente() {
        return $this->hasOne(Paciente::class, 'cd_paciente', 'cd_paciente');
    }

    public function tab_usuario() {
        return $this->hasOne(Usuario::class, 'cd_usuario', 'cd_usuario');
    }
}
