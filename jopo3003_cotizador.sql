CREATE PROCEDURE `PA_AddServicesCot`(IN pid INT(11))
BEGIN
SET @idcount=(SELECT COUNT(id_servicio) FROM t_serviciocotizado WHERE id_cotizacion=pid);
WHILE 0 <= @idcount DO
    SET @idservicio=(SELECT id_servicio FROM t_serviciocotizado WHERE id_cotizacion=pid AND cotizando=0 LIMIT 1);
    UPDATE t_servicio SET estado=1 WHERE id=@idservicio;
SET @srow=(SELECT orden FROM t_servicio WHERE estado=1 ORDER BY orden DESC LIMIT 1);
    @srow = @srow + 1;
    UPDATE t_servicio SET orden=@srow WHERE id=@idservicio;
    SET @idcount = @idcount - 1;
    UPDATE t_serviciocotizado SET cotizando=1 WHERE id_servicio =@idservicio;  
END WHILE;
COMMIT;
END$$

CREATE PROCEDURE `PA_DeleteServiceCot`(IN pid INT(11))
BEGIN
DECLARE idservicio INT DEFAULT 0;
UPDATE t_servicio SET estado=0 WHERE id=pid;
SET @srow=(SELECT orden FROM t_servicio WHERE id=pid);
SET @rowcount=(SELECT COUNT(id) FROM t_servicio WHERE orden>@srow);
WHILE 0 <= @rowcount DO
    UPDATE t_servicio SET orden=@srow WHERE orden=@srow+1;
    SET @srow = @srow + 1:
    SET @rowcount = @rowcount -1;
END WHILE;
SET @count=(SELECT COUNT(id_servicio) FROM t_serviciocotizado WHERE id_servicio=pid);
WHILE 0 <= @count DO
    SET idservicio=(SELECT id_servicio FROM t_serviciocotizado WHERE id_servicio=pid LIMIT 1);
        UPDATE t_serviciocotizado SET cotizando=0 WHERE id_servicio =idservicio;
    SET @count = @count -1;
END WHILE;
COMMIT;
END$$

CREATE PROCEDURE `PA_UpServiceCot`(IN pid INT(11))
BEGIN
SET @srow=(SELECT orden FROM t_servicio WHERE id=pid);
UPDATE t_servicio SET orden=@srow-1 WHERE orden=@srow;
UPDATE t_servicio SET orden=@srow+1 WHERE orden=@srow-1;
END$$

CREATE PROCEDURE `PA_DownServiceCot`(IN pid INT(11))
BEGIN
SET @srow=(SELECT orden FROM t_servicio WHERE id=pid);
UPDATE t_servicio SET orden=@srow+1 WHERE orden=@srow;
UPDATE t_servicio SET orden=@srow-1 WHERE orden=@srow+1;
END$$

-- phpMyAdmin SQL Dump
-- version 4.3.8
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 12-05-2016 a las 20:57:37
-- Versión del servidor: 5.5.42-37.1
-- Versión de PHP: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `jopo3003_cotizador`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`jopo3003`@`localhost` PROCEDURE `PA_AddServicesCot`(IN pid INT(11))
BEGIN
SET @idcount=(SELECT COUNT(id_servicio) FROM t_serviciocotizado WHERE id_cotizacion=pid);
WHILE 0 <= @idcount DO
    SET @idservicio=(SELECT id_servicio FROM t_serviciocotizado WHERE id_cotizacion=pid AND cotizando=0 LIMIT 1);
    UPDATE t_servicio SET estado=1 WHERE id=@idservicio;
    SET @idcount = @idcount - 1;
    UPDATE t_serviciocotizado SET cotizando=1 WHERE id_servicio =@idservicio;
END WHILE;
COMMIT;
END$$

CREATE DEFINER=`jopo3003`@`localhost` PROCEDURE `PA_DeleteServiceCot`(IN pid INT(11))
BEGIN
DECLARE idservicio INT DEFAULT 0;
UPDATE t_servicio SET estado=0 WHERE id=pid;
SET @count=(SELECT COUNT(id_servicio) FROM t_serviciocotizado WHERE id_servicio=pid);
WHILE 0 <= @count DO
    SET idservicio=(SELECT id_servicio FROM t_serviciocotizado WHERE id_servicio=pid LIMIT 1);
        UPDATE t_serviciocotizado SET cotizando=0 WHERE id_servicio =idservicio;
    SET @count = @count -1;
