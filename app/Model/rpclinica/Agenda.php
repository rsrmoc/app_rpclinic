<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agenda extends Model {
    use SoftDeletes;

    protected $table = 'agenda';
    protected $primaryKey = 'cd_agenda';

    protected $fillable = [
        'nm_agenda',
        'tela_exec',
        'cd_local_atendimento',
        'cd_especialidade',
        'cd_profissional',
        'cd_empresa',
        'segunda',
        'terca',
        'quarta',
        'quinta',
        'sexta',
        'sabado',
        'domingo',
        'hr_inicial',
        'hr_final',
        'intervalo',
        'obs',
        'sn_agenda_aberta',
        'cd_usuario',
        'cd_tipo_atend',
        'data_inicial',
        'data_final',
        'cd_proc',
        'tipo_atend_editavel',
        'profissional_editavel',
        'especialidade_editavel',
        'local_atendimento_editavel',
        'qtde_sus',
        'qtde_particular',
        'qtde_convenio',
        'sn_sus',
        'sn_particular',
        'sn_convenio',
        'qtde_sessao',
        'sn_sessao',
        'agendamento_gerado',
        'qtde_encaixe',
        'tipo_agendamento',
        'sn_agenda_manual',
        
    ];

    protected $casts = [
        'segunda' => 'boolean',
        'terca' => 'boolean',
        'quarta' => 'boolean',
        'quinta' => 'boolean',
        'sexta' => 'boolean',
        'sabado' => 'boolean',
        'domingo' => 'boolean',
        'procedimento_editavel' => 'boolean',
        'profissional_editavel' => 'boolean',
        'especialidade_editavel' => 'boolean',
        'local_atendimento_editavel' => 'boolean',
        'agendamento_gerado' => 'boolean',
    ];

    public function profissional() {
        return $this->hasOne(Profissional::class, 'cd_profissional', 'cd_profissional');
    }

    public function especialidade() {
        return $this->hasOne(Especialidade::class, 'cd_especialidade', 'cd_especialidade');
    }

    public function procedimento() {
        return $this->hasOne(Procedimento::class, 'cd_proc', 'cd_proc');
    }

    public function local() {
        return $this->hasOne(LocalAtendimento::class, 'cd_local', 'cd_local_atendimento');
    }

    public function tp_agendamento() {
        return $this->hasOne(TipoAtendimento::class, 'cd_tipo_atendimento', 'tipo_agendamento');
    }

    public function profissionais() {
        return $this->hasMany(AgendaProfissionais::class, 'cd_agenda', 'cd_agenda');
    }

    public function especialidades() {
        return $this->hasMany(AgendaEspecialidades::class, 'cd_agenda', 'cd_agenda');
    }

    public function procedimentos() {
        return $this->hasMany(AgendaProcedimentos::class, 'cd_agenda', 'cd_agenda');
    }

    public function locais() {
        return $this->hasMany(AgendaLocais::class, 'cd_agenda', 'cd_agenda');
    }

    public function convenios() {
        return $this->hasMany(AgendaConvenios::class, 'cd_agenda', 'cd_agenda');
    }

    public function bloqueiosGerados() {
        return $this->hasOne(BloqueioAgendamentoGerado::class, "cd_agenda", "cd_agenda");
    }

    public function feriadosGerados() {
        return $this->hasOne(FeriadoAgendamentoGerado::class, "cd_agenda", "cd_agenda");
    }
    public function escalas() {
        return $this->hasMany(AgendaEscala::class, 'cd_agenda', 'cd_agenda')->where('sn_ativo','S');
    }

    public function escalas_manual() {
        return $this->hasMany(AgendaEscala::class, 'cd_agenda', 'cd_agenda');
    }

    public function dow() {
        return $this->hasMany(AgendaEscala::class, 'cd_agenda', 'cd_agenda');
    }

    public function itens() {
        return $this->hasMany(AgendaExames::class, 'cd_agenda', 'cd_agenda')
        ->join('exames','agenda_exames.cd_exame','exames.cd_exame')
        ->selectRaw("cd_agenda_exame,cd_agenda,exames.cd_exame,tp_item,nm_exame")
        ->orderBy('nm_exame');
    }
 
}
