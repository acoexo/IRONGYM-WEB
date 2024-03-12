<?php
define('TEMPLATES_URL', __DIR__ . '\\templates');
define('FUNCIONES_URL', __DIR__ . '\\funciones.php');
define('JS_URL', __DIR__ . '\\..\\public\\src\\phpfunctions');
define('LOGIN_REDIRECT_URL', '/user/mainpage');
define('LOGOUT_REDIRECT_URL', '/');
define('SIGNUP_REDIRECT_URL', '/user/mainpage');
function incluirTemplate($nombre, $inicio = false)
{
    include TEMPLATES_URL . "\\{$nombre}.php";
}
function loadPHPFunction($nombre)
{
    include JS_URL . "\\{$nombre}.php";
}
function estaAutenticado()
{
    session_start();
    if (!$_SESSION['login']) {
        header('Location: /');
        return false;
    }
    return true;
}
function debuguear($variable)
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
}
function s($html): string
{
    $s = htmlspecialchars($html);
    return $s;
}
