<?php if (session_status() == PHP_SESSION_NONE) {session_start();}?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="shortcut icon" href="./../../src/SVG/mancuerna_roja.svg" >
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IRON SIGNUP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./../../src/css/normalize.css">
    <link rel="stylesheet" href="./../../src/css/signupcss.css">
</head>
<body>
    <section class="signup_container">
        <img src="./../../src/img/iron_stats.png" alt="IronStatsPNG" width="252" height="94">
        <form class="signup_boxes" method="POST" >
            <input class="box" type="text" name="name" placeholder="Name" id="name">
            <input class="box" type="date" name="date" placeholder="Birth Date" id="date">
            <input class="box" type="number" name="tfn" placeholder="Phone Number" pattern="[0-9]{3} [0-9]{2} [0-9]{2} [0-9]{2}" id="tfn">
            <select class="select--ph" name="gen" id="gen">
                <option value="0">Sexo</option>
                <option value="F">Femenino</option>
                <option value="M">Masculino</option>
            </select>
            <input class="box" type="text" name="username" placeholder="Username" id="username">
            <input class="box" type="email" name="email" placeholder="Correo Electronico" id="email">
            <div class="box" id="password--div">
                <input class="box" id="password" type="password" name="password" id="password" placeholder="Contraseña"> 
                <div class="toggle-password-button" onclick="togglePasswordVisibility()">
                    <img src="./../../src/img/ojo2.png" alt="Mostrar/Ocultar contraseña">
                </div>
            </div>
            <input class="box" type="number" name="height" placeholder="Altura (cm)" id="height">
            <input class="box" type="number" name="weight" placeholder="Peso (kg)" id="weight">
            <select class="select--ph" name="activity_factor" id="activity_factor">
                <option value="0">ACTIVITY FACTOR</option>
                <option value="1">LITTLE OR NONE</option>
                <option value="2">LIGHT (1-3 DAYS/WEEK)</option>
                <option value="3">MODERATE (3-5 DAYS/WEEK)</option>
                <option value="4">HEAVY (6-7 DAYS/WEEK)</option>
                <option value="5">VERY HEAVY (2 SESSIONS/DAY)</option>
            </select>
            <input type="submit" name="signup" class="submit--button" value="SIGNUP">
            <a href="/" class="exit--button">Salir</a>
        </form>
    </section>
    <section class="errores">
    <?php 
        if(!empty($errores)){
            echo '<ul>';
            foreach($errores as $error){
                echo "<li>".($error)."</li>";
            }
            echo '</ul>';
        }
    ?>
    </section>
    <!-- Script al final del documento -->
    <script src="./../../src/JS/js2.js"></script>
</body>
</html>
