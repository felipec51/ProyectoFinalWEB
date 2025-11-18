<?php
// Archivo: crud_pelicula.php
include_once './conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

// -------------------------
// SOPORTE GET PARA PANELADMIN
// -------------------------
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    try {
        $consulta = "DELETE FROM pelicula WHERE id_pelicula=?";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute([$id]);
        header("Location: paneladmin.php?msg=eliminado");
        exit;
    } catch (Exception $e) {
        header("Location: paneladmin.php?msg=error");
        exit;
    }
}

if (isset($_GET['fetch'])) {
    $id = intval($_GET['fetch']);

    $consulta = "SELECT * FROM pelicula WHERE id_pelicula=?";
    $resultado = $conexion->prepare($consulta);
    $resultado->execute([$id]);

    echo json_encode($resultado->fetch(PDO::FETCH_ASSOC));
    exit;
}

// -------------------------
// MANEJO POST (AJAX)
// -------------------------
$titulo = $_POST['titulo'] ?? '';
$anio = $_POST['anio'] ?? '';
$duracion_min = $_POST['duracion_min'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$poster_path = $_POST['poster_path'] ?? '';
$precio_alquiler = $_POST['precio_alquiler'] ?? '';
$calificacion = $_POST['calificacion'] ?? '';
$director_id_director = $_POST['director_id_director'] ?? '';

$opc = $_POST['opc'] ?? '';
$id_pelicula = $_POST['id_pelicula'] ?? '';

$data = [];

try {
    switch ($opc) {

        case 1: // INSERTAR
            $consulta = "INSERT INTO pelicula 
                (titulo, anio, duracion_min, descripcion, poster_path, precio_alquiler, calificacion, director_id_director) 
                VALUES(?, ?, ?, ?, ?, ?, ?, ?)";

            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$titulo, $anio, $duracion_min, $descripcion, $poster_path, $precio_alquiler, $calificacion, $director_id_director]);

            $id_insertado = $conexion->lastInsertId();

            $consulta = "SELECT p.*, d.nombre AS director_nombre 
                         FROM pelicula p 
                         JOIN director d ON p.director_id_director = d.id_director 
                         WHERE p.id_pelicula = ?";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$id_insertado]);
            $data = $resultado->fetch(PDO::FETCH_ASSOC);

            break;

        case 2: // ACTUALIZAR
            $consulta = "UPDATE pelicula 
                         SET titulo=?, anio=?, duracion_min=?, descripcion=?, poster_path=?, precio_alquiler=?, calificacion=?, director_id_director=? 
                         WHERE id_pelicula=?";

            $resultado = $conexion->prepare($consulta);
            $resultado->execute([
                $titulo, $anio, $duracion_min, $descripcion, 
                $poster_path, $precio_alquiler, $calificacion, 
                $director_id_director, $id_pelicula
            ]);

            $consulta = "SELECT p.*, d.nombre AS director_nombre 
                         FROM pelicula p 
                         JOIN director d ON p.director_id_director = d.id_director 
                         WHERE p.id_pelicula=?";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$id_pelicula]);
            $data = $resultado->fetch(PDO::FETCH_ASSOC);

            break;

        case 3: // ELIMINAR (AJAX)
            $consulta = "DELETE FROM pelicula WHERE id_pelicula=?";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$id_pelicula]);

            $data = ["status" => "ok", "id_eliminado" => $id_pelicula];
            break;

        case 4: // LISTAR TODO
            $consulta = "SELECT p.*, d.nombre AS director_nombre 
                         FROM pelicula p 
                         JOIN director d ON p.director_id_director = d.id_director
                         ORDER BY p.id_pelicula DESC";

            $resultado = $conexion->prepare($consulta);
            $resultado->execute();
            $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
            break;
    }

} catch (PDOException $e) {
    $data = ["error" => $e->getMessage()];
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);
$conexion = null;
?>
