<?php
// index.php
// Página simple de alquiler de películas (PHP + CSS)
// GitHub Copilot

session_start();

// Lista de películas (simulada)
$movies = [
    1 => ['title' => 'La Aventura', 'genre' => 'Acción', 'year' => 2020, 'price' => 3.99],
    2 => ['title' => 'Noche de Misterio', 'genre' => 'Suspenso', 'year' => 2019, 'price' => 2.99],
    3 => ['title' => 'Amor en París', 'genre' => 'Romance', 'year' => 2021, 'price' => 4.50],
    4 => ['title' => 'Risas y Locuras', 'genre' => 'Comedia', 'year' => 2018, 'price' => 2.50],
    5 => ['title' => 'Documental Vivo', 'genre' => 'Documental', 'year' => 2022, 'price' => 3.25],
];

// Inicializar carrito
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Manejo de acciones: add, remove, clear, checkout
$action = $_GET['action'] ?? null;
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$message = '';

if ($action === 'add' && $id && isset($movies[$id])) {
    if (!isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] = 0;
    }
    $_SESSION['cart'][$id]++;
    $message = 'Película añadida al carrito.';
    header("Location: index.php?message=" . urlencode($message));
    exit;
}

if ($action === 'remove' && $id && isset($_SESSION['cart'][$id])) {
    unset($_SESSION['cart'][$id]);
    $message = 'Película eliminada del carrito.';
    header("Location: index.php?view=cart&message=" . urlencode($message));
    exit;
}

if ($action === 'clear') {
    $_SESSION['cart'] = [];
    $message = 'Carrito vaciado.';
    header("Location: index.php?view=cart&message=" . urlencode($message));
    exit;
}

if ($action === 'checkout' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Datos simples del formulario
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    if ($name === '' || $email === '' || empty($_SESSION['cart'])) {
        $message = 'Complete nombre, email y asegúrese de tener artículos en el carrito.';
    } else {
        // Simular pago/proceso de alquiler
        $order = [
            'name' => $name,
            'email' => $email,
            'items' => $_SESSION['cart'],
            'total' => 0,
            'date' => date('Y-m-d H:i:s'),
        ];
        foreach ($order['items'] as $mid => $qty) {
            $order['total'] += $movies[$mid]['price'] * $qty;
        }
        // Guardar orden en sesión como historial (simulación)
        if (!isset($_SESSION['orders'])) $_SESSION['orders'] = [];
        $_SESSION['orders'][] = $order;
        $_SESSION['cart'] = [];
        $message = 'Gracias, su alquiler ha sido procesado.';
        header("Location: index.php?view=orders&message=" . urlencode($message));
        exit;
    }
}

// Mensaje opcional desde redirección
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
}

// Helpers
function money($n) {
    return '$' . number_format($n, 2);
}

function posterUrl($title) {
    return 'https://via.placeholder.com/200x300?text=' . urlencode($title);
}

// Vista
$view = $_GET['view'] ?? 'home';
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Alquiler de Películas</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <div class="container topbar">
        <div>
            <div class="brand">CineAlquila</div>
            <div style="font-size:0.9rem">Películas para alquiler en línea</div>
        </div>
        <div class="cart-info">
            <a href="index.php?view=cart" style="color:#e6fffb;text-decoration:none">
                Ver carrito (<?= array_sum($_SESSION['cart'] ?? []) ?>)
            </a>
        </div>
    </div>
</header>

