<?php
session_start();

$error_login = $_SESSION["error"] ?? "";
unset($_SESSION["error"]);

$notification = $_SESSION['notification'] ?? null;
unset($_SESSION['notification']);
?>
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

                <?php if (!empty($error_login)): ?>
                    <div style="color: #f44336; 
                                text-align: center; 
                                margin-bottom: 15px; 
                                padding: 10px; 
                                background-color: #fdd8d6;
                                border: 1px solid #f44336;
                                border-radius: 6px;
                                font-weight: 500;
                                position: absolute;
                                top: 85%; /* Ajusta la posición según tu CSS */
                                width: 80%;
                                left: 50%;
                                transform: translateX(-50%);
                                font-size: 14px;">
                        <?= htmlspecialchars($error_login) ?>
                    </div>
                <?php endif; ?>
                <div class="btn-continuar">
                    <div class="btn-continuar-child"></div>
                    <button type="submit" class="iniciar-sesion2"
                        style="background:none;border:none;color:white;cursor:pointer;">
                        Iniciar Sesión
                    </button>
                </div>

                <a href="recuperar.php" style="text-decoration: none; color: inherit;">
                    <div class="olvidaste-contrasea">¿Olvidaste contraseña?</div>
                </a>


                <div class="primera-vez-en">¿Primera vez en rewindCodeFilm?</div>

                <a href="index.php" style="text-decoration: none; color: inherit;">
                    <div class="registrarse">
                        <p>Registrarse</p>
                    </div>
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
                        <div class="correo-electrnico">Correo electrónico o username</div>
                    </div>

                    <div class="loginscreen">
                        <div class="input estilo-input-base">
                            <input class="estilo-input-basico tuemailcom"
                                type="text" name="username"
                                placeholder="Ingresa tu dirección o tu username" required>
                        </div>
                        <img src="./imgs/icons/Icon-1.svg" class="icon" alt="icon correo">
                    </div>
                </div>

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
        </div>

        <div class="rewindcodefilm">
            <div class="rewindcodefilm2">RewindCodeFilm</div>
        </div>
    </div>

</body>

</html>

<style>
   
    .notification-box {
        position: fixed;
        bottom: 10px;
        right: 10px;
        left: 10px;
        max-width: 98%;
        padding: 20px 30px;
        border-radius: 12px;
        color: white;
        font-weight: bold;
        font-size: 1.2em;
        text-align: center;
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.5s, transform 0.5s;
        transform: translateY(100%);
      
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);

    }

    .notification-box.success {
        background-color: #4CAF50;

    }

    .notification-box.error-flash {
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

    if (isset($notification)) {

        $type = $notification['type'];
        $message = $notification['message'];


        $css_type = ($type === 'error') ? 'error-flash' : $type;
    ?>

        const notifBox = document.getElementById('notification-area');
        notifBox.textContent = "<?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>";
        notifBox.classList.add("<?php echo $css_type; ?>");
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