END WHILE;
COMMIT;
END$$

CREATE DEFINER=`jopo3003`@`localhost` PROCEDURE `PA_InsertaCotizacion`(IN `panticipo` DECIMAL(7,2), IN `pcellphone` BIGINT(20), IN `pcomentario` TEXT, IN `pcuatrodigitos` INT(11), IN `pdescuento` DECIMAL(7,2), IN `pemail` VARCHAR(70), IN `pestatus` VARCHAR(100), IN `pfecha` VARCHAR(100), `pformadepago` VARCHAR(100), IN `piva` DECIMAL(7,2), IN `pnombre` VARCHAR(80), IN `prestricciones` TEXT, IN `pphone` BIGINT(20), IN `psaldo` DECIMAL(7,2), IN `psubtotal` DECIMAL(7,2), IN `ptema` VARCHAR(200), IN `ptotal` DECIMAL(7,2), IN `pvigencia` VARCHAR(100))
BEGIN
INSERT INTO t_cotizacion(tema,fecha,vigencia,formadepago,cuatrodigitos,subtotal,iva,estatus,anticipo,saldo,descuento,total,comentario,notasyrestricciones)
VALUES(ptema,pfecha,pvigencia,pformadepago,pcuatrodigitos,psubtotal,piva,pestatus,panticipo,psaldo,pdescuento,ptotal,pcomentario,prestricciones);
COMMIT;
SET @idcotizacion=(SELECT id_cotizacion FROM t_cotizacion ORDER BY `id_cotizacion` DESC LIMIT 1);
INSERT INTO t_cliente(nombre,email,phone,cellphone,id_cotizacion)
VALUES(pnombre,pemail,pphone,pcellphone,@idcotizacion);
COMMIT;
SET @idcount=(SELECT COUNT(id) FROM t_servicio where estado=1);
WHILE 0 <= @idcount DO
	SET @idservicio=(SELECT id FROM t_servicio where estado=1 LIMIT 1);
    INSERT INTO t_serviciocotizado(id_servicio,id_cotizacion) VALUES(@idservicio,@idcotizacion);
    UPDATE t_servicio SET estado=0 WHERE id=@idservicio;
END WHILE;
SET @count=(SELECT COUNT(id_servicio) FROM t_serviciocotizado WHERE cotizando=1);
WHILE 0 <= @count DO
    SET @idservicio=(SELECT id_servicio FROM t_serviciocotizado WHERE id_cotizacion=pid AND cotizando=1 LIMIT 1);
    SET @count = @count - 1;
    UPDATE t_serviciocotizado SET cotizando=0 WHERE id_servicio =@idservicio;
END WHILE;
COMMIT;
END$$

CREATE DEFINER=`jopo3003`@`localhost` PROCEDURE `PA_Subtotal`()
BEGIN
DECLARE t1 INT DEFAULT 0;
DECLARE t2 INT DEFAULT 0;
DECLARE total INT DEFAULT 0;
SET t1=(SELECT SUM(precioPublico) AS total FROM t_servicio total WHERE estado=1 AND tipoprecio='PUBLICO');
IF t1 is NULL THEN SET t1 = 0;
END IF;
SET t2=(SELECT SUM(precioEspecial) AS total FROM t_servicio WHERE estado=1 AND tipoprecio='PAQUETE');
IF t2 is NULL THEN SET t2 = 0;
END IF;
SET total=t1+t2;
SELECT total;
END$$

