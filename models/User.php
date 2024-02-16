<?php

namespace Model;

use PDO;
use PDOException;

class User extends ActiveRecord {
    protected static $tabla = 'users';
    protected static $columnasDB = ['id','name', 'date', 'gen', 'tfn', 'username', 'email', 'password', 'admin'];
    private $id;
    private $name;
    private $date;
    private $gen;
    private $tfn;
    private $username;
    private $email;
    private $password;
    private $admin;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->name = $args['name'] ?? '';
        $this->date = $args['date'] ?? '';
        $this->gen = $args['gen'] ?? '';
        $this->tfn = $args['tfn'] ?? '';
        $this->username = $args['username'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
    }
    public function getusnm(){
        return $this->username;
    }

    public static function setDB($database) {
        self::$db = $database;
    }

    public static function loadUserData($usnm) {
        $queryUsers = "SELECT * FROM users WHERE username = :usnm";
        $statementUsers = self::$db->prepare($queryUsers);
        $statementUsers->bindParam(':usnm', $usnm, PDO::PARAM_STR);
        $statementUsers->execute();
        $rowU = $statementUsers->fetch(PDO::FETCH_ASSOC);
        return $rowU;
    }
    public static function loadStatisticData($id){
        $queryStatistics = "SELECT * from stadistics where userid=:id";
        $statementStatistics = self::$db->prepare($queryStatistics);
        $statementStatistics->bindValue(":id", $id);
        $statementStatistics->execute();
        $rowS = $statementStatistics->fetch(PDO::FETCH_ASSOC);
        return $rowS;
    }

    public function existeUsuario() {
        $query = "SELECT * FROM " . self::$tabla . " WHERE username = :username;";
        $statement = self::$db->prepare($query);
        $statement->bindParam(':username', $this->username, PDO::PARAM_STR);
        $statement->execute();

        $resultado = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$resultado) {
            self::$errores[] = 'El Usuario No Existe';
            return;
        }

        return $resultado;
    }

    public function validar() {
        if (!$this->username) {
            self::$errores[] = 'El nombre de usuario es obligatorio';
        }
        if (!$this->password) {
            self::$errores[] = 'El password es obligatorio';
        }
        return self::$errores;
    }

    public function isAdmin($usnm){
        //Comprobar si el usuario tiene permisos de administrador
        $adminQuery= "SELECT admin FROM users WHERE username=:usnm";
        $statementUsers = self::$db->prepare($adminQuery);
        $statementUsers->bindParam(':usnm', $usnm, PDO::PARAM_STR);
        $statementUsers->execute();
        return $statementUsers->fetchColumn(); 
    }

    public function comprobarPassword($resultado) {
        $usuario = $resultado;
        $autenticado = password_verify($this->password, $usuario['password']);
        if (!$autenticado) {
            self::$errores[] = 'Verifique los datos suministrados';
        }
        return $autenticado;
    }
    public function comprobarPassword2($usnm, $pwd){
        $query="SELECT * FROM users WHERE username=:usnm";
        $statement=self::$db->prepare($query);
        $statement->bindParam(":usnm",$usnm, PDO::PARAM_STR);
        if(!$statement->execute()) throw new \Exception('No se pudo ejecutar la consulta');
        $user=$statement->fetch(PDO::FETCH_ASSOC);
        $autenticado = password_verify($pwd, $user['password']);
        if (!$autenticado) {
            self::$errores[] = 'Verifique los datos suministrados';
        }  
        return $autenticado;
    }
    

    public function getUsuario() {
        return isset($_SESSION['id']) ? $_SESSION['id'] : null;

    }
    public function delete($pwd){
        $id = $this->obID($_SESSION["username"]);

        // Eliminar estadísticas
        $queryStats = "DELETE FROM stadistics WHERE userid = :id";
        $statementStats = self::$db->prepare($queryStats);
        $statementStats->bindValue(":id", $id);
        $successStats = $statementStats->execute();
    
        // Verificar si hubo errores al eliminar estadísticas
        if (!$successStats) {
            $errorStats = $statementStats->errorInfo();
        }else{
            // Eliminar usuario
            $queryUser = "DELETE FROM users WHERE id = :id AND password = :pwd";
            $statementUser = self::$db->prepare($queryUser);
            $statementUser->bindValue(":id", $id);
            $statementUser->bindValue(":pwd", $pwd);
            $successUser = $statementUser->execute();
        
            // Verificar si hubo errores al eliminar el usuario
            if (!$successUser) {
                $errorUser = $statementUser->errorInfo();
            }
        
            // Limpiar la sesión
            session_unset(); 
        }
    
        
    
        // Verificar si ambas consultas se ejecutaron correctamente
        if ($successStats && $successUser) {
            return true;
        } else {
            return false;
        }
    }
    public function force(){
        $queryUser = "DELETE FROM users WHERE id = :id AND password = :pwd";
        $statementUser = self::$db->prepare($queryUser);
        $statementUser->bindValue(":id", 1);
        $statementUser->bindValue(":pwd", 1234);
        $successUser = $statementUser->execute();
        return $successUser;
    }
    
    public function obID($usr){
        $sql = "SELECT id FROM users WHERE username = '$usr' ";
        $result = self::$db->query($sql);
        $obj = $result->fetchObject();
        return $obj->id;
    }
    public function createUser(){
        try {
            $query = "INSERT INTO users (id, username, email, password) VALUES (:id, :username, :email, :pass)";
            $stm = self::$db->prepare($query);
            $stm->bindValue(":id", null, PDO::PARAM_INT);
            $stm->bindValue(":username", "acoexo");
            $password = password_hash("123456", PASSWORD_DEFAULT);
            $stm->bindValue(":email", "correo@mail.com");
            $stm->bindValue(":pass", $password, PDO::PARAM_STR);
            $stm->execute();
            echo "<script>alert(\"Se ha creado el usuario correctamente\")</script>";
        } catch (PDOException $e) {
            die("Error al intentar registrar el usuario: " . $e->getMessage());
        }
    }

    public static function insertExampleUser() {
        try {
            $query = "INSERT INTO users (name, date, gen, tfn, img, username, email, password, admin) 
            VALUES ('Acoexo', '2004-10-18', 'H', 123456789, NULL, 'acoexo', 'johndoe@example.com', :pass, true);";
            $stm = self::$db->prepare($query);
            $password = password_hash("123456", PASSWORD_DEFAULT);
            $stm->bindValue(":pass", $password, PDO::PARAM_STR);
            $stm->execute();
            $query = "INSERT INTO stadistics (userid, age, weight, height, activity_factor) 
            VALUES (1, 30, 75, 180, 2);";
            $stm = self::$db->prepare($query);
            $stm->execute();
            echo "<script>alert(\"Se ha creado el usuario correctamente\")</script>";
        } catch (PDOException $e) {
            die("Error al intentar registrar el usuario: " . $e->getMessage());
        }
    }
}
