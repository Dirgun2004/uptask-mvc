<?php 

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;

class DashboardController{
    public static function index(Router $router){
        session_start();
        isAuth();

        $id = $_SESSION['id'];

        $proyectos = Proyecto::belongsTo('propietarioId', $id);

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }

    // -- CREAR PROYECTO --
    public static function crear(Router $router){
        session_start();
        isAuth();
        $alertas = [];
    
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $proyecto = new Proyecto($_POST);
            $alertas = $proyecto->validarProyecto();
            if(empty($alertas)){
                $proyecto->crearUrl();
                $proyecto->propietarioId = $_SESSION['id'];
                $proyecto->guardar();

                header('Location: /proyecto?id=' . $proyecto->url);
            }
        }
        $alertas = Proyecto::getAlertas();
        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyectos',
            'alertas' => $alertas
        ]);
    }

    // En DashboardController.php
    public static function eliminar_proyecto() {
        session_start();
        isAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Leemos el ID que viene del FormData de JS
            $url = $_POST['id'];
            $proyecto = Proyecto::where('url', $url);

            if (!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                echo json_encode(['tipo' => 'error', 'mensaje' => 'No tienes permiso']);
                return;
            }

            $resultado = $proyecto->eliminar();
            echo json_encode(['resultado' => $resultado, 'tipo' => 'exito']);
        }
    }

    public static function perfil(Router $router){
        session_start();
        isAuth();
        $alertas = [];

        $usuario = Usuario::find($_SESSION['id']);

        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPerfil();

            if(empty($alertas)){
                $existeUsuario = Usuario::where('email', $usuario->email);
                if($existeUsuario && $existeUsuario->id !== $usuario->id){
                    Usuario::setAlerta('error', 'El correo pertenece a otro usuario');
                }else{
                    $usuario->guardar();
                    Usuario::setAlerta('exito', 'Datos actualizados correctamente');
                    $_SESSION['nombre'] = $usuario->nombre;
                }
            }
        }
        $alertas = Usuario::getAlertas();

        $router->render('dashboard/perfil', [
            'titulo' => 'Mi Perfil',
            'alertas' => $alertas,
            'usuario' => $usuario
        ]);
    }

    
    public static function cambiar_password(Router $router){
        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $usuario = Usuario::find($_SESSION['id']);
            $usuario->sincronizar($_POST);
            $alertas = $usuario->passwordNuevo();

            if(empty($alertas)){
               $resultado = $usuario->comprobarPassword();
               if($resultado){
                $usuario->password = $usuario->password_new;

                unset($usuario->password_old);
                unset($usuario->password_new);

                $usuario->passwordHash();

                $resultado = $usuario->guardar();

                if($resultado){
                    $alertas = Usuario::setAlerta('exito', 'Contraseña cambiada correctamente');
                }
               }else{
                $alertas = Usuario::setAlerta('error', 'Contraseña incorrecta');
               }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('dashboard/cambiar-password', [
            'titulo' => 'Cambiar Contraseña',
            'alertas' => $alertas
        ]);
    }

    public static function proyecto(Router $router){
        session_start();
        isAuth();

        $token = $_GET['id'];

        if (!$token) header('Location: /dashboard');

        $proyecto = Proyecto::where('url', $token);

        if($proyecto->propietarioId !== $_SESSION['id']) header('Location: /dashboard');


        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto
        ]);
    }

}