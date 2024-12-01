<?php 

require_once __DIR__ . '/../includes/app.php';

use Controller\ApiController;
use Controller\CitaController;
use Controller\LoginController;
use Controller\AdminController;
use Controller\ServicioController;
use MVC\Router;

$router = new Router();
 

// Iniciar Sesión 

$router->get('/', [LoginController::class, 'login']);
$router->post('/', [LoginController::class, 'login']);

// Cerrar Sesión
$router->get('/logout', [LoginController::class, 'logout']);


// Recuperar Contraseña
$router->get('/olvide', [LoginController::class, 'olvide']);
$router->post('/olvide', [LoginController::class, 'olvide']);
$router->get('/recuperar', [LoginController::class, 'recuperar']);
$router->post('/recuperar', [LoginController::class, 'recuperar']);


// Crear Cuenta
$router->get('/crear-cuenta', [LoginController::class, 'crear']);
$router->post('/crear-cuenta', [LoginController::class, 'crear']);

$router->get('/confirmar-cuenta', [LoginController::class, 'confirmar']);
$router->get('/mensaje', [LoginController::class, 'mensaje']);

$router->get('/cita', [CitaController::class, 'index']);
$router->get('/admin', [AdminController::class, 'index']);


$router->get('/api/servicios', [ApiController::class, 'index']);
$router->post('/api/citas', [ApiController::class, 'guardar']);
$router->post('/api/eliminar', [ApiController::class, 'eliminar']);

$router->get('/servicios', [ServicioController::class, 'index']);

$router->get('/servicios/crear', [ServicioController::class, 'crear']);
$router->post('/servicios/crear', [ServicioController::class, 'crear']);

$router->get('/servicios/actualizar', [ServicioController::class, 'actualizar']);
$router->post('/servicios/actualizar', [ServicioController::class, 'actualizar']);

$router->post('/servicios/eliminar', [ServicioController::class, 'eliminar']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();