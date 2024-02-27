<?php
// Incluye tu archivo de clase Admin
use Model\Admin;

// Crea una instancia de la clase Admin
$admin = new Admin();

// Obtén los usuarios usando el método printUsers
$users = $admin->load($search); // Reemplaza $search con el valor de búsqueda, si es necesario

?>
