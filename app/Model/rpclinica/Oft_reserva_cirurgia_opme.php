<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model; 

class Oft_reserva_cirurgia_opme extends Model {

    protected $table = 'oft_reserva_cirurgia_opme';
    protected $primaryKey = 'cd_reserva_cirurgia_opme';

    protected $fillable = [
        'cd_reserva_cirurgia_opme',
        'cd_reserva_cirurgia',
        'cd_produto',     
        'cd_usuario', 
        'created_at',
        'updated_at', 
    ];

    public function reserva() {
        return $this->hasOne(Oft_reserva_cirurgia::class, 'cd_reserva_cirurgia', 'cd_reserva_cirurgia');
    }
 
    public function produtos() {
        return $this->hasOne(Produto::class, 'cd_produto', 'cd_produto');
    }
}
