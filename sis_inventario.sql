-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 11-07-2020 a las 16:58:21
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
  `idproveedor` int NOT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `stock` int NOT NULL,
  `descripcion` varchar(512) DEFAULT NULL,
  `imagen` varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `estado` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `articulo`
--

INSERT INTO `articulo` (`idarticulo`, `idcategoria`, `idproveedor`, `codigo`, `nombre`, `stock`, `descripcion`, `imagen`, `estado`) VALUES
(1, 3, 1, '123456', 'Abrecartas', 40, 'Abrir cartas de forma sencilla', 'unnamed.jpg', 'Activo'),
(2, 2, 16, '123213', 'Bloc de notas', 209, 'Para escribir', 'blocs-notas.jpg', 'Activo'),
(3, 4, 1, '1234567', 'Destructor de archivos', 206, 'Un destructor de archivos', 'Destructora_de_papel_Despacho.png', 'Activo'),
(4, 1, 1, '12321222', 'Calculadora', 312, 'Calcular', 'product-nspire-cx-cas-hero.png', 'Activo'),
(5, 1, 1, '12321322', 'Fechadores-Numeradores', 12, 'Para timbres', 'db9c9e2e1f7c796f1d857257b23ab0a8.png', 'Activo'),
(6, 1, 16, '213123', 'Porta Lapices', 234, 'Almacenar lapices', 'pngtree-black-cylindrical-pen-element-png-image_4407790.jpg', 'Activo');

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
(1, 1, 3, 123, '12321.00', '123212.00'),
(39, 32, 2, 2, '1000.00', '2000.00'),
(40, 33, 3, 1, '200000.00', '300000.00'),
(41, 34, 6, 100, '1000.00', '2000.00'),
(42, 35, 6, 122, '1000.00', '2000.00'),
(43, 36, 4, 300, '1000.00', '2000.00'),
(44, 36, 2, 100, '2000.00', '5000.00');

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
(1, 1, 2, 123, '123123.00', '0.00'),
(14, 21, 3, 12, '123212.00', '0.00'),
(15, 22, 3, 12, '123212.00', '12.00'),
(16, 23, 3, 12, '123212.00', '12.00'),
(17, 24, 3, 1, '123212.00', '0.00'),
(18, 25, 3, 2, '123212.00', '0.00'),
(19, 26, 3, 12, '211606.00', '0.00');

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
  `idresponsable` int NOT NULL,
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

INSERT INTO `ingreso` (`idingreso`, `idproveedor`, `idresponsable`, `tipo_comprobante`, `serie_comprobante`, `num_comprobante`, `fecha_hora`, `impuesto`, `estado`) VALUES
(1, 1, 1, 'Factura', '12', '21', '2020-07-10 19:27:22', '19.00', 'Aprobado'),
(32, 1, 18, 'Factura', '12321', '123123', '2020-07-11 00:58:18', '19.00', 'Aprobado'),
(33, 1, 18, 'Factura', '21321', '12321', '2020-07-11 01:33:28', '19.00', 'Aprobado'),
(34, 1, 18, 'Boleta', '12123', '12312', '2020-07-11 04:54:05', '19.00', 'Aprobado'),
(35, 16, 18, 'Factura', '123123', '1231', '2020-07-11 04:54:40', '19.00', 'Aprobado'),
(36, 17, 18, 'Ticket', '1232123', '1231232', '2020-07-11 05:15:27', '19.00', 'Aprobado');

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
  `fecha_hora` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `perdida`
--