<div class="container">
    <nav>
        <a href="index.php">Inicio</a>
        <a href="index.php?view=catalog">Catálogo</a>
        <a href="index.php?view=cart">Carrito</a>
        <a href="index.php?view=orders">Mis alquileres</a>
    </nav>

    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif ?>

    <?php if ($view === 'home' || $view === 'catalog'): ?>
        <h2><?= $view === 'home' ? 'Películas Destacadas' : 'Catálogo' ?></h2>
        <div class="grid">
            <?php foreach ($movies as $mid => $m): ?>
                <div class="card">
                    <img src="<?= posterUrl($m['title']) ?>" alt="<?= htmlspecialchars($m['title']) ?>">
                    <div class="card-body">
                        <div class="title"><?= htmlspecialchars($m['title']) ?></div>
                        <div class="meta"><?= htmlspecialchars($m['genre']) ?> · <?= $m['year'] ?></div>
                        <div class="price"><?= money($m['price']) ?> / 48h</div>
                        <div style="margin-top:8px">
                            <a class="btn" href="index.php?action=add&id=<?= $mid ?>">Añadir al carrito</a>
                            <a class="btn secondary" href="index.php?view=details&id=<?= $mid ?>">Ver</a>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    <?php elseif ($view === 'details' && isset($_GET['id']) && isset($movies[(int)$_GET['id']])): 
        $mid = (int)$_GET['id']; $m = $movies[$mid];
    ?>
        <h2><?= htmlspecialchars($m['title']) ?></h2>
        <div style="display:flex;gap:16px;flex-wrap:wrap">
            <img src="<?= posterUrl($m['title']) ?>" alt="<?= htmlspecialchars($m['title']) ?>" style="width:200px">
            <div>
                <p><strong>Género:</strong> <?= htmlspecialchars($m['genre']) ?></p>
                <p><strong>Año:</strong> <?= $m['year'] ?></p>
                <p><strong>Precio:</strong> <?= money($m['price']) ?> por 48 horas</p>
                <p>Lorem ipsum dolor sit amet, descripción breve de la película para atraer al cliente.</p>
                <p>
                    <a class="btn" href="index.php?action=add&id=<?= $mid ?>">Añadir al carrito</a>
                    <a class="btn secondary" href="index.php?view=catalog">Volver</a>
                </p>
            </div>
        </div>

    <?php elseif ($view === 'cart'): ?>
        <h2>Carrito de alquiler</h2>
        <?php if (empty($_SESSION['cart'])): ?>
            <p>Tu carrito está vacío. <a href="index.php?view=catalog">Ver catálogo</a></p>
        <?php else: ?>
            <table>
                <thead>
                    <tr><th>Película</th><th>Cantidad</th><th>Precio unidad</th><th>Subtotal</th><th></th></tr>
                </thead>
                <tbody>
                    <?php $total = 0; foreach ($_SESSION['cart'] as $mid => $qty): 
                        $m = $movies[$mid];
                        $sub = $m['price'] * $qty;
                        $total += $sub;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($m['title']) ?></td>
                            <td><?= $qty ?></td>
                            <td><?= money($m['price']) ?></td>
                            <td><?= money($sub) ?></td>
                            <td><a href="index.php?action=remove&id=<?= $mid ?>" class="btn secondary">Eliminar</a></td>
                        </tr>
                    <?php endforeach ?>
                    <tr>
                        <th colspan="3" style="text-align:right">Total</th>
                        <th><?= money($total) ?></th>
                        <th></th>
                    </tr>
                </tbody>
            </table>

            <div style="margin-top:12px;display:flex;gap:12px;flex-wrap:wrap">
                <a class="btn" href="index.php?view=checkout">Finalizar alquiler</a>
                <a class="btn secondary" href="index.php?action=clear">Vaciar carrito</a>
            </div>
        <?php endif ?>

    <?php elseif ($view === 'checkout'): ?>
        <h2>Finalizar alquiler</h2>
        <?php if (empty($_SESSION['cart'])): ?>
            <p>No hay artículos en el carrito. <a href="index.php?view=catalog">Ir al catálogo</a></p>
        <?php else: ?>
            <form method="post" action="index.php?action=checkout" style="max-width:480px">
                <label>Nombre completo<br><input type="text" name="name" required style="width:100%;padding:8px;margin-top:4px"></label><br><br>
                <label>Email<br><input type="email" name="email" required style="width:100%;padding:8px;margin-top:4px"></label><br><br>
                <button class="btn" type="submit">Pagar y alquilar</button>
                <a class="btn secondary" href="index.php?view=cart">Volver al carrito</a>
            </form>
        <?php endif ?>

    <?php elseif ($view === 'orders'): ?>
        <h2>Mis alquileres</h2>
        <?php if (empty($_SESSION['orders'])): ?>
            <p>No hay alquileres realizados aún.</p>
        <?php else: ?>
            <?php foreach (array_reverse($_SESSION['orders']) as $ord): ?>
                <div style="border:1px solid #eee;padding:12px;border-radius:6px;margin-bottom:8px">
                    <div><strong>Cliente:</strong> <?= htmlspecialchars($ord['name']) ?> — <?= htmlspecialchars($ord['email']) ?></div>
                    <div><strong>Fecha:</strong> <?= $ord['date'] ?></div>
                    <div><strong>Total:</strong> <?= money($ord['total']) ?></div>
                    <div style="margin-top:8px">
                        <strong>Películas:</strong>
                        <ul>
                            <?php foreach ($ord['items'] as $mid => $q): ?>
                                <li><?= htmlspecialchars($movies[$mid]['title']) ?> × <?= $q ?></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                </div>
            <?php endforeach ?>
        <?php endif ?>

    <?php else: ?>
        <h2>Página no encontrada</h2>
        <p>Vista desconocida. <a href="index.php">Volver al inicio</a></p>
    <?php endif ?>

    <footer>
        &copy; <?= date('Y') ?> CineAlquila — Demo simple de PHP
    </footer>
</div>
</body>
</html>