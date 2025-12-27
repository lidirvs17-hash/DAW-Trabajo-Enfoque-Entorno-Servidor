-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: sql110.infinityfree.com
-- Tiempo de generación: 26-12-2025 a las 17:00:19
-- Versión del servidor: 11.4.7-MariaDB
-- Versión de PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `if0_40734835_cross_kicks`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos`
--

CREATE TABLE `articulos` (
  `id_articulo` int(10) NOT NULL COMMENT 'AUTO_INCREMENT',
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(8,2) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `genero` varchar(50) NOT NULL COMMENT '''Hombre'', ''Mujer'', ''Unisex''',
  `categoria` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `articulos`
--

INSERT INTO `articulos` (`id_articulo`, `nombre`, `descripcion`, `precio`, `imagen`, `genero`, `categoria`) VALUES
(1, 'The Pipe Jumper', 'Gravity Control: Soporte de tobillo y suela para máxima altura y aterrizajes suaves.', '165.00', 'THEPIPEJUMPER.png', '0', 'Baloncesto'),
(2, 'The Rift Walker', 'The Stabilizer Rig: Base ancha para levantar peso y hacer entrenamiento de fuerza. Estabilidad para seguir hasta el fallo.', '148.00', 'THERIFTWALKER.png', '0', 'Entrenamiento'),
(3, 'The Ring Dash', 'Velocity Coil: Amortiguación ultraligera y propulsiva para el Dash de velocidad. Pensadas para carreras de larga duración.', '158.95', 'THERINGDASH.png', '0', 'Running'),
(4, 'The Keyblade Keeper', 'Heartless Defense: Diseño duradero &#34;multi-mundo&#34;. Combinación de trail y comodidad versátil.', '134.95', 'THEKEYBLADEKEEPER.png', '0', 'Running'),
(5, 'The Sheikah Climb', 'Climbing Grip Matrix: Agarre avanzado en puntera y talón para ascensos. Ideal para el parkour de montaña.', '179.00', 'THESHEIKAHCLIMB.png', '0', 'Senderismo/Escalada'),
(6, 'The Iron Fist Punch', 'Impact Dampener: Amortiguación estratégica para entrenamientos de impacto.', '155.00', 'THEIRONFIRSTPUNCH.png', '0', 'Boxeo'),
(8, 'The Tarnished Journey', 'BOSS FINAL – Resilience Core &#38; Flask of Crimson Tears: Máxima protección y amortiguación doble para carreras ultra-largas de resistencia extrema.', '220.00', 'THETARNISHEDJOURNEY.png', '0', 'Running');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulo_talla`
--

