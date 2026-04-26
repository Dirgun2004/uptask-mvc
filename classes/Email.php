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
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'UpTask.com');
        $mail->Subject = 'Confirma tu cuenta';

        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> has creado tu cuenta en AppSalon, solo debes completar el ultimo paso y dar click en el enlace para confirmar tu registro</p>";
        $contenido .= "<p>Haz click en el siguiente enlace:<a href='http://localhost:3000/confirmar?token=" . $this->token . "'>Confirmar Cuenta</a> </p>";
        $contenido .= '<p>Si tu no solicitaste esta cuenta, ignora este mensaje</p>';
        $contenido .= "</html>";

        $mail->Body = $contenido;

        $mail->send();
    }

    public function olvidePass(){
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'UpTask.com');
        $mail->Subject = 'Recupera Tu Acceso a UpTask';

        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong>e estas intentando recuperar acceso a tu cuenta en UpTask, solo debes completar el ultimo paso y dar click en el enlace para recuperar tu acceso</p>";
        $contenido .= "<p>Haz click en el siguiente enlace:<a href='" . $_ENV['APP_URL'] ."/reestablecer?token=" . $this->token . "'>Confirmar Cuenta</a> </p>";
        $contenido .= '<p>Si tu no solicitaste la recuperacion de acceso a tu cuenta, ignora este mensaje</p>';
        $contenido .= "</html>";

        $mail->Body = $contenido;

        $mail->send(); 
    }
}