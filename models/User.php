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
use Model\Statistic;

class User extends ActiveRecord
{
    /**
     * The database table for the user.
     *
     * @var string
     */
    protected static $table = 'users';

    /**
     * The columns of the database table for the user.
     *
     * @var array
     */
    protected static $columnasDB = ['id', 'name', 'date', 'gen', 'tfn', 'username', 'email', 'password'];

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
    public function __construct($args = [])
    {
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
     * 
     * @return string Returns the username value.
     */
    public function getusnm()
    {
        return $this->username;
    }

    /**
     * Stablish a connection to the database
     * 
     * @param PDO $pdo A PDO object representing a connection to the database.
     */
    public static function setDB($database)
    {
        self::$db = $database;
    }

    /**
     * Gets the user data
     * 
     * @param String $username The username of the user
     * 
     * @return Array|null Returns an associative array with the user's data if found, otherwise returns NULL
     */
    public static function loadUserData($usnm)
    {
        $queryUsers = "SELECT * FROM users WHERE username = :usnm";
        $statementUsers = self::$db->prepare($queryUsers);
        $statementUsers->bindParam(':usnm', $usnm, PDO::PARAM_STR);
        $statementUsers->execute();
        $rowU = $statementUsers->fetch(PDO::FETCH_ASSOC);
        return $rowU;
    }



    /**
     * Proves if an user exists
     * 
     * @return boolean True if the user exists, false otherwise
     */
    public function existeUsuario()
    {
        $query = "SELECT * FROM " . self::$tabla . " WHERE username = :username;";
        $statement = self::$db->prepare($query);
        $statement->bindParam(':username', $this->username, PDO::PARAM_STR);
        $statement->execute();
        $resultado = $statement->fetch(PDO::FETCH_ASSOC);

        // Verifica si $resultado es false (no se encontraron filas)
        if ($resultado === false) {
            self::$errors[] = 'The user does not exixsts';
            return false;
        }

        // Ahora podemos acceder al 'id' en $resultado de manera segura
        $id = $resultado['id'];

        // También verificamos si hay strikes para este usuario
        if ($this->getStrikes($id) === 3) {
            self::$errors[] = 'The user does not have permission to log in due to accumulating too many warnings.';
            return false;
        }

        return $resultado;
    }


    /**
     * Proves if the user have any strikes
     * 
     */
    public function getStrikes($id)
    {
        if (isset($id)) {
            $stats = new Statistic($id);
            return $stats->getStrikes();
        }
    }
    /**
     * Another form to prove the existence of an user
     * 
     * @return boolean False if the user exists, true otherwise.
     */
    public function existeUsuario2()
    {
        $query = "SELECT * FROM " . self::$table . " WHERE username = :username;";
        $statement = self::$db->prepare($query);
        $statement->bindParam(':username', $this->username, PDO::PARAM_STR);
        $statement->execute();

        $resultado = $statement->rowCount();
        if ($resultado === 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Validate the login form
     * 
     * @return array|null array with errors on invalid data, null if no errors found
     */
    public function validateLF()
    {
        if (!$this->username) {
            self::$errors[] = 'El nombre de usuario es obligatorio';
        }
        if (!$this->password) {
            self::$errors[] = 'El password es obligatorio';
        }
        return self::$errors;
    }
    /**
     * Validate the signup form
     * 
     * @return array|null array with errors on invalid data, null if no errors found
     */
    public function validateSF()
    {
        $errors = [];
        if (empty($this->username)) {
            $errors['username'] = 'The username field cannot be empty. Please enter a valid username.';
        }
        if (empty($this->password)) {
            $errors['password'] = 'The password field cannot be empty. Please enter a valid password.';
        }
        if (empty($this->name)) {
            $errors['name'] = 'The name field cannot be empty. Please enter a valid name.';
        }
        if (empty($this->date)) {
            $errors['date'] = 'The birth date field cannot be empty. Please enter a valid date.';
        }
        if (empty($this->gen)) {
            $errors['gender'] = 'The gender field cannot be empty. Please pick an option.';
        }
        if (empty($this->tfn) || !preg_match('/^\d{9,}$/', $this->tfn)) {
            $errors['contact_phone'] = 'The contact phone field is empty or not in a valid format. Please enter a valid phone number (9 digits).';
        }
        if (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'The email field is empty or not in a valid format. Please enter a valid email.';
        }
        if (empty($this->height) || !is_numeric($this->height)) {
            $errors['height'] = 'The height field is empty or not a valid number. Please enter a valid number.';
        }
        if (empty($this->weight) || !is_numeric($this->weight)) {
            $errors['weight'] = 'The weight field is empty or not a valid number. Please enter a valid number.';
        }
        if (empty($this->activity_factor)) {
            $errors['activity_factor'] = 'The Activity Factor field cannot be empty. Please pick an option.';
        }
        return $errors;
    }


    /**
     * Proves if the password provided by the form matches with the password provided by the database
     * 
     * @param String Array with the user data
     * 
     * @return boolean True if they match, False otherwise
     */
    public function comprobarPassword($resultado)
    {
        $usuario = $resultado;
        $autenticado = password_verify($this->password, $usuario['password']);
        if (!$autenticado) {
            self::$errors[] = 'Verifique los datos suministrados';
        }
        return $autenticado;
    }

    /**
     * Another way to authenticate a user. It uses the username and password instead
     * 
     * @param string $user Username of the user trying to log in
     * @param string $pass Password of the user trying to log in
     * 
     * @return  array|boolean If there are no errors it returns an associative array with all the information of the
     */
    public function comprobarPassword2($usnm, $pwd)
    {
        $query = "SELECT * FROM users WHERE username=:usnm";
        $statement = self::$db->prepare($query);
        $statement->bindParam(":usnm", $usnm, PDO::PARAM_STR);
        if (!$statement->execute()) throw new \Exception('No se pudo ejecutar la consulta');
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        $autenticado = password_verify($pwd, $user['password']);
        if (!$autenticado) {
            self::$errors[] = 'Verifique los datos suministrados';
        }
        return $autenticado;
    }

    /**
     * Get user id provided by the $_SESSION variable
     * 
     * @return int|null The user's id or null if there is no session established yet
     */
    public function getUsuario()
    {
        return isset($_SESSION['id']) ? $_SESSION['id'] : null;
    }

    /**
     * Delete function for the Users class. Deletes an user from the DB
     * 
     * @param String $pwd The password of the user
     * 
     * @return bool true on success, false on failure
     */
    public function delete($pwd)
    {
        $id = $this->obID($_SESSION["username"]);
        if ($this->comprobarPassword2($_SESSION["username"], $pwd)) {
            $stats = new Statistic($id);
            $successStats = $stats->deleteStats();
            if ($successStats) {
                $queryUser = "DELETE FROM users WHERE id = :id";
                $statementUser = self::$db->prepare($queryUser);
                $statementUser->bindValue(":id", $id);
                $successUser = $statementUser->execute();
                session_unset();
            }
            if ($successStats && $successUser) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Gets the user id
     * 
     * @param  string $usr Username of the user
     * 
     * @return int|null The user's id or null if not found
     */
    public function obID($usr)
    {
        $sql = "SELECT id FROM users WHERE username = '$usr' ";
        $result = self::$db->query($sql);
        $obj = $result->fetchObject();
        return $obj->id;
    }

    /**
     * Inserts a test user
     * 
     * @return void
     */
    public static function insertExampleUser()
    {
        try {
            $query = "INSERT INTO users (name, date, gen, tfn, img, username, email, password) 
            VALUES ('Acoexo', '2004-10-18', 'H', 123456789, NULL, 'acoexo', 'johndoe@example.com', :pass);";
            $stm = self::$db->prepare($query);
            $password = password_hash("123456", PASSWORD_DEFAULT);
            $stm->bindValue(":pass", $password, PDO::PARAM_STR);
            $stm->execute();
            $query = "INSERT INTO statistics (userid, age, weight, height, activity_factor, strikes) 
            VALUES (1, 30, 75, 180, 2, 0);";
            $stm = self::$db->prepare($query);
            $stm->execute();
            echo "<script>alert(\"Se ha creado el usuario correctamente\")</script>";
        } catch (PDOException $e) {
            die("Error al intentar registrar el usuario: " . $e->getMessage());
        }
    }

    /**
     * Method to calculate the age of the user based on its birthdate
     * 
     * @param String $fechaNacimiento the user's birthdate
     * 
     * @return Integer Age of the user
     */
    public function calcularEdad($fechaNacimiento)
    {
        $hoy = new DateTime();
        $fechaNac = new DateTime($fechaNacimiento);
        $edad = $hoy->diff($fechaNac);
        return $edad->y;
    }

    /**
     * Update function that updates user's data
     * 
     * @param Array $args contains all the fields with their values to update 
     * @param Int $id User id
     * 
     * @return boolean True if everything is ok and false otherwise
     */
    public function update($args, $id)
    {
        $stats = new Statistic();
        $resultado = $stats->updateStats($id, $args['height'], $args['weight'], $args['activity_factor']);
        if ($resultado) {
            self::$errors[] = "El usuario se ha actualizado correctamente";
            error_log("Success in update function: El usuario se ha actualizado correctamente. \n", 3, './../errorLog/error.log');
            return true;
        } else {
            self::$errors[] = "";
            error_log("Error al registrar estadísticas del usuario. \n", 3, './../errorLog/error.log');
        }
    }

    /**
     * Method to create a complete user that includes his/her stats.
     * @return boolean Returns true if the user signup correctly, false otherwise
     */
    public function signup()
    {
        $nombre = $this->name;
        $fNac = $this->date;
        $num = $this->tfn;
        $sexo = $this->gen;
        $usuario = $this->username;
        $email = $this->email;
        $pwd = password_hash($this->password, PASSWORD_DEFAULT);
        $height = $this->height;
        $weight = $this->weight;
        $activity_factor = $this->activity_factor;
        $query = "INSERT INTO users (name, date, gen, tfn, username, email, password) VALUES (:nombre, :fNac, :sexo, :num, :usuario, :email, :pwd)";
        $stmt = self::$db->prepare($query);
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
            $stats = new Statistic($userId, $weight, $height, $activity_factor);
            $resultado = $stats->createStats($age);
            if ($resultado) {
                session_start();
                $_SESSION['username'] = $usuario;
                self::$errors[] = "El usuario se ha creado correctamente";
                return true;
            } else {
                self::$errors[] = "Error al registrar estadísticas del usuario.";
            }
        } else {
            self::$errors[] = "Error al registrar el usuario.";
        }
        return false;
    }
}
