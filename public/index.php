<?php 
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/app.php';


use MVC\Router;
use Controllers\UserController;
use Controllers\PageController;
use Controllers\LoginController;

$router = new Router();

$router->get('/', [PageController::class, 'index']);
$router->get('/user/mainpage', [PageController::class, 'mainPageLoader']);
$router->get('/example/insertUser', [PageController::class, 'example']);
$router->get('/user/update', [PageController::class, 'userUpdate']);
$router->get('/user/delete', [PageController::class, 'delteUser']);
$router->post('/user/delete', [UserController::class, 'delete']);


$router->get('/vendedores', [UserController::class, 'index']);


//Temas del login
$router->get('/login', [LoginController::class, 'login']);
$router->post('/login', [LoginController::class, 'login']);
$router->get('/signup', [LoginController::class, 'signup']);
$router->post('/signup', [LoginController::class, 'signup']);
$router->get('/user/logout', [LoginController::class, 'logout']);


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();