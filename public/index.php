<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\LogController;
use MVC\Router;

$router = new Router();

//Iniciar sesión
$router->get('/', [LogController::class, 'login']);
$router->post('/', [LogController::class, 'login']);

//Cerrar sesion
$router->get('/logout', [LogController::class, 'logout']);

//Recupero Password
$router->get('/olvide', [LogController::class, 'olvide']);
$router->post('/olvide', [LogController::class, 'olvide']);
$router->get('/recuperar', [LogController::class, 'recuperar']);
$router->post('/recuperar', [LogController::class, 'recuperar']);

//Crear cuenta
$router->get('/crear_cuenta', [LogController::class, 'crear']);
$router->post('/crear_cuenta', [LogController::class, 'crear']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();