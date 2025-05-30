<?php

namespace Controllers;


use Exception;
use Model\ActiveRecord;
use Model\Productos;
use MVC\Router;

class ProductoController extends ActiveRecord{
    public static function renderizarPagina(Router $router){
        $router->render('productos/index', []);
    }

    public static function guardarAPI(){
        getHeadersApi();

        $_POST['pro_nombre'] = htmlspecialchars($_POST['pro_nombre']);
        $cantidad_nombre = strlen($_POST['pro_nombre']);

        if ($cantidad_nombre < 2){
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre del producto debe tener al menos 2 caracteres'
            ]);
            return;
        }

        $_POST['pro_precio'] = filter_var(['pro_precio'], FILTER_VALIDATE_INT);

        if ($_POST['pro_precio'] <= 0){
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mesnaje' => 'El precio debe ser mayor a cero'
            ]);
        }


        $_POST['pro_cantidad'] = filter_var($_POST['pro_cantidad'], FILTER_VALIDATE_INT);

        if ($_POST['pro_precio'] < 0){
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad del producto no puede ser esa'
            ]);
        }

        try {
            $data = new Productos([
                
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }


    }

}