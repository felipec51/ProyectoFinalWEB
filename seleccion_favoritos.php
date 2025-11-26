<?php


$consulta_actores = "SELECT id_actor, nombre FROM actor ORDER BY nombre ASC";
$sentencia_actores = $conexion->prepare($consulta_actores);
$sentencia_actores->execute();
$todos_actores = $sentencia_actores->fetchAll(PDO::FETCH_ASSOC);

$consulta_favoritos = "SELECT actor_id_actor FROM gusto_actor WHERE Usuario_id_usuario = :id_usuario";
$sentencia_favoritos = $conexion->prepare($consulta_favoritos);
$sentencia_favoritos->execute([':id_usuario' => $id_usuario_actual]);
$favoritos_usuario_raw = $sentencia_favoritos->fetchAll(PDO::FETCH_COLUMN, 0); 
$favoritos_usuario = array_map('strval', $favoritos_usuario_raw); 

if (isset($mensaje_actores)) {
    echo $mensaje_actores;
}
?>

<div class="section-card">
    <h3>Actores Favoritos</h3>
    <form method="POST">
        <p style="color:var(--text-secondary); font-size: 0.85em; margin-bottom: 15px;">Selecciona hasta 5 actores:</p>
        <div class="favorites-list" id="actores-list">
            <?php foreach ($todos_actores as $actor): ?>
                <?php
                    $is_selected = in_array($actor['id_actor'], $favoritos_usuario);
                    $class = $is_selected ? 'favorite-item selected' : 'favorite-item';
                ?>
                <div 
                    class="<?php echo $class; ?>" 
                    data-id="<?php echo htmlspecialchars($actor['id_actor']); ?>"
                    onclick="toggleSelection(this, 'actores')"
                >
                    <?php echo htmlspecialchars($actor['nombre']); ?>
                </div>
                <input 
                    type="hidden" 
                    name="actores[]" 
                    value="<?php echo htmlspecialchars($actor['id_actor']); ?>" 
                    class="actor-input"
                    data-id="<?php echo htmlspecialchars($actor['id_actor']); ?>"
                    <?php echo $is_selected ? '' : 'disabled'; ?>
                >
            <?php endforeach; ?>
        </div>
        <button type="submit" name="btn_actualizar_actores" class="btn-save">
            Guardar Actores
        </button>
    </form>
</div>