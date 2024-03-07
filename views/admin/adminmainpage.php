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
        <!-- <?php echo $usrData['username'] ?> -->
    </title>
</head>
<body>
    <main>
        <section class="nav">
            <button class="home-button" id="home-button"><img src="./../../src/img/botonMancuerna.png" alt=""></button>
            <div class="user">
                <h2 id="user-name">
                    <!-- <?php echo $usrData['username'] ?> -->
                </h2>
                <button id="user-button" class="user-button"><i class="bi bi-person"></i></button>
            </div>
            <button class="abrir-menu" id="abrir"> <i class="bi bi-list"></i> </button>
        </section>
        <section class="menu" id="menu">
            <div class="options-menu" id="options_menu">
                <h2 id="user-name">
                    <!-- <?php echo $usrData['username'] ?> -->
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