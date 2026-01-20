<?php

namespace App\Bibliotecas;
use PHPMailer\PHPMailer\PHPMailer;


class EnvioEmail {


	public function enviar_email($dados) {



        try {

            $mail = new PHPMailer;
            $mail->setLanguage('br');
            $mail->CharSet='UTF-8';
            $mail->SMTPDebug = 0;
            $mail->Debugoutput = 'html';

            $mail->IsSMTP(); // envia por SMTP
            $mail->SMTPAuth = true;		// Autenticaï¿½ï¿½o ativada
            $mail->Host = "ssl://smtp.gmail.com";
            $mail->Port = 465;  		// A porta 587 deverï¿½ estar aberta em seu servidor
            $mail->Username = 'info@rpsys.com.br'; // SMTP username
            $mail->Password = 'FyaMJRVk8c'; // SMTP password
            $mail->From = 'info@rpsys.com.br'; // From
            if($dados['FromName']){
               $mail->FromName = "RPclinic"; // Nome de quem envia o email
            }else{
               $mail->FromName = "RPclinic"; // Nome de quem envia o email
            }
            $mail->addAddress($dados['email']);
            // $mail->AddBCC("carolina@santacasamontesclaros.com.br", 'ANA CAROLINA');
            $mail->WordWrap = 50; // Definir quebra de linha
            $mail->IsHTML(true); // Enviar como HTML
            $mail->AltBody = "This is the text-only body"; //PlainText, para caso quem receber o email no aceite o corpo HTML



            if($dados['sn_anexo']=='S'){
               $mail->addAttachment('C:\wamp64\www\sistema_apoio\storage\app'.$dados['anexo'],$dados['arquivo_anexo']);
            }


            $mail->isHTML(true);

            $mail->Subject = $dados['assunto'];
            $mail->Body    = $dados['conteudo'];


            if( !$mail->send() ) {
                
               return false;
            } else {
               return true;
            }


        } catch (Exception $e) {
           return false;
        }


	}



}

