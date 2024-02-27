<?php
// Incluye tu archivo de clase Admin
require_once './load.php';
use Model\Admin;

// Crea una instancia de la clase Admin
$admin = new Admin();

// Obtén los usuarios usando el método printUsers
$users = $admin->printUsers($search); // Reemplaza $search con el valor de búsqueda, si es necesario

// Devuelve los usuarios como JSON
echo json_encode($users);
?>
