<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\Estadisticas;
use MVC\Router;

class EstadisticaController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('estadisticas/index', []);
    }

    public static function buscarAPI(){
        try {
            // Consulta para productos más vendidos
            $sqlProductos = "SELECT pro_nombre as producto, pro_id, sum(detalle_cantidad) as cantidad 
                           from venta_detalles 
                           inner join productos on pro_id = detalle_producto_id 
                           group by pro_id, pro_nombre 
                           order by cantidad desc";
            $datosProductos = self::fetchArray($sqlProductos);

            // Consulta para clientes con más compras
            $sqlClientes = "SELECT 
                              u.usuario_nombres || ' ' || u.usuario_apellidos AS cliente,
                              COUNT(vd.detalle_id) AS total_prod_comprados,
                              SUM(vd.detalle_cantidad) AS cantidad_total_prod,
                              SUM(v.venta_total) AS total_gastado
                          FROM usuarios u
                          INNER JOIN ventas v ON u.usuario_id = v.venta_cliente_id
                          INNER JOIN venta_detalles vd ON v.venta_id = vd.detalle_venta_id
                          WHERE u.usuario_situacion = 1 
                              AND v.venta_situacion = 1
                          GROUP BY u.usuario_id, u.usuario_nombres, u.usuario_apellidos
                          ORDER BY cantidad_total_prod DESC";
            $datosClientes = self::fetchArray($sqlClientes);

            // Consulta para ventas por mes
            $sqlVentasMes = "SELECT 
                               SUM(CASE WHEN MONTH(v.venta_fecha) = 1 THEN 1 ELSE 0 END) AS enero,
                               SUM(CASE WHEN MONTH(v.venta_fecha) = 2 THEN 1 ELSE 0 END) AS febrero,
                               SUM(CASE WHEN MONTH(v.venta_fecha) = 3 THEN 1 ELSE 0 END) AS marzo,
                               SUM(CASE WHEN MONTH(v.venta_fecha) = 4 THEN 1 ELSE 0 END) AS abril,
                               SUM(CASE WHEN MONTH(v.venta_fecha) = 5 THEN 1 ELSE 0 END) AS mayo,
                               SUM(CASE WHEN MONTH(v.venta_fecha) = 6 THEN 1 ELSE 0 END) AS junio,
                               SUM(CASE WHEN MONTH(v.venta_fecha) = 7 THEN 1 ELSE 0 END) AS julio,
                               SUM(CASE WHEN MONTH(v.venta_fecha) = 8 THEN 1 ELSE 0 END) AS agosto,
                               SUM(CASE WHEN MONTH(v.venta_fecha) = 9 THEN 1 ELSE 0 END) AS septiembre,
                               SUM(CASE WHEN MONTH(v.venta_fecha) = 10 THEN 1 ELSE 0 END) AS octubre,
                               SUM(CASE WHEN MONTH(v.venta_fecha) = 11 THEN 1 ELSE 0 END) AS noviembre,
                               SUM(CASE WHEN MONTH(v.venta_fecha) = 12 THEN 1 ELSE 0 END) AS diciembre
                           FROM ventas v
                           WHERE v.venta_situacion = 1";
            $datosVentasMes = self::fetchArray($sqlVentasMes);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Estadísticas obtenidas correctamente',
                'productos' => $datosProductos,
                'clientes' => $datosClientes,
                'ventasMes' => $datosVentasMes
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las estadísticas',
                'detalle' => $e->getMessage()
            ]);
        }
    }

}