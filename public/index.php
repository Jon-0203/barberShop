<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\AdminController;
use Controllers\ApiController;
use Controllers\CitaController;
use Controllers\LogController;
use Controllers\ServicioController;
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
$router->get('/admin', [AdminController::class, 'index']);

//API DE CITASñ
$router->get('/api/servicios', [ApiController::class, 'index']);
$router->post('/api/citas', [ApiController::class, 'guardar']);
//$router->post('/api/eliminar', [ApiController::class, 'eliminar']);

//CRUD DE SERVICIOS
$router->get('/servicios', [ServicioController::class, 'index']);
$router->get('/servicios/crear', [ServicioController::class, 'crear']);
$router->post('/servicios/crear', [ServicioController::class, 'crear']);
$router->get('/servicios/actualizar', [ServicioController::class, 'actualizar']);
$router->post('/servicios/actualizar', [ServicioController::class, 'actualizar']);
$router->post('/servicios/eliminar', [ServicioController::class, 'eliminar']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();