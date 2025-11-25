<?php
session_start();

$data = $_SESSION['form_data'] ?? [];

unset($_SESSION['form_data']);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <link rel="stylesheet" href="./styles/config.css">
    <link rel="stylesheet" href="./styles/index.css" />

</head>

<body>

    <div class="home-registrar-iniciar">
        <img src="imgs/fondo.png" class="cofondocompleto" alt="fondo">

        <div class="home">
            <div class="rewindcodefilm">
                <div class="home-registrar-iniciar-rewindcodefilm">RewindCodeFilm</div>
            </div>

            <div class="iniciar-sesion-btn">
                <div class="boton-fondo iniciar-sesion-fondo">
                </div>
                <a href="iniciarsesion.php" style="text-decoration: none; color: inherit;">
                    <div class="iniciar-sesion" onclick="iniciarSesion()">Iniciar Sesion</div>
                </a>
            </div>

            <div class="text-de-mnu-home">
                <div class="text">
                    <div class="el-nico-lugar texto-linea-grande">El Único Lugar <br>Donde el Cine Se Toca.</div>
                    <div class="quieres-ver-pelculas">¿Quieres ver películas en formato físico?</div>
                </div>
                <div class="accede-al-catlogo texto-linea-grande">Accede al catálogo completo de RewindCodeFilm y a sus exclusivas copias físicas.</div>
            </div>

            <div class="comenzatbtn">
                <div class="boton-fondo comenzatbtn-fondo">
                </div>
                <div class="comenzar">Comenzar </div>
            </div>

            <div class="box-email">
                <div class="caja-email">
                </div>
                <div class="email">Email</div>
            </div>
        </div>

        <div class="las-mas-pedidas">
            <div class="home-registrar-iniciar-las-mas-pedidas">Las mas pedidas.</div>
            <img src="imgs/tronares.png" class="tronaresimg imgIni" alt="">
            <img src="imgs/john4wick.jpg" class="john4wickimg imgIni" alt="">
            <img src="imgs/culpaNuestra.png" class="culpaNuestraimg imgIni" alt="">
            <img src="imgs/blackphone.jpg" class="blackphoneimg imgIni" alt="">
            <img src="imgs/missionimpossible.png" class="missionImposible imgIni" alt="">
            <div class="div numero-pelicula">1</div>
            <div class="home-registrar-iniciar-div numero-pelicula">2</div>
            <div class="div2 numero-pelicula">3<br></div>
            <div class="div3 numero-pelicula">4</div>
            <div class="div4 numero-pelicula">5<br></div>
        </div>

        <div class="faq">
            <div class="despegable-1 desplegable-base">
                <div class="desplegable-titulo-1">
                    <div class="que-es-rewindcodefilm">¿Que es RewindCodeFilm ?</div>
                </div>
                <div class="despleglable-text-1">
                    <div class="desplegable-contenido">
                        <div class="rewindcodefilm-es-un">RewindCodeFilm es un videoclub premium moderno. Somos el único lugar donde puedes alquilar y disfrutar de una amplia colección de películas en formato físico de alta calidad (4K, Blu-ray, DVD, e incluso VHS de culto). ¡El cine se toca, no se transmite!</div>
                    </div>
                </div>
            </div>
            <div class="despegable-2 desplegable-base">
                <div class="desplegable-titulo-1">
                    <div class="que-es-rewindcodefilm">¿Qué sucede si la película que quiero está prestada?</div>
                </div>
                <div class="despleglable-text-1">
                    <div class="desplegable-contenido">
                        <div class="rewindcodefilm-es-un">¡Te apuntas a la Lista de Espera! Si la película no tiene copias disponibles, la añades a tu lista. Nuestro sistema te notificará automáticamente (por email/llamada) tan pronto como la primera copia física sea devuelta a la tienda.</div>
                    </div>
                </div>
            </div>
            <div class="despegable-3 desplegable-base">
                <div class="desplegable-titulo-1">
                    <div class="que-es-rewindcodefilm">¿Manejan streaming o descargas digitales?</div>
                </div>
                <div class="despleglable-text-1">
                    <div class="desplegable-contenido">
                        <div class="rewindcodefilm-es-un">No. Nuestra misión es preservar y promover el formato físico. Todas nuestras películas son copias únicas (cintas o discos) que se deben recoger y devolver en nuestra tienda física.</div>
                    </div>
                </div>
            </div>
            <div class="despegable-4 desplegable-base">
                <div class="desplegable-titulo-1">
                    <div class="que-es-rewindcodefilm">¿Cómo funciona RewindCodeFilm?</div>
                </div>
                <div class="despleglable-text-1">
                    <div class="desplegable-contenido">
                        <div class="rewindcodefilm-es-un">Funciona con una membresía de socio. Te registras en línea, obtienes tu Código de Socio, y lo usas para gestionar tus préstamos, consultar la disponibilidad de copias y unirte a listas de espera en nuestra tienda física.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-registrar">
            <div class="container">
                <div class="heading-2">
                    <div class="crear-una-cuenta">Crear una cuenta</div>
                </div>
                <div class="paragraph">
                    <div class="nete-a-rewindcodefilm">Únete a RewindCodeFilm y disfruta del mejor catálogo de películas</div>
                </div>
            </div>

            <div class="form" style="height: 541px;">
                <form action="registro.php" method="post">

                    <div class="home-registrar-iniciar-container" style="width: calc(100% - 33.3px); top: 0px; right: 33.3px; left: 0px; height: 433px;">

                        <div class="container2 input-campo-base">
                            <div class="primitivelabel">
                                <div class="direccin">Dirección</div>
                            </div>
                            <input class="estilo-input-basico  estilo-input"
                                type="text"
                                placeholder="Ingresa tu dirección"
                                name="direccion"
                                value="<?= htmlspecialchars($data['direccion'] ?? '') ?>">
                        </div>

                        <div class="container3 input-campo-base">
                            <div class="primitivelabel">
                                <div class="direccin">username</div>
                            </div>
                            <input class="estilo-input-basico  estilo-input"
                                type="text"
                                placeholder="Ingresa tu username"
                                name="username"
                                value="<?= htmlspecialchars($data['username'] ?? '') ?>">
                        </div>

                        <div class="container4 input-campo-base">
                            <div class="primitivelabel">
                                <div class="direccin">Nombre</div>
                            </div>
                            <input class="estilo-input-basico  estilo-input"
                                type="text"
                                placeholder="Tu nombre"
                                name="nombre"
                                value="<?= htmlspecialchars($data['nombre'] ?? '') ?>">
                        </div>

                        <div class="container5 input-campo-base">
                            <div class="primitivelabel">
                                <div class="direccin">Apellido</div>
                            </div>
                            <input class="estilo-input-basico  estilo-input"
                                type="text"
                                placeholder="Tu apellido"
                                name="apellido"
                                value="<?= htmlspecialchars($data['apellido'] ?? '') ?>">
                        </div>

                        <div class="container6 input-campo-base">
                            <div class="primitivelabel">
                                <div class="direccin">Email</div>
                            </div>
                            <input class="estilo-input-basico estilo-input"
                                type="email"
                                placeholder="ejemplo@email.com"
                                name="email"
                                value="<?= htmlspecialchars($data['email'] ?? '') ?>">
                        </div>

                        <div class="container7 input-campo-base">
                            <div class="primitivelabel">
                                <div class="direccin">Contraseña</div>
                            </div>
                            <input class="estilo-input-basico  estilo-input "
                                type="password"
                                placeholder="Ingresa tu contraseña"
                                name="password">
                        </div>

                        <div class="container8 input-campo-base">
                            <div class="primitivelabel">
                                <div class="direccin">Pregunta de Seguridad</div>
                            </div>
                            <?php $selected_question = $data['pregunta_seguridad'] ?? ''; ?>
                            <select class="estilo-input-basico  estilo-input"
                                name="pregunta_seguridad"
                                required>
                                <option value="" disabled <?= empty($selected_question) ? 'selected' : '' ?>>Selecciona una pregunta...</option>
                                <option value="mascota" <?= $selected_question == 'mascota' ? 'selected' : '' ?>>Nombre de tu primera mascota?</option>
                                <option value="madre" <?= $selected_question == 'madre' ? 'selected' : '' ?>>apodo de la infancia?</option>
                                <option value="ciudad" <?= $selected_question == 'ciudad' ? 'selected' : '' ?>>Ciudad donde naciste?</option>
                                <option value="escuela" <?= $selected_question == 'escuela' ? 'selected' : '' ?>>Nombre de tu primera escuela?</option>
                            </select>
                        </div>

                        <div class="container9 input-campo-base">
                            <div class="primitivelabel">
                                <div class="direccin">Respuesta</div>
                            </div>
                            <input class="estilo-input-basico  estilo-input"
                                type="text"
                                name="respuesta_seguridad"
                                placeholder="Tu respuesta"
                                required
                                value="<?= htmlspecialchars($data['respuesta_seguridad'] ?? '') ?>">
                        </div>
                        <div class="container10 input-campo-base">
                            <div class="primitivelabel">
                                <div class="direccin">Telefono</div>
                            </div>
                            <input class="estilo-input-basico  estilo-input"
                                type="text"
                                name="telefono"
                                placeholder="Telefono de contacto"
                                required
                                value="<?= htmlspecialchars($data['telefono'] ?? '') ?>">
                        </div>

                    </div>

                    <div class="button">
                        <input class="estilo-input-basico  registrarse-registro-grande " type="submit" value="Registrarse">
                    </div>
                </form>

                <div class="home-registrar-iniciar-button" style="position: absolute; top: 520px; left: 454px;">
                    <div class="obtener-ayuda">Obtener ayuda</div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .notification-box {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.5s, transform 0.5s;
            transform: translateY(100%);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            font-family: Arial, sans-serif;

        }

        .notification-box.success {
            background-color: #4CAF50;
        }

        .notification-box.error {
            background-color: #f44336;
        }

        .notification-box.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    <div id="notification-area" class="notification-box"></div>

    <script>
        <?php

        if (isset($_SESSION['notification'])) {
            $type = $_SESSION['notification']['type'];
            $message = $_SESSION['notification']['message'];
            unset($_SESSION['notification']);
        ?>
            const notifBox = document.getElementById('notification-area');

            try {
                notifBox.textContent = "<?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>";
            } catch (e) {
                notifBox.textContent = "Mensaje de notificación.";
            }

            notifBox.classList.add("<?php echo $type; ?>");
            setTimeout(() => {
                notifBox.classList.add('show');
            }, 100);
            setTimeout(() => {
                notifBox.classList.remove('show');
                setTimeout(() => {
                    notifBox.remove();
                }, 500);
            }, 5000);

        <?php
        }
        ?>
    </script>
</body>

</html>