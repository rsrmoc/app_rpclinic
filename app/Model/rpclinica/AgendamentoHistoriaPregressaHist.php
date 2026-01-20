<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model; 

class AgendamentoHistoriaPregressaHist extends Model
{ 
    protected $table = 'agendamento_historia_pregressa_hist';
    protected $primaryKey = 'cd_hist_pregressa_hist';

    protected $fillable = [
        'cd_hist_pregressa_hist',
        'cd_paciente',
        'cd_agendamento',
        'cd_profissional',
        'cd_usuario',
        'conteudo',
        'created_at',
        'updated_at',
        'deleted_at', 
    ];
 
    public function agendamento() {
        return $this->hasMany(Agendamento::class, "cd_agendamento", "cd_agendamento");
    } 
    public function usuario() {
        return $this->hasOne(Usuario::class, 'cd_usuario', 'cd_usuario');
    }
    public function paciente() {
        return $this->hasOne(Paciente::class, 'cd_paciente', 'cd_paciente');
    }
}
