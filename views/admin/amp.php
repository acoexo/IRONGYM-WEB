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
        $file_path = '/public/src/JSON/users.json';
    
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
        $file_path = '/public/src/JSON/search.json';
        file_put_contents($file_path, $json_data);
    
        // Devolver una respuesta de éxito
    } catch (PDOException $e) {
        // En caso de error en la conexión o la consulta, manejarlo adecuadamente
        http_response_code(500); // Error interno del servidor
    }
}

load();
search();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="shortcut icon" href="./../../src/SVG/mancuerna_roja.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./../../src/css/normalize.css">
    <link rel="stylesheet" href="./../../src/css/main_page.css">
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME PAGE
         <?php echo $adminData['name'] ?> 
    </title>
</head>
<body>
    <main>
        <section class="nav">
            <button class="home-button" id="home-button"><img src="./../../src/img/botonMancuerna.png" alt=""></button>
            <div class="user">
                <h2 id="user-name">
                     <?php echo $adminData['name'] ?>
                </h2>
                <button id="user-button" class="user-button"><i class="bi bi-person"></i></button>
            </div>
            <button class="abrir-menu" id="abrir"> <i class="bi bi-list"></i> </button>
        </section>
        <section class="menu" id="menu">
            <div class="options-menu" id="options_menu">
                <h2 id="user-name">
                    <?php echo $adminData['name'] ?>
                </h2>
                <button class="cerrar-menu" id="cerrar"><i class="bi bi-x-lg"></i></button>
                <div class="links">
                    </br>
                    <a href="logout">LOG OUT</a>
                    </br>
                    <a class="link" href="#Nosotros">US</a>
                    </br>
                    <a class="link" href="#Contactos">CONTACT</a>
                    </br>
                    <a href="update" class="update" id="update">UPDATE STATS</a>
                </div>
            </div>
        </section>
        <section class="tabla_usuarios">
            <table id=results>
                <h1>Tabla de Usuarios</h1>
                <input type="text" id="search" placeholder="Buscar por nombre de usuario">
                <table id="userTable" border="1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre de Usuario</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <?php
                // Consulta SQL para buscar usuarios por nombre
                if (isset($_GET['q'])) {
                    $search = $_GET['q'];
                    $admin->printUsers($search);
                }
                ?>
        </section>
        <section class="user_properties">
            <div class="user_principal">
                <h1 id="respuesta"></h1>
            </div>
            <div class="propiedades">
            </div>
        </section>
    </main>
    <script src="./../../src/JS/admin.js"></script>
    <script src="./../../src/JS/menu_main.js"></script>
</body>
</html>