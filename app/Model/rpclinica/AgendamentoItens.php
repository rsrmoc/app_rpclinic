<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model; 
use App\Model\rpclinica\Exame;
use Illuminate\Support\Facades\DB; 
use Illuminate\Database\Eloquent\Builder;
use App\Model\Support\ModelPossuiLogs;

class AgendamentoItens extends Model
{ 
    use ModelPossuiLogs;

    protected $table = 'agendamento_itens';
    protected $primaryKey = 'cd_agendamento_item';

    protected $fillable = [
        'cd_agendamento_item',
        'cd_agendamento',
        'cd_agendamento_guia',
        'cd_formulario',
        'cd_exame', 
        'cd_procedimento',
        'qtde',
        'olho',
        'obs_exame',
        'cd_usuario', 
        'conteudo_laudo',
        'sn_laudo',
        'usuario_laudo',
        'vl_item',
        'key',
        'sn_anomalia',
        'cd_status_envio',
        'dt_envio',
        'cd_usuario_envio',
        'dt_valor',
        'usuario_valor',
        'dt_laudo',
        'created_at',
        'updated_at',
        'cd_status_faturamento',
        'dt_lacnc_fat',
        'fat_user',
        'vl_recebido',
        'vl_glosado' 
    ];
 
    public function atendimento() {
        return $this->hasOne(Agendamento::class, "cd_agendamento", "cd_agendamento")
        ->selectRaw("agendamento.*, date_format(dt_atendimento,'%d/%m/%Y') data_atendimento, 
        date_format(dt_agenda,'%d/%m/%Y') data_agenda ");
    }
  
    public function exame() {
        return $this->hasOne(Exame::class, 'cd_exame', 'cd_exame');
    }
 
    public function usuario() {
        return $this->hasOne(Usuario::class, 'cd_usuario', 'cd_usuario');
    }

    public function usuario_laudo() {
        return $this->hasOne(Usuario::class, 'cd_usuario', 'usuario_laudo');
    }

    public function formulario() {
        return $this->hasOne(FormulariosOftalmo::class, 'cd_formulario', 'cd_formulario');
    }

    public function status_envio() {
        return $this->hasOne(SituacaoItem::class, 'cd_situacao_itens', 'cd_status_envio') 
        ->where('tipo','log_envio');
    }

    public function situacao_laudo() {
        return $this->hasOne(SituacaoItem::class, 'cd_situacao_itens', 'situacao') 
        ->where('tipo','central_laudos');
    }

    public function whast_send() {
        return $this->hasMany(WhastSend::class, 'cd_agendamento_item', 'cd_agendamento_item')
        ->selectRaw("whast_send.*,date_format(dt_envio,'%d/%m/%Y %H:%i') data ");
    }
 
    public function img() {
        return $this->hasMany(Oft_formularios_imagens::class, 'cd_agendamento_item', 'cd_agendamento_item')
        ->selectRaw("oft_formularios_imagens.*, null img");
    }

    public function historico() {
        return $this->hasMany(AgendamentoItensHist::class, "cd_agendamento_item", "cd_agendamento_item")
        ->selectRaw("agendamento_item_hist.*,date_format(created_at,'%d/%m/%Y %H:%i') data ")
        ->orderBy('created_at','desc');
    }

    public function guia() {
        return $this->hasOne(AgendamentoGuia::class, "cd_agendamento_guia", "cd_agendamento_guia")
        ->selectRaw("agendamento_guias.*, date_format(dt_solicitacao,'%d/%m/%Y %H:%i') data_solicitacao ");
    }
    
    public function status_faturamento() {
        return $this->hasOne(SituacaoItem::class, 'cd_situacao_itens', 'cd_status_faturamento') 
        ->where('tipo','faturamento');
    }

    public function scopePainelFaturamento(Builder $query, $request): Builder
    { 

        $query = $query->with(['guia.situacao_guia','guia'=> function($q) use($request){ 
            if(isset($request['guia']))
            if($request['guia']){
                $q->where(DB::raw("nr_guia"),$request['guia']); 
            } 
        },'atendimento.profissional','atendimento.tab_situacao','status_faturamento',
        'atendimento.paciente' => function($q) use($request){
            if(isset($request['beneficiario']))
            if($request['beneficiario']){
                $q->where(DB::raw("upper(nm_paciente)"),'like','%'.mb_strtoupper($request['beneficiario']).'%'); 
            } 
        },'exame.procedimento','atendimento' => function($q) use($request){ 
            $q->join('convenio','convenio.cd_convenio','agendamento.cd_convenio')
            ->whereIn('tp_convenio',['CO','SUS']); 
            if(isset($request['sancoop']))
            if($request['sancoop']=='S'){
                $q->whereRaw("sn_sancoop='S'"); 
            }
            if(isset($request['vencida']))
            if($request->vencida=='S'){
                $q->whereRaw(" (datediff(curdate(),dt_agenda)) >= ifnull(prazo_retorno,0) ");
            }
            if(isset($request['dti']))
            if($request['dti']){
                $q->whereRaw(" date(dt_agenda) >='".trim($request->dti)."'");
            }
            if(isset($request['dtf']))
            if($request['dtf']){
                $q->whereRaw(" date(dt_agenda) <='".trim($request->dtf)."'"); 
            }
            if(isset($request['convenio']))
            if($request['convenio']){
                $q->where("agendamento.cd_convenio",$request->convenio); 
            } 
            if(isset($request['profissional']))
            if($request['profissional']){
                $q->where("cd_profissional",$request->profissional); 
            }  
            if(isset($request['agenda']))
            if($request['agenda']){
                $q->where("cd_agenda",$request->agenda); 
            } 
            
            $q->selectRaw("agendamento.*, 
            case when (datediff(curdate(),dt_agenda)) >= ifnull(prazo_retorno,0) then 'S' else 'N' end vencido,
            date_format(date_add(dt_agenda, interval ifnull(prazo_retorno,0) day ),'%d/%m/%Y') dt_venc_conta,nm_convenio");
        }]);

        $query = $query->whereHas('atendimento', function($q)  use($request) {

            $q->join('convenio','convenio.cd_convenio','agendamento.cd_convenio')
            ->whereIn('tp_convenio',['CO','SUS']); 
            if(isset($request['sancoop']))
            if($request['sancoop']=='S'){
                $q->whereRaw("sn_sancoop='S'"); 
            }
            if(isset($request['vencida']))
            if($request['vencida']=='S'){
                $q->whereRaw(" (datediff(curdate(),dt_agenda)) >= ifnull(prazo_retorno,0) ");
            }
            if(isset($request['dti']))
            if($request['dti']){
                $q->whereRaw(" date(dt_agenda) >='".trim($request->dti)."'");
            }
            if(isset($request['dtf']))
            if($request['dtf']){
                $q->whereRaw(" date(dt_agenda) <='".trim($request->dtf)."'"); 
            }
            if(isset($request['convenio']))
            if($request['convenio']){
                $q->where("cd_convenio",$request->convenio); 
            } 
            if(isset($request['profissional']))
            if($request['profissional']){
                $q->where("cd_profissional",$request->profissional); 
            }  
            if(isset($request['agenda']))
            if($request['agenda']){
                $q->where("cd_agenda",$request->agenda); 
            }   

        });
 

        if(isset($request['beneficiario']))
        if($request['beneficiario']){
            $query = $query->whereHas('atendimento.paciente', function($q)  use($request) {
                $q->where(DB::raw("upper(nm_paciente)"),'like','%'.mb_strtoupper($request['beneficiario']).'%'); 
            });
        }
 
        if((isset($request['guia'])))
        if(($request['situacao'])||($request['guia'])){
            $query = $query->whereHas('atendimento.paciente', function($q)  use($request) {

                if($request['guia']){
                    $q->where(DB::raw("nr_guia"),$request['guia']); 
                } 
            });
        }
 
        if(isset($request['atendimento']))
        if($request['atendimento']){ 
            $query =$query->where("cd_agendamento",$request->atendimento); 
        } 
        if(isset($request['cd_item']))
        if($request['cd_item']){ 
            $query =$query->where("cd_exame",$request->cd_item); 
        } 
        if(isset($request['codigo']))
        if($request['codigo']){
            $query =$query->where("cd_agendamento_item",$request['codigo']); 
        }  
        if($request['situacao']){
            $query =$query->where("cd_status_faturamento",$request['situacao']); 
        } 

        return $query;
    }


    public function scopePainelCentralLaudos(Builder $query, $request): Builder
    {

        $query = $query->with([
        'exame' => function($q) use($request){ 
            $q->where("tp_item",'EX'); 
        },
        'img','status_envio','situacao_laudo','whast_send','atendimento.paciente','atendimento.profissional',
        'atendimento.especialidade','atendimento.convenio','atendimento.local',
        'atendimento' => function($q) use($request){

            if(empty($request->user()->visualizar_exame)){
                $q->where('cd_profissional', ($request->user()->cd_profissional) ? $request->user()->cd_profissional : 0);
            }
            $q->join('paciente','paciente.cd_paciente','agendamento.cd_paciente');
            if($request['cd_executante']){
                $q->where("cd_profissional",$request['cd_executante']); 
            }
            if($request['cd_convenio']){
                $q->where("cd_convenio",$request['cd_convenio']); 
            }
            if($request['paciente']){
                $q->where("nm_paciente",'like','%'.$request['paciente'].'%'); 
            } 
            if($request['tipo_painel']=='central_laudo'){
                if($request->dti){
                    $q->whereRaw(" date(dt_agenda) >='".trim($request->dti)."'"); 
                }
                if($request->dtf){
                    $q->whereRaw(" date(dt_agenda) <='".trim($request->dtf)."'"); 
                }
            }
        }]);
       
        $query = $query->whereHas('exame', function($query) {
            $query->where('tp_item','EX');
        });

        $query = $query->whereHas('atendimento', function($q) use($request){

            if(empty($request->user()->visualizar_exame)){
                $q->where('cd_profissional', ($request->user()->cd_profissional) ? $request->user()->cd_profissional : 0);
            }
            $q->join('paciente','paciente.cd_paciente','agendamento.cd_paciente');
            if($request['cd_executante']){
                $q->where("cd_profissional",$request['cd_executante']); 
            }
            if($request['cd_solicitante']){
                $q->where("cd_profissional",$request['cd_solicitante']); 
            }
            if($request['cd_convenio']){
                $q->where("cd_convenio",$request['cd_convenio']); 
            }
            if($request['paciente']){
                $q->where("nm_paciente",'like','%'.$request['paciente'].'%'); 
            } 
            if($request['tipo_painel']=='central_laudo'){
                if($request->dti){
                    $q->whereRaw(" date(dt_agenda) >='".trim($request->dti)."'"); 
                }
                if($request->dtf){
                    $q->whereRaw(" date(dt_agenda) <='".trim($request->dtf)."'"); 
                }
            }

        });

        if($request['tipo_painel']=='log_envio'){
           if($request->dti){  
                $query =$query->whereRaw(" date(dt_laudo) >='".trim($request->dti)."'"); 
           }  
           if($request->dtf){ 
                $query =$query->whereRaw(" date(dt_laudo) <='".trim($request->dtf)."'"); 
           }  
        }

        if($request->dtenvio){ 
            $query =$query->whereRaw(" date(dt_envio) = '".trim($request->dtenvio)."'"); 
        }  
        if($request->status_envio){ 
            $query =$query->where("cd_status_envio",$request->status_envio); 
        } 
         
        if($request['cd_executante']){ 
            $query = $query->whereHas('atendimento',function($q) use($request) { 
                $q->where("cd_profissional",$request['cd_executante']); 
            });
        }  

        if($request['cd_solicitante']){ 
            $query = $query->whereHas('atendimento',function($q) use($request) { 
                $q->where("cd_profissional",$request['cd_solicitante']); 
            });
        } 
        
        if($request['paciente']){ 
            $query = $query->whereHas('atendimento',function($q) use($request) { 
                $q->join('paciente','paciente.cd_paciente','agendamento.cd_paciente');
                $q->where("nm_paciente",'like','%'.$request['paciente'].'%'); 
            });
        } 

        if($request['cd_convenio']){ 
            $query = $query->whereHas('atendimento',function($q) use($request) { 
                $q->where("cd_convenio",$request['cd_convenio']); 
            });
        }
 
        if($request->atendimento){ 
            $query =$query->where(DB::raw("cd_agendamento"),$request->atendimento); 
        }  
 
        if($request['situacao']){ 
            $query =$query->where("situacao",$request['situacao']); 
        }  
 
        if($request->cd_exame){ 
            $query =$query->where(DB::raw("cd_exame"),$request->cd_exame); 
        }  

        return $query;
    }

 

    public function scopePainelCirurgias(Builder $query, $request): Builder
    {

        $query = $query->with(['exame' => function($q) use($request){ 
            $q->where("tp_item",'CI'); 
        },'img','atendimento.paciente','atendimento.profissional','atendimento.especialidade','atendimento.convenio',
        'atendimento.local','atendimento' => function($q) use($request){
            $q->join('paciente','paciente.cd_paciente','agendamento.cd_paciente');
            if($request['cd_executante']){
                $q->where("cd_profissional",$request['cd_executante']); 
            }
            if($request['cd_convenio']){
                $q->where("cd_convenio",$request['cd_convenio']); 
            }
            if($request['paciente']){
                $q->where("nm_paciente",'like','%'.$request['paciente'].'%'); 
            }
        }]);

        $query = $query->whereHas('exame', function($query) {
            $query->where('tp_item','CI');
         });
 
        if($request->dti){ 
            $query =$query->where("created_at",'>=',trim($request->dti)); 
        } 

        if($request->dtf){ 
            $query =$query->where("created_at",'<=',trim($request->dtf)); 
        }  
 
        if($request['cd_executante']){ 
            $query = $query->whereHas('atendimento',function($q) use($request) { 
                $q->where("cd_profissional",$request['cd_executante']); 
            });
        }  

        if($request['paciente']){ 
            $query = $query->whereHas('atendimento',function($q) use($request) { 
                $q->join('paciente','paciente.cd_paciente','agendamento.cd_paciente');
                $q->where("nm_paciente",'like','%'.$request['paciente'].'%'); 
            });
        } 

        if($request['cd_convenio']){ 
            $query = $query->whereHas('atendimento',function($q) use($request) { 
                $q->where("cd_convenio",$request['cd_convenio']); 
            });
        }
 
        if($request->atendimento){ 
            $query =$query->where(DB::raw("cd_agendamento"),$request->atendimento); 
        }  
 
        if($request->situacao){ 
            $query =$query->where(DB::raw("situacao"),$request->situacao); 
        }  
 
        if($request->cd_exame){ 
            $query =$query->where(DB::raw("cd_exame"),$request->cd_exame); 
        }  

        return $query;
    }

   
}
