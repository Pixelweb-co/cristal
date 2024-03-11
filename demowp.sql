-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-03-2024 a las 15:38:36
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `demowp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_orden`
--

CREATE TABLE `wp_orden` (
  `id` mediumint(9) NOT NULL,
  `fecha_orden` datetime NOT NULL,
  `cliente` varchar(100) NOT NULL,
  `cliente_name` varchar(255) NOT NULL,
  `totalOrden` decimal(10,2) NOT NULL,
  `fichero_adjunto` varchar(100) NOT NULL,
  `marca` varchar(100) NOT NULL,
  `image_marca` varchar(100) NOT NULL,
  `name_marca` varchar(100) NOT NULL,
  `links` varchar(255) NOT NULL,
  `is_send` varchar(255) NOT NULL,
  `tienda` varchar(255) NOT NULL,
  `tienda_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Volcado de datos para la tabla `wp_orden`
--

INSERT INTO `wp_orden` (`id`, `fecha_orden`, `cliente`, `cliente_name`, `totalOrden`, `fichero_adjunto`, `marca`, `image_marca`, `name_marca`, `links`, `is_send`, `tienda`, `tienda_name`) VALUES
(1, '2024-03-11 02:19:34', '1', '', 50000.00, '', '18', 'http://localhost/demowp/wp-content/uploads/2024/02/Baby-fresh-1.png', 'baby fresh', '[]', '0', '50', 'undefined'),
(2, '2024-03-11 11:11:59', '1', '', 50000.00, '', '18', 'http://localhost/demowp/wp-content/uploads/2024/02/Baby-fresh-1.png', 'baby fresh', '[]', '0', '50', 'undefined'),
(3, '2024-03-11 14:45:34', '1', '', 276000.00, '', '18', 'http://localhost/demowp/wp-content/uploads/2024/02/Baby-fresh-1.png', 'baby fresh', '[\"http://www.google.com\",\"https://facebook.com\"]', '1', '50', 'undefined'),
(4, '2024-03-11 15:22:15', '1', '', 500000.00, '', '18', 'http://localhost/demowp/wp-content/uploads/2024/02/Baby-fresh-1.png', 'baby fresh', '[]', '0', '50', 'undefined');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `wp_orden`
--
ALTER TABLE `wp_orden`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `wp_orden`
--
ALTER TABLE `wp_orden`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
