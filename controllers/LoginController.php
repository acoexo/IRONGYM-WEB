<?php

namespace Controllers;

use MVC\Router;
use Model\User;

/**
 * Class LoginController
 * @package Controllers
 */
class LoginController
{
    /**
     * Handles the login functionality
     * 
     * @param Router $router The router instance
     * @return void
     */
    public static function login(Router $router)
    {
        try {
            $errores = [];
            session_start();
            if (isset($_SESSION['username'])) {
                header("Location: /user/mainpage");
            } else {
                if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                    $auth = new User($_POST);
                    $usnm = $auth->getusnm();
                    $errores = $auth->validar();
                    if (empty($errores)) {
                        $resultado = $auth->existeUsuario();
                        if (!$resultado) {
                            $errores = User::getErrores();
                        } else {
                            $autenticado = $auth->comprobarPassword($resultado);
                            if ($autenticado) {
                                $_SESSION['username'] = $usnm;
                                header("Location: /user/mainpage");
                            } else {
                                throw new \Exception('Contraseña incorrecta');
                            }
                        }
                    }
                }
                $router->render('auth/login', ['errores' => $errores]);
            }
        } catch (\Exception $e) {
            error_log("Error in login function: " . $e->getMessage() . "\n", 3, './../errorLog/error.log');
            $router->render('auth/login', ['errores' => ['message' => $e->getMessage()]]); // Render the login view with error message
        }
    }

    /**
     * Handles the logout functionality
     * 
     * @return void
     */
    public static function logout()
    {
        try {
            session_start();
            $_SESSION = [];
            header('Location: /');
        } catch (\Exception $e) {
            error_log("Error in logout function: " . $e->getMessage() . "\n", 3, './../errorLog/error.log');
        }
    }

    /**
     * Handles the signup functionality
     * 
     * @param Router $router The router instance
     * @return void
     */
    public static function signup(Router $router)
    {
        try {
            $errores = [];
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $auth = new User($_POST);
                if (empty($errores)) {
                    $resultado = $auth->existeUsuario2();
                    debuguear($resultado);
                    if (!$resultado) {
                        $errores = User::getErrores();
                    } else {
                        $required_fields = ['name', 'date', 'tfn', 'username', 'email', 'password', 'height', 'weight', 'activity_factor', 'gen'];
                        $fields_missing = false;
                        foreach ($required_fields as $field) {
                            if (empty($_POST[$field])) {
                                $fields_missing = true;
                                $errores[] = "El campo $field es obligatorio.";
                            }
                        }
                        if ($_POST['activity_factor'] === '0') {
                            $fields_missing = true;
                            $errores[] = "Por favor, seleccione una actividad física válida.";
                        }
                        if ($_POST['gen'] === '0') {
                            $fields_missing = true;
                            $errores[] = "Por favor, seleccione un sexo válido.";
                        }
                        // If no required fields are missing
                        if (!$fields_missing) {
                            // Get form field values
                            $existingUser = $auth->existeUsuario();
                            if (!$existingUser) {
                                $auth->signup();
                                $_SESSION['username'] = $_POST['username'];
                                header("Location: /user/mainpage");
                            } else {
                                $errores[] = "El correo electrónico o el nombre de usuario ya existen en la base de datos. Por favor, elija otros.";
                            }
                        }
                    }
                }
            }
            $router->render('auth/signup', ['errores' => $errores]);
        } catch (\Exception $e) {
            error_log("Error in signup function: " . $e->getMessage() . "\n", 3, './../errorLog/error.log');
            $router->render('auth/signup', ['errores' => ['message' => $e->getMessage()]]); // Render the signup view with error message
        }
    }
}
