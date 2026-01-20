<?php

namespace App\Http\Controllers\rpclinica;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EstoqueSaldo extends Controller
{
    public function index() {
        return view('rpclinica.estoqueSaldo.listar');
    }
}
