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

    //Guardar Productos
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

        $_POST['pro_precio'] = filter_var($_POST['pro_precio'], FILTER_VALIDATE_INT);

        if ($_POST['pro_precio'] <= 0){
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El precio debe ser mayor a cero'
            ]);
            return;
        }

        $_POST['pro_cantidad'] = filter_var($_POST['pro_cantidad'], FILTER_VALIDATE_INT);

        if ($_POST['pro_cantidad'] < 0){
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad del producto no puede ser negativa'
            ]);
            return;
        }

        try {
            $data = new Productos([
                'pro_nombre' => $_POST['pro_nombre'],
                'pro_precio' => $_POST['pro_precio'],
                'pro_cantidad' => $_POST['pro_cantidad'],
                'pro_situacion' => 1
            ]);

            $crear = $data->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El producto ha sido registrado con exito'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar el producto',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    //Buscar Productos
    public static function buscarAPI(){
        try {
            $sql = "SELECT * FROM productos WHERE pro_situacion = 1";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Productos obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los productos',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    //Modificar Productos
    public static function modificarAPI(){
        getHeadersApi();

        $id = $_POST['pro_id'];

        $_POST['pro_nombre'] = htmlspecialchars($_POST['pro_nombre']);

        $cantidad_nombre = strlen($_POST['pro_nombre']);

        if ($cantidad_nombre < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre del producto debe tener al menos 2 caracteres'
            ]);
            return;
        }

        $_POST['pro_precio'] = filter_var($_POST['pro_precio'], FILTER_VALIDATE_INT);

        if ($_POST['pro_precio'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El precio debe ser mayor a cero'
            ]);
            return;
        }

        $_POST['pro_cantidad'] = filter_var($_POST['pro_cantidad'], FILTER_VALIDATE_INT);

        if ($_POST['pro_cantidad'] < 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad no puede ser negativa'
            ]);
            return;
        }

        try {
            $data = Productos::find($id);
            $data->sincronizar([
                'pro_nombre' => $_POST['pro_nombre'],
                'pro_precio' => $_POST['pro_precio'],
                'pro_cantidad' => $_POST['pro_cantidad'],
                'pro_situacion' => 1
            ]);

            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La informacion del producto ha sido modificada con exito'
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar el producto',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    //Eliminar Producto
    public static function EliminarAPI(){
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

            self::EliminarProducto($id, 0);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El producto ha sido desactivado correctamente'
            ]);
        
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al desactivar el producto',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function productosDisponiblesAPI(){
        try {
            $data = self::ObtenerProductosDisponibles();
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Productos disponibles obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener productos disponibles',
                'detalle' => $e->getMessage()
            ]);
        }
    }


    public static function EliminarProducto($id, $situacion)
    {
        $sql = "UPDATE productos SET pro_situacion = $situacion WHERE pro_id = $id";
        return self::SQL($sql);
    }

    public static function ValidarStockProducto($id)
    {
        $sql = "SELECT pro_cantidad FROM productos WHERE pro_id = $id AND pro_situacion = 1";
        $resultado = self::fetchFirst($sql);
        return $resultado['pro_cantidad'] ?? 0;
    }

    public static function ActualizarStockProducto($id, $cantidad_vendida)
    {
        $sql = "UPDATE productos SET pro_cantidad = pro_cantidad - $cantidad_vendida WHERE pro_id = $id";
        return self::SQL($sql);
    }

    public static function ObtenerProductosDisponibles()
    {
        $sql = "SELECT * FROM productos WHERE pro_cantidad > 0 AND pro_situacion = 1";
        return self::fetchArray($sql);
    }

    public static function ReactivarProducto($id)
    {
        return self::EliminarProducto($id, 1);
    }
}