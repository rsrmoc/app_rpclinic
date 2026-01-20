<?php

namespace App\Bibliotecas;

use App\Model\rpclinica\Empresa;
use Exception;
use Illuminate\Support\Facades\Auth;
use PHPMailer\PHPMailer\PHPMailer;


class Kentro {
   
	public $key = "f59f332a2c5827556c9782e1c3b3c0a6"; 
	public $url = "https://ventus.atenderbem.com/";
 
   
	public function __construct(){
		set_time_limit(0);
		date_default_timezone_set('America/Sao_Paulo');
		header('Access-Control-Allow-Origin: *');
	}
	 
	public function sendWaTemplateRetorno($numero,$Template,$empresa,$variaveis=null){ 

		if(($empresa->api_whast=='kentro') and ($empresa->key_whast) and ($empresa->url_whast) and ($empresa->fila_whast) and ($empresa->whast_temp_agenda)){

		   $body['queueId']=$empresa->fila_whast;
		   $body['apiKey']=$empresa->key_whast;  
		   $body['number']=$numero; 
		   $body['templateId']=$Template;   
		   $body['data']=($variaveis) ? $variaveis : [];
	   
		   $ch = curl_init(); 
		   curl_setopt($ch, CURLOPT_URL, $empresa->url_whast . 'int/sendWaTemplate' );
		   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		   curl_setopt($ch, CURLOPT_POST, 1);
		   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
		   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json') );
	 
		   $protocol = (isset($_SERVER['HTTP_CF_VISITOR'])) ? 'https' : 'http';
		   if($protocol=='http'){
			  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		   }
		      
		   $result = curl_exec($ch);
		   curl_close($ch);

		   return ['retorno'=>true,'dados'=>$result];
		   
		}else{
  
		   return ['retorno'=>false,'dados'=>' Sistema não configurado!'];
  
		}   
	}

	public function sendWaTemplate($numero,$variaveis,$empresa){ 

		if(($empresa->api_whast=='kentro') and ($empresa->key_whast) and ($empresa->url_whast) and ($empresa->fila_whast) and ($empresa->whast_temp_agenda)){

		   $body['queueId']=$empresa->fila_whast;
		   $body['apiKey']=$empresa->key_whast;  
		   $body['number']=$numero; 
		   $body['templateId']=$empresa->whast_temp_agenda;   
		   $body['data']=$variaveis;
	   
		   $ch = curl_init(); 
		   curl_setopt($ch, CURLOPT_URL, $empresa->url_whast . 'int/sendWaTemplate' );
		   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		   curl_setopt($ch, CURLOPT_POST, 1);
		   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
		   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json') );
	 
		   $protocol = (isset($_SERVER['HTTP_CF_VISITOR'])) ? 'https' : 'http';
		   if($protocol=='http'){
			  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		   }
		      
		   $result = curl_exec($ch);
		   curl_close($ch);

		   return ['retorno'=>true,'dados'=>$result];
  
		}else{
  
		   return ['retorno'=>false,'dados'=>' Sistema não configurado!'];
  
		}
	}
	
	public function enqueueMessageToSend($numero,$texto,$CodEmpresa,$xData=null,$empresa=null){ 
	    
		if(empty($empresa)){
			$empresa = Empresa::find($CodEmpresa);
		}
		
		if(($empresa->api_whast=='kentro') and ($empresa->key_whast) and ($empresa->url_whast) and ($empresa->fila_whast)){
		
		   $body['queueId']=$empresa->fila_whast;
		   $body['apiKey']=$empresa->key_whast;  
		   $body['number']=$numero; 
		   $body['text']=$texto;  
		   $body['extData']=$xData;
	 
		   $ch = curl_init(); 
		   curl_setopt($ch, CURLOPT_URL, $empresa->url_whast . 'int/enqueueMessageToSend' );
		   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		   curl_setopt($ch, CURLOPT_POST, 1);
		   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
		   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json') );
	 
		   $protocol = (isset($_SERVER['HTTP_CF_VISITOR'])) ? 'https' : 'http';
		   if($protocol=='http'){
			  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		   }
		      
		   $result = curl_exec($ch);
		   curl_close($ch);
	   
		   return ['retorno'=>true,'dados'=>$result];
  
		}else{
  
		   return ['retorno'=>false,'dados'=>' Sistema não configurado!'];
  
		}
  
	}
 
