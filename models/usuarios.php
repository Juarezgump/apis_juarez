<?php

namespace Model;
class Usuarios extends Activerecord{
    public $tabla = 'usuarios';
    public $columnasDB = [
        'usuario_nombres',
        'usuario_apellidos',
        'usuario_nit',
        'usuario_telefono',
        'usuario_correo',
        'usuario_estado',
        'usuario_situacion'
    ];

    public $idTabla = 'usuario_id';

    public $usuario_id;
    public $usuario_nombres;
    public $usuario_apellidos;
    public $usuario_nit;
    public $usuario_telefono;
    public $usuario_correo;
    public $usuario_estado;
    public $usuario_situacion;



    public function __construct($args = []){
        $this ->usuario_id = $args['usuario_id'] ?? null;
        $this ->usuario_nombres = $args['usuario_nombres'] ?? '';
        $this ->usuario_apellidos = $args['usuario_apellidos'] ?? '';
        $this ->usuario_nit = $args['usuario_nit'] ?? 0;
        $this ->usuario_telefono = $args['usuario_telefono'] ?? 0;
        $this ->usuario_correo = $args['usuario_correo'] ?? 0;
        $this ->usuario_estado = $args['usuario_estado'] ?? 0;
        $this ->usuario_situacion = $args['usuario_situacion'] ?? 0;

    }

}