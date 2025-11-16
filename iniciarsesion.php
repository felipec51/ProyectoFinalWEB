<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <link rel="stylesheet" href="./styles/config.css">
    <link rel="stylesheet" href="./styles/iniciarsesion.css" />
</head>

<body>

    <div class="desktop">
        <img src="./imgs/fondoini.jpg" class="cofondo" alt="fondo">

        <div class="iniciar-sesion-pc">

            <!-- ABRIMOS EL FORMULARIO -->
            <form action="login.php" method="POST" style="width:100%; height:100%;">

                <i class="iniciar-sesion"> iniciar sesion</i>
                <div class="obtener-ayuda">Obtener ayuda</div>

                <!-- BOTÓN INICIAR SESION -->
                <div class="btn-continuar">
                    <div class="btn-continuar-child"></div>
                    <button type="submit" class="iniciar-sesion2" 
                        style="background:none;border:none;color:white;cursor:pointer;">
                        Iniciar Sesión
                    </button>
                </div>

                <div class="olvidaste-contrasea">¿Olvidaste contraseña?</div>

                <div class="check-remenbered">
                    <input type="checkbox" name="remember" class="checkbox" style="margin-right:8px;">
                    <div class="recordarme">Recordarme</div>
                </div>

                <div class="primera-vez-en">¿Primera vez en rewindCodeFilm?</div>

                <a href="index.php" style="text-decoration: none; color: inherit;">
                    <div class="registrarse"><p>Registrarse</p></div>
                </a>

                <div class="login-with-wrapper">
                    <div class="login-with">
                        <div class="or-login-with">Or Login with</div>
                        <div class="login-with-child linea-divisoria-social"></div>
                        <div class="login-with-item linea-divisoria-social"></div>
                    </div>
                </div>

                <div class="facebook-ic-parent">
                    <img src="./imgs/icons/facebook.svg" class="icono-social" alt="">
                    <img src="./imgs/icons/google.svg" class="icono-social" alt="">
                    <img src="./imgs/icons/TwitterX.svg" class="frame-child" alt="">
                </div>

                <!-- CAMPO CORREO -->
                <div class="container campo-input-contenedor">
                    <div class="primitivelabel">
                        <div class="correo-electrnico">Correo electrónico</div>
                    </div>

                    <div class="loginscreen">
                        <div class="input estilo-input-base">
                            <input class="estilo-input-basico tuemailcom" 
                                   type="text" name="username"
                                   placeholder="Ingresa tu dirección" required>
                        </div>
                        <img src="./imgs/icons/Icon-1.svg" class="icon" alt="icon correo">
                    </div>
                </div>

                <!-- CAMPO CONTRASEÑA -->
                <div class="container2 campo-input-contenedor">
                    <div class="primitivelabel">
                        <div class="correo-electrnico">Contraseña</div>
                    </div>
                    <div class="loginscreen">
                        <div class="input estilo-input-base">
                            <input class="estilo-input-basico tuemailcom" 
                                   type="password" name="password"
                                   placeholder="••••••••" required>
                        </div>
                        <img src="./imgs/icons/Icon.svg" class="icon" alt="icon pws">
                    </div>
                </div>

            </form>
            <!-- CERRAMOS EL FORM -->
        </div>

        <div class="rewindcodefilm">
            <div class="rewindcodefilm2">RewindCodeFilm</div>
        </div>
    </div>

</body>

</html>
