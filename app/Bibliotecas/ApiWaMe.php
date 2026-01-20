<?php

namespace App\Bibliotecas;

use App\Model\rpclinica\Empresa;
use Exception;
use Illuminate\Support\Facades\Auth;
use PHPMailer\PHPMailer\PHPMailer;


class ApiWaMe {
   
   public $link = 'https://us.api-wa.me/';

   public function getInstance($key,$metod) {
      try {
         
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $this->link.$key.'/instance' );
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $metod);
         curl_setopt($ch, CURLOPT_POST, 1);
         curl_setopt($ch, CURLOPT_POSTFIELDS,  null);
         curl_setopt($ch, CURLOPT_HTTPHEADER, array() );

         $protocol = (isset($_SERVER['HTTP_CF_VISITOR'])) ? 'https' : 'http';
         if($protocol=='http'){
           curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
           curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
         }

         $result = curl_exec($ch);
         curl_close($ch);
         
         return ['retorno'=>true,'dados'=>$result];

      } catch (Exception $e) {

         return ['retorno'=>false,'dados'=>$e];

      }

   }

   function getDesconectar($key)
   {

      try {

         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $this->link . $key . '/instance');
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
         curl_setopt($ch, CURLOPT_POST, 1);
         curl_setopt($ch, CURLOPT_POSTFIELDS,  null);
         curl_setopt($ch, CURLOPT_HTTPHEADER, array() ); 

         $protocol = (isset($_SERVER['HTTP_CF_VISITOR'])) ? 'https' : 'http';
         if($protocol=='http'){
           curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
           curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
         }

         $result = curl_exec($ch); 
         curl_close($ch); 
         return ['retorno'=>true,'dados'=>$result];

      } catch (Exception $e) {

         return ['retorno'=>false,'dados'=>$e];

      }

   }

   public function getRegistered($number) {

      try {
         
         
         $empresa = Empresa::find(Auth::user()->cd_empresa);
 

         if($empresa->key_whast){ 
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->link.$empresa->key_whast.'/actions/registered?number=55'.$number );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,  null);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array() );
   
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
          

      } catch (Exception $e) {

         return ['retorno'=>false,'dados'=>$e];

      }

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

   public function sendTextMessage($number,$Texto,$empresa=null) {
      
      try {
 
         if(empty($empresa)){
            $empresa=Auth::user()->cd_empresa;
         }else{
            if(!isset($empresa->cd_empresa)){
               $empresa = Empresa::find($empresa);
            }
         }
           
         $body['to']=$number;
         $body['text']=$Texto;
  

         if($empresa->key_whast){ 
             
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $this->link.$empresa->key_whast.'/message/text' );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','application/json; charset=utf-8') );

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

      } catch (Exception $e) {

         return ['retorno'=>false,'dados'=>$e];

      }

   }

   public function sendDocumentoMessage($body,$codEmpresa) {

      try {
  
         $empresa = Empresa::find($codEmpresa); 
          
         if($empresa->key_whast){ 
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $this->link.$empresa->key_whast.'/message/document' );
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

      } catch (Exception $e) {
 
         return ['retorno'=>false,'dados'=>$e];

      }

   }
 
   public function sendMediaBase64($number,$Texto,$base64,$type,$empresa=null) {
     
      try {

         if(empty($empresa)){
            if(Auth::user()->cd_empresa){ 
               $empresa = Empresa::find(Auth::user()->cd_empresa);
            } 
         }else{
            if(!isset($empresa->cd_empresa)){
               $empresa = Empresa::find($empresa);
            }
         }
     
         $body['to']=$number;
         $body['base64']= $base64;
         $body['caption']=$Texto;
  

         if($empresa->key_whast){ 
            
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $this->link.$empresa->key_whast.'/message/base64/image' );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','application/json; charset=utf-8') );

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

      } catch (Exception $e) {

         return ['retorno'=>false,'dados'=>$e];

      }

   }


   public function sendDocumentoBase64($number,$Texto,$base64,$type,$empresa=null) {
     
      try {

         if(empty($empresa)){
            $empresa=Auth::user()->cd_empresa;

         } 
         $empresa = Empresa::find($empresa);
         
         $body['to']=$number;
         $body['base64']= ($base64);
         $body['caption']=$Texto;
         $body['mimetype']=$type;
         $body['fileName']='Documento.pdf';
     
         if($empresa->key_whast){ 
         
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $this->link.$empresa->key_whast.'/message/base64/document' );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','application/json; charset=utf-8') );

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

      } catch (Exception $e) {

         return ['retorno'=>false,'dados'=>$e];

      }

   }

   public function inforInstance($key_whast) {
 
      try {

         if(($key_whast)){   
               // Executa a requisição e retorna o resultado.  
               return  $this->getInstance($key_whast,"GET");
         }
     
      } catch (Exception $e) {

         return ['retorno'=>false,'dados'=>$e];

      }
   }

   public function listMessage($number,$empresa=null) {
      
      try {
 
         if(empty($empresa)){
            $empresa=Auth::user()->cd_empresa;
         }else{
            if(!isset($empresa->cd_empresa)){
               $empresa = Empresa::find($empresa);
            }
         }
              
         if($empresa->key_whast){ 
          
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $this->link.$empresa->key_whast.'/message?phoneNumber='.$number );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','application/json; charset=utf-8') );

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

      } catch (Exception $e) {

         return ['retorno'=>false,'dados'=>$e];

      }

   }

}

