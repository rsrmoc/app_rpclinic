<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class AgendamentoImg extends Model
{
    protected $table = 'agendamento_img';
    protected $primaryKey = 'cd_agendamento_img';

    protected $fillable = [
        'cd_agendamento_img',
        'cd_agendamento',
        'cd_paciente',
        'nm_img',
        'tp_file',
        'tp_store',
        'caminho',
        'cd_usuario',
        'created_at',
        'updated_at'
    ];

    public function usuario() {
        return $this->hasOne(Usuario::class, 'cd_usuario', 'cd_usuario');
    }

    public function agendamento() {
        return $this->hasMany(Agendamento::class, "cd_agendamento", "cd_agendamento");
    }
  
}
