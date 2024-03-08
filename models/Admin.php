<?php

namespace Model;
use PDO;

/**
 * Class Admin
 * 
 * Represents an administrator in the application.
 */
class Admin extends ActiveRecord
{
    // Database
    protected static $table = 'admin';
    protected static $columnsDB = ['id','name', 'email', 'password'];

    private $id;
    private $name;
    private $email;
    private $password;
    protected static $errors = [];

    /**
     * Constructor of the Admin class
     * 
     * @param array $args Arguments for the initialization of the Admin
     */
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->name = $args['name'] ?? null;
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
    }

    /**
     * Validate the Admin data
     *
     * @return array Array with the error messages
     */
    public function validate()
    {
        if (!$this->name) {
            self::$errors[] = 'The name is required';
        }
        if (!$this->password) {
            self::$errors[] = 'The password is required';
        }
        return self::$errors;
    }

    /**
     * Verify the existence of the Admin in the table
     *
     * @return mixed|null Object with the Admin if exists, null otherwise
     */
    public function adminExists()
    {
        $query = "SELECT * FROM " . self::$table . " WHERE name = " . "'" . $this->name . "'" . " LIMIT 1";
        $statement = self::$db->query($query);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if ($result === false) {
            self::$errors[] = 'The admin does not exists';
            return false;
        }
        $id = $result['id'];
        return $result;

    }

    /**
     * Verify the password is correct
     *
     * @param mixed $result SQL query result
     * @return bool True if the password is correct, False otherwise
     */
    public function checkPassword($result)
    {

        $authenticated = password_verify($this->password, $result['password']);

        if (!$authenticated) {
            self::$errors[] = 'Verify the provided data';
        }

        return $authenticated;
        
    }

    /**
     * Start session for the administrator
     *
     * @return void
     */
    public function authenticate()
    {
        session_start();
        $_SESSION['admin'] = $this->email;
        $_SESSION['login'] = true;
        header('Location: /admin');
    }

    /**
     * Load users from the database
     *
     * @return mixed JSON with users data
     */
    public function load()
    {
        $sql = "SELECT id, name FROM admin LIMIT 10";
        $result = self::$db->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $admin[] = $row;
            }
        }
        header('Content-Type: application/json');
        return json_encode($admin);
    }

    /**
     * Search users in the database
     *
     * @param string $search Search term
     * @return array Array with data of found users
     */
    public function search($search)
    {
        $sql = "SELECT id FROM admin WHERE name LIKE '%$search%' LIMIT 10";
        $result = self::$db->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $admin[] = $row;
            }
        }
        return $admin;
    }

    /**
     * Print users in JSON format
     *
     * @param array $admin Array with admin data
     * @return mixed JSON with admin data
     */
    public function printAdmin($admin)
    {
        header('Content-Type: application/json');
        return json_encode($admin);
    }

    /**
     * Proves if the admin has admin options
     * 
     * @param String $username The name of the admin that we want to check
     * 
     * @return Boolean True if the admin has admin options, false otherwise
     */
    public function isAdmin($name)
    {
        $adminQuery = "SELECT * FROM admin WHERE name=:name";
        $statementAdmin = self::$db->prepare($adminQuery);
        $statementAdmin->bindParam(':name', $name, PDO::PARAM_STR);
        $statementAdmin->execute();
        return $statementAdmin->fetchColumn();
    }

    /**
     * Get name property.
     * 
     * @return string Returns the name value.
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Create a test Admin
     */
    public static function testAdmin(){
        try {
            $adminQuery = "INSERT INTO " . self::$table . " (" . implode(', ', self::$columnsDB) . ") VALUES (default, :name, :email, :password)";
            $statementAdmin = self::$db->prepare($adminQuery);
            $name = 'Joh123'; 
            $email = 'admin@admin.com';
            $password = password_hash('1234', PASSWORD_DEFAULT); 
            $statementAdmin->bindParam(':name', $name, PDO::PARAM_STR);
            $statementAdmin->bindParam(':email', $email, PDO::PARAM_STR);
            $statementAdmin->bindParam(':password', $password, PDO::PARAM_STR);
            $statementAdmin->execute();
            error_log("SUCCESS: Created Admin by testAdmin function in Admin.php", 3, './../errorLog/error.log');
            return true;
        } catch (\PDOException $e) {
            error_log("ERROR: Database error in testAdmin function, Admin.php: " . $e->getMessage(),3, './../errorLog/error.log');
            return false;
        }
    }
    
}