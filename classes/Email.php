<?php

namespace Classes;
use PHPMailer\PHPMailer\PHPMailer;

class Email {

    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {
        $this -> email = $email;
        $this -> nombre = $nombre;
        $this -> token = $token;
    }

    public function enviarConfirmacion() {
        //Creamos el objeto mail

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'd6f754e8b972de';
        $mail->Password = '3fb209aba77c24';

        $mail->setFrom('cuentas@barbershop.com');
        $mail->addAddress('cuentas@barbershop.com','Barbershop.com');
        $mail->Subject = 'Confirma tu cuenta';

        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong>Has creado tu cuenta en BarberShop,
        solo debes confirmar presionandi en el siguiente enlace</p>";
        $contenido .= "<p>Presiona aqui: <a href='http://localhost:3000/confirmar-cuenta?token=" 
        . $this->token ."'>Confirmar cuenta</a></p>";
        $contenido .= "<p>Situ no solicitaste este cambio, puedes ignorar el mensaje</p>";
        $contenido .= '</html>';

        $mail->Body = $contenido;

        //Enviar mail
        $mail->send();
        
        
    }
}