CREATE DEFINER=`jopo3003`@`localhost` PROCEDURE `PA_UpdateCotizacion`(IN `panticipo` DECIMAL(7,2), IN `pcellphone` BIGINT(20), IN `pcomentario` TEXT, IN `pcuatrodigitos` INT(11), IN `pdescuento` DECIMAL(7,2), IN `pemail` VARCHAR(70), IN `pestatus` VARCHAR(100), IN `pfecha` VARCHAR(100), `pformadepago` VARCHAR(100), IN `pid` INT(11), IN `piva` DECIMAL(7,2), IN `pnombre` VARCHAR(80), IN `prestricciones` TEXT, IN `pphone` BIGINT(20), IN `psaldo` DECIMAL(7,2), IN `psubtotal` DECIMAL(7,2), IN `ptema` VARCHAR(200), IN `ptotal` DECIMAL(7,2), IN `pvigencia` VARCHAR(100))
BEGIN
UPDATE t_cotizacion SET tema=ptema,fecha=pfecha,vigencia=pvigencia,formadepago=pformadepago,cuatrodigitos=pcuatrodigitos,subtotal=psubtotal,iva=piva,estatus=pestatus,anticipo=panticipo,saldo=psaldo,descuento=pdescuento,total=ptotal,comentario=pcomentario,notasyrestricciones=prestricciones WHERE id_cotizacion=pid;
COMMIT;
UPDATE t_cliente SET 
nombre=pnombre,email=pemail,phone=pphone,cellphone=pcellphone
WHERE id_cotizacion=pid;
COMMIT;
SET @idcount=(SELECT COUNT(id) FROM t_servicio where estado=1);
WHILE 0 <= @idcount DO
	SET @idservicio=(SELECT id FROM t_servicio where estado=1 LIMIT 1);
    INSERT INTO t_serviciocotizado(id_servicio,id_cotizacion) VALUES(@idservicio,pid);
    UPDATE t_servicio SET estado=0 WHERE id=@idservicio;
END WHILE;
COMMIT;
SET @count=(SELECT COUNT(id_servicio) FROM t_serviciocotizado WHERE cotizando=1);
WHILE 0 <= @count DO
    SET @idservicio=(SELECT id_servicio FROM t_serviciocotizado WHERE id_cotizacion=pid AND cotizando=1 LIMIT 1);
    SET @count = @count - 1;
    UPDATE t_serviciocotizado SET cotizando=0 WHERE id_servicio =@idservicio;
END WHILE;
COMMIT;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_categoria`
--

CREATE TABLE IF NOT EXISTS `t_categoria` (
  `id_categoria` int(11) NOT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `descripcion` text
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `t_categoria`
--

INSERT INTO `t_categoria` (`id_categoria`, `categoria`, `descripcion`) VALUES
(1, 'Marketing', 'Todo sobre marketing'),
(2, 'Diseño', NULL),
(3, 'ATL', NULL),
(4, 'Lineamientos Legales', NULL),
(5, 'Medios', NULL),
(6, 'BTL', NULL),
(7, 'Programación', NULL),
(8, 'Fotografía', NULL),
(9, 'Impresión', NULL),
(10, 'SEO', NULL),
(11, 'Community Management', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_cliente`
--

CREATE TABLE IF NOT EXISTS `t_cliente` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(70) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` bigint(20) DEFAULT NULL,
  `cellphone` bigint(20) DEFAULT NULL,
  `id_cotizacion` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1604204 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `t_cliente`
--

INSERT INTO `t_cliente` (`id_cliente`, `nombre`, `email`, `phone`, `cellphone`, `id_cotizacion`) VALUES
(1604203, 'Cecilia Saavedra', 'luis940622@hotmail.com', 5514284655, 0, 333);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_cotizacion`
--

CREATE TABLE IF NOT EXISTS `t_cotizacion` (
  `id_cotizacion` int(11) NOT NULL,
  `tema` varchar(200) DEFAULT NULL,
  `fecha` varchar(100) DEFAULT NULL,
  `vigencia` varchar(100) NOT NULL,
  `formadepago` varchar(100) NOT NULL,
  `cuatrodigitos` int(11) NOT NULL,
  `subtotal` decimal(7,2) NOT NULL,
  `iva` decimal(7,2) DEFAULT NULL,
  `estatus` varchar(100) DEFAULT NULL,
  `anticipo` decimal(7,2) DEFAULT NULL,
  `saldo` decimal(7,2) DEFAULT NULL,
  `descuento` decimal(7,2) DEFAULT NULL,
  `total` decimal(7,2) DEFAULT NULL,
  `comentario` text,
  `notasyrestricciones` text
) ENGINE=InnoDB AUTO_INCREMENT=334 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `t_cotizacion`
--

