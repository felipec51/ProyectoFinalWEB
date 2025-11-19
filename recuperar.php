<?php 
session_start(); 

// 1. Manejo de mensajes de notificación (éxito o error)
$notification = $_SESSION['notification'] ?? null;
unset($_SESSION['notification']);

// 2. Recuperar datos del formulario si hubo un error (para rellenar campos automáticamente)
$data = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <link rel="stylesheet" href="./styles/recuperar.css" /> 

</head>

<body>

    <div class="desktop">
        <img src="./imgs/fondoini.jpg" class="cofondo" alt="fondo">

        <div class="iniciar-sesion-pc">

            <form action="procesar_recuperacion.php" method="POST" class="formulario-recuperar">

                <i class="iniciar-sesion"> Recuperar Contraseña</i>
                
                <div class="campo-input-contenedor campo-usuario">
                    <div class="primitivelabel">
                        <div class="correo-electrnico">Usuario o Correo Electrónico</div>
                    </div>
                    <div class="loginscreen">
                        <div class="input estilo-input-base">
                            <input class="estilo-input-basico tuemailcom" 
                                   type="text" 
                                   name="user_identifier"
                                   placeholder="Ingresa tu usuario o correo" 
                                   required
                                   value="<?= htmlspecialchars($data['user_identifier'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <div class="campo-input-contenedor campo-pregunta">
                    <div class="primitivelabel">
                        <div class="correo-electrnico">Pregunta de Seguridad</div>
                    </div>
                    <?php $selected_question = $data['pregunta_seguridad'] ?? ''; ?>
                    <select class="estilo-input-basico estilo-input-base select-recuperar"
                        name="pregunta_seguridad"
                        required>
                        <option value="" disabled <?= empty($selected_question) ? 'selected' : '' ?>>Selecciona una pregunta...</option>
                        <option value="mascota" <?= $selected_question == 'mascota' ? 'selected' : '' ?>>Nombre de tu primera mascota?</option>
                        <option value="madre" <?= $selected_question == 'madre' ? 'selected' : '' ?>>Apodo de la infancia?</option>
                        <option value="ciudad" <?= $selected_question == 'ciudad' ? 'selected' : '' ?>>Ciudad donde naciste?</option>
                        <option value="escuela" <?= $selected_question == 'escuela' ? 'selected' : '' ?>>Nombre de tu primera escuela?</option>
                    </select>
                </div>

                <div class="campo-input-contenedor campo-respuesta">
                    <div class="primitivelabel">
                        <div class="correo-electrnico">Respuesta de Seguridad</div>
                    </div>
                    <div class="loginscreen">
                        <div class="input estilo-input-base">
                            <input class="estilo-input-basico tuemailcom" 
                                   type="text" 
                                   name="respuesta_seguridad"
                                   placeholder="Tu respuesta secreta" 
                                   required
                                   value="<?= htmlspecialchars($data['respuesta_seguridad'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <div class="campo-input-contenedor campo-nueva-password">
                    <div class="primitivelabel">
                        <div class="correo-electrnico">Nueva Contraseña</div>
                    </div>
                    <div class="loginscreen">
                        <div class="input estilo-input-base">
                            <input class="estilo-input-basico tuemailcom" 
                                   type="password" 
                                   name="nueva_password"
                                   placeholder="Ingresa tu nueva contraseña" 
                                   required>
                        </div>
                    </div>
                </div>
                
                <div class="btn-continuar btn-recuperar-final">
                    <div class="btn-continuar-child"></div>
                    <button type="submit" class="iniciar-sesion2" 
                        style="background:none;border:none;color:white;cursor:pointer;">
                        Cambiar Contraseña
                    </button>
                </div>
                
                <a href="iniciarsesion.php" class="enlace-volver" style="text-decoration: none; color: inherit;">
                    <div class="olvidaste-contrasea">← Volver al inicio de sesión</div>
                </a>

            </form>
            </div>

        <div class="rewindcodefilm">
            <a href="index.php" style="text-decoration: none; color: inherit;">
                <div class="rewindcodefilm2">RewindCodeFilm</div>
            </a>
        </div>
    </div>
    
</body>

</html>
<style>
    /* Estilos de la Notificación (Reutilizado) */
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
    /* Estilo del SELECT para que se vea oscuro (copiado del index/config) */
    .select-recuperar {
        color: var(--color-texto-principal) !important; 
        background-color: var(--color-transparencia-3) !important; 
        -webkit-appearance: none; 
        -moz-appearance: none; 
        appearance: none;
        background-image: none !important;
        position: absolute;
        width: 100%;
        height: 56px;
        top: 27.98px;
        left: 0;
        border-radius: 16px;
        border: 0.8px solid rgba(255, 255, 255, 0.2);
    }
    .select-recuperar option {
        background-color: var(--color-bg-principal) !important; 
        color: var(--color-texto-principal) !important; 
        -webkit-text-fill-color: var(--color-texto-principal) !important; 
    }
</style>

<div id="notification-area" class="notification-box"></div>

<script>
<?php
// Lógica JavaScript para mostrar la notificación
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