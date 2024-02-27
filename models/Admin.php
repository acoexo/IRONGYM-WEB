<?php 

namespace Model;

class Admin extends ActiveRecord {
    //Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'email', 'password', 'admin'];
    
    public $id;
    public $email;
    public $password;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
    }
    public static function setDB($database) {
        self::$db = $database;
    }

    public function validar() {
        if(!$this->email) {
            self::$errores[] = 'El email es obligatorio';   
        }
        if(!$this->password) {
            self::$errores[] = 'El password es obligatorio';              
        }
        return self::$errores;
    }

    public function existeUsuario() {
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = " . "'" . $this->email . "'" . " LIMIT 1";
        $resultado = self::$db->query($query);
        if(!$resultado->num_rows) {
            self::$errores[] = 'El usuario no existe';
            return;
        }
        return $resultado;
    }

    public function comprobarPassword($resultado) {
        $usuario = $resultado->fetch_object();

        $autenticado = password_verify($this->password, $usuario->password);
        
        if(!$autenticado) {
            self::$errores[] = 'Verifique los datos suministrados';
        }
        
        return $autenticado;
    }

    public function autenticar() {
        session_start();
        $_SESSION['admin'] = $this->email;
        $_SESSION['login'] = true;
        header('Location: /admin');
    }
    public function load(){
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
    public function search($search){
        $sql = "SELECT id, username FROM users WHERE username LIKE '%$search%' LIMIT 10";
        $result = self::$db->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        return $users;
    }
    public function printUsers($users){
        header('Content-Type: application/json');
        return json_encode($users);
    }
}