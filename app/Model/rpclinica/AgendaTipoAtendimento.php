<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class AgendaTipoAtendimento extends Model
{
    protected $table = 'agenda_tipo_atendimento';
    protected $primaryKey = 'cd_agenda_tipo_atendimento';

    protected $fillable = [
        'cd_agenda_tipo_atendimento',
        'cd_escala',
        'cd_agenda',
        'cd_tipo_atendimento',
        'created_at',
        'updated_at'
    ];

    public function tipo_atend() {
        return $this->hasOne(TipoAtendimento::class, 'cd_tipo_atendimento', 'cd_tipo_atendimento');
    }
}