INSERT INTO `t_cotizacion` (`id_cotizacion`, `tema`, `fecha`, `vigencia`, `formadepago`, `cuatrodigitos`, `subtotal`, `iva`, `estatus`, `anticipo`, `saldo`, `descuento`, `total`, `comentario`, `notasyrestricciones`) VALUES
(333, 'CARPETAS CORPORATIVAS', '2016-05-05T05:00:00.000Z', '2016-06-05T05:00:00.000Z', '50% y 50%', 4567, '4760.00', '761.60', 'ENTREGADA POR MAIL Y EN ESPERA', '1330.00', '4191.60', '0.00', '5521.60', 'en pruebas', '<p>\n\n</p>\n<ul type="circle" style="margin-bottom: 0cm;">\n  <li style="list-style: none;">\n    <ul type="circle" style="margin-bottom: 0cm;">\n      <li><span style="font-size:11.0pt;">Precios Unitarios y a selección del cliente.</span>\n      </li>\n      <li><b><span style="font-size:11.0pt;">El costo\nantes mencionado no incluye I.V.A.</span></b>\n      </li>\n      <li><span style="font-size:11.0pt;">La presente cotización tendrá vigencia de un mes\na partir de la fecha de elaboración.</span>\n      </li>\n      <li><span style="font-size:11.0pt;">Cualquier requerimiento extra no incluido en este\ndocumento será cotizado de manera independiente.</span>\n      </li>\n      <li><span style="font-size:11.0pt;">Se requiere el 50% de anticipo y el 50% contra\nentrega</span></li>\n    </ul>\n  </li>\n</ul>\n<ul style="margin-top: 0cm; margin-bottom: 0cm;" type="circle">\n  <ul style="margin-top: 0cm; margin-bottom: 0cm;" type="circle">\n    <li style="margin: 0cm 0cm 10pt; text-align: justify; line-height: 115%; font-size: 11pt; font-family: Calibri, sans-serif;"><u>Deposito a Nombre de Mauricio López\nFuentes, Banco Santander No. Cuenta 5653559032-8 Clabe:\n014180565355903287.</u></li>\n  </ul>\n</ul>\n<p></p>');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_servicio`
--

CREATE TABLE IF NOT EXISTS `t_servicio` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `descripcion` text,
  `precioPublico` decimal(7,2) DEFAULT NULL,
  `IVAPub` decimal(7,2) NOT NULL,
  `precioEspecial` decimal(7,2) DEFAULT NULL,
  `IVAPaq` decimal(7,2) NOT NULL,
  `nota` text,
  `estado` int(1) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `tipoprecio` varchar(10) NOT NULL DEFAULT 'PUBLICO'
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `t_servicio`
--

INSERT INTO `t_servicio` (`id`, `nombre`, `descripcion`, `precioPublico`, `IVAPub`, `precioEspecial`, `IVAPaq`, `nota`, `estado`, `id_categoria`, `tipoprecio`) VALUES
(25, 'DESARROLLO Y CREACIÓN DE CARPETAS CORPORATIVAS', '<p>\n\n  <ol style="margin-bottom: 0cm;">\n    <li><span style="font-size:11.0pt;">Brief a Agencia.</span></li>\n    <li><span style="font-size:11.0pt;">Recopilación, auditoría y análisis de información.</span></li>\n    <li><span style="font-size:11.0pt;">Desarrollo y creación de <b>dos propuestas </b>de conceptos creativos (portada, contraportada y\nlomo)</span></li>\n    <li><span style="font-size:11.0pt;">Selección y refinamiento de concepto creativo. (<b>3 cambios</b>)</span></li>\n    <li><span style="font-size:11.0pt;">Optimización de concepto definitivo y\ncorrecciones. (<b>1 cambio</b>)</span></li>\n    <li><span style="font-size:11.0pt;">Autorización final. </span></li>\n    <li><span style="font-size:11.0pt;">Diagramación y producción de originales.</span></li>\n  </ol>\n\n\n\n  <br />\n</p>', '2660.00', '425.60', '2560.00', '409.60', 'Color azul marino (sugerencia)', 1, 2, 'PUBLICO'),
(26, 'Diseño de Pared', '<ul style="margin-bottom: 0cm;">\n\n  <li>Diseño de Layout.- Se realizarán <b>DOS</b> propuestas gráficas para revisión. Éstas serán presentadas a nivel dummy digital para revisión y autorización.&nbsp; </li>\n</ul>\n<p style="margin: 0cm 0cm 0.0001pt 36pt; font-size: 12pt; font-family: Calibri, sans-serif;">&nbsp;</p>\n<ul style="margin-bottom: 0cm;">\n  <li>Cambios sobre diseño elegido&nbsp;<span style="font-family: Calibri, sans-serif; font-size: 12pt; text-align: justify; text-indent: 16.35pt;">Dos.</span></li>\n</ul>\n\n\n\n<p>\n  <br />\n</p>', '2100.00', '336.00', '2000.00', '320.00', '-', 1, 2, 'PUBLICO'),
(27, 'Servicio de marketing', '<ol style="list-style-type: lower-roman;">\n  <li>Un servicio y su descripción&nbsp;</li>\n</ol>', '2500.00', '400.00', '2400.00', '384.00', '-', 0, 1, 'PUBLICO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_serviciocotizado`
--

