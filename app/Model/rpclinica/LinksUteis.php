<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 
use Illuminate\Support\Facades\DB; 
use Illuminate\Database\Eloquent\Builder;
use App\Model\Support\ModelPossuiLogs;

class LinksUteis extends Model
{
    use SoftDeletes;

    protected $table = 'links_uteis';
    protected $primaryKey = 'cd_link';

    protected $fillable = [
        'cd_link',
        'nm_link',
        'url_link',
        'sn_ativo',
        'cd_usuario',  
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    
 
}
