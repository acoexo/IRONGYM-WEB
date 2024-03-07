<?php if (session_status() == PHP_SESSION_NONE) {session_start();}?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="shortcut icon" href="./../../src/SVG/mancuerna_roja.svg" >
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IRON LOGIN</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./../../src/css/normalize.css">
    <link rel="stylesheet" href="./../../src/css/logincss.css">
</head>
<body>
    <main>
        <div class="mainform">
           <img src="./../../src/img/Icon.png" alt="IRONICON">
            <form id="login" class="login" method="POST">
                <input type="text" class="username" name="username" id="username" placeholder="Nombre" class="login--name">
                <div class="password--div">
                    <input class="password" type="password" name="password" id="password" placeholder="Contraseña"> 
                    <div class="toggle-password-button" onclick="togglePasswordVisibility()">
                    <img src="./../../src/img/ojo.png" alt="Mostrar/Ocultar contraseña">
                </div>
                </div>
                <div class="nav--buttons">
                    <input type="submit" class="submit--button">
                    <a href="/signup" class="signup--button">SIGNUP</a>
                </div>
            </form>
        </div>
        <div class="errores">
            <?php if (!empty($errores)) {echo '<ul style="color: #fff;">';foreach ($errores as $error) {echo '<li>' . $error . '</li>';}echo '</ul>';}?>
        </div>
    </main>
    <script src="./../../src/JS/js.js"></script>
</body>
</html>