<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exame extends Model
{ 

    protected $table = 'exames';
    protected $primaryKey = 'cd_exame';

    protected $fillable = [
        'cd_exame',
        'nm_exame',
        'cd_formulario',
        'tp_item',
        'cd_local',
        'sn_ativo',
        'cod_proc',
        'cd_usuario',
        'updated_at',
        'created_at',
        'deleted_at'
    ];

    public function procedimento() {
        return $this->hasOne(Procedimento::class, 'cod_proc', 'cod_proc');
    }
    public function agenda_exame() {
        return $this->hasOne(AgendaExames::class, 'cd_exame', 'cd_exame');
    } 
    public function valor() {
        return $this->hasOne(ProcedimentoConvenio::class, 'cod_proc', 'cod_proc');
    } 
    public function formularios() {
        return $this->hasMany(ExameFormulario::class, 'cd_exame', 'cd_exame')
        ->selectRaw("exames_formularios.*,date_format(created_at,'%d/%m/%Y') data")
        ->orderBy("created_at",'desc');
    }
}
