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
            <div class="form">
                <form action="iniciarsesion.php" method="post">
                    <div class="home-registrar-iniciar-container">
                        <div class="container2 input-campo-base">
                            <div class="primitivelabel">
                                <div class="direccin">Dirección</div>
                            </div>
                            <input class="estilo-input-basico  estilo-input" type="text" placeholder="Ingresa tu dirección">
                        </div>
                        <div class="container3 input-campo-base">
                            <div class="primitivelabel">
                                <div class="direccin">Código</div>
                            </div>
                            <input class="estilo-input-basico  estilo-input" type="text" placeholder="Ingresa tu código de socio">
                        </div>

                        <div class="container4 input-campo-base">
                            <div class="primitivelabel">
                                <div class="direccin">Nombre</div>
                            </div>
                            <input class="estilo-input-basico  estilo-input" type="text" placeholder="Tu nombre">
                        </div>

                        <div class="container5 input-campo-base">
                            <div class="primitivelabel">
                                <div class="direccin">Apellido</div>
                            </div>
                            <input class="estilo-input-basico  estilo-input" type="text" placeholder="Tu apellido">
                        </div>

                        <div class="container6 input-campo-base">
                            <div class="primitivelabel">
                                <div class="direccin">Email</div>
                            </div>
                            <input class="estilo-input-basico estilo-input" type="email" placeholder="ejemplo@email.com">
                        </div>
                        <div class="container7 input-campo-base">
                            <div class="primitivelabel">
                                <div class="direccin">Contraseña</div>
                            </div>
                            <input class="estilo-input-basico  estilo-input " type="password" placeholder="Ingresa tu contraseña">
                        </div>
                    </div>
                    <div class="button">
                        <input class="estilo-input-basico  registrarse-registro-grande " type="submit" value="Registrarse">
                    </div>
                </form>
                <div class="home-registrar-iniciar-button">
                    <div class="obtener-ayuda">Obtener ayuda</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>