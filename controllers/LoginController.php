<?php 

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login(Router $router){
        $usuario = new Usuario;
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarLogin();

            if(empty($alertas)){
                $usuario = Usuario::where('email', $usuario->email);
                if(!$usuario || !$usuario->confirmado){
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }else{
                    if(password_verify($_POST['password'], $usuario->password)){
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;
                        
                        header('Location: /dashboard');
                    }else{
                        Usuario::setAlerta('error', 'Contraseña incorrecta');
                    }
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesion',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
    public static function logout(){
        session_start();
        $_SESSION = [];

        header('Location: /');
    }

    public static function crear(Router $router){

        $usuario = new Usuario;
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarCuentaNueva();
            if(empty($alertas)){
                $existeUsuario = Usuario::where('email', $usuario->email);
                if($existeUsuario){
                    Usuario::setAlerta('error', 'El usuario ya existe');
                    $alertas = Usuario::getAlertas();
                }else{
                    // HASHEAR PASSWORD
                    $usuario->passwordHash();

                    //Eliminar password2
                    unset($usuario->password2);

                    // Crear Token
                    $usuario->crearToken();

                    // Enviar Correo de Confirmacion
                    $mail = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $mail->enviarConfirmacion();

                    // Crear usuario
                    $resultado = $usuario->guardar();

                    if($resultado){
                        header('Location: /mensaje');
                    }

                }
            }

        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/crear', [
            'titulo' => 'Crea tu Cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function olvide(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $usuario = Usuario::where('email', $_POST['email']);
            //debuguear($usuario);
            if(!$usuario || $usuario->confirmado !== '1'){
                Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
            }else{
                // token
                $usuario->crearToken();

                // Eliminar a Password 2
                unset($usuario->password2);

                // Enviar Correo de Confirmacion
                $mail = new Email($usuario->email, $usuario->nombre, $usuario->token);
                $mail->olvidePass();

                // Crear usuario
                $resultado = $usuario->guardar();

                if($resultado){
                    Usuario::setAlerta('exito', 'Las instrucciones fueron enviadas al correo');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide', [
            'titulo' => 'Olvide Mi Contraseña',
            'alertas' => $alertas
        ]);
    }
    public static function reestablecer(Router $router){
        $token = s($_GET['token']);
        $alertas = [];
        $error = true;

        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error', 'El Token no es Valido');
            $error = false;
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $password = new Usuario($_POST);
            $alertas = $password->validarPass();

            if(empty($alertas)){
                $usuario->password = $password->password;
                $usuario->passwordHash();

                unset($usuario->password2);

                $usuario->token = '';

                $resultado = $usuario->guardar();

                if($resultado){
                    $error = false;
                    Usuario::setAlerta('exito', 'Acceso recuperado, inicie sesion');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer Contraseña',
            'error' => $error,
            'alertas' => $alertas
        ]);
    }
    public static function mensaje(Router $router){
        
        $router->render('auth/mensaje', [
            'titulo' => 'Mensaje de Confirmación'
        ]);

    }
    public static function confirmar(Router $router){
        $token = s($_GET['token']);
        $alertas = [];

        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no valido o Usuario ya verificado');
        }else{
            $usuario->confirmado = 1;
            $usuario->token = '';

            $usuario->guardar();
            Usuario::setAlerta('exito', 'Usuario confirmado correctamente');
        }
        
        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar', [
            'titulo' => 'Cuenta Confirmada',
            'alertas' => $alertas
        ]);

    }
}