<?php

namespace Model;

use PDO;
use Exception;

class ActiveRecord
{

    // Base DE DATOS
    protected static $db;
    protected static $tabla = '';
    protected static $columnasDB = [];

    protected static $idTabla = [];
    protected static $id = 'id';

    // Alertas y Mensajes
    protected static $alertas = [];

    // Definir la conexión a la BD - includes/database.php
    public static function setDB($database)
    {
        self::$db = $database;
    }

    public static function setAlerta($tipo, $mensaje)
    {
        static::$alertas[$tipo][] = $mensaje;
    }
    // Validación
    public static function getAlertas()
    {
        return static::$alertas;
    }

    public function validar()
    {
        static::$alertas = [];
        return static::$alertas;
    }

    // Registros - CRUD
    public function guardar()
    {
        $resultado = '';
        $id = static::$idTabla ?? 'id';
        if (!is_null($this->$id)) {
            // actualizar
            $resultado = $this->actualizar();
        } else {
            // Creando un nuevo registro
            $resultado = $this->crear();
        }
        return $resultado;
    }

    public static function all()
    {
        $query = "SELECT * FROM " . static::$tabla;
        $resultado = self::consultarSQL($query);

        // debuguear($resultado);
        return $resultado;
    }

    // Busca un registro por su id
    public static function find($id = [])
    {
        $idQuery = static::$idTabla ?? 'id';
        $query = "SELECT * FROM " . static::$tabla;

        if (is_array(static::$idTabla)) {
            foreach (static::$idTabla as $key => $value) {
                if ($value == reset(static::$idTabla)) {
                    $query .= " WHERE $value = " . self::$db->quote($id[$value]);
                } else {
                    $query .= " AND $value = " . self::$db->quote($id[$value]);
                }
            }
        } else {

            $query .= " WHERE $idQuery = $id";
        }

        $resultado = self::consultarSQL($query);
        return array_shift($resultado);
    }

    // Obtener Registro
    public static function get($limite)
    {
        $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $limite;
        $resultado = self::consultarSQL($query);
        return array_shift($resultado);
    }

    // Busqueda Where con Columna 
    public static function where($columna, $valor, $condicion = '=')
    {
        $query = "SELECT * FROM " . static::$tabla . " WHERE " . $columna . " " . $condicion . " '" . $valor . "'";
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // SQL para Consultas Avanzadas.
    public static function SQL($consulta)
    {
        $query = $consulta;
        $resultado = self::$db->query($query);
        return $resultado;
    }

    // crea un nuevo registro
    public function crear()
    {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        $columnas = join(', ', array_keys($atributos));
        $valores = join("', '", array_values($atributos));

        $query = "INSERT INTO " . static::$tabla . " ({$columnas}) VALUES ('{$valores}')";

        $resultado = self::$db->query($query);

        return [
            'resultado' => $resultado,
            'id' => self::$db->lastInsertId()
        ];
    }

    public function actualizar()
    {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        // Iterar para ir agregando cada campo de la BD
        $valores = [];
        foreach ($atributos as $key => $value) {
            $valores[] = "{$key}={$value}";
        }
        $id = static::$idTabla ?? 'id';

        $query = "UPDATE " . static::$tabla . " SET ";
        $query .=  join(', ', $valores);

        if (is_array(static::$idTabla)) {

            foreach (static::$idTabla as $key => $value) {
                if ($value == reset(static::$idTabla)) {
                    $query .= " WHERE $value = " . self::$db->quote($this->$value);
                } else {
                    $query .= " AND $value = " . self::$db->quote($this->$value);
                }
            }
        } else {
            $query .= " WHERE " . $id . " = " . self::$db->quote($this->$id) . " ";
        }

        // debuguear($query);

        $resultado = self::$db->exec($query);
        return [
            'resultado' =>  $resultado,
        ];
    }

    // Eliminar un registro - Toma el ID de Active Record
    public function eliminar()
    {
        $idQuery = static::$idTabla ?? 'id';
        $query = "DELETE FROM "  . static::$tabla . " WHERE $idQuery = " . self::$db->quote($this->id);
        $resultado = self::$db->exec($query);
        return $resultado;
    }

    public static function consultarSQL($query)
    {
        // Consultar la base de datos
        $resultado = self::$db->query($query);

        // Iterar los resultados
        $array = [];
        while ($registro = $resultado->fetch(PDO::FETCH_ASSOC)) {
            $array[] = static::crearObjeto($registro);
        }

        // liberar la memoria
        $resultado->closeCursor();

        // retornar los resultados
        return $array;
    }

    public static function fetchArray($query)
    {
        try {
            $resultado = self::$db->query($query);

            if (!$resultado) {
                return [];
            }

            $respuesta = $resultado->fetchAll(PDO::FETCH_ASSOC);
            $data = [];

            foreach ($respuesta as $value) {
                // Usar mb_convert_encoding en lugar de utf8_encode deprecated
                $data[] = array_change_key_case(array_map(function ($item) {
                    return mb_convert_encoding($item ?? '', 'UTF-8', 'auto');
                }, $value));
            }

            $resultado->closeCursor();
            return $data;
        } catch (Exception $e) {
            error_log("Error en fetchArray: " . $e->getMessage());
            return [];
        }
    }


    public static function fetchFirst($query)
    {
        try {
            $resultado = self::$db->query($query);

            if (!$resultado) {
                return null;
            }

            $respuesta = $resultado->fetchAll(PDO::FETCH_ASSOC);
            $data = [];

            foreach ($respuesta as $value) {
                // Usar mb_convert_encoding en lugar de utf8_encode deprecated
                $data[] = array_change_key_case(array_map(function ($item) {
                    return mb_convert_encoding($item ?? '', 'UTF-8', 'auto');
                }, $value));
            }

            $resultado->closeCursor();
            return array_shift($data);
        } catch (Exception $e) {
            error_log("Error en fetchFirst: " . $e->getMessage());
            return null;
        }
    }

    protected static function crearObjeto($registro)
    {
        $objeto = new static;

        foreach ($registro as $key => $value) {
            $key = strtolower($key);
            if (property_exists($objeto, $key)) {
                // Usar mb_convert_encoding en lugar de utf8_encode deprecated
                $objeto->$key = mb_convert_encoding($value ?? '', 'UTF-8', 'auto');
            }
        }

        return $objeto;
    }

    // Identificar y unir los atributos de la BD
    public function atributos()
    {
        $atributos = [];
        foreach (static::$columnasDB as $columna) {
            $columna = strtolower($columna);
            if ($columna === 'id' || $columna === static::$idTabla) continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function sanitizarAtributos()
    {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->quote($value);
        }
        return $sanitizado;
    }

    public function sincronizar($args = [])
    {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }
}
