<?php 
require_once __DIR__ . '/../includes/app.php';

use Controllers\UsuarioController;
use MVC\Router;
use Controllers\AppController;


$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [AppController::class,'index']);

//ESTE ES EL URL PARA USUARIO
$router->get('/usuario', [UsuarioController::class, 'renderizarPAgina']);
$router->post('/usuarios/guardarAPI', [UsuarioController::class, 'guardarAPI']);
// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();