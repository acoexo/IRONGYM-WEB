<?php

namespace Model;

class ActiveRecord {
    protected static $db;
    // protected static $imagen;
    protected static $tabla = '';
    protected static $columnasDB = [];
    protected static $errores = [];
    
    public static function setDB($database) {
        self::$db = $database;
    }

    public static function getErrores() {
        return static::$errores;
    }

    public function validar() {
        static::$errores = [];
        return static::$errores;
    }

    public static function all() {
        $query = "SELECT * FROM " . static::$tabla;
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    public static function find($id) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = ?";
        $params = [$id];
        $resultado = self::consultarSQL($query, $params);
        return array_shift($resultado);
    }

    public static function get($id = null) {
        $query = "SELECT * FROM " . static::$tabla;
    
        if ($id !== null) {
            $query .= " WHERE id = ?";
            $params = [$id];
            return self::consultarSQL($query, $params);
        } else {
            return self::consultarSQL($query);
        }
    }

    public static function consultarSQL($query, $params = []) {
        $resultado = self::$db->prepare($query);
        $resultado->execute($params);
        return $resultado->fetchAll(\PDO::FETCH_ASSOC);
    }

    protected static function crearObjeto($registro) {
        $objeto = new static;
        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }
        return $objeto;
    }

    public function atributos() {
        $atributos = [];
        foreach (static::$columnasDB as $columna) {
            if ($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function sanitizarAtributos() {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->quote($value);
        }
        return $sanitizado;
    }
    

    public function sincronizar($args = []) {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }

    // public function setImagen($imagen) {
    //     if (!is_null($this->id)) {
    //         $this->borrarImagen();
    //     }

    //     if ($imagen) {
    //         $this->imagen = $imagen;
    //     }
    // }

    // public function borrarImagen() {
    //     $rutaImagen = CARPETA_IMAGENES . $this->imagen;

    //     if (file_exists($rutaImagen)) {
    //         unlink($rutaImagen);
    //     }
    // }
}
