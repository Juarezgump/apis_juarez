<?php 
require_once __DIR__ . '/../includes/app.php';

use Controllers\UsuarioController;
use MVC\Router;
use Controllers\AppController;
use Controllers\ProductoController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [AppController::class,'index']);

$router->get('/usuario', [UsuarioController::class, 'renderizarPAgina']);
$router->post('/usuarios/guardarAPI', [UsuarioController::class, 'guardarAPI']);
$router->get('/usuarios/buscarAPI', [UsuarioController::class, 'buscarAPI']);
$router->post('/usuarios/modificarAPI', [UsuarioController::class, 'modificarAPI']);

//Rutas Productos
$router->get('/productos', [ProductoController::class, 'renderizarPagina']);
$router->post('/productos/guardarAPI', [ProductoController::class, 'guardarAPI']);
$router->get('/productos/buscarAPI', [ProductoController::class, 'buscarAPI']);
$router->post('/productos/modificarAPI', [ProductoController::class, 'modificarAPI']);
$router->get('/productos/eliminar', [ProductoController::class, 'EliminarAPI']);
$router->get('/productos/disponibles', [ProductoController::class, 'productosDisponiblesAPI']);



$router->comprobarRutas();