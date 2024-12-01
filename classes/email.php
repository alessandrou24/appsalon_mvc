<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email{
    public $email;
    public $nombre;
    public $token;
    public function __construct($email, $nombre, $token){
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion(){
        // Looking to send emails in production? Check out our Email API/SMTP product!
        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->Host = $_ENV['EMAIL_HOST'];
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = $_ENV['EMAIL_PORT'];
        $phpmailer->Username = $_ENV['EMAIL_USER'];
        $phpmailer->Password = $_ENV['EMAIL_PASS'];
        $phpmailer->setFrom('appsalon@ejemplo.com');
        $phpmailer->addAddress('appsalon@ejemplo.com', 'Appsalon.com');
        $phpmailer->Subject = 'Confirma tu cuenta';
        
        $phpmailer->isHTML(TRUE);
        $phpmailer->CharSet="UTF-8";
       
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola ".  $this->nombre . ".</strong>Confirma tu cuenta</p>";
        $contenido .= "<p>Presiona aquí: <a href='" . $_ENV["APP_URL"] . "/confirmar-cuenta?token=".$this->token. "'>Confirmar</a><p>";
        $contenido .= "<p>Si tu no solicistaste esta cuenta, ignora este mensaje </p>";
        $contenido .= "</html>";
        $phpmailer->Body = $contenido;

        $phpmailer->send();


    }

    public function enviarInstrucciones(){
        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->Host = $_ENV['EMAIL_HOST'];
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = $_ENV['EMAIL_PORT'];
        $phpmailer->Username = $_ENV['EMAIL_USER'];
        $phpmailer->Password = $_ENV['EMAIL_PASS'];
        $phpmailer->setFrom('appsalon@ejemplo.com');
        $phpmailer->addAddress('appsalon@ejemplo.com', 'Appsalon.com');
        $phpmailer->Subject = 'Resetea tu contraseña';
        
        $phpmailer->isHTML(TRUE);
        $phpmailer->CharSet="UTF-8";
       
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola ".  $this->nombre . ".</strong>Has solicitado resetear tu contraseña</p>";
        $contenido .= "<p>Presiona aquí: <a href='" . $_ENV["APP_URL"] . "/recuperar?token=".$this->token. "'>Resetear</a><p>";
        $contenido .= "<p>Si tu no solicistaste esta cuenta, ignora este mensaje </p>";
        $contenido .= "</html>";
        $phpmailer->Body = $contenido;

        $phpmailer->send();
    }
}