<?php
use Model\ActiveRecord;
function load(){
    try {
        $ac = new ActiveRecord();
        $pdo = $ac::getDB();
    
        // Consulta para obtener los primeros 10 usuarios
        $query = "SELECT id, username FROM users LIMIT 10";
        $statement = $pdo->prepare($query);
        $statement->execute();
        $users = $statement->fetchAll(PDO::FETCH_ASSOC);
    
        // Guardar resultados en un archivo JSON
        $json_data = json_encode($users);
        $file_path = 'users.json';
    
        if (!file_exists($file_path)) {
            // Si el archivo no existe, crearlo
            file_put_contents($file_path, $json_data);
        } else {
            // Si el archivo existe, agregar los datos al final
            $current_data = file_get_contents($file_path);
            $current_users = json_decode($current_data, true);
            $current_users = array_merge($current_users, $users);
            $json_data = json_encode($current_users);
            file_put_contents($file_path, $json_data);
        }
    
    } catch (PDOException $e) {
        // En caso de error en la conexión o la consulta, manejarlo adecuadamente
        http_response_code(500); // Error interno del servidor
    }
}

?>
