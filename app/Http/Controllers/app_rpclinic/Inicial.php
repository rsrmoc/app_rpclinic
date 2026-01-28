<?php


namespace App\Http\Controllers\app_rpclinic;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\rpclinica\procedimento\ProcedimentosRequest;
use App\Model\rpclinica\Classificacao;
use App\Model\rpclinica\Procedimento;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;

class Inicial extends Controller
{

    public function index(Request $request)
    {
 
       return view('app_rpclinic.inicial.inicial');
    }

    public function permission(Request $request)
    {
        $attemptedUrl = $request['path'] ?? 'erro desconhecido'; 
        return view('app_rpclinic.permission.permission', [
            'attemptedUrl' => $attemptedUrl
        ]);
    }

    public function notFound(Request $request)
    {
        return view('app_rpclinic.errors.not-found');
    }
}
