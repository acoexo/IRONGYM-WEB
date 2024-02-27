<?php
/**
 * Represents a user in the system.
 *
 * This class handles user-related operations such as user authentication, creation, updating statistics, etc.
 *
 * @package Model
 */
namespace Model;

use PDO;
use PDOException;
use DateTime;

class User extends ActiveRecord {
    /**
     * The database table for the user.
     *
     * @var string
     */
    protected static $tabla = 'users';
    
    /**
     * The columns of the database table for the user.
     *
     * @var array
     */
    protected static $columnasDB = ['id','name', 'date', 'gen', 'tfn', 'username', 'email', 'password', 'admin'];

    /**
     * The ID of the user.
     *
     * @var int|null
     */
    private $id;
    
    /**
     * The name of the user.
     *
     * @var string
     */
    private $name;
    
    /**
     * The date of birth of the user.
     *
     * @var string
     */
    private $date;
    
    /**
     * The gender of the user.
     *
     * @var string
     */
    private $gen;
    
    /**
     * The phone number of the user.
     *
     * @var string
     */
    private $tfn;
    
    /**
     * The username of the user.
     *
     * @var string
     */
    private $username;
    
    /**
     * The email of the user.
     *
     * @var string
     */
    private $email;
    
    /**
     * The password of the user.
     *
     * @var string
     */
    private $password;
    
    /**
     * The admin status of the user.
     *
     * @var bool
     */
    private $admin;
    
    /**
     * The height of the user.
     *
     * @var int|null
     */
    private $height;
    
    /**
     * The weight of the user.
     *
     * @var int|null
     */
    private $weight;
    
    /**
     * The activity factor of the user.
     *
     * @var int|null
     */
    private $activity_factor;