	public function getQueueQrCode(){ 
	  
		$empresa = Empresa::find(Auth::user()->cd_empresa);
		if(($empresa->api_whast=='kentro') and ($empresa->key_whast) and ($empresa->url_whast) and ($empresa->fila_whast)){
		
		   $body['queueId']=$empresa->fila_whast;
		   $body['apiKey']=$empresa->key_whast;  
	 
		   $ch = curl_init(); 
		   curl_setopt($ch, CURLOPT_URL, $empresa->url_whast . 'int/getQueueQrCode' );
		   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		   curl_setopt($ch, CURLOPT_POST, 1);
		   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
		   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json') );
	 
		   $protocol = (isset($_SERVER['HTTP_CF_VISITOR'])) ? 'https' : 'http';
		   if($protocol=='http'){
			  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		   }
		      
		   $result = curl_exec($ch);
		   curl_close($ch);
	   
		   return ['retorno'=>true,'dados'=>$result];
  
		}else{
  
		   return ['retorno'=>false,'dados'=>' Sistema não configurado!'];
  
		}
  
	}
 
	public function enableQueue(){ 
	  
		$empresa = Empresa::find(Auth::user()->cd_empresa);
		if(($empresa->api_whast=='kentro') and ($empresa->key_whast) and ($empresa->url_whast) and ($empresa->fila_whast)){
		
		   $body['queueId']=$empresa->fila_whast;
		   $body['apiKey']=$empresa->key_whast;  
	 
		   $ch = curl_init(); 
		   curl_setopt($ch, CURLOPT_URL, $empresa->url_whast . 'int/enableQueue' );
		   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		   curl_setopt($ch, CURLOPT_POST, 1);
		   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
		   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json') );
	 
		   $protocol = (isset($_SERVER['HTTP_CF_VISITOR'])) ? 'https' : 'http';
		   if($protocol=='http'){
			  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		   }
		      
		   $result = curl_exec($ch);
		   curl_close($ch);
	   
		   return ['retorno'=>true,'dados'=>$result];
  
		}else{
  
		   return ['retorno'=>false,'dados'=>' Sistema não configurado!'];
  
		}
  
	}

	public function connectQueue(){ 
	  
		$empresa = Empresa::find(Auth::user()->cd_empresa);
		if(($empresa->api_whast=='kentro') and ($empresa->key_whast) and ($empresa->url_whast) and ($empresa->fila_whast)){
		
		   $body['queueId']=$empresa->fila_whast;
		   $body['apiKey']=$empresa->key_whast;  
	 
		   $ch = curl_init(); 
		   curl_setopt($ch, CURLOPT_URL, $empresa->url_whast . 'int/connectQueue' );
		   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		   curl_setopt($ch, CURLOPT_POST, 1);
		   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
		   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json') );
	 
		   $protocol = (isset($_SERVER['HTTP_CF_VISITOR'])) ? 'https' : 'http';
		   if($protocol=='http'){
			  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		   }
		      
		   $result = curl_exec($ch);
		   curl_close($ch);
	   
		   return ['retorno'=>true,'dados'=>$result];
  
		}else{
  
		   return ['retorno'=>false,'dados'=>' Sistema não configurado!'];
  
		}
  
	}
	
