<?php

namespace App\Model\rpclinica;

use App\Model\laudos\UsuarioLaudo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; 
use Illuminate\Database\Eloquent\Builder;
use App\Model\Support\ModelPossuiLogs;
class Agendamento extends Model
{
    
    use ModelPossuiLogs;
    
    protected $table = 'agendamento';
    protected $primaryKey = 'cd_agendamento';

    protected $fillable = [
        'cd_agendamento',
        'cd_agenda',
        'cd_escala',
        'sn_atend_avulso',
        'cd_agenda_escala_horario',
        'cd_paciente',
        'carater',
        'vl_conta',
        'cd_profissional',
        'sn_pre_exame',
        'dt_pre_exame',
        'usuario_pre_exame',
        'cd_local_atendimento',
        'intervalo',
        'cd_procedimento',
        'cd_convenio',
        'dt_validade',
        'cd_especialidade',
        "cd_cid",
        'cd_classificacao',
        'situacao',
        'valor',
        'recebido',
        'tipo',
        'data_horario',
        'email',
        'celular',
        'whast',
        'dt_whast',
        'dt_inicio',
        'usuario_inicio',
        'obs',
        "queixa_principal",
        "dt_inicio_sintoma",
        "peso",
        "altura",
        "imc",
        "temperatura",
        "arterial_sistotica",
        "arterial_diastolica",
        "frequencia_respiratoria",
        "frequencia_cardiaca",
        "sms",
        "whatsapp",
        'informacoes_adicionais',
        'motivo_consulta',
        'hist_oft',
        'medicamentos',
        'alergias', 
        'historia_pregressa',
        'anamnese',
        'exame_fisico',
        'hipotese_diagnostica',
        'conduta',
        'cartao',
        'cd_bloqueio_gerado',
        'cd_feriado_gerado',
        'dt_agenda',
        'hr_agenda',
        'hr_final',
        'dia_semana',
        'cd_reagendamento',
        'sn_presenca',
        'dt_presenca',
        'user_presenca',
        'whast_id',
        'whast_resp',
        'dt_resp_whast',
        'cd_whast_receive',
        'ds_whast',
        'id_retorno_whast',
        'dt_anamnese',
        'usuario_anamnese',
        'anamnese',
        'usuario_geracao',
        'usuario_receb',
        'dt_receb',
        'vl_acrescimo',
        'vl_desconto',
        'user_desconto',
        'dt_desconto',
        'doc_assinado',
        'doc_conteudo',
        'dt_assinado_digital',
        'user_assinado_digital',
        'hash_assinado_digital',
        "sn_atendimento",
        "dt_atendimento",
        "usuario_atendimento",
        "sn_pre_exame",
        "dt_pre_exame",
        "usuario_pre_exame",
        "cd_origem",
        "cd_prof_solicitante",
        "situacao_conta",
        "user_conta",
        "dt_conta",
        "usuario_finalizacao",
        "dt_finalizacao",
        "sn_finalizado",
        ""
    ];

    protected $casts = [
        'sms' => 'boolean',
        'whatsapp' => 'boolean',
    ];

    public function getValorAttribute() {
        return formatCurrencyForFront($this->attributes['valor']);
    }

    public function agenda() {
        return $this->hasOne(Agenda::class, 'cd_agenda', 'cd_agenda');
    }

    public function paciente() {
        return $this->hasOne(Paciente::class, 'cd_paciente', 'cd_paciente')
        ->selectRaw("paciente.*,date_format(dt_nasc,'%d/%m/%Y') data_nasc ");
    }

    public function profissional() {
        return $this->hasOne(Profissional::class, 'cd_profissional', 'cd_profissional');
    }

    public function profissional_externo() {
        return $this->hasOne(Profissional_externo::class, 'cd_profissional_externo', 'cd_prof_solicitante');
    }

    public function origem() {
        return $this->hasOne(Origem_paciente::class, 'cd_origem', 'cd_origem');
    }
 
    public function especialidade() {
        return $this->hasOne(Especialidade::class, 'cd_especialidade', 'cd_especialidade');
    }

    public function procedimento() {
        return $this->hasOne(Procedimento::class, 'cd_proc', 'cd_procedimento');
    }

    public function local() {
        return $this->hasOne(LocalAtendimento::class, 'cd_local', 'cd_local_atendimento');
    }

    public function cid() {
        return $this->hasOne(CID::class, 'cd_cid', 'cd_cid');
    }

