<?php

namespace Controllers;

use MVC\Router;
use Model\Admin;
use Controllers\PageController;

class AdminController
{
    /**
     * Handles the login functionality
     * 
     * @param Router $router The router instance
     * @return void
     */
    public static function adminlogin(Router $router)
    {
        try {
            $errors = [];
            session_start();
            if (isset($_SESSION['admin'])) {
                header("Location: /admin/amp");
            } else {
                if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                    $auth = new Admin($_POST);
                    $usnm = $auth->getName();
                    $errors = $auth->validate();
                    if (empty($errors)) {
                        $resultado = $auth->adminExists();
                        if (!$resultado) {
                            $errors = Admin::getErrors();
                        } else {
                            $autenticado = $auth->checkPassword($resultado);
                            if ($autenticado) {
                                error_log("Success Admin loged correctly: \n", 3, './../errorLog/error.log');

                                $_SESSION['admin'] = $usnm;
                                header("Location: /admin/amp");
                            } else {
                                throw new \Exception('Wrong Password');
                            }
                        }
                    }else{error_log("ERROR: error array is not empty: \n", 3, './../errorLog/error.log');}
                }
                $router->render('admin/adminlogin', ['errors' => $errors]);
            }
        } catch (\Exception $e) {
            error_log("ERROR: Error in adminLogin function, AdminController : " . $e->getMessage() . "\n", 3, './../errorLog/error.log');
            $router->render('admin/adminlogin', ['errors' => ['message' => $e->getMessage()]]); // Render the login view with error message
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
    public static function example(){
        Admin::testAdmin();
    }
    
}
