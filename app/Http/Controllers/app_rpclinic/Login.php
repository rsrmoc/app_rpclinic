<?php


namespace App\Http\Controllers\app_rpclinic;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\rpclinica\procedimento\ProcedimentosRequest;
use App\Model\rpclinica\AppConfig;
use App\Model\rpclinica\Classificacao;
use App\Model\rpclinica\Procedimento;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator; 

class Login extends Controller
{

    public function index(Request $request)
    {
        //$business = DB::connection('master')->table('empresas_app')->get();

        //echo $userName = request()->cookie('namesdsaaa');
        $business=[];   
        return view('app_rpclinic.login.login', compact('business'));
    }

    public function login(Request $request) {
        //dd($request->toArray());
     
        $validator = Validator::make($request->all(), [
            'business' => 'nullable',
            'email' => 'required|string|email',
            'password' => 'required|string|min:2',
            'remember' => 'nullable|boolean'
        ]);
        
        if ($validator->fails()) {
            dd($validator);
        }

        try {

            /*
            $business = DB::connection('master')->table('empresas_app')
                ->where('cd_empresa_app', $request->business)
                ->where('sn_ativo', 'S')
                ->first();
             
            if (!$business) throw new Exception('Empresa não encontrada!');
 
            $database = DB::connection('master')->table('databases_clients')
                ->where('database', $business->banco_dados)
                ->first();
             */

            /*
            $database = DB::connection('master')->table('databases_clients')->select('*')->where('domain', request()->getHost())->first();    
            if (!$database) throw new Exception('Banco de dados da empresa não encontrado!');

            config(['database.default' => 'mysql']);
            config(['database.connections.mysql.host' => $database->host]);
            config(['database.connections.mysql.username' => $database->username]);
            config(['database.connections.mysql.password' => $database->password]);
            config(['database.connections.mysql.database' => $database->database]);
           */
           
            $telas = AppConfig::where('id','app')->first();  
            if($telas==null){
              throw new Exception('App não configurado!');
            }   
            if($telas->app=='nao'){
              throw new Exception('Base de dados não esta permitido para esse App!');
            } 

            if (!Auth::guard('rpclinica')->attempt($request->only(['email', 'password']), ($request->remember ? true: false)))
                throw new Exception('Email ou senha inválidos!');
          
            /*    
            $database = DB::connection('master')->table('databases_clients')->select('*')->where('domain', request()->getHost())->first();                
            Cookie::queue(
                'business',
                json_encode([
                    'name' => 'App',
                    'host' => $database->host,
                    'username' => $database->username,
                    'password' => $database->password,
                    'database' => $database->database
                ]),
                2628000
            );
            */
           
            Cookie::queue(
                'business',
                json_encode([
                    'name' => 'App',
                    'host' => 'localhost',
                    'username' => 'root',
                    'password' => '',
                    'database' => 'castelo'
                ]),
                2628000
            );
             
            $user = Auth::guard('rpclinica')->user();
            $user->update(['app_name' => $telas->app_name 
            ]);

            return redirect()->route('app.inicial');
        }
        catch(Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function logout() {
        Auth::guard('rpclinica')->logout();
        Cookie::queue(Cookie::forget('business'));

        return redirect()->route('app.login');
    }
}
