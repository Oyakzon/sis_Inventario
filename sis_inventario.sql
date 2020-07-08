-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 08-07-2020 a las 01:50:04
-- Versión del servidor: 8.0.20
-- Versión de PHP: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sis_inventario`
--
CREATE DATABASE IF NOT EXISTS `sis_inventario` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `sis_inventario`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulo`
--

DROP TABLE IF EXISTS `articulo`;
CREATE TABLE `articulo` (
  `idarticulo` int NOT NULL,
  `idcategoria` int NOT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `stock` int NOT NULL,
  `descripcion` varchar(512) DEFAULT NULL,
  `imagen` varchar(50) DEFAULT NULL,
  `estado` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `articulo`
--

INSERT INTO `articulo` (`idarticulo`, `idcategoria`, `codigo`, `nombre`, `stock`, `descripcion`, `imagen`, `estado`) VALUES
(5, 1, '21231233', 'Fechadores-Numeradores', 51, 'Impresión instantánea', 'db9c9e2e1f7c796f1d857257b23ab0a8.png', 'Inactivo'),
(7, 1, '125254531', 'Cubiletes', 210, 'Portalapiz', '540.png', 'Activo'),
(8, 1, '1234567', 'Abrecartas', 1338, 'Abrir cartas de forma sencilla', 'unnamed.jpg', 'Activo'),
(9, 2, '4216512', 'Blocs de notas y cartas', 566, 'Variedad de blocs de notas y cartas', 'blocs-notas.jpg', 'Activo'),
(10, 2, '126377', 'Libretas espiral', 804, 'Libretas de tipo espiral variedad', 'rite-673.png', 'Activo'),
(11, 4, '123123', 'Calculadoras y accesorios', 44, 'Calculadoras y sus accesorios', 'product-nspire-cx-cas-hero.png', 'Activo'),
(12, 4, '12365216323', 'Destructor de documentos', 275, 'Para destruir archivos basura', 'Destructora_de_papel_Despacho.png', 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

DROP TABLE IF EXISTS `categoria`;
CREATE TABLE `categoria` (
  `idcategoria` int NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(256) DEFAULT NULL,
  `condicion` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`idcategoria`, `nombre`, `descripcion`, `condicion`) VALUES
(1, 'Sobre-mesa', 'Artículos para sobremesa', 1),
(2, 'Manipulados de Papel', 'Objetos relacionados al papel', 1),
(3, 'Utensilios', 'Articulos tales como tijeras, lapices, reglas', 1),
(4, 'Maquinas de oficina', 'Objetos electrónicos', 1),
(5, 'Oficina', 'Objetos comunes de oficina', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ingreso`
--

DROP TABLE IF EXISTS `detalle_ingreso`;
CREATE TABLE `detalle_ingreso` (
  `iddetalle_ingreso` int NOT NULL,
  `idingreso` int NOT NULL,
  `idarticulo` int NOT NULL,
  `cantidad` int NOT NULL,
  `precio_compra` decimal(11,2) NOT NULL,
  `precio_venta` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `detalle_ingreso`
--

INSERT INTO `detalle_ingreso` (`iddetalle_ingreso`, `idingreso`, `idarticulo`, `cantidad`, `precio_compra`, `precio_venta`) VALUES
(21, 20, 8, 400, '5000.00', '7000.00'),
(22, 20, 12, 100, '2000.00', '5000.00'),
(24, 22, 9, 122, '5000.00', '9000.00'),
(27, 24, 8, 12, '10000.00', '30000.00'),
(28, 24, 9, 10, '1000.00', '20000.00'),
(29, 25, 7, 12, '1111.00', '1111.00'),
(30, 26, 8, 100, '10000.00', '20000.00'),
(31, 26, 10, 102, '1000.00', '20000.00'),
(32, 27, 8, 12, '12.00', '12.00'),
(33, 28, 8, 100, '2000.00', '5000.00'),
(34, 28, 12, 30, '3000.00', '5000.00'),
(35, 29, 11, 2, '2.00', '2.00'),
(36, 29, 12, 3, '12.00', '33.00');

--
-- Disparadores `detalle_ingreso`
--
DROP TRIGGER IF EXISTS `tr_updStockIngreso`;
DELIMITER $$
CREATE TRIGGER `tr_updStockIngreso` AFTER INSERT ON `detalle_ingreso` FOR EACH ROW BEGIN
	UPDATE articulo SET stock = stock + NEW.cantidad
    WHERE articulo.idarticulo = NEW.idarticulo;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

DROP TABLE IF EXISTS `detalle_venta`;
CREATE TABLE `detalle_venta` (
  `iddetalle_venta` int NOT NULL,
  `idventa` int NOT NULL,
  `idarticulo` int NOT NULL,
  `cantidad` int NOT NULL,
  `precio_venta` decimal(11,2) NOT NULL,
  `descuento` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `detalle_venta`
--

INSERT INTO `detalle_venta` (`iddetalle_venta`, `idventa`, `idarticulo`, `cantidad`, `precio_venta`, `descuento`) VALUES
(1, 5, 8, 2, '300.00', '0.00'),
(2, 6, 7, 12, '3006.00', '1.00'),
(3, 7, 8, 40, '1559.00', '0.00'),
(4, 7, 10, 12, '3500.00', '0.00'),
(5, 8, 8, 122, '1559.00', '0.00'),
(6, 8, 10, 100, '3500.00', '0.00'),
(7, 9, 12, 12, '300000.00', '0.00'),
(8, 10, 11, 100, '4500.00', '0.00'),
(9, 10, 10, 12, '3500.00', '0.00'),
(10, 11, 9, 100, '9000.00', '0.00'),
(11, 11, 12, 20, '2502500.00', '0.00'),
(12, 19, 8, 12, '12402.40', '1.00'),
(13, 20, 7, 1, '1111.00', '1.00');

--
-- Disparadores `detalle_venta`
--
DROP TRIGGER IF EXISTS `tr_updStockVenta`;
DELIMITER $$
CREATE TRIGGER `tr_updStockVenta` AFTER INSERT ON `detalle_venta` FOR EACH ROW BEGIN
UPDATE articulo SET stock = stock -
NEW.cantidad
WHERE articulo.idarticulo = NEW.idarticulo;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingreso`
--

DROP TABLE IF EXISTS `ingreso`;
CREATE TABLE `ingreso` (
  `idingreso` int NOT NULL,
  `idproveedor` int NOT NULL,
  `tipo_comprobante` varchar(20) NOT NULL,
  `serie_comprobante` varchar(7) DEFAULT NULL,
  `num_comprobante` varchar(10) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `impuesto` decimal(4,2) NOT NULL,
  `estado` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `ingreso`
--

INSERT INTO `ingreso` (`idingreso`, `idproveedor`, `tipo_comprobante`, `serie_comprobante`, `num_comprobante`, `fecha_hora`, `impuesto`, `estado`) VALUES
(20, 6, 'Factura', '1', '1', '2020-04-26 21:23:55', '0.00', 'Aprobado'),
(21, 7, 'Factura', '2', '2', '2020-03-26 21:33:16', '0.00', 'Aprobado'),
(22, 8, 'Boleta', '3', '3', '2020-06-27 00:19:01', '0.00', 'Aprobado'),
(24, 4, 'Factura', '3211', '321', '2020-07-02 22:46:14', '0.00', 'Aprobado'),
(25, 6, 'Boleta', '123123', '12321', '2020-07-02 23:11:50', '0.00', 'Aprobado'),
(26, 13, 'Ticket', '123456', '645321', '2020-07-02 23:22:36', '0.00', 'Aprobado'),
(27, 7, 'Factura', '1232', '123212', '2020-07-03 00:15:41', '19.00', 'Aprobado'),
(28, 13, 'Factura', '123456', '65423', '2020-07-03 00:31:13', '19.00', 'Anulado'),
(29, 5, 'Ticket', '12322', '12344', '2020-07-04 23:45:48', '19.00', 'Anulado'),
(30, 5, 'Factura', '123', '123', '2020-07-05 00:48:34', '19.00', 'Aprobado');

--
-- Disparadores `ingreso`
--
DROP TRIGGER IF EXISTS `tr_updStockAnularCompra`;
DELIMITER $$
CREATE TRIGGER `tr_updStockAnularCompra` AFTER UPDATE ON `ingreso` FOR EACH ROW update articulo
    join detalle_ingreso
      on detalle_ingreso.idarticulo = articulo.idarticulo
     and detalle_ingreso.idingreso = new.idingreso
     set articulo.stock = articulo.stock + detalle_ingreso.cantidad
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `migration` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_10_12_000000_create_users_table', 1),
('2014_10_12_100000_create_password_resets_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('damianguerra@gmail.com', '606b18e9dbbb4638536cd8aad91faf98df1ec692d790037afdca62e580255c46', '2020-07-02 21:52:30'),
('fran@gmail.com', '46ff490f288566ed84ff9697d4f5bc7a558924c86afbfce36a6a7b7364b8577a', '2020-07-02 21:53:40'),
('marcos_oyarzo97@outlook.com', '35e4370539c72b9bf3e37bfd73b1d7ec71b1fc8bb3f0c4253994466b03cba43a', '2020-07-02 22:13:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perdida`
--

DROP TABLE IF EXISTS `perdida`;
CREATE TABLE `perdida` (
  `idperdida` int NOT NULL,
  `idarticulo` int NOT NULL,
  `stock` int NOT NULL,
  `descripcion` varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `imagen` varchar(50) DEFAULT NULL,
  `fecha_hora` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `perdida`
--

INSERT INTO `perdida` (`idperdida`, `idarticulo`, `stock`, `descripcion`, `imagen`, `fecha_hora`) VALUES
(11, 5, 20, 'Extraviado', 'PicsArt_12-30-02.59.40.jpg', '2020-07-04 16:27:29'),
(15, 7, 2, 'Dañado', NULL, '2020-07-04 23:18:22'),
(17, 9, 20, 'Extraviado', NULL, '2020-07-05 01:00:39'),
(18, 12, 1, 'Dañado', NULL, '2020-07-05 15:32:52');

--
-- Disparadores `perdida`
--
DROP TRIGGER IF EXISTS `tr_updStockPerdida`;
DELIMITER $$
CREATE TRIGGER `tr_updStockPerdida` AFTER INSERT ON `perdida` FOR EACH ROW BEGIN
     UPDATE articulo SET stock = stock - NEW.stock
        WHERE articulo.idarticulo =NEW.idarticulo;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

DROP TABLE IF EXISTS `persona`;
CREATE TABLE `persona` (
  `idpersona` int NOT NULL,
  `tipo_persona` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo_documento` varchar(20) DEFAULT NULL,
  `num_documento` varchar(15) DEFAULT NULL,
  `direccion` varchar(70) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`idpersona`, `tipo_persona`, `nombre`, `tipo_documento`, `num_documento`, `direccion`, `telefono`, `email`) VALUES
(1, 'Cliente', 'Sebastian Acosta', 'DNI', '123123123123', '11 de septiembre #2554', '962969091', 'sebastian.ipchile@gmail.com'),
(3, 'Cliente', 'Marcos Oyarzo', 'RUC', '12635123576', 'Puente Alto #666', '812938921', 'oyarzo@gmail.com'),
(4, 'Proveedor', 'Jose pinto', 'RUC', '1728129812', 'Los Lagos #3322', '962999721', 'JosePinto@gmail.com'),
(5, 'Proveedor', 'Juan Costa', 'RUC', '1251624561', 'Libertador #2254', '962329091', 'juan.costaipchile@gmail.com'),
(6, 'Proveedor', 'Carla Acosta', 'RUC', '21123123', '12 de septiembre #2534', '962969093', 'CarlaAcosta@gmail.com'),
(7, 'Proveedor', 'Cecilia Alvarez', 'RUC', '44524553', 'Bellavista #3443', '962364091', 'Cecilia.Alvarez@gmail.com'),
(8, 'Proveedor', 'Jordan Castillo', 'RUC', '1245345', 'La torre #2333', '962999722', 'JordanCastillo@gmail.com'),
(9, 'Inactivo', 'Prueba Prueba', 'RUC', '1263566532', 'Prueba #2554', '962999721', 'PruebaPrueba@gmail.com'),
(10, 'Inactivo', 'prueba', 'RUC', '1231231', 'Los Lagos #3322', '962999721', 'prueba@gmail.com'),
(11, 'Inactivo', 'Jose pinto', 'RUC', '123213213', 'Los Lagos #3322', '962999722', 'JosePinto@gmail.com'),
(12, 'Inactivo', 'ipchile prueba 2', 'RUC', '123432', 'Los magos #12322', '962999721', 'prueba@gmail.com'),
(13, 'Inactivo', 'Prueba', 'RUC', '1234567', 'Los magos #12322', '962999721', 'prueba@gmail.com'),
(14, 'Inactivo', 'PedroTorres', 'DNI', '12233212', 'Bellavista #3443', '56962364091', 'PedroTorres@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `role`, `email`, `password`, `phone`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Francisco Guerrero', 'Operador', 'fran@gmail.com', '$2y$10$FNNXPnJnSjqlmDwxOECUIu2pw2h7N.jIMr/D1vwu/2l7ED3X2mN3m', '56932459091', '0fOXQRTIYeky4vNrWwVPz6yhDiPOOxeyDSKa103LxWbGtFMyzoJzVzB8Dms8', '2020-06-20 00:08:38', '2020-07-01 23:04:12'),
(2, 'Sebastian Acosta', 'Administrador', 'sebastian.ipchile@gmail.com', '$2y$10$wjpco2s.2sMrWQMleJmA2O7.H0uiwNEJpytoowp06Z8kh6FcFzkzW', '56962768099', 'NieUS5010ebfIrLv8bbV0BzkqqLV7VYo7BgXaJEeQWgke4ApbA7DEVv2qpdo', '2020-06-27 00:33:27', '2020-07-06 19:01:51'),
(4, 'Marcos Oyarzo', 'Administrador', 'marcos_oyarzo97@hotmail.com', '$2y$10$/W78HQURy0Ca8CJ0HNn3uuhpdC940NDwz3jdUX5l750txKfuMsOcy', '56962469094', 'Px0sRyKaAaTHzCSU4h4BTtqQXIVJHbNop0DmZhndg1pqYRB8y5Cz5cGgaghv', '2020-07-01 22:56:00', '2020-07-04 18:19:34'),
(6, 'Jose Costa', 'Gerente', 'Jose@gmail.com', '$2y$10$INro9q0nQ/RZ6g.2U2f0ie5VdSM9beAzgXHPUYREhrdUTlkYIb91i', '56962969091', 'xoQUJujdYHRy0BKe5rB7NlTQ5cc405UyE178wj2E4M57rU5wRSFc79b7ucfD', '2020-07-05 03:34:45', '2020-07-07 23:14:31'),
(13, 'Sebastian Acosta', 'Gerente', 'Luis1sBriceno@gmail.com', '$2y$10$.0F/lgMAGgzQFSQs4GUSUeapidCqrLdWmhnND69FY6b9zLDAFgtBm', '962969091', NULL, '2020-07-08 00:23:29', '2020-07-08 00:23:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

DROP TABLE IF EXISTS `venta`;
CREATE TABLE `venta` (
  `idventa` int NOT NULL,
  `idcliente` int NOT NULL,
  `tipo_comprobante` varchar(20) NOT NULL,
  `serie_comprobante` varchar(7) NOT NULL,
  `num_comprobante` varchar(10) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `impuesto` decimal(4,2) NOT NULL,
  `total_venta` decimal(11,2) NOT NULL,
  `estado` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`idventa`, `idcliente`, `tipo_comprobante`, `serie_comprobante`, `num_comprobante`, `fecha_hora`, `impuesto`, `total_venta`, `estado`) VALUES
(5, 1, 'Boleta', '222', '222', '2020-06-21 01:58:21', '19.00', '600.00', 'Aprobado'),
(6, 1, 'Factura', '1232132', '12321312', '2020-06-26 15:52:52', '18.00', '36071.00', 'Aprobado'),
(7, 3, 'Factura', '123123', '213213', '2020-06-26 16:57:32', '19.00', '104360.00', 'Aprobado'),
(8, 1, 'Boleta', '1234567', '1223231', '2020-06-26 19:11:35', '19.00', '540198.00', 'Aprobado'),
(9, 3, 'Factura', '1234567', '2132133', '2020-06-26 19:41:53', '19.00', '3600000.00', 'Aprobado'),
(10, 1, 'Boleta', '1231', '1231', '2020-06-26 20:21:21', '19.00', '352500.00', 'Aprobado'),
(11, 3, 'Factura', '4', '4', '2020-06-27 00:19:48', '19.00', '50950000.00', 'Aprobado'),
(12, 1, 'Boleta', '2252', '2252', '2020-05-21 01:58:21', '19.00', '600.00', 'Aprobado'),
(13, 1, 'Factura', '153132', '12132', '2020-04-26 15:52:52', '18.00', '36071.00', 'Aprobado'),
(14, 3, 'Factura', '123523', '23513', '2020-03-26 16:57:32', '19.00', '104360.00', 'Aprobado'),
(15, 1, 'Boleta', '123467', '122321', '2020-02-26 19:11:35', '19.00', '540198.00', 'Aprobado'),
(16, 3, 'Factura', '123457', '213213', '2020-01-26 19:41:53', '19.00', '3600000.00', 'Aprobado'),
(17, 1, 'Boleta', '1531', '1531', '2020-01-26 20:21:21', '19.00', '352500.00', 'Aprobado'),
(18, 3, 'Factura', '425', '425', '2020-01-27 00:19:48', '19.00', '50950000.00', 'Aprobado'),
(19, 3, 'Factura', '1212', '1212', '2020-07-03 10:39:43', '19.00', '148827.80', 'Aprobado'),
(20, 1, 'Factura', '1', '2', '2020-07-03 10:47:08', '19.00', '1110.00', 'Aprobado');

--
-- Disparadores `venta`
--
DROP TRIGGER IF EXISTS `tr_updStockAnularVenta`;
DELIMITER $$
CREATE TRIGGER `tr_updStockAnularVenta` AFTER UPDATE ON `venta` FOR EACH ROW update articulo
    join detalle_venta
      on detalle_venta.idarticulo = articulo.idarticulo
     and detalle_venta.idVenta = new.idVenta
     set articulo.stock = articulo.stock + detalle_venta.cantidad
$$
DELIMITER ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `articulo`
--
ALTER TABLE `articulo`
  ADD PRIMARY KEY (`idarticulo`),
  ADD KEY `fk_articulo_categoria_idx` (`idcategoria`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`idcategoria`);

--
-- Indices de la tabla `detalle_ingreso`
--
ALTER TABLE `detalle_ingreso`
  ADD PRIMARY KEY (`iddetalle_ingreso`),
  ADD KEY `fk_detalle_ingreso_idx` (`idingreso`),
  ADD KEY `fk_detalle_ingreso_articulo_idx` (`idarticulo`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`iddetalle_venta`),
  ADD KEY `fk_detalle_venta_articulo_idx` (`idarticulo`),
  ADD KEY `fk_detalle_venta_idx` (`idventa`);

--
-- Indices de la tabla `ingreso`
--
ALTER TABLE `ingreso`
  ADD PRIMARY KEY (`idingreso`),
  ADD KEY `fk_ingreso_persona_idx` (`idproveedor`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Indices de la tabla `perdida`
--
ALTER TABLE `perdida`
  ADD PRIMARY KEY (`idperdida`),
  ADD KEY `idarticulo` (`idarticulo`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`idpersona`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`idventa`),
  ADD KEY `fk_venta_cliente_idx` (`idcliente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `articulo`
--
ALTER TABLE `articulo`
  MODIFY `idarticulo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `idcategoria` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `detalle_ingreso`
--
ALTER TABLE `detalle_ingreso`
  MODIFY `iddetalle_ingreso` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `iddetalle_venta` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `ingreso`
--
ALTER TABLE `ingreso`
  MODIFY `idingreso` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `perdida`
--
ALTER TABLE `perdida`
  MODIFY `idperdida` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `idpersona` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `idventa` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `articulo`
--
ALTER TABLE `articulo`
  ADD CONSTRAINT `fk_articulo_categoria` FOREIGN KEY (`idcategoria`) REFERENCES `categoria` (`idcategoria`);

--
-- Filtros para la tabla `detalle_ingreso`
--
ALTER TABLE `detalle_ingreso`
  ADD CONSTRAINT `fk_detalle_ingreso` FOREIGN KEY (`idingreso`) REFERENCES `ingreso` (`idingreso`),
  ADD CONSTRAINT `fk_detalle_ingreso_articulo` FOREIGN KEY (`idarticulo`) REFERENCES `articulo` (`idarticulo`);

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `fk_detalle_venta` FOREIGN KEY (`idventa`) REFERENCES `venta` (`idventa`),
  ADD CONSTRAINT `fk_detalle_venta_articulo` FOREIGN KEY (`idarticulo`) REFERENCES `articulo` (`idarticulo`);

--
-- Filtros para la tabla `ingreso`
--
ALTER TABLE `ingreso`
  ADD CONSTRAINT `fk_ingreso_persona` FOREIGN KEY (`idproveedor`) REFERENCES `persona` (`idpersona`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `perdida`
--
ALTER TABLE `perdida`
  ADD CONSTRAINT `perdida_ibfk_1` FOREIGN KEY (`idarticulo`) REFERENCES `articulo` (`idarticulo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `fk_venta_cliente` FOREIGN KEY (`idcliente`) REFERENCES `persona` (`idpersona`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
