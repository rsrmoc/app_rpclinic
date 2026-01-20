<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model; 
use App\Model\Support\ModelPossuiLogs;

class Oft_refracao extends Model { 

    use ModelPossuiLogs;
    
    protected $table = 'oft_refracao';
    protected $primaryKey = 'cd_refracao';

    protected $fillable = [
        'cd_refracao',
        'cd_agendamento',
        'cd_profissional',
        'dt_exame',
        'cd_usuario_exame',
        'dt_cad_exame',
        'dt_liberacao',
        'cd_usuario_liberacao',
        'dt_cad_liberacao',
        'rd_receita',
        'dp',
        'ard_od_de',
        'ard_od_dc',
        'ard_od_eixo',
        'ard_od_av',
        'ard_od_add',
        'ard_od_add_av',
        'ard_oe_de',
        'ard_oe_dc',
        'ard_oe_eixo',
        'ard_oe_av',
        'ard_oe_add',
        'ard_oe_add_av',
        're_receita',
        'are_od_de',
        'are_od_dc',
        'are_od_eixo',
        'are_od_av',
        'are_oe_de',
        'are_oe_dc',
        'are_oe_eixo',
        'are_oe_av', 
        'obs', 
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
