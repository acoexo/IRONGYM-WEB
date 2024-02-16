<?php 
namespace Controllers;

use MVC\Router;
use Model\User;
use Controllers\PageController;
class UserController {
    public static function updateStats(){
        session_start();
    }
    public static function delete(){
        session_start();
        if(isset($_POST['password'])){
            $password = $_POST['password'];
            if(isset($_SESSION['username'])){
                $auth = new User($_SESSION['username'], $password);
                if($auth->comprobarPassword2($_SESSION['username'], $password)){
                    if($auth->isAdmin($_SESSION['username'])){
                        $deleted = $auth->delete($password);
                        if($deleted){
                            echo "Usuario eliminado exitosamente.";
                        } else {
                            echo "Error al eliminar el usuario.";
                        }
                    } else {
                        echo "No tienes permiso para eliminar usuarios.";
                    }
                } else {
                    echo "Contraseña incorrecta.";
                }
            } else {
                echo "Usuario no autenticado.";
            }
        } else {
            echo "No se proporcionó la contraseña.";
        }
    }
    
    
}
