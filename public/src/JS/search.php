<?php
use Model\ActiveRecord;

function search(){
    try {
        // Obtener el término de búsqueda de la solicitud GET
        $query = $_GET['q'];
    
        // Establecer la conexión a la base de datos
        $ac = new ActiveRecord();
        $pdo = $ac::getDB();
    
        // Consulta SQL para buscar usuarios que coincidan con el término de búsqueda
        $query = "SELECT id, username FROM users WHERE username LIKE :query LIMIT 10";
        $statement = $pdo->prepare($query);
        $statement->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    
        // Guardar resultados de la búsqueda en un archivo JSON (reescribir en cada búsqueda)
        $json_data = json_encode($results);
        $file_path = 'search_results.json';
        file_put_contents($file_path, $json_data);
    
        // Devolver una respuesta de éxito
    } catch (PDOException $e) {
        // En caso de error en la conexión o la consulta, manejarlo adecuadamente
        http_response_code(500); // Error interno del servidor
    }
}
?>