	public function getQueueStatus(){ 
	  
		$empresa = Empresa::find(Auth::user()->cd_empresa);
		if(($empresa->api_whast=='kentro') and ($empresa->key_whast) and ($empresa->url_whast) and ($empresa->fila_whast)){
		
		   $body['queueId']=$empresa->fila_whast;
		   $body['apiKey']=$empresa->key_whast;  
	 
		   $ch = curl_init(); 
		   curl_setopt($ch, CURLOPT_URL, $empresa->url_whast . 'int/getQueueStatus' );
		   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		   curl_setopt($ch, CURLOPT_POST, 1);
		   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
		   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','charset=utf-8') );
	 
		   $protocol = (isset($_SERVER['HTTP_CF_VISITOR'])) ? 'https' : 'http';
		   if($protocol=='http'){
			  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		   }
		      
		   $result = curl_exec($ch);
		   curl_close($ch);
	   
		   return ['retorno'=>true,'dados'=>$result];
  
		}else{
  
		   return ['retorno'=>false,'dados'=>' Sistema não configurado!'];
  
		}

		 
	}
	 
	public function logoutQueue(){ 
	  
		$empresa = Empresa::find(Auth::user()->cd_empresa);
		if(($empresa->api_whast=='kentro') and ($empresa->key_whast) and ($empresa->url_whast) and ($empresa->fila_whast)){
		
		   $body['queueId']=$empresa->fila_whast;
		   $body['apiKey']=$empresa->key_whast;  
	 
		   $ch = curl_init(); 
		   curl_setopt($ch, CURLOPT_URL, $empresa->url_whast . 'int/logoutQueue' );
		   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		   curl_setopt($ch, CURLOPT_POST, 1);
		   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
		   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json') );
	 
		   $protocol = (isset($_SERVER['HTTP_CF_VISITOR'])) ? 'https' : 'http';
		   if($protocol=='http'){
			  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		   }
		      
		   $result = curl_exec($ch);
		   curl_close($ch);
	   
		   return ['retorno'=>true,'dados'=>$result];
  
		}else{
  
		   return ['retorno'=>false,'dados'=>' Sistema não configurado!'];
  
		}
  
	}

   public function checkIfUserExists($numero){ 
	  
      $empresa = Empresa::find(Auth::user()->cd_empresa);
      if(($empresa->api_whast=='kentro') and ($empresa->key_whast) and ($empresa->url_whast) and ($empresa->fila_whast)){
      
         $body['queueId']=$empresa->fila_whast;
         $body['apiKey']=$empresa->key_whast; 
         $body['number']=$numero; 
         $body['country']='BR'; 
   
         $ch = curl_init(); 
         curl_setopt($ch, CURLOPT_URL, $empresa->url_whast . 'int/checkIfUserExists' );
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
         curl_setopt($ch, CURLOPT_POST, 1);
         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
         curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','charset=utf-8') );
   
         $protocol = (isset($_SERVER['HTTP_CF_VISITOR'])) ? 'https' : 'http';
         if($protocol=='http'){
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
         }
   
         $result = curl_exec($ch);
         curl_close($ch);
     
         return ['retorno'=>true,'dados'=>$result];

      }else{

         return ['retorno'=>false,'dados'=>' Sistema não configurado!'];

      }
       

	}

	public function getEtiquetas(){ 
	  
		$body['queueId']=11;
		$body['apiKey']=$this->key; 

		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $this->url . 'int/getTags' );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','charset=utf-8') );

		$protocol = (isset($_SERVER['HTTP_CF_VISITOR'])) ? 'https' : 'http';
		if($protocol=='http'){
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		}

		$result = curl_exec($ch);
		curl_close($ch);
      return ['retorno'=>true,'dados'=>$result];
		 
	}

	public function createUsuario($body){
	  
		$body['queueId']=11;
		$body['apiKey']=$this->key; 
  
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $this->url . 'int/addContact' );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','charset=utf-8') );

		$protocol = (isset($_SERVER['HTTP_CF_VISITOR'])) ? 'https' : 'http';
		if($protocol=='http'){
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		}

		$result = curl_exec($ch);
		curl_close($ch);
 
		return $result;
	}

	function formatPhone($phone)
	{
	   $phone = preg_replace('/[^0-9]/', '', $phone);
 
	   if(strlen($phone)==11){
		  if(substr($phone,2,1)==9){
				$phone = trim(substr($phone,0,2).substr($phone,3,10));
		  }
	   }
	   return $phone;
	}

}

