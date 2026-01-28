<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 
use Illuminate\Support\Facades\DB; 
use Illuminate\Database\Eloquent\Builder;
use App\Model\Support\ModelPossuiLogs;

class EscalaMedica extends Model
{
    use SoftDeletes;

    protected $table = 'escala_medica';
    protected $primaryKey = 'cd_escala_medica';

    protected $fillable = [
        'cd_escala_medica',
        'dt_escala',
        'cd_dia',
        'hr_inicial',
        'hr_final',
        'cd_profissional',
        'cd_escala_localidade',
        'cd_escala_tipo',
        'qtde_escala',
        'qtde_localidade',
        'qtde_profissional',
        'qtde_final',
        'situacao',
        'app_confirmacao_user',
        'app_confirmacao_dt',
        'obs',
        'informativo',
        'cd_usuario_agenda',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function profissional() {
        return $this->hasOne(Profissional::class, 'cd_profissional', 'cd_profissional');
    }

    public function localidade() {
        return $this->hasOne(EscalaLocalidade::class, 'cd_escala_localidade', 'cd_escala_localidade');
    }

    public function tipo_escala() {
        return $this->hasOne(EscalaTipo::class, 'cd_escala_tipo', 'cd_escala_tipo');
    }

    public function especialidade() {
        return $this->hasOne(Especialidade::class, 'cd_especialidade', 'cd_especialidade');
    }

    public function usuario() {
        return $this->hasOne(Usuario::class, 'cd_usuario', 'cd_usuario');
    }


    public function scopeGetEscalasMedicas(Builder $query, $request): Builder
    {

        $query = $query->with(['profissional','localidade','tipo_escala','usuario']);
 
        if($request->data){ 
            $query =$query->where(DB::raw("date(dt_escala)"),'=',trim($request->data)); 
        }
        if($request->profissional){ 
            $query =$query->where(DB::raw("cd_profissional"),'=',trim($request->profissional)); 
        }   
        if($request->localidade){ 
            $query =$query->where(DB::raw("cd_escala_localidade"),trim($request->localidade)); 
        } 
        if($request->tipo_escala){ 
            $query =$query->where(DB::raw("cd_escala_tipo"),$request->tipo_escala); 
        }   
        if($request->situacao){ 
            $query =$query->where(DB::raw("situacao"),$request->situacao); 
        }  
 
        return $query;
    }

    public function scopeHeader(Builder $query, $request): Builder
    {

        if($request->data){ 
            $query =$query->whereRaw("date(dt_escala) like '".substr($request->data,0,7)."%'"); 
        }
        if($request->profissional){ 
            $query =$query->where(DB::raw("cd_profissional"),'=',trim($request->profissional)); 
        }   
        if($request->localidade){ 
            $query =$query->where(DB::raw("cd_escala_localidade"),trim($request->localidade)); 
        } 
        if($request->tipo_escala){ 
            $query =$query->where(DB::raw("cd_escala_tipo"),$request->tipo_escala); 
        }   
        if($request->situacao){ 
            $query =$query->where(DB::raw("situacao"),$request->situacao); 
        }

        $query =$query->selectRaw("
        sum( case when situacao='Agendado' then 1 else 0 end ) agendado,
        sum( case when situacao='Confirmado' then 1 else 0 end ) confirmado,
        sum( case when situacao='Finalizado' then 1 else 0 end ) finalizado,
        sum( case when situacao='Pago' then 1 else 0 end ) pago
        ");

        return $query;
    }
 
}
