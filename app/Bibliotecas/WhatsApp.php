<?php

namespace App\Bibliotecas;

use App\Model\rpclinica\Empresa;
use Exception;
use Illuminate\Support\Facades\Auth;
use PHPMailer\PHPMailer\PHPMailer;

use stdClass;

class WhatsApp
{
  public $key;
  public $server;
  public $from;
  private $header = array();
  private $parth;
  private $method;
  private $body;

  public function __construct($key = null)
  {
    $this->server = 'https://us.api-wa.me'; 
    if (isset($key)) {
      $this->key = $key;
    } 
  }

  private function request()
  {
    try {
      $this->header[] = 'Content-Type: application/json';
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $this->server . $this->parth);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);
 
      $protocol = (isset($_SERVER['HTTP_CF_VISITOR'])) ? 'https' : 'http';
      if($protocol=='http'){
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      }
      
      if ($this->method === 'POST' || $this->method === 'PUT') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->body);
      }
      curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
      $result = curl_exec($ch);
      if (curl_errno($ch)) {
        $errorMessage = curl_error($ch);
        curl_close($ch);
        throw new \RuntimeException("cURL Error: $errorMessage");
      }
      curl_close($ch);
      return $result;
    } catch (\Throwable $th) {
      return null;
    }
  }

 
  public function connect()
  {
    // Define o caminho, método e corpo da requisição para obter o código QR em HTML.
    $this->parth = "/{$this->key}/instance";
    $this->method = "POST";
    // Executa a requisição e retorna o resultado.
    return $this->request();
  }
 
  public function inforInstance()
  {
    // Define o caminho, método e corpo da requisição para obter informações sobre a instância.
    $this->parth = "/{$this->key}/instance";
    $this->method = "GET";
    // Executa a requisição e retorna o resultado.
    return $this->request();
  }
 
  public function listContacts()
  {
    // Define o caminho, método e corpo da requisição para listar os contatos.
    $this->parth = "/{$this->key}/contacts";
    $this->method = "GET";
    // Executa a requisição e retorna o resultado.
    return $this->request();
  }
  
  public function updateProfilePicture(string $url)
  {
    // Define o caminho, método e corpo da requisição para atualizar a imagem do perfil.
    $this->parth = "/{$this->key}/actions/picture";
    $this->method = "PUT";
    $this->body = json_encode(["url" => $url]);
    // Executa a requisição e retorna o resultado.
    return $this->request();
  }
 
  public function sendText(string $to, string $text)
  {
    // Define o corpo da requisição para enviar uma mensagem de texto.
    $this->parth = "/{$this->key}/message/text";
    $this->method = "POST";
    $this->body = json_encode([
      "to" => $to,
      "text" => $text
    ]);
    // Executa a requisição e retorna o resultado.
    return $this->request();
  }

 
 
 
  public function sendDocument($dados)
  {
    
    // Define o corpo da requisição para enviar uma mensagem de mídia.
    $this->parth = "/{$this->key}/message/document";
 
    $this->method = "POST";
    
    $this->body = json_encode([
      "to" => $dados['to'],
      "url" => $dados['url'],
      "caption" => $dados['caption'],
      "mimetype" => $dados['mimetype'],
      "fileName" => $dados['fileName'],
    ]);
    
    return $this->request();
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