    public function documentos() {
        return $this->hasMany(AgendamentoDocumentos::class, 'cd_agendamento', 'cd_agendamento')
        ->selectRaw("agendamento_documentos.*,date_format(created_at,'%d/%m/%Y %H:%i') data_hora ")
        ->orderBy('created_at', 'desc');
    }

    public function usuario_portal() {
        return $this->hasOne(UsuarioLaudo::class, 'cd_usuario', 'cd_agendamento');
    }

    public function itens() {
        return $this->hasMany(AgendamentoItens::class, 'cd_agendamento', 'cd_agendamento')->orderBy('created_at', 'desc')
        ->selectRaw("agendamento_itens.*,date_format(created_at,'%d/%m/%Y') data,date_format(created_at,'%d/%m/%Y %H:%i') data_hora ");
    }

    public function tab_exames() {
        return $this->hasMany(AgendamentoItens::class, 'cd_agendamento', 'cd_agendamento') 
        ->selectRaw("agendamento_itens.*,date_format(created_at,'%d/%m/%Y') data,date_format(created_at,'%d/%m/%Y %H:%i') data_hora ");
    }
 
    public function itens_pedente() {
        return $this->hasMany(AgendamentoItens::class, 'cd_agendamento', 'cd_agendamento')
        ->whereNull('cd_agendamento_guia')
        ->orderBy('created_at', 'desc')
        ->selectRaw(" agendamento_itens.*,date_format(created_at,'%d/%m/%Y') data ");
    }

    public function convenio() {
        return $this->hasOne(Convenio::class, 'cd_convenio', 'cd_convenio');
    }

    public function anexos() {
        return $this->hasMany(AgendamentoAnexos::class, 'cd_agendamento', 'cd_agendamento');
    }

    public function agenda_data() {
        return $this->hasMany(Agendamento::class, 'cd_agendamento', 'cd_agendamento');
    }

    public function boleto() {
        return $this->hasMany(DocumentoBoleto::class, 'cd_agendamento', 'cd_agendamento');
    }
  
    public function contas() {
        return $this->hasMany(FaturamentoConta::class, 'cd_agendamento', 'cd_agendamento');
    }

    public function escalas() {
        return $this->hasOne(AgendaEscala::class, 'cd_escala_agenda', 'cd_escala');
    }

    public function tipo_atend() {
        return $this->hasOne(TipoAtendimento::class, 'cd_tipo_atendimento', 'tipo');
    }

    public function situacao() {
        return $this->hasOne(AgendamentoSituacao::class, 'cd_situacao', 'situacao');
    }

    public function tab_situacao() {
        return $this->hasOne(AgendamentoSituacao::class, 'cd_situacao', 'situacao');
    }

    public function user_anamnese() {
        return $this->hasOne(Usuario::class, 'cd_usuario', 'usuario_anamnese');
    }

    public function user_descontos() {
        return $this->hasOne(Usuario::class, 'cd_usuario', 'user_desconto');
    }

    public function user_agendamento() {
        return $this->hasOne(Usuario::class, 'cd_usuario', 'usuario_geracao');
    }

    public function user_atendimento() {
        return $this->hasOne(Usuario::class, 'cd_usuario', 'usuario_atendimento');
    }

    public function user_finalizacao() {
        return $this->hasOne(Usuario::class, 'cd_usuario', 'usuario_finalizacao');
    }

    public function user_pre_exame() {
        return $this->hasOne(Usuario::class, 'cd_usuario', 'usuario_pre_exame');
    }

    public function user_presenca() {
        return $this->hasOne(Usuario::class, 'cd_usuario', 'user_presenca');
    }
 
    public function agendamento_hist_pregressa() {
        return $this->hasOne(AgendamentoHistoriaPregressaHist::class, 'cd_agendamento', 'cd_agendamento');
    }
 
 
    public function guia() {
        return $this->hasMany(AgendamentoGuia::class, 'cd_agendamento', 'cd_agendamento')
        ->selectRaw("agendamento_guias.*,date_format(created_at,'%d/%m/%Y') data,date_format(dt_solicitacao,'%d/%m/%Y') data_solicitacao ");
    }
      
