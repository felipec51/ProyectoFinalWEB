<?php
require_once 'conexion.php';

echo "<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'><title>Sincronizando Copias</title>";
echo "<style>body { font-family: sans-serif; background-color: #121212; color: #e0e0e0; padding: 20px; } h1 { color: #4CAF50; } .movie { border-left: 3px solid #333; padding-left: 15px; margin-bottom: 15px; } .status { font-weight: bold; } .ok { color: #4CAF50; } .mismatch { color: #FFC107; } </style>";
echo "</head><body>";
echo "<h1>Iniciando Sincronización de Copias Disponibles</h1>";

try {
    $conexion = Conexion::Conectar();
    
    // Obtener todas las películas
    $sql_peliculas = "SELECT id_pelicula, titulo, ncopias FROM pelicula";
    $stmt_peliculas = $conexion->prepare($sql_peliculas);
    $stmt_peliculas->execute();
    $peliculas = $stmt_peliculas->fetchAll(PDO::FETCH_ASSOC);

    $updates_needed = 0;

    foreach ($peliculas as $pelicula) {
        $id_pelicula = $pelicula['id_pelicula'];
        $titulo = htmlspecialchars($pelicula['titulo']);
        $ncopias_actual_db = (int)$pelicula['ncopias'];

        // 1. Contar el total de cintas físicas para esta película
        $sql_total_cintas = "SELECT COUNT(*) FROM cinta WHERE pelicula_id_pelicula = :id_pelicula";
        $stmt_total_cintas = $conexion->prepare($sql_total_cintas);
        $stmt_total_cintas->bindParam(':id_pelicula', $id_pelicula, PDO::PARAM_INT);
        $stmt_total_cintas->execute();
        $total_cintas = (int)$stmt_total_cintas->fetchColumn();

        // 2. Contar cuántas de esas cintas están actualmente en préstamo ('en curso')
        $sql_prestamos_activos = "SELECT COUNT(pr.id_prestamo) 
                                  FROM prestamo pr
                                  JOIN cinta c ON pr.cinta_id_cinta = c.id_cinta
                                  WHERE c.pelicula_id_pelicula = :id_pelicula AND pr.estado_alquiler = 'en curso'";
        $stmt_prestamos_activos = $conexion->prepare($sql_prestamos_activos);
        $stmt_prestamos_activos->bindParam(':id_pelicula', $id_pelicula, PDO::PARAM_INT);
        $stmt_prestamos_activos->execute();
        $prestamos_activos = (int)$stmt_prestamos_activos->fetchColumn();

        // 3. Calcular el número correcto de copias disponibles
        $ncopias_calculado = $total_cintas - $prestamos_activos;

        echo "<div class='movie'>";
        echo "<strong>Película:</strong> {$titulo} (ID: {$id_pelicula})<br>";
        echo "Copias en BD: {$ncopias_actual_db} | Copias Calculadas: {$ncopias_calculado} (Total: {$total_cintas} - Prestadas: {$prestamos_activos})<br>";

        // 4. Comparar y actualizar si es necesario
        if ($ncopias_actual_db !== $ncopias_calculado) {
            $updates_needed++;
            echo "<span class='status mismatch'>INCONSISTENCIA DETECTADA. Actualizando...</span><br>";
            $sql_update = "UPDATE pelicula SET ncopias = :ncopias_calculado WHERE id_pelicula = :id_pelicula";
            $stmt_update = $conexion->prepare($sql_update);
            $stmt_update->bindParam(':ncopias_calculado', $ncopias_calculado, PDO::PARAM_INT);
            $stmt_update->bindParam(':id_pelicula', $id_pelicula, PDO::PARAM_INT);
            $stmt_update->execute();
            echo "<span class='status ok'>ACTUALIZADO a {$ncopias_calculado}.</span>";
        } else {
            echo "<span class='status ok'>OK. Los datos son consistentes.</span>";
        }
        echo "</div>";
    }

    if ($updates_needed > 0) {
        echo "<h2>Sincronización completada. Se corrigieron {$updates_needed} registros.</h2>";
    } else {
        echo "<h2>Sincronización completada. Todos los registros ya eran consistentes.</h2>";
    }

} catch (Exception $e) {
    echo "<h1>Error durante la sincronización</h1>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
}

echo "</body></html>";
?>
