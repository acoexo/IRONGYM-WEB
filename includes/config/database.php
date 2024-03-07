<?php

/**
 * Connects to the database using PDO
 *
 * @return PDO Retorna una instancia de PDO si la conexión tiene éxito
 * @throws PDOException Si ocurre un error al conectar a la base de datos
 */
function connectDB()
{
    try {
        // Se intenta establecer una conexión a la base de datos utilizando las credenciales proporcionadas en las variables de entorno
        $db = new PDO(
            "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DB'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASS']
        );
        // Se establece el modo de error de PDO para que lance excepciones en caso de errores
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Se devuelve la instancia de PDO para ser utilizada en la conexión a la base de datos
        return $db;
    } catch (PDOException $e) {
        // Si ocurre un error al conectar a la base de datos, se registra el error en el log de errores y se muestra un mensaje al usuario
        error_log("Error al conectar a la base de datos: " . $e->getMessage(), 0);
        echo "Ha ocurrido un error. Por favor, inténtalo de nuevo más tarde." . $e->getMessage();
        exit; // Se termina la ejecución del script debido al error en la conexión
    }
}
