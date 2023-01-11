<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\CitaController;
use Controllers\LogController;
use MVC\Router;

$router = new Router();
//Aqui se colocan todas las rutas

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
$router->get('/crear-cuenta', [LogController::class, 'crear']);
$router->post('/crear-cuenta', [LogController::class, 'crear']);

//confirmar cuenta
$router->get('/confirmar-cuenta', [LogController::class, 'confirmar']);
$router->get('/mensaje', [LogController::class, 'mensaje']);

//Area privada
$router->get('/cita', [CitaController::class, 'index']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();