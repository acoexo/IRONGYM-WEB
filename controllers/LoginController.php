<?php 
namespace Controllers;

use MVC\Router;
use Model\User;

class LoginController {
    public static function login(Router $router) {
        try {
            $errores = [];
            session_start();
            if(isset($_SESSION['username'])){
                header("Location: /user/mainpage");
            }else{
                if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST')  {
                    $auth = new User($_POST);
                    $usnm = $auth->getusnm();
                    $errores = $auth->validar();
                    if (empty($errores)) {
                        $resultado = $auth->existeUsuario();
                        if (!$resultado) {
                            $errores = User::getErrores();
                        } else {
                            $logMessage = "Not an error: ";
                            foreach ($resultado as $row) {
                                $logMessage .= $row . " ";
                            }
                            error_log($logMessage . "\n", 3, './../errorLog/error.log'); 
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
            error_log("Error in login function: " . $e->getMessage()."\n", 3, './../errorLog/error.log');
            $router->render('auth/login', ['errores' => ['message' => $e->getMessage()]]); // Renderizar la vista de login con mensaje de error
        }

    }

    public static function logout() {
        try {
            session_start();
            $_SESSION = [];
            header('Location: /');
        } catch (\Exception $e) {
            error_log("Error in logout function: " . $e->getMessage()."\n", 3, './../errorLog/error.log');
        }
    }
    
    public static function signup(Router $router){
        try {
            $errores = [];
            $router->render('auth/signup', ['errores' => $errores]);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $auth = new User($_POST);
                $errores = $auth->validar();
                if (empty($errores)) {
                    $resultado = $auth->existeUsuario();
                     if (!$resultado) {
                        $errores = User::getErrores();
                    } else {
                        $logMessage = "Not an error: ";
                        foreach ($resultado as $row) {
                            $logMessage .= $row . " ";
                        }
                        error_log($logMessage . "\n", 3, './../errorLog/error.log'); 
                    }
                }
            }
        } catch(\Exception $e){
            error_log("Error in signup function: " . $e->getMessage()."\n", 3, './../errorLog/error.log');
        }
    }
}
?>
