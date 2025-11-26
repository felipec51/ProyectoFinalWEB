<?php
// seleccion_favoritos_directores.php
// ... [Lógica PHP de SELECT y UPDATE de la BBDD] ...

// La lógica de la BBDD es la misma que la última versión, se asume que se actualiza si se envía el formulario.
// ...

// Consultar todos los directores y los favoritos del usuario (igual que antes)
$consulta_directores = "SELECT id_director, nombre FROM director ORDER BY nombre ASC";
$sentencia_directores = $conexion->prepare($consulta_directores);
$sentencia_directores->execute();
$todos_directores = $sentencia_directores->fetchAll(PDO::FETCH_ASSOC);

$consulta_favoritos = "SELECT director_id_director FROM gusta_director WHERE Usuario_id_usuario = :id_usuario";
$sentencia_favoritos = $conexion->prepare($consulta_favoritos);
$sentencia_favoritos->execute([':id_usuario' => $id_usuario_actual]);
$favoritos_usuario_raw_dir = $sentencia_favoritos->fetchAll(PDO::FETCH_COLUMN, 0);
$favoritos_usuario_dir = array_map('strval', $favoritos_usuario_raw_dir);

if (isset($mensaje_directores)) {
    echo $mensaje_directores;
}
?>

<div class="section-card">
    <h3>Directores Favoritos 🎬</h3>
    <form method="POST">
        <p style="color:var(--text-secondary); font-size: 0.85em; margin-bottom: 15px;">Selecciona hasta 5 directores:</p>
        <div class="favorites-list" id="directores-list">
            <?php foreach ($todos_directores as $director): ?>
                <?php
                    $is_selected = in_array($director['id_director'], $favoritos_usuario_dir);
                    $class = $is_selected ? 'favorite-item selected' : 'favorite-item';
                ?>
                <div 
                    class="<?php echo $class; ?>" 
                    data-id="<?php echo htmlspecialchars($director['id_director']); ?>"
                    onclick="toggleSelection(this, 'directores')"
                >
                    <?php echo htmlspecialchars($director['nombre']); ?>
                </div>
                <input 
                    type="hidden" 
                    name="directores[]" 
                    value="<?php echo htmlspecialchars($director['id_director']); ?>" 
                    class="director-input"
                    data-id="<?php echo htmlspecialchars($director['id_director']); ?>"
                    <?php echo $is_selected ? '' : 'disabled'; ?>
                >
            <?php endforeach; ?>
        </div>
        <button type="submit" name="btn_actualizar_directores" class="btn-save">
            Guardar Directores
        </button>
    </form>
</div>