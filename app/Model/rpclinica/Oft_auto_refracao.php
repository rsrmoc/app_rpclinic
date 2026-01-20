<?php

namespace App\Model\rpclinica;

use App\Model\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Model; 

class Oft_auto_refracao extends Model { 

    use ModelPossuiLogs;

    protected $table = 'oft_auto_refracao';
    protected $primaryKey = 'cd_auto_refracao';

    protected $fillable = [
        'cd_auto_refracao',
        'cd_agendamento',
        'cd_profissional',
        'dt_exame',
        'cd_usuario_exame',
        'dt_cad_exame',
        'dt_liberacao',
        'cd_usuario_liberacao',
        'dt_cad_liberacao',
        'dp', 
        'od_dc_dinamica',
        'od_dc_estatica',
        'od_de_dinamica',
        'od_de_estatica',
        'od_eixo_dinamica',
        'od_eixo_estatica',
        'od_reflexo_dinamica',
        'od_reflexo_estatica',
        'oe_dc_estatica',
        'oe_dc_estatica',
        'oe_de_dinamica',
        'oe_de_estatica',
        'oe_eixo_dinamica',
        'oe_eixo_estatica',
        'oe_reflexo_dinamica',
        'oe_reflexo_estatica',
        'oe_dc_dinamica',
        'comentario',
        'receita_dinamica',
        'receita_estatica',
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

}