CREATE TABLE IF NOT EXISTS `t_serviciocotizado` (
  `id_servicio` int(11) NOT NULL,
  `id_cotizacion` int(11) NOT NULL,
  `cotizando` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `t_serviciocotizado`
--

INSERT INTO `t_serviciocotizado` (`id_servicio`, `id_cotizacion`, `cotizando`) VALUES
(25, 333, 1),
(25, 333, 1),
(26, 333, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_usuario`
--

CREATE TABLE IF NOT EXISTS `t_usuario` (
  `uid` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(70) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nivel` varchar(20) NOT NULL DEFAULT 'USUARIO'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `t_usuario`
--

INSERT INTO `t_usuario` (`uid`, `name`, `email`, `password`, `nivel`) VALUES
(1, 'Luis', 'luis@gmail.com', '$2a$10$b1bc7449391650c947333O7zk2a7rPph/nibBShC6c54svWSzKw1G', 'ADMINISTRADOR'),
(2, 'mlopez', 'mlopez@masoko.mx', '$2a$10$f3f2aab208161780aefeduDATBKthblcZ9GIS7GTUbGGY.IQkRUjG', 'ADMINISTRADOR');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `t_categoria`
--
ALTER TABLE `t_categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `t_cliente`
--
ALTER TABLE `t_cliente`
  ADD PRIMARY KEY (`id_cliente`), ADD KEY `idc` (`id_cotizacion`);

--
-- Indices de la tabla `t_cotizacion`
--
ALTER TABLE `t_cotizacion`
  ADD PRIMARY KEY (`id_cotizacion`);

--
-- Indices de la tabla `t_servicio`
--
ALTER TABLE `t_servicio`
  ADD PRIMARY KEY (`id`), ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `t_serviciocotizado`
--
ALTER TABLE `t_serviciocotizado`
  ADD KEY `id_servicio` (`id_servicio`), ADD KEY `id_cotizacion` (`id_cotizacion`);

--
-- Indices de la tabla `t_usuario`
--
ALTER TABLE `t_usuario`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `t_categoria`
--
ALTER TABLE `t_categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT de la tabla `t_cliente`
--
ALTER TABLE `t_cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1604204;
--
-- AUTO_INCREMENT de la tabla `t_cotizacion`
--
ALTER TABLE `t_cotizacion`
  MODIFY `id_cotizacion` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=334;
--
-- AUTO_INCREMENT de la tabla `t_servicio`
--
ALTER TABLE `t_servicio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT de la tabla `t_usuario`
--
ALTER TABLE `t_usuario`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `t_cliente`
--
ALTER TABLE `t_cliente`
ADD CONSTRAINT `t_cliente_ibfk_1` FOREIGN KEY (`id_cotizacion`) REFERENCES `t_cotizacion` (`id_cotizacion`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `t_servicio`
--
ALTER TABLE `t_servicio`
ADD CONSTRAINT `t_servicio_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `t_categoria` (`id_categoria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `t_serviciocotizado`
--
ALTER TABLE `t_serviciocotizado`
ADD CONSTRAINT `t_serviciocotizado_ibfk_1` FOREIGN KEY (`id_servicio`) REFERENCES `t_servicio` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `t_serviciocotizado_ibfk_2` FOREIGN KEY (`id_cotizacion`) REFERENCES `t_cotizacion` (`id_cotizacion`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
