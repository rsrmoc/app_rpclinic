<?php

namespace App\Http\Controllers\rpclinica\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Bibliotecas\EnvioEmail;
use App\Model\rpclinica\EmpresaEmail;
use App\Model\rpclinica\Usuario;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{


    use AuthenticatesUsers; 
    
    protected $redirectTo = '/rpclinica/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function showLoginForm()
    {
        return view('rpclinica.login.login');
    }

    public function valida(Request $request)
    {
        Session::put('setar_perfil', array()); 
        return $this->login($request);

    }

  

    public function logout(Request $request)
    {
       
        Session::put('setar_perfil', null);  
        Session::put('perfil', []); 
        Session::put('perfil_menu', []); 
        Session::put('perfil_sub_menu', []); 
        Session::put('nao_controla_rota', []);  

        Auth::guard('rpclinica','faturamento')->logout(); 
        return $this->loggedOut($request);
    }

    public function esqueci(Request $request)
    {
        return view('rpclinica.login.esqueci');
    }

    public function esqueci_email(Request $request)
    {
 
        $User=Usuario::whereRaw("email='".$request['email']."'")->first();
        if(isset($User['email'])){

            if($User['email']){ 

                $Emp = EmpresaEmail::find('EMAIL');
                
                $DadosEmail['sn_anexo']='N';
                $DadosEmail['anexo']=null;
                $DadosEmail['arquivo_anexo']=null;
                $DadosEmail['email']=$User['email']; 
                $DadosEmail['FromName']=$Emp->nome;
                $DadosEmail['assunto']=$Emp->assunto_esqueci;
                $senha = rand(111111, 999999);
                $User->update(array('password'=>Hash::make($senha),'resetar_senha'=>'S'));

                $DadosEmail['conteudo']='
                <style type="text/css">
                .center {
                    text-align: left;
                }
                </style>
                <table width="70%" border="0" align="center" cellpadding="0" cellspacing="0">
                   
                  <tr> 
                    <td>
                    <div id=":on" class="a3s aiL " style="margin-left:20px;">
                    Olá <a href="mailto:rsrmoc@gmail.com" target="_blank">'.$User['email'].'</a>,<br />
                      <br />
                      Nós recebemos uma solicitação para criação de um acesso na RPclinic.<br />
                  <br />
                      Seu usuário de acesso  é: '.$User['email'].'<br />
                      Sua senha provisória  é: '.$senha.'<br />
                      Link de Acesso é: <a href="'.$Emp->link.'">'.$Emp->link.'</a> <br /> 
                  <br />
                      Se você não solicitou este código, poderá ignorar com segurança este email. Outra pessoa pode ter digitado seu endereço de email por engano.<br />
                  <br />
                      Obrigado,<br />
                    Equipe de contas da RPclinic<br /></div>
                    <img height="80" src="https://rpclinic.rpsys.com.br/assets/images/logo_rpclinic.jpg" />
                    
                    </td>
                  </tr>
                </table>
                '; 
                $email = new EnvioEmail();
                if($email->enviar_email($DadosEmail)==true){
                    echo "Enviado";
                    dd("Enviado");
                    return redirect()->route('login')->with('success', 'Email enviado com sucesso!');
                }else{
                    dd("Não Enviado");
                    return redirect()->back()->withInput()->withErrors(['error' => 'Erro no envio do e-mail!']);
                }
            }else{
                dd("Erro");
                return redirect()->back()->withInput()->withErrors(['error' => 'Erro no envio do e-mail!']);
            }
        }else{
            return redirect()->back()->withInput()->withErrors(['error' => 'Erro no envio do e-mail!']);
        }

       // return view('rpclinica.login.esqueci');
    }

    protected function loggedOut(Request $request)
    {
        return redirect()->route('rpclinica.login');
    }

    protected function guard()
    { 
        return Auth::guard('rpclinica');
    }

    protected function authenticated(Request $request, $user)
    {
        //HelperSessionUsusario($request['email']);
    }
}
