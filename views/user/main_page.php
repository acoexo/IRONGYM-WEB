<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="shortcut icon" href="./../../src/SVG/mancuerna_roja.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./../../src/css/normalize.css">
    <link rel="stylesheet" href="./../../src/css/main_page.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME PAGE
        <?php echo $usrData['username'] ?>
    </title>
</head>
<body>
    <main >
        <section class="nav">
            <button class="home-button" id="home-button"><img src="./../../src/img/botonMancuerna.png" alt=""></button>
            <div class="user">
                <h2 id="user-name">
                    <?php  echo $usrData['username'] ?>
                    
                </h2>
                <button id="user-button" class="user-button"><i class="bi bi-person"></i></button>
            </div>

            <button class="abrir-menu" id="abrir"> <i class="bi bi-list"></i> </button>
        </section>
        <section class="menu" id="menu">
            <div class="options-menu" id="options_menu">
                <h2 id="user-name">
                    <?php  echo $usrData['username'] ?>
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
                    <a href="update"class="update" id="update">UPDATE STATS</a>
                </div>
            </div>
        </section>
        <section class="stats">
            <h3>STATS</h3>
            <div class="stats_form">
                <div class="altura">
                    <p>
                        <?php echo $usrStats['height'] . "cm"; ?>
                    </p>
                    <h2>HEIGHT</h2>
                </div>
                <div class="peso">
                    <p>
                        <?php echo $usrStats['weight'] . "kg"; ?>
                    </p>
                    <h2>WEIGHT</h2>
                </div>
                <div class="actividad">
                    <p>
                        <?php
                        switch ($usrStats['activity_factor']) {
                            case '1':
                                echo "Little or none";
                                break;
                            case '2':
                                echo "Light (1-3 days/week)";
                                break;
                            case '3':
                                echo "Moderate (3-5 days/week)";
                                break;
                            case '4':
                                echo "Intense (6-7 days/week)";
                                break;
                            case '5':
                                echo "Very Intense (2 sessions/day)";
                                break;
                            default:
                                echo "Not specified, update your statistics";
                                break;
                        }
                        ?>
                    </p>
                    <h2>ACTIVITY FACTOR</h2>
                </div>
                <div class="tmb">
                    <p>
                        <?php
                        if ($usrData['gen'] === 'H') {
                            echo intval(((10 * $usrStats['weight']) + (6.25 * $usrStats['height']) - (5 * $usrStats['age']) + 5));
                        } else {
                            echo intval((((10 * $usrStats['weight']) + (6.25 * $usrStats['height']) - (5 * $usrStats['age']) - 161)));
                        }
                        ?>
                    </p>
                    <h2>TMB</h2>
                </div>
                <div class="icm">
                    <p>
                        <?php
                        $icm = ($usrStats['weight'] / (($usrStats['height'] / 100) ** 2));
                        if ($icm < 18.5) {
                            echo ' Below normal ';
                        } elseif ($icm >= 18.5 && $icm <= 24.9) {
                            echo ' Normal';
                        } elseif ($icm >= 25.0 && $icm <= 29.9) {
                            echo ' Overweigth';
                        } elseif ($icm >= 30) {
                            echo ' Obesity';
                        }
                        ?>
                    </p>
                    <h2>ICM</h2>
                </div>
            </div>
        </section>
    </main>
    <script src="./../../src/JS/menu_main.js"></script>
</body>

</html>