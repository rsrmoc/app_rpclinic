<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class AgendamentoItensHist extends Model
{

    protected $table = 'agendamento_item_hist';
    protected $primaryKey = 'cd_agendamento_item_hist';

    protected $fillable = [
        'cd_agendamento_item_hist',
        'cd_agendamento_item',
        'ds_historico',
        'cd_usuario', 
        'cd_whast_send',
        'sn_laudo',
        'laudo',
        'created_at',
        'updated_at'
    ];

    public function item() {
        return $this->hasOne(AgendamentoItens::class, "cd_agendamento_item", "cd_agendamento_item");
    }

    public function usuario() {
        return $this->hasOne(Usuario::class, 'cd_usuario', 'cd_usuario');
    }

}
