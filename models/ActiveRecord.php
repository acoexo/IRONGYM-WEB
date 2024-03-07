<?php

namespace Model;

/**
 * Clase ActiveRecord
 * 
 * Proporciona métodos para interactuar con la base de datos de manera genérica.
 */
class ActiveRecord
{
    protected static $db;
    protected static $tabla = '';
    protected static $columnasDB = [];
    protected static $errores = [];

    /**
     * Establece la conexión a la base de datos
     *
     * @param PDO $database Instancia de PDO que representa la conexión a la base de datos
     * @return void
     */
    public static function setDB($database)
    {
        self::$db = $database;
    }

    /**
     * Obtiene los errores ocurridos durante la validación
     *
     * @return array Array que contiene los errores de validación
     */
    public static function getErrores()
    {
        return static::$errores;
    }

    /**
     * Valida los datos del modelo
     *
     * @return array Array vacío si no hay errores de validación
     */
    public function validar()
    {
        static::$errores = [];
        return static::$errores;
    }

    /**
     * Obtiene todos los registros de la tabla
     *
     * @return array|bool Array de registros si la consulta tiene éxito, false si falla
     */
    public static function all()
    {
        try {
            $query = "SELECT * FROM " . static::$tabla;
            $resultado = self::consultarSQL($query);
            return $resultado;
        } catch (\PDOException $e) {
            // Manejar la excepción
            return false;
        }
    }

    /**
     * Encuentra un registro por su ID
     *
     * @param int $id ID del registro a buscar
     * @return array|bool Array que representa el registro si se encuentra, false si no se encuentra o falla la consulta
     */
    public static function find($id)
    {
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

    /**
     * Obtiene registros opcionales por ID
     *
     * @param int|null $id ID opcional del registro a obtener
     * @return array|bool Array de registros si la consulta tiene éxito, false si falla
     */
    public static function get($id = null)
    {
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

    /**
     * Realiza consultas SQL preparadas
     *
     * @param string $query Consulta SQL
     * @param array $params Parámetros para la consulta preparada
     * @return array|bool Array de resultados si la consulta tiene éxito, false si falla
     */
    public static function consultarSQL($query, $params = [])
    {
        try {
            $resultado = self::$db->prepare($query);
            $resultado->execute($params);
            return $resultado->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Manejar la excepción
            return false;
        }
    }

    /**
     * Crea un objeto a partir de un registro de la base de datos
     *
     * @param array $registro Registro de la base de datos
     * @return mixed|bool Objeto creado si tiene éxito, false si falla
     */
    protected static function crearObjeto($registro)
    {
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

    /**
     * Obtiene los atributos del objeto
     *
     * @return array|bool Array de atributos si tiene éxito, false si falla
     */
    public function atributos()
    {
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

    /**
     * Sanitiza los atributos del objeto
     *
     * @return array|bool Array de atributos sanitizados si tiene éxito, false si falla
     */
    public function sanitizarAtributos()
    {
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


    /**
     * Sincroniza los atributos del objeto
     *
     * @param array $args Argumentos para sincronizar
     * @return void
     */
    public function sincronizar($args = [])
    {
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
}
