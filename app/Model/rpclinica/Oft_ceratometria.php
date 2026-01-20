<?php

namespace App\Model\rpclinica;
use App\Model\Support\ModelPossuiLogs;

use Illuminate\Database\Eloquent\Model; 

class Oft_ceratometria extends Model { 

    use ModelPossuiLogs;
    
    protected $table = 'oft_ceratometria';
    protected $primaryKey = 'cd_ceratometria';

    protected $fillable = [
        'cd_ceratometria',
        'cd_agendamento',
        'cd_profissional',
        'dt_exame',
        'cd_usuario_exame',
        'dt_cad_exame',
        'dt_liberacao',
        'cd_usuario_liberacao',
        'dt_cad_liberacao',
        'od_curva1_ceratometria',
        'od_curva1_milimetros',
        'od_eixo1_ceratometria',
        'od_curva2_ceratometria',
        'od_curva2_milimetros',
        'od_eixo2_ceratometria',
        'od_media_ceratometria',
        'od_media_milimetros',
        'od_cilindro_neg',
        'od_eixo_neg',
        'od_cilindro_pos',
        'od_eixo_pos',
        'oe_curva1_ceratometria',
        'oe_curva1_milimetros',
        'oe_eixo1_ceratometria',
        'oe_curva2_ceratometria',
        'oe_curva2_milimetros',
        'oe_eixo2_ceratometria',
        'oe_media_ceratometria',
        'oe_media_milimetros',
        'oe_cilindro_neg',
        'oe_eixo_neg',
        'oe_cilindro_pos',
        'oe_eixo_pos',
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
