<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model; 
use App\Model\Support\ModelPossuiLogs;

class Oft_receita_oculos extends Model {

    use ModelPossuiLogs;
    protected $table = 'oft_receita_oculos';
    protected $primaryKey = 'cd_receita_oculo';

    protected $fillable = [
        'cd_receita_oculo',
        'cd_agendamento',
        'cd_profissional',
        'tipo_lente',
        'orientacao',
        'longe_od_de',
        'longe_od_dc',
        'longe_od_eixo',
        'longe_od_add',
        'longe_oe_de',
        'longe_oe_dc',
        'longe_oe_eixo',
        'longe_oe_add',
        'perto_od_de',
        'perto_od_dc',
        'perto_od_eixo',
        'perto_oe_de',
        'perto_oe_dc',
        'perto_oe_eixo',
        'inter_od_de',
        'inter_od_dc',
        'inter_od_eixo',
        'inter_oe_de',
        'inter_oe_dc',
        'inter_oe_eixo', 
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
