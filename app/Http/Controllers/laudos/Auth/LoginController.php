<?php

namespace App\Http\Controllers\laudos\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Bibliotecas\EnvioEmail;
use App\Model\laudos\UsuarioLaudoLog;
use App\Model\rpclinica\EmpresaEmail;
use App\Model\rpclinica\Usuario;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{


    use AuthenticatesUsers; 
    
    protected $redirectTo = '/laudos/';

    public function __construct()
    {
        $this->middleware('guest:laudos')->except('logout');
    }


    public function showLoginForm()
    {
        return view('laudos.login.login');
    }

    public function valida(Request $request)
    { 
        
        return $this->login($request);

    }

    public function login(Request $request)
    {
        
        $this->validateLogin($request);
          
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
         
        if ($this->attemptLogin($request)) {
           
            return $this->sendLoginResponse($request);
        }
  
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function attemptLogin(Request $request)
    {

       
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    protected function validateLogin(Request $request)
    { 
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard('laudos')->user())) {
            return $response;
        }
        //dd('252');
        return redirect()->route('laudos.inicial');
    }

    public function logout(Request $request)
    {
        
        Auth::guard('laudos')->logout(); 
        
        return $this->loggedOut($request);
    }

    public function esqueci(Request $request)
    {
        return view('laudos.login.esqueci');
    }

    public function username()
    {
        return 'cd_usuario';
    }

    protected function loggedOut(Request $request)
    {
        return redirect()->route('laudos.login');
    }

    protected function guard()
    { 
        return Auth::guard('laudos');
    }

    protected function authenticated(Request $request, $user)
    {
        UsuarioLaudoLog::create([
            'cd_usuario'=> '',
            'ip'=> $_SERVER['SERVER_ADDR']
        ]);
    }
}
