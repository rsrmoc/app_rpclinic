<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgendaEscala extends Model
{
    use SoftDeletes;
    protected $table = 'agenda_escala';
    protected $primaryKey = 'cd_escala_agenda';

    protected $fillable = [
        'cd_escala_agenda',
        'cd_agenda',
        'cd_dia',
        'nr_dia',
        'qtde_proc',
        'dt_inicial',
        'dt_fim',
        'hr_inicial',
        'hr_final',
        'intervalo',
        'tp_agenda',
        'escala_gerada',
        'escala_manual',
        'escala_diaria',
        'situacao',
        'qtde_sessao',
        'sn_sessao',
        'qtde_encaixe',
        'sn_particular',
        'sn_convenio',
        'sn_sus',
        'nm_intervalo',
        'dt_geracao',
        'sn_ativo',
        'usuario_up',
        'usuario_geracao',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function bloqueiosGerados() {
        return $this->hasOne(BloqueioAgendamentoGerado::class, "cd_escala", "cd_escala_agenda");
    }

    public function feriadosGerados() {
        return $this->hasOne(FeriadoAgendamentoGerado::class, "cd_escala", "cd_escala_agenda");
    }

    public function usuario() {
        return $this->hasOne(Usuario::class, 'cd_usuario', 'usuario_geracao');
    }

    public function escalaTipoAtend() {
        return $this->hasMany(AgendaTipoAtendimento::class, 'cd_escala', 'cd_escala_agenda');
    }
 
    public function escala_tipo() {
                
        return $this->hasMany(AgendaTipoAtendimento::class, 'cd_escala', 'cd_escala_agenda');
    }

    public function escalaEspec() {
        return $this->hasMany(AgendaEspecialidades::class, 'cd_escala', 'cd_escala_agenda');
    }
    

    public function escalaLocal() {
        return $this->hasMany(AgendaLocais::class, 'cd_escala', 'cd_escala_agenda');
    }

    public function escalaConv() {
        return $this->hasMany(AgendaConvenios::class, 'cd_escala', 'cd_escala_agenda');
    }

    public function escalaProf() {
        return $this->hasMany(AgendaProfissionais::class, 'cd_escala', 'cd_escala_agenda');
    }

    public function agenda() {
        return $this->hasOne(Agenda::class, "cd_agenda", "cd_agenda");
    }

    public function escala_horarios() {
        return $this->hasMany(AgendaEscalaHorario::class, 'cd_escala_agenda', 'cd_escala_agenda');
    }

    public function agendamento() {
        return $this->hasMany(Agendamento::class, "cd_escala", "cd_escala_agenda");
    }
    
    public function agendamento_pendente() {
        return $this->hasMany(Agendamento::class, "cd_escala", "cd_escala_agenda")
        ->whereNull('cd_agenda_escala_horario')
        ->whereRaw("cd_paciente is not null")
        ->whereRaw('dt_agenda >= curdate()');
    }
    
}
