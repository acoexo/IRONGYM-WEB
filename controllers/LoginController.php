<?php

namespace Controllers;

use MVC\Router;
use Model\User;

class LoginController
{
    public static function login(Router $router)
    {
        try {
            $errors = [];
            session_start();
            if (isset($_SESSION['username'])) {
                header("Location: " . LOGIN_REDIRECT_URL);
                exit;
            } else {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $auth = new User($_POST);
                    $errors = $auth->validateLF();
                    if (empty($errors)) {
                        $usuario = $auth->existeUsuario();
                        if ($usuario) {
                            $autenticado = $auth->comprobarPassword($usuario);
                            if ($autenticado) {
                                $_SESSION['username'] = $usuario['username'];
                                header("Location: " . LOGIN_REDIRECT_URL);
                                exit;
                            } else {
                                throw new \Exception('Contraseña incorrecta');
                            }
                        } else {
                            throw new \Exception('Usuario no encontrado');
                        }
                    }
                }
                $router->render('auth/login', ['errors' => $errors]);
            }
        } catch (\Exception $e) {
            error_log("Error en la función de inicio de sesión: " . $e->getMessage() . "\n", 3, './../errorLog/error.log');
            $router->render('auth/login', ['errors' => ['message' => 'Hubo un problema al iniciar sesión.']]);
        }
    }

    public static function logout(Router $router)
    {
        try {
            session_start();
            $_SESSION = [];
            session_destroy();
            header('Location: ' . LOGOUT_REDIRECT_URL);
            exit;
        } catch (\Exception $e) {
            error_log("Error en la función de cierre de sesión: " . $e->getMessage() . "\n", 3, './../errorLog/error.log');
            $router->render('error', ['errors' => ['message' => 'Hubo un problema al cerrar sesión.']]);
        }
    }

    public static function renderForm($errors = [])
    {
        // Devolver los valores previamente ingresados (si existen)
        return $_POST;
    }

    public static function signup(Router $router)
    {
        try {
            $errors = [];
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $auth = new User($_POST);
                $errors = $auth->validateSF();
                if (empty($errors)) {
                    $existingUser = $auth->existeUsuario2();
                    error_log("Succes?: ".$_POST. "\n", 3, './../errorLog/error.log');
                    if ($existingUser) {
                        $auth->signup();
                        $_SESSION['username'] = $_POST['username'];
                        header("Location: " . SIGNUP_REDIRECT_URL);
                        exit;
                    } else {
                        $errors[] = "El correo electrónico o el nombre de usuario ya existen en la base de datos. Por favor, elija otro.";
                    }
                } else {
                    // Renderizar el formulario con los valores previamente ingresados
                    $formData = self::renderForm();
                    $router->render('auth/signup', ['errors' => $errors, 'formData' => $formData]);
                    return;
                }
            }
            // Renderizar el formulario con valores vacíos (para la primera carga)
            $router->render('auth/signup', ['errors' => $errors]);
        } catch (\Exception $e) {
            error_log("Error en la función de registro: " . $e->getMessage() . "\n", 3, './../errorLog/error.log');
            $router->render('auth/signup', ['errors' => ['message' => 'Hubo un problema al registrarse.']]);
        }
    }
}
