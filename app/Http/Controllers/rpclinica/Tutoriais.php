<?php

namespace App\Http\Controllers\rpclinica;
 
 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\Agendamento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http; 
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class Tutoriais extends Controller
{
 
    public function index(Request $request)
    {     
 
      return view('rpclinica.tutoriais.tela');
  
    }
}
 
