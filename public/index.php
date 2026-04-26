<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\DashboardController;
use Controllers\LoginController;
use Controllers\TareaController;
use MVC\Router;
$router = new Router();

// PAGINAS LOGIN
$router->get('/', [LoginController::class, 'login']);
$router->post('/', [LoginController::class, 'login']);

// PAGINAS LOGOUT 
$router->get('/logout', [LoginController::class, 'logout']);

//PAGINAS CREAR
$router->get('/crear', [LoginController::class, 'crear']);
$router->post('/crear', [LoginController::class, 'crear']);

// OLVIDE MI PASSWORD
$router->get('/olvide', [LoginController::class, 'olvide']);
$router->post('/olvide', [LoginController::class, 'olvide']);

// CAMBIAR PASSWORD
$router->post('/reestablecer', [LoginController::class, 'reestablecer']);
$router->get('/reestablecer', [LoginController::class, 'reestablecer']);

// CONFIRMAR
$router->get('/mensaje', [LoginController::class, 'mensaje']);
$router->get('/confirmar', [LoginController::class, 'confirmar']);

// ---  PROYECTOS  ---

// DASHBOARD
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/crear-proyecto', [DashboardController::class, 'crear']);
$router->post('/crear-proyecto', [DashboardController::class, 'crear']);
$router->get('/perfil', [DashboardController::class, 'perfil']);
$router->post('/perfil', [DashboardController::class, 'perfil']);

$router->post('/eliminar-proyecto', [DashboardController::class, 'eliminar_proyecto']);

$router->get('/cambiar-password', [DashboardController::class, 'cambiar_password']);
$router->post('/cambiar-password', [DashboardController::class, 'cambiar_password']);

// Proyecto
$router->get('/proyecto', [DashboardController::class, 'proyecto']);

// API DE TAREAS
$router->get('/api/tareas', [TareaController::class, 'index']);
$router->post('/api/tarea', [TareaController::class, 'crear']);
$router->post('/api/tareas/actualizar', [TareaController::class, 'actualizar']);
$router->post('/api/tareas/eliminar', [TareaController::class, 'eliminar']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();