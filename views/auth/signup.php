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
            <input class="box" type="text" name="name" placeholder="Name" id="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
            <input class="box" type="date" name="date" placeholder="Birth Date" id="date" value="<?php echo htmlspecialchars($_POST['date'] ?? ''); ?>">
            <input class="box" type="number" name="tfn" placeholder="Phone Number" pattern="[0-9]{3} [0-9]{2} [0-9]{2} [0-9]{2}" id="tfn" value="<?php echo htmlspecialchars($_POST['tfn'] ?? ''); ?>">
            <select class="select--ph" name="gen" id="gen">
                <option value="0">Sexo</option>
                <option value="F" <?php echo (isset($_POST['gen'])&&$_POST['gen'] === 'F') ? 'selected' : ''; ?>>Femenino</option>
                <option value="M" <?php echo (isset($_POST['gen'])&&$_POST['gen'] === 'M') ? 'selected' : ''; ?>>Masculino</option>
            </select>
            <input class="box" type="text" name="username" placeholder="Username" id="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            <input class="box" type="email" name="email" placeholder="Correo Electronico" id="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            <div class="box" id="password--div">
                <input class="box" id="password" type="password" name="password" id="password" placeholder="Contraseña" value="<?php echo htmlspecialchars($_POST['password'] ?? ''); ?>"> 
                <div class="toggle-password-button" onclick="togglePasswordVisibility()">
                    <img src="./../../src/img/ojo2.png" alt="Mostrar/Ocultar contraseña">
                </div>
            </div>
            <input class="box" type="number" name="height" placeholder="Altura (cm)" id="height" value="<?php echo htmlspecialchars($_POST['height'] ?? ''); ?>">
            <input class="box" type="number" name="weight" placeholder="Peso (kg)" id="weight" value="<?php echo htmlspecialchars($_POST['weight'] ?? ''); ?>">
            <select class="select--ph" name="activity_factor" id="activity_factor">
                <option value="0">ACTIVITY FACTOR</option>
                <option value="1" <?php echo (isset($_POST['activity_factor'])&& $_POST['activity_factor'] === '1') ? 'selected' : ''; ?>>LITTLE OR NONE</option>
                <option value="2" <?php echo (isset($_POST['activity_factor'])&& $_POST['activity_factor'] === '2') ? 'selected' : ''; ?>>LIGHT (1-3 DAYS/WEEK)</option>
                <option value="3" <?php echo (isset($_POST['activity_factor'])&& $_POST['activity_factor'] === '3') ? 'selected' : ''; ?>>MODERATE (3-5 DAYS/WEEK)</option>
                <option value="4" <?php echo (isset($_POST['activity_factor'])&& $_POST['activity_factor'] === '4') ? 'selected' : ''; ?>>HEAVY (6-7 DAYS/WEEK)</option>
                <option value="5" <?php echo (isset($_POST['activity_factor'])&& $_POST['activity_factor'] === '5') ? 'selected' : ''; ?>>VERY HEAVY (2 SESSIONS/DAY)</option>
            </select>
            <input type="submit" name="signup" class="submit--button" value="SIGNUP">
            <a href="/" class="exit--button">Salir</a>
        </form>
    </section>
    <section class="errores">
    <?php 
        if(!empty($errors)){
            echo '<ul>';
            foreach($errors as $error){
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