    public function auto_refracao() {
        return $this->hasOne(Oft_auto_refracao::class, 'cd_agendamento', 'cd_agendamento')
        ->selectRaw("oft_auto_refracao.*,date_format(dt_cad_exame,'%d/%m/%Y') data_cad_exame,date_format(dt_cad_liberacao,'%d/%m/%Y') data_cad_liberacao ");
    }
    public function ceratometria() {
        return $this->hasOne(Oft_ceratometria::class, 'cd_agendamento', 'cd_agendamento')
        ->selectRaw("oft_ceratometria.*,date_format(dt_exame,'%d/%m/%Y') data_cad_exame,date_format(dt_liberacao,'%d/%m/%Y') data_cad_liberacao ");
    }
    public function formularios_imagens_ceratometria_comp() {
        return $this->hasOne(Oft_formularios_imagens::class, 'cd_agendamento', 'cd_agendamento')
        ->selectRaw("oft_formularios_imagens.*,date_format(dt_exame,'%d/%m/%Y') data_cad_exame")
        ->where('cd_formulario','CERATOSCOPIA_COMP');
    }

    public function situacao_log() {
        return $this->hasMany(AgendamentoSituacaoLog::class, 'cd_agendamento', 'cd_agendamento')
        ->selectRaw("agendamento_situacao_log.*,date_format(created_at,'%d/%m/%Y %H:%i') data");
    }
    
    public function horario_disponivel() {
        return $this->hasMany(AgendaEscalaHorario::class, 'cd_escala_agenda', 'cd_escala')
        ->orderBy("cd_horario");
    }
 
    public function tab_whast_send() {
        return $this->hasMany(WhastSend::class, 'cd_agendamento', 'cd_agendamento')
        ->selectRaw("whast_send.*,date_format(dt_envio,'%d/%m/%Y %H:%i') data")
        ->orderBy("dt_envio");
    }
    

    public function scopeAgendamentos(Builder $query, $request): Builder
    {

        $query = $query->with(['agenda','user_finalizacao','paciente' => function($q) use($request){ 
            if($request['cpf']){  
                $q->where(DB::raw(" replace(replace(cpf,'-',''),'.','')"),preg_replace('/[^0-9]/', '', $request['cpf'])); 
            } 
            if($request['paciente']){
                $q->where(DB::raw("upper(nm_paciente)"),'like',mb_strtoupper($request['paciente'].'%')); 
            } 
        },'profissional','especialidade','local','itens','itens.img',
        'itens.exame.procedimento','convenio','situacao','tipo_atend','escalas','user_agendamento',
        'user_atendimento','user_pre_exame','guia.itens.exame.procedimento','convenio','origem',
        'itens.usuario','itens.historico','itens.historico.usuario','boleto.forma','boleto.usuario',
        'boleto.conta','boleto.setor','boleto.marca']);
 
        if(($request['cpf'])||($request['paciente'])){
            $query = $query->whereHas('paciente', function($q)  use($request) {
                if($request['cpf']){
                    $q->where(DB::raw(" replace(replace(cpf,'-',''),'.','')"),preg_replace('/[^0-9]/', '', $request['cpf'])); 
                }
                if($request['paciente']){
                    $q->where(DB::raw("upper(nm_paciente)"),'like',mb_strtoupper($request['paciente'].'%')); 
                }
            });
        }

        if($request->dti){ 
            $query =$query->where(DB::raw("date(dt_agenda)"),'>=',trim($request->dti)); 
        }
        if($request->dtf){ 
            $query =$query->where(DB::raw("date(dt_agenda)"),'<=',trim($request->dtf)); 
        }   
        if($request->data){ 
            $query =$query->where(DB::raw("date(dt_agenda)"),trim($request->data)); 
        } 
        if($request->especialidade){ 
            $query =$query->where(DB::raw("cd_especialidade"),$request->especialidade); 
        }  

        if($request->cd_executante){ 
            $query =$query->where(DB::raw("cd_profissional"),$request->cd_executante); 
        }  

        if($request->agenda){ 
            $query =$query->where(DB::raw("cd_agenda"),$request->agenda); 
        }  

        if($request->profissional){ 
            $query =$query->where(DB::raw("cd_profissional"),$request->profissional); 
        }  
 
        if($request->situacao){ 
            $query =$query->where(DB::raw("situacao"),$request->situacao); 
        }
        

        if($request->atendimento){ 
            $query =$query->where(DB::raw("cd_agendamento"),$request->atendimento); 
        }
 
        if($request->not_situacao){ 
            $query =$query->whereNotIn("situacao",$request['not_situacao']); 
        }

        if($request->cd_local){ 
            $query =$query->where(DB::raw("cd_local_atendimento"),$request->local); 
        }  
        if($request->cd_tipo){ 
            $query =$query->where(DB::raw("tipo"),$request->cd_tipo); 
        }  
        if($request->situacao){ 
            $query =$query->where(DB::raw("situacao"),$request->situacao); 
        }  
        
        return $query;
    }
}
