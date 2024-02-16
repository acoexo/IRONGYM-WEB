<?php 
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/app.php';


use MVC\Router;
use Controllers\UserController;
use Controllers\PageController;
use Controllers\LoginController;

$router = new Router();

$router->get('/', [PageController::class, 'index']);
$router->get('/propiedades/crear', [PageController::class, 'crear']);
$router->post('/propiedades/crear', [PageController::class, 'crear']);
$router->get('/propiedades/actualizar', [PageController::class, 'actualizar']);
$router->post('/propiedades/actualizar', [PageController::class, 'actualizar']);
$router->post('/propiedades/eliminar', [PageController::class, 'eliminar']);

$router->get('/vendedores', [UserController::class, 'index']);
$router->get('/vendedores/crear', [UserController::class, 'crear']);
$router->post('/vendedores/crear', [UserController::class, 'crear']);
$router->get('/vendedores/actualizar', [UserController::class, 'actualizar']);
$router->post('/vendedores/actualizar', [UserController::class, 'actualizar']);
$router->post('/vendedores/eliminar', [UserController::class, 'eliminar']);

$router->get('/login', [LoginController::class, 'login']);
$router->post('/login', [LoginController::class, 'login']);
$router->get('/signup', [LoginController::class, 'signup']);
$router->post('/signup', [LoginController::class, 'signup']);
$router->get('/logout', [LoginController::class, 'logout']);


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();