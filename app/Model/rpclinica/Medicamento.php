<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class Medicamento extends Model
{
    // use HasFactory;

    protected $fillable = [
        'description',
        'present',
        'quantity',
        'use',
        'prescript',
        'favorite',
    ];
}

