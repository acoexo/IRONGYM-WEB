<?php

namespace Controllers;

use MVC\Router;
use Model\Admin;
use Model\User;

class PageController {
    public static function index(Router $router) {
        $router->render('/paginas/index');
    }
    public static function example(Router $router) {
        $router->render('/example/insertUser');
    }
    public static function nosotros(Router $router) {
        $router->render('paginas/nosotros', [
        ]);
    }
    public static function blog(Router $router) {
        $router->render('paginas/blog');
    }
    public static function entrada(Router $router) {
        $router->render('paginas/entrada');
    }
    public static function mainPageLoader(Router $router){
        session_start();
        $usr = $_SESSION['username'];
        $usrData = User::loadUserData($usr);
        $usrStats = User::loadStatisticData($usrData['id']);
        $router->render('user/main_page', [
            'user'=>$usr,
            'usrData'=>$usrData,
            'usrStats'=>$usrStats
        ]);
    }
    public static function adminlogin(Router $router){
        $router->render('auth/adminlogin');
    }
    public static function delteUser(Router $router){
        session_start();
        $usr = $_SESSION['username'];
        $usrData = User::loadUserData($usr);
        $usrStats = User::loadStatisticData($usrData['id']);
        $router->render('user/delete' , [
            'user'=>$usr,
            'usrData'=>$usrData,
            'usrStats'=>$usrStats
        ]);
    }
    public static function updateUser(Router $router){
        session_start();
        $usr = $_SESSION['username'];
        $usrData = User::loadUserData($usr);
        $usrStats = User::loadStatisticData($usrData['id']);
        $router->render('user/update' , [
            'user'=>$usr,
            'usrData'=>$usrData,
            'usrStats'=>$usrStats
        ]);
    }
    public static function adminpage(Router $router){
        // $admin = Admin::get();
        $router->render('admin/adminmainpage'/*, [
            // 'admin' => $admin
        ]*/);
    }
    public static function createUser(Router $router){
        $router->render('auth/signup');
    }
    public static function reload(Router $router){
        session_start();
        session_destroy();
        $router->render('/paginas/index');
    }
}