<?php 
session_start(); 

// 1. Manejo del mensaje de error del login
$error_login = $_SESSION["error"] ?? "";
unset($_SESSION["error"]);

// 2. Manejo de mensajes de éxito/error genéricos (como el de registro exitoso)
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
                        <div class="correo-electrnico">Correo electrónico o username</div>
                    </div>

                    <div class="loginscreen">
                        <div class="input estilo-input-base">
                            <!-- Es importante que el name sea 'username' como lo espera login.php -->
                            <input class="estilo-input-basico tuemailcom" 
                                   type="text" name="username"
                                   placeholder="Ingresa tu dirección o tu username" required>
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
<!-- Estilos y Script de Notificación para mensajes de ÉXITO (ej. después de registro) -->
<style>
    /* Estilos de la Notificación (MODIFICADOS PARA SER GRANDES Y ABAJO) */
    .notification-box {
        position: fixed;
        bottom: 10px; /* Más cerca del borde inferior */
        right: 10px; /* Dejar un pequeño margen a la derecha */
        left: 10px;  /* Ocupar casi todo el ancho */
        max-width: 98%; /* Para asegurar que no exceda el ancho del viewport */
        padding: 20px 30px; /* Relleno grande */
        border-radius: 12px; /* Esquinas más redondeadas */
        color: white;
        font-weight: bold;
        font-size: 1.2em; /* Letra más grande */
        text-align: center; /* Centrar el texto */
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.5s, transform 0.5s;
        transform: translateY(100%); /* Sale desde abajo */
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4); /* Sombra más pronunciada */
    }
    .notification-box.success {
        background-color: #4CAF50; /* Verde */
    }
    .notification-box.error-flash { 
        background-color: #f44336; /* Rojo */
    }
    .notification-box.show {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<div id="notification-area" class="notification-box"></div>

<script>
<?php
// Lógica PHP para manejar la sesión de notificación (usada principalmente para éxito de registro)
if (isset($notification)) {
    // Usamos el mensaje recuperado en el punto 2 del PHP
    $type = $notification['type'];
    $message = $notification['message'];
    
    // Convertimos 'error' a 'error-flash' para usar la clase CSS correcta
    $css_type = ($type === 'error') ? 'error-flash' : $type;
?>
    
    // Lógica JavaScript para mostrar la notificación
    const notifBox = document.getElementById('notification-area');
    notifBox.textContent = "<?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>";
    notifBox.classList.add("<?php echo $css_type; ?>");
    
    // Usar un pequeño timeout para aplicar la transición
    setTimeout(() => {
        notifBox.classList.add('show');
    }, 100);

    // Ocultar la notificación después de 5 segundos
    setTimeout(() => {
        notifBox.classList.remove('show');
        // Eliminar completamente el elemento del DOM después de la transición
        setTimeout(() => {
            notifBox.remove();
        }, 500);
    }, 5000);

<?php
}
?>
</script>