<?php 
$_SESSION['userid']=$usrData['id']
?>
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
        <form class="signup_boxes" method="POST" action="">
            <label for="">Los campos en blanco no se actualizarán</label>
            <input class="box" type="number" name="height" placeholder="Altura (cm)" id="height" value="<?php echo isset($userStats['height']) ? $userStats['height'] : ''; ?>">
            <input class="box" type="number" name="weight" placeholder="Peso (kg)" id="weight" value="<?php echo isset($userStats['weight']) ? $userStats['weight'] : ''; ?>">
            <select class="select--ph" name="actividadFisica" id="actividadFisica">
                <option value="0">ACTIVITY FACTOR</option>
                <option value="1">LITTLE OR NONE</option>
                <option value="2">LIGHT (1-3 DAYS/WEEK)</option>
                <option value="3">MODERATE (3-5 DAYS/WEEK)</option>
                <option value="4">HEAVY (6-7 DAYS/WEEK)</option>
                <option value="5">VERY HEAVY (2 SESSIONS/DAY)</option>
            </select>
            <input type="submit" name="signup" class="submit--button" value="UPDATE">
            <a href="/user/mainpage" class="exit--button">Salir</a>
            <a href="/user/delete" class="exit--button">Borrar datos</a>
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
</body>
</html>