<?php
function connectDB(){
    try {
        $db = new PDO(
            "mysql:host=".$_ENV['DB_HOST'].";dbname=".$_ENV['DB_DB'],
            $_ENV['DB_USER'], 
            $_ENV['DB_PASS']);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        error_log("Error al conectar a la base de datos: " . $e->getMessage(), 0);
        echo "Ha ocurrido un error. Por favor, inténtalo de nuevo más tarde.".$e->getMessage();
        exit;
    }
}
?>