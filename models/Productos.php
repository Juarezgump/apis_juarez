<?php

namespace Model;


class Productos extends ActiveRecord {

    public static $tabla = 'productos';
    public static $columnasDB = [
        'pro_nombre',
        'pro_precio',
        'pro_cantidad',
        'pro_situacion'
    ];

    public static $idTabla = 'pro_id';
    public $pro_id;
    public $pro_nombre;
    public $pro_precio;
    public $pro_cantidad;
    public $pro_situacion;


    public function __construct($args = []){
        $this->pro_id = $args['pro_id'] ?? null;
        $this->pro_nombre = ['pro_nombre'] ?? '';
        $this->pro_precio = ['pro_precio'] ?? 0;
        $this->pro_cantidad = ['pro_cantidad'] ?? 0;
        $this->pro_situacion = ['pro_situacion'] ?? 1;
    }
}