    /**
     * User constructor.
     *
     * @param array $args An associative array containing user data.
     */
    public function __construct($args = []) {
        // Initialize properties from the provided array or set them to default values
        $this->id = $args['id'] ?? null;
        $this->name = $args['name'] ?? '';
        $this->date = $args['date'] ?? '';
        $this->gen = $args['gen'] ?? '';
        $this->tfn = $args['tfn'] ?? '';
        $this->username = $args['username'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->height = $args['height'] ?? null;
        $this->weight = $args['weight'] ?? null;
        $this->activity_factor = $args['activity_factor'] ?? null;
    }
    /**
     * Get username property.
     * @return string Returns the username value.
     */
    public function getusnm(){
        return $this->username;
    }
    /**
     * Stablish a connection to the database
     * @param PDO $pdo A PDO object representing a connection to the database.
     */
    public static function setDB($database) {
        self::$db = $database;
    }
    /**
     * Gets the user data
     * @param String $username The username of the user
     * @return Array|null Returns an associative array with the user's data if found, otherwise returns NULL
     */
    public static function loadUserData($usnm) {
        $queryUsers = "SELECT * FROM users WHERE username = :usnm";
        $statementUsers = self::$db->prepare($queryUsers);
        $statementUsers->bindParam(':usnm', $usnm, PDO::PARAM_STR);
        $statementUsers->execute();
        $rowU = $statementUsers->fetch(PDO::FETCH_ASSOC);
        return $rowU;
    }
    /**
     * Gets the statistics of the user
     * @param id $user_id The id of the user
     */
    public static function loadStatisticData($id){
        $queryStatistics = "SELECT * from stadistics where userid=:id";
        $statementStatistics = self::$db->prepare($queryStatistics);
        $statementStatistics->bindValue(":id", $id);
        $statementStatistics->execute();
        $rowS = $statementStatistics->fetch(PDO::FETCH_ASSOC);
        return $rowS;
    }
    /**
     * Prooves if an user exists
     * @return boolean True if the user exists, false otherwise
     */
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
    /**
     * Another form to proove the existence of an user
     * @return boolean False if the password is incorrect or the user does not exist, true otherwise.
     */
    public function existeUsuario2(){
        $query = "SELECT * FROM " . self::$tabla . " WHERE username = :username;";
        $statement = self::$db->prepare($query);
        $statement->bindParam(':username', $this->username, PDO::PARAM_STR);
        $statement->execute();

        // $resultado = $statement->fetch(PDO::FETCH_ASSOC);
        $resultado = $statement->rowCount();
        if ($resultado===0) {
            return true;
        } else{
            return false;
        }
    }

    /**
     * Validate the login form
     * @return array|null true if  all fields are filled correctly, false otherwise
     */
    public function validar() {
        if (!$this->username) {
            self::$errores[] = 'El nombre de usuario es obligatorio';
        }
        if (!$this->password) {
            self::$errores[] = 'El password es obligatorio';
        }
        return self::$errores;
    }
    /**
     * Prooves if the user have admin options
     * @param String usnm  The username of the user that we want to check
     * @return Boolean True if the user has admin options, false otherwise
     */
    public function isAdmin($usnm){
        $adminQuery= "SELECT admin FROM users WHERE username=:usnm";
        $statementUsers = self::$db->prepare($adminQuery);
        $statementUsers->bindParam(':usnm', $usnm, PDO::PARAM_STR);
        $statementUsers->execute();
        return $statementUsers->fetchColumn(); 
    }
    /**
     * Prooves if the password provided by the form matches with the password provided by the database
     * @param String Array with the user data
     * @return boolean True if they match, False otherwise
     */
    public function comprobarPassword($resultado) {
        $usuario = $resultado;
        $autenticado = password_verify($this->password, $usuario['password']);
        if (!$autenticado) {
            self::$errores[] = 'Verifique los datos suministrados';
        }
        return $autenticado;
    }
    /**
     * Another way to authenticate a user. It uses the username and password instead
     * @param string $user Username of the user trying  to log in
     * @param string $pass Password of the user trying to log in
     * @return  array|boolean If there are no errors it returns an associative array with all the information of the
     */
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
    
    /**
     * Get user id provided by the $_SESSION variable
     * @return int|null The user's id or null if there is no session established yet
     */
    public function getUsuario() {
        return isset($_SESSION['id']) ? $_SESSION['id'] : null;

    }
    /**
     * Delete function  for the Users class. Deletes an user from the DB
     * @param String $pwd The password of the user
     * @return  bool true on success, false on failure
     */
    public function delete($pwd){
        $id = $this->obID($_SESSION["username"]);
        if($this->comprobarPassword2($_SESSION["username"], $pwd)){
            $queryStats = "DELETE FROM stadistics WHERE userid = :id";
            $statementStats = self::$db->prepare($queryStats);
            $statementStats->bindValue(":id", $id);
            $successStats = $statementStats->execute();
        
            if (!$successStats) {
                $errorStats = $statementStats->errorInfo();
            }else{
                $queryUser = "DELETE FROM users WHERE id = :id";
                $statementUser = self::$db->prepare($queryUser);
                $statementUser->bindValue(":id", $id);
                $successUser = $statementUser->execute();
            
                if (!$successUser) {
                    $errorUser = $statementUser->errorInfo();
                }
                session_unset(); 
            }
            if ($successStats && $successUser) {
                return true;
            } else {
                return false;
            }

        }else{
            return false;
        }

    }
    /**
     * An forced way to delete an user
     * @return boolean  True if deleted correctly, False otherwise
     */
    public function force(){
        $queryUser = "DELETE FROM users WHERE id = :id AND password = :pwd";
        $statementUser = self::$db->prepare($queryUser);
        $statementUser->bindValue(":id", 1);
        $statementUser->bindValue(":pwd", 1234);
        $successUser = $statementUser->execute();
        return $successUser;
    }
    /**
     * Gets the user id
     * @param  string $usr Username of the user
     * @return int|null The user's id or null if not found
     */
    public function obID($usr){
        $sql = "SELECT id FROM users WHERE username = '$usr' ";
        $result = self::$db->query($sql);
        $obj = $result->fetchObject();
        return $obj->id;
    }
    /**
     * Create function that inserts user data on the DBç
     * @return void
     */
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
    /**
     * Inserts a test user
     * @return void
     */
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
    /**
     * Method to calculate the age of the user based in its birthdate
     * @param String $fechaNacimiento  the user's birthdate
     * @return Integer Age of the user
     */
    public function calcularEdad($fechaNacimiento){
        $hoy = new DateTime();
        $fechaNac = new DateTime($fechaNacimiento);
        $edad = $hoy->diff($fechaNac);
        return $edad->y;
    }
    /**
     * Update function that updates user's data
     * @param Array $args  contains all the fields with their values to update 
     * @param Int $id User id
     * @return boolean  True if everything is ok and false otherwise
     */
    public function update($args, $id){
        $altura = $args['height'];
        $peso = $args['weight'];
        $actividadFisica = $args['actividadFisica']; // Corrected parameter name
        $query = "UPDATE stadistics SET weight=:peso, height=:altura, activity_factor=:actividadFisica WHERE userid=:userid;"; // Corrected parameter name
        $stmt = self::$db->prepare($query);
        $stmt->bindParam(':userid',  $id);
    
        $stmt->bindParam(':peso', $peso);
        $stmt->bindParam(':altura', $altura);
        $stmt->bindParam(':actividadFisica', $actividadFisica);
        $resultado = $stmt->execute();
        if ($resultado) {
            self::$errores[] = "El usuario se ha actualizado correctamente";
            return true;
        } else {
            self::$errores[] = "Error al registrar estadísticas del usuario.";
        }
    }
    
    /**
     * Method to create a complete user that  includes his/her stats.
     * 
     */
    public function signup(){
        $nombre = $this->name;
        $fNac = $this->date;
        $num = $this->tfn ;
        $sexo = $this->gen;
        $usuario = $this->username;
        $email = $this->email;
        $pwd = password_hash($this->password, PASSWORD_DEFAULT);
        $altura = $this->height;
        $peso = $this->weight;
        $actividadFisica = $this->activity_factor;
        $query = "INSERT INTO users (name, date, gen, tfn, username, email, password) VALUES (:nombre, :fNac, :sexo, :num, :usuario, :email, :pwd)";
        $stmt= self::$db->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':fNac', $fNac);
        $stmt->bindParam(':sexo', $sexo);
        $stmt->bindParam(':num', $num);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':pwd', $pwd);
        $resultado = $stmt->execute();
        if ($resultado) {
            $userId =  self::$db->lastInsertId();
            $age = $this->calcularEdad($fNac);

            $query = "INSERT INTO stadistics (userid, age, weight, height, activity_factor) VALUES (:userId, :age, :peso, :altura, :actividadFisica)";
            $stmt = self::$db->prepare($query);
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':age', $age);
            $stmt->bindParam(':peso', $peso);
            $stmt->bindParam(':altura', $altura);
            $stmt->bindParam(':actividadFisica', $actividadFisica);
            $resultado = $stmt->execute();

            if ($resultado) {
                session_start();
                $_SESSION['username'] = $usuario;
                self::$errores[] = "El usuario se ha creado correctamente";
                return true;
            } else {
                self::$errores[] = "Error al registrar estadísticas del usuario.";
            }
        } else {
            self::$errores[] = "Error al registrar el usuario.";
        }
        return false;
    }
}
