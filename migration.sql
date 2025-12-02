ALTER TABLE `prestamo`
ADD COLUMN `fecha_devolucion_real` DATETIME DEFAULT NULL,
ADD COLUMN `estado_prestamo` ENUM('activo', 'devuelto') NOT NULL DEFAULT 'activo';
