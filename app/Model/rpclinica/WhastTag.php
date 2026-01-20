<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class WhastTag extends Model {


    protected $table ='whast_tag';
    protected $primaryKey = 'cd_whast_tag';

    protected $fillable = [
        'cd_whast_tag',
        'tipo',
        'tag',
        'situacao', 
    ];
}
