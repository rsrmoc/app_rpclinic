<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class CBOs extends Model
{
    protected $table = 'cbos';
    protected $primaryKey = 'cd_cbos';

    protected $fillable = [
        'nm_cbos'
    ];
}
