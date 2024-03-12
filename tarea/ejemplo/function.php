<?php
// Conexión a la base de datos (reemplaza con tus propias credenciales)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "irondb";

// Obtener los parámetros de búsqueda
$usernameParam = isset($_GET['username']) ? $_GET['username'] : '';
$idParam = isset($_GET['id']) ? $_GET['id'] : '';

try {
    // Crear conexión utilizando PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Establecer el modo de error PDO en excepción
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Construir la consulta SQL basada en los parámetros de búsqueda
    $sql = "SELECT id, username FROM users WHERE 1=1";
    $params = array();

    if (!empty($usernameParam)) {
        $sql .= " AND username LIKE :username";
        $params[':username'] = "%$usernameParam%";
    }
    if (!empty($idParam)) {
        $sql .= " AND id = :id";
        $params[':id'] = $idParam;
    }

    // Agregar la cláusula LIMIT para limitar los resultados a 10 registros
    $sql .= " LIMIT 10";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);
    // Ejecutar la consulta con los parámetros
    $stmt->execute($params);
    // Obtener los resultados como un array asociativo
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Convertir el array a formato JSON y devolverlo
    echo json_encode($data);
} catch(PDOException $e) {
    // Manejar errores de conexión
    echo "Error de conexión: " . $e->getMessage();
}
