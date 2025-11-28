CREATE TABLE `notificaciones` (
  `id_notificacion` INT(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` INT(11) NOT NULL,
  `mensaje` VARCHAR(255) NOT NULL,
  `fecha_creacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `leido` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_notificacion`),
  FOREIGN KEY (`usuario_id`) REFERENCES `Usuario`(`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
