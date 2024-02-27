<?php

namespace Model;

class ActiveRecord {
    protected static $db;
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

    // Método para obtener todos los registros
    public static function all() {
        try {
            $query = "SELECT * FROM " . static::$tabla;
            $resultado = self::consultarSQL($query);
            return $resultado;
        } catch (\PDOException $e) {
            // Manejar la excepción
            return false;
        }
    }

    // Método para encontrar un registro por su ID
    public static function find($id) {
        try {
            $query = "SELECT * FROM " . static::$tabla . " WHERE id = ?";
            $params = [$id];
            $resultado = self::consultarSQL($query, $params);
            return array_shift($resultado);
        } catch (\PDOException $e) {
            // Manejar la excepción
            return false;
        }
    }

    // Método para obtener registros opcionales por ID
    public static function get($id = null) {
        try {
            $query = "SELECT * FROM " . static::$tabla;
            if ($id !== null) {
                $query .= " WHERE id = ?";
                $params = [$id];
                return self::consultarSQL($query, $params);
            } else {
                return self::consultarSQL($query);
            }
        } catch (\PDOException $e) {
            // Manejar la excepción
            return false;
        }
    }

    // Método para realizar consultas SQL preparadas
    public static function consultarSQL($query, $params = []) {
        try {
            $resultado = self::$db->prepare($query);
            $resultado->execute($params);
            return $resultado->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Manejar la excepción
            return false;
        }
    }

    // Método para crear un objeto a partir de un registro de la base de datos
    protected static function crearObjeto($registro) {
        try {
            $objeto = new static;
            foreach ($registro as $key => $value) {
                if (property_exists($objeto, $key)) {
                    $objeto->$key = $value;
                }
            }
            return $objeto;
        } catch (\Exception $e) {
            // Manejar la excepción
            return false;
        }
    }

    // Método para obtener los atributos del objeto
    public function atributos() {
        try {
            $atributos = [];
            foreach (static::$columnasDB as $columna) {
                if ($columna === 'id') continue;
                $atributos[$columna] = $this->$columna;
            }
            return $atributos;
        } catch (\Exception $e) {
            // Manejar la excepción
            return false;
        }
    }

    // Método para sanitizar los atributos del objeto
    public function sanitizarAtributos() {
        try {
            $atributos = $this->atributos();
            $sanitizado = [];
            foreach ($atributos as $key => $value) {
                $sanitizado[$key] = self::$db->quote($value);
            }
            return $sanitizado;
        } catch (\PDOException $e) {
            // Manejar la excepción
            return false;
        }
    }
    

    // Método para sincronizar los atributos del objeto
    public function sincronizar($args = []) {
        try {
            foreach ($args as $key => $value) {
                if (property_exists($this, $key) && !is_null($value)) {
                    $this->$key = $value;
                }
            }
        } catch (\Exception $e) {
            // Manejar la excepción
            return false;
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
