-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 28-11-2025 a las 20:49:37
-- Versión del servidor: 12.0.2-MariaDB
-- Versión de PHP: 8.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mydb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actor`
--

CREATE TABLE `actor` (
  `id_actor` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `actor`
--

INSERT INTO `actor` (`id_actor`, `nombre`) VALUES
(1, 'Winona Ryder'),
(2, 'David Harbour'),
(3, 'Millie Bobby Brown'),
(4, 'Finn Wolfhard'),
(6, 'Imelda Staunton'),
(7, 'Anthony Gonzalez'),
(8, 'Gael García Bernal'),
(9, 'Tom Cruise'),
(10, 'Jon Voight'),
(11, 'Bryan Cranston'),
(12, 'Aaron Paul'),
(13, 'Karl Urban'),
(14, 'Jack Quaid'),
(15, 'Francisca Estévez'),
(16, 'Emmanuel Restrepo'),
(17, 'Timothée Chalamet'),
(18, 'Zendaya'),
(19, 'Sam Worthington'),
(20, 'Zoe Saldaña'),
(21, 'Daniel Craig'),
(22, 'Keanu Reeves'),
(24, 'Rebecca Ferguson');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cinta`
--

CREATE TABLE `cinta` (
  `id_cinta` int(11) NOT NULL,
  `pelicula_id_pelicula` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `cinta`
--

INSERT INTO `cinta` (`id_cinta`, `pelicula_id_pelicula`) VALUES
(25, 4),
(5, 5),
(6, 6),
(8, 8),
(17, 10),
(18, 10),
(13, 17),
(14, 17),
(15, 17),
(16, 17),
(20, 17),
(19, 18),
(21, 20),
(22, 20),
(23, 20),
(24, 21);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `director`
--

CREATE TABLE `director` (
  `id_director` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `director`
--

INSERT INTO `director` (`id_director`, `nombre`) VALUES
(1, 'The Duffer Brothers'),
(2, 'Peter Morgan'),
(3, 'Lee Unkrich'),
(4, 'Brian De Palma'),
(5, 'Vince Gilligan'),
(6, 'Eric Kripke'),
(7, 'Dago García'),
(8, 'Denis Villeneuve'),
(9, 'James Cameron'),
(10, 'Sam Mendes'),
(11, 'Chris Sanders'),
(12, 'Lilly Wachowski'),
(13, 'Louis Leterrier'),
(14, 'Nola'),
(15, 'Denis Villeneuve');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `idFactura` int(11) NOT NULL,
  `Nombre_user` varchar(45) NOT NULL,
  `precio_alquiler` varchar(45) NOT NULL,
  `fecha_factura` varchar(45) NOT NULL,
  `nombre_pelicula` int(11) NOT NULL,
  `Usuario_id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`idFactura`, `Nombre_user`, `precio_alquiler`, `fecha_factura`, `nombre_pelicula`, `Usuario_id_usuario`) VALUES
(1, 'felipe murillo', '16000.0', '2025-11-28 05:18:48', 6, 1),
(2, 'felipe murillo', '19900.0', '2025-11-28 05:19:35', 17, 1),
(3, 'felipe murillo', '19900.0', '2025-11-28 05:19:42', 18, 1),
(4, 'felipe murillo', '19900.0', '2025-11-28 05:35:10', 17, 1),
(5, 'maria Rodriguez', '19900.0', '2025-11-28 05:49:57', 18, 16),
(6, 'felipe murillo', '25600.0', '2025-11-28 05:58:04', 13, 1),
(18, 'maria Rodriguez', '16000.0', '2025-11-28 17:56:47', 5, 16),
(19, 'maria Rodriguez', '16000.0', '2025-11-28 17:57:46', 5, 16),
(20, 'maria Rodriguez', '25600.0', '2025-11-28 13:02:18', 14, 16),
(22, 'felipe murillo', '19900.0', '2025-11-28 13:05:18', 17, 1),
(23, 'felipe murillo', '17000.0', '2025-11-28 13:09:25', 8, 1),
(24, 'felipe murillo', '13000.0', '2025-11-28 15:04:07', 25, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero`
--

CREATE TABLE `genero` (
  `id_genero` int(11) NOT NULL,
  `nombre` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `genero`
--

INSERT INTO `genero` (`id_genero`, `nombre`) VALUES
(1, 'Sci-fi'),
(2, 'Terror'),
(3, 'Drama'),
(4, 'Animación'),
(5, 'Acción'),
(7, 'Comedia'),
(8, 'Fantasía'),
(9, 'Espionaje');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gusta_director`
--

CREATE TABLE `gusta_director` (
  `Usuario_id_usuario` int(11) NOT NULL,
  `director_id_director` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `gusta_director`
--

INSERT INTO `gusta_director` (`Usuario_id_usuario`, `director_id_director`) VALUES
(1, 8),
(1, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gusto_actor`
--

CREATE TABLE `gusto_actor` (
  `Usuario_id_usuario` int(11) NOT NULL,
  `actor_id_actor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `gusto_actor`
--

INSERT INTO `gusto_actor` (`Usuario_id_usuario`, `actor_id_actor`) VALUES
(1, 14),
(1, 3),
(1, 9),
(1, 18);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gusto_genero`
--

CREATE TABLE `gusto_genero` (
  `Usuario_id_usuario` int(11) NOT NULL,
  `genero_id_genero` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `gusto_genero`
--

INSERT INTO `gusto_genero` (`Usuario_id_usuario`, `genero_id_genero`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lista_espera`
--

CREATE TABLE `lista_espera` (
  `id_espera` int(11) NOT NULL,
  `fecha_solicitud` datetime NOT NULL,
  `Usuario_id_usuario` int(11) NOT NULL,
  `pelicula_id_pelicula` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `lista_espera`
--

INSERT INTO `lista_espera` (`id_espera`, `fecha_solicitud`, `Usuario_id_usuario`, `pelicula_id_pelicula`) VALUES
(4, '2025-11-28 13:48:19', 1, 4),
(5, '2025-11-28 15:04:25', 16, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pelicula`
--

CREATE TABLE `pelicula` (
  `id_pelicula` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `anio` int(11) NOT NULL,
  `duracion_min` int(11) NOT NULL,
  `descripcion` varchar(350) NOT NULL,
  `poster_path` varchar(255) NOT NULL,
  `precio_alquiler` decimal(8,0) NOT NULL,
  `calificacion` varchar(45) NOT NULL,
  `director_id_director` int(11) NOT NULL,
  `idioma` varchar(50) NOT NULL DEFAULT 'Desconocido',
  `ncopias` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `pelicula`
--

INSERT INTO `pelicula` (`id_pelicula`, `titulo`, `anio`, `duracion_min`, `descripcion`, `poster_path`, `precio_alquiler`, `calificacion`, `director_id_director`, `idioma`, `ncopias`) VALUES
(2, 'The Crown', 2023, 2160, 'Crónica de la vida de la Reina Isabel II.', 'https://hbz.h-cdn.co/assets/16/40/1600x800/landscape-1475588380-the-crown-index.jpg', 12000, '13+', 2, 'Inglés', 0),
(4, 'Misión Imposible', 2024, 110, 'Un agente del FMI es incriminado; debe limpiar su nombre.', 'https://github.com/felipec51/ProyectoFinalWEB/blob/main/imgs/mission.webp?raw=true', 13000, '13+', 4, 'Inglés', 1),
(5, 'Breaking Bad', 2008, 3000, 'Profesor de química con cáncer fabrica y vende metanfetamina.', 'https://github.com/felipec51/ProyectoFinalWEB/blob/main/imgs/breaking-bad_2880x1800_xtrafondos.com.webp?raw=true', 16000, '18+', 5, 'Inglés', 1),
(6, 'The Boys', 2019, 1920, 'Vigilantes se enfrentan a superhéroes corruptos.', 'https://www.hdwallpapers.in/download/the_boys_poster_4k_hd-3840x2160.jpg', 16000, '18+', 6, 'Inglés', 1),
(8, 'Dune', 2021, 155, 'Épica odisea de la familia Atreides en un planeta desértico.', 'https://github.com/felipec51/ProyectoFinalWEB/blob/main/imgs/poster-de-dune_2560x1440_xtrafondos.com.webp?raw=true', 17000, '13+', 8, 'Inglés', 1),
(10, 'Matrix 1999', 1999, 136, 'Un experto en computadoras descubre que su mundo es una simulación total creada con maliciosas intenciones por parte de la ciberinteligencia.', 'https://github.com/felipec51/ProyectoFinalWEB/blob/main/imgs/the-matrix-resurrections_2560x1440_xtrafondos.com.webp?raw=true', 19900, '+18', 12, 'Inglés', 2),
(17, 'Robot salvaje ', 2022, 102, 'es u. robot salvaje', 'https://hablemosconspoilers.com/wp-content/uploads/2024/09/HCS-32-png.webp', 25600, '13+', 11, 'Inglés ', 5),
(18, 'Blade Runner 2049', 2017, 200, 'Blade Runner 2049 trata sobre el agente K, un nuevo \"blade runner\" que descubre un secreto enterrado durante mucho tiempo que podría desestabilizar la sociedad: la capacidad de los replicantes para reproducirse.', 'https://preview.redd.it/won4unyk84y31.jpg?width=1080&crop=smart&auto=webp&s=f36f1d7f90a30dd2c1021ecd55842d0ba527093b', 12000, '13+', 8, 'Inglés', 1),
(20, 'Interestelas', 2018, 120, 'es una ', 'https://media.vandalsports.com/i/640x360/4-2024/202441895311_1.jpg', 19000, '19+', 14, 'Inglés', 3),
(21, 'El joker', 2024, 155, 'Joker es una película de suspense psicológico estadounidense de 2019 dirigida y producida por Todd Phillips, quien coescribió el guion con Scott Silver. La película, basada en personajes de DC Comics, está protagonizada por Joaquin Phoenix como El Joker y proporciona una historia alternativa del origen del personaje. ', 'https://i.ytimg.com/vi/QtZPjL_CbQI/maxresdefault.jpg', 18000, 'PG-13', 15, 'Inglés', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pelicula_actor`
--

CREATE TABLE `pelicula_actor` (
  `pelicula_id_pelicula` int(11) NOT NULL,
  `actor_id_actor` int(11) NOT NULL,
  `rol_pelicula` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `pelicula_actor`
--

INSERT INTO `pelicula_actor` (`pelicula_id_pelicula`, `actor_id_actor`, `rol_pelicula`) VALUES
(2, 6, 'Protagonista (Isabel II - posterior)'),
(5, 11, 'Protagonista (Walter White)'),
(5, 12, 'Protagonista (Jesse Pinkman)'),
(6, 13, 'Protagonista (Billy)'),
(6, 14, 'Protagonista (Hughie)'),
(8, 17, 'Protagonista (Paul Atreides)'),
(8, 18, 'Protagonista (Chani)'),
(17, 2, 'Protagonista'),
(10, 22, 'Protagonista\r\n'),
(21, 2, 'Protagonista'),
(21, 24, 'Protagonista'),
(21, 17, 'Protagonista'),
(21, 18, 'Protagonista'),
(4, 9, 'Protagonista');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pelicula_genero`
--

CREATE TABLE `pelicula_genero` (
  `pelicula_id_pelicula` int(11) NOT NULL,
  `genero_id_genero` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `pelicula_genero`
--

INSERT INTO `pelicula_genero` (`pelicula_id_pelicula`, `genero_id_genero`) VALUES
(2, 3),
(5, 3),
(6, 5),
(8, 1),
(8, 8),
(17, 4),
(10, 1),
(20, 5),
(20, 8),
(20, 1),
(18, 5),
(18, 1),
(21, 5),
(21, 3),
(4, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamo`
--

CREATE TABLE `prestamo` (
  `id_prestamo` int(11) NOT NULL,
  `fecha_prestamo` datetime NOT NULL,
  `fecha_devolucion` datetime NOT NULL,
  `Usuario_id_usuario` int(11) NOT NULL,
  `cinta_id_cinta` int(11) NOT NULL,
  `estado_alquiler` enum('en curso','finalizado') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `prestamo`
--

INSERT INTO `prestamo` (`id_prestamo`, `fecha_prestamo`, `fecha_devolucion`, `Usuario_id_usuario`, `cinta_id_cinta`, `estado_alquiler`) VALUES
(1, '2025-11-28 05:18:48', '2025-12-05 05:18:48', 1, 6, 'en curso'),
(3, '2025-11-28 05:19:42', '2025-12-05 05:19:42', 1, 18, 'finalizado'),
(4, '2025-11-28 05:35:10', '2025-12-05 05:35:10', 1, 17, 'finalizado'),
(5, '2025-11-28 05:49:57', '2025-12-05 05:49:57', 16, 18, 'en curso'),
(6, '2025-11-30 05:58:04', '2025-12-05 05:58:04', 1, 13, 'en curso'),
(25, '2025-11-28 17:56:47', '2025-12-05 17:56:47', 16, 5, 'finalizado'),
(26, '2025-11-28 17:57:46', '2025-12-05 17:57:46', 16, 5, 'en curso'),
(27, '2025-11-28 13:02:18', '2025-12-05 13:02:18', 16, 14, 'en curso'),
(29, '2025-11-28 13:05:18', '2025-12-04 13:05:18', 1, 17, 'en curso'),
(30, '2025-11-28 13:09:25', '2025-12-05 13:09:25', 1, 8, 'en curso'),
(31, '2025-11-28 15:04:07', '2025-12-05 15:04:07', 1, 25, 'en curso');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre`) VALUES
(1, 'admin'),
(2, 'socio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `traileres`
--

CREATE TABLE `traileres` (
  `id_traileres` int(11) NOT NULL,
  `ruta` varchar(255) NOT NULL,
  `pelicula_id_pelicula` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `traileres`
--

INSERT INTO `traileres` (`id_traileres`, `ruta`, `pelicula_id_pelicula`) VALUES
(3, 'https://www.youtube.com/embed/Way9Dexny3w?si=Az-JibfmXD5b4qLS', 8),
(4, 'https://www.youtube.com/embed/2O5VhM1f1pY', 2),
(8, 'https://www.youtube.com/embed/HhFk_v_7y7U', 5),
(9, 'https://www.youtube.com/embed/UPgUIORqja4?si=hI5gDRr3XpLbzAWl\" title=\"YouTube video player', 17),
(10, 'https://www.youtube.com/embed/ECtK_IR4j5I?si=M3UUiBzBc1hyVOeY', 21),
(11, 'https://www.youtube.com/embed/ygUHhImN98w?si=TqAoBgCdg11U_pty', 21),
(12, 'https://www.youtube.com/embed/Z4Y4eJ_403Q', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuario`
--

CREATE TABLE `Usuario` (
  `id_usuario` int(11) NOT NULL,
  `username` varchar(80) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `direccion` varchar(45) NOT NULL,
  `telefono` varchar(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `pregunta_seguridad` varchar(255) DEFAULT '¿Cuál es el nombre de tu primera mascota?',
  `respuesta_seguridad` varchar(255) DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL,
  `rol_id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `Usuario`
--

INSERT INTO `Usuario` (`id_usuario`, `username`, `password`, `nombre`, `direccion`, `telefono`, `email`, `pregunta_seguridad`, `respuesta_seguridad`, `fecha_creacion`, `rol_id_rol`) VALUES
(1, 'felipe', 'nata1234', 'felipe murillo', 'calle12', '3052781', 'marlon@gmail.com', '¿Cuál es el nombre de tu primera mascota?', 'nata', '2025-11-17 03:20:52', 2),
(2, 'admin', 'admin', 'Administrador', 'Sede Central', '0000000000', 'admin@videoclub.local', '¿Cuál es el nombre de tu primera mascota?', 'mono', '2025-11-01 08:00:00', 1),
(10, 'johanc', 'temporal123', 'johan perez', 'calle 13', '3059239494', 'johan@gmail.com', '¿Cuál es el nombre de tu primera mascota?', 'mono', '2025-11-18 00:59:20', 2),
(16, 'maria', 'maria123', 'maria Rodriguez', 'calle 15', '305924981', 'mari123@gmail.com', 'madre', 'isa', '2025-11-19 22:55:16', 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actor`
--
ALTER TABLE `actor`
  ADD PRIMARY KEY (`id_actor`);

--
-- Indices de la tabla `cinta`
--
ALTER TABLE `cinta`
  ADD PRIMARY KEY (`id_cinta`),
  ADD KEY `fk_cinta_pelicula1_idx` (`pelicula_id_pelicula`);

--
-- Indices de la tabla `director`
--
ALTER TABLE `director`
  ADD PRIMARY KEY (`id_director`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`idFactura`),
  ADD KEY `fk_Factura_Usuario1_idx` (`Usuario_id_usuario`);

--
-- Indices de la tabla `genero`
--
ALTER TABLE `genero`
  ADD PRIMARY KEY (`id_genero`);

--
-- Indices de la tabla `gusta_director`
--
ALTER TABLE `gusta_director`
  ADD KEY `fk_Usuario_has_director_director1_idx` (`director_id_director`),
  ADD KEY `fk_Usuario_has_director_Usuario1_idx` (`Usuario_id_usuario`);

--
-- Indices de la tabla `gusto_actor`
--
ALTER TABLE `gusto_actor`
  ADD KEY `fk_gusto_actor_Usuario1_idx` (`Usuario_id_usuario`),
  ADD KEY `fk_gusto_actor_actor1_idx` (`actor_id_actor`);

--
-- Indices de la tabla `gusto_genero`
--
ALTER TABLE `gusto_genero`
  ADD KEY `fk_gusto_genero_Usuario1_idx` (`Usuario_id_usuario`),
  ADD KEY `fk_gusto_genero_genero1_idx` (`genero_id_genero`);

--
-- Indices de la tabla `lista_espera`
--
ALTER TABLE `lista_espera`
  ADD PRIMARY KEY (`id_espera`),
  ADD KEY `fk_lista_espera_Usuario1_idx` (`Usuario_id_usuario`),
  ADD KEY `fk_lista_espera_pelicula1_idx` (`pelicula_id_pelicula`);

--
-- Indices de la tabla `pelicula`
--
ALTER TABLE `pelicula`
  ADD PRIMARY KEY (`id_pelicula`),
  ADD KEY `fk_pelicula_director1_idx` (`director_id_director`);

--
-- Indices de la tabla `pelicula_actor`
--
ALTER TABLE `pelicula_actor`
  ADD KEY `fk_table1_pelicula1_idx` (`pelicula_id_pelicula`),
  ADD KEY `fk_table1_actor1_idx` (`actor_id_actor`);

--
-- Indices de la tabla `pelicula_genero`
--
ALTER TABLE `pelicula_genero`
  ADD KEY `fk_table1_pelicula2_idx` (`pelicula_id_pelicula`),
  ADD KEY `fk_table1_genero1_idx` (`genero_id_genero`);

--
-- Indices de la tabla `prestamo`
--
ALTER TABLE `prestamo`
  ADD PRIMARY KEY (`id_prestamo`),
  ADD KEY `fk_prestamo_Usuario1_idx` (`Usuario_id_usuario`),
  ADD KEY `fk_prestamo_cinta1_idx` (`cinta_id_cinta`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `traileres`
--
ALTER TABLE `traileres`
  ADD PRIMARY KEY (`id_traileres`),
  ADD KEY `fk_imagenes_pelicula1_idx` (`pelicula_id_pelicula`);

--
-- Indices de la tabla `Usuario`
--
ALTER TABLE `Usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `fk_Usuario_rol_idx` (`rol_id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actor`
--
ALTER TABLE `actor`
  MODIFY `id_actor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `cinta`
--
ALTER TABLE `cinta`
  MODIFY `id_cinta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `director`
--
ALTER TABLE `director`
  MODIFY `id_director` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `idFactura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `genero`
--
ALTER TABLE `genero`
  MODIFY `id_genero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `lista_espera`
--
ALTER TABLE `lista_espera`
  MODIFY `id_espera` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pelicula`
--
ALTER TABLE `pelicula`
  MODIFY `id_pelicula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `prestamo`
--
ALTER TABLE `prestamo`
  MODIFY `id_prestamo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `traileres`
--
ALTER TABLE `traileres`
  MODIFY `id_traileres` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `Usuario`
--
ALTER TABLE `Usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cinta`
--
ALTER TABLE `cinta`
  ADD CONSTRAINT `fk_cinta_pelicula1` FOREIGN KEY (`pelicula_id_pelicula`) REFERENCES `pelicula` (`id_pelicula`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `fk_Factura_Usuario1` FOREIGN KEY (`Usuario_id_usuario`) REFERENCES `Usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Factura_cinta1` FOREIGN KEY (`nombre_pelicula`) REFERENCES `cinta` (`id_cinta`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `gusta_director`
--
ALTER TABLE `gusta_director`
  ADD CONSTRAINT `fk_Usuario_has_director_Usuario1` FOREIGN KEY (`Usuario_id_usuario`) REFERENCES `Usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Usuario_has_director_director1` FOREIGN KEY (`director_id_director`) REFERENCES `director` (`id_director`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `gusto_actor`
--
ALTER TABLE `gusto_actor`
  ADD CONSTRAINT `fk_gusto_actor_Usuario1` FOREIGN KEY (`Usuario_id_usuario`) REFERENCES `Usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_gusto_actor_actor1` FOREIGN KEY (`actor_id_actor`) REFERENCES `actor` (`id_actor`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `gusto_genero`
--
ALTER TABLE `gusto_genero`
  ADD CONSTRAINT `fk_gusto_genero_Usuario1` FOREIGN KEY (`Usuario_id_usuario`) REFERENCES `Usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_gusto_genero_genero1` FOREIGN KEY (`genero_id_genero`) REFERENCES `genero` (`id_genero`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `lista_espera`
--
ALTER TABLE `lista_espera`
  ADD CONSTRAINT `fk_lista_espera_Usuario1` FOREIGN KEY (`Usuario_id_usuario`) REFERENCES `Usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_lista_espera_pelicula1` FOREIGN KEY (`pelicula_id_pelicula`) REFERENCES `pelicula` (`id_pelicula`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pelicula`
--
ALTER TABLE `pelicula`
  ADD CONSTRAINT `fk_pelicula_director1` FOREIGN KEY (`director_id_director`) REFERENCES `director` (`id_director`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pelicula_actor`
--
ALTER TABLE `pelicula_actor`
  ADD CONSTRAINT `fk_table1_actor1` FOREIGN KEY (`actor_id_actor`) REFERENCES `actor` (`id_actor`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_table1_pelicula1` FOREIGN KEY (`pelicula_id_pelicula`) REFERENCES `pelicula` (`id_pelicula`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pelicula_genero`
--
ALTER TABLE `pelicula_genero`
  ADD CONSTRAINT `fk_table1_genero1` FOREIGN KEY (`genero_id_genero`) REFERENCES `genero` (`id_genero`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_table1_pelicula2` FOREIGN KEY (`pelicula_id_pelicula`) REFERENCES `pelicula` (`id_pelicula`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `prestamo`
--
ALTER TABLE `prestamo`
  ADD CONSTRAINT `fk_prestamo_Usuario1` FOREIGN KEY (`Usuario_id_usuario`) REFERENCES `Usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_prestamo_cinta1` FOREIGN KEY (`cinta_id_cinta`) REFERENCES `cinta` (`id_cinta`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `traileres`
--
ALTER TABLE `traileres`
  ADD CONSTRAINT `fk_imagenes_pelicula1` FOREIGN KEY (`pelicula_id_pelicula`) REFERENCES `pelicula` (`id_pelicula`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `Usuario`
--
ALTER TABLE `Usuario`
  ADD CONSTRAINT `fk_Usuario_rol` FOREIGN KEY (`rol_id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