INSERT INTO `perdida` (`idperdida`, `idarticulo`, `stock`, `descripcion`, `fecha_hora`) VALUES
(20, 3, 2, 'Dañado', '2020-07-10 18:54:19');

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
(1, 'Proveedor', 'Suministros Ofc', 'PAS', '13131313', 'Padre Mariano #356, Providencia', '56232449091', 'SuministrosOfc@hotmail.com'),
(2, 'Cliente', 'Jose Manuel Rivera', 'RUT', '12635123', '11 de septiembre #5543', '56962969892', 'JoseManuelClliente@gmail.com'),
(16, 'Proveedor', 'Lapiz Lopez', 'DNI', '1232112', 'Av. Bernardo O’Higgins N° 3470, Estación', '56963469091', 'LápizLópez@gmail.com'),
(17, 'Proveedor', 'Libreria Las Rojas', 'PAS', '12321212', 'O’Higgins #324 La Serena', '512221331', 'LibreriaLasRojas@gmail.com'),
(18, 'Cliente', 'Cecilia Alvarez Molina', 'PAS', '213121222', 'Los Lagos #3322', '56962999721', 'CeciliaAlvarezMolina@gmail.com'),
(19, 'Cliente', 'Luis Moyano Torres', 'DNI', '12312321', 'Los Magos #1232', '56962999999', 'LuisMoyanoTorres@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `role`, `email`, `password`, `phone`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Marcos Oyarzo', 'Gerente', 'marcos_oyarzo97@outlook.com', '$2y$10$FNNXPnJnSjqlmDwxOECUIu2pw2h7N.jIMr/D1vwu/2l7ED3X2mN3m', '933279376', '0fOXQRTIYeky4vNrWwVPz6yhDiPOOxeyDSKa103LxWbGtFMyzoJzVzB8Dms8', '2020-06-20 04:08:38', '2020-07-02 03:04:12'),
(2, 'Sebastian Acosta', 'Administrador', 'sebastian.ipchile@gmail.com', '$2y$10$FNNXPnJnSjqlmDwxOECUIu2pw2h7N.jIMr/D1vwu/2l7ED3X2mN3m', '962969091', 'nliCfzChMHAygmbAyKn44nGfNShnp7W7S91gQ3Fdnnpj43GBH3pGNE7ehwaA', '2020-06-20 04:08:38', '2020-07-11 13:12:42'),
(3, 'Francisco Guerrero', 'Operador', 'fran@gmail.com', '$2y$10$FNNXPnJnSjqlmDwxOECUIu2pw2h7N.jIMr/D1vwu/2l7ED3X2mN3m', '988539601', '0fOXQRTIYeky4vNrWwVPz6yhDiPOOxeyDSKa103LxWbGtFMyzoJzVzB8Dms8', '2020-06-20 04:08:38', '2020-07-02 03:04:12'),
(18, 'Damian Acosta', 'Administrador', 'admin@gmail.com', '$2y$10$vDCADfwKwcAYFww1f6bYkejwIyqs31j/FhSJHniURa8wjUt.zbJhm', '962969091', NULL, '2020-07-11 00:09:33', '2020-07-11 00:09:33'),
(19, 'Visita', 'Visita', 'visita@gmail.com', '$2y$10$WBKwQKmMT.ncIJMUg8LmbOAPN.jNrD7q.SaevgxNZOW31Wu3lKkIS', '962239056', NULL, '2020-07-11 13:12:36', '2020-07-11 13:12:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

DROP TABLE IF EXISTS `venta`;
CREATE TABLE `venta` (
  `idventa` int NOT NULL,
  `idcliente` int NOT NULL,
  `idresponsable` int NOT NULL,
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

INSERT INTO `venta` (`idventa`, `idcliente`, `idresponsable`, `tipo_comprobante`, `serie_comprobante`, `num_comprobante`, `fecha_hora`, `impuesto`, `total_venta`, `estado`) VALUES
(1, 2, 2, 'Boleta', '001', '0008', '2020-07-10 19:39:17', '19.00', '700000.00', 'Aprobado'),
(21, 2, 2, 'Factura', '12321', '12312', '2020-07-10 20:08:58', '19.00', '1478544.00', 'Aprobado'),
(22, 2, 18, 'Factura', '12321', '12321', '2020-07-10 20:10:10', '19.00', '1478532.00', 'Aprobado'),
(23, 2, 18, 'Factura', '34455', '45454', '2020-07-10 22:02:24', '19.00', '1478532.00', 'Aprobado'),
(24, 2, 18, 'Factura', '77657', '65456', '2020-07-10 22:45:44', '19.00', '123212.00', 'Aprobado'),
(25, 2, 18, 'Factura', '213213', '12321', '2020-07-11 00:55:55', '19.00', '246424.00', 'Aprobado'),
(26, 18, 18, 'Factura', '1212322', '1221122', '2020-07-11 05:20:49', '19.00', '2539272.00', 'Aprobado');

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
  ADD KEY `fk_articulo_categoria_idx` (`idcategoria`),
  ADD KEY `idproveedor` (`idproveedor`);

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
  ADD KEY `fk_ingreso_persona_idx` (`idproveedor`),
  ADD KEY `idresponsable` (`idresponsable`);

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
  ADD KEY `fk_venta_cliente_idx` (`idcliente`),
  ADD KEY `id` (`idresponsable`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `articulo`
--
ALTER TABLE `articulo`
  MODIFY `idarticulo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `idcategoria` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `detalle_ingreso`
--
ALTER TABLE `detalle_ingreso`
  MODIFY `iddetalle_ingreso` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `iddetalle_venta` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `ingreso`
--
ALTER TABLE `ingreso`
  MODIFY `idingreso` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `perdida`
--
ALTER TABLE `perdida`
  MODIFY `idperdida` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `idpersona` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `idventa` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `articulo`
--
ALTER TABLE `articulo`
  ADD CONSTRAINT `articulo_ibfk_1` FOREIGN KEY (`idproveedor`) REFERENCES `persona` (`idpersona`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_articulo_categoria` FOREIGN KEY (`idcategoria`) REFERENCES `categoria` (`idcategoria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_ingreso`
--
ALTER TABLE `detalle_ingreso`
  ADD CONSTRAINT `fk_detalle_ingreso` FOREIGN KEY (`idingreso`) REFERENCES `ingreso` (`idingreso`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detalle_ingreso_articulo` FOREIGN KEY (`idarticulo`) REFERENCES `articulo` (`idarticulo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `fk_detalle_venta` FOREIGN KEY (`idventa`) REFERENCES `venta` (`idventa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detalle_venta_articulo` FOREIGN KEY (`idarticulo`) REFERENCES `articulo` (`idarticulo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ingreso`
--
ALTER TABLE `ingreso`
  ADD CONSTRAINT `fk_ingreso_persona` FOREIGN KEY (`idproveedor`) REFERENCES `persona` (`idpersona`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ingreso_ibfk_1` FOREIGN KEY (`idresponsable`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla `perdida`
--
ALTER TABLE `perdida`
  ADD CONSTRAINT `perdida_ibfk_1` FOREIGN KEY (`idarticulo`) REFERENCES `articulo` (`idarticulo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `fk_venta_cliente` FOREIGN KEY (`idcliente`) REFERENCES `persona` (`idpersona`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`idresponsable`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
