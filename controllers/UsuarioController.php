<?php

namespace Controllers;

use Model\ActiveRecord;
use MVC\Router;

class UsuarioController extends ActiveRecord{

    public function renderizarPagina(Router $router){
        $router->render('usuarios/index', []);
    }
}