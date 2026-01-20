<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FaturamentoConta extends Model
{


    protected $table = 'faturamento_conta';
    protected $primaryKey = 'id_conta';

    protected $fillable = [
        'id_conta',
        'cd_conta',
        'cd_agendamento',
        'cd_atendimento',
        'cd_profissional',
        'nm_profissional',
        'cd_prof_exec',
        'nm_prof_exec',
        'cd_convenio',
        'nm_convenio',
        'dt_conta',
        'cd_paciente',
        'nm_paciente',
        'cd_proc',
        'cod_proc',
        'ds_proc',
        'qtde',
        'vl_unitario',
        'vl_total',
        'created_at',
        'updated_at',
        'situacao',
        'sn_confere',
        'usuario_confere',
        'dt_confere'
    ];

    public function convenio() {
        return $this->hasOne(Convenio::class, 'cd_convenio', 'cd_convenio');
    }

    public function agendamentos() {
        return $this->hasMany(Agendamento::class, 'cd_agendamento', 'cd_agendamento');
    }

    public function scopeConta(Builder $query, $request): Builder
    {
        $query = $query->selectRaw("cd_conta,cd_atendimento,nm_profissional,nm_prof_exec,nm_convenio,nm_paciente, dt_conta, DATE_FORMAT(dt_conta, '%d/%m/%Y') data_conta,
        FORMAT(sum(qtde), 0, 'de_DE') qtde,  FORMAT(sum(vl_total), 2, 'de_DE') vl_total ")
        ->groupByRaw("cd_conta,cd_atendimento,nm_profissional,nm_prof_exec,nm_convenio,nm_paciente, dt_conta, DATE_FORMAT(dt_conta, '%d/%m/%Y')");


        return $query;
    }


}
