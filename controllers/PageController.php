<?php

namespace Controllers;

use MVC\Router;
use Model\Admin;
use Model\Statistic;
use Model\User;

/**
 * Class PageController
 * @package Controllers
 */
class PageController
{
    /**
     * Renders the index page
     *
     * @param Router $router The router instance
     * @return void
     */
    public static function index(Router $router)
    {
        $router->render('/paginas/index');
    }
    /**
     * Renders the admin index page
     *
     * @param Router $router The router instance
     * @return void
     */
    public static function adindex(Router $router)
    {
        header("Location: /admin/adminlogin");
    }

    /**
     * Renders the example page
     *
     * @param Router $router The router instance
     * @return void
     */
    public static function example(Router $router)
    {
        $router->render('/example/insertUser');
    }

    /**
     * Renders the nosotros page
     *
     * @param Router $router The router instance
     * @return void
     */
    public static function nosotros(Router $router)
    {
        $router->render('paginas/nosotros', []);
    }

    /**
     * Renders the blog page
     *
     * @param Router $router The router instance
     * @return void
     */
    public static function blog(Router $router)
    {
        $router->render('paginas/blog');
    }

    /**
     * Renders the entrada page
     *
     * @param Router $router The router instance
     * @return void
     */
    public static function entrada(Router $router)
    {
        $router->render('paginas/entrada');
    }

    /**
     * Loads the main page for the user
     *
     * @param Router $router The router instance
     * @return void
     */
    public static function mainPageLoader(Router $router)
    {
        session_start();
        $usr = $_SESSION['username'];
        $usrData = User::loadUserData($usr);
        $usrStats = Statistic::loadStatisticData($usrData['id']);
        $router->render('user/main_page', [
            'user' => $usr,
            'usrData' => $usrData,
            'usrStats' => $usrStats
        ]);
    }

    /**
     * Renders the admin login page
     *
     * @param Router $router The router instance
     * @return void
     */
    public static function adminlogin(Router $router)
    {
        $router->render('auth/adminlogin');
    }

    /**
     * Renders the delete user page
     *
     * @param Router $router The router instance
     * @return void
     */
    public static function delteUser(Router $router)
    {
        session_start();
        $usr = $_SESSION['username'];
        $usrData = User::loadUserData($usr);
        $usrStats = Statistic::loadStatisticData($usrData['id']);
        $router->render('user/delete', [
            'user' => $usr,
            'usrData' => $usrData,
            'usrStats' => $usrStats
        ]);
    }

    /**
     * Renders the update user page
     *
     * @param Router $router The router instance
     * @return void
     */
    public static function updateUser(Router $router)
    {
        session_start();
        $usr = $_SESSION['username'];
        $usrData = User::loadUserData($usr);
        $usrStats = Statistic::loadStatisticData($usrData['id']);
        $router->render('user/update', [
            'user' => $usr,
            'usrData' => $usrData,
            'usrStats' => $usrStats
        ]);
    }

    /**
     * Renders the admin main page
     *
     * @param Router $router The router instance
     * @return void
     */
    public static function adminpage(Router $router)
    {
        // $admin = Admin::get();
        $router->render('admin/adminmainpage'/*, [
            // 'admin' => $admin
        ]*/);
    }

    /**
     * Renders the create user page
     *
     * @param Router $router The router instance
     * @return void
     */
    public static function createUser(Router $router)
    {
        $router->render('auth/signup');
    }

    /**
     * Reloads the index page after destroying the session
     *
     * @param Router $router The router instance
     * @return void
     */
    public static function reload(Router $router)
    {
        session_start();
        session_destroy();
        $router->render('/paginas/index');
    }
}
