<?php

include 'check_session.php'; 

if (!isset($_SESSION["rol_id_rol"]) || $_SESSION["rol_id_rol"] != 1) {
    header("Location: peliculasMenu.php"); 
    exit;
}
$usuario_logueado_id = $_SESSION["id_usuario"];



include 'listado_peliculas.php'; 
include 'componentes/adminpeliculas.php';
include 'componentes/sidebar.php';


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <link rel="stylesheet" href="styles/peliculasMenu.css" />
    <link rel="stylesheet" href="styles/config.css" />
    <link rel="stylesheet" href="./styles/paneladmin.css" />
</head>

<body>
    <div class="desktop">
        <div class="admin-movie-panel-activity">
            <div class="app">
                <div class="sidebar">
                </div>
                <div class="sidebarinset">
                    <div class="app2">
                        <header class="app3">
                            <img src="./imgs/icons/icono-10-(1).svg" class="button-icon" alt="">

                            <div class="primitivediv"></div>
                            <div class="container">
                                <div class="primitivebutton flex-row-center">
                                    <div class="primitivespan flex-row-center">
                                        <div class="todos text-base">Todos</div>
                                    </div>
                                    <img src="./imgs/icons/icono-10-(20).svg" class="icon" alt="icono de todos">
                                </div>
                                <div class="container2">
                                    <div class="input flex-row-center">
                                        <div class="buscar-pelculas-directores text-secundario">Buscar películas, directores...</div>
                                    </div>
                                    <img src="./imgs/icons/Icon-7.svg" class="icon2" alt="">
                                </div>
                            </div>

                        </header>

                        <div class="container4 text-secundario">
                            <div class="card card-base">
                                <div class="statscard">
                                    <div class="container5">
                                        <div class="paragraph flex-row-center">
                                            <div class="total-pelculas text-secundario">Total Películas</div>
                                        </div>
                                        <div class="heading-3 text-base">8</div>
                                        <div class="paragraph2 ">
                                            <div class="en-el-catlogo">En el catálogo</div>
                                        </div>
                                        <div class="container6">
                                            <div class="div2">↑ +12.5%</div>
                                        </div>
                                        <img src="./imgs/icons/Container.svg" class="container-icon" alt="icono de configuración">
                                    </div>
                                </div>
                            </div>
                            <div class="card2 card-base">
                                <div class="statscard2">
                                    <div class="container5">
                                        <div class="paragraph flex-row-center">
                                            <div class="total-pelculas text-secundario">Rating Promedio</div>
                                        </div>
                                        <div class="heading-32 text-base">8.3</div>
                                        <div class="paragraph4 text-secundario">
                                            <div class="en-el-catlogo">De todas las películas</div>
                                        </div>
                                        <div class="container8">
                                            <div class="div4">↑ +0.3</div>
                                        </div>
                                    </div>
                                    <img src="./imgs/icons/Container2.svg" class="container-icon" alt="icono de configuración">
                                </div>
                            </div>
                            <div class="card3 card-base">
                                <div class="statscard3">
                                    <div class="container9">
                                        <div class="paragraph5 flex-row-center">
                                            <div class="total-pelculas text-secundario">Géneros</div>
                                        </div>
                                        <div class="heading-33 text-base">7</div>
                                    </div>
                                    <img src="./imgs/icons/Container3.svg" class="container-icon" alt="icono de configuración">
                                </div>
                            </div>
                            <div class="card4 card-base">
                                <div class="statscard4">
                                    <div class="container10">
                                        <div class="paragraph flex-row-center">
                                            <div class="total-pelculas text-secundario">Visualizaciones</div>
                                        </div>
                                        <div class="heading-32 text-base">156K</div>
                                        <div class="paragraph4 text-secundario">
                                            <div class="en-el-catlogo">Este mes</div>
                                        </div>
                                        <div class="container11">
                                            <div class="div6">↑ +8.2%</div>
                                        </div>
                                    </div>
                                    <img src="./imgs/icons/Container4.svg" class="container-icon" alt="icono de configuración">
                                </div>
                            </div>
                        </div>

                        <div class="container12">
                            <div class="container13">
                                <div class="heading-2 text-base">Catálogo de Películas</div>
                                <div class="paragraph8 text-secundario">
                                    <div class="pelculas-encontradas">8 películas encontradas</div>
                                </div>
                            </div>

                        </div>
                        <div class="container14">

                            <?php renderpeliculas($peliculas); ?>

                        </div>
                    </div>
                </div>

            </div>

            <?php rendersidebar() ?>
        </div>
    </div>
</body>

</html>