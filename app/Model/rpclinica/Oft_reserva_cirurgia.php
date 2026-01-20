<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model; 
use Illuminate\Support\Facades\DB; 
use Illuminate\Database\Eloquent\Builder;
use App\Model\Support\ModelPossuiLogs;

class Oft_reserva_cirurgia extends Model {

    use ModelPossuiLogs;
    protected $table = 'oft_reserva_cirurgia';
    protected $primaryKey = 'cd_reserva_cirurgia';

    protected $fillable = [
        'cd_reserva_cirurgia',
        'cd_agendamento',
        'cd_profissional',
        'cd_cirurgiao',
        'cd_cirurgia',   
        'cd_usuario',
        'comentarios',
        'cd_convenio',
        'dt_solicitacao',
        'dt_autorizacao',
        'guia',
        'cd_opme',
        'sn_negociado',
        'valor',
        'agendamento_reserva',
        'comentarios_form',
        'cd_usuario_form',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
 
    public function agendamento() {
        return $this->hasOne(Agendamento::class, 'cd_agendamento', 'cd_agendamento');
    }

    public function profissional() {
        return $this->hasOne(Profissional::class, 'cd_profissional', 'cd_profissional');
    }

    public function cirurgiao() {
        return $this->hasOne(Profissional::class, 'cd_profissional', 'cd_cirurgiao');
    }

    public function cirurgia() {
        return $this->hasOne(Exame::class, 'cd_exame', 'cd_cirurgia');
    }

    public function produto() {
        return $this->hasOne(produto::class, 'cd_produto', 'cd_opme');
    }


    public function opme() {
        return $this->hasMany(Oft_reserva_cirurgia_opme::class, 'cd_reserva_cirurgia', 'cd_reserva_cirurgia');
    }
 
    public function historicos() {
        return $this->hasMany(Oft_reserva_cirurgia_hist::class, 'cd_reserva_cirurgia', 'cd_reserva_cirurgia');
    }

    public function scopeReservasCrirugia(Builder $query, $request): Builder
    {

        $query = $query->with('opme','agendamento.paciente','profissional','cirurgiao','cirurgia',
        'opme.produtos', 'historicos','agendamento.convenio','produto');
  
        if($request->dti){ 
            $query =$query->where("created_at",'>=',trim($request->dti)); 
        } 
        if($request->dtf){ 
            $query =$query->where("created_at",'<=',trim($request->dtf)); 
        }  

        if($request->cd_profissional){ 
            $query =$query->where(DB::raw("cd_profissional"),$request->cd_profissional); 
        }  

        if($request->cd_cirurgiao){ 
            $query =$query->where(DB::raw("cd_cirurgiao"),$request->cd_cirurgiao); 
        }  

        if($request->cd_cirurgia){ 
            $query =$query->where(DB::raw("cd_cirurgia"),$request->cd_cirurgia); 
        }  

        if($request->atendimento){ 
            $query =$query->where(DB::raw("cd_agendamento"),$request->atendimento); 
        }  
 
        if($request->situacao){ 
            $query =$query->where(DB::raw("situacao"),$request->situacao); 
        }  
 
        return $query;
    }
}
