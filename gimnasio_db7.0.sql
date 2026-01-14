-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-12-2025 a las 11:40:03
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
-- Base de datos: `gimnasio_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_asesorias`
--

CREATE TABLE `tb_asesorias` (
  `id_asesoria` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_entrenador` int(11) DEFAULT NULL,
  `monto_final` decimal(10,0) NOT NULL,
  `descuento` decimal(10,2) DEFAULT 0.00,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `fyh_creacion` datetime NOT NULL,
  `fyh_actualizacion` datetime NOT NULL,
  `id_gimnasio` int(11) NOT NULL,
  `estado` int(1) DEFAULT 1 COMMENT '1=Activo, 0=Anulado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_asesorias`
--

INSERT INTO `tb_asesorias` (`id_asesoria`, `id_cliente`, `id_entrenador`, `monto_final`, `descuento`, `fecha_inicio`, `fecha_fin`, `fyh_creacion`, `fyh_actualizacion`, `id_gimnasio`, `estado`) VALUES
(12, 1000, 2, 500, 0.00, '2025-12-11', '2025-12-10', '2025-12-11 23:25:35', '2025-12-11 23:25:35', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_asistencias_clientes`
--

CREATE TABLE `tb_asistencias_clientes` (
  `id_asistencia` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `fecha_asistencia` date NOT NULL,
  `hora_entrada` time NOT NULL,
  `fyh_creacion` datetime DEFAULT NULL,
  `fyh_actualizacion` datetime DEFAULT NULL,
  `id_gimnasio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_asistencias_clientes`
--

INSERT INTO `tb_asistencias_clientes` (`id_asistencia`, `id_cliente`, `fecha_asistencia`, `hora_entrada`, `fyh_creacion`, `fyh_actualizacion`, `id_gimnasio`) VALUES
(15, 1001, '2025-10-28', '00:44:00', '2025-10-28 00:44:02', NULL, 2),
(16, 1000, '2025-11-30', '14:51:00', '2025-11-30 14:51:51', NULL, 1),
(17, 1000, '2025-11-11', '14:51:00', '2025-11-30 14:52:02', NULL, 1),
(18, 1000, '2025-10-07', '14:52:00', '2025-11-30 14:52:13', NULL, 1),
(19, 1008, '2025-11-01', '15:45:00', '2025-11-30 15:45:38', NULL, 1),
(20, 1008, '2025-10-29', '15:45:00', '2025-11-30 15:45:48', NULL, 1),
(21, 1008, '2025-11-30', '16:38:00', '2025-11-30 16:38:28', NULL, 1),
(22, 1007, '2025-12-11', '16:24:00', '2025-12-11 16:24:59', NULL, 1),
(23, 1002, '2025-12-11', '16:25:00', '2025-12-11 16:25:04', NULL, 1),
(24, 1009, '2025-12-11', '18:01:00', '2025-12-11 18:01:26', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_caja`
--

CREATE TABLE `tb_caja` (
  `id_caja` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_gimnasio` int(11) NOT NULL,
  `fecha_apertura` datetime NOT NULL,
  `fecha_cierre` datetime DEFAULT NULL,
  `monto_apertura` decimal(10,2) NOT NULL,
  `monto_cierre` decimal(10,2) DEFAULT NULL,
  `monto_sistema` decimal(10,2) DEFAULT NULL,
  `diferencia` decimal(10,2) DEFAULT NULL,
  `cantidad_pagos` int(11) DEFAULT 0,
  `estado` int(1) DEFAULT 1,
  `observaciones` text DEFAULT NULL,
  `fyh_creacion` datetime NOT NULL,
  `fyh_actualizacion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_caja`
--

INSERT INTO `tb_caja` (`id_caja`, `id_usuario`, `id_gimnasio`, `fecha_apertura`, `fecha_cierre`, `monto_apertura`, `monto_cierre`, `monto_sistema`, `diferencia`, `cantidad_pagos`, `estado`, `observaciones`, `fyh_creacion`, `fyh_actualizacion`) VALUES
(4, 10, 1, '2025-12-11 17:45:46', '2025-12-11 23:34:59', 500.00, 593.00, 593.00, -500.00, 5, 0, '', '2025-12-11 17:45:46', '2025-12-11 23:34:59'),
(5, 10, 1, '2025-12-11 23:35:43', '2025-12-11 23:36:27', 500.00, 910.00, 410.00, 0.00, 1, 0, 'ok', '2025-12-11 23:35:43', '2025-12-11 23:36:27'),
(6, 10, 1, '2025-12-13 06:25:32', '2025-12-13 06:26:19', 500.00, 500.00, 0.00, 0.00, 0, 0, '', '2025-12-13 06:25:32', '2025-12-13 06:26:19'),
(7, 10, 1, '2025-12-13 06:39:56', '2025-12-13 11:35:08', 500.00, 500.00, 0.00, 0.00, 0, 0, '', '2025-12-13 06:39:56', '2025-12-13 11:35:08'),
(8, 10, 1, '2025-12-13 11:35:34', NULL, 300.00, NULL, NULL, NULL, 0, 1, NULL, '2025-12-13 11:35:34', '2025-12-13 11:35:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_clientes`
--

CREATE TABLE `tb_clientes` (
  `id_cliente` int(11) NOT NULL,
  `dni` varchar(15) DEFAULT NULL,
  `nombres` varchar(60) NOT NULL,
  `ape_pat` varchar(40) DEFAULT NULL,
  `ape_mat` varchar(40) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `fyh_creacion` datetime NOT NULL,
  `fyh_actualizacion` datetime NOT NULL,
  `foto` varchar(50) NOT NULL DEFAULT 'default_image.jpg',
  `id_gimnasio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_clientes`
--

INSERT INTO `tb_clientes` (`id_cliente`, `dni`, `nombres`, `ape_pat`, `ape_mat`, `telefono`, `email`, `fyh_creacion`, `fyh_actualizacion`, `foto`, `id_gimnasio`) VALUES
(1000, '12345678', 'Juan', 'Perez', 'Gomez', '987654321', 'juan.perez@example.com', '2025-07-21 19:37:52', '2025-11-30 14:36:46', '692c9cce309a9.webp', 1),
(1001, '87654321', 'Maria', 'Lopez', 'Diaz', '912345678', 'maria.lopez@example.com', '2025-07-21 19:37:52', '2025-07-21 19:37:52', 'default_image.jpg', 2),
(1002, '78471238', 'Andres', 'Sanchez', 'Garcia', '964905207', 'aabensurc07@gmai.com', '2025-07-22 21:03:45', '2025-11-30 15:41:51', '692cac0f89435.webp', 1),
(1003, '45789632', 'Juana', 'Mendez', 'Coronado', '964905207', 'juanam123@live.com', '2025-07-24 20:00:12', '0000-00-00 00:00:00', 'default_image.jpg', 2),
(1004, '75458965', 'Jose', 'Parraga', 'Mafaldo', '985214578', 'PEPITO123@GMAIL.COM', '2025-10-27 13:20:20', '2025-10-27 16:49:00', 'default_image.jpg', 2),
(1005, '85632544', 'Ernesto', 'Vidal', 'Somar', '965874587', 'ernesto123@gmail.com', '2025-10-27 23:28:08', '2025-10-28 11:20:59', 'default_image.jpg', 2),
(1007, '85458785', 'Carlos ', 'Rodrigueza', 'Almenara', '985478554', 'carlosr25@gmail.com', '2025-11-30 10:55:02', '0000-00-00 00:00:00', 'WS-Bio-Pic-Square-1_large.webp', 1),
(1008, '75548512', 'Alberto', 'Mendez', 'Quispe', '965874125', 'albever@live.com', '2025-11-30 15:43:57', '2025-11-30 15:45:17', '692cacdded969.webp', 1),
(1009, '54874589', 'Sebastian', 'Arce', 'Perez', '985487569', 'sebasarce4@gmail.com', '2025-12-11 11:45:59', '0000-00-00 00:00:00', 'default_image.jpg', 1),
(1010, '85458789', 'Aaron', 'Medina', 'Flores', '968545879', 'aaron158f@gmail.com', '2025-12-11 17:00:23', '0000-00-00 00:00:00', 'default_image.jpg', 1),
(1011, '56987458', 'Roberto', 'Lanaurre', 'Gomez', '985458785', 'robhert896@gmail.com', '2025-12-11 22:03:02', '0000-00-00 00:00:00', 'default_image.jpg', 1),
(1012, '85487896', 'Jorge', 'Tiensa', 'Gomez', '985632589', 'jorget02@gmail.com', '2025-12-12 19:41:26', '2025-12-12 19:41:56', 'default_image.jpg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_congelamientos`
--

CREATE TABLE `tb_congelamientos` (
  `id_congelamiento` int(11) NOT NULL,
  `id_matricula` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `motivo` text DEFAULT NULL,
  `dias_congelados` int(11) NOT NULL,
  `fyh_creacion` datetime NOT NULL,
  `fyh_actualizacion` datetime NOT NULL,
  `estado` int(1) DEFAULT 1 COMMENT '1=Activo, 0=Anulado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_congelamientos`
--

INSERT INTO `tb_congelamientos` (`id_congelamiento`, `id_matricula`, `fecha_inicio`, `fecha_fin`, `motivo`, `dias_congelados`, `fyh_creacion`, `fyh_actualizacion`, `estado`) VALUES
(1, 34, '2025-12-12', '2026-01-12', 'salud', 32, '2025-12-12 13:18:10', '2025-12-12 13:18:10', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_cronograma_pagos`
--

CREATE TABLE `tb_cronograma_pagos` (
  `id_cronograma` int(11) NOT NULL,
  `id_matricula_fk` int(11) DEFAULT NULL,
  `id_pago_fk` int(11) DEFAULT NULL,
  `id_venta_fk` int(11) DEFAULT NULL,
  `id_asesoria_fk` int(11) DEFAULT NULL,
  `nro_cuota` int(11) NOT NULL,
  `monto_programado` decimal(10,2) NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `estado_cuota` enum('Pendiente','Pagado','Parcial','Vencido') NOT NULL DEFAULT 'Pendiente',
  `fecha_pago_completado` datetime DEFAULT NULL,
  `observacion` text DEFAULT NULL,
  `fyh_creacion` datetime NOT NULL,
  `fyh_actualizacion` datetime NOT NULL,
  `id_gimnasio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_cronograma_pagos`
--

INSERT INTO `tb_cronograma_pagos` (`id_cronograma`, `id_matricula_fk`, `id_pago_fk`, `id_venta_fk`, `id_asesoria_fk`, `nro_cuota`, `monto_programado`, `fecha_vencimiento`, `estado_cuota`, `fecha_pago_completado`, `observacion`, `fyh_creacion`, `fyh_actualizacion`, `id_gimnasio`) VALUES
(16, 34, 105, NULL, NULL, 1, 33.33, '2025-12-11', 'Pagado', '2025-12-11 23:07:43', NULL, '2025-12-11 23:07:43', '2025-12-11 23:07:43', 1),
(17, 34, 107, NULL, NULL, 2, 30.00, '2026-01-10', 'Pagado', '2025-12-11 23:08:24', NULL, '2025-12-11 23:07:43', '2025-12-11 23:08:24', 1),
(18, 34, NULL, NULL, NULL, 3, 33.33, '2026-02-09', 'Pendiente', NULL, NULL, '2025-12-11 23:07:43', '2025-12-11 23:07:43', 1),
(19, 34, NULL, NULL, NULL, 2, 3.33, '2026-01-10', 'Pendiente', NULL, NULL, '2025-12-11 23:07:55', '2025-12-11 23:07:55', 1),
(20, NULL, 108, 27, NULL, 1, 0.00, '0000-00-00', 'Pagado', '2025-12-11 23:19:54', NULL, '2025-12-11 23:19:54', '2025-12-11 23:19:54', 1),
(21, NULL, 109, NULL, 12, 1, 250.00, '2025-12-11', 'Pagado', '2025-12-11 23:25:35', NULL, '2025-12-11 23:25:35', '2025-12-11 23:25:35', 1),
(22, NULL, 110, NULL, 12, 2, 200.00, '2026-01-10', 'Pagado', '2025-12-11 23:25:47', NULL, '2025-12-11 23:25:35', '2025-12-11 23:25:47', 1),
(23, NULL, NULL, NULL, 12, 2, 10.00, '2026-01-10', 'Pendiente', NULL, NULL, '2025-12-11 23:25:47', '2025-12-11 23:25:59', 1),
(24, NULL, NULL, NULL, 12, 2, 40.00, '2026-01-10', 'Pendiente', NULL, NULL, '2025-12-11 23:25:59', '2025-12-11 23:25:59', 1),
(25, NULL, 112, 29, NULL, 1, 80.00, '2025-12-11', 'Pagado', '2025-12-11 23:30:51', NULL, '2025-12-11 23:30:51', '2025-12-11 23:30:51', 1),
(26, NULL, NULL, 29, NULL, 2, 23.00, '2026-01-10', 'Pendiente', NULL, NULL, '2025-12-11 23:30:51', '2025-12-11 23:31:08', 1),
(27, NULL, NULL, 29, NULL, 3, 80.00, '2026-02-09', 'Pendiente', NULL, NULL, '2025-12-11 23:30:51', '2025-12-11 23:30:51', 1),
(28, NULL, NULL, 29, NULL, 2, 57.00, '2026-01-10', 'Pendiente', NULL, NULL, '2025-12-11 23:31:08', '2025-12-11 23:31:08', 1),
(29, NULL, 114, 30, NULL, 1, 410.00, '2025-12-11', 'Pagado', '2025-12-11 23:36:16', NULL, '2025-12-11 23:36:16', '2025-12-11 23:36:16', 1),
(30, 35, 115, NULL, NULL, 1, 500.00, '2025-12-13', 'Pagado', '2025-12-13 11:35:48', NULL, '2025-12-13 11:35:48', '2025-12-13 11:35:48', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_detalle_ventas`
--

CREATE TABLE `tb_detalle_ventas` (
  `id_detalle_venta` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `fyh_creacion` datetime NOT NULL,
  `fyh_actualizacion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_detalle_ventas`
--

INSERT INTO `tb_detalle_ventas` (`id_detalle_venta`, `id_venta`, `id_producto`, `cantidad`, `precio_unitario`, `subtotal`, `fyh_creacion`, `fyh_actualizacion`) VALUES
(43, 29, 13, 1, 60.00, 60.00, '2025-12-11 23:30:51', '2025-12-11 23:30:51'),
(44, 29, 12, 2, 70.00, 140.00, '2025-12-11 23:30:51', '2025-12-11 23:30:51'),
(45, 29, 11, 1, 40.00, 40.00, '2025-12-11 23:30:51', '2025-12-11 23:30:51'),
(46, 30, 12, 5, 70.00, 350.00, '2025-12-11 23:36:16', '2025-12-11 23:36:16'),
(47, 30, 13, 1, 60.00, 60.00, '2025-12-11 23:36:16', '2025-12-11 23:36:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_entrenadores`
--

CREATE TABLE `tb_entrenadores` (
  `id_entrenador` int(11) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `ape_pat` varchar(30) NOT NULL,
  `ape_mat` varchar(30) NOT NULL,
  `dni` varchar(15) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `foto` varchar(60) NOT NULL,
  `fyh_creacion` datetime NOT NULL,
  `fyh_actualizacion` datetime NOT NULL,
  `id_gimnasio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_entrenadores`
--

INSERT INTO `tb_entrenadores` (`id_entrenador`, `nombre`, `ape_pat`, `ape_mat`, `dni`, `telefono`, `email`, `foto`, `fyh_creacion`, `fyh_actualizacion`, `id_gimnasio`) VALUES
(1, 'Carlos', 'Ramirez', 'Soto', '11223344', '998877665', 'carlos.r@example.com', 'default_image.jpg', '2025-07-21 19:37:53', '2025-07-21 19:37:53', 2),
(2, 'Ana', 'Diaz', 'Vega', '55667788', '911223344', 'ana.d@example.com', '692c94e1e9c66.webp', '2025-07-21 19:37:53', '2025-11-30 14:02:57', 1),
(4, 'ESTEBAN', 'garcia', 'sanchez', '58962365', '985632578', 'esteban123@gmail.com', 'default_trainer.png', '2025-10-27 16:36:53', '2025-10-27 16:41:54', 2),
(6, 'Jose', 'Gomez', 'Peralta', '45217854', '985458754', 'joseg05@gmail.com', 'default_trainer.png', '2025-11-30 15:42:46', '2025-11-30 15:42:46', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_gimnasios`
--

CREATE TABLE `tb_gimnasios` (
  `id_gimnasio` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `imagen` varchar(50) NOT NULL DEFAULT 'default.jpg',
  `clave_descuento` varchar(255) DEFAULT '12345'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_gimnasios`
--

INSERT INTO `tb_gimnasios` (`id_gimnasio`, `nombre`, `imagen`, `clave_descuento`) VALUES
(1, 'CASSAFIT', 'cassafit.webp', '12345'),
(2, 'FITHGYM', 'fithgym.jpg', '12345');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_matriculas`
--

CREATE TABLE `tb_matriculas` (
  `id_matricula` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_plan` int(11) DEFAULT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `descuento` decimal(10,0) NOT NULL,
  `monto_final` decimal(10,0) NOT NULL,
  `fyh_creacion` datetime NOT NULL,
  `fyh_actualizacion` datetime NOT NULL,
  `id_gimnasio` int(11) NOT NULL,
  `estado` int(1) DEFAULT 1 COMMENT '1=Activo, 0=Anulado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_matriculas`
--

INSERT INTO `tb_matriculas` (`id_matricula`, `id_cliente`, `id_plan`, `fecha_inicio`, `fecha_fin`, `descuento`, `monto_final`, `fyh_creacion`, `fyh_actualizacion`, `id_gimnasio`, `estado`) VALUES
(34, 1000, 3, '2025-12-11', '2026-03-14', 0, 100, '2025-12-11 23:07:43', '2025-12-12 13:18:10', 1, 1),
(35, 1002, 4, '2025-12-13', '2025-12-31', 0, 500, '2025-12-13 11:35:48', '2025-12-13 11:35:48', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_pagos`
--

CREATE TABLE `tb_pagos` (
  `id_pago` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `tipo_pago` enum('matricula','venta','asesoria') NOT NULL,
  `id_matricula_fk` int(11) DEFAULT NULL,
  `id_venta_fk` int(11) DEFAULT NULL,
  `id_asesoria_fk` int(11) DEFAULT NULL,
  `metodo_pago` enum('efectivo','tarjeta_debito','tarjeta_credito','yape','plin') NOT NULL,
  `monto` decimal(10,0) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `id_gimnasio` int(11) NOT NULL,
  `estado` int(1) DEFAULT 1 COMMENT '1=Activo, 0=Anulado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_pagos`
--

INSERT INTO `tb_pagos` (`id_pago`, `id_cliente`, `id_usuario`, `tipo_pago`, `id_matricula_fk`, `id_venta_fk`, `id_asesoria_fk`, `metodo_pago`, `monto`, `fecha_hora`, `id_gimnasio`, `estado`) VALUES
(105, 1000, 10, 'matricula', 34, NULL, NULL, 'efectivo', 33, '2025-12-11 23:07:43', 1, 1),
(106, 1000, 10, 'matricula', 34, NULL, NULL, 'efectivo', 30, '2025-12-11 23:07:55', 1, 0),
(107, 1000, 10, 'matricula', 34, NULL, NULL, 'efectivo', 30, '2025-12-11 23:08:24', 1, 1),
(109, 1000, 10, 'asesoria', NULL, NULL, 12, 'efectivo', 250, '2025-12-11 23:25:35', 1, 1),
(110, 1000, 10, 'asesoria', NULL, NULL, 12, 'efectivo', 200, '2025-12-11 23:25:47', 1, 1),
(111, 1000, 10, 'asesoria', NULL, NULL, 12, 'efectivo', 10, '2025-12-11 23:25:59', 1, 0),
(112, 1008, 10, 'venta', NULL, 29, NULL, 'efectivo', 80, '2025-12-11 23:30:51', 1, 1),
(113, 1008, 10, 'venta', NULL, 29, NULL, 'efectivo', 23, '2025-12-11 23:31:08', 1, 0),
(114, 1009, 10, 'venta', NULL, 30, NULL, 'yape', 410, '2025-12-11 23:36:16', 1, 1),
(115, 1002, 10, 'matricula', 35, NULL, NULL, 'efectivo', 500, '2025-12-13 11:35:48', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_planes`
--

CREATE TABLE `tb_planes` (
  `id_plan` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `tipo_duracion` enum('relativa','fija') NOT NULL,
  `duracion_meses` int(11) DEFAULT NULL,
  `duracion_dias` int(11) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `fyh_creacion` datetime NOT NULL,
  `fyh_actualizacion` datetime NOT NULL,
  `id_gimnasio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_planes`
--

INSERT INTO `tb_planes` (`id_plan`, `nombre`, `precio`, `estado`, `tipo_duracion`, `duracion_meses`, `duracion_dias`, `fecha_inicio`, `fecha_fin`, `fyh_creacion`, `fyh_actualizacion`, `id_gimnasio`) VALUES
(1, 'Plan Básico 1 Mes', 30.00, 1, 'relativa', 1, 0, NULL, NULL, '2025-07-21 19:37:53', '2025-07-21 19:37:53', 1),
(2, 'Plan Anual Fijo', 300.00, 1, 'fija', NULL, NULL, '2025-01-01', '2025-12-31', '2025-07-21 19:37:53', '2025-07-21 19:37:53', 2),
(3, '2 meses x 100 soles', 100.00, 1, 'relativa', 2, 0, '0000-00-00', '0000-00-00', '2025-07-22 21:04:59', '0000-00-00 00:00:00', 1),
(4, 'entrena todo el 2025', 500.00, 1, 'fija', 0, 0, '0000-00-00', '2025-12-31', '2025-07-22 21:05:42', '0000-00-00 00:00:00', 1),
(6, '4 meses x 500', 500.00, 1, 'relativa', 4, 0, '0000-00-00', '0000-00-00', '2025-10-13 16:31:37', '0000-00-00 00:00:00', 2),
(7, '7 meses +10 dias x 350 soles', 350.00, 1, 'relativa', 7, 10, '0000-00-00', '0000-00-00', '2025-10-27 11:40:49', '0000-00-00 00:00:00', 2),
(8, 'entrena hasta enero 2026', 289.00, 1, 'fija', 0, 0, '0000-00-00', '2026-01-31', '2025-10-27 11:41:51', '2025-10-28 11:49:08', 2),
(12, 'entrena hasta 25 de noviembre de 2025', 45.00, 1, 'fija', NULL, NULL, NULL, '2025-11-25', '2025-10-28 12:43:39', '2025-10-28 12:48:43', 2),
(13, '1 mes + 8 dias', 30.00, 1, 'relativa', 1, 8, NULL, NULL, '2025-10-28 12:44:18', '2025-10-28 12:49:08', 2),
(14, 'PROMOCION ENTRENA TODO EL VERANO 2026', 200.00, 1, 'fija', NULL, NULL, NULL, '2026-04-30', '2025-11-30 16:25:28', '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_productos`
--

CREATE TABLE `tb_productos` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `foto` varchar(60) NOT NULL DEFAULT 'default_product.png',
  `fyh_creacion` datetime NOT NULL,
  `fyh_actualizacion` datetime NOT NULL,
  `id_gimnasio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_productos`
--

INSERT INTO `tb_productos` (`id_producto`, `nombre`, `descripcion`, `precio_venta`, `stock`, `foto`, `fyh_creacion`, `fyh_actualizacion`, `id_gimnasio`) VALUES
(10, 'Iso Whey 90', 'Proteina de suro de leche isolatada', 130.00, 42, '692c67f3a6d6b.webp', '2025-11-30 10:51:15', '2025-11-30 16:32:25', 1),
(11, 'Guantes gimnasio', 'Guantes para gimnasio entrenar pesado', 40.00, 92, '692c68266244a.webp', '2025-11-30 10:52:06', '2025-12-11 23:30:51', 1),
(12, 'Creatina Levrone 300gr', 'Creatina pare la masa muscular y fuerza', 70.00, 20, '692c686266264.webp', '2025-11-30 10:53:06', '2025-12-11 23:36:16', 1),
(13, 'Faja cuero marron oscuro', 'Faja para levantar peso en el gimnasio', 60.00, 15, '692c93c198c07.webp', '2025-11-30 13:58:09', '2025-12-11 23:36:16', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_roles`
--

CREATE TABLE `tb_roles` (
  `id_rol` int(11) NOT NULL,
  `rol` varchar(255) NOT NULL,
  `fyh_creacion` datetime NOT NULL,
  `fyh_actualizacion` datetime NOT NULL,
  `id_gimnasio` int(11) NOT NULL,
  `permisos` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tb_roles`
--

INSERT INTO `tb_roles` (`id_rol`, `rol`, `fyh_creacion`, `fyh_actualizacion`, `id_gimnasio`, `permisos`) VALUES
(1, 'ADMINISTRADOR', '2024-09-14 16:58:54', '2025-12-11 12:42:24', 1, '[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\",\"9\",\"10\",\"11\",\"12\",\"13\",\"14\",\"15\"]'),
(3, 'SUPERVISOR', '2024-09-14 10:39:29', '2024-09-14 12:37:41', 1, '[\"1\",\"3\",\"4\",\"5\",\"6\"]'),
(4, 'ASESOR DE VENTAS', '2024-09-14 12:34:02', '2025-12-11 12:42:17', 1, '[\"1\",\"4\",\"6\",\"7\",\"9\",\"10\",\"12\",\"13\"]'),
(5, 'ADMINISTRADOR', '2025-10-27 11:25:37', '2025-11-05 14:40:56', 2, '[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\",\"9\",\"10\",\"11\",\"12\"]'),
(7, 'RECEPCIONISTA', '2025-10-27 11:31:30', '2025-11-05 15:08:02', 2, '[\"1\",\"4\",\"5\",\"6\"]'),
(9, 'SECRETARIA', '2025-11-05 14:36:56', '2025-11-05 15:08:19', 2, '[\"1\",\"4\",\"5\",\"6\"]');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_usuarios`
--

CREATE TABLE `tb_usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombres` varchar(255) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password_user` text NOT NULL,
  `token` varchar(100) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `fyh_creacion` datetime NOT NULL,
  `fyh_actualizacion` datetime NOT NULL,
  `foto` varchar(50) NOT NULL DEFAULT 'default_image.jpg',
  `id_gimnasio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tb_usuarios`
--

INSERT INTO `tb_usuarios` (`id_usuario`, `nombres`, `email`, `password_user`, `token`, `id_rol`, `fyh_creacion`, `fyh_actualizacion`, `foto`, `id_gimnasio`) VALUES
(4, 'Jose Quenta', 'jose@gmail.com', '$2y$10$EyBkG4mp6YEijplZ.TLnlOFODKeauk9lELGiYvnchpZZ.o8BnXQfm', '', 4, '2024-09-14 17:07:48', '2025-12-11 12:29:38', '', 1),
(5, 'Yesica Medrano', 'yesica@gmail.com', '$2y$10$.zm2VnVV7GtXMoo3AtfKgu3H84zudH5J0RWazHdTk5GssXk3gS2k2', '', 4, '2024-09-14 13:21:54', '0000-00-00 00:00:00', 'default_image.jpg', 1),
(9, 'Marcos Suarez', 'david_m209@gmail.com', '$2y$10$kqbJe.cEtqkznCZ/oFBTfeg/GbXQy/h7SexV4qN1iRNmuBf1E5cta', '', 1, '2024-09-14 14:34:33', '2025-07-19 00:26:34', 'default_image.jpg', 1),
(10, 'Alex Abensur', 'aabensurc@gmail.com', '$2y$10$jtNHUgGn7qGD6F/EhXQZUeuYlhrRzrTk6l99SwxLsKlFQU/7mlNfy', '', 1, '2024-09-14 15:11:00', '2025-07-19 00:54:42', 'perfil_alex_abensur.jpg', 1),
(12, 'Christian Franco', 'christianf@fithgym.com', '$2y$10$jtNHUgGn7qGD6F/EhXQZUeuYlhrRzrTk6l99SwxLsKlFQU/7mlNfy', '', 5, '2025-10-26 21:05:52', '2025-10-27 11:51:50', 'christianf.jpg', 2),
(15, 'Sindy Gym', 'sindy123@gmail.com', '$2y$10$u4zyXg/8X0f8usCRKS1mL.hyRMjnRcquEOEJ5BQzVx5cTWDNIKQWi', '', 7, '2025-10-26 21:37:37', '2025-11-05 15:09:14', '', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_ventas`
--

CREATE TABLE `tb_ventas` (
  `id_venta` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `fecha_venta` datetime NOT NULL,
  `monto_total` decimal(10,2) NOT NULL,
  `descuento_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `fyh_creacion` datetime NOT NULL,
  `fyh_actualizacion` datetime NOT NULL,
  `id_gimnasio` int(11) NOT NULL,
  `estado` int(1) DEFAULT 1 COMMENT '1=Activo, 0=Anulado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_ventas`
--

INSERT INTO `tb_ventas` (`id_venta`, `id_cliente`, `fecha_venta`, `monto_total`, `descuento_total`, `fyh_creacion`, `fyh_actualizacion`, `id_gimnasio`, `estado`) VALUES
(29, 1008, '2025-12-11 23:30:51', 240.00, 0.00, '2025-12-11 23:30:51', '2025-12-11 23:30:51', 1, 1),
(30, 1009, '2025-12-11 23:36:16', 410.00, 0.00, '2025-12-11 23:36:16', '2025-12-11 23:36:16', 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tb_asesorias`
--
ALTER TABLE `tb_asesorias`
  ADD PRIMARY KEY (`id_asesoria`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_entrenador` (`id_entrenador`),
  ADD KEY `fk_asesoria_gimnasio` (`id_gimnasio`);

--
-- Indices de la tabla `tb_asistencias_clientes`
--
ALTER TABLE `tb_asistencias_clientes`
  ADD PRIMARY KEY (`id_asistencia`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `fk_asistencia_gimnasio` (`id_gimnasio`);

--
-- Indices de la tabla `tb_caja`
--
ALTER TABLE `tb_caja`
  ADD PRIMARY KEY (`id_caja`);

--
-- Indices de la tabla `tb_clientes`
--
ALTER TABLE `tb_clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD KEY `fk_cliente_gimnasio` (`id_gimnasio`);

--
-- Indices de la tabla `tb_congelamientos`
--
ALTER TABLE `tb_congelamientos`
  ADD PRIMARY KEY (`id_congelamiento`),
  ADD KEY `id_matricula` (`id_matricula`);

--
-- Indices de la tabla `tb_cronograma_pagos`
--
ALTER TABLE `tb_cronograma_pagos`
  ADD PRIMARY KEY (`id_cronograma`),
  ADD KEY `id_matricula_fk` (`id_matricula_fk`),
  ADD KEY `id_venta_fk` (`id_venta_fk`),
  ADD KEY `fk_cronograma_gimnasio` (`id_gimnasio`);

--
-- Indices de la tabla `tb_detalle_ventas`
--
ALTER TABLE `tb_detalle_ventas`
  ADD PRIMARY KEY (`id_detalle_venta`),
  ADD KEY `id_venta` (`id_venta`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `tb_entrenadores`
--
ALTER TABLE `tb_entrenadores`
  ADD PRIMARY KEY (`id_entrenador`),
  ADD KEY `fk_entrenador_gimnasio` (`id_gimnasio`);

--
-- Indices de la tabla `tb_gimnasios`
--
ALTER TABLE `tb_gimnasios`
  ADD PRIMARY KEY (`id_gimnasio`);

--
-- Indices de la tabla `tb_matriculas`
--
ALTER TABLE `tb_matriculas`
  ADD PRIMARY KEY (`id_matricula`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_plan` (`id_plan`),
  ADD KEY `fk_matricula_gimnasio` (`id_gimnasio`);

--
-- Indices de la tabla `tb_pagos`
--
ALTER TABLE `tb_pagos`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_matricula_fk` (`id_matricula_fk`),
  ADD KEY `id_venta_fk` (`id_venta_fk`),
  ADD KEY `id_asesoria_fk` (`id_asesoria_fk`),
  ADD KEY `fk_pago_gimnasio` (`id_gimnasio`);

--
-- Indices de la tabla `tb_planes`
--
ALTER TABLE `tb_planes`
  ADD PRIMARY KEY (`id_plan`),
  ADD KEY `fk_plan_gimnasio` (`id_gimnasio`);

--
-- Indices de la tabla `tb_productos`
--
ALTER TABLE `tb_productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `fk_producto_gimnasio` (`id_gimnasio`);

--
-- Indices de la tabla `tb_roles`
--
ALTER TABLE `tb_roles`
  ADD PRIMARY KEY (`id_rol`),
  ADD KEY `fk_rol_gimnasio` (`id_gimnasio`);

--
-- Indices de la tabla `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `fk_usuario_gimnasio` (`id_gimnasio`);

--
-- Indices de la tabla `tb_ventas`
--
ALTER TABLE `tb_ventas`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `fk_venta_gimnasio` (`id_gimnasio`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tb_asesorias`
--
ALTER TABLE `tb_asesorias`
  MODIFY `id_asesoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `tb_asistencias_clientes`
--
ALTER TABLE `tb_asistencias_clientes`
  MODIFY `id_asistencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `tb_caja`
--
ALTER TABLE `tb_caja`
  MODIFY `id_caja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tb_clientes`
--
ALTER TABLE `tb_clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1013;

--
-- AUTO_INCREMENT de la tabla `tb_congelamientos`
--
ALTER TABLE `tb_congelamientos`
  MODIFY `id_congelamiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tb_cronograma_pagos`
--
ALTER TABLE `tb_cronograma_pagos`
  MODIFY `id_cronograma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `tb_detalle_ventas`
--
ALTER TABLE `tb_detalle_ventas`
  MODIFY `id_detalle_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `tb_entrenadores`
--
ALTER TABLE `tb_entrenadores`
  MODIFY `id_entrenador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tb_gimnasios`
--
ALTER TABLE `tb_gimnasios`
  MODIFY `id_gimnasio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tb_matriculas`
--
ALTER TABLE `tb_matriculas`
  MODIFY `id_matricula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `tb_pagos`
--
ALTER TABLE `tb_pagos`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT de la tabla `tb_planes`
--
ALTER TABLE `tb_planes`
  MODIFY `id_plan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `tb_productos`
--
ALTER TABLE `tb_productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `tb_roles`
--
ALTER TABLE `tb_roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `tb_ventas`
--
ALTER TABLE `tb_ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tb_asesorias`
--
ALTER TABLE `tb_asesorias`
  ADD CONSTRAINT `fk_asesoria_gimnasio` FOREIGN KEY (`id_gimnasio`) REFERENCES `tb_gimnasios` (`id_gimnasio`),
  ADD CONSTRAINT `tb_asesorias_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `tb_clientes` (`id_cliente`) ON DELETE SET NULL,
  ADD CONSTRAINT `tb_asesorias_ibfk_2` FOREIGN KEY (`id_entrenador`) REFERENCES `tb_entrenadores` (`id_entrenador`) ON DELETE SET NULL;

--
-- Filtros para la tabla `tb_asistencias_clientes`
--
ALTER TABLE `tb_asistencias_clientes`
  ADD CONSTRAINT `fk_asistencia_gimnasio` FOREIGN KEY (`id_gimnasio`) REFERENCES `tb_gimnasios` (`id_gimnasio`),
  ADD CONSTRAINT `tb_asistencias_clientes_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `tb_clientes` (`id_cliente`) ON DELETE SET NULL;

--
-- Filtros para la tabla `tb_clientes`
--
ALTER TABLE `tb_clientes`
  ADD CONSTRAINT `fk_cliente_gimnasio` FOREIGN KEY (`id_gimnasio`) REFERENCES `tb_gimnasios` (`id_gimnasio`);

--
-- Filtros para la tabla `tb_congelamientos`
--
ALTER TABLE `tb_congelamientos`
  ADD CONSTRAINT `fk_congelamiento_matricula` FOREIGN KEY (`id_matricula`) REFERENCES `tb_matriculas` (`id_matricula`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tb_cronograma_pagos`
--
ALTER TABLE `tb_cronograma_pagos`
  ADD CONSTRAINT `fk_cronograma_gimnasio` FOREIGN KEY (`id_gimnasio`) REFERENCES `tb_gimnasios` (`id_gimnasio`),
  ADD CONSTRAINT `tb_cronograma_pagos_ibfk_1` FOREIGN KEY (`id_matricula_fk`) REFERENCES `tb_matriculas` (`id_matricula`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tb_detalle_ventas`
--
ALTER TABLE `tb_detalle_ventas`
  ADD CONSTRAINT `tb_detalle_ventas_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `tb_ventas` (`id_venta`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_detalle_ventas_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `tb_productos` (`id_producto`) ON DELETE SET NULL;

--
-- Filtros para la tabla `tb_entrenadores`
--
ALTER TABLE `tb_entrenadores`
  ADD CONSTRAINT `fk_entrenador_gimnasio` FOREIGN KEY (`id_gimnasio`) REFERENCES `tb_gimnasios` (`id_gimnasio`);

--
-- Filtros para la tabla `tb_matriculas`
--
ALTER TABLE `tb_matriculas`
  ADD CONSTRAINT `fk_matricula_gimnasio` FOREIGN KEY (`id_gimnasio`) REFERENCES `tb_gimnasios` (`id_gimnasio`),
  ADD CONSTRAINT `tb_matriculas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `tb_clientes` (`id_cliente`) ON DELETE SET NULL,
  ADD CONSTRAINT `tb_matriculas_ibfk_2` FOREIGN KEY (`id_plan`) REFERENCES `tb_planes` (`id_plan`) ON DELETE SET NULL;

--
-- Filtros para la tabla `tb_pagos`
--
ALTER TABLE `tb_pagos`
  ADD CONSTRAINT `fk_pago_gimnasio` FOREIGN KEY (`id_gimnasio`) REFERENCES `tb_gimnasios` (`id_gimnasio`),
  ADD CONSTRAINT `tb_pagos_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `tb_clientes` (`id_cliente`) ON DELETE SET NULL,
  ADD CONSTRAINT `tb_pagos_ibfk_2` FOREIGN KEY (`id_matricula_fk`) REFERENCES `tb_matriculas` (`id_matricula`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_pagos_ibfk_3` FOREIGN KEY (`id_venta_fk`) REFERENCES `tb_ventas` (`id_venta`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_pagos_ibfk_4` FOREIGN KEY (`id_asesoria_fk`) REFERENCES `tb_asesorias` (`id_asesoria`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tb_planes`
--
ALTER TABLE `tb_planes`
  ADD CONSTRAINT `fk_plan_gimnasio` FOREIGN KEY (`id_gimnasio`) REFERENCES `tb_gimnasios` (`id_gimnasio`);

--
-- Filtros para la tabla `tb_productos`
--
ALTER TABLE `tb_productos`
  ADD CONSTRAINT `fk_producto_gimnasio` FOREIGN KEY (`id_gimnasio`) REFERENCES `tb_gimnasios` (`id_gimnasio`);

--
-- Filtros para la tabla `tb_roles`
--
ALTER TABLE `tb_roles`
  ADD CONSTRAINT `fk_rol_gimnasio` FOREIGN KEY (`id_gimnasio`) REFERENCES `tb_gimnasios` (`id_gimnasio`);

--
-- Filtros para la tabla `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  ADD CONSTRAINT `fk_usuario_gimnasio` FOREIGN KEY (`id_gimnasio`) REFERENCES `tb_gimnasios` (`id_gimnasio`),
  ADD CONSTRAINT `tb_usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `tb_roles` (`id_rol`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `tb_ventas`
--
ALTER TABLE `tb_ventas`
  ADD CONSTRAINT `fk_venta_gimnasio` FOREIGN KEY (`id_gimnasio`) REFERENCES `tb_gimnasios` (`id_gimnasio`),
  ADD CONSTRAINT `tb_ventas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `tb_clientes` (`id_cliente`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
