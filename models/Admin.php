<?php

namespace Model;

/**
 * Clase Admin
 * 
 * Representa un administrador en la aplicación.
 */
class Admin extends ActiveRecord
{
    // Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'email', 'password', 'admin'];

    public $id;
    public $email;
    public $password;

    /**
     * Constructor de la clase Admin
     *
     * @param array $args Argumentos para inicializar el administrador
     */
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
    }

    /**
     * Valida los datos del administrador
     *
     * @return array Array de errores de validación
     */
    public function validar()
    {
        if (!$this->email) {
            self::$errores[] = 'El email es obligatorio';
        }
        if (!$this->password) {
            self::$errores[] = 'El password es obligatorio';
        }
        return self::$errores;
    }

    /**
     * Verifica si el usuario existe en la base de datos
     *
     * @return mixed|null Objeto con los datos del usuario si existe, null si no existe
     */
    public function existeUsuario()
    {
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = " . "'" . $this->email . "'" . " LIMIT 1";
        $resultado = self::$db->query($query);
        if (!$resultado->num_rows) {
            self::$errores[] = 'El usuario no existe';
            return null;
        }
        return $resultado;
    }

    /**
     * Verifica si el password proporcionado es correcto
     *
     * @param mixed $resultado Resultado de la consulta SQL
     * @return bool True si el password es correcto, False si no lo es
     */
    public function comprobarPassword($resultado)
    {
        $usuario = $resultado->fetch_object();

        $autenticado = password_verify($this->password, $usuario->password);

        if (!$autenticado) {
            self::$errores[] = 'Verifique los datos suministrados';
        }

        return $autenticado;
    }

    /**
     * Inicia sesión para el administrador
     *
     * @return void
     */
    public function autenticar()
    {
        session_start();
        $_SESSION['admin'] = $this->email;
        $_SESSION['login'] = true;
        header('Location: /admin');
    }

    /**
     * Carga los usuarios desde la base de datos
     *
     * @return mixed JSON con los datos de los usuarios
     */
    public function load()
    {
        $sql = "SELECT id, username FROM users LIMIT 10";
        $result = self::$db->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        header('Content-Type: application/json');
        return json_encode($users);
    }

    /**
     * Busca usuarios en la base de datos
     *
     * @param string $search Término de búsqueda
     * @return array Array con los datos de los usuarios encontrados
     */
    public function search($search)
    {
        $sql = "SELECT id, username FROM users WHERE username LIKE '%$search%' LIMIT 10";
        $result = self::$db->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        return $users;
    }

    /**
     * Imprime los usuarios en formato JSON
     *
     * @param array $users Array con los datos de los usuarios
     * @return mixed JSON con los datos de los usuarios
     */
    public function printUsers($users)
    {
        header('Content-Type: application/json');
        return json_encode($users);
    }
}