CREATE TABLE `articulo_talla` (
  `id_articulo` int(11) NOT NULL COMMENT 'Clave Foránea a articulos.id_articulo',
  `talla` varchar(10) NOT NULL,
  `stock` int(11) NOT NULL COMMENT 'El stock específico de esta talla.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `articulo_talla`
--

INSERT INTO `articulo_talla` (`id_articulo`, `talla`, `stock`) VALUES
(1, '37', 3),
(1, '38', 5),
(1, '39', 4),
(1, '40', 5),
(1, '41', 0),
(1, '42', 6),
(1, '43', 0),
(1, '44', 0),
(1, '45', 0),
(2, '37', 4),
(2, '38', 5),
(2, '39', 2),
(2, '40', 3),
(2, '41', 0),
(2, '42', 3),
(2, '43', 0),
(2, '44', 0),
(2, '45', 0),
(3, '37', 5),
(3, '38', 2),
(3, '39', 5),
(3, '40', 0),
(3, '41', 0),
(3, '42', 4),
(3, '43', 0),
(3, '44', 0),
(3, '45', 0),
(4, '37', 0),
(4, '38', 5),
(4, '39', 5),
(4, '40', 4),
(4, '41', 3),
(4, '42', 0),
(4, '43', 0),
(4, '44', 0),
(4, '45', 0),
(5, '37', 5),
(5, '38', 4),
(5, '39', 0),
(5, '40', 5),
(5, '41', 0),
(5, '42', 6),
(5, '43', 0),
(5, '44', 0),
(5, '45', 0),
(6, '37', 0),
(6, '38', 3),
(6, '39', 3),
(6, '40', 1),
(6, '41', 3),
(6, '42', 1),
(6, '43', 4),
(6, '44', 2),
(6, '45', 0),
(8, '37', 0),
(8, '38', 0),
(8, '39', 1),
(8, '40', 1),
(8, '41', 0),
(8, '42', 1),
(8, '43', 0),
(8, '44', 0),
(8, '45', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `id_pedido` int(10) NOT NULL,
  `id_articulo` int(10) NOT NULL,
  `cantidad` int(10) NOT NULL,
  `precio_unitario` decimal(8,2) NOT NULL,
  `talla` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_pedido`
--

INSERT INTO `detalle_pedido` (`id_pedido`, `id_articulo`, `cantidad`, `precio_unitario`, `talla`) VALUES
(11, 2, 1, '148.00', ''),
(11, 6, 1, '155.00', ''),
(12, 6, 1, '155.00', ''),
(13, 5, 1, '179.00', ''),
(14, 4, 1, '134.95', ''),
(15, 6, 2, '155.00', ''),
(18, 6, 1, '155.00', ''),
(19, 4, 1, '134.95', ''),
(24, 6, 2, '155.00', ''),
(25, 6, 3, '155.00', ''),
(26, 6, 1, '155.00', ''),
(27, 1, 1, '165.00', ''),
(27, 6, 1, '155.00', ''),
(28, 6, 2, '155.00', ''),
(29, 6, 1, '155.00', ''),
(30, 6, 1, '155.00', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `id_pedido` int(10) NOT NULL COMMENT 'AUTO_INCREMENT',
  `id_usuario` int(10) NOT NULL,
  `fecha_pedido` datetime NOT NULL,
  `total` decimal(8,2) NOT NULL,
  `estado_pedido` varchar(50) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `cp` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`id_pedido`, `id_usuario`, `fecha_pedido`, `total`, `estado_pedido`, `direccion`, `ciudad`, `cp`) VALUES
(11, 1, '2025-12-04 10:54:20', '303.00', '', '', '', ''),
(12, 1, '2025-12-04 13:22:46', '155.00', '', '', '', ''),
(13, 1, '2025-12-10 18:21:09', '179.00', '', '', '', ''),
(14, 11, '2025-12-21 17:42:41', '134.95', '', '', '', ''),
(15, 1, '2025-12-22 12:12:47', '310.00', '', '', '', ''),
(18, 1, '2025-12-22 13:09:39', '155.00', '', '', '', ''),
(19, 1, '2025-12-22 13:52:06', '134.95', '', '', '', ''),
(24, 15, '2025-12-22 14:29:15', '310.00', '', '', '', ''),
(25, 1, '2025-12-23 14:07:17', '465.00', '', 'C/La piña', 'Debajodelmar', '12344'),
(26, 1, '2025-12-25 12:08:33', '155.00', '', 'C/Piña ', 'Debajodelmar', '23445'),
(27, 1, '2025-12-25 12:27:36', '320.00', '', 'C/VÍCAR Nº2 Bloque 4 Bajo C', 'SANTA FE', '18320'),
(28, 1, '2025-12-25 12:29:33', '310.00', '', 'C/VÍCAR Nº2 Bloque 4 Bajo C', 'SANTA FE', '18320'),
(29, 1, '2025-12-26 05:47:50', '155.00', '', 'C/VÍCAR Nº2 Bloque 4 Bajo C', 'SANTA FE', '18320'),
(30, 1, '2025-12-26 12:17:53', '155.00', '', 'C/Piña ', 'Debajodelmar', '12344');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(10) NOT NULL COMMENT 'AUTO_INCREMENT',
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('Admin','Cliente') NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `email`, `password`, `rol`, `nombre`, `apellido`) VALUES
(1, 'Admin@tiendaz.com', '$2y$10$7Ss4x5mamsB3fYP.KwJgeO.Zz5NbsmgyM5hthcyFKOSelWu0Iz0IS', 'Admin', 'Sup', 'Admin'),
(11, 'UsuarioCliente@gmail.com', '$2y$10$6yq793DtAQOOK/ou9PY0xOQxZV7B1nWu.CO9VJpO89Tb4wGQPRLfy', 'Cliente', 'Prueba', 'Cliente'),
(15, 'cesarino@medac.com', '$2y$10$xtFQupEu4/CDVtV5slQ/suTUuEqNUg79x6hpXG6ZVScceBpGw2fZ.', 'Cliente', 'Cesarino', 'Pan y Vino'),
(17, 'unodosetc@gmail.com', '$2y$10$iAT2ZE3YolPY0SP8f.nebO4yRfHgwkjCPLMPOp6Hv8QwXDrR7rxxS', 'Cliente', 'Uno', 'Dos'),
(18, 'mecmecmec@gmail.com', '$2y$10$C8nTc5ZX3oDV6AJf9YPtHOUX.rcARsQkQdJmQAQasgcJHsacGaNQq', 'Cliente', 'Mec', 'Mec');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `articulos`
--
ALTER TABLE `articulos`
  ADD PRIMARY KEY (`id_articulo`);

--
-- Indices de la tabla `articulo_talla`
--
ALTER TABLE `articulo_talla`
  ADD PRIMARY KEY (`id_articulo`,`talla`);

--
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`id_pedido`,`id_articulo`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `fk_usuario_id` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `articulos`
--
ALTER TABLE `articulos`
  MODIFY `id_articulo` int(10) NOT NULL AUTO_INCREMENT COMMENT 'AUTO_INCREMENT', AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id_pedido` int(10) NOT NULL AUTO_INCREMENT COMMENT 'AUTO_INCREMENT', AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(10) NOT NULL AUTO_INCREMENT COMMENT 'AUTO_INCREMENT', AUTO_INCREMENT=19;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `articulo_talla`
--
ALTER TABLE `articulo_talla`
  ADD CONSTRAINT `articulo_talla_ibfk_1` FOREIGN KEY (`id_articulo`) REFERENCES `articulos` (`id_articulo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
