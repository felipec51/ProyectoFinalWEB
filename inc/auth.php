<?php
// inc/auth.php
if (session_status() === PHP_SESSION_NONE) session_start();

// Guarda datos mínimos del usuario en sesión
function loginUser(array $userRow) {
    // Normaliza campos para trabajar en el resto del proyecto
    $_SESSION['usuario'] = [
        'id' => $userRow['id_usuario'] ?? $userRow['id'] ?? null,
        'username' => $userRow['username'] ?? ($userRow['user'] ?? null),
        'rol' => $userRow['id_rol'] ?? $userRow['rol_id_rol'] ?? $userRow['rol'] ?? null,
        'nombre' => $userRow['nombre'] ?? null,
        'email' => $userRow['email'] ?? null
    ];
}

// Comprueba si hay sesión activa
function isLogged() {
    return !empty($_SESSION['usuario']['id']);
}

function requireLogin() {
    if (!isLogged()) {
        header('Location: iniciarsesion.php');
        exit;
    }
}

// Comprueba si es admin (asume rol id 1 = ADMIN)
function isAdmin() {
    return isset($_SESSION['usuario']['rol']) && intval($_SESSION['usuario']['rol']) === 1;
}

function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        http_response_code(403);
        echo "Acceso denegado - requiere rol ADMIN";
        exit;
    }
}

function logout() {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $_SESSION = [];
    setcookie(session_name(), '', time()-3600, '/');
    session_destroy();
}
