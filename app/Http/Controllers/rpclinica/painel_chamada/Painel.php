<?php

namespace App\Http\Controllers\rpclinica\painel_chamada;
 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
 
class Painel extends Controller
{
   
    public function index(Request $request)
    { 
        
        return view('rpclinica.painel_chamada/painel' );
   
    }
 




 
}
