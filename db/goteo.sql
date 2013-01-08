-- phpMyAdmin SQL Dump
-- version 3.3.8
-- http://www.phpmyadmin.net
--
-- Servidor: localhost:3306
-- Tiempo de generación: 06-02-2012 a las 09:21:42
-- Versión del servidor: 5.1.41
-- Versión de PHP: 5.3.2-1ubuntu4.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `goteo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acl`
--

DROP TABLE IF EXISTS `acl`;
CREATE TABLE `acl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `node_id` varchar(50) NOT NULL,
  `role_id` varchar(50) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `allow` tinyint(1) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `role_FK` (`role_id`),
  KEY `user_FK` (`user_id`),
  KEY `node_FK` (`node_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2080 ;

--
-- Volcar la base de datos para la tabla `acl`
--

INSERT INTO `acl` VALUES(1, '*', '*', '*', '//', 1, '2011-05-18 16:45:40');
INSERT INTO `acl` VALUES(2, '*', '*', '*', '/image/*', 1, '2011-05-18 23:08:42');
INSERT INTO `acl` VALUES(3, '*', '*', '*', '/tpv/*', 1, '2011-05-27 01:04:02');
INSERT INTO `acl` VALUES(4, '*', '*', '*', '/admin/*', 0, '2011-05-18 16:45:40');
INSERT INTO `acl` VALUES(5, '*', '*', '*', '/project/*', 1, '2011-05-18 16:45:40');
INSERT INTO `acl` VALUES(6, '*', 'superadmin', '*', '/admin/*', 1, '2011-05-18 16:45:40');
INSERT INTO `acl` VALUES(7, '*', '*', '*', '/user/edit/*', 0, '2011-05-18 16:49:36');
INSERT INTO `acl` VALUES(8, '*', '*', '*', '/user/*', 1, '2011-05-18 20:59:54');
INSERT INTO `acl` VALUES(9, '*', '*', '*', 'user/logout', 1, '2011-05-18 21:15:02');
INSERT INTO `acl` VALUES(10, '*', '*', '*', '/search', 1, '2011-05-18 21:16:40');
INSERT INTO `acl` VALUES(11, '*', 'user', '*', '/project/create', 1, '2011-05-18 21:46:44');
INSERT INTO `acl` VALUES(12, '*', 'user', '*', '/dashboard/*', 1, '2011-05-18 21:48:43');
INSERT INTO `acl` VALUES(13, '*', 'public', '*', '/invest/*', 0, '2011-05-18 22:30:23');
INSERT INTO `acl` VALUES(14, '*', 'user', '*', '/message/*', 1, '2011-05-18 22:30:23');
INSERT INTO `acl` VALUES(15, '*', '*', '*', '/user/logout', 1, '2011-05-18 22:33:27');
INSERT INTO `acl` VALUES(16, '*', '*', '*', '/discover/*', 1, '2011-05-18 22:37:00');
INSERT INTO `acl` VALUES(17, '*', '*', '*', '/project/create', 0, '2011-05-18 22:38:22');
INSERT INTO `acl` VALUES(18, '*', '*', '*', '/project/edit/*', 0, '2011-05-18 22:38:22');
INSERT INTO `acl` VALUES(19, '*', '*', '*', '/project/raw/*', 0, '2011-05-18 22:39:37');
INSERT INTO `acl` VALUES(20, '*', 'root', '*', '/project/raw/*', 1, '2011-05-18 22:39:37');
INSERT INTO `acl` VALUES(21, '*', 'superadmin', '*', '/project/edit/*', 1, '2011-05-18 22:43:08');
INSERT INTO `acl` VALUES(22, '*', '*', '*', '/project/delete/*', 0, '2011-05-18 22:43:51');
INSERT INTO `acl` VALUES(23, '*', 'superadmin', '*', '/project/delete/*', 1, '2011-05-18 22:44:37');
INSERT INTO `acl` VALUES(24, '*', '*', '*', '/blog/*', 1, '2011-05-18 22:45:14');
INSERT INTO `acl` VALUES(25, '*', '*', '*', '/faq/*', 1, '2011-05-18 22:49:01');
INSERT INTO `acl` VALUES(26, '*', '*', '*', '/about/*', 1, '2011-05-18 22:49:01');
INSERT INTO `acl` VALUES(27, '*', 'superadmin', '*', '/user/edit/*', 1, '2011-05-18 22:56:56');
INSERT INTO `acl` VALUES(29, '*', 'user', '*', '/user/edit', 1, '2011-05-18 23:56:56');
INSERT INTO `acl` VALUES(30, '*', 'user', '*', '/message/edit/*', 0, '2011-05-19 00:45:29');
INSERT INTO `acl` VALUES(31, '*', 'user', '*', '/message/delete/*', 0, '2011-05-19 00:45:29');
INSERT INTO `acl` VALUES(32, '*', 'superadmin', '*', '/message/edit/*', 1, '2011-05-19 00:56:55');
INSERT INTO `acl` VALUES(33, '*', 'superadmin', '*', '/message/delete/*', 1, '2011-05-19 00:00:00');
INSERT INTO `acl` VALUES(34, '*', 'user', '*', '/invest/*', 1, '2011-05-19 00:56:32');
INSERT INTO `acl` VALUES(35, '*', 'public', '*', '/message/*', 0, '2011-05-19 00:56:32');
INSERT INTO `acl` VALUES(36, '*', 'public', '*', '/user/edit/*', 0, '2011-05-19 01:00:18');
INSERT INTO `acl` VALUES(37, '*', 'superadmin', '*', '/cron/*', 1, '2011-05-27 01:04:02');
INSERT INTO `acl` VALUES(38, '*', '*', '*', '/widget/*', 1, '2011-06-10 11:30:39');
INSERT INTO `acl` VALUES(39, '*', '*', '*', '/user/recover/*', 1, '2011-06-12 22:31:36');
INSERT INTO `acl` VALUES(40, '*', '*', '*', '/news/*', 1, '2011-06-19 13:36:34');
INSERT INTO `acl` VALUES(41, '*', 'user', '*', '/community/*', 1, '2011-06-19 13:49:36');
INSERT INTO `acl` VALUES(42, '*', '*', '*', '/ws/*', 1, '2011-06-20 23:18:15');
INSERT INTO `acl` VALUES(43, '*', 'checker', '*', '/review/*', 1, '2011-06-21 17:18:51');
INSERT INTO `acl` VALUES(44, '*', '*', '*', '/contact/*', 1, '2011-06-30 00:24:00');
INSERT INTO `acl` VALUES(45, '*', '*', '*', '/service/*', 1, '2011-07-13 17:26:04');
INSERT INTO `acl` VALUES(47, '*', 'translator', '*', '/translate/*', 1, '2011-07-24 12:47:50');
INSERT INTO `acl` VALUES(48, '*', '*', '*', '/legal/*', 1, '2011-08-05 13:09:08');
INSERT INTO `acl` VALUES(49, '*', '*', '*', '/rss/*', 1, '2011-08-14 18:32:01');
INSERT INTO `acl` VALUES(50, '*', 'superadmin', '*', '/impersonate/*', 1, '2011-08-20 09:41:05');
INSERT INTO `acl` VALUES(51, '*', '*', '*', '/glossary/*', 1, '2011-08-25 15:39:17');
INSERT INTO `acl` VALUES(52, '*', 'user', 'paypal', '/paypal/*', 1, '2011-09-05 00:58:55');
INSERT INTO `acl` VALUES(53, '*', 'user', 'paypal', '/cron/*', 1, '2011-09-05 00:58:55');
INSERT INTO `acl` VALUES(54, '*', '*', '*', '/press/*', 1, '2011-09-06 10:04:52');
INSERT INTO `acl` VALUES(55, '*', '*', '*', '/project/view/*', 0, '2011-09-16 15:46:34');
INSERT INTO `acl` VALUES(56, '*', '*', '*', '/mail/*', 1, '2011-09-25 14:13:58');
INSERT INTO `acl` VALUES(57, '*', 'user', 'diegobus', '/admin/*', 1, '2011-09-29 16:17:50');
INSERT INTO `acl` VALUES(58, '*', '*', '*', '/json/*', 1, '2011-11-22 16:10:08');
INSERT INTO `acl` VALUES(67, '*', '*', '*', '/wof/*', 1, '2011-12-14 16:44:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banner`
--

DROP TABLE IF EXISTS `banner`;
CREATE TABLE `banner` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `node` varchar(50) NOT NULL,
  `project` varchar(50) NOT NULL,
  `order` smallint(5) unsigned NOT NULL DEFAULT '1',
  `image` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_node` (`node`,`project`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Proyectos en banner superior' AUTO_INCREMENT=32 ;

--
-- Volcar la base de datos para la tabla `banner`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `blog`
--

DROP TABLE IF EXISTS `blog`;
CREATE TABLE `blog` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL,
  `owner` varchar(50) NOT NULL COMMENT 'la id del proyecto o nodo',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Blogs de nodo o proyecto' AUTO_INCREMENT=28 ;

--
-- Volcar la base de datos para la tabla `blog`
--

INSERT INTO `blog` VALUES(1, 'node', 'goteo', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campaign`
--

DROP TABLE IF EXISTS `campaign`;
CREATE TABLE `campaign` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `campaign`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext,
  `description` text,
  `order` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Categorias de los proyectos' AUTO_INCREMENT=15 ;

--
-- Volcar la base de datos para la tabla `category`
--

INSERT INTO `category` VALUES(2, 'Social', 'Proyectos que promueven el cambio social, la resolución de problemas en las relaciones humanas y/o su fortalecimiento para conseguir un mayor bienestar.', 1);
INSERT INTO `category` VALUES(6, 'Comunicativo', 'Proyectos con el objetivo de informar, denunciar, comunicar (por ejemplo periodismo ciudadano, documentales, blogs, programas de radio).', 3);
INSERT INTO `category` VALUES(7, 'Tecnológico', 'Desarrollos técnicos de software, hardware, herramientas etc. para solucionar problemas o necesidades concretas. ', 1);
INSERT INTO `category` VALUES(9, 'Comercial', 'Proyectos que aspiran a convertirse en una iniciativa empresarial, generando beneficios económicos. ', 1);
INSERT INTO `category` VALUES(10, 'Educativo', 'Proyectos donde el objetivo primordial es la formación o el aprendizaje. ', 5);
INSERT INTO `category` VALUES(11, 'Cultural', 'Proyectos con objetivos artísticos y culturales en un sentido amplio.', 6);
INSERT INTO `category` VALUES(13, 'Ecológico', 'Proyectos relacionados con el cuidado del medio ambiente, la sostenibilidad y/o la diversidad biológica.\r\n', 7);
INSERT INTO `category` VALUES(14, 'Científico', 'Estudios o investigaciones de alguna materia, proyectos que buscan respuestas, soluciones, explicaciones nuevas.', 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `category_lang`
--

DROP TABLE IF EXISTS `category_lang`;
CREATE TABLE `category_lang` (
  `id` bigint(20) unsigned NOT NULL,
  `lang` varchar(2) NOT NULL,
  `name` tinytext,
  `description` text,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `category_lang`
--

INSERT INTO `category_lang` VALUES(2, 'ca', 'Social', 'Projectes que promouen el canvi social, la resolució de problemes en les relacions humanes i/o el seu enfortiment per aconseguir un major benestar.');
INSERT INTO `category_lang` VALUES(2, 'de', 'Gesellschaft', 'Projekte, die den sozialen Austausch sowie die Problemlösung in zwischenmenschlichen Beziehungen fördern und die eine Stärkung gesellschaftlicher Bindungen zur Förderung des Allgemeinwohls unterstützen.');
INSERT INTO `category_lang` VALUES(2, 'en', 'Social', 'Projects that promote social change, resolve problems with or strengthen human relationshiops in order to achieve better well-being.');
INSERT INTO `category_lang` VALUES(6, 'ca', 'Comunicatiu', 'Projectes amb l''objectiu d''informar, denunciar, comunicar (per exemple periodisme ciutadà, documentals, blogs, programes de radio).');
INSERT INTO `category_lang` VALUES(6, 'de', 'Kommunikation', 'Projekte, deren Ziel es ist zu informieren, Misstände öffentlich zu machen oder die sich um Kommunikation im Allgemeinen drehen (z.B. Bürgerzeitungen, Dokumentarfilme, Blogs, Radioprogramme).');
INSERT INTO `category_lang` VALUES(6, 'en', 'Communications', 'Projects whose objective is to inform, denounce and/or communicate (for example, civic journalism, documentaries, blogs, radio programs).');
INSERT INTO `category_lang` VALUES(7, 'ca', 'Tecnològic', 'Desenvolupaments tècnics de programari, maquinari, eines etc. per solucionar problemes o necessitats concretes. ');
INSERT INTO `category_lang` VALUES(7, 'de', 'Technologie', 'Technische Entwicklungen im Bereich Software, Hardware, Werkzeuge etc. die der Problemlösung dienen oder die auf konkrete Bedürfnisse eingehen.\r\n');
INSERT INTO `category_lang` VALUES(7, 'en', 'Technological', 'Technical development of software, hardware, tools, etc in order to solve concrete problems or needs.');
INSERT INTO `category_lang` VALUES(9, 'ca', 'Comercial', 'Projectes que aspiren a convertir-se en una iniciativa empresarial, generant beneficis econòmics. ');
INSERT INTO `category_lang` VALUES(9, 'de', 'Kommerziell', 'Projekte, die eine unternehmerische Initiative darstellen und die die Absicht haben, ökonomischen Gewinn zu generieren.');
INSERT INTO `category_lang` VALUES(9, 'en', 'Commercial', 'Projects that are business initiatives, and that hope to generate profits.');
INSERT INTO `category_lang` VALUES(10, 'ca', 'Educatiu', 'Projectes on l''objectiu primordial és la formació o l''aprenentatge. ');
INSERT INTO `category_lang` VALUES(10, 'de', 'Bildung', 'Projekte, deren primäres Ziel im Bereich Bildung und Lernen liegt.');
INSERT INTO `category_lang` VALUES(10, 'en', 'Educational', 'Projects whose most important objective is formation or learning. ');
INSERT INTO `category_lang` VALUES(11, 'ca', 'Cultural', 'Projectes amb objectius artístics i culturals en un sentit ampli.');
INSERT INTO `category_lang` VALUES(11, 'de', 'Kultur', 'Projekte mit künstlerischen und kulturellen Zielsetzungen im weiteren Sinne.');
INSERT INTO `category_lang` VALUES(11, 'en', 'Cultural', 'Projects with artistic or cultural objectives.');
INSERT INTO `category_lang` VALUES(13, 'ca', 'Ecològic', 'Projectes relacionats amb la cura del medi ambient, la sostenibilitat i/o la diversitat biològica.\r\n');
INSERT INTO `category_lang` VALUES(13, 'de', 'Ökologie', 'Projekte im Bereich Umweltschutz, Nachhaltigkeit und Biodiversität.');
INSERT INTO `category_lang` VALUES(13, 'en', 'Ecological', 'Projects that are related to the care of the environment, sustainability, and/or biological diversity.\r\n');
INSERT INTO `category_lang` VALUES(14, 'ca', 'Científic', 'Estudis o investigacions d''alguna matèria, projectes que busquen respostes, solucions, explicacions noves.');
INSERT INTO `category_lang` VALUES(14, 'de', 'Wissenschaft', 'Studien und Untersuchungen jeglicher Art, Projekte auf der Suche nach Antworten, Lösungen, und neuen Erklärungen.');
INSERT INTO `category_lang` VALUES(14, 'en', 'Scientific', 'Studies or research, projects that look for answers, solutions, new explanations.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post` bigint(20) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `text` text NOT NULL,
  `user` varchar(50) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Comentarios' AUTO_INCREMENT=19 ;

--
-- Volcar la base de datos para la tabla `comment`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cost`
--

DROP TABLE IF EXISTS `cost`;
CREATE TABLE `cost` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project` varchar(50) NOT NULL,
  `cost` tinytext,
  `description` text,
  `type` varchar(50) DEFAULT NULL,
  `amount` int(5) DEFAULT '0',
  `required` tinyint(1) DEFAULT '0',
  `from` date DEFAULT NULL,
  `until` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Desglose de costes de proyectos' AUTO_INCREMENT=1021 ;

--
-- Volcar la base de datos para la tabla `cost`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cost_lang`
--

DROP TABLE IF EXISTS `cost_lang`;
CREATE TABLE `cost_lang` (
  `id` int(20) NOT NULL,
  `lang` varchar(2) NOT NULL,
  `cost` tinytext,
  `description` text,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `cost_lang`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `criteria`
--

DROP TABLE IF EXISTS `criteria`;
CREATE TABLE `criteria` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `section` varchar(50) NOT NULL DEFAULT 'node',
  `title` tinytext,
  `description` text,
  `order` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Criterios de puntuación' AUTO_INCREMENT=28 ;

--
-- Volcar la base de datos para la tabla `criteria`
--

INSERT INTO `criteria` VALUES(5, 'project', 'Es original', 'donde va esta descripción? donde esta el tool tip?\r\n\r\nHola, este tooltip ira en el formulario de revision', 1);
INSERT INTO `criteria` VALUES(6, 'project', 'Es eficaz en su estrategia de comunicación', '', 2);
INSERT INTO `criteria` VALUES(7, 'project', 'Aporta información suficiente del proyecto', '', 3);
INSERT INTO `criteria` VALUES(8, 'project', 'Aporta productos, servicios o valores “deseables” para la comunidad', '', 4);
INSERT INTO `criteria` VALUES(9, 'project', 'Es afín a la cultura abierta', '', 5);
INSERT INTO `criteria` VALUES(10, 'project', 'Puede crecer, es escalable', '', 6);
INSERT INTO `criteria` VALUES(11, 'project', 'Son coherentes los recursos solicitados con los objetivos y el tiempo de desarrollo', '', 7);
INSERT INTO `criteria` VALUES(12, 'project', 'Riesgo proporcional al grado de beneficios (sociales, culturales y/o económicos)', 'Test descripción de un criterio...', 8);
INSERT INTO `criteria` VALUES(13, 'owner', 'Posee buena reputación en su sector', '', 1);
INSERT INTO `criteria` VALUES(14, 'owner', 'Ha trabajado con organizaciones y colectivos con buena reputación', '', 2);
INSERT INTO `criteria` VALUES(15, 'owner', 'Aporta información sobre experiencias anteriores (éxitos y fracasos)', '', 3);
INSERT INTO `criteria` VALUES(16, 'owner', 'Tiene capacidades para llevar a cabo el proyecto', '', 4);
INSERT INTO `criteria` VALUES(17, 'owner', 'Cuenta con un equipo formado', '', 5);
INSERT INTO `criteria` VALUES(18, 'owner', 'Cuenta con una comunidad de seguidores', '', 6);
INSERT INTO `criteria` VALUES(19, 'owner', 'Tiene visibilidad en la red', '', 7);
INSERT INTO `criteria` VALUES(20, 'reward', 'Es viable (su coste está incluido en la producción del proyecto)', '', 1);
INSERT INTO `criteria` VALUES(21, 'reward', 'Puede tener efectos positivos, transformadores (sociales, culturales, empresariales)', '', 2);
INSERT INTO `criteria` VALUES(22, 'reward', 'Aporta conocimiento nuevo, de difícil acceso o en proceso de desaparecer', '', 3);
INSERT INTO `criteria` VALUES(23, 'reward', 'Aporta oportunidades de generar economía alrededor', '', 4);
INSERT INTO `criteria` VALUES(24, 'reward', 'Da libertad en el uso de sus resultados (es reproductible)', '', 5);
INSERT INTO `criteria` VALUES(25, 'reward', 'Ofrece un retorno atractivo (por original, por útil, por inspirador... )', '', 6);
INSERT INTO `criteria` VALUES(26, 'reward', 'Cuenta con actualizaciones', '', 7);
INSERT INTO `criteria` VALUES(27, 'reward', 'Integra a la comunidad (a los seguidores, cofinanciadores, a un grupo social)', '', 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `criteria_lang`
--

DROP TABLE IF EXISTS `criteria_lang`;
CREATE TABLE `criteria_lang` (
  `id` bigint(20) unsigned NOT NULL,
  `lang` varchar(2) NOT NULL,
  `title` tinytext,
  `description` text,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `criteria_lang`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `faq`
--

DROP TABLE IF EXISTS `faq`;
CREATE TABLE `faq` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `node` varchar(50) NOT NULL,
  `section` varchar(50) NOT NULL DEFAULT 'node',
  `title` tinytext,
  `description` text,
  `order` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Preguntas frecuentes' AUTO_INCREMENT=110 ;

--
-- Volcar la base de datos para la tabla `faq`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `faq_lang`
--

DROP TABLE IF EXISTS `faq_lang`;
CREATE TABLE `faq_lang` (
  `id` bigint(20) unsigned NOT NULL,
  `lang` varchar(2) NOT NULL,
  `title` tinytext,
  `description` text,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `faq_lang`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `feed`
--

DROP TABLE IF EXISTS `feed`;
CREATE TABLE `feed` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` tinytext NOT NULL,
  `url` tinytext,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `scope` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `html` text NOT NULL,
  `image` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `scope` (`scope`),
  KEY `type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Log de eventos' AUTO_INCREMENT=10487 ;

--
-- Volcar la base de datos para la tabla `feed`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `glossary`
--

DROP TABLE IF EXISTS `glossary`;
CREATE TABLE `glossary` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` tinytext,
  `text` longtext COMMENT 'texto de la entrada',
  `media` tinytext,
  `legend` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Entradas para el glosario' AUTO_INCREMENT=12 ;

--
-- Volcar la base de datos para la tabla `glossary`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `glossary_image`
--

DROP TABLE IF EXISTS `glossary_image`;
CREATE TABLE `glossary_image` (
  `glossary` bigint(20) NOT NULL,
  `image` int(10) unsigned NOT NULL,
  PRIMARY KEY (`glossary`,`image`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `glossary_image`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `glossary_lang`
--

DROP TABLE IF EXISTS `glossary_lang`;
CREATE TABLE `glossary_lang` (
  `id` bigint(20) unsigned NOT NULL,
  `lang` varchar(2) NOT NULL,
  `title` tinytext,
  `text` longtext,
  `legend` text,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `glossary_lang`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `icon`
--

DROP TABLE IF EXISTS `icon`;
CREATE TABLE `icon` (
  `id` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` tinytext,
  `group` varchar(50) DEFAULT NULL COMMENT 'exclusivo para grupo',
  `order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Iconos para retorno/recompensa';

--
-- Volcar la base de datos para la tabla `icon`
--

INSERT INTO `icon` VALUES('code', 'Código fuente', 'Por código fuente entendemos programas y software en general.', 'social', 0);
INSERT INTO `icon` VALUES('design', 'Diseño', 'Los diseños pueden ser de planos o patrones, esquemas, esbozos, diagramas de flujo, etc.', 'social', 0);
INSERT INTO `icon` VALUES('file', 'Archivos digitales', 'Los archivos digitales pueden ser de música, vídeo, documentos de texto, etc.', '', 0);
INSERT INTO `icon` VALUES('manual', 'Manuales', 'Documentos prácticos detallando pasos, materiales formativos, bussiness plans, “how tos”, recetas, etc.', 'social', 0);
INSERT INTO `icon` VALUES('money', 'Dinero', 'Retornos económicos proporcionales a la inversión realizada, que se deben detallar en cantidad pero también forma de pago.', 'individual', 50);
INSERT INTO `icon` VALUES('other', 'Otro', 'Sorpréndenos con esta nueva tipología, realmente nos interesa :) ', '', 99);
INSERT INTO `icon` VALUES('product', 'Producto', 'Los productos pueden ser los que se han producido, en edición limitada, o fragmentos u obras derivadas del original.', 'individual', 0);
INSERT INTO `icon` VALUES('service', 'Servicios', 'Acciones y/o sesiones durante tiempo determinado para satisfacer una necesidad individual o de grupo: una formación, una ayuda técnica, un asesoramiento, etc.', '', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `icon_lang`
--

DROP TABLE IF EXISTS `icon_lang`;
CREATE TABLE `icon_lang` (
  `id` varchar(50) NOT NULL,
  `lang` varchar(2) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` tinytext,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `icon_lang`
--

INSERT INTO `icon_lang` VALUES('code', 'ca', 'Codi font', 'Per codi font entenem programes i programari en general.');
INSERT INTO `icon_lang` VALUES('code', 'en', 'Source code', 'By source code, we mean programs and software in general.');
INSERT INTO `icon_lang` VALUES('design', 'ca', 'Disseny', 'Els dissenys poden ser de plànols o patrons, esquemes, esbossos, diagrames de flux, etc.');
INSERT INTO `icon_lang` VALUES('design', 'en', 'Design', 'Designs can be drawings, patterns, sketches, rough drafts, flowcharts, etc.');
INSERT INTO `icon_lang` VALUES('file', 'ca', 'Arxius digitals', 'Els arxius digitals poden ser de música, vídeo, documents de text, etc.');
INSERT INTO `icon_lang` VALUES('file', 'en', 'Digital files', 'Digital files may be music, video, text documents, etc.');
INSERT INTO `icon_lang` VALUES('manual', 'ca', 'Manuals', 'Documents pràctics detallant passos, materials formatius, plans de negoci, “how tos”, receptes, etc.');
INSERT INTO `icon_lang` VALUES('manual', 'en', 'Manuals', 'Practical documentation that details step-by-step instructions, tutorials, business plans, how-to''s, code cookbooks, etc. ');
INSERT INTO `icon_lang` VALUES('money', 'ca', 'Diners', 'Retorns econòmics proporcionals a la inversió realitzada, que s''han de detallar en quantitat però també forma de pagament.');
INSERT INTO `icon_lang` VALUES('money', 'en', 'Money', 'Economic benefits that are proportional to the investment made, with details about quantity and also form of payment');
INSERT INTO `icon_lang` VALUES('other', 'ca', 'Altres', 'Sorprèn-nos amb aquesta nova tipologia, realment ens interessa :) ');
INSERT INTO `icon_lang` VALUES('other', 'en', 'Other', 'Surprise us with this category, we''re really interested!');
INSERT INTO `icon_lang` VALUES('product', 'ca', 'Producte', 'Els productes poden ser els que s''han produït, en edició limitada, o fragments o obres derivades de l''original.');
INSERT INTO `icon_lang` VALUES('product', 'en', 'Product', 'Products can be limited editions or prototypes, or pieces or works derived from the original.');
INSERT INTO `icon_lang` VALUES('service', 'ca', 'Serveis', 'Accions i/o sessions durant temps determinat per satisfer una necessitat individual o de grup: una formació, una ajuda tècnica, un assessorament, etc.');
INSERT INTO `icon_lang` VALUES('service', 'en', 'Services', 'Actions or sessions during a specific period of time which satisfy an individual or group need: education, technical assistance, advice, etc. ');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `icon_license`
--

DROP TABLE IF EXISTS `icon_license`;
CREATE TABLE `icon_license` (
  `icon` varchar(50) NOT NULL,
  `license` varchar(50) NOT NULL,
  UNIQUE KEY `icon` (`icon`,`license`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Licencias para cada icono, solo social';

--
-- Volcar la base de datos para la tabla `icon_license`
--

INSERT INTO `icon_license` VALUES('code', 'agpl');
INSERT INTO `icon_license` VALUES('code', 'apache');
INSERT INTO `icon_license` VALUES('code', 'balloon');
INSERT INTO `icon_license` VALUES('code', 'bsd');
INSERT INTO `icon_license` VALUES('code', 'gpl');
INSERT INTO `icon_license` VALUES('code', 'gpl2');
INSERT INTO `icon_license` VALUES('code', 'lgpl');
INSERT INTO `icon_license` VALUES('code', 'mit');
INSERT INTO `icon_license` VALUES('code', 'mpl');
INSERT INTO `icon_license` VALUES('code', 'odbl');
INSERT INTO `icon_license` VALUES('code', 'odcby');
INSERT INTO `icon_license` VALUES('code', 'oshw');
INSERT INTO `icon_license` VALUES('code', 'pd');
INSERT INTO `icon_license` VALUES('code', 'php');
INSERT INTO `icon_license` VALUES('code', 'tapr');
INSERT INTO `icon_license` VALUES('code', 'xoln');
INSERT INTO `icon_license` VALUES('design', 'balloon');
INSERT INTO `icon_license` VALUES('design', 'cc0');
INSERT INTO `icon_license` VALUES('design', 'ccby');
INSERT INTO `icon_license` VALUES('design', 'ccbync');
INSERT INTO `icon_license` VALUES('design', 'ccbyncnd');
INSERT INTO `icon_license` VALUES('design', 'ccbyncsa');
INSERT INTO `icon_license` VALUES('design', 'ccbynd');
INSERT INTO `icon_license` VALUES('design', 'ccbysa');
INSERT INTO `icon_license` VALUES('design', 'fal');
INSERT INTO `icon_license` VALUES('design', 'fdl');
INSERT INTO `icon_license` VALUES('design', 'gpl');
INSERT INTO `icon_license` VALUES('design', 'gpl2');
INSERT INTO `icon_license` VALUES('design', 'oshw');
INSERT INTO `icon_license` VALUES('design', 'pd');
INSERT INTO `icon_license` VALUES('design', 'tapr');
INSERT INTO `icon_license` VALUES('file', 'cc0');
INSERT INTO `icon_license` VALUES('file', 'ccby');
INSERT INTO `icon_license` VALUES('file', 'ccbync');
INSERT INTO `icon_license` VALUES('file', 'ccbyncnd');
INSERT INTO `icon_license` VALUES('file', 'ccbyncsa');
INSERT INTO `icon_license` VALUES('file', 'ccbynd');
INSERT INTO `icon_license` VALUES('file', 'ccbysa');
INSERT INTO `icon_license` VALUES('file', 'fal');
INSERT INTO `icon_license` VALUES('manual', 'cc0');
INSERT INTO `icon_license` VALUES('manual', 'ccby');
INSERT INTO `icon_license` VALUES('manual', 'ccbync');
INSERT INTO `icon_license` VALUES('manual', 'ccbyncnd');
INSERT INTO `icon_license` VALUES('manual', 'ccbyncsa');
INSERT INTO `icon_license` VALUES('manual', 'ccbynd');
INSERT INTO `icon_license` VALUES('manual', 'ccbysa');
INSERT INTO `icon_license` VALUES('manual', 'fal');
INSERT INTO `icon_license` VALUES('manual', 'fdl');
INSERT INTO `icon_license` VALUES('manual', 'freebsd');
INSERT INTO `icon_license` VALUES('manual', 'pd');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE `image` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `size` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1986 ;

--
-- Volcar la base de datos para la tabla `image`
--

INSERT INTO `image` VALUES(1, 'avatar.png', 'image/png', 1469);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `info`
--

DROP TABLE IF EXISTS `info`;
CREATE TABLE `info` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `node` varchar(50) NOT NULL,
  `title` tinytext,
  `text` longtext COMMENT 'texto de la entrada',
  `media` tinytext,
  `publish` tinyint(1) NOT NULL DEFAULT '0',
  `order` int(11) DEFAULT '1',
  `legend` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Entradas about' AUTO_INCREMENT=7 ;

--
-- Volcar la base de datos para la tabla `info`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `info_image`
--

DROP TABLE IF EXISTS `info_image`;
CREATE TABLE `info_image` (
  `info` bigint(20) NOT NULL,
  `image` int(10) unsigned NOT NULL,
  PRIMARY KEY (`info`,`image`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `info_image`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `info_lang`
--

DROP TABLE IF EXISTS `info_lang`;
CREATE TABLE `info_lang` (
  `id` bigint(20) unsigned NOT NULL,
  `lang` varchar(2) NOT NULL,
  `title` tinytext,
  `text` longtext,
  `legend` text,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `info_lang`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invest`
--

DROP TABLE IF EXISTS `invest`;
CREATE TABLE `invest` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(50) NOT NULL,
  `project` varchar(50) NOT NULL,
  `account` varchar(256) NOT NULL COMMENT 'Solo para aportes de cash',
  `amount` int(6) NOT NULL,
  `status` int(1) NOT NULL COMMENT '-1 en proceso, 0 pendiente, 1 cobrado, 2 devuelto, 3 pagado al proyecto',
  `anonymous` tinyint(1) DEFAULT NULL,
  `resign` tinyint(1) DEFAULT NULL,
  `invested` date DEFAULT NULL,
  `charged` date DEFAULT NULL,
  `returned` date DEFAULT NULL,
  `preapproval` varchar(256) DEFAULT NULL COMMENT 'PreapprovalKey',
  `payment` varchar(256) DEFAULT NULL COMMENT 'PayKey',
  `transaction` varchar(256) DEFAULT NULL COMMENT 'PaypalId',
  `method` varchar(20) NOT NULL COMMENT 'Metodo de pago',
  `admin` varchar(50) DEFAULT NULL COMMENT 'Admin que creó el aporte manual',
  `campaign` bigint(20) unsigned DEFAULT NULL COMMENT 'campaña de la que forma parte este dinero',
  `datetime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Aportes monetarios a proyectos' AUTO_INCREMENT=1714 ;

--
-- Volcar la base de datos para la tabla `invest`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invest_address`
--

DROP TABLE IF EXISTS `invest_address`;
CREATE TABLE `invest_address` (
  `invest` bigint(20) unsigned NOT NULL,
  `user` varchar(50) NOT NULL,
  `address` tinytext,
  `zipcode` varchar(10) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `nif` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`invest`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Dirección de entrega de recompensa';

--
-- Volcar la base de datos para la tabla `invest_address`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invest_reward`
--

DROP TABLE IF EXISTS `invest_reward`;
CREATE TABLE `invest_reward` (
  `invest` bigint(20) unsigned NOT NULL,
  `reward` bigint(20) unsigned NOT NULL,
  `fulfilled` tinyint(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `invest` (`invest`,`reward`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Recompensas elegidas al aportar';

--
-- Volcar la base de datos para la tabla `invest_reward`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lang`
--

DROP TABLE IF EXISTS `lang`;
CREATE TABLE `lang` (
  `id` varchar(2) NOT NULL COMMENT 'Código ISO-639',
  `name` varchar(20) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0',
  `short` varchar(10) DEFAULT NULL,
  `locale` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Idiomas';

--
-- Volcar la base de datos para la tabla `lang`
--

INSERT INTO `lang` VALUES('ca', 'Català', 1, 'CAT', 'ca_ES');
INSERT INTO `lang` VALUES('de', 'Deutsch', 0, NULL, NULL);
INSERT INTO `lang` VALUES('en', 'English', 1, 'ENG', 'en_GB');
INSERT INTO `lang` VALUES('es', 'Español', 1, 'ES', 'es_ES');
INSERT INTO `lang` VALUES('eu', 'Euskara', 0, 'EUSK', 'eu_ES');
INSERT INTO `lang` VALUES('fr', 'Français', 0, 'FRA', 'fr_FR');
INSERT INTO `lang` VALUES('gl', 'Galego', 0, NULL, NULL);
INSERT INTO `lang` VALUES('it', 'Italiano', 0, 'ITA', 'it_IT');
INSERT INTO `lang` VALUES('pt', 'Português', 0, NULL, NULL);
INSERT INTO `lang` VALUES('nl', 'Dutch', 1, 'NL', 'nl_NL');
INSERT INTO `lang` VALUES('el', 'Greek', 0, 'ελληνικά', 'el_GR');


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `license`
--

DROP TABLE IF EXISTS `license`;
CREATE TABLE `license` (
  `id` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` tinytext,
  `group` varchar(50) DEFAULT NULL COMMENT 'grupo de restriccion de menor a mayor',
  `url` varchar(256) DEFAULT NULL,
  `order` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Licencias de distribucion';

--
-- Volcar la base de datos para la tabla `license`
--

INSERT INTO `license` VALUES('agpl', 'Affero General Public License', 'Licencia pública general de Affero para software libre que corra en servidores de red', '', 'http://www.affero.org/oagf.html', 2);
INSERT INTO `license` VALUES('apache', 'Apache License', 'Licencia Apache de software libre, que no exige que las obras derivadas se distribuyan usando la misma licencia ni como software libre', '', 'http://www.apache.org/licenses/LICENSE-2.0', 10);
INSERT INTO `license` VALUES('balloon', 'Balloon Open Hardware License', 'Licencia para hardware libre de los procesadores Balloon', '', 'http://balloonboard.org/licence.html', 20);
INSERT INTO `license` VALUES('bsd', 'Berkeley Software Distribution', 'Licencia de software libre permisiva, con pocas restricciones y que permite el uso del código fuente en software no libre', 'open', 'http://es.wikipedia.org/wiki/Licencia_BSD', 5);
INSERT INTO `license` VALUES('cc0', 'CC0 Universal (Dominio Público)', 'Licencia Creative Commons de obra dedicada al dominio público, mediante renuncia a todos los derechos de autoría sobre la misma', '', 'http://creativecommons.org/publicdomain/zero/1.0/deed.es', 25);
INSERT INTO `license` VALUES('ccby', 'CC - Reconocimiento', 'Licencia Creative Commons (bienes comunes creativos) con reconocimiento de autoría', 'open', 'http://creativecommons.org/licenses/by/2.0/deed.es_ES', 12);
INSERT INTO `license` VALUES('ccbync', 'CC - Reconocimiento - NoComercial', 'Licencia Creative Commons (bienes comunes creativos) con reconocimiento de autoría y sin que se pueda hacer uso comercial', '', 'http://creativecommons.org/licenses/by-nc/2.0/deed.es_ES', 13);
INSERT INTO `license` VALUES('ccbyncnd', 'CC - Reconocimiento - NoComercial - SinObraDerivada', 'Licencia Creative Commons (bienes comunes creativos) con reconocimiento de autoría, sin que se pueda hacer uso comercial ni otras obras derivadas', '', 'http://creativecommons.org/licenses/by-nc-nd/2.0/deed.es_ES', 15);
INSERT INTO `license` VALUES('ccbyncsa', 'CC - Reconocimiento - NoComercial - CompartirIgual', 'Licencia Creative Commons (bienes comunes creativos) con reconocimiento de autoría, sin que se pueda hacer uso comercial y a compartir en idénticas condiciones', '', 'http://creativecommons.org/licenses/by-nc-sa/3.0/deed.es_ES', 14);
INSERT INTO `license` VALUES('ccbynd', 'CC - Reconocimiento - SinObraDerivada', 'Licencia Creative Commons (bienes comunes creativos) con reconocimiento de autoría, sin que se puedan hacer obras derivadas ', '', 'http://creativecommons.org/licenses/by-nd/2.0/deed.es_ES', 17);
INSERT INTO `license` VALUES('ccbysa', 'CC - Reconocimiento - CompartirIgual', 'Licencia Creative Commons (bienes comunes creativos) con reconocimiento de autoría y a compartir en idénticas condiciones', 'open', 'http://creativecommons.org/licenses/by-sa/2.0/deed.es_ES', 16);
INSERT INTO `license` VALUES('fal', 'Free Art License', 'Licencia de arte libre', '', 'http://artlibre.org/licence/lal/es', 11);
INSERT INTO `license` VALUES('fdl', 'Free Documentation License ', 'Licencia de documentación libre de GNU, pudiendo ser ésta copiada, redistribuida, modificada e incluso vendida siempre y cuando se mantenga bajo los términos de esa misma licencia', 'open', 'http://www.gnu.org/copyleft/fdl.html', 4);
INSERT INTO `license` VALUES('freebsd', 'FreeBSD Documentation License', 'Licencia de documentación libre para el sistema operativo FreeBSD', 'open', 'http://www.freebsd.org/copyright/freebsd-doc-license.html', 6);
INSERT INTO `license` VALUES('gpl', 'General Public License', 'Licencia Pública General de GNU para la libre distribución, modificación y uso de software', 'open', 'http://www.gnu.org/licenses/gpl.html', 1);
INSERT INTO `license` VALUES('gpl2', 'General Public License (v.2)', 'Licencia Pública General de GNU para la libre distribución, modificación y uso de software', 'open', 'http://www.gnu.org/licenses/gpl-2.0.html', 1);
INSERT INTO `license` VALUES('lgpl', 'Lesser General Public License', 'Licencia Pública General Reducida de GNU, para software libre que puede ser utilizado por un programa no-GPL, que a su vez puede ser software libre o no', 'open', 'http://www.gnu.org/copyleft/lesser.html', 3);
INSERT INTO `license` VALUES('mit', 'MIT / X11 License', 'Licencia tanto para software libre como para software no libre, que permite no liberar los cambios realizados sobre el programa original', '', 'http://es.wikipedia.org/wiki/MIT_License', 8);
INSERT INTO `license` VALUES('mpl', 'Mozilla Public License', 'Licencia pública de Mozilla de software libre, que posibilita la reutilización no libre del software, sin restringir la reutilización del código ni el relicenciamiento bajo la misma licencia', '', 'http://www.mozilla.org/MPL/', 7);
INSERT INTO `license` VALUES('odbl', 'Open Database License ', 'Licencia de base de datos abierta, que permite compartir, modificar y utilizar bases de datos en idénticas condiciones', 'open', 'http://www.opendatacommons.org/licenses/odbl/', 22);
INSERT INTO `license` VALUES('odcby', 'Open Data Commons Attribution License', 'Licencia de datos abierta, que permite compartir, modificar y utilizar los datos en idénticas condiciones atribuyendo la fuente original', 'open', 'http://www.opendatacommons.org/licenses/by/', 23);
INSERT INTO `license` VALUES('oshw', 'Open Hardware License', 'Licencia para obras de hardware libre', 'open', 'http://www.tapr.org/OHL', 18);
INSERT INTO `license` VALUES('pd', 'Dominio público', 'La obra puede ser libremente reproducida, distribuida, transmitida, usada, modificada, editada u objeto de cualquier otra forma de explotación para el propósito que sea, comercial o no', '', 'http://creativecommons.org/licenses/publicdomain/deed.es', 24);
INSERT INTO `license` VALUES('php', 'PHP License', 'Licencia bajo la que se publica el lenguaje de programación PHP', '', 'http://www.php.net/license/', 9);
INSERT INTO `license` VALUES('tapr', 'TAPR Noncommercial Hardware License', 'Licencia para obras de hardware libre con limitación en su comercialización ', '', 'http://www.tapr.org/NCL.html', 19);
INSERT INTO `license` VALUES('xoln', 'Procomún de la XOLN', 'Licencia de red abierta, libre y neutral, como acuerdo de interconexión entre iguales promovido por Guifi.net', 'open', 'http://guifi.net/es/ProcomunXOLN', 21);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `license_lang`
--

DROP TABLE IF EXISTS `license_lang`;
CREATE TABLE `license_lang` (
  `id` varchar(50) NOT NULL,
  `lang` varchar(2) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` tinytext,
  `url` varchar(256) DEFAULT NULL,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `license_lang`
--

INSERT INTO `license_lang` VALUES('agpl', 'ca', 'Affero General Public License', 'Llicència pública general d''Affero per a programari lliure que corri en servidors de xarxa', 'http://www.affero.org/oagf.html');
INSERT INTO `license_lang` VALUES('agpl', 'en', 'Affero General Public License', 'Affero General Public License for open networked software', 'http://www.affero.org/oagf.html');
INSERT INTO `license_lang` VALUES('apache', 'ca', 'Apache License', 'Llicencia Apatxe de programari lliure, que no exigeix que les obres derivades es distribueixin usant la mateixa llicència ni com a programari lliure', 'http://www.apache.org/licenses/LICENSE-2.0');
INSERT INTO `license_lang` VALUES('apache', 'en', 'Apache License', 'Apache License for open software, that does not require that derivative works be distributed with the same license, or even as open software', 'http://www.apache.org/licenses/LICENSE-2.0');
INSERT INTO `license_lang` VALUES('balloon', 'ca', 'Balloon Open Hardware License', 'Llicència per a maquinari lliure dels processadors Balloon', 'http://balloonboard.org/licence.html');
INSERT INTO `license_lang` VALUES('balloon', 'en', 'Balloon Open Hardware License', 'License for open Balloon boards', 'http://balloonboard.org/licence.html');
INSERT INTO `license_lang` VALUES('bsd', 'ca', 'Berkeley Software Distribution', 'Llicència de programari lliure permissiva, amb poques restriccions i que permet l''ús del codi font en programari no lliure', 'http://es.wikipedia.org/wiki/Licencia_BSD');
INSERT INTO `license_lang` VALUES('bsd', 'en', 'Berkeley Software Distribution Licenses', 'Permissive free software licenses, with few restrictions, that permit the use of source code in non-free software', 'http://en.wikipedia.org/wiki/BSD_licenses');
INSERT INTO `license_lang` VALUES('cc0', 'ca', 'CC0 Universal (Domini Públic)', 'Llicència Creative Commons d''obra dedicada al domini públic, mitjançant renúncia a tots els drets d''autoria sobre la mateixa', 'http://creativecommons.org/publicdomain/zero/1.0/deed.ca');
INSERT INTO `license_lang` VALUES('cc0', 'en', 'CC0 Universal (Public Domain)', 'Creative Commons License for works dedicated to the public domain, by which all intellectual property rights over a work are waived', 'http://creativecommons.org/publicdomain/zero/1.0/deed.en');
INSERT INTO `license_lang` VALUES('ccby', 'ca', 'CC - Reconeixement', 'Llicència Creative Commons (béns comuns creatius) amb reconeixement d''autoria', 'http://creativecommons.org/licenses/by/2.0/deed.ca');
INSERT INTO `license_lang` VALUES('ccby', 'en', 'CC - Attribution', 'Creative Commons License with attribution', 'http://creativecommons.org/licenses/by/2.0/deed.en');
INSERT INTO `license_lang` VALUES('ccbync', 'ca', 'CC - Reconeixement - NoComercial', 'Llicència Creative Commons (béns comuns creatius) amb reconeixement d''autoria i sense que es pugui fer ús comercial', 'http://creativecommons.org/licenses/by-nc/2.0/deed.ca');
INSERT INTO `license_lang` VALUES('ccbync', 'en', 'CC - Attribution-NonCommercial', 'Creative Commons License with attribution that does not permit commercial use', 'http://creativecommons.org/licenses/by-nc/2.0/deed.en');
INSERT INTO `license_lang` VALUES('ccbyncnd', 'ca', 'CC - Reconeixement - NoComercial - SenseObraDerivada', 'Llicència Creative Commons (béns comuns creatius) amb reconeixement d''autoria, sense que es pugui fer ús comercial ni altres obres derivades', 'http://creativecommons.org/licenses/by-nc-nd/2.0/deed.ca');
INSERT INTO `license_lang` VALUES('ccbyncnd', 'en', 'CC - Attribution  - NonCommercial - NoDerivs', 'Creative Commons License with attribution, that does not allow commercial use nor derivative works', 'http://creativecommons.org/licenses/by-nc-nd/2.0/deed.en');
INSERT INTO `license_lang` VALUES('ccbyncsa', 'ca', 'CC - Reconeixement - NoComercial - CompartirIgual', 'Llicència Creative Commons (béns comuns creatius) amb reconeixement d''autoria, sense que es pugui fer ús comercial i a compartir en idèntiques condicions', 'http://creativecommons.org/licenses/by-nc-sa/3.0/deed.ca');
INSERT INTO `license_lang` VALUES('ccbyncsa', 'en', 'CC - Attribution - NonCommercial - ShareAlike', 'Creative Commons License with attribution, that does not allow commercial use, and only allows sharing under identical licensing conditions', 'http://creativecommons.org/licenses/by-nc-sa/3.0/deed.en');
INSERT INTO `license_lang` VALUES('ccbynd', 'ca', 'CC - Reconeixement - SenseObraDerivada', 'Llicència Creative Commons (béns comuns creatius) amb reconeixement d''autoria, sense que s''en puguin fer obres derivades ', 'http://creativecommons.org/licenses/by-nd/2.0/deed.ca');
INSERT INTO `license_lang` VALUES('ccbynd', 'en', 'CC - Attribution - NoDerivs', 'Creative Commons License with attribution that does not allow derivative works', 'http://creativecommons.org/licenses/by-nd/2.0/deed.en');
INSERT INTO `license_lang` VALUES('ccbysa', 'ca', 'CC - Reconeixement - CompartirIgual', 'Llicència Creative Commons (béns comuns creatius) amb reconeixement d''autoria i a compartir en idèntiques condicions', 'http://creativecommons.org/licenses/by-sa/2.0/deed.ca');
INSERT INTO `license_lang` VALUES('ccbysa', 'en', 'CC - Attribution - ShareAlike', 'Creative Commons License with attribution that only allows sharing under identical licensing conditions', 'http://creativecommons.org/licenses/by-sa/2.0/deed.en');
INSERT INTO `license_lang` VALUES('fal', 'ca', 'Free Art License', 'Llicència d''art lliure', 'http://artlibre.org/licence/lal/es');
INSERT INTO `license_lang` VALUES('fal', 'en', 'Free Art License', 'Free art license', 'http://artlibre.org/licence/lal/en');
INSERT INTO `license_lang` VALUES('fdl', 'ca', 'Free Documentation License ', 'Llicència de documentació lliure de GNU, podent ser aquesta copiada, redistribuïda, modificada i fins i tot venuda sempre que es mantingui sota els termes d''aquesta mateixa llicència', 'http://www.gnu.org/copyleft/fdl.html');
INSERT INTO `license_lang` VALUES('fdl', 'en', 'Free Documentation License ', 'GNU free documentation license, which can be copied, redistributed, modified and even sold, as long as the original terms of this same license are maintained.', 'http://www.gnu.org/copyleft/fdl.html');
INSERT INTO `license_lang` VALUES('freebsd', 'ca', 'FreeBSD Documentation License', 'Llicència de documentació lliure per al sistema operatiu FreeBSD', 'http://www.freebsd.org/copyright/freebsd-doc-license.html');
INSERT INTO `license_lang` VALUES('freebsd', 'en', 'FreeBSD Documentation License', 'Free Documentation License for the FreeBSD operating system', 'http://www.freebsd.org/copyright/freebsd-doc-license.html');
INSERT INTO `license_lang` VALUES('gpl', 'ca', 'General Public License', 'Llicència Pública General de GNU per a la lliure distribució, modificació i ús de programari', 'http://www.gnu.org/licenses/gpl.html');
INSERT INTO `license_lang` VALUES('gpl', 'en', 'General Public License', 'GNU General Public License for the free distribution, modification, and use of software', 'http://www.gnu.org/licenses/gpl.html');
INSERT INTO `license_lang` VALUES('gpl2', 'ca', 'General Public License (v.2)', 'Llicència Pública General de GNU per a la lliure distribució, modificació i ús de programari', 'http://www.gnu.org/licenses/gpl-2.0.html');
INSERT INTO `license_lang` VALUES('gpl2', 'en', 'General Public License (v.2)', 'GNU General Public License for the free distribution, modification, and use of software', 'http://www.gnu.org/licenses/gpl-2.0.html');
INSERT INTO `license_lang` VALUES('lgpl', 'ca', 'Lesser General Public License', 'Llicència Pública General Reduïda de GNU, per a programari lliure que pot ser utilitzat per un programa no-GPL, que al seu torn pot ser programari lliure o no', 'http://www.gnu.org/copyleft/lesser.html');
INSERT INTO `license_lang` VALUES('lgpl', 'en', 'Lesser General Public License', 'GNU Lesser General Public License for free software that can be used by a non-GPL program, which in turn can be free software or not. ', 'http://www.gnu.org/copyleft/lesser.html');
INSERT INTO `license_lang` VALUES('mit', 'ca', 'MIT / X11 License', 'Llicència tant per a programari lliure com per a programari no lliure, que permet no alliberar els canvis realitzats sobre el programa original', 'http://ca.wikipedia.org/wiki/Llic%C3%A8ncia_X11');
INSERT INTO `license_lang` VALUES('mit', 'en', 'MIT / X11 License', 'License both for open and closed software, that allows changes made to the original program to be protected', 'http://es.wikipedia.org/wiki/MIT_License');
INSERT INTO `license_lang` VALUES('mpl', 'ca', 'Mozilla Public License', 'Llicència pública de Mozilla de programari lliure, que possibilita la reutilització no lliure del programari, sense restringir-ne la reutilització del codi ni el rellicenciament sota la mateixa llicència', 'http://www.mozilla.org/MPL/');
INSERT INTO `license_lang` VALUES('mpl', 'en', 'Mozilla Public License', 'Mozilla Public License for open software that makes possible the non-open reuse of software, without restricting the reuse of the code or the relicensing under the same license. ', 'http://www.mozilla.org/MPL/');
INSERT INTO `license_lang` VALUES('odbl', 'ca', 'Open Database License ', 'Llicència de base de dades oberta, que permet compartir, modificar i utilitzar bases de dades en idèntiques condicions', 'http://www.opendatacommons.org/licenses/odbl/');
INSERT INTO `license_lang` VALUES('odbl', 'en', 'Open Database License ', 'Open Database License that allows for sharing, modifying, and using databases in identical conditions', 'http://www.opendatacommons.org/licenses/odbl/');
INSERT INTO `license_lang` VALUES('odcby', 'ca', 'Open Data Commons Attribution License', 'Llicència de dades oberta, que permet compartir, modificar i utilitzar les dades en idèntiques condicions atribuint-hi la font original', 'http://www.opendatacommons.org/licenses/by/');
INSERT INTO `license_lang` VALUES('odcby', 'en', 'Open Data Commons Attribution License', 'Open data license that allows for sharing, modifying and using data under identical conditions, as long as attribution is given for the original source', 'http://www.opendatacommons.org/licenses/by/');
INSERT INTO `license_lang` VALUES('oshw', 'ca', 'Open Hardware License', 'Llicència per a obres de maquinari lliure', 'http://www.tapr.org/OHL');
INSERT INTO `license_lang` VALUES('oshw', 'en', 'Open Hardware License', 'Open Hardware License', 'http://www.tapr.org/OHL');
INSERT INTO `license_lang` VALUES('pd', 'ca', 'Domini públic', 'L''obra pot ser lliurement reproduïda, distribuïda, transmesa, usada, modificada, editada o objecte de qualsevol altra forma d''explotació per al propòsit que sigui, comercial o no', 'http://creativecommons.org/licenses/publicdomain/deed.ca');
INSERT INTO `license_lang` VALUES('pd', 'en', 'Public Domain', 'The work may be freely reproduced, distributed, transmitted, used, modified, edited, or subject to any other form of exploitation for any commerical or non-commercial use.', 'http://creativecommons.org/licenses/publicdomain/deed.en');
INSERT INTO `license_lang` VALUES('php', 'ca', 'PHP License', 'Llicència sota la que es publica el llenguatge de programació PHP', 'http://www.php.net/license/');
INSERT INTO `license_lang` VALUES('php', 'en', 'PHP License', 'License under which the PHP programming language is published', 'http://www.php.net/license/');
INSERT INTO `license_lang` VALUES('tapr', 'ca', 'TAPR Noncommercial Hardware License', 'Llicència per a obres de maquinari lliure amb limitació en la seva comercialització ', 'http://www.tapr.org/NCL.html');
INSERT INTO `license_lang` VALUES('tapr', 'en', 'TAPR Noncommercial Hardware License', 'TAPR Noncommercial Hardware License', 'http://www.tapr.org/NCL.html');
INSERT INTO `license_lang` VALUES('xoln', 'ca', 'Procomú de la XOLN', 'Llicència de xarxa oberta, lliure i neutral, com a acord d''interconnexió entre iguals promogut per Guifi.net', 'http://guifi.net/es/ProcomunXOLN');
INSERT INTO `license_lang` VALUES('xoln', 'en', 'XOLN Common Good License', 'License for an open, free, neutral network, as an agreement of interconnection among equals, promoted by Guifi.net ', 'http://guifi.net/es/ProcomunXOLN');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mail`
--

DROP TABLE IF EXISTS `mail`;
CREATE TABLE `mail` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` tinytext NOT NULL,
  `html` longtext NOT NULL,
  `template` int(20) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Contenido enviado por email para el -si no ves-' AUTO_INCREMENT=10604 ;

--
-- Volcar la base de datos para la tabla `mail`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(50) NOT NULL,
  `project` varchar(50) NOT NULL,
  `thread` bigint(20) unsigned DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `message` text NOT NULL,
  `blocked` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'No se puede modificar ni borrar',
  `closed` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'No se puede responder',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Mensajes de usuarios en proyecto' AUTO_INCREMENT=812 ;

--
-- Volcar la base de datos para la tabla `message`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `message_lang`
--

DROP TABLE IF EXISTS `message_lang`;
CREATE TABLE `message_lang` (
  `id` int(20) NOT NULL,
  `lang` varchar(2) NOT NULL,
  `message` text,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `message_lang`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` tinytext NOT NULL,
  `description` text COMMENT 'Entradilla',
  `url` tinytext NOT NULL,
  `order` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Noticias en la cabecera' AUTO_INCREMENT=20 ;

--
-- Volcar la base de datos para la tabla `news`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `news_lang`
--

DROP TABLE IF EXISTS `news_lang`;
CREATE TABLE `news_lang` (
  `id` bigint(20) unsigned NOT NULL,
  `lang` varchar(2) NOT NULL,
  `title` tinytext,
  `description` text,
  `url` tinytext,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `news_lang`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `node`
--

DROP TABLE IF EXISTS `node`;
CREATE TABLE `node` (
  `id` varchar(50) NOT NULL,
  `name` varchar(256) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Nodos';

--
-- Volcar la base de datos para la tabla `node`
--

INSERT INTO `node` VALUES('goteo', 'Master node', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `page`
--

DROP TABLE IF EXISTS `page`;
CREATE TABLE `page` (
  `id` varchar(50) NOT NULL,
  `name` tinytext NOT NULL,
  `description` text,
  `url` tinytext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Páginas institucionales';

--
-- Volcar la base de datos para la tabla `page`
--

INSERT INTO `page` VALUES('about', 'Sobre Goteo', 'Sobre Goteo', '/about');
INSERT INTO `page` VALUES('campaign', 'Servicio Campañas', 'Si tu proyecto necesita una <span class="greenblue"> campaña a medida</span>, hablemos ', '/service/campaign');
INSERT INTO `page` VALUES('community', 'Comunidad Goteo', 'Bienvenido/a al <span class="greenblue">río</span>. Desde aquí se puede seguir cómo fluye la actividad de Goteo.', '/community');
INSERT INTO `page` VALUES('consulting', 'Servicio Consultoría', 'La Fundación <span class="greenblue">Fuentes Abiertas</span> te enseña los beneficios de las nuevas tecnologías distribuidas', '/service/consulting');
INSERT INTO `page` VALUES('contact', 'Contacto', 'Pagina de contacto', '/contact');
INSERT INTO `page` VALUES('credits', 'Agraïments', ' <span class="greenblue">Recolzaments i col·laboracions</span>', '/about/credits');
INSERT INTO `page` VALUES('dashboard', 'Bienvenida', 'Texto de bienvenida en el dashboard', '/dashboard');
INSERT INTO `page` VALUES('howto', 'Instrucciones para ser productor/a', '4 condiciones y <br /><span class="red">2 requisitos</span> para proponer un proyecto', '/about/howto');
INSERT INTO `page` VALUES('legal', 'Legales', 'Términos legales de Goteo', '/about/legal');
INSERT INTO `page` VALUES('news', 'Noticias', 'Pagina de noticias', '/news');
INSERT INTO `page` VALUES('press', 'Press kit', 'Kit de prensa', '/press');
INSERT INTO `page` VALUES('privacy', 'Política de privacidad', 'Política de privacidad', '/legal/privacy');
INSERT INTO `page` VALUES('resources', 'FEEDER CAPITAL', 'A <span class="greenblue">Feeder Capital</span> social investment market with contributions from public institutions, business and other private institutions, and individuals.', '/service/resources');
INSERT INTO `page` VALUES('service', 'Servicios', 'General de servicios', '/service');
INSERT INTO `page` VALUES('team', 'Equipo', 'Sobre la gente que impulsa Goteo', '/about/team');
INSERT INTO `page` VALUES('terms', 'Condiciones de uso', 'Reglas del juego para el <span class="greenblue">buen funcionamiento</span> <br>de la <span class="greenblue">comunidad Goteo</span>', '/legal/terms');
INSERT INTO `page` VALUES('workshop', 'Tallers de finançament col·lectiu', 'Apliquem pràctiques d''<span class="greenblue">innovació social</span>.\r\n<br />Com obrir <span class="greenblue">i compartir recursos</span> al voltant del crowdfunding', '/service/workshop');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `page_lang`
--

DROP TABLE IF EXISTS `page_lang`;
CREATE TABLE `page_lang` (
  `id` varchar(50) NOT NULL,
  `lang` varchar(2) NOT NULL,
  `name` tinytext NOT NULL,
  `description` text,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `page_lang`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `page_node`
--

DROP TABLE IF EXISTS `page_node`;
CREATE TABLE `page_node` (
  `page` varchar(50) NOT NULL,
  `node` varchar(50) NOT NULL,
  `lang` varchar(2) NOT NULL,
  `content` longtext,
  UNIQUE KEY `page` (`page`,`node`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Contenidos de las paginas';

--
-- Volcar la base de datos para la tabla `page_node`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `post`
--

DROP TABLE IF EXISTS `post`;
CREATE TABLE `post` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `blog` bigint(20) unsigned NOT NULL,
  `title` tinytext,
  `text` longtext COMMENT 'texto de la entrada',
  `media` tinytext,
  `image` int(10) DEFAULT NULL,
  `date` date NOT NULL COMMENT 'fehca de publicacion',
  `order` int(11) DEFAULT '1',
  `allow` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Permite comentarios',
  `home` tinyint(1) DEFAULT '0' COMMENT 'para los de portada',
  `footer` tinyint(1) DEFAULT '0' COMMENT 'Para los del footer',
  `publish` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Publicado',
  `legend` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Entradas para la portada' AUTO_INCREMENT=168 ;

--
-- Volcar la base de datos para la tabla `post`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `post_image`
--

DROP TABLE IF EXISTS `post_image`;
CREATE TABLE `post_image` (
  `post` bigint(20) NOT NULL,
  `image` int(10) unsigned NOT NULL,
  PRIMARY KEY (`post`,`image`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `post_image`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `post_lang`
--

DROP TABLE IF EXISTS `post_lang`;
CREATE TABLE `post_lang` (
  `id` bigint(20) unsigned NOT NULL,
  `lang` varchar(2) NOT NULL,
  `title` tinytext,
  `text` longtext,
  `legend` text,
  `media` tinytext,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `post_lang`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `post_tag`
--

DROP TABLE IF EXISTS `post_tag`;
CREATE TABLE `post_tag` (
  `post` bigint(20) unsigned NOT NULL,
  `tag` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`post`,`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tags de las entradas';

--
-- Volcar la base de datos para la tabla `post_tag`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `project`
--

DROP TABLE IF EXISTS `project`;
CREATE TABLE `project` (
  `id` varchar(50) NOT NULL,
  `name` tinytext,
  `subtitle` tinytext,
  `lang` varchar(2) DEFAULT 'es',
  `status` int(1) NOT NULL,
  `translate` int(1) NOT NULL DEFAULT '0',
  `progress` int(3) NOT NULL,
  `owner` varchar(50) NOT NULL COMMENT 'usuario que lo ha creado',
  `node` varchar(50) NOT NULL COMMENT 'nodo en el que se ha creado',
  `amount` int(6) DEFAULT NULL COMMENT 'acumulado actualmente',
  `days` int(3) NOT NULL DEFAULT '0' COMMENT 'Dias restantes',
  `created` date DEFAULT NULL,
  `updated` date DEFAULT NULL,
  `published` date DEFAULT NULL,
  `success` date DEFAULT NULL,
  `closed` date DEFAULT NULL,
  `passed` date DEFAULT NULL,
  `contract_name` varchar(255) DEFAULT NULL,
  `contract_nif` varchar(15) DEFAULT NULL COMMENT 'Guardar sin espacios ni puntos ni guiones',
  `phone` varchar(20) DEFAULT NULL COMMENT 'guardar talcual',
  `contract_email` varchar(255) DEFAULT NULL,
  `address` tinytext,
  `zipcode` varchar(10) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `image` varchar(256) DEFAULT NULL,
  `description` text,
  `motivation` text,
  `video` varchar(256) DEFAULT NULL,
  `video_usubs` int(1) NOT NULL DEFAULT '0',
  `about` text,
  `goal` text,
  `related` text,
  `category` varchar(50) DEFAULT NULL,
  `keywords` tinytext COMMENT 'Separadas por comas',
  `media` varchar(256) DEFAULT NULL,
  `media_usubs` int(1) NOT NULL DEFAULT '0',
  `currently` int(1) DEFAULT NULL,
  `project_location` varchar(256) DEFAULT NULL,
  `scope` int(1) DEFAULT NULL COMMENT 'Ambito de alcance',
  `resource` text,
  `comment` text COMMENT 'Comentario para los admin',
  `contract_entity` int(1) NOT NULL DEFAULT '0',
  `contract_birthdate` date DEFAULT NULL,
  `entity_office` varchar(255) DEFAULT NULL COMMENT 'Cargo del responsable',
  `entity_name` varchar(255) DEFAULT NULL,
  `entity_cif` varchar(10) DEFAULT NULL COMMENT 'Guardar sin espacios ni puntos ni guiones',
  `post_address` tinytext,
  `secondary_address` int(11) NOT NULL DEFAULT '0',
  `post_zipcode` varchar(10) DEFAULT NULL,
  `post_location` varchar(255) DEFAULT NULL,
  `post_country` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `owner` (`owner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Proyectos de la plataforma';

--
-- Volcar la base de datos para la tabla `project`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `project_account`
--

DROP TABLE IF EXISTS `project_account`;
CREATE TABLE `project_account` (
  `project` varchar(50) NOT NULL,
  `bank` tinytext,
  `paypal` tinytext,
  PRIMARY KEY (`project`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Cuentas bancarias de proyecto';

--
-- Volcar la base de datos para la tabla `project_account`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `project_category`
--

DROP TABLE IF EXISTS `project_category`;
CREATE TABLE `project_category` (
  `project` varchar(50) NOT NULL,
  `category` int(12) NOT NULL,
  UNIQUE KEY `project_category` (`project`,`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Categorias de los proyectos';

--
-- Volcar la base de datos para la tabla `project_category`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `project_image`
--

DROP TABLE IF EXISTS `project_image`;
CREATE TABLE `project_image` (
  `project` varchar(50) NOT NULL,
  `image` int(10) unsigned NOT NULL,
  PRIMARY KEY (`project`,`image`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `project_image`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `project_lang`
--

DROP TABLE IF EXISTS `project_lang`;
CREATE TABLE `project_lang` (
  `id` varchar(50) NOT NULL,
  `lang` varchar(2) NOT NULL,
  `description` text,
  `motivation` text,
  `video` varchar(256) DEFAULT NULL,
  `about` text,
  `goal` text,
  `related` text,
  `keywords` tinytext,
  `media` varchar(255) DEFAULT NULL,
  `subtitle` tinytext,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `project_lang`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promote`
--

DROP TABLE IF EXISTS `promote`;
CREATE TABLE `promote` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `node` varchar(50) NOT NULL,
  `project` varchar(50) NOT NULL,
  `title` tinytext,
  `description` text,
  `order` smallint(5) unsigned NOT NULL DEFAULT '1',
  `active` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_node` (`node`,`project`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Proyectos destacados' AUTO_INCREMENT=46 ;

--
-- Volcar la base de datos para la tabla `promote`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promote_lang`
--

DROP TABLE IF EXISTS `promote_lang`;
CREATE TABLE `promote_lang` (
  `id` bigint(20) unsigned NOT NULL,
  `lang` varchar(2) NOT NULL,
  `title` tinytext,
  `description` text,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `promote_lang`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purpose`
--

DROP TABLE IF EXISTS `purpose`;
CREATE TABLE `purpose` (
  `text` varchar(50) NOT NULL,
  `purpose` text NOT NULL,
  `html` tinyint(1) DEFAULT NULL COMMENT 'Si el texto lleva formato html',
  `group` varchar(50) NOT NULL DEFAULT 'general' COMMENT 'Agrupacion de uso',
  PRIMARY KEY (`text`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Explicación del propósito de los textos';

--
-- Volcar la base de datos para la tabla `purpose`
--

INSERT INTO `purpose` VALUES('blog-coments-header', 'Comentarios', NULL, 'blog');
INSERT INTO `purpose` VALUES('blog-comments', 'Comentarios', NULL, 'general');
INSERT INTO `purpose` VALUES('blog-comments_no_allowed', 'No se permiten comentarios en  esta entrada', NULL, 'blog');
INSERT INTO `purpose` VALUES('blog-comments_no_comments', 'No hay comentarios en esta entrada', NULL, 'blog');
INSERT INTO `purpose` VALUES('blog-main-header', 'Blog de Goteo', NULL, 'general');
INSERT INTO `purpose` VALUES('blog-no_comments', 'Sin comentarios', NULL, 'blog');
INSERT INTO `purpose` VALUES('blog-no_posts', 'No se ha publicado ninguna entrada ', NULL, 'blog');
INSERT INTO `purpose` VALUES('blog-send_comment-button', 'Enviar', NULL, 'blog');
INSERT INTO `purpose` VALUES('blog-send_comment-header', 'Escribe tu comentario', NULL, 'blog');
INSERT INTO `purpose` VALUES('blog-side-last_comments', 'Últimos comentarios', NULL, 'blog');
INSERT INTO `purpose` VALUES('blog-side-last_posts', 'Últimas entradas', NULL, 'blog');
INSERT INTO `purpose` VALUES('blog-side-tags', 'Categorías', NULL, 'blog');
INSERT INTO `purpose` VALUES('community-menu-activity', 'Actividad', NULL, 'menu');
INSERT INTO `purpose` VALUES('community-menu-main', 'Comunidad', NULL, 'menu');
INSERT INTO `purpose` VALUES('community-menu-sharemates', 'Compartiendo', NULL, 'menu');
INSERT INTO `purpose` VALUES('contact-email-field', 'Email', NULL, 'contact');
INSERT INTO `purpose` VALUES('contact-message-field', 'Mensaje', NULL, 'contact');
INSERT INTO `purpose` VALUES('contact-send_message-button', 'Enviar', NULL, 'contact');
INSERT INTO `purpose` VALUES('contact-send_message-header', 'Envíanos un mensaje', NULL, 'contact');
INSERT INTO `purpose` VALUES('contact-subject-field', 'Asunto', NULL, 'contact');
INSERT INTO `purpose` VALUES('cost-type-lend', 'Préstamo', NULL, 'costs');
INSERT INTO `purpose` VALUES('cost-type-material', 'Material', NULL, 'costs');
INSERT INTO `purpose` VALUES('cost-type-structure', 'Infraestructura', NULL, 'costs');
INSERT INTO `purpose` VALUES('cost-type-task', 'Tarea', NULL, 'costs');
INSERT INTO `purpose` VALUES('costs-field-amount', 'Valor', NULL, 'costs');
INSERT INTO `purpose` VALUES('costs-field-cost', 'Coste', NULL, 'costs');
INSERT INTO `purpose` VALUES('costs-field-dates', 'Fechas', NULL, 'costs');
INSERT INTO `purpose` VALUES('costs-field-date_from', 'Desde', NULL, 'costs');
INSERT INTO `purpose` VALUES('costs-field-date_until', 'Hasta', NULL, 'costs');
INSERT INTO `purpose` VALUES('costs-field-description', 'Descripción', NULL, 'costs');
INSERT INTO `purpose` VALUES('costs-field-required_cost', 'Este coste es', NULL, 'costs');
INSERT INTO `purpose` VALUES('costs-field-required_cost-no', 'Adicional', NULL, 'costs');
INSERT INTO `purpose` VALUES('costs-field-required_cost-yes', 'Imprescindible', NULL, 'costs');
INSERT INTO `purpose` VALUES('costs-field-resoure', 'Otros recursos', NULL, 'costs');
INSERT INTO `purpose` VALUES('costs-field-schedule', 'Agenda de trabajo', NULL, 'costs');
INSERT INTO `purpose` VALUES('costs-field-type', 'Tipo', NULL, 'costs');
INSERT INTO `purpose` VALUES('costs-fields-main-title', 'Desglose de costes', NULL, 'costs');
INSERT INTO `purpose` VALUES('costs-fields-metter-title', 'Visualización de costes', NULL, 'costs');
INSERT INTO `purpose` VALUES('costs-fields-resources-title', 'Recurso', NULL, 'costs');
INSERT INTO `purpose` VALUES('costs-main-header', 'Aspectos económicos', NULL, 'costs');
INSERT INTO `purpose` VALUES('criteria-owner-section-header', 'Respecto al creador/equipo', NULL, 'review');
INSERT INTO `purpose` VALUES('criteria-project-section-header', 'Respecto al proyecto', NULL, 'review');
INSERT INTO `purpose` VALUES('criteria-reward-section-header', 'Respecto al retorno', NULL, 'review');
INSERT INTO `purpose` VALUES('dashboard-embed_code', 'CÓDIGO DIFUSIÓN SIMPLE', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-embed_code_investor', 'CÓDIGO CON IMAGEN DE COFINANCIADOR', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-header-main', 'Mi panel', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-investors-mail-fail', 'Fallo al enviar el mensaje a %s: %s', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-investors-mail-nowho', 'No se han encontrado destinatarios', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-investors-mail-sended', 'Mensaje enviado correctamente a %s: %s', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-investors-mail-sendto', 'Enviado a %s de tus cofinanciadores:', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-investors-mail-text-required', 'Escribe el mensaje', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-menu-activity', 'Mi actividad', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-menu-activity-spread', 'Difusión', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-menu-activity-summary', 'Resumen', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-menu-activity-wall', 'Mi muro', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-menu-admin_board', 'Administración', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-menu-main', 'Mi panel', NULL, 'menu');
INSERT INTO `purpose` VALUES('dashboard-menu-profile', 'Mi perfil', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-menu-profile-access', 'Datos de acceso', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-menu-profile-personal', 'Datos personales', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-menu-profile-preferences', 'Preferencias', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-menu-profile-profile', 'Editar perfil', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-menu-profile-public', 'Perfil público', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-menu-projects', 'Mis proyectos', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-menu-projects-contract', 'Cuenta bancaria', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-menu-projects-preview', 'Página pública', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-menu-projects-rewards', 'Gestión cofinanciadores', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-menu-projects-summary', 'Resumen', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-menu-projects-supports', 'Colaboraciones', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-menu-projects-updates', 'Novedades', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-menu-projects-widgets', 'Widget', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-menu-review_board', 'Revisión', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-menu-translates', 'Mis Traducciones', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-menu-translate_board', 'Traducción', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-password-recover-advice', 'Asegúrate de reestablecer tu contraseña', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-project-blog-fail', 'Contacta con nosotr*s', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-project-blog-inactive', 'Lo sentimos, la publicación de novedades en este proyecto está desactivada', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-project-blog-wrongstatus', 'Lo sentimos, aún no se pueden publicar novedades en este proyecto...', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-project-delete_alert', '¿Seguro que deseas eliminar absoluta y definitivamente este proyecto?', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-project-updates-deleted', 'Entrada eliminada', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-project-updates-delete_fail', 'Error al eliminar la entrada', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-project-updates-fail', 'Ha habido algun problema al guardar los datos', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-project-updates-inserted', 'Se ha añadido una nueva entrada', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-project-updates-noblog', 'No se ha encontrado ningún blog para este proyecto', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-project-updates-nopost', 'No se ha encontrado la entrada', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-project-updates-postcorrupt', 'La entrada está corrupta, contacta con nosotros', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('dashboard-project-updates-saved', 'La entrada se ha actualizado correctamente', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('discover-banner-header', 'Por categoría, lugar o retorno,<br /><span class="red">encuentra el proyecto</span> con el que más te identificas', 1, 'banners');
INSERT INTO `purpose` VALUES('discover-group-all-header', 'En campaña', NULL, 'discover');
INSERT INTO `purpose` VALUES('discover-group-archive-header', 'Archivados', NULL, 'discover');
INSERT INTO `purpose` VALUES('discover-group-outdate-header', 'A punto de ser archivado', NULL, 'discover');
INSERT INTO `purpose` VALUES('discover-group-popular-header', 'Más populares', NULL, 'discover');
INSERT INTO `purpose` VALUES('discover-group-recent-header', 'Publicados recientemente', NULL, 'discover');
INSERT INTO `purpose` VALUES('discover-group-success-header', 'Exitosos', NULL, 'discover');
INSERT INTO `purpose` VALUES('discover-results-empty', 'No hemos encontrado ningún proyecto que cumpla los criterios de búsqueda', NULL, 'discover');
INSERT INTO `purpose` VALUES('discover-results-header', 'Resultado de búsqueda', NULL, 'discover');
INSERT INTO `purpose` VALUES('discover-searcher-button', 'Buscar', NULL, 'discover');
INSERT INTO `purpose` VALUES('discover-searcher-bycategory-all', 'TODAS', NULL, 'discover');
INSERT INTO `purpose` VALUES('discover-searcher-bycategory-header', 'Por categoría:', NULL, 'discover');
INSERT INTO `purpose` VALUES('discover-searcher-bycontent-header', 'Por contenido:', NULL, 'discover');
INSERT INTO `purpose` VALUES('discover-searcher-bylocation-all', 'TODOS', NULL, 'discover');
INSERT INTO `purpose` VALUES('discover-searcher-bylocation-header', 'Por lugar:', NULL, 'discover');
INSERT INTO `purpose` VALUES('discover-searcher-byreward-all', 'TODOS', NULL, 'discover');
INSERT INTO `purpose` VALUES('discover-searcher-byreward-header', 'Por retorno:', NULL, 'discover');
INSERT INTO `purpose` VALUES('discover-searcher-header', 'Busca un proyecto', NULL, 'discover');
INSERT INTO `purpose` VALUES('error-contact-email-empty', 'No has añadido tu email', NULL, 'contact');
INSERT INTO `purpose` VALUES('error-contact-email-invalid', 'El email que has escrito no es válido', NULL, 'general');
INSERT INTO `purpose` VALUES('error-contact-message-empty', 'No has escrito ningún mensaje', NULL, 'contact');
INSERT INTO `purpose` VALUES('error-contact-subject-empty', 'No has escrito el asunto', NULL, 'contact');
INSERT INTO `purpose` VALUES('error-image-name', 'Error en el nombre del archivo', NULL, 'general');
INSERT INTO `purpose` VALUES('error-image-size', 'Error en el tamaño del archivo', NULL, 'general');
INSERT INTO `purpose` VALUES('error-image-size-too-large', 'La imagen es demasiado grande', NULL, 'general');
INSERT INTO `purpose` VALUES('error-image-tmp', 'Error al cargar el archivo', NULL, 'general');
INSERT INTO `purpose` VALUES('error-image-type', 'Solo se permiten imágenes jpg, png y gif', NULL, 'general');
INSERT INTO `purpose` VALUES('error-image-type-not-allowed', 'Solo se permiten archivos de imagen tipo  .png  .jpg  .gif', NULL, 'general');
INSERT INTO `purpose` VALUES('error-register-email', 'La dirección de correo es obligatoria', NULL, 'register');
INSERT INTO `purpose` VALUES('error-register-email-confirm', 'La comprobación de email no coincide', NULL, 'register');
INSERT INTO `purpose` VALUES('error-register-email-exists', 'La dirección de correo facilitada corresponde a un usuario ya registrado', NULL, 'general');
INSERT INTO `purpose` VALUES('error-register-invalid-password', 'La contraseña no es válida', NULL, 'register');
INSERT INTO `purpose` VALUES('error-register-password-confirm', 'La comprobación de contraseña no coincide', NULL, 'register');
INSERT INTO `purpose` VALUES('error-register-pasword', 'La contraseña no puede estar vacía', NULL, 'register');
INSERT INTO `purpose` VALUES('error-register-pasword-empty', 'No has puesto contraseña', NULL, 'general');
INSERT INTO `purpose` VALUES('error-register-short-password', 'La contraseña debe contener un mínimo de 8 caracteres', NULL, 'register');
INSERT INTO `purpose` VALUES('error-register-user-exists', 'Este nombre de usuario ya está registrado.', NULL, 'register');
INSERT INTO `purpose` VALUES('error-register-userid', 'Es obligatorio escribir un nombre de acceso', NULL, 'login');
INSERT INTO `purpose` VALUES('error-register-username', 'El nombre público es obligatorio.', NULL, 'register');
INSERT INTO `purpose` VALUES('error-user-email-confirm', 'La confirmación de correo electrónico no coincide', NULL, 'general');
INSERT INTO `purpose` VALUES('error-user-email-empty', 'No puedes dejar el campo de email vacío', NULL, 'general');
INSERT INTO `purpose` VALUES('error-user-email-exists', 'Ya hay un usuario registrado con este email', NULL, 'general');
INSERT INTO `purpose` VALUES('error-user-email-invalid', 'El email que has puesto no es válido', NULL, 'general');
INSERT INTO `purpose` VALUES('error-user-email-token-invalid', 'El código no es correcto', NULL, 'general');
INSERT INTO `purpose` VALUES('error-user-password-confirm', 'La confirmación de contraseña no coincide', NULL, 'general');
INSERT INTO `purpose` VALUES('error-user-password-empty', 'No has puesto la contraseña', NULL, 'general');
INSERT INTO `purpose` VALUES('error-user-password-invalid', 'La contraseña es demasiado corta', NULL, 'general');
INSERT INTO `purpose` VALUES('error-user-wrong-password', 'La contraseña no es correcta', NULL, 'general');
INSERT INTO `purpose` VALUES('explain-project-progress', 'Este gráfico explica de un modo visual el nivel de datos que has introducido junto con una evaluación básica que hace el sistema. Para poder enviar el proyecto tienes que superar el 80%. Los criterios que hacen subir este "termómetro"  tienen que ver con la información relevante que facilitas, los media, imágenes y links que introduces, si eliges una licencia más abierta que otra, la coherencia de tu presupuesto respecto a las tareas a desarrollar, etc. No pierdas de vista los consejos de la columna derecha, que guían durante todo el proceso.', NULL, 'general');
INSERT INTO `purpose` VALUES('faq-ask-question', '¿No has podido resolver tu duda?\r\n Envía un mensaje con tu pregunta.', NULL, 'faq');
INSERT INTO `purpose` VALUES('faq-investors-section-header', 'Para cofinanciador@s', NULL, 'general');
INSERT INTO `purpose` VALUES('faq-main-section-header', 'Una aproximación a Goteo', NULL, 'faq');
INSERT INTO `purpose` VALUES('faq-nodes-section-header', 'Sobre nodos locales', NULL, 'faq');
INSERT INTO `purpose` VALUES('faq-project-section-header', 'Sobre los proyectos', NULL, 'faq');
INSERT INTO `purpose` VALUES('faq-sponsor-section-header', 'Para impulsor@s', NULL, 'general');
INSERT INTO `purpose` VALUES('fatal-error-project', 'Este proyecto que buscas... <span class="red">no existe :(</span>', 1, 'error');
INSERT INTO `purpose` VALUES('fatal-error-teapot', '<span class="greenblue">How embarassing...</span> unexpected Error occurred', 1, 'error');
INSERT INTO `purpose` VALUES('fatal-error-user', 'Este usuario que buscas... <span class="red">no existe :(</span>', 1, 'error');
INSERT INTO `purpose` VALUES('feed-blog-comment', 'Ha escrito un <span class="green">Comentario</span> en la entrada "%s" del blog de %s', 1, 'feed');
INSERT INTO `purpose` VALUES('feed-head-community', 'Comunidad', NULL, 'community');
INSERT INTO `purpose` VALUES('feed-head-goteo', 'Goteo', NULL, 'community');
INSERT INTO `purpose` VALUES('feed-head-projects', 'Proyectos', NULL, 'community');
INSERT INTO `purpose` VALUES('feed-header', 'Actividad reciente', NULL, 'community');
INSERT INTO `purpose` VALUES('feed-invest', 'Ha aportado %s al proyecto %s', 1, 'feed');
INSERT INTO `purpose` VALUES('feed-messages-new_thread', 'Ha abierto un tema en %s del proyecto %s', 1, 'feed');
INSERT INTO `purpose` VALUES('feed-messages-response', 'Ha respondido en %s del proyecto %s', 1, 'feed');
INSERT INTO `purpose` VALUES('feed-new_project', '<span class="red">Nuevo proyecto en Goteo</span>, desde ahora tenemos 40 días para apoyarlo!', 1, 'feed');
INSERT INTO `purpose` VALUES('feed-new_support', 'Ha publicado una nueva <span class="green">Colaboración</span> en el proyecto %s, con el título "%s"', 1, 'feed');
INSERT INTO `purpose` VALUES('feed-new_update', '%s ha publicado un nuevo post en %s sobre el proyecto %s', 1, 'feed');
INSERT INTO `purpose` VALUES('feed-new_user', 'Nuevo usuario en Goteo %s', 1, 'feed');
INSERT INTO `purpose` VALUES('feed-project_fail', 'El proyecto %s ha sido <span class="red">archivado sin éxito</span> obteniendo <span class="violet">%s € (%s &#37;) de aportes sobre mínimo</span>', 1, 'feed');
INSERT INTO `purpose` VALUES('feed-project_finish', 'El proyecto %s ha <span class="red">completado la segunda ronda</span> obteniendo <span class="violet">%s € (%s &#37;) de aportes sobre mínimo</span>', 1, 'feed');
INSERT INTO `purpose` VALUES('feed-project_goon', 'El proyecto %s <span class="red">continúa en campaña</span> en segunda ronda obteniendo <span class="violet">%s € (%s &#37;) de aportes sobre mínimo</span>', 1, 'feed');
INSERT INTO `purpose` VALUES('feed-project_runout', 'Al proyecto %s le faltan <span class="red">%s días</span> para finalizar la %sª ronda', 1, 'feed');
INSERT INTO `purpose` VALUES('feed-side-top_ten', 'Top ten cofinanciadores', NULL, 'community');
INSERT INTO `purpose` VALUES('feed-timeago', 'Hace %s', NULL, 'feed');
INSERT INTO `purpose` VALUES('feed-timeago-justnow', 'nada', NULL, 'feed');
INSERT INTO `purpose` VALUES('feed-timeago-periods', 'segundo-segundos_minuto-minutos_hora-horas_día-días_semana-semanas_mes-meses_año-años_década-décadas', NULL, 'feed');
INSERT INTO `purpose` VALUES('feed-timeago-published', 'Publicado hace %s', NULL, 'feed');
INSERT INTO `purpose` VALUES('feed-updates-comment', 'Ha escrito un <span class="green">Comentario</span> en la entrada "%s" en %s del proyecto %s', 1, 'feed');
INSERT INTO `purpose` VALUES('footer-header-categories', 'Categorías', NULL, 'footer');
INSERT INTO `purpose` VALUES('footer-header-projects', 'Proyectos', NULL, 'footer');
INSERT INTO `purpose` VALUES('footer-header-resources', 'Recursos', NULL, 'footer');
INSERT INTO `purpose` VALUES('footer-header-services', 'Servicios', NULL, 'footer');
INSERT INTO `purpose` VALUES('footer-header-social', 'Síguenos', NULL, 'footer');
INSERT INTO `purpose` VALUES('footer-header-sponsors', 'Apoyos institucionales', NULL, 'footer');
INSERT INTO `purpose` VALUES('footer-platoniq-iniciative', 'Una iniciativa de:', NULL, 'footer');
INSERT INTO `purpose` VALUES('footer-resources-glossary', 'Glosario', NULL, 'footer');
INSERT INTO `purpose` VALUES('footer-resources-press', 'Prensa', NULL, 'footer');
INSERT INTO `purpose` VALUES('footer-service-campaign', 'Campañas', NULL, 'footer');
INSERT INTO `purpose` VALUES('footer-service-consulting', 'Consultoría', NULL, 'footer');
INSERT INTO `purpose` VALUES('footer-service-resources', 'Capital riego', NULL, 'footer');
INSERT INTO `purpose` VALUES('footer-service-workshop', 'Talleres', NULL, 'footer');
INSERT INTO `purpose` VALUES('form-accept-button', 'Aceptar', NULL, 'form');
INSERT INTO `purpose` VALUES('form-add-button', 'Añadir', NULL, 'form');
INSERT INTO `purpose` VALUES('form-ajax-info', 'El formulario de proyecto se va grabando segun pases por cada campo', NULL, 'form');
INSERT INTO `purpose` VALUES('form-apply-button', 'Aplicar', NULL, 'form');
INSERT INTO `purpose` VALUES('form-errors-info', 'Total: %s | En este paso: %s', NULL, 'form');
INSERT INTO `purpose` VALUES('form-errors-total', 'Hay %s errores en total', NULL, 'form');
INSERT INTO `purpose` VALUES('form-footer-errors_title', 'Errores', NULL, 'form');
INSERT INTO `purpose` VALUES('form-image_upload-button', 'Subir imagen', NULL, 'form');
INSERT INTO `purpose` VALUES('form-navigation_bar-header', 'Ir a', NULL, 'form');
INSERT INTO `purpose` VALUES('form-next-button', 'Siguiente', NULL, 'form');
INSERT INTO `purpose` VALUES('form-project-info_status-title', 'Estado global de la información', NULL, 'form');
INSERT INTO `purpose` VALUES('form-project-progress-title', 'Evaluación de datos', NULL, 'form');
INSERT INTO `purpose` VALUES('form-project-status-title', 'Estado del proyecto', NULL, 'form');
INSERT INTO `purpose` VALUES('form-project_status-campaing', 'En campaña', NULL, 'form');
INSERT INTO `purpose` VALUES('form-project_status-cancel', 'Desechado', NULL, 'form');
INSERT INTO `purpose` VALUES('form-project_status-cancelled', 'Descartado', NULL, 'form');
INSERT INTO `purpose` VALUES('form-project_status-edit', 'Editándose', NULL, 'form');
INSERT INTO `purpose` VALUES('form-project_status-expired', 'Archivado', NULL, 'form');
INSERT INTO `purpose` VALUES('form-project_status-fulfilled', 'Retorno cumplido', NULL, 'form');
INSERT INTO `purpose` VALUES('form-project_status-review', 'Pendiente de valoración', NULL, 'form');
INSERT INTO `purpose` VALUES('form-project_status-success', 'Financiado', NULL, 'form');
INSERT INTO `purpose` VALUES('form-project_waitfor-campaing', 'Difunde tu proyecto, ayuda a que consiga el máximo de aportaciones!', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('form-project_waitfor-cancel', 'Finalmente hemos desestimado la propuesta para publicarla en Goteo, te invitamos a intentarlo con otra idea o concepto.', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('form-project_waitfor-edit', 'Cuando lo tengas listo mándalo a revisión. Necesitas llegar a un mínimo de información sobre el proyecto en el formulario.', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('form-project_waitfor-expired', 'No lo conseguiste :( Trata de mejorarlo e inténtalo de nuevo!', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('form-project_waitfor-fulfilled', 'Has cumplido con los retornos :) Gracias por tu participación!', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('form-project_waitfor-review', 'En breve nos pondremos en contacto contigo respecto al proyecto, una vez se lleve a cabo el proceso de revisión. A continuación lo publicaremos o bien te sugeriremos cosas para mejorarlo.', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('form-project_waitfor-success', 'Has conseguido el mínimo o más en aportes de cofinanciación para el proyecto. Enseguida te contactaremos para hablar de dinero :)', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('form-remove-button', 'Quitar', NULL, 'form');
INSERT INTO `purpose` VALUES('form-self_review-button', 'Corregir', NULL, 'form');
INSERT INTO `purpose` VALUES('form-send_review-button', 'Enviar', NULL, 'form');
INSERT INTO `purpose` VALUES('form-upload-button', 'Enviar', NULL, 'form');
INSERT INTO `purpose` VALUES('guide-dashboard-user-access', 'Desde aquí puedes modificar los datos con que accedes a tu cuenta de Goteo.', NULL, 'general');
INSERT INTO `purpose` VALUES('guide-dashboard-user-personal', 'Sólo debes cumplimentar estos datos si has creado un proyecto y quieres que sea cofinanciado y apoyado mediante Goteo.\r\n\r\nLa información de este apartado es necesaria para contactarte en caso de que obtengas la financiación requerida, y que así se pueda efectuar el ingreso.', NULL, 'general');
INSERT INTO `purpose` VALUES('guide-dashboard-user-preferences', 'Marca ''Sí'' en las notificaciones automáticas que quieras bloquear', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('guide-dashboard-user-profile', 'Tanto si quieres presentar un proyecto como incorporarte como cofinanciador/a, para formar parte de la comunidad de Goteo te recomendamos que pongas atención en tu texto de presentación, que añadas links relevantes sobre lo que haces y subas una imagen de perfil con la que te identifiques.', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('guide-project-comment', 'guide-project-comment', NULL, 'general');
INSERT INTO `purpose` VALUES('guide-project-contract-information', '<strong>A partir de este paso sólo debes cumplimentar los datos si quieres que tu proyecto sea cofinanciado y apoyado mediante Goteo. </strong><br><br>La información de este apartado es necesaria para contactarte si obtienes la financiación requerida, y que así se pueda efectuar el ingreso. En el caso de entidades, se recomienda que quien represente a la organización pueda luego acreditarlo formalmente (por ejemplo a través de los estatutos o de un certificado del secretario con el visto bueno del presidente, en el caso de asociaciones).', NULL, 'general');
INSERT INTO `purpose` VALUES('guide-project-costs', '<strong>En esta sección debes elaborar un pequeño presupuesto basado en los costes que calcules va a tener la realización del proyecto.</strong><br><br>\r\nDebes especificar según tareas, infraestructura o materiales. Intenta ser realista en los costes y explicar brevemente por qué necesitas cubrir cada uno de ellos. Ten en cuenta que por norma general, al menos un 80% del proyecto deberá ser realizado directamente por la persona o equipo que promueve el proyecto, y no subcontratado a terceros.<br><br>\r\n<strong>Muy importante</strong>: En Goteo diferenciamos entre costes imprescindibles y costes adicionales. Los primeros deben lograrse en su totalidad para poder obtener la financiación, mientras que los segundo se solicitan y obtienen directamente en una campaña posterior, una vez está en marcha el proyecto, para poder poder cubrir costes de optimización del mismo (difusión, diseño, alcance, más unidades, etc). Estos costes adicionales no pueden superar la mitad de los costes totales del proyecto.', NULL, 'general');
INSERT INTO `purpose` VALUES('guide-project-description', '<strong>Éste es el apartado donde explicar con detalle los aspectos conceptuales del proyecto. </strong><br><br>Es lo primero con lo que cualquier usuario de la red se encontrará, así que cuida la redacción y evita las faltas de ortografía. Verás que hay campos obligatorios como incluir un vídeo o subir imágenes. Esto es así porque los consideramos imprescindibles para empezar con éxito una campaña de recaudación de fondos mediante Goteo.<br><br>\r\nTen en cuenta que lo más valorado en Goteo es: la información o conocimiento libre de interés general que tu proyecto aportará a la comunidad,  la originalidad, aspirar a resolver una demanda social,  el potencial para atraer a una comunidad amplia de personas interesadas, dejar claro que el equipo promotor posee las capacidades y experiencia para poder llevarlo a buen puerto. Así que no pierdas de vista informar sobre esos aspectos.', NULL, 'general');
INSERT INTO `purpose` VALUES('guide-project-error-mandatories', 'Faltan campos obligatorios', NULL, 'preview');
INSERT INTO `purpose` VALUES('guide-project-preview', '<strong>Éste es un resumen de toda la información sobre el proyecto.</strong><br><br> Repasa los puntos de cada apartado para ver si puedes mejorar algo, o bien envía el proyecto para su valoración (mediante el botón "Enviar" de la parte de abajo) si ya están cumplimentados todos los campos obligatorios, para que así pueda ser valorado por el equipo y la comunidad de Goteo. Una vez lo envíes ya no se podrán introducir cambios. <br><br>Ten en cuenta que sólo podemos seleccionar unos cuantos proyectos al mes para garantizar la atención y la difusión de las propuestas que se hacen públicas. Próximamente recibirás un mensaje con toda la información, que te indicará los pasos a seguir y recomendaciones para que tu proyecto pueda alcanzar la meta propuesta. ', NULL, 'preview');
INSERT INTO `purpose` VALUES('guide-project-rewards', '<strong>En este apartado debes establecer qué ofrece el proyecto a cambio a sus cofinanciadores, y también sus retornos colectivos.</strong><br><br>\r\nAdemás de las recompensas individuales para cada importe de cofinanciación, aquí debes definir qué tipo de licencia asignar al proyecto, en función de su formato y/o del grado de abertura del mismo (o de alguna de sus partes). Esta parte es muy importante, ya que Goteo es una plataforma de crowdfunding para proyectos basados en la filosofía del código abierto y que fortalezcan el procomún.<br><br>\r\nEn caso de que además de una de las licencias aquí especificadas te interese adicionalmente registrar la propiedad intelectual de tu obra o idea, manteniendo su compatibilidad con los retornos colectivos, te recomendamos obtener una protección legal específica mediante el servicio <a href="http://www.safecreative.org/" target="new">Safe Creative</a>.', NULL, 'general');
INSERT INTO `purpose` VALUES('guide-project-success-minprogress', 'Ha llegado al porcentaje mínimo', NULL, 'preview');
INSERT INTO `purpose` VALUES('guide-project-success-noerrors', 'Todos los campos obligatorios se han cumplimentado', NULL, 'preview');
INSERT INTO `purpose` VALUES('guide-project-success-okfinish', 'Puede enviarse para revisión', NULL, 'preview');
INSERT INTO `purpose` VALUES('guide-project-support', 'guide-project-support', NULL, 'general');
INSERT INTO `purpose` VALUES('guide-project-supports', '<strong>En este apartado puedes especificar qué otras ayudas, aparte de financiación, se necesitan para llevar a cabo el proyecto.</strong><br><br> Pueden ser tareas o acciones a cargo de otras personas (traducciones, gestiones, difusión, etc), o bien préstamos específicos (de material, transporte, hardware, etc).', NULL, 'general');
INSERT INTO `purpose` VALUES('guide-project-updates', '<b>Es muy importante que los proyectos mantengan informados a sus cofinanciadores y el resto de personas potencialmente interesadas sobre cómo avanza su campaña. Desde este apartado puedes publicar mensajes de actualización sobre el proyecto, como una especie de blog público.</b>\r\n\r\nEn Goteo además, una vez se han logrado los fondos mínimos, para la segunda ronda de cofinanciación es crítico explicar regularmente cómo ha arrancado la producción, avances, problemas, etc que permitan la mayor transparencia posible y saber cómo evoluciona el inicio del proyecto, para así tratar de generar más interés y comunidad en torno al mismo.', NULL, 'general');
INSERT INTO `purpose` VALUES('guide-project-user-information', '<strong>En este apartado debes introducir los datos para la información pública de tu perfil de usuario. </strong><br><br>Tanto si quieres presentar un proyecto como incorporarte como cofinanciador/a, para formar parte de la comunidad de Goteo te recomendamos que pongas atención en tu texto de presentación, que añadas links relevantes sobre lo que haces y subas una imagen de perfil con la que te identifiques.', NULL, 'profile');
INSERT INTO `purpose` VALUES('guide-user-data', 'Texto guía en la edición de campos sensibles.', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('guide-user-register', 'Texto guía en el registro de un nuevo usuario.', NULL, 'register');
INSERT INTO `purpose` VALUES('header-about-side', 'Lo que nos mueve', NULL, 'general');
INSERT INTO `purpose` VALUES('home-posts-header', 'En nuestro blog', NULL, 'general');
INSERT INTO `purpose` VALUES('home-promotes-header', 'Destacados', NULL, 'home');
INSERT INTO `purpose` VALUES('image-upload-fail', 'Fallo al subir la imagen', NULL, 'bluead');
INSERT INTO `purpose` VALUES('invest-address-address-field', 'Dirección:', NULL, 'invest');
INSERT INTO `purpose` VALUES('invest-address-country-field', 'País:', NULL, 'invest');
INSERT INTO `purpose` VALUES('invest-address-header', 'Dónde quieres recibir la recompensa (sólo en caso de envíos postales)', NULL, 'invest');
INSERT INTO `purpose` VALUES('invest-address-location-field', 'Ciudad:', NULL, 'invest');
INSERT INTO `purpose` VALUES('invest-address-name-field', 'Nombre:', NULL, 'invest');
INSERT INTO `purpose` VALUES('invest-address-nif-field', 'Número de NIF / NIE / VAT:', NULL, 'invest');
INSERT INTO `purpose` VALUES('invest-address-zipcode-field', 'Código postal:', NULL, 'invest');
INSERT INTO `purpose` VALUES('invest-alert-investing', 'Vas a aportar', NULL, 'invest');
INSERT INTO `purpose` VALUES('invest-alert-noreward', 'No has marcado ninguna recompensa, ¿es correcto?', NULL, 'invest');
INSERT INTO `purpose` VALUES('invest-alert-noreward_renounce', '¿Deseas renunciar a la recompensa y desgravar tu donativo?', NULL, 'invest');
INSERT INTO `purpose` VALUES('invest-alert-renounce', 'Renuncias pero no has puesto tu NIF para desgravar el donativo, ¿es correcto?', NULL, 'invest');
INSERT INTO `purpose` VALUES('invest-alert-rewards', 'Has elegido las siguientes recompensas:', NULL, 'invest');
INSERT INTO `purpose` VALUES('invest-amount', 'Cantidad', NULL, 'invest');
INSERT INTO `purpose` VALUES('invest-amount-error', 'Tienes que indicar el importe', NULL, 'bluead');
INSERT INTO `purpose` VALUES('invest-amount-tooltip', 'Introduce la cantidad con la que apoyarás al proyecto', NULL, 'invest');
INSERT INTO `purpose` VALUES('invest-anonymous', 'Quiero que mi aportación sea anónima', NULL, 'invest');
INSERT INTO `purpose` VALUES('invest-create-error', 'Ha habido algun problema al inicializar la transacción', NULL, 'bluead');
INSERT INTO `purpose` VALUES('invest-data-error', 'No se han recibido los datos necesarios', NULL, 'bluead');
INSERT INTO `purpose` VALUES('invest-donation-header', 'Introduce los datos fiscales para el donativo', NULL, 'invest');
INSERT INTO `purpose` VALUES('invest-individual-header', 'Puedes renunciar a recibir recompensas por tu aportación, o seleccionar las que igualen o estén por debajo del importe que hayas introducido.', NULL, 'general');
INSERT INTO `purpose` VALUES('invest-mail_info-address', 'Has especificado la siguiente dirección:', NULL, 'invest');
INSERT INTO `purpose` VALUES('invest-mail_info-drop', 'Además, el proyecto ha conseguido %s &euro; de capital riego de la campaña %s', NULL, 'invest');
INSERT INTO `purpose` VALUES('invest-next_step', 'Paso siguiente', NULL, 'invest');
INSERT INTO `purpose` VALUES('invest-owner-error', 'Eres el autor del proyecto, no puedes aportar personalmente a tu propio proyecto.', NULL, 'bluead');
INSERT INTO `purpose` VALUES('invest-payment-email', 'Introduce tu cuenta de PayPal', NULL, 'invest');
INSERT INTO `purpose` VALUES('invest-payment_method-header', 'Elige el método de pago', NULL, 'invest');
INSERT INTO `purpose` VALUES('invest-paypal-error_fatal', 'Ha ocurrido un error fatal al conectar con PayPal. Se ha reportado la incidencia, disculpa las molestias.', NULL, 'bluead');
INSERT INTO `purpose` VALUES('invest-resign', 'Renuncio a una recompensa individual, solo quiero ayudar al proyecto', NULL, 'invest');
INSERT INTO `purpose` VALUES('invest-reward-none', 'Ya no se puede elegir', NULL, 'invest');
INSERT INTO `purpose` VALUES('invest-social-header', 'Con los retornos colectivos ganamos tod@s', NULL, 'invest');
INSERT INTO `purpose` VALUES('invest-tpv-error_fatal', 'Ha ocurrido un error fatal al conectar con el TPV. Se ha reportado la incidencia, disculpa las molestias.', NULL, 'bluead');
INSERT INTO `purpose` VALUES('leave-email-sended', 'Te hemos enviado un email para completar el proceso de baja. Verifica también la carpeta de correo no deseado o spam.', NULL, 'register');
INSERT INTO `purpose` VALUES('leave-process-completed', 'La cuenta se ha dado de baja correctamente', NULL, 'register');
INSERT INTO `purpose` VALUES('leave-process-fail', 'No hemos podido completar el proceso para darte de baja. Por favor, contáctanos a hola@goteo.org', NULL, 'register');
INSERT INTO `purpose` VALUES('leave-request-fail', 'No hemos encontrado ninguna cuenta con este email en nuestra base de datos para darla de baja', NULL, 'register');
INSERT INTO `purpose` VALUES('leave-token-incorrect', 'El código para completar el proceso de baja no es válido', NULL, 'register');
INSERT INTO `purpose` VALUES('login-access-button', 'Entrar', NULL, 'login');
INSERT INTO `purpose` VALUES('login-access-header', 'Usuario registrado', NULL, 'login');
INSERT INTO `purpose` VALUES('login-access-password-field', 'Contraseña', NULL, 'login');
INSERT INTO `purpose` VALUES('login-access-username-field', 'Nombre de acceso', NULL, 'login');
INSERT INTO `purpose` VALUES('login-banner-header', 'Accede a la comunidad goteo<br /><span class="greenblue">100% abierto</span>', 1, 'banners');
INSERT INTO `purpose` VALUES('login-fail', 'Error de acceso', NULL, 'login');
INSERT INTO `purpose` VALUES('login-leave-button', 'Dar de baja', NULL, 'login');
INSERT INTO `purpose` VALUES('login-leave-header', 'Cancelar la cuenta', NULL, 'login');
INSERT INTO `purpose` VALUES('login-leave-message', 'Déjanos un mensaje', NULL, 'login');
INSERT INTO `purpose` VALUES('login-oneclick-header', 'Accede con un solo click', NULL, 'login');
INSERT INTO `purpose` VALUES('login-recover-button', 'Recuperar', NULL, 'login');
INSERT INTO `purpose` VALUES('login-recover-email-field', 'Email de la cuenta', NULL, 'login');
INSERT INTO `purpose` VALUES('login-recover-header', 'Recuperar contraseña', NULL, 'login');
INSERT INTO `purpose` VALUES('login-recover-link', 'Recuperar contraseña', NULL, 'login');
INSERT INTO `purpose` VALUES('login-recover-username-field', 'Nombre de acceso', NULL, 'login');
INSERT INTO `purpose` VALUES('login-register-button', 'Registrar', NULL, 'login');
INSERT INTO `purpose` VALUES('login-register-conditions', 'Acepto las condiciones de uso de la plataforma, así­ como presto mi consentimiento para el tratamiento de mis datos personales. A tal efecto, el responsable del portal ha establecido una <a href="/legal/privacy" target="_blank">polí­tica de privacidad</a> donde se puede conocer la finalidad que se le darán a los datos suministrados a través del presente formulario, así­ como los derechos que asisten a la persona que suministra dichos datos.', 1, 'general');
INSERT INTO `purpose` VALUES('login-register-confirm-field', 'Confirmar email', NULL, 'login');
INSERT INTO `purpose` VALUES('login-register-confirm_password-field', 'Confirmar contraseña', NULL, 'login');
INSERT INTO `purpose` VALUES('login-register-email-field', 'Email', NULL, 'login');
INSERT INTO `purpose` VALUES('login-register-header', 'Nuevo usuario', NULL, 'login');
INSERT INTO `purpose` VALUES('login-register-password-field', 'Contraseña', NULL, 'login');
INSERT INTO `purpose` VALUES('login-register-password-minlength', 'Mínimo 6 carácteres', NULL, 'login');
INSERT INTO `purpose` VALUES('login-register-userid-field', 'Nombre de acceso', NULL, 'login');
INSERT INTO `purpose` VALUES('login-register-username-field', 'Nombre público', NULL, 'login');
INSERT INTO `purpose` VALUES('login-signin-facebook', 'Accede con Facebook', NULL, 'login');
INSERT INTO `purpose` VALUES('login-signin-google', 'Accede con Google', NULL, 'login');
INSERT INTO `purpose` VALUES('login-signin-linkedin', 'Accede con LinkedIn', NULL, 'login');
INSERT INTO `purpose` VALUES('login-signin-myopenid', 'Accede con myOpenID', NULL, 'login');
INSERT INTO `purpose` VALUES('login-signin-openid', 'Otro servidor Open ID', NULL, 'login');
INSERT INTO `purpose` VALUES('login-signin-openid-go', 'Ir', NULL, 'login');
INSERT INTO `purpose` VALUES('login-signin-twitter', 'Accede con Twitter', NULL, 'login');
INSERT INTO `purpose` VALUES('login-signin-view-more', 'Mostrar más opciones de acceso', NULL, 'login');
INSERT INTO `purpose` VALUES('login-signin-yahoo', 'Accede con Yahoo', NULL, 'login');
INSERT INTO `purpose` VALUES('mailer-baja', '¿No quieres recibir más comunicaciones de Goteo? Puedes dar tu email de baja mediante este <a href="%s">enlace</a>', 1, 'mailer');
INSERT INTO `purpose` VALUES('mailer-disclaimer', 'Goteo es una plataforma digital para la financiación colectiva, colaboración y distribución de recursos para el desarrollo de proyectos sociales, culturales, educativos, tecnológicos... que contribuyan al fortalecimiento del procomún, el código abierto y/o el conocimiento libre.', NULL, 'mailer');
INSERT INTO `purpose` VALUES('mailer-sinoves', 'Si no puedes ver este mensaje utiliza este <a href="%s">enlace</a>', 1, 'mailer');
INSERT INTO `purpose` VALUES('main-banner-header', '<h2 class="message">Red social para <span class="greenblue">cofinanciar y colaborar con</span><br /> proyectos creativos que fomentan el procomún<br /> ¿Tienes un proyecto con <span class="greenblue">adn abierto</span>?</h2><a href="/contact" class="button banner-button">Contáctanos</a>', 1, 'banners');
INSERT INTO `purpose` VALUES('mandatory-cost-field-amount', 'Es obligatorio asignar un importe a los costes', NULL, 'costs');
INSERT INTO `purpose` VALUES('mandatory-cost-field-description', 'Es obligatorio poner alguna descripción a los costes', NULL, 'costs');
INSERT INTO `purpose` VALUES('mandatory-cost-field-name', 'Es obligatorio ponerle un nombre al coste', NULL, 'costs');
INSERT INTO `purpose` VALUES('mandatory-cost-field-task_dates', 'Es obligatorio especificar las fechas aproximadas de la tarea', NULL, 'costs');
INSERT INTO `purpose` VALUES('mandatory-cost-field-type', 'Es obligatorio seleccionar el tipo de coste', NULL, 'general');
INSERT INTO `purpose` VALUES('mandatory-individual_reward-field-amount', 'Es obligatorio indicar el importe que permite obtener la recompensa', NULL, 'general');
INSERT INTO `purpose` VALUES('mandatory-individual_reward-field-description', 'Es obligatorio poner alguna descripción', NULL, 'rewards');
INSERT INTO `purpose` VALUES('mandatory-individual_reward-field-icon', 'Es obligatorio seleccionar el tipo de recompensa', NULL, 'rewards');
INSERT INTO `purpose` VALUES('mandatory-individual_reward-field-name', 'Es obligatorio poner la recompensa', NULL, 'rewards');
INSERT INTO `purpose` VALUES('mandatory-project-costs', 'Debe desglosarse en al menos dos costes.', NULL, 'general');
INSERT INTO `purpose` VALUES('mandatory-project-field-about', 'Es obligatorio explicar las características básicas del proyecto', NULL, 'overview');
INSERT INTO `purpose` VALUES('mandatory-project-field-address', 'La dirección de la/el responsable del proyecto es obligatoria', NULL, 'personal');
INSERT INTO `purpose` VALUES('mandatory-project-field-category', 'Es obligatorio elegir al menos una categoria para el proyecto.', NULL, 'overview');
INSERT INTO `purpose` VALUES('mandatory-project-field-contract_birthdate', 'Es obligatorio poner la fecha de nacimiento del responsable del proyecto', NULL, 'personal');
INSERT INTO `purpose` VALUES('mandatory-project-field-contract_email', 'Es obligatorio poner el email de la/el responsable del proyecto.', NULL, 'personal');
INSERT INTO `purpose` VALUES('mandatory-project-field-contract_name', 'Es obligatorio poner el nombre de la/el responsable del proyecto', NULL, 'personal');
INSERT INTO `purpose` VALUES('mandatory-project-field-contract_nif', 'Es obligatorio poner el documento de identificación de la/el responsable del proyecto', NULL, 'personal');
INSERT INTO `purpose` VALUES('mandatory-project-field-country', 'El país de la/el responsable del proyecto es obligatorio', NULL, 'personal');
INSERT INTO `purpose` VALUES('mandatory-project-field-description', 'Es obligatorio resumir el proyecto', NULL, 'overview');
INSERT INTO `purpose` VALUES('mandatory-project-field-entity_cif', 'Es obligatorio poner el CIF de la entidad jurídica', NULL, 'personal');
INSERT INTO `purpose` VALUES('mandatory-project-field-entity_name', 'Es obligatorio poner el nombre de la organización', NULL, 'personal');
INSERT INTO `purpose` VALUES('mandatory-project-field-entity_office', 'Es obligatorio poner el cargo que tienes dentro la organización que vas a representar', NULL, 'personal');
INSERT INTO `purpose` VALUES('mandatory-project-field-goal', 'Es obligatorio explicar los objetivos en la descripción del proyecto', NULL, 'overview');
INSERT INTO `purpose` VALUES('mandatory-project-field-image', 'Es obligatorio vincular una imagen como mínimo al proyecto. ', NULL, 'overview');
INSERT INTO `purpose` VALUES('mandatory-project-field-lang', 'Tienes que indicar el idioma del proyecto', NULL, 'overview');
INSERT INTO `purpose` VALUES('mandatory-project-field-location', 'Es obligatorio poner el alcance potencial del proyecto', NULL, 'overview');
INSERT INTO `purpose` VALUES('mandatory-project-field-media', 'Recomendamos poner un vídeo para mejorar la valoración del proyecto a la hora de decidir si publicarlo o no en Goteo.', NULL, 'overview');
INSERT INTO `purpose` VALUES('mandatory-project-field-motivation', 'Es obligatorio explicar la motivación en la descripción del proyecto', NULL, 'overview');
INSERT INTO `purpose` VALUES('mandatory-project-field-name', 'Es obligatorio poner un nombre al proyecto', NULL, 'overview');
INSERT INTO `purpose` VALUES('mandatory-project-field-phone', 'El teléfono de la/el responsable del proyecto es obligatorio', NULL, 'personal');
INSERT INTO `purpose` VALUES('mandatory-project-field-related', 'Es obligatorio explicar en la descripción del proyecto la experiencia relacionada y/o el equipo con que se cuenta ', NULL, 'overview');
INSERT INTO `purpose` VALUES('mandatory-project-field-residence', 'Es obligatorio poner el lugar de residencia de la/el responsable del proyecto', NULL, 'personal');
INSERT INTO `purpose` VALUES('mandatory-project-field-resource', 'Es obligatorio especificar si cuentas con otros recursos o no', NULL, 'costs');
INSERT INTO `purpose` VALUES('mandatory-project-field-zipcode', 'El código postal de la/el responsable del proyecto es obligatorio', NULL, 'personal');
INSERT INTO `purpose` VALUES('mandatory-project-resource', 'Es obligatorio especificar si cuentas con otros recursos o no', NULL, 'costs');
INSERT INTO `purpose` VALUES('mandatory-project-total-costs', 'Es obligatorio especificar algún coste', NULL, 'costs');
INSERT INTO `purpose` VALUES('mandatory-register-field-email', 'Tienes que indicar un email', NULL, 'general');
INSERT INTO `purpose` VALUES('mandatory-social_reward-field-description', 'Es obligatorio poner alguna descripción al retorno', NULL, 'rewards');
INSERT INTO `purpose` VALUES('mandatory-social_reward-field-icon', 'Es obligatorio seleccionar el tipo de retorno', NULL, 'rewards');
INSERT INTO `purpose` VALUES('mandatory-social_reward-field-name', 'Es obligatorio especificar el retorno', NULL, 'rewards');
INSERT INTO `purpose` VALUES('mandatory-support-field-description', 'Es obligatorio poner alguna descripción', NULL, 'supports');
INSERT INTO `purpose` VALUES('mandatory-support-field-name', 'Es obligatorio ponerle un nombre a la colaboración', NULL, 'supports');
INSERT INTO `purpose` VALUES('oauth-confirm-user', 'Vincular usuario existente', NULL, 'login');
INSERT INTO `purpose` VALUES('oauth-facebook-access-denied', 'Acceso desde Facebook denegado', NULL, 'login');
INSERT INTO `purpose` VALUES('oauth-goteo-openid-sync-password', 'Estás intentando vincular una cuenta ya existente en Goteo con un proveedor externo. Esto te permitirá entrar en Goteo con un solo clic en el futuro.<br/>Esta primera vez deberás proporcionar la contraseña de tu cuenta en Goteo para confirmar tu identidad.', 1, 'login');
INSERT INTO `purpose` VALUES('oauth-goteo-user-not-exists', 'Ese usuario no existe en Goteo', NULL, 'login');
INSERT INTO `purpose` VALUES('oauth-goteo-user-password-exists', 'Ese usuario ya existe en Goteo', NULL, 'login');
INSERT INTO `purpose` VALUES('oauth-import-about', 'Acerca de ti', NULL, 'login');
INSERT INTO `purpose` VALUES('oauth-import-facebook', 'Link a tu cuenta de Facebook', NULL, 'login');
INSERT INTO `purpose` VALUES('oauth-import-location', 'Lugar de residencia', NULL, 'login');
INSERT INTO `purpose` VALUES('oauth-import-name', 'Nombre', NULL, 'login');
INSERT INTO `purpose` VALUES('oauth-import-twitter', 'Link a tu cuenta de Twitter', NULL, 'login');
INSERT INTO `purpose` VALUES('oauth-import-website', 'Tus sitios webs', NULL, 'login');
INSERT INTO `purpose` VALUES('oauth-linkedin-access-denied', 'Acceso desde Linkedin denegado', NULL, 'login');
INSERT INTO `purpose` VALUES('oauth-login-imported-data', 'También van a importarse estos datos, puedes cambiarlos una vez autentificado:', NULL, 'login');
INSERT INTO `purpose` VALUES('oauth-login-welcome-from', 'Bienvenido/a a Goteo! Comprueba tu nombre de usuario y email para finalizar el proceso. En caso de que no se haya podido importar el email o lo cambies por otro, recibirás un correo electrónico con un link de activación para comprovar su validez.', NULL, 'login');
INSERT INTO `purpose` VALUES('oauth-openid-access-denied', 'Acceso desde Open ID denegado', NULL, 'login');
INSERT INTO `purpose` VALUES('oauth-openid-not-logged', 'Usuario desconectado desde Open ID', NULL, 'login');
INSERT INTO `purpose` VALUES('oauth-token-request-error', 'Ha ocurrido un error al obtener las credenciales con el proveedor', NULL, 'login');
INSERT INTO `purpose` VALUES('oauth-twitter-access-denied', 'Acceso desde Twitter denegado', NULL, 'login');
INSERT INTO `purpose` VALUES('oauth-unknown-provider', 'No se puede iniciar sesión con este proveedor', NULL, 'login');
INSERT INTO `purpose` VALUES('open-banner-header', '<div class="modpo-open">OPEN</div><div class="modpo-percent">100&#37; ABIERTO</div><div class="modpo-whyopen">%s</div>', 1, 'banners');
INSERT INTO `purpose` VALUES('overview-field-about', 'Características básicas', NULL, 'general');
INSERT INTO `purpose` VALUES('overview-field-categories', 'Categorías', NULL, 'overview');
INSERT INTO `purpose` VALUES('overview-field-currently', 'Estado actual', NULL, 'overview');
INSERT INTO `purpose` VALUES('overview-field-description', 'Breve descripción ', NULL, 'overview');
INSERT INTO `purpose` VALUES('overview-field-goal', 'Objetivos de la campaña de crowdfunding', NULL, 'overview');
INSERT INTO `purpose` VALUES('overview-field-image_gallery', 'Imágenes actuales', NULL, 'overview');
INSERT INTO `purpose` VALUES('overview-field-image_upload', 'Subir una imagen', NULL, 'overview');
INSERT INTO `purpose` VALUES('overview-field-keywords', 'Palabras clave del proyecto', NULL, 'overview');
INSERT INTO `purpose` VALUES('overview-field-lang', 'Idioma original', NULL, 'overview');
INSERT INTO `purpose` VALUES('overview-field-media', 'Vídeo de presentación', NULL, 'overview');
INSERT INTO `purpose` VALUES('overview-field-media_preview', 'Vista previa', NULL, 'overview');
INSERT INTO `purpose` VALUES('overview-field-motivation', 'Motivación y a quién va dirigido el proyecto', NULL, 'general');
INSERT INTO `purpose` VALUES('overview-field-name', 'Título del proyecto', NULL, 'overview');
INSERT INTO `purpose` VALUES('overview-field-options-currently_avanzado', 'Avanzado', NULL, 'overview');
INSERT INTO `purpose` VALUES('overview-field-options-currently_finalizado', 'Finalizado', NULL, 'overview');
INSERT INTO `purpose` VALUES('overview-field-options-currently_inicial', 'Inicial', NULL, 'overview');
INSERT INTO `purpose` VALUES('overview-field-options-currently_medio', 'Medio', NULL, 'overview');
INSERT INTO `purpose` VALUES('overview-field-options-scope_global', 'Global', NULL, 'overview');
INSERT INTO `purpose` VALUES('overview-field-options-scope_local', 'Local', NULL, 'overview');
INSERT INTO `purpose` VALUES('overview-field-options-scope_nacional', 'Nacional', NULL, 'overview');
INSERT INTO `purpose` VALUES('overview-field-options-scope_regional', 'Regional', NULL, 'overview');
INSERT INTO `purpose` VALUES('overview-field-project_location', 'Ubicación', NULL, 'overview');
INSERT INTO `purpose` VALUES('overview-field-related', 'Experiencia previa y equipo', NULL, 'overview');
INSERT INTO `purpose` VALUES('overview-field-scope', 'Alcance del proyecto', NULL, 'overview');
INSERT INTO `purpose` VALUES('overview-field-subtitle', 'Frase de resumen', NULL, 'overview');
INSERT INTO `purpose` VALUES('overview-field-usubs', 'Cargar con Universal Subtitles', NULL, 'overview');
INSERT INTO `purpose` VALUES('overview-field-video', 'Vídeo adicional sobre motivación', NULL, 'overview');
INSERT INTO `purpose` VALUES('overview-fields-images-title', 'Imágenes del proyecto', NULL, 'overview');
INSERT INTO `purpose` VALUES('overview-main-header', 'Descripción del proyecto', NULL, 'overview');
INSERT INTO `purpose` VALUES('personal-field-address', 'Dirección', NULL, 'personal');
INSERT INTO `purpose` VALUES('personal-field-contract_birthdate', 'Fecha de nacimiento', NULL, 'personal');
INSERT INTO `purpose` VALUES('personal-field-contract_data', 'Datos del/la responsable del proyecto', NULL, 'personal');
INSERT INTO `purpose` VALUES('personal-field-contract_email', 'Email vinculado al proyecto', NULL, 'personal');
INSERT INTO `purpose` VALUES('personal-field-contract_entity', 'Promotor/a del proyecto', NULL, 'personal');
INSERT INTO `purpose` VALUES('personal-field-contract_entity-entity', 'Persona jurídica (asociaciones, fundaciones, empresas)', NULL, 'personal');
INSERT INTO `purpose` VALUES('personal-field-contract_entity-person', 'Persona física', NULL, 'personal');
INSERT INTO `purpose` VALUES('personal-field-contract_name', 'Nombre y apellidos', NULL, 'personal');
INSERT INTO `purpose` VALUES('personal-field-contract_nif', 'Número de NIF / NIE / VAT', NULL, 'personal');
INSERT INTO `purpose` VALUES('personal-field-country', 'País', NULL, 'personal');
INSERT INTO `purpose` VALUES('personal-field-entity_cif', 'CIF de la entidad', NULL, 'personal');
INSERT INTO `purpose` VALUES('personal-field-entity_name', 'Denominación social (nombre) de la entidad', NULL, 'personal');
INSERT INTO `purpose` VALUES('personal-field-entity_office', 'Cargo en la organización', NULL, 'personal');
INSERT INTO `purpose` VALUES('personal-field-location', 'Localidad', NULL, 'personal');
INSERT INTO `purpose` VALUES('personal-field-main_address', 'Domicilio fiscal', NULL, 'personal');
INSERT INTO `purpose` VALUES('personal-field-phone', 'Teléfono', NULL, 'personal');
INSERT INTO `purpose` VALUES('personal-field-post_address', 'Domicilio postal', NULL, 'personal');
INSERT INTO `purpose` VALUES('personal-field-post_address-different', 'Diferente', NULL, 'personal');
INSERT INTO `purpose` VALUES('personal-field-post_address-same', 'Igual', NULL, 'personal');
INSERT INTO `purpose` VALUES('personal-field-zipcode', 'Código postal', NULL, 'personal');
INSERT INTO `purpose` VALUES('personal-main-header', 'Datos del promotor/a', NULL, 'personal');
INSERT INTO `purpose` VALUES('preview-main-header', 'Previsualización de datos', NULL, 'preview');
INSERT INTO `purpose` VALUES('preview-send-comment', 'Notas adicionales para el administrador', NULL, 'preview');
INSERT INTO `purpose` VALUES('profile-about-header', 'Sobre mí', NULL, 'general');
INSERT INTO `purpose` VALUES('profile-field-about', 'Cuéntanos algo sobre ti', NULL, 'profile');
INSERT INTO `purpose` VALUES('profile-field-avatar_current', 'Tu imagen actual', NULL, 'profile');
INSERT INTO `purpose` VALUES('profile-field-avatar_upload', 'Subir una imagen', NULL, 'profile');
INSERT INTO `purpose` VALUES('profile-field-contribution', 'Qué puedes aportar a Goteo', NULL, 'profile');
INSERT INTO `purpose` VALUES('profile-field-interests', 'Qué tipo de proyecto te motiva más', NULL, 'profile');
INSERT INTO `purpose` VALUES('profile-field-keywords', 'Temas que te interesan', NULL, 'profile');
INSERT INTO `purpose` VALUES('profile-field-location', 'Lugar de residencia habitual', NULL, 'profile');
INSERT INTO `purpose` VALUES('profile-field-name', 'Nombre de usuario/a', NULL, 'profile');
INSERT INTO `purpose` VALUES('profile-field-url', 'URL', NULL, 'profile');
INSERT INTO `purpose` VALUES('profile-field-websites', 'Mis páginas web', NULL, 'profile');
INSERT INTO `purpose` VALUES('profile-fields-image-title', 'Imagen de perfil', NULL, 'profile');
INSERT INTO `purpose` VALUES('profile-fields-social-title', 'Perfiles sociales', NULL, 'profile');
INSERT INTO `purpose` VALUES('profile-interests-header', 'Me interesan proyectos con fin...', NULL, 'public_profile');
INSERT INTO `purpose` VALUES('profile-invest_on-header', 'Proyectos que apoyo', NULL, 'public_profile');
INSERT INTO `purpose` VALUES('profile-invest_on-title', 'Cofinancia', NULL, 'public_profile');
INSERT INTO `purpose` VALUES('profile-keywords-header', 'Mis palabras clave', NULL, 'public_profile');
INSERT INTO `purpose` VALUES('profile-last_worth-title', 'Fecha', NULL, 'public_profile');
INSERT INTO `purpose` VALUES('profile-location-header', 'Mi ubicación', NULL, 'public_profile');
INSERT INTO `purpose` VALUES('profile-main-header', 'Datos de perfil', NULL, 'profile');
INSERT INTO `purpose` VALUES('profile-my_investors-header', 'Mis cofinanciadores', NULL, 'public_profile');
INSERT INTO `purpose` VALUES('profile-my_projects-header', 'Mis proyectos', NULL, 'public_profile');
INSERT INTO `purpose` VALUES('profile-my_worth-header', 'Mi caudal en goteo', NULL, 'public_profile');
INSERT INTO `purpose` VALUES('profile-name-header', 'Perfil de ', NULL, 'public_profile');
INSERT INTO `purpose` VALUES('profile-sharing_interests-header', 'Compartiendo intereses', NULL, 'public_profile');
INSERT INTO `purpose` VALUES('profile-social-header', 'Social', NULL, 'public_profile');
INSERT INTO `purpose` VALUES('profile-webs-header', 'Mis webs', NULL, 'public_profile');
INSERT INTO `purpose` VALUES('profile-widget-button', 'Ver perfil', NULL, 'public_profile');
INSERT INTO `purpose` VALUES('profile-widget-user-header', 'Usuario', NULL, 'public_profile');
INSERT INTO `purpose` VALUES('profile-worth-title', 'Aporta aquí:', NULL, 'public_profile');
INSERT INTO `purpose` VALUES('profile-worthcracy-title', 'Posición', NULL, 'public_profile');
INSERT INTO `purpose` VALUES('project-collaborations-supertitle', 'Necesidades no monetarias', NULL, 'project');
INSERT INTO `purpose` VALUES('project-collaborations-title', 'Se busca', NULL, 'project');
INSERT INTO `purpose` VALUES('project-form-header', 'Formulario', NULL, 'form');
INSERT INTO `purpose` VALUES('project-invest-closed', 'El proyecto ya no está en campaña', NULL, 'bluead');
INSERT INTO `purpose` VALUES('project-invest-continue', 'Elige el modo de pago', NULL, 'invest');
INSERT INTO `purpose` VALUES('project-invest-fail', 'Algo ha fallado, por favor inténtalo de nuevo', NULL, 'invest');
INSERT INTO `purpose` VALUES('project-invest-guest', 'Invitado (no olvides registrarte)', NULL, 'invest');
INSERT INTO `purpose` VALUES('project-invest-ok', 'Se ha tramitado tu aportación para cofinanciar este proyecto :)', NULL, 'invest');
INSERT INTO `purpose` VALUES('project-invest-start', 'Estás a un paso de ser cofinanciador/a de este proyecto', NULL, 'invest');
INSERT INTO `purpose` VALUES('project-invest-thanks_mail-fail', 'Ha habido algún error al enviar el mensaje de agradecimiento', NULL, 'bluead');
INSERT INTO `purpose` VALUES('project-invest-thanks_mail-success', 'Mensaje de agradecimiento enviado correctamente', NULL, 'bluead');
INSERT INTO `purpose` VALUES('project-invest-total', 'Total de aportaciones', NULL, 'general');
INSERT INTO `purpose` VALUES('project-menu-home', 'Proyecto', NULL, 'project');
INSERT INTO `purpose` VALUES('project-menu-messages', 'Mensajes', NULL, 'project');
INSERT INTO `purpose` VALUES('project-menu-needs', 'Necesidades', NULL, 'project');
INSERT INTO `purpose` VALUES('project-menu-supporters', 'Cofinanciadores', NULL, 'project');
INSERT INTO `purpose` VALUES('project-menu-updates', 'Novedades', NULL, 'project');
INSERT INTO `purpose` VALUES('project-messages-answer_it', 'Responder', NULL, 'project');
INSERT INTO `purpose` VALUES('project-messages-closed', 'Aun no se pueden enviar mensajes al proyecto', NULL, 'project');
INSERT INTO `purpose` VALUES('project-messages-send_direct-header', 'Envía un mensaje al impulsor/a del proyecto', NULL, 'project');
INSERT INTO `purpose` VALUES('project-messages-send_message-button', 'Enviar', NULL, 'project');
INSERT INTO `purpose` VALUES('project-messages-send_message-header', 'Escribe tu mensaje', NULL, 'project');
INSERT INTO `purpose` VALUES('project-messages-send_message-your_answer', 'Escribe aquí tu respuesta', NULL, 'general');
INSERT INTO `purpose` VALUES('project-review-confirm_mail-fail', 'Ha habido algún error al enviar el mensaje de confirmación de recepción', NULL, 'bluead');
INSERT INTO `purpose` VALUES('project-review-confirm_mail-success', 'Mensaje de confirmación de recepción para revisión enviado correctamente', NULL, 'bluead');
INSERT INTO `purpose` VALUES('project-review-request_mail-fail', 'Ha habido algún error al enviar la solicitud de revisión', NULL, 'bluead');
INSERT INTO `purpose` VALUES('project-review-request_mail-success', 'Mensaje de solicitud de revisión enviado correctamente', NULL, 'bluead');
INSERT INTO `purpose` VALUES('project-rewards-header', 'Retorno', NULL, 'project');
INSERT INTO `purpose` VALUES('project-rewards-individual_reward-limited', 'Recompensa limitada', NULL, 'project');
INSERT INTO `purpose` VALUES('project-rewards-individual_reward-title', 'Recompensas individuales', NULL, 'project');
INSERT INTO `purpose` VALUES('project-rewards-individual_reward-units_left', 'Quedan <span class="left">%s</span> unidades', 1, 'project');
INSERT INTO `purpose` VALUES('project-rewards-social_reward-title', 'Retorno colectivo', NULL, 'project');
INSERT INTO `purpose` VALUES('project-rewards-supertitle', 'Qué ofrece a cambio', NULL, 'project');
INSERT INTO `purpose` VALUES('project-share-header', 'Comparte este proyecto', NULL, 'project');
INSERT INTO `purpose` VALUES('project-share-pre_header', 'Deja saber a tu red que', NULL, 'project');
INSERT INTO `purpose` VALUES('project-side-investors-header', 'Ya han aportado', NULL, 'project');
INSERT INTO `purpose` VALUES('project-spread-embed_code', 'Código Embed', NULL, 'project');
INSERT INTO `purpose` VALUES('project-spread-header', 'Difunde este proyecto', NULL, 'project');
INSERT INTO `purpose` VALUES('project-spread-pre_widget', 'Difunde este proyecto', NULL, 'project');
INSERT INTO `purpose` VALUES('project-spread-widget', 'Widget del proyecto', NULL, 'project');
INSERT INTO `purpose` VALUES('project-spread-widget_legend', 'Copia y pega el código en tu web o blog y ayuda a difundir este proyecto', NULL, 'project');
INSERT INTO `purpose` VALUES('project-spread-widget_title', 'publica el widget del proyecto', NULL, 'project');
INSERT INTO `purpose` VALUES('project-support-supertitle', 'Necesidades económicas', NULL, 'project');
INSERT INTO `purpose` VALUES('project-view-categories-title', 'Categorías', NULL, 'project');
INSERT INTO `purpose` VALUES('project-view-metter-days', 'Quedan', NULL, 'project');
INSERT INTO `purpose` VALUES('project-view-metter-got', 'Obtenido', NULL, 'project');
INSERT INTO `purpose` VALUES('project-view-metter-investment', 'Cofinanciación', NULL, 'project');
INSERT INTO `purpose` VALUES('project-view-metter-investors', 'Cofinanciadores', NULL, 'project');
INSERT INTO `purpose` VALUES('project-view-metter-minimum', 'Mínimo', NULL, 'project');
INSERT INTO `purpose` VALUES('project-view-metter-optimum', 'Óptimo', NULL, 'project');
INSERT INTO `purpose` VALUES('recover-email-sended', 'Te hemos enviado un email para reestablecer la contraseña de tu cuenta. Verifica también la carpeta de correo no deseado o spam.', NULL, 'register');
INSERT INTO `purpose` VALUES('recover-request-fail', 'No se puede recuperar la contraseña de ninguna cuenta con estos datos', NULL, 'register');
INSERT INTO `purpose` VALUES('recover-token-incorrect', 'El código de recuperación de contraseña no es válido', NULL, 'register');
INSERT INTO `purpose` VALUES('register-confirm_mail-fail', 'Ha habido algún error al enviar el email de activación de cuenta. Por favor, contáctanos a %s', NULL, 'bluead');
INSERT INTO `purpose` VALUES('register-confirm_mail-success', 'Mensaje de activación de cuenta enviado. Si no está en tu buzón de correo, revisa la carpeta de /Spam', NULL, 'bluead');
INSERT INTO `purpose` VALUES('regular-admin_board', 'Panel admin', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-allsome', 'todos/algunos de', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-anonymous', 'Anónimo', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-ask', 'Preguntar', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-banner-metter', 'obtenido-de-quedan', NULL, 'banner');
INSERT INTO `purpose` VALUES('regular-by', 'Por:', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-collaborate', 'Colabora', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-create', 'Crea un proyecto', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-days', 'días', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-delete', 'Borrar', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-discover', 'Descubre proyectos', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-edit', 'Editar', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-facebook', 'Facebook', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-facebook-url', 'http://www.facebook.com/', NULL, 'url');
INSERT INTO `purpose` VALUES('regular-fail_mark', 'Archivado...', NULL, 'widget');
INSERT INTO `purpose` VALUES('regular-faq', 'Preguntas frecuentes', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-first', 'Primera', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-footer-contact', 'Contacto', NULL, 'footer');
INSERT INTO `purpose` VALUES('regular-footer-legal', 'Términos legales', NULL, 'footer');
INSERT INTO `purpose` VALUES('regular-footer-privacy', 'Política de privacidad', NULL, 'footer');
INSERT INTO `purpose` VALUES('regular-footer-terms', 'Condiciones de uso', NULL, 'footer');
INSERT INTO `purpose` VALUES('regular-google', 'Google+', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-google-url', 'https://plus.google.com/', NULL, 'url');
INSERT INTO `purpose` VALUES('regular-gotit_mark', 'Financiado!', NULL, 'widget');
INSERT INTO `purpose` VALUES('regular-go_up', 'Subir', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-header-about', 'Sobre Goteo', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-header-blog', 'Blog', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-header-faq', 'FAQ', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-header-glossary', 'Principios para una economía abierta', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-hello', 'Hola', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-home', 'Inicio', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-identica', 'Identi.ca', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-identica-url', 'http://identi.ca/', NULL, 'url');
INSERT INTO `purpose` VALUES('regular-im', 'Soy', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-invest', 'Aportar', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-investing', 'Aportando', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-invest_it', 'Cofinancia este proyecto', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-keepiton_mark', 'Mínimo conseguido', NULL, 'widget');
INSERT INTO `purpose` VALUES('regular-last', 'Última', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-license', 'Licencia', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-linkedin', 'LinkedIn', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-linkedin-url', 'http://es.linkedin.com/in/', NULL, 'url');
INSERT INTO `purpose` VALUES('regular-login', 'Accede', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-logout', 'Cerrar sesión', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-looks_for', 'busca:', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-main-header', 'Goteo.org', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-mandatory', 'Campo obligatorio!', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-media_legend', 'Leyenda', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-menu', 'Menú', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-message_fail', 'Ha habido algun error al enviar el mensaje', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-message_success', 'Mensaje enviado correctamente', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-months', 'meses', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-more_info', '+ info', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-news', 'Noticias:', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-new_project', 'Proyecto nuevo', NULL, 'project');
INSERT INTO `purpose` VALUES('regular-no', 'No', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-onrun_mark', 'En marcha!', NULL, 'widget');
INSERT INTO `purpose` VALUES('regular-preview', 'Previsualizar', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-projects', 'proyectos', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-published_no', 'Borrador', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-published_yes', 'Publicado', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-read_more', 'Leer más', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-review_board', 'Panel revisor', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-round', 'ª ronda', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-save', 'Guardar', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-search', 'Buscar', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-see_all', 'Ver todos', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-see_blog', 'Blog', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-see_details', 'Ver detalles', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-see_more', 'Ver más', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-send_message', 'Enviar mensaje', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-share-facebook', 'Goteo en Facebook', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-share-rss', 'RSS/BLOG', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-share-twitter', 'Síguenos en Twitter', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-share_this', 'Compartir en:', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-sorry', 'Lo sentimos', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-success_mark', 'Exitoso!', NULL, 'widget');
INSERT INTO `purpose` VALUES('regular-thanks', 'Gracias', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-total', 'Total', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-translate_board', 'Panel traductor', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-twitter', 'Twitter', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-twitter-url', 'http://twitter.com/#!/', NULL, 'url');
INSERT INTO `purpose` VALUES('regular-view_project', 'Ver proyecto', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-weeks', 'semanas', NULL, 'general');
INSERT INTO `purpose` VALUES('regular-yes', 'Sí', NULL, 'general');
INSERT INTO `purpose` VALUES('review-ajax-alert', 'Los criterios y los campos de evaluación / mejoras se guardan automáticamente al modificarse', NULL, 'bluead');
INSERT INTO `purpose` VALUES('review-closed-alert', 'Has dado por terminada esta revisión, no puedes realizar más cambios', NULL, 'bluead');
INSERT INTO `purpose` VALUES('rewards-field-individual_reward-amount', 'Importe financiado', NULL, 'rewards');
INSERT INTO `purpose` VALUES('rewards-field-individual_reward-description', 'Descripción', NULL, 'rewards');
INSERT INTO `purpose` VALUES('rewards-field-individual_reward-other', 'Especificar el tipo de recompensa', NULL, 'rewards');
INSERT INTO `purpose` VALUES('rewards-field-individual_reward-reward', 'Recompensa', NULL, 'rewards');
INSERT INTO `purpose` VALUES('rewards-field-individual_reward-type', 'Tipo de recompensa', NULL, 'rewards');
INSERT INTO `purpose` VALUES('rewards-field-individual_reward-units', 'Unidades', NULL, 'rewards');
INSERT INTO `purpose` VALUES('rewards-field-social_reward-description', 'Descripción', NULL, 'rewards');
INSERT INTO `purpose` VALUES('rewards-field-social_reward-license', 'Opciones de licencia', NULL, 'rewards');
INSERT INTO `purpose` VALUES('rewards-field-social_reward-other', 'Especificar el tipo de retorno', NULL, 'rewards');
INSERT INTO `purpose` VALUES('rewards-field-social_reward-reward', 'Retorno', NULL, 'rewards');
INSERT INTO `purpose` VALUES('rewards-field-social_reward-type', 'Tipo de retorno', NULL, 'rewards');
INSERT INTO `purpose` VALUES('rewards-fields-individual_reward-title', 'Recompensas individuales', NULL, 'rewards');
INSERT INTO `purpose` VALUES('rewards-fields-social_reward-title', 'Retornos colectivos', NULL, 'rewards');
INSERT INTO `purpose` VALUES('rewards-main-header', 'Retornos y recompensas', NULL, 'rewards');
INSERT INTO `purpose` VALUES('social-account-facebook', 'http://www.facebook.com/pages/Goteo/268491113192109', NULL, 'social');
INSERT INTO `purpose` VALUES('social-account-google', 'https://plus.google.com/b/116559557256583965659/', NULL, 'social');
INSERT INTO `purpose` VALUES('social-account-identica', 'http://identi.ca/goteofunding', NULL, 'social');
INSERT INTO `purpose` VALUES('social-account-linkedin', 'Página Goteo LinkedIn', NULL, 'social');
INSERT INTO `purpose` VALUES('social-account-twitter', 'http://twitter.com/goteofunding', NULL, 'social');
INSERT INTO `purpose` VALUES('step-1', 'Perfil', NULL, 'profile');
INSERT INTO `purpose` VALUES('step-2', 'Promotor/a', NULL, 'personal');
INSERT INTO `purpose` VALUES('step-3', 'Descripción', NULL, 'overview');
INSERT INTO `purpose` VALUES('step-4', 'Costes', NULL, 'costs');
INSERT INTO `purpose` VALUES('step-5', 'Retorno', NULL, 'rewards');
INSERT INTO `purpose` VALUES('step-6', 'Colaboraciones', NULL, 'supports');
INSERT INTO `purpose` VALUES('step-7', 'Previsualización', NULL, 'preview');
INSERT INTO `purpose` VALUES('step-costs', 'Paso 4: Proyecto / Costes', NULL, 'costs');
INSERT INTO `purpose` VALUES('step-overview', 'Paso 3: Descripción del proyecto', NULL, 'overview');
INSERT INTO `purpose` VALUES('step-preview', 'Proyecto / Previsualización', NULL, 'preview');
INSERT INTO `purpose` VALUES('step-rewards', 'Paso 5: Proyecto / Retornos', NULL, 'rewards');
INSERT INTO `purpose` VALUES('step-supports', 'Paso 6: Proyecto / Colaboraciones', NULL, 'supports');
INSERT INTO `purpose` VALUES('step-userPersonal', 'Paso 2: Datos personales', NULL, 'personal');
INSERT INTO `purpose` VALUES('step-userProfile', 'Paso 1: Usuario / Perfil', NULL, 'profile');
INSERT INTO `purpose` VALUES('supports-field-description', 'Descripción', NULL, 'supports');
INSERT INTO `purpose` VALUES('supports-field-support', 'Resumen', NULL, 'supports');
INSERT INTO `purpose` VALUES('supports-field-type', 'Tipo de ayuda', NULL, 'supports');
INSERT INTO `purpose` VALUES('supports-fields-support-title', 'Colaboraciones', NULL, 'supports');
INSERT INTO `purpose` VALUES('supports-main-header', 'Solicitud de colaboraciones', NULL, 'supports');
INSERT INTO `purpose` VALUES('tooltip-dashboard-user-access_data', 'Estos son tus datos actuales de acceso. Lo único que no se puede cambiar es el login de usuario.', NULL, 'general');
INSERT INTO `purpose` VALUES('tooltip-dashboard-user-change_email', 'Desde aquí puedes cambiar la dirección de correo electrónico en que recibes los mensajes de Goteo.', NULL, 'general');
INSERT INTO `purpose` VALUES('tooltip-dashboard-user-change_password', 'Desde aquí puedes cambiar la contraseña con que accedes a Goteo.', NULL, 'general');
INSERT INTO `purpose` VALUES('tooltip-dashboard-user-confirm_email', 'Confirma la nueva dirección de correo electrónico en que quieres recibir los mensajes de Goteo.', NULL, 'general');
INSERT INTO `purpose` VALUES('tooltip-dashboard-user-confirm_password', 'Confirma la nueva contraseña con que quieres acceder a Goteo.', NULL, 'general');
INSERT INTO `purpose` VALUES('tooltip-dashboard-user-new_email', 'Indica la nueva dirección de correo electrónico en que quieres recibir los mensajes de Goteo.', NULL, 'general');
INSERT INTO `purpose` VALUES('tooltip-dashboard-user-new_password', 'Escribe la nueva contraseña con que quieres acceder a Goteo.', NULL, 'general');
INSERT INTO `purpose` VALUES('tooltip-dashboard-user-user_password', 'Escribe la contraseña actual con que accedes a Goteo.', NULL, 'general');
INSERT INTO `purpose` VALUES('tooltip-project-about', 'Describe brevemente el proyecto de modo conceptual, técnico o práctico. Por ejemplo detallando sus características de funcionamiento, o en qué partes consistirá. Piensa en cómo será una vez acabado y todo lo que la gente podrá hacer con él.', NULL, 'overview');
INSERT INTO `purpose` VALUES('tooltip-project-category', 'Selecciona tantas categorías como creas necesario para describir el proyecto, basándote en todo lo que has descrito arriba. Mediante estas palabras clave lo podremos hacer llegar a diferentes usuarios de Goteo.', NULL, 'overview');
INSERT INTO `purpose` VALUES('tooltip-project-comment', '¿Tienes dudas o comentarios para que las lea el administrador de Goteo? Éste es lugar para explicar alguna parte de lo que has escrito de la que no estás seguro,  para proponer mejoras, etc.', NULL, 'preview');
INSERT INTO `purpose` VALUES('tooltip-project-contract_birthdate', 'Indica la fecha de tu nacimiento. No se hará pública en ningún caso, nos interesa por temas estadísticos.', NULL, 'personal');
INSERT INTO `purpose` VALUES('tooltip-project-contract_data', 'Ya sea como persona física o bien jurídica, es necesario que alguien figure como promotor/a del proyecto, y también para la interlocución con el equipo de Goteo. No tiene que coincidir necesariamente con el perfil de usuario del apartado anterior.', NULL, 'personal');
INSERT INTO `purpose` VALUES('tooltip-project-contract_email', 'Dirección de correo electrónica principal asociada al proyecto. Aquí recibirás las notificaciones y mensajes del equipo de Goteo en relación al proyecto propuesto.', NULL, 'personal');
INSERT INTO `purpose` VALUES('tooltip-project-contract_entity', 'Selecciona "Persona física" en el caso de que tú seas el/la promotor/a del proyecto y te representes a ti mismo/a. Si el promotor es un grupo es necesario para elegir la segunda opción que tenga un CIF propio, en ese caso elige "Persona jurídica". ', NULL, 'personal');
INSERT INTO `purpose` VALUES('tooltip-project-contract_name', 'Deben ser tu nombre y apellidos reales. Ten en cuenta que figurarás como responsable del proyecto.', NULL, 'personal');
INSERT INTO `purpose` VALUES('tooltip-project-contract_nif', 'Tu número de NIF o NIE con cifras y letra.', NULL, 'personal');
INSERT INTO `purpose` VALUES('tooltip-project-contract_surname', 'P2-Consejo-5  Consejo para rellenar el apellido del responsable del proyecto', NULL, 'personal');
INSERT INTO `purpose` VALUES('tooltip-project-cost-amount', 'Especifica el importe en euros de lo que consideras que implica este coste. No utilices puntos para las cifras de miles ok?', NULL, 'costs');
INSERT INTO `purpose` VALUES('tooltip-project-cost-cost', 'Introduce un título lo más descriptivo posible de este coste.', NULL, 'costs');
INSERT INTO `purpose` VALUES('tooltip-project-cost-dates', 'Indica entre qué fechas calculas que se va a llevar a cabo esa tarea o cubrir ese coste. Planifícalo empezando no antes de dos meses a partir de ahora, pues hay que considerar el plazo para revisar la propuesta, publicarla si es seleccionada y los 40 días de la primera financiación. No incluyas en este calendario la agenda de lo desarrollado anteriormente aunque es bueno que lo expliques en la descripción del proyecto. En la agenda sólo nos interesan las fases que quedan por hacer y buscan ser cofinanciadas.', NULL, 'costs');
INSERT INTO `purpose` VALUES('tooltip-project-cost-description', 'Explica brevemente en qué consiste este coste.', NULL, 'costs');
INSERT INTO `purpose` VALUES('tooltip-project-cost-required', 'Este punto es muy importante: en cada coste que introduzcas tienes que marcar si es imprescindible o bien adicional. Todos los costes marcados como imprescindibles se sumarán dando el valor del importe de financiación mínimo para el proyecto. La suma de los costes adicionales, en cambio, se podrá obtener durante la segunda ronda de financiación, después de haber obtenido los fondos imprescindibles.', NULL, 'costs');
INSERT INTO `purpose` VALUES('tooltip-project-cost-type', 'Aquí debes especificar el tipo de coste: vinculado a una tarea (algo que requiere la habilidad o conocimientos de alguien), la obtención de material (consumibles, materias primas) o bien infraestructura (espacios, equipos, mobiliario).', NULL, 'costs');
INSERT INTO `purpose` VALUES('tooltip-project-cost-type-material', 'Materiales necesarios para el proyecto como herramientas, papelería, equipos informáticos, etc.', NULL, 'types');
INSERT INTO `purpose` VALUES('tooltip-project-cost-type-structure', 'Inversión en costes vinculados a un local, medio de transporte u otras infraestructuras básicas para llevar a cabo el proyecto.  ', NULL, 'types');
INSERT INTO `purpose` VALUES('tooltip-project-cost-type-task', 'Tareas donde invertir conocimientos y/o habilidades para desarrollar alguna parte del proyecto.', NULL, 'types');
INSERT INTO `purpose` VALUES('tooltip-project-costs', 'Cuanto más precisión en el desglose mejor valorará Goteo la información general del proyecto. ', NULL, 'costs');
INSERT INTO `purpose` VALUES('tooltip-project-currently', 'Indica en qué fase se encuentra el proyecto actualmente respecto a su proceso de creación o ejecución.', NULL, 'overview');
INSERT INTO `purpose` VALUES('tooltip-project-description', 'Describe el proyecto con un mínimo de 80 palabras (con menos marcará error). Explícalo de modo que sea fácil de entender para cualquier persona. Intenta darle un enfoque atractivo y social, resumiendo sus puntos fuertes, qué lo hace único, innovador o especial.', NULL, 'overview');
INSERT INTO `purpose` VALUES('tooltip-project-entity_cif', 'Escribe el CIF (letra + número) de la organización.', NULL, 'personal');
INSERT INTO `purpose` VALUES('tooltip-project-entity_name', 'Escribe el nombre de la organización tal como aparece en su CIF.', NULL, 'personal');
INSERT INTO `purpose` VALUES('tooltip-project-entity_office', 'Escribe el cargo con el que representas a la organización (secretario/a, presidente/a, vocal...). ', NULL, 'personal');
INSERT INTO `purpose` VALUES('tooltip-project-goal', 'Enumera las metas principales del proyecto, a corto y largo plazo, en todos los aspectos que consideres importante destacar. Se trata de otra oportunidad para contactar y conseguir el apoyo de gente que simpatice con esos objetivos.', NULL, 'overview');
INSERT INTO `purpose` VALUES('tooltip-project-image', 'Pueden ser esquemas, pantallazos, fotografías, ilustraciones, storyboards, etc. (su licencia de autoría debe ser compatible con la que selecciones en el apartado 5). Te recomendamos que sean diversas y de buena resolución. Puedes subir tantas como quieras!', NULL, 'overview');
INSERT INTO `purpose` VALUES('tooltip-project-image_upload', 'BORRAR', NULL, 'overview');
INSERT INTO `purpose` VALUES('tooltip-project-individual_reward-amount', 'Importe a cambio del cual se puede obtener este tipo de recompensa. ', NULL, 'rewards');
INSERT INTO `purpose` VALUES('tooltip-project-individual_reward-description', 'Explica brevemente en qué consistirá la recompensa para quienes cofinancien con este importe el proyecto.', NULL, 'rewards');
INSERT INTO `purpose` VALUES('tooltip-project-individual_reward-icon-other', 'Especifica brevemente en qué consistirá este otro tipo de recompensa individual.', NULL, 'rewards');
INSERT INTO `purpose` VALUES('tooltip-project-individual_reward-reward', 'Intenta que el título sea lo más descriptivo posible. Recuerda que puedes añadir más recompensas a continuación.', NULL, 'rewards');
INSERT INTO `purpose` VALUES('tooltip-project-individual_reward-type', 'Selecciona el tipo de recompensa que el proyecto puede ofrecer a la gente que aporta esta cantidad.', NULL, 'rewards');
INSERT INTO `purpose` VALUES('tooltip-project-individual_reward-units', 'Cantidad limitada de ítems que se pueden ofrecer individualizadamente a cambio de ese importe. Calcula que la suma total de todas las recompensas individuales del proyecto se acerquen al presupuesto mínimo de financiación que has establecido. También la posibilidad de incorporar las recompensas previas a medida que suba el importe, puedes empezar diciendo "Todo lo anterior más..."  ', NULL, 'rewards');
INSERT INTO `purpose` VALUES('tooltip-project-individual_rewards', 'Aquí debes especificar la recompensa para quien apoye el proyecto, vinculada a una cantidad de dinero concreta. Elige bien lo que ofreces, intenta que sean productos/servicios atractivos o ingeniosos pero que no generen gastos extra de producción. Si no hay más remedio que tener esos gastos extra, calcula lo que cuesta producir esa recompensa (tiempo, materiales, envíos, etc) y oferta un número limitado. Piensa que tendrás que cumplir con todos esos compromisos al final de la producción del proyecto. ', NULL, 'rewards');
INSERT INTO `purpose` VALUES('tooltip-project-keywords', 'A medida que introduzcas texto el sistema te sugerirá palabras clave que ya han escrito otros usuarios. Estas categorías ayudan a vincular tu proyecto con personas afines.', NULL, 'overview');
INSERT INTO `purpose` VALUES('tooltip-project-lang', 'Indica en qué idioma cumplimentas el formulario del proyecto.', NULL, 'overview');
INSERT INTO `purpose` VALUES('tooltip-project-main_address', 'Dirección fiscal de la persona u organización (según proceda).', NULL, 'personal');
INSERT INTO `purpose` VALUES('tooltip-project-media', 'Copia y pega la dirección URL de un vídeo de presentación del proyecto, publicado previamente en Youtube o Vimeo. Esta parte es fundamental para atraer la atención de potenciales cofinanciadores y colaboradores, y cuanto más original sea mejor. Te recomendamos que tenga una duración de entre 2 y 4 minutos. ', NULL, 'overview');
INSERT INTO `purpose` VALUES('tooltip-project-motivation', 'Explica qué motivos o circunstancias te han llevado a idear el proyecto, así como las comunidades o usuarios a las que va destinado. Te ayudará a conectar con personas movidas por ese mismo tipo de intereses, problemáticas o gustos.', NULL, 'overview');
INSERT INTO `purpose` VALUES('tooltip-project-name', 'Escribe un nombre para titular el proyecto. Cuanto más breve mejor, para hacerlo más descriptivo puedes ampliarlo en el siguiente apartado.', NULL, 'overview');
INSERT INTO `purpose` VALUES('tooltip-project-nsupport', 'Consejo para rellenar una nueva colaboración', NULL, 'supports');
INSERT INTO `purpose` VALUES('tooltip-project-phone', 'Número de teléfono móvil o fijo, con su prefijo de marcado.', NULL, 'personal');
INSERT INTO `purpose` VALUES('tooltip-project-post_address', 'Indica en caso necesario una dirección postal detallada.', NULL, 'personal');
INSERT INTO `purpose` VALUES('tooltip-project-project_location', 'Indica el lugar donde se desarrollará el proyecto, en qué población o poblaciones se encuentra su impulsor o impulsores principales.', NULL, 'overview');
INSERT INTO `purpose` VALUES('tooltip-project-related', 'Resume tu trayectoria o la del grupo impulsor del proyecto. ¿Qué experiencia tiene relacionada con la propuesta? ¿Con qué equipo de personas, recursos y/o infraestructuras cuenta? ', NULL, 'overview');
INSERT INTO `purpose` VALUES('tooltip-project-resource', 'Indica aquí si cuentas con recursos adicionales, aparte de los que solicitas, para llevar a cabo el proyecto: fuentes de financiación, recursos propios o bien ya has hecho acopio de materiales. Puede suponer un aliciente para los potenciales cofinanciadores del proyecto.', NULL, 'costs');
INSERT INTO `purpose` VALUES('tooltip-project-schedule', 'Visualización de cómo queda la agenda de producción de tu proyecto. Recuerda que sólo debes señalar las nuevas tareas a realizar, no las que ya se hayan efectuado.', NULL, 'general');
INSERT INTO `purpose` VALUES('tooltip-project-scope', 'Indica el impacto geográfico que aspira a tener el proyecto (cada categoría incluye la anterior). ', NULL, 'project');
INSERT INTO `purpose` VALUES('tooltip-project-social_reward-description', 'Explica brevemente el tipo de retorno colectivo que ofrecerá o permitirá el proyecto.', NULL, 'rewards');
INSERT INTO `purpose` VALUES('tooltip-project-social_reward-icon-other', 'Especifica brevemente en qué consistirá este otro tipo de retorno colectivo.', NULL, 'rewards');
INSERT INTO `purpose` VALUES('tooltip-project-social_reward-license', 'Aquí debes seleccionar una licencia de entre cada una del grupo que se muestran. Te recomendamos leerlas con calma antes de decidir, pero piensa que un aspecto importante para Goteo es que los proyectos dispongan de licencias lo más abiertas posible.', NULL, 'rewards');
INSERT INTO `purpose` VALUES('tooltip-project-social_reward-reward', 'Intenta que el título sea lo más descriptivo posible. Recuerda que puedes añadir más recompensas a continuación.', NULL, 'rewards');
INSERT INTO `purpose` VALUES('tooltip-project-social_reward-type', 'Especifica el tipo de retorno: ARCHIVOS DIGITALES como música, vídeo, documentos de texto, etc. CÓDIGO FUENTE de software informático. DISEÑOS de  planos o patrones. MANUALES en forma de kits, business plans, “how tos” o recetas. SERVICIOS como talleres, cursos, asesorías, acceso a websites, bases de datos online. ', NULL, 'rewards');
INSERT INTO `purpose` VALUES('tooltip-project-social_rewards', 'Define el tipo de retorno o retornos del proyecto a los que se podrá acceder abiertamente, y la licencia que los debe regular. Si tienes dudas sobre qué opción escoger o lo que se adaptaría mejor a tu caso, <a href="http://www.goteo.org/contact" target="new">contáctanos</a> y te asesoraremos en este punto.', NULL, 'rewards');
INSERT INTO `purpose` VALUES('tooltip-project-subtitle', 'Define con una frase un subtítulo que acabe de explicar en qué consistirá la iniciativa, que permita hacerse una idea mínima de para qué sirve o en qué consiste. Aparecerá junto al título del proyecto.', NULL, 'overview');
INSERT INTO `purpose` VALUES('tooltip-project-support', 'Consejo para editar colaboraciones existentes', NULL, 'supports');
INSERT INTO `purpose` VALUES('tooltip-project-support-description', 'Explica brevemente en qué consiste la ayuda que necesita el proyecto, para facilitar que la gente la reconozca y se anime a colaborar. \r\n', NULL, 'supports');
INSERT INTO `purpose` VALUES('tooltip-project-support-support', 'Título descriptivo sobre la colaboración necesaria.', NULL, 'supports');
INSERT INTO `purpose` VALUES('tooltip-project-support-type', 'Selecciona si el proyecto necesita ayuda en tareas concretas  o bien préstamos (de material, infraestructura, etc).  ', NULL, 'supports');
INSERT INTO `purpose` VALUES('tooltip-project-support-type-lend', 'Préstamo temporal de material necesario para el proyecto, o bien de cesión de infraestructuras o espacios por un periodo determinado. También puede implicar préstamos permanentes, o sea regalos :)', NULL, 'types');
INSERT INTO `purpose` VALUES('tooltip-project-support-type-task', 'Colaboración que requiera una habilidad para una tarea específica, ya sea a distancia mediante ordenador o bien presencialmente.', NULL, 'types');
INSERT INTO `purpose` VALUES('tooltip-project-supports', 'En Goteo los proyectos pueden recibir otro tipo de ayudas además de aportaciones monetarias. Hay gente que a lo mejor no puede ayudar económicamente pero sí con su talento, tiempo, energía, etc.', NULL, 'supports');
INSERT INTO `purpose` VALUES('tooltip-project-totals', 'Este gráfico muestra la suma de costes imprescindibles (mínimos para realizar el proyecto) y la suma de costes secundarios (importe óptimo) para las dos rondas de financiación. La primera ronda es de 40 días, para conseguir el importe mínimo imprescindible. Sólo si se consigue ese volumen de financiación se puede optar a la segunda ronda, de 40 días más, para llegar al presupuesto óptimo. A diferencia de la primera, en la segunda ronda se obtiene todo lo recaudado (aunque no se haya llegado al mínimo). ', NULL, 'costs');
INSERT INTO `purpose` VALUES('tooltip-project-usubs', 'Marca la casilla en caso de que hayas subtitulado a otros idiomas el vídeo mediante Universal Subtitles: http://www.universalsubtitles.org/', NULL, 'overview');
INSERT INTO `purpose` VALUES('tooltip-project-video', 'Considera aquí la posibilidad de publicar y enlazar un vídeo (en Youtube o Vimeo) donde expliques brevemente a la cámara el porqué de tu proyecto. Se trata de algo que pueda complementar el vídeo principal, con una persona que transmita su necesidad u originalidad, del modo más directo posible. Si te da corte hablar a la cámara, también puede ser alguna persona que conoces y sigue el proyecto o la idea y pueda hacer esta aportación como "fan". La empatía y necesidad de ver a alguien al otro lado del proyecto es muy importante para determinado tipo de cofinanciadores. ', NULL, 'overview');
INSERT INTO `purpose` VALUES('tooltip-updates-allow_comments', 'tooltip-updates-allow_comments', NULL, 'project');
INSERT INTO `purpose` VALUES('tooltip-updates-date', 'tooltip-updates-date', NULL, 'project');
INSERT INTO `purpose` VALUES('tooltip-updates-home', 'Texto tooltip-updates-home', NULL, 'project');
INSERT INTO `purpose` VALUES('tooltip-updates-image', 'tooltip-updates-image', NULL, 'project');
INSERT INTO `purpose` VALUES('tooltip-updates-image_upload', 'tooltip-updates-image_upload', NULL, 'project');
INSERT INTO `purpose` VALUES('tooltip-updates-media', 'tooltip-updates-media', NULL, 'project');
INSERT INTO `purpose` VALUES('tooltip-updates-tags', 'Texto tooltip-updates-tags', NULL, 'project');
INSERT INTO `purpose` VALUES('tooltip-updates-text', 'tooltip-updates-text', NULL, 'project');
INSERT INTO `purpose` VALUES('tooltip-updates-title', 'tooltip-updates-title', NULL, 'project');
INSERT INTO `purpose` VALUES('tooltip-user-about', 'Como red social, Goteo pretende ayudar a difundir y financiar proyectos interesantes entre el máximo de gente posible. Para eso es importante la información que puedas compartir sobre tus habilidades o experiencia (profesional, académica, aficiones, etc).\r\n', NULL, 'profile');
INSERT INTO `purpose` VALUES('tooltip-user-avatar_upload', 'Texto tooltip subir imagen usuario', NULL, 'profile');
INSERT INTO `purpose` VALUES('tooltip-user-contribution', 'Explica brevemente tus habilidades o los ámbitos en que podrías ayudar a un proyecto (traduciendo, difundiendo, testeando, programando, enseñando, etc).', NULL, 'profile');
INSERT INTO `purpose` VALUES('tooltip-user-facebook', 'Esta red social puede ayudar a que difundas proyectos de Goteo que te interesan entre amigos y familiares.', NULL, 'profile');
INSERT INTO `purpose` VALUES('tooltip-user-google', 'La red social de Google+ es muy nueva pero también puedes indicar tu usuario si ya la usas :)', NULL, 'profile');
INSERT INTO `purpose` VALUES('tooltip-user-identica', 'Este canal puede ayudar a que difundas proyectos de Goteo entre la comunidad afín al software libre.', NULL, 'profile');
INSERT INTO `purpose` VALUES('tooltip-user-image', 'No es obligatorio que pongas una fotografía en tu perfil, pero ayuda a que los demás usuarios te identifiquen.', NULL, 'personal');
INSERT INTO `purpose` VALUES('tooltip-user-interests', 'Indica el tipo de proyectos que pueden conectar con tus intereses para cofinanciarlos y/o aportar con otros recursos, conocimientos o habilidades. Estas categorías son transversales, puedes seleccionar más de una.', NULL, 'profile');
INSERT INTO `purpose` VALUES('tooltip-user-keywords', 'A medida que introduzcas texto el sistema te sugerirá palabras clave que ya han escrito otros usuarios. Estas categorías ayudan a vincular tu perfil con otras personas y con proyectos concretos.', NULL, 'profile');
INSERT INTO `purpose` VALUES('tooltip-user-linkedin', 'Esta red social puede ayudar a que difundas proyectos de Goteo que te interesan entre contactos profesionales.', NULL, 'profile');
INSERT INTO `purpose` VALUES('tooltip-user-location', 'Este dato es importante para poderte vincular con un grupo local de Goteo.', NULL, 'profile');
INSERT INTO `purpose` VALUES('tooltip-user-name', 'Tu nombre o nickname dentro de Goteo. Lo puedes cambiar siempre que quieras (ojo: no es lo mismo que el login de acceso, que ya no se puede modificar).', NULL, 'profile');
INSERT INTO `purpose` VALUES('tooltip-user-twitter', 'Esta red social puede ayudar a que difundas proyectos de Goteo de manera ágil y viral.', NULL, 'profile');
INSERT INTO `purpose` VALUES('tooltip-user-webs', 'Indica las direcciones URL de páginas personales o de otro tipo vinculadas a ti.', NULL, 'profile');
INSERT INTO `purpose` VALUES('translate-home-guide', 'Mensaje para el traductor', NULL, 'general');
INSERT INTO `purpose` VALUES('user-account-inactive', 'La cuenta está desactivada. Debes recuperar la contraseña para activarla de nuevo', NULL, 'general');
INSERT INTO `purpose` VALUES('user-activate-already-active', 'La cuenta de usuario ya está activada', NULL, 'register');
INSERT INTO `purpose` VALUES('user-activate-fail', 'Error al activar la cuenta de usuario', NULL, 'general');
INSERT INTO `purpose` VALUES('user-activate-success', 'La cuenta de usuario se ha activado correctamente', NULL, 'register');
INSERT INTO `purpose` VALUES('user-changeemail-fail', 'Error al cambiar el email', NULL, 'general');
INSERT INTO `purpose` VALUES('user-changeemail-success', 'El email se ha cambiado con éxito ;)', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('user-changeemail-title', 'Cambiar email', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('user-changepass-confirm', 'Confirmar nueva contraseña', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('user-changepass-new', 'Nueva contraseña', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('user-changepass-old', 'Contraseña actual', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('user-changepass-title', 'Cambiar contraseña', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('user-email-change-sended', 'Te hemos enviado un email para que confirmes el cambio de dirección electrónica', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('user-login-required', 'Debes iniciar sesión para interactuar con la comunidad de Goteo', NULL, 'bluead');
INSERT INTO `purpose` VALUES('user-login-required-access', 'Debes iniciar sesión o solicitar permisos para acceder a esa sección', NULL, 'bluead');
INSERT INTO `purpose` VALUES('user-login-required-to_create', 'Debes iniciar sesión para crear un proyecto', NULL, 'bluead');
INSERT INTO `purpose` VALUES('user-login-required-to_invest', 'Debes iniciar sesión para cofinanciar un proyecto', NULL, 'bluead');
INSERT INTO `purpose` VALUES('user-login-required-to_message', 'Debes iniciar sesión para enviar mensajes', NULL, 'bluead');
INSERT INTO `purpose` VALUES('user-login-required-to_see', 'Debes iniciar sesión para ver esta página', NULL, 'bluead');
INSERT INTO `purpose` VALUES('user-login-required-to_see-supporters', 'Debes iniciar sesión para ver los cofinanciadores', NULL, 'bluead');
INSERT INTO `purpose` VALUES('user-message-send_personal-header', 'Envia un mensaje al usuario', NULL, 'public_profile');
INSERT INTO `purpose` VALUES('user-password-changed', 'Has cambiado tu contraseña', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('user-personal-saved', 'Datos personales actualizados', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('user-prefer-saved', 'Tus preferencias de notificación se han guardado correctamente', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('user-preferences-mailing', 'Bloquear el envio de newsletter', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('user-preferences-rounds', 'Bloquear notificaciones de progreso de los proyectos que apoyo', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('user-preferences-threads', 'Bloquear notificaciones de respuestas en los mensajes que yo inicio', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('user-preferences-updates', 'Bloquear notificaciones de novedades sobre los proyectos que apoyo', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('user-profile-saved', 'Información de perfil actualizada', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('user-register-success', 'El usuario se ha registrado correctamente. A continuación recibirás un mensaje de correo para activarlo.', NULL, 'general');
INSERT INTO `purpose` VALUES('user-save-fail', 'Ha habido algun problema al guardar los datos', NULL, 'dashboard');
INSERT INTO `purpose` VALUES('validate-cost-field-dates', 'Debes indicar las fechas de inicio y final de este coste para poder valorar mejor el proyecto.', NULL, 'costs');
INSERT INTO `purpose` VALUES('validate-project-costs-any_error', 'Falta alguna información en el desglose de costes', NULL, 'costs');
INSERT INTO `purpose` VALUES('validate-project-field-about', 'La explicación del proyecto es demasiado corta', NULL, 'overview');
INSERT INTO `purpose` VALUES('validate-project-field-costs', 'Recomendamos desglosar hasta 5 costes diferentes para mejorar la valoración del proyecto de cara a determinar si publicarlo en Goteo.', NULL, 'costs');
INSERT INTO `purpose` VALUES('validate-project-field-currently', 'Indica el estado del proyecto para mejorar la valoración del mismo, de cara a determinar si publicarlo en Goteo.', NULL, 'overview');
INSERT INTO `purpose` VALUES('validate-project-field-description', 'La descripción del proyecto es demasiado corta', NULL, 'overview');
INSERT INTO `purpose` VALUES('validate-project-individual_rewards', 'Indica hasta 5 recompensas individuales para mejorar la puntuación.', NULL, 'rewards');
INSERT INTO `purpose` VALUES('validate-project-individual_rewards-any_error', 'Falta alguna información sobre recompensas individuales', NULL, 'rewards');
INSERT INTO `purpose` VALUES('validate-project-social_rewards', 'Es obligatorio indicar como mínimo un retorno colectivo ', NULL, 'general');
INSERT INTO `purpose` VALUES('validate-project-social_rewards-any_error', 'Falta alguna información sobre retornos colectivos', NULL, 'rewards');
INSERT INTO `purpose` VALUES('validate-project-total-costs', 'El coste óptimo no puede superar en más de un 50% al coste mínimo. O subes los costes imprescindibles o bajas los costes adicionales.\r\n', NULL, 'general');
INSERT INTO `purpose` VALUES('validate-project-userProfile-any_error', 'Hay algún error en la dirección URL introducida', NULL, 'profile');
INSERT INTO `purpose` VALUES('validate-project-userProfile-web', 'Es recomendable indicar alguna web', NULL, 'profile');
INSERT INTO `purpose` VALUES('validate-project-value-contract_email', 'La dirección de email no es correcta', NULL, 'register');
INSERT INTO `purpose` VALUES('validate-project-value-contract_nif', 'El NIF no es correcto.', NULL, 'personal');
INSERT INTO `purpose` VALUES('validate-project-value-description', 'La descripción del proyecto es demasiado corta	', NULL, 'overview');
INSERT INTO `purpose` VALUES('validate-project-value-entity_cif', 'El CIF no es válido', NULL, 'personal');
INSERT INTO `purpose` VALUES('validate-project-value-keywords', 'Indica un mínimo de 5 palabras clave del proyecto para mejorar la valoración del mismo, de cara a determinar si publicarlo en Goteo.', NULL, 'overview');
INSERT INTO `purpose` VALUES('validate-project-value-phone', 'El formato de número de teléfono no es correcto.', NULL, 'personal');
INSERT INTO `purpose` VALUES('validate-register-value-email', 'El email introducido no es válido', NULL, 'register');
INSERT INTO `purpose` VALUES('validate-social_reward-license', 'Indicar una licencia para mejorar la puntuación', NULL, 'rewards');
INSERT INTO `purpose` VALUES('validate-user-field-about', 'Cuenta algo sobre ti, para mejorar la valoración del proyecto de cara a determinar si publicarlo en Goteo.', NULL, 'profile');
INSERT INTO `purpose` VALUES('validate-user-field-avatar', 'Pon una imagen de perfil para mejorar la valoración del proyecto de cara a determinar si publicarlo en Goteo.', NULL, 'profile');
INSERT INTO `purpose` VALUES('validate-user-field-contribution', 'Explica qué podrías aportar en Goteo para mejorar la valoración del proyecto de cara a determinar si publicarlo en la plataforma.', NULL, 'profile');
INSERT INTO `purpose` VALUES('validate-user-field-facebook', 'Pon tu cuenta de Facebook para mejorar la valoración del proyecto de cara a determinar si publicarlo en Goteo.', NULL, 'profile');
INSERT INTO `purpose` VALUES('validate-user-field-interests', 'Selecciona algún interés para mejorar la valoración del proyecto de cara a determinar si publicarlo en Goteo.', NULL, 'profile');
INSERT INTO `purpose` VALUES('validate-user-field-keywords', 'Indica hasta 5 palabras clave que te definan, para mejorar la valoración del proyecto de cara a determinar si publicarlo en Goteo.', NULL, 'profile');
INSERT INTO `purpose` VALUES('validate-user-field-linkedin', 'El campo de LinkedIn no es válido', NULL, 'profile');
INSERT INTO `purpose` VALUES('validate-user-field-location', 'El lugar de residencia del usuario no es válido', NULL, 'profile');
INSERT INTO `purpose` VALUES('validate-user-field-name', 'Pon tu nombre completo para mejorar la valoración del proyecto de cara a determinar si publicarlo en Goteo.', NULL, 'profile');
INSERT INTO `purpose` VALUES('validate-user-field-twitter', 'El usuario de Twitter no es válido', NULL, 'profile');
INSERT INTO `purpose` VALUES('validate-user-field-web', 'Debes poner la dirección (URL) de la web', NULL, 'profile');
INSERT INTO `purpose` VALUES('validate-user-field-webs', 'Pon tu página web para mejorar la valoración del proyecto de cara a determinar si publicarlo en Goteo.', NULL, 'profile');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `review`
--

DROP TABLE IF EXISTS `review`;
CREATE TABLE `review` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `to_checker` text,
  `to_owner` text,
  `score` int(2) NOT NULL DEFAULT '0',
  `max` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Revision para evaluacion de proyecto' AUTO_INCREMENT=10 ;

--
-- Volcar la base de datos para la tabla `review`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `review_comment`
--

DROP TABLE IF EXISTS `review_comment`;
CREATE TABLE `review_comment` (
  `review` bigint(20) unsigned NOT NULL,
  `user` varchar(50) NOT NULL,
  `section` varchar(50) NOT NULL,
  `evaluation` text,
  `recommendation` text,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`review`,`user`,`section`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Comentarios de revision';

--
-- Volcar la base de datos para la tabla `review_comment`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `review_score`
--

DROP TABLE IF EXISTS `review_score`;
CREATE TABLE `review_score` (
  `review` bigint(20) unsigned NOT NULL,
  `user` varchar(50) NOT NULL,
  `criteria` bigint(20) unsigned NOT NULL,
  `score` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`review`,`user`,`criteria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Puntuacion por citerio';

--
-- Volcar la base de datos para la tabla `review_score`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reward`
--

DROP TABLE IF EXISTS `reward`;
CREATE TABLE `reward` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project` varchar(50) NOT NULL,
  `reward` tinytext,
  `description` text,
  `type` varchar(50) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `other` tinytext COMMENT 'Otro tipo de recompensa',
  `license` varchar(50) DEFAULT NULL,
  `amount` int(5) DEFAULT NULL,
  `units` int(5) DEFAULT NULL,
  `fulsocial` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Retorno colectivo cumplido',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Retornos colectivos e individuales' AUTO_INCREMENT=1015 ;

--
-- Volcar la base de datos para la tabla `reward`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reward_lang`
--

DROP TABLE IF EXISTS `reward_lang`;
CREATE TABLE `reward_lang` (
  `id` int(20) NOT NULL,
  `lang` varchar(2) NOT NULL,
  `reward` tinytext,
  `description` text,
  `other` tinytext,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `reward_lang`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `role`
--

INSERT INTO `role` VALUES('admin', 'Administrador');
INSERT INTO `role` VALUES('checker', 'Revisor de proyectos');
INSERT INTO `role` VALUES('root', 'ROOT');
INSERT INTO `role` VALUES('superadmin', 'Super administrador');
INSERT INTO `role` VALUES('translator', 'Traductor de contenidos');
INSERT INTO `role` VALUES('user', 'Usuario mediocre');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sponsor`
--

DROP TABLE IF EXISTS `sponsor`;
CREATE TABLE `sponsor` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `url` tinytext,
  `image` int(10) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Patrocinadores' AUTO_INCREMENT=7 ;

--
-- Volcar la base de datos para la tabla `sponsor`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `support`
--

DROP TABLE IF EXISTS `support`;
CREATE TABLE `support` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project` varchar(50) NOT NULL,
  `support` tinytext,
  `description` text,
  `type` varchar(50) DEFAULT NULL,
  `thread` bigint(20) unsigned DEFAULT NULL COMMENT 'De la tabla message',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Colaboraciones' AUTO_INCREMENT=413 ;

--
-- Volcar la base de datos para la tabla `support`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `support_lang`
--

DROP TABLE IF EXISTS `support_lang`;
CREATE TABLE `support_lang` (
  `id` int(20) NOT NULL,
  `lang` varchar(2) NOT NULL,
  `support` tinytext,
  `description` text,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `support_lang`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tag`
--

DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `blog` bigint(20) unsigned NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tags de blogs (de nodo)' AUTO_INCREMENT=11 ;

--
-- Volcar la base de datos para la tabla `tag`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tag_lang`
--

DROP TABLE IF EXISTS `tag_lang`;
CREATE TABLE `tag_lang` (
  `id` bigint(20) unsigned NOT NULL,
  `lang` varchar(2) NOT NULL,
  `name` tinytext,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `tag_lang`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `template`
--

DROP TABLE IF EXISTS `template`;
CREATE TABLE `template` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `purpose` tinytext NOT NULL,
  `title` tinytext NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Plantillas emails automáticos' AUTO_INCREMENT=33 ;

--
-- Volcar la base de datos para la tabla `template`
--

INSERT INTO `template` VALUES(1, 'Mensaje de contacto', 'Plantilla para un mensaje de contacto desde Goteo', 'Contacto desde Goteo: %SUBJECT%', '');
INSERT INTO `template` VALUES(2, 'Mensaje a los cofinanciadores', 'Plantilla del mensaje masivo a cofinanciadores desde dashboard - gestión de retornos', 'Mensaje de un promotor', '');
INSERT INTO `template` VALUES(3, 'Mensaje al autor', 'Plantilla del mensaje al autor después de aportar a su proyecto', 'Mensaje de un/a cofinanciador/a de %PROJECTNAME%', '');
INSERT INTO `template` VALUES(4, 'Mensaje entre usuarios', 'Mensaje de un usuario a otro desde la página de perfil del destinatario', 'Mensaje personal de %USERNAME% desde Goteo', '');
INSERT INTO `template` VALUES(5, 'Confirmación de registro', 'Plantilla del mensaje de confirmación de registro', 'Confirmación de registro en Goteo', '');
INSERT INTO `template` VALUES(6, 'Recuperar contraseña', 'Plantilla para el mensaje al solicitar la recuperación de contraseña', 'Petición de recuperación de contraseña en Goteo', '');
INSERT INTO `template` VALUES(7, 'Cambio de email', 'Plantilla del mensaje al cambiar el email', 'Petición de cambio de email en Goteo', '');
INSERT INTO `template` VALUES(8, 'Confirmacion de proyecto enviado', 'Mensaje al usuario cuando envia un proyecto a revisión desde el preview del formulario', 'El proyecto %PROJECTNAME% ha pasado a fase de valoración', '');
INSERT INTO `template` VALUES(9, 'Darse de baja', 'Plantilla para el mensaje al solicitar la baja', 'Solicitud de baja en Goteo', '');
INSERT INTO `template` VALUES(10, 'Agradecimiento aporte', 'Mensaje al usuario después de aportar a un proyecto', 'Gracias por cofinanciar el proyecto %PROJECTNAME%', '');
INSERT INTO `template` VALUES(11, 'Comunicación desde admin', 'Plantilla para un mensaje de comunicación enviado desde admin a los destinatarios seleccionados', 'El asunto lo pone el admin', '');
INSERT INTO `template` VALUES(12, 'Mensaje al autor de un thread', 'Plantilla del mensaje al autor de un hilo de mensajes cuando hay una respuesta', 'Respuesta a tu mensaje en el proyecto %PROJECTNAME%', '');
INSERT INTO `template` VALUES(13, 'Aviso de 8 días para fallar', 'Mensaje al autor de un proyecto cuando este está proximo (8 dias) a fallar (no minimo)', 'Al proyecto %PROJECTNAME% le faltan 8 días para caducar', '');
INSERT INTO `template` VALUES(14, 'Aviso de 1 día para fallar', 'Mensaje al autor de un proyecto cuando este está condenado a fallar', 'Al proyecto %PROJECTNAME% le falta 1 día para caducar', '');
INSERT INTO `template` VALUES(15, 'Agradecimiento cofinanciadores si supera primera', 'Mensaje a los cofinanciadores de un proyecto cuando este supera la primera ronda', 'El proyecto %PROJECTNAME% ha pasado a segunda ronda en Goteo', '');
INSERT INTO `template` VALUES(16, 'Agradecimiento cofinanciadores final segunda', 'Mensaje a los cofinanciadores de un proyecto cuando este llega al final de la segunda ronda', 'El proyecto %PROJECTNAME% ha finalizado la segunda ronda', '');
INSERT INTO `template` VALUES(17, 'Aviso cofinanciadores proyecto fallido', 'Mensaje a los cofinanciadores de un proyecto cuando este caduca sin conseguir el mínimo', 'El proyecto %PROJECTNAME% no ha logrado su objetivo mínimo en Goteo :(', '');
INSERT INTO `template` VALUES(18, 'Aviso cofinanciadores novedade en proyecto', 'Mensaje a los cofinanciadores de un proyecto cuando se publica una novedad en este', 'El proyecto %PROJECTNAME% ha publicado novedades', '');
INSERT INTO `template` VALUES(19, 'Recuerdo al autor a los 20 días', 'Mensaje al autor de un proyecto cuando este lleva 20 días de campaña', 'El proyecto %PROJECTNAME% lleva 20 días en campaña', '');
INSERT INTO `template` VALUES(20, 'Notificación al autor proyecto supera primera ronda', 'Mensaje al autor de un proyecto cuando este pasa a segunda ronda', 'El proyecto %PROJECTNAME% ha pasado a segunda ronda', '');
INSERT INTO `template` VALUES(21, 'Notificación al autor proyecto fallido', 'Mensaje al autor de un proyecto cuando este caduca sin conseguir el mínomo', 'El proyecto %PROJECTNAME% ha caducado', '');
INSERT INTO `template` VALUES(22, 'Notificación al autor proyecto fin segunda ronda', 'Mensaje al autor de un proyecto cuando este finaliza la segunda ronda', 'El proyecto %PROJECTNAME% ha finalizado la segunda ronda', '');
INSERT INTO `template` VALUES(23, 'Recuerdo al autor proyecto sin novedades', 'Mensaje mensual al autor de un proyecto si no ha publicado novedades durante 3 meses', 'El proyecto %PROJECTNAME% sin novedades', '');
INSERT INTO `template` VALUES(24, 'Recuerdo al autor proyecto sin actividad', 'Mensaje bisemanal al autor de un proyecto si este no ha tenido actividad durante 3 meses', 'El proyecto %PROJECTNAME% sin actividad', '');
INSERT INTO `template` VALUES(25, 'Recuerdo al autor proyecto financiado', 'Mensaje al autor de un proyecto después de 2 meses de haber sido financiado', 'El proyecto %PROJECTNAME% hace 2 meses que se financió', '');
INSERT INTO `template` VALUES(26, 'Informa al autor de proyecto listo para traducción', 'Plantilla del mensaje al autor al habilitar la traducción de su proyecto', 'Ya puedes traducir tu proyecto %PROJECTNAME%', '');
INSERT INTO `template` VALUES(27, 'Aviso a los talleristas', 'Plantilla del mensaje a los usuarios que aun tienen su email como contraseña', 'El crowdfunding de Goteo.org en marcha', '');
INSERT INTO `template` VALUES(28, 'Agradecimiento donativo', 'Mensaje al usuario aporta renunciando a la recompensa', 'Gracias por tu donativo al proyecto %PROJECTNAME%', '');
INSERT INTO `template` VALUES(29, 'Notificación de nuevo aporte al autor', 'Mensaje al autor de un proyecto cuando un nuevo aporte', 'Nuevo aporte al proyecto %PROJECTNAME%', '');
INSERT INTO `template` VALUES(30, 'Notificacion nuevo thread', 'Mensaje al autor de un proyecto cuando se abre un nuevo hilo de mensajes', 'Nuevo hilo de mensajes en el proyecto %PROJECTNAME%', '');
INSERT INTO `template` VALUES(31, 'Notificación comentario en novedades', 'Mensaje al autor de un proyecto cuando hay un comentario en las novedades', 'Nuevo comentario en las Novedades del proyecto %PROJECTNAME%', '');
INSERT INTO `template` VALUES(32, 'Informa al autor de convocatoria lista para traducción', 'Plantilla del mensaje al convocador al habilitar la traducción de su Convocatoria', 'Ya puedes traducir tu convocatoria %CALLNAME%', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `template_lang`
--

DROP TABLE IF EXISTS `template_lang`;
CREATE TABLE `template_lang` (
  `id` bigint(20) unsigned NOT NULL,
  `lang` varchar(2) NOT NULL,
  `title` tinytext,
  `text` text,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `template_lang`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `text`
--

DROP TABLE IF EXISTS `text`;
CREATE TABLE `text` (
  `id` varchar(50) NOT NULL,
  `lang` varchar(2) NOT NULL DEFAULT 'es',
  `text` text NOT NULL,
  PRIMARY KEY (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Textos multi-idioma';

--
-- Volcar la base de datos para la tabla `text`
--

INSERT INTO `text` VALUES('blog-coments-header', 'ca', 'Comentaris');
INSERT INTO `text` VALUES('blog-coments-header', 'de', 'Kommentare');
INSERT INTO `text` VALUES('blog-coments-header', 'en', 'Comments');
INSERT INTO `text` VALUES('blog-comments', 'ca', 'Comentaris');
INSERT INTO `text` VALUES('blog-comments', 'en', 'Comments');
INSERT INTO `text` VALUES('blog-comments_no_allowed', 'ca', 'No es permeten comentaris en aquesta entrada');
INSERT INTO `text` VALUES('blog-comments_no_allowed', 'de', 'Zu diesem Eintrag sind keine Kommentare erlaubt');
INSERT INTO `text` VALUES('blog-comments_no_allowed', 'en', 'Comments are not allowed on this post');
INSERT INTO `text` VALUES('blog-comments_no_comments', 'ca', 'No hi ha comentaris en aquesta entrada');
INSERT INTO `text` VALUES('blog-comments_no_comments', 'de', 'Zu diesem Eintrag gibt es keine Kommentare');
INSERT INTO `text` VALUES('blog-comments_no_comments', 'en', 'This post has no comments');
INSERT INTO `text` VALUES('blog-main-header', 'ca', 'Blog de Goteo');
INSERT INTO `text` VALUES('blog-main-header', 'en', 'Goteo Blog');
INSERT INTO `text` VALUES('blog-no_comments', 'ca', 'Sense comentaris');
INSERT INTO `text` VALUES('blog-no_comments', 'de', 'Ohne Kommentare');
INSERT INTO `text` VALUES('blog-no_comments', 'en', 'No comment');
INSERT INTO `text` VALUES('blog-no_posts', 'ca', 'No s''ha publicat cap entrada d''actualització');
INSERT INTO `text` VALUES('blog-no_posts', 'de', 'Es wurde kein Eintrag veröffentlicht');
INSERT INTO `text` VALUES('blog-no_posts', 'en', 'No posts have been published');
INSERT INTO `text` VALUES('blog-send_comment-button', 'ca', 'Enviar');
INSERT INTO `text` VALUES('blog-send_comment-button', 'de', 'Senden');
INSERT INTO `text` VALUES('blog-send_comment-button', 'en', 'Send');
INSERT INTO `text` VALUES('blog-send_comment-header', 'ca', 'Escriu el teu comentari');
INSERT INTO `text` VALUES('blog-send_comment-header', 'de', 'Verfasse deinen Kommentar');
INSERT INTO `text` VALUES('blog-send_comment-header', 'en', 'Write a comment');
INSERT INTO `text` VALUES('blog-side-last_comments', 'ca', 'Darrers comentaris');
INSERT INTO `text` VALUES('blog-side-last_comments', 'de', 'Letzte Kommentare');
INSERT INTO `text` VALUES('blog-side-last_comments', 'en', 'Latest comments');
INSERT INTO `text` VALUES('blog-side-last_posts', 'ca', 'Darreres entrades');
INSERT INTO `text` VALUES('blog-side-last_posts', 'de', 'Letzte Einträge');
INSERT INTO `text` VALUES('blog-side-last_posts', 'en', 'Latest posts');
INSERT INTO `text` VALUES('blog-side-tags', 'ca', 'Categories de projecte');
INSERT INTO `text` VALUES('blog-side-tags', 'de', 'Kategorien');
INSERT INTO `text` VALUES('blog-side-tags', 'en', 'Categories');
INSERT INTO `text` VALUES('community-menu-activity', 'ca', 'Activitat');
INSERT INTO `text` VALUES('community-menu-activity', 'de', 'Aktivitäten');
INSERT INTO `text` VALUES('community-menu-activity', 'en', 'Activity');
INSERT INTO `text` VALUES('community-menu-main', 'ca', 'Comunitat');
INSERT INTO `text` VALUES('community-menu-main', 'de', 'Community');
INSERT INTO `text` VALUES('community-menu-main', 'en', 'Community');
INSERT INTO `text` VALUES('community-menu-sharemates', 'ca', 'Compartint');
INSERT INTO `text` VALUES('community-menu-sharemates', 'de', 'Teilen');
INSERT INTO `text` VALUES('community-menu-sharemates', 'en', 'Sharing');
INSERT INTO `text` VALUES('contact-email-field', 'ca', 'Email');
INSERT INTO `text` VALUES('contact-email-field', 'en', 'Email address');
INSERT INTO `text` VALUES('contact-message-field', 'ca', 'Missatge');
INSERT INTO `text` VALUES('contact-message-field', 'en', 'Message');
INSERT INTO `text` VALUES('contact-send_message-button', 'ca', 'Enviar');
INSERT INTO `text` VALUES('contact-send_message-button', 'en', 'Send');
INSERT INTO `text` VALUES('contact-send_message-header', 'ca', 'Envia''ns un missatge');
INSERT INTO `text` VALUES('contact-send_message-header', 'en', 'Send us a message');
INSERT INTO `text` VALUES('contact-subject-field', 'ca', 'Tema');
INSERT INTO `text` VALUES('contact-subject-field', 'en', 'Subject');
INSERT INTO `text` VALUES('cost-type-lend', 'ca', 'Préstec');
INSERT INTO `text` VALUES('cost-type-lend', 'en', 'Loan');
INSERT INTO `text` VALUES('cost-type-material', 'ca', 'Material');
INSERT INTO `text` VALUES('cost-type-material', 'en', 'Material');
INSERT INTO `text` VALUES('cost-type-structure', 'ca', 'Infraestructura');
INSERT INTO `text` VALUES('cost-type-structure', 'en', 'Infrastructure');
INSERT INTO `text` VALUES('cost-type-task', 'ca', 'Tasca');
INSERT INTO `text` VALUES('cost-type-task', 'en', 'Task');
INSERT INTO `text` VALUES('costs-field-amount', 'ca', 'Valor');
INSERT INTO `text` VALUES('costs-field-amount', 'en', 'Value');
INSERT INTO `text` VALUES('costs-field-cost', 'ca', 'Cost');
INSERT INTO `text` VALUES('costs-field-cost', 'en', 'Expense');
INSERT INTO `text` VALUES('costs-field-dates', 'ca', 'Dates');
INSERT INTO `text` VALUES('costs-field-dates', 'en', 'Dates');
INSERT INTO `text` VALUES('costs-field-date_from', 'ca', 'Des de');
INSERT INTO `text` VALUES('costs-field-date_from', 'en', 'From');
INSERT INTO `text` VALUES('costs-field-date_until', 'ca', 'Fins');
INSERT INTO `text` VALUES('costs-field-date_until', 'en', 'Until');
INSERT INTO `text` VALUES('costs-field-description', 'ca', 'Descripció');
INSERT INTO `text` VALUES('costs-field-description', 'en', 'Description');
INSERT INTO `text` VALUES('costs-field-required_cost', 'ca', 'Aquest cost és');
INSERT INTO `text` VALUES('costs-field-required_cost', 'en', 'This expense is');
INSERT INTO `text` VALUES('costs-field-required_cost-no', 'ca', 'Addicional');
INSERT INTO `text` VALUES('costs-field-required_cost-no', 'en', 'Supplemental');
INSERT INTO `text` VALUES('costs-field-required_cost-yes', 'ca', 'Imprescindible');
INSERT INTO `text` VALUES('costs-field-required_cost-yes', 'en', 'Necessary');
INSERT INTO `text` VALUES('costs-field-resoure', 'ca', 'Altres recursos');
INSERT INTO `text` VALUES('costs-field-resoure', 'en', 'Other resources');
INSERT INTO `text` VALUES('costs-field-schedule', 'ca', 'Agenda de treball');
INSERT INTO `text` VALUES('costs-field-schedule', 'en', 'Tasks schedule');
INSERT INTO `text` VALUES('costs-field-type', 'ca', 'Tipus');
INSERT INTO `text` VALUES('costs-field-type', 'en', 'Type');
INSERT INTO `text` VALUES('costs-fields-main-title', 'ca', 'Desglossament de costos');
INSERT INTO `text` VALUES('costs-fields-main-title', 'en', 'Breakdown of expenses');
INSERT INTO `text` VALUES('costs-fields-metter-title', 'ca', 'Visualització de costos');
INSERT INTO `text` VALUES('costs-fields-metter-title', 'en', 'View of expenses');
INSERT INTO `text` VALUES('costs-fields-resources-title', 'ca', 'Recurs');
INSERT INTO `text` VALUES('costs-fields-resources-title', 'en', 'Resource');
INSERT INTO `text` VALUES('costs-main-header', 'ca', 'Aspectes econòmics');
INSERT INTO `text` VALUES('costs-main-header', 'en', 'Expenses');
INSERT INTO `text` VALUES('criteria-owner-section-header', 'ca', 'Respecte al creador/equip');
INSERT INTO `text` VALUES('criteria-owner-section-header', 'en', 'About project responsible/team');
INSERT INTO `text` VALUES('criteria-project-section-header', 'ca', 'Respecte al projecte');
INSERT INTO `text` VALUES('criteria-project-section-header', 'en', 'About the project');
INSERT INTO `text` VALUES('criteria-reward-section-header', 'ca', 'Respecte al retorn');
INSERT INTO `text` VALUES('criteria-reward-section-header', 'en', 'About the rewards');
INSERT INTO `text` VALUES('dashboard-header-main', 'ca', 'El meu panell');
INSERT INTO `text` VALUES('dashboard-header-main', 'en', 'My dashboard');
INSERT INTO `text` VALUES('dashboard-investors-mail-fail', 'ca', 'Error en enviar el missatge a %s: %s');
INSERT INTO `text` VALUES('dashboard-investors-mail-fail', 'en', '    Message failed to %s: %s');
INSERT INTO `text` VALUES('dashboard-investors-mail-nowho', 'ca', 'No s''han trobat destinataris');
INSERT INTO `text` VALUES('dashboard-investors-mail-nowho', 'en', 'No recipient was found');
INSERT INTO `text` VALUES('dashboard-investors-mail-sended', 'ca', 'Missatge enviat correctament a %s: %s');
INSERT INTO `text` VALUES('dashboard-investors-mail-sended', 'en', 'Message sent correctly to %s: %s');
INSERT INTO `text` VALUES('dashboard-investors-mail-sendto', 'ca', 'Enviat a %s dels teus cofinançadors:');
INSERT INTO `text` VALUES('dashboard-investors-mail-sendto', 'en', 'Sent to %s of your backers:');
INSERT INTO `text` VALUES('dashboard-investors-mail-text-required', 'ca', 'Escriu el missatge');
INSERT INTO `text` VALUES('dashboard-investors-mail-text-required', 'en', 'Write the message');
INSERT INTO `text` VALUES('dashboard-menu-activity', 'ca', 'Activitat');
INSERT INTO `text` VALUES('dashboard-menu-activity', 'en', 'My activity');
INSERT INTO `text` VALUES('dashboard-menu-activity-spread', 'ca', 'Difusió');
INSERT INTO `text` VALUES('dashboard-menu-activity-spread', 'en', 'Spread the word');
INSERT INTO `text` VALUES('dashboard-menu-activity-summary', 'ca', 'Resum');
INSERT INTO `text` VALUES('dashboard-menu-activity-summary', 'en', 'Summary');
INSERT INTO `text` VALUES('dashboard-menu-activity-wall', 'ca', 'El meu mur');
INSERT INTO `text` VALUES('dashboard-menu-activity-wall', 'en', 'My wall');
INSERT INTO `text` VALUES('dashboard-menu-admin_board', 'ca', 'Administració');
INSERT INTO `text` VALUES('dashboard-menu-admin_board', 'en', 'Administration');
INSERT INTO `text` VALUES('dashboard-menu-main', 'ca', 'Panell');
INSERT INTO `text` VALUES('dashboard-menu-main', 'de', 'Mein Menü');
INSERT INTO `text` VALUES('dashboard-menu-main', 'en', 'My panel');
INSERT INTO `text` VALUES('dashboard-menu-profile', 'ca', 'Perfil');
INSERT INTO `text` VALUES('dashboard-menu-profile', 'en', 'My profile');
INSERT INTO `text` VALUES('dashboard-menu-profile-access', 'ca', 'Dades d''accés');
INSERT INTO `text` VALUES('dashboard-menu-profile-access', 'en', 'Account details');
INSERT INTO `text` VALUES('dashboard-menu-profile-personal', 'ca', 'Dades personals');
INSERT INTO `text` VALUES('dashboard-menu-profile-personal', 'en', 'Personal information');
INSERT INTO `text` VALUES('dashboard-menu-profile-preferences', 'ca', 'Preferències');
INSERT INTO `text` VALUES('dashboard-menu-profile-preferences', 'en', 'Preferences');
INSERT INTO `text` VALUES('dashboard-menu-profile-profile', 'ca', 'Editar perfil');
INSERT INTO `text` VALUES('dashboard-menu-profile-profile', 'en', 'Edit profile');
INSERT INTO `text` VALUES('dashboard-menu-profile-public', 'ca', 'Perfil públic');
INSERT INTO `text` VALUES('dashboard-menu-profile-public', 'en', 'Public profile');
INSERT INTO `text` VALUES('dashboard-menu-projects', 'ca', 'Projectes');
INSERT INTO `text` VALUES('dashboard-menu-projects', 'en', 'My projects');
INSERT INTO `text` VALUES('dashboard-menu-projects-contract', 'ca', 'Contracte');
INSERT INTO `text` VALUES('dashboard-menu-projects-contract', 'en', 'Contract');
INSERT INTO `text` VALUES('dashboard-menu-projects-preview', 'ca', 'Pàgina pública');
INSERT INTO `text` VALUES('dashboard-menu-projects-preview', 'en', 'Public page');
INSERT INTO `text` VALUES('dashboard-menu-projects-rewards', 'ca', 'Gestió cofinançadors');
INSERT INTO `text` VALUES('dashboard-menu-projects-rewards', 'en', 'Manage benefits');
INSERT INTO `text` VALUES('dashboard-menu-projects-summary', 'ca', 'Resum');
INSERT INTO `text` VALUES('dashboard-menu-projects-summary', 'en', 'Summary');
INSERT INTO `text` VALUES('dashboard-menu-projects-supports', 'ca', 'Col·laboracions ');
INSERT INTO `text` VALUES('dashboard-menu-projects-supports', 'en', 'Collaborations');
INSERT INTO `text` VALUES('dashboard-menu-projects-updates', 'ca', 'Actualitzacions');
INSERT INTO `text` VALUES('dashboard-menu-projects-updates', 'en', 'News');
INSERT INTO `text` VALUES('dashboard-menu-projects-widgets', 'ca', 'Widget');
INSERT INTO `text` VALUES('dashboard-menu-projects-widgets', 'en', 'Widget');
INSERT INTO `text` VALUES('dashboard-menu-review_board', 'ca', 'Revisió');
INSERT INTO `text` VALUES('dashboard-menu-review_board', 'en', 'Review');
INSERT INTO `text` VALUES('dashboard-menu-translates', 'ca', 'Traduccions');
INSERT INTO `text` VALUES('dashboard-menu-translates', 'en', 'My translations');
INSERT INTO `text` VALUES('dashboard-menu-translate_board', 'ca', 'Traducció');
INSERT INTO `text` VALUES('dashboard-menu-translate_board', 'en', 'Translation');
INSERT INTO `text` VALUES('dashboard-password-recover-advice', 'ca', 'Assegura''t de restablir la teva contrasenya');
INSERT INTO `text` VALUES('dashboard-password-recover-advice', 'en', 'You are recovering your password. Remember to put your user name in the "current password" field to change it.');
INSERT INTO `text` VALUES('dashboard-project-blog-fail', 'ca', 'Contacta amb nosaltres');
INSERT INTO `text` VALUES('dashboard-project-blog-fail', 'en', 'Contact us');
INSERT INTO `text` VALUES('dashboard-project-blog-inactive', 'ca', 'Ho sentim, la publicació de novetats en aquest projecte està desactivada');
INSERT INTO `text` VALUES('dashboard-project-blog-inactive', 'en', 'Sorry, you can''t publish news on this project right now.');
INSERT INTO `text` VALUES('dashboard-project-blog-wrongstatus', 'ca', 'Ho sentim, encara no es poden publicar actualitzacions en aquest projecte');
INSERT INTO `text` VALUES('dashboard-project-blog-wrongstatus', 'en', 'Sorry, you can''t publish news about this project yet...');
INSERT INTO `text` VALUES('dashboard-project-delete_alert', 'ca', 'Segur que desitges eliminar absoluta i definitivament aquest projecte?');
INSERT INTO `text` VALUES('dashboard-project-delete_alert', 'en', 'Are you sure you want to permanently delete this project?');
INSERT INTO `text` VALUES('dashboard-project-updates-deleted', 'ca', 'Entada eliminada');
INSERT INTO `text` VALUES('dashboard-project-updates-deleted', 'en', 'Post removed');
INSERT INTO `text` VALUES('dashboard-project-updates-delete_fail', 'ca', 'Error en eliminar l''entrada');
INSERT INTO `text` VALUES('dashboard-project-updates-delete_fail', 'en', 'Error while removing post');
INSERT INTO `text` VALUES('dashboard-project-updates-fail', 'ca', 'Hi ha hagut algun problema en desar les dades');
INSERT INTO `text` VALUES('dashboard-project-updates-fail', 'en', 'There was a problem saving the data');
INSERT INTO `text` VALUES('dashboard-project-updates-inserted', 'ca', 'S''ha afegit una nova entrada');
INSERT INTO `text` VALUES('dashboard-project-updates-inserted', 'en', 'New post added');
INSERT INTO `text` VALUES('dashboard-project-updates-noblog', 'ca', 'No s''ha trobat cap blog per aquest projecte');
INSERT INTO `text` VALUES('dashboard-project-updates-noblog', 'en', 'No blog was found for this project');
INSERT INTO `text` VALUES('dashboard-project-updates-nopost', 'ca', 'No s''ha trobat l''entrada');
INSERT INTO `text` VALUES('dashboard-project-updates-nopost', 'en', 'Post not found');
INSERT INTO `text` VALUES('dashboard-project-updates-postcorrupt', 'ca', 'L''entrada s''ha corromput, contacta amb nosaltres');
INSERT INTO `text` VALUES('dashboard-project-updates-postcorrupt', 'en', 'There is a problem with the post. Please contact us.');
INSERT INTO `text` VALUES('dashboard-project-updates-saved', 'ca', 'L''entrada s''ha actualitzat correctament');
INSERT INTO `text` VALUES('dashboard-project-updates-saved', 'en', 'Post updated correctly');
INSERT INTO `text` VALUES('discover-banner-header', 'ca', 'Per categoria, lloc o retorn,<br /><span class="red">troba el projecte</span> amb que més t''identifiques');
INSERT INTO `text` VALUES('discover-banner-header', 'de', '  Sortiert nach Kategorie, Ort oder Gegenleistung,<br /><span class="red">finde das Projekt</span> mit dem du dich am meisten identifizierst.');
INSERT INTO `text` VALUES('discover-banner-header', 'en', '    By category, location or benefit,<br /><span class="red">find the project</span> that you identify with the most.');
INSERT INTO `text` VALUES('discover-group-all-header', 'ca', 'En campanya');
INSERT INTO `text` VALUES('discover-group-all-header', 'en', 'Campaign in progress');
INSERT INTO `text` VALUES('discover-group-archive-header', 'ca', 'Arxivats ');
INSERT INTO `text` VALUES('discover-group-archive-header', 'en', 'Filed');
INSERT INTO `text` VALUES('discover-group-outdate-header', 'ca', 'A punt de caducar');
INSERT INTO `text` VALUES('discover-group-outdate-header', 'en', 'About to expire');
INSERT INTO `text` VALUES('discover-group-popular-header', 'ca', 'Més populars');
INSERT INTO `text` VALUES('discover-group-popular-header', 'en', 'Most popular');
INSERT INTO `text` VALUES('discover-group-recent-header', 'ca', 'Recents');
INSERT INTO `text` VALUES('discover-group-recent-header', 'en', 'Recent');
INSERT INTO `text` VALUES('discover-group-success-header', 'ca', 'Reeixits ');
INSERT INTO `text` VALUES('discover-group-success-header', 'en', 'Successes');
INSERT INTO `text` VALUES('discover-results-empty', 'ca', 'No hem trobat cap projecte que compleixi els criteris de cerca');
INSERT INTO `text` VALUES('discover-results-empty', 'en', 'We did not find any project that matches your search criteria');
INSERT INTO `text` VALUES('discover-results-header', 'ca', 'Resultat de la cerca');
INSERT INTO `text` VALUES('discover-results-header', 'en', 'Search results');
INSERT INTO `text` VALUES('discover-searcher-button', 'ca', 'Cercar');
INSERT INTO `text` VALUES('discover-searcher-button', 'en', 'Search');
INSERT INTO `text` VALUES('discover-searcher-bycategory-all', 'ca', 'TOTES');
INSERT INTO `text` VALUES('discover-searcher-bycategory-all', 'en', 'ALL');
INSERT INTO `text` VALUES('discover-searcher-bycategory-header', 'ca', 'Per categoria:');
INSERT INTO `text` VALUES('discover-searcher-bycategory-header', 'en', 'By category:');
INSERT INTO `text` VALUES('discover-searcher-bycontent-header', 'ca', 'Per contingut:');
INSERT INTO `text` VALUES('discover-searcher-bycontent-header', 'en', 'By content:');
INSERT INTO `text` VALUES('discover-searcher-bylocation-all', 'ca', 'TOTS');
INSERT INTO `text` VALUES('discover-searcher-bylocation-all', 'en', 'ALL');
INSERT INTO `text` VALUES('discover-searcher-bylocation-header', 'ca', 'Per lloc:');
INSERT INTO `text` VALUES('discover-searcher-bylocation-header', 'en', 'By location:');
INSERT INTO `text` VALUES('discover-searcher-byreward-all', 'ca', 'TOTS');
INSERT INTO `text` VALUES('discover-searcher-byreward-all', 'en', 'ALL');
INSERT INTO `text` VALUES('discover-searcher-byreward-header', 'ca', 'Per retorn:');
INSERT INTO `text` VALUES('discover-searcher-byreward-header', 'en', 'By benefit:');
INSERT INTO `text` VALUES('discover-searcher-header', 'ca', 'Cerca un projecte');
INSERT INTO `text` VALUES('discover-searcher-header', 'en', 'Find a project');
INSERT INTO `text` VALUES('error-contact-email-empty', 'ca', 'No has escrit el teu correu electrònic');
INSERT INTO `text` VALUES('error-contact-email-empty', 'en', 'You didn''t add your email address');
INSERT INTO `text` VALUES('error-contact-email-invalid', 'ca', 'El correu electrònic que has escrit no és vàlid');
INSERT INTO `text` VALUES('error-contact-email-invalid', 'en', 'The email you entered is not valid.');
INSERT INTO `text` VALUES('error-contact-message-empty', 'ca', 'No has escrit cap missatge');
INSERT INTO `text` VALUES('error-contact-message-empty', 'en', 'You haven''t written a message');
INSERT INTO `text` VALUES('error-contact-subject-empty', 'ca', 'No has escrit el tema del missatge');
INSERT INTO `text` VALUES('error-contact-subject-empty', 'en', 'You didn''t enter a subject');
INSERT INTO `text` VALUES('error-image-name', 'ca', 'Error en el nom de l''arxiu');
INSERT INTO `text` VALUES('error-image-name', 'en', 'Error in the file name');
INSERT INTO `text` VALUES('error-image-size', 'ca', 'Error en la mida de l''arxiu');
INSERT INTO `text` VALUES('error-image-size', 'en', 'Error with the file size');
INSERT INTO `text` VALUES('error-image-size-too-large', 'ca', 'La imatge és massa gran');
INSERT INTO `text` VALUES('error-image-size-too-large', 'en', 'The image is too big.');
INSERT INTO `text` VALUES('error-image-tmp', 'ca', 'Error en carregar l''arxiu');
INSERT INTO `text` VALUES('error-image-tmp', 'en', 'Error while loading the file');
INSERT INTO `text` VALUES('error-image-type', 'ca', 'Només es permeten imatges jpg, png i gif');
INSERT INTO `text` VALUES('error-image-type', 'en', 'Only JPG, PNG, and GIF images are permitted');
INSERT INTO `text` VALUES('error-image-type-not-allowed', 'ca', 'Només es permeten arxius d''imatge tipus .png .jpg .gif');
INSERT INTO `text` VALUES('error-image-type-not-allowed', 'en', 'Only .png, .jpg, and .gif images are allowed.');
INSERT INTO `text` VALUES('error-register-email', 'ca', 'L''adreça de correu és obligatòria');
INSERT INTO `text` VALUES('error-register-email', 'en', 'The email field is required.');
INSERT INTO `text` VALUES('error-register-email-confirm', 'ca', 'La comprovació de correu electrònic no coincideix');
INSERT INTO `text` VALUES('error-register-email-confirm', 'en', 'The emails don''t match.');
INSERT INTO `text` VALUES('error-register-email-exists', 'ca', 'L''adreça de correu facilitada correspon a un usuari ja registrat');
INSERT INTO `text` VALUES('error-register-email-exists', 'en', 'The email you entered is registered to an existing user.');
INSERT INTO `text` VALUES('error-register-invalid-password', 'ca', 'La contrasenya no és vàlida');
INSERT INTO `text` VALUES('error-register-invalid-password', 'en', 'That password is not valid.');
INSERT INTO `text` VALUES('error-register-password-confirm', 'ca', 'La comprovació de contrasenya no coincideix');
INSERT INTO `text` VALUES('error-register-password-confirm', 'en', 'The passwords don''t match.');
INSERT INTO `text` VALUES('error-register-pasword', 'ca', 'La contrasenya no pot estar buida');
INSERT INTO `text` VALUES('error-register-pasword', 'en', 'You can''t leave the password field empty.');
INSERT INTO `text` VALUES('error-register-pasword-empty', 'ca', 'No has posat contrasenya');
INSERT INTO `text` VALUES('error-register-pasword-empty', 'en', 'You didn''t enter a password');
INSERT INTO `text` VALUES('error-register-short-password', 'ca', 'La contrasenya ha de contenir un mínim de 8 caràcters');
INSERT INTO `text` VALUES('error-register-short-password', 'en', 'The password should have at least 8 characters.');
INSERT INTO `text` VALUES('error-register-user-exists', 'ca', 'Aquest nom d''usuari ja està registrat');
INSERT INTO `text` VALUES('error-register-user-exists', 'en', 'That user name is already registered.');
INSERT INTO `text` VALUES('error-register-userid', 'ca', 'És obligatori posar un nom d''accés');
INSERT INTO `text` VALUES('error-register-userid', 'en', 'You have to enter a user name');
INSERT INTO `text` VALUES('error-register-username', 'ca', 'El nom públic és obligatori.');
INSERT INTO `text` VALUES('error-register-username', 'en', 'You have to enter a screen name.');
INSERT INTO `text` VALUES('error-user-email-confirm', 'ca', 'La confirmació de correu electrònic no coincideix');
INSERT INTO `text` VALUES('error-user-email-confirm', 'en', 'The email confirmation is not the same as the email.');
INSERT INTO `text` VALUES('error-user-email-empty', 'ca', 'No pots deixar el camp d''email buit ');
INSERT INTO `text` VALUES('error-user-email-empty', 'en', 'You can''t leave the email field empty');
INSERT INTO `text` VALUES('error-user-email-exists', 'ca', 'Ja hi ha un usuari registrat amb aquest email');
INSERT INTO `text` VALUES('error-user-email-exists', 'en', 'There is already a registered user with that email address');
INSERT INTO `text` VALUES('error-user-email-invalid', 'ca', 'L''email que has posat no és vàlid');
INSERT INTO `text` VALUES('error-user-email-invalid', 'en', 'The email you entered is not valid.');
INSERT INTO `text` VALUES('error-user-email-token-invalid', 'ca', 'El codi no és correcte');
INSERT INTO `text` VALUES('error-user-email-token-invalid', 'en', 'The code is incorrect');
INSERT INTO `text` VALUES('error-user-password-confirm', 'ca', 'La confirmació de contrasenya no coincideix');
INSERT INTO `text` VALUES('error-user-password-confirm', 'en', 'The password confirmation is not the same as the password.');
INSERT INTO `text` VALUES('error-user-password-empty', 'ca', 'No has posat la contrasenya');
INSERT INTO `text` VALUES('error-user-password-empty', 'en', 'You didn''t enter a password');
INSERT INTO `text` VALUES('error-user-password-invalid', 'ca', 'La contrasenya és massa curta');
INSERT INTO `text` VALUES('error-user-password-invalid', 'en', 'The password is too short. It has to have at least 6 characters.');
INSERT INTO `text` VALUES('error-user-wrong-password', 'ca', 'La contrasenya no és correcta');
INSERT INTO `text` VALUES('error-user-wrong-password', 'en', 'The password is incorrect.');
INSERT INTO `text` VALUES('explain-project-progress', 'ca', 'Aquest gràfic explica d''una manera visual el nivell de dades que has introduït juntament amb una avaluació bàsica que fa el sistema. Per poder enviar el projecte has de superar el 80%. Els criteris que fan pujar aquest "termòmetre" tenen a veure amb la informació rellevant que facilites, els media, imatges i links que introdueixes, si tries una llicència més oberta que una altre, la coherència del teu pressupost respecte a les tasques a desenvolupar, etc. No perdis de vista els consells de la columna dreta, que guien durant tot el procés.');
INSERT INTO `text` VALUES('explain-project-progress', 'en', 'This graph explains in a visual way the level of data that you have entered together with a basic evaluation completed by the system. To be able to submit the project, you have to reach at least 80%. The criteria that make the “temperature” rise have to do with the relevant information that you provide, the media, images and links that you enter, the degree of openness of the license you choose, the coherence of your estimate with respect to the work that needs to be carried out, etc. Don''t forget to take advantage of the tooltips on the right that will guide you through the process.');
INSERT INTO `text` VALUES('faq-ask-question', 'ca', 'No has pogut resoldre el teu dubte? Envia un missatge amb la teva pregunta');
INSERT INTO `text` VALUES('faq-ask-question', 'en', 'Didn''t this solve your question? Send us a message.');
INSERT INTO `text` VALUES('faq-investors-section-header', 'ca', 'Per a cofinançadors/es');
INSERT INTO `text` VALUES('faq-investors-section-header', 'en', 'For co-financiers');
INSERT INTO `text` VALUES('faq-main-section-header', 'ca', 'Una aproximació a Goteo');
INSERT INTO `text` VALUES('faq-main-section-header', 'en', 'An approach to Goteo');
INSERT INTO `text` VALUES('faq-nodes-section-header', 'ca', 'Sobre nodes locals');
INSERT INTO `text` VALUES('faq-nodes-section-header', 'en', 'About local nodes');
INSERT INTO `text` VALUES('faq-project-section-header', 'ca', 'Sobre els projectes');
INSERT INTO `text` VALUES('faq-project-section-header', 'en', 'About the projects');
INSERT INTO `text` VALUES('faq-sponsor-section-header', 'ca', 'Per a impulsors/es');
INSERT INTO `text` VALUES('faq-sponsor-section-header', 'en', 'For promoters');
INSERT INTO `text` VALUES('fatal-error-project', 'ca', 'Aquest projecte que cerques... <span class="red">no existeix :(</span>');
INSERT INTO `text` VALUES('fatal-error-project', 'en', 'The project you''re looking for... <span class="red">does not exist :(</span>');
INSERT INTO `text` VALUES('fatal-error-user', 'ca', 'L''usuari que cerques... <span class="red">no existeix :(</span>');
INSERT INTO `text` VALUES('fatal-error-user', 'en', 'The user you''re looking for... <span class="red">does not exist :(</span>');
INSERT INTO `text` VALUES('feed-blog-comment', 'ca', 'Ha escrit un <span class="green">Comentari</span> a l''entrada "%s" del blog de %s');
INSERT INTO `text` VALUES('feed-blog-comment', 'en', 'Has written a <span class="green">Comment</span> at the post "%s" from the %s blog');
INSERT INTO `text` VALUES('feed-head-community', 'ca', 'Comunitat');
INSERT INTO `text` VALUES('feed-head-community', 'en', 'Community');
INSERT INTO `text` VALUES('feed-head-goteo', 'ca', 'Goteo');
INSERT INTO `text` VALUES('feed-head-goteo', 'en', 'Goteo');
INSERT INTO `text` VALUES('feed-head-projects', 'ca', 'Projectes');
INSERT INTO `text` VALUES('feed-head-projects', 'en', 'Projects');
INSERT INTO `text` VALUES('feed-header', 'ca', 'Activitat recent ');
INSERT INTO `text` VALUES('feed-header', 'en', 'Recent activity');
INSERT INTO `text` VALUES('feed-invest', 'ca', 'Ha aportat %s al projecte %s');
INSERT INTO `text` VALUES('feed-invest', 'en', 'Has contributed with %s to the %s project');
INSERT INTO `text` VALUES('feed-messages-new_thread', 'ca', 'Ha obert un tema en %s del projecte %s');
INSERT INTO `text` VALUES('feed-messages-new_thread', 'en', 'Has opened a thread at %s of the project %s');
INSERT INTO `text` VALUES('feed-messages-response', 'ca', 'Ha respost en %s del projecte %s');
INSERT INTO `text` VALUES('feed-messages-response', 'en', 'Has answered to %s of the project %s');
INSERT INTO `text` VALUES('feed-new_project', 'ca', '<span class="red">Nou projecte a Goteo</span>, des d''ara tens 40 dies per a recolzar aquest projecte');
INSERT INTO `text` VALUES('feed-new_project', 'en', '    <span class="red">New Goteo project</span>, you''ve got 40 days from today to support this project.');
INSERT INTO `text` VALUES('feed-new_support', 'ca', 'Ha publicat una nova <span class="green">Col·laboració</span> al projecte %s, amb el títol "%s"');
INSERT INTO `text` VALUES('feed-new_support', 'en', 'Has published a new <span class="green">Collaboration</span> at the %s project, with the title "%s"');
INSERT INTO `text` VALUES('feed-new_update', 'ca', 'Ha publicat un nou post en %s sobre el projecte %s, amb el títol "%s"');
INSERT INTO `text` VALUES('feed-new_user', 'ca', 'Nou usuari a Goteo %s');
INSERT INTO `text` VALUES('feed-new_user', 'en', 'New Goteo user, %s');
INSERT INTO `text` VALUES('feed-project_fail', 'ca', 'El projecte %s ha <span class="red">caducat sense èxit </span> obtenint <span class="violet">%s ? (%s %) d''aportacions sobre el mínim</span>');
INSERT INTO `text` VALUES('feed-project_fail', 'en', '    The project, %s, has <span class="red">closed</span> having only received <span class="violet">%s ? (%s %) of the minimum contribution</span>');
INSERT INTO `text` VALUES('feed-project_finish', 'ca', 'El projecte %s ha <span class="red">complert la segona ronda</span> obtenint <span class="violet">%s ? (%s %) d''aportacions sobre el mínim</span>');
INSERT INTO `text` VALUES('feed-project_finish', 'en', '    The project, %s, has <span class="red">completed the second round</span> having received <span class="violet">%s ? (%s %) of the minimum contribution</span>');
INSERT INTO `text` VALUES('feed-project_goon', 'ca', 'El projecte %s <span class="red">continua en campanya</span> en segona ronda obtenint <span class="violet">%s ? (%s %) d''aportacions sobre el mínim</span>');
INSERT INTO `text` VALUES('feed-project_goon', 'en', '    The project, %s, <span class="red">will continue</span> in the second round since it received <span class="violet">%s ? (%s %) of the minimum required contributions</span>');
INSERT INTO `text` VALUES('feed-project_runout', 'ca', 'Al projecte %s li queden <span class="red">%s dies</span> per a finalitzar la %sª ronda');
INSERT INTO `text` VALUES('feed-project_runout', 'en', 'The %s project has <span class="red">%s more days</span> until the end of round number %s');
INSERT INTO `text` VALUES('feed-side-top_ten', 'ca', 'Top ten cofinançadors');
INSERT INTO `text` VALUES('feed-side-top_ten', 'en', 'Top ten co-financiers');
INSERT INTO `text` VALUES('feed-timeago', 'ca', 'Fa %s');
INSERT INTO `text` VALUES('feed-timeago', 'en', '%s ago');
INSERT INTO `text` VALUES('feed-timeago-justnow', 'ca', 'res');
INSERT INTO `text` VALUES('feed-timeago-justnow', 'en', 'just');
INSERT INTO `text` VALUES('feed-timeago-periods', 'ca', 'segon-segons_minut-minuts_hora-hores_dia-dies_setmana-setmanes_mes-mesos_any-anys_dècada-dècades');
INSERT INTO `text` VALUES('feed-timeago-periods', 'en', 'second-seconds_minute-minutes_hour-hours_day-days_week-weeks_month-months_year-years_decade-decades');
INSERT INTO `text` VALUES('feed-timeago-published', 'ca', 'Publicat fa %s');
INSERT INTO `text` VALUES('feed-timeago-published', 'en', 'Published %s ago');
INSERT INTO `text` VALUES('feed-updates-comment', 'ca', 'Ha escrit un <span class="green">Comentari</span> a l''entrada "%s" en %s del prohecte %s');
INSERT INTO `text` VALUES('feed-updates-comment', 'en', 'Has written a <span class="green">Comment</span> to the message "%s" on %s of the %s project');
INSERT INTO `text` VALUES('footer-header-categories', 'ca', 'Categories');
INSERT INTO `text` VALUES('footer-header-categories', 'de', 'Kategorien');
INSERT INTO `text` VALUES('footer-header-categories', 'en', 'Categories');
INSERT INTO `text` VALUES('footer-header-projects', 'ca', 'Projectes');
INSERT INTO `text` VALUES('footer-header-projects', 'de', 'Projekte');
INSERT INTO `text` VALUES('footer-header-projects', 'en', 'Projects');
INSERT INTO `text` VALUES('footer-header-resources', 'ca', 'Recursos');
INSERT INTO `text` VALUES('footer-header-resources', 'de', 'Ressourcen');
INSERT INTO `text` VALUES('footer-header-resources', 'en', 'Resources');
INSERT INTO `text` VALUES('footer-header-services', 'ca', 'Serveis');
INSERT INTO `text` VALUES('footer-header-services', 'de', 'Dienstleistungen');
INSERT INTO `text` VALUES('footer-header-services', 'en', 'Services');
INSERT INTO `text` VALUES('footer-header-social', 'ca', 'Segueix-nos');
INSERT INTO `text` VALUES('footer-header-social', 'de', 'Folge uns');
INSERT INTO `text` VALUES('footer-header-social', 'en', 'Follow us');
INSERT INTO `text` VALUES('footer-header-sponsors', 'ca', 'Suports institucionals');
INSERT INTO `text` VALUES('footer-header-sponsors', 'de', 'Institutionelle Unterstützung');
INSERT INTO `text` VALUES('footer-header-sponsors', 'en', 'Institutional support');
INSERT INTO `text` VALUES('footer-platoniq-iniciative', 'ca', 'Una iniciativa de:');
INSERT INTO `text` VALUES('footer-platoniq-iniciative', 'de', 'Eine Initiative von:');
INSERT INTO `text` VALUES('footer-platoniq-iniciative', 'en', 'An initiative of:');
INSERT INTO `text` VALUES('footer-resources-glossary', 'ca', 'Glossari ');
INSERT INTO `text` VALUES('footer-resources-glossary', 'de', 'Glossar');
INSERT INTO `text` VALUES('footer-resources-glossary', 'en', 'Glossary');
INSERT INTO `text` VALUES('footer-resources-press', 'ca', 'Premsa');
INSERT INTO `text` VALUES('footer-resources-press', 'de', 'Presse');
INSERT INTO `text` VALUES('footer-resources-press', 'en', 'Press Kit');
INSERT INTO `text` VALUES('footer-service-campaign', 'ca', 'Campanyes');
INSERT INTO `text` VALUES('footer-service-campaign', 'de', 'Kampagnen');
INSERT INTO `text` VALUES('footer-service-campaign', 'en', 'Campaigns');
INSERT INTO `text` VALUES('footer-service-consulting', 'ca', 'Consultoria');
INSERT INTO `text` VALUES('footer-service-consulting', 'de', 'Beratung');
INSERT INTO `text` VALUES('footer-service-consulting', 'en', 'Consulting firm');
INSERT INTO `text` VALUES('footer-service-resources', 'ca', 'Capital d''irrigació');
INSERT INTO `text` VALUES('footer-service-resources', 'de', 'Risikokapital');
INSERT INTO `text` VALUES('footer-service-resources', 'en', 'Feeder capital');
INSERT INTO `text` VALUES('footer-service-workshop', 'ca', 'Tallers');
INSERT INTO `text` VALUES('footer-service-workshop', 'de', 'Workshops');
INSERT INTO `text` VALUES('footer-service-workshop', 'en', 'Workshops');
INSERT INTO `text` VALUES('form-accept-button', 'ca', 'Acceptar');
INSERT INTO `text` VALUES('form-accept-button', 'en', 'OK');
INSERT INTO `text` VALUES('form-add-button', 'ca', 'Afegir');
INSERT INTO `text` VALUES('form-add-button', 'en', 'Add');
INSERT INTO `text` VALUES('form-ajax-info', 'ca', 'El formulari de projecte es va desant segons passis per cada camp');
INSERT INTO `text` VALUES('form-ajax-info', 'en', 'This form is saved automatically while you fill it out');
INSERT INTO `text` VALUES('form-apply-button', 'ca', 'Aplicar');
INSERT INTO `text` VALUES('form-apply-button', 'en', 'Apply');
INSERT INTO `text` VALUES('form-errors-info', 'ca', 'Total: %s | En aquesta passa: %s');
INSERT INTO `text` VALUES('form-errors-info', 'en', 'Total: %s | At this step: %s');
INSERT INTO `text` VALUES('form-errors-total', 'ca', 'Hi ha %s errors en total');
INSERT INTO `text` VALUES('form-errors-total', 'en', 'There''s %s errors in total');
INSERT INTO `text` VALUES('form-footer-errors_title', 'ca', 'Errors');
INSERT INTO `text` VALUES('form-footer-errors_title', 'en', 'Errors');
INSERT INTO `text` VALUES('form-image_upload-button', 'ca', 'Pujar imatge');
INSERT INTO `text` VALUES('form-image_upload-button', 'en', 'Upload image');
INSERT INTO `text` VALUES('form-navigation_bar-header', 'ca', 'Anar a');
INSERT INTO `text` VALUES('form-navigation_bar-header', 'en', 'Go to');
INSERT INTO `text` VALUES('form-next-button', 'ca', 'Següent');
INSERT INTO `text` VALUES('form-next-button', 'en', 'Next');
INSERT INTO `text` VALUES('form-project-info_status-title', 'ca', 'Estat global de la informació');
INSERT INTO `text` VALUES('form-project-info_status-title', 'en', 'Overall status of information');
INSERT INTO `text` VALUES('form-project-progress-title', 'ca', 'Avaluació de dades');
INSERT INTO `text` VALUES('form-project-progress-title', 'en', 'Data evaluation');
INSERT INTO `text` VALUES('form-project-status-title', 'ca', 'Estat del projecte');
INSERT INTO `text` VALUES('form-project-status-title', 'en', 'Project status');
INSERT INTO `text` VALUES('form-project_status-campaing', 'ca', 'En campanya');
INSERT INTO `text` VALUES('form-project_status-campaing', 'en', 'Campaign in progress');
INSERT INTO `text` VALUES('form-project_status-cancel', 'ca', 'Rebutjat');
INSERT INTO `text` VALUES('form-project_status-cancel', 'en', 'Discarded');
INSERT INTO `text` VALUES('form-project_status-cancelled', 'ca', 'Cancel·lat');
INSERT INTO `text` VALUES('form-project_status-cancelled', 'en', 'Canceled');
INSERT INTO `text` VALUES('form-project_status-edit', 'ca', 'Editant-se');
INSERT INTO `text` VALUES('form-project_status-edit', 'en', 'In review');
INSERT INTO `text` VALUES('form-project_status-expired', 'ca', 'Caducat');
INSERT INTO `text` VALUES('form-project_status-expired', 'en', 'Closed');
INSERT INTO `text` VALUES('form-project_status-fulfilled', 'ca', 'Retorn complert');
INSERT INTO `text` VALUES('form-project_status-fulfilled', 'en', 'Benefit completed');
INSERT INTO `text` VALUES('form-project_status-review', 'ca', 'Pendent de valoració');
INSERT INTO `text` VALUES('form-project_status-review', 'en', 'Evaluation pending');
INSERT INTO `text` VALUES('form-project_status-success', 'ca', 'Finançat');
INSERT INTO `text` VALUES('form-project_status-success', 'en', 'Funded');
INSERT INTO `text` VALUES('form-project_waitfor-campaing', 'ca', 'Difon el teu projecte, ajuda a que aconsegueixi el màxim d''aportacions!');
INSERT INTO `text` VALUES('form-project_waitfor-campaing', 'en', 'Spread the word about your project. Help get the most support!');
INSERT INTO `text` VALUES('form-project_waitfor-cancel', 'ca', 'Finalment hem desestimat la proposta per publicar-la a Goteo, et convidem a intentar-ho amb una altra idea o concepte.');
INSERT INTO `text` VALUES('form-project_waitfor-cancel', 'en', 'We have decided not to publish your proposal on Goteo, though we invite you to try again with a different idea or concept.');
INSERT INTO `text` VALUES('form-project_waitfor-edit', 'ca', 'Quan ho tinguis llest envia-ho a revisió. Necessites arribar a un mínim d''informació sobre el projecte al formulari.');
INSERT INTO `text` VALUES('form-project_waitfor-edit', 'en', 'When it''s ready, send it for review. You have to provide a minimum of information about your project on the form.');
INSERT INTO `text` VALUES('form-project_waitfor-expired', 'ca', 'No ho has aconseguit :( Tracta de millorar-ho i intenta-ho de nou!');
INSERT INTO `text` VALUES('form-project_waitfor-expired', 'en', 'It didn''t work out. Make it better and try again!');
INSERT INTO `text` VALUES('form-project_waitfor-fulfilled', 'ca', 'Has complert amb els retorns :) Gràcies per la teva participació!');
INSERT INTO `text` VALUES('form-project_waitfor-fulfilled', 'en', 'You''ve managed all the benefits. Thanks for participating!');
INSERT INTO `text` VALUES('form-project_waitfor-review', 'ca', 'En breu ens posarem en contacte amb tu respecte al projecte, una vegada es dugui a terme el procés de revisió. A continuació ho publicarem o bé et suggerirem coses per millorar-lo.');
INSERT INTO `text` VALUES('form-project_waitfor-review', 'en', 'We will get back to you as soon as we''ve had a chance to review your project. At that point, we will either post your project, or suggest ways to make it fit better on Goteo.');
INSERT INTO `text` VALUES('form-project_waitfor-success', 'ca', 'Has aconseguit el mínim o més en aportacions de cofinançament pel projecte. De seguida et contactarem per parlar de diners :)');
INSERT INTO `text` VALUES('form-project_waitfor-success', 'en', 'You have reached or surpassed your co-financing goal for this project. We will contact you shortly to talk about money :)');
INSERT INTO `text` VALUES('form-remove-button', 'ca', 'Llevar');
INSERT INTO `text` VALUES('form-remove-button', 'en', 'Remove');
INSERT INTO `text` VALUES('form-self_review-button', 'ca', 'Corregir');
INSERT INTO `text` VALUES('form-self_review-button', 'en', 'Correct');
INSERT INTO `text` VALUES('form-send_review-button', 'ca', 'Enviar');
INSERT INTO `text` VALUES('form-send_review-button', 'en', 'Send');
INSERT INTO `text` VALUES('form-upload-button', 'ca', 'Pujar');
INSERT INTO `text` VALUES('form-upload-button', 'en', 'Send');
INSERT INTO `text` VALUES('guide-dashboard-user-access', 'ca', 'Des d''aquí pots modificar les dades amb que accedeixes al teu compte de Goteo.');
INSERT INTO `text` VALUES('guide-dashboard-user-access', 'en', 'You can change the information with which you log in to your Goteo account.');
INSERT INTO `text` VALUES('guide-dashboard-user-personal', 'ca', 'Només has d''omplir aquestes dades si has creat un projecte i vols que sigui cofinançat i recolzat mitjançant Goteo. La informació d''aquest apartat és necessària per contactar-te en cas que obtinguis el finançament, i que així es pugui fer efectiu l''ingrés.');
INSERT INTO `text` VALUES('guide-dashboard-user-personal', 'en', 'You should only fill in this data if you have created a project and you want it to be co-financed and supported through Goteo. The information in this section is necessary so that we can contact you in the event that you obtain the necessary co-financing, and make the corresponding deposit.');
INSERT INTO `text` VALUES('guide-dashboard-user-preferences', 'ca', 'Marca ''Sí'' a les notificacions automàtiques que vulguis bloquejar.');
INSERT INTO `text` VALUES('guide-dashboard-user-preferences', 'en', 'Select ''Yes'' for the automatic notifications you want to block.');
INSERT INTO `text` VALUES('guide-dashboard-user-profile', 'ca', 'Tant si vols presentar un projecte com incorporar-te com a cofinanciador/a, per formar part de la comunitat de Goteo et recomanem que escriguis el teu text de presentació, que afegeixis links rellevants sobre el que fas i pugis una imatge de perfil amb la qual t''identifiquis.');
INSERT INTO `text` VALUES('guide-dashboard-user-profile', 'en', 'Whether you want to create a project or co-finance someone else''s, in order to join the Goteo community, we recommend that you carefully edit your presentation text, and include relevant links about what you do as well as a profile image with which you identify');
INSERT INTO `text` VALUES('guide-project-comment', 'en', 'guide-project-comment');
INSERT INTO `text` VALUES('guide-project-contract-information', 'ca', '<b>A partir d''aquest pas només has d''emplenar les dades si vols que el teu projecte sigui cofinançat i recolzat mitjançant Goteo.</b>\r\n<br><br>\r\nLa informació d''aquest apartat és necessària per contactar-te en cas que obtinguis el finançament requerit, i que així es pugui efectuar l''ingrés.');
INSERT INTO `text` VALUES('guide-project-contract-information', 'en', '<strong>The only thing left is to fill in your data if you want your project to be co-financed and supported through Goteo.</strong><br><br>The information in this section is necessary so that we can contact you if you get the required financing, and be able to make the deposit. In the case of organizations, we recommend that the representative of the organization be formally accredited (for example, by way of the statutes or a certificate of the secretary with an OK from the president, in the case of associations).');
INSERT INTO `text` VALUES('guide-project-costs', 'ca', '<b>En aquesta secció has d''elaborar un petit pressupost basat en els costos que calculis tindrà la realització del projecte.</b><br>\r\n<br>\r\nHas d''especificar segons tasques, infraestructura o materials. Intenta ser realista en els costos i explicar breument per què necessites cobrir cadascun d''ells. Veuràs que diferenciem entre costos imprescindibles i costos addicionals, on els primers han de suposar més de la meitat del total a cofinançar.');
INSERT INTO `text` VALUES('guide-project-costs', 'en', '<strong>In this section, you should fill out a short business plan based on the estimated costs for your project.</strong><br><br>You should break down the numbers by tasks, infrastructure, or materials. Try to be realistic about the costs and briefly explain why you need each one. Keep in mind, that as a general rule, at least 80% of the project will have to be undertaken by the person or team that is promoting the project, and not subcontracted to third parties. <br><br><strong>Very important</strong>: On Goteo, we differentiate among necessary and supplemental costs. The former must be totally covered in order to obtain funding, while the latter can be obtained as part of a second campaign, once the project is underway, in order to cover optimization costs (promotion, design, outreach, additional units, etc.) These supplemental costs can not be more than one half of the total cost of the project.');
INSERT INTO `text` VALUES('guide-project-description', 'ca', '<strong>Aquest és l''apartat on explicar amb detall els aspectes conceptuals del projecte.</strong><br><br>És la primera informació amb que qualsevol usuari de la xarxa es trobarà, així que tingues cura de la redacció i evita les faltes d''ortografia. Veuràs que hi ha camps obligatoris com incloure un vídeo o pujar imatges. Això és així perquè els considerem imprescindibles per començar amb èxit una campanya de recaptació de fons a Goteo.<br><br> Tingues en compte que el més valorat en Goteo és: la informació o coneixement lliure d''interès general que el teu projecte aportarà a la comunitat, l''originalitat, aspirar a resoldre una demanda social, el potencial per atreure a una comunitat àmplia de persones interessades, deixar clar que l''equip promotor posseeix les capacitats i experiència per poder portar-ho a bon termini. Per tant no perdis de vista informar sobre aquests aspectes.');
INSERT INTO `text` VALUES('guide-project-description', 'en', '<strong>Use this section to explain the conceptual aspects of the project</strong><br><br>This is the first information that a visitor to the site will see. For that reason, we recommend being careful with editing and avoiding typos. You will see that there are required fields, including one for video and images. We believe that these are essential for starting a campaign to raise funds via Goteo.<br><br>Keep in mind that the most valuable thing to Goteo is: the free information and general knowledge that your project will bring to the community, the originality, the desire to answer a social need, the power to attract a wide community of interested people, making it clear that the promotion team has the capacity and experience to bring the project to fruition. So don''t forget to give us information about all of these points.');
INSERT INTO `text` VALUES('guide-project-error-mandatories', 'ca', 'Falten camps obligatoris');
INSERT INTO `text` VALUES('guide-project-error-mandatories', 'en', 'Some required fields are missing');
INSERT INTO `text` VALUES('guide-project-preview', 'ca', '<strong>Aquest és un resum de tota la informació sobre el projecte.</strong><br><br>Repassa els punts de cada apartat per veure si pots millorar alguna cosa, o bé envia el projecte per a la seva valoració (amb el botó "Enviar" de la part inferior) si ja estan emplenats tots els camps obligatoris, per a que així pugui ser valorat per l''equip i la comunitat de Goteo. Una vegada ho enviïs ja no es podran introduir canvis.<br><br>Tingues en compte que només podem seleccionar uns quants projectes al mes per garantir-ne l''atenció i la difusió de les propostes que es fan públiques. Pròximament rebràs un missatge amb tota la informació, que t''indicarà els passos a seguir i recomanacions per a que el teu projecte pugui aconseguir la meta proposada. ');
INSERT INTO `text` VALUES('guide-project-preview', 'en', '<strong>This is a summary of all of the information about the project.</strong><br><br>Review the information given in each field to see if any improvements can be made, and when you''re ready, send us the project (by clicking the Send button below) so we can evaluate it. Once the proposal is sent, no further changes are permitted.<br><br>Keep in mind that we can only choose a few projects per month in order to guarantee a certain amount of attention and promotion for those that are published. You will receive a message with all of this information shortly, which will indicate the next steps, as well as some recommendations that will help your project reach its proposed objectives.');
INSERT INTO `text` VALUES('guide-project-rewards', 'ca', '<strong>En aquest apartat has d''establir què ofereix el projecte a canvi als seus cofinançadors, i també els seur retorns col·lectius.</strong><br><br>A més de les recompenses individuals per a cada import de cofinançament, aquí has de definir quin tipus de llicència assignar al projecte, en funció del seu format i/o del grau d''obertura que té (o d''alguna de les seves parts). Aquesta part és molt important, ja que Goteo és una plataforma de crowdfunding per a projectes basats en la filosofia del codi obert i que enforteixin els béns comuns.<br><br>En cas que a més d''una de les llicències aquí especificades t''interessi addicionalment registrar la propietat intel·lectual de la teva obra o idea, tot mantenint la seva compatibilitat amb els retorns col·lectius, et recomanem obtenir una protecció legal específica mitjançant el servei <a href="http://www.safecreative.org/" target="new">Safe Creative</a>.');
INSERT INTO `text` VALUES('guide-project-rewards', 'en', '<strong>In this section, you should establish what the project is offering to its co-financiers and also what the collective benefits will be.</strong><br><br>In addition to the individual rewards for each level of co-financing, you should define the kind of license that you will assign to the project, according to its format and/or level of openness (or that of its parts). This is very important, since Goteo is a crowdfunding platform for projects based on a philosophy of open source software and promotion of the common good.<br><br>In the event that in addition to one of the licenses specified here you would also like to register the intellectual property rights of your work or ideas, while maintaining its compatibility with collective benefits, you can get specific legal protection with <a href="http://www.safecreative.org/" target="new">Safe Creative</a>.');
INSERT INTO `text` VALUES('guide-project-success-minprogress', 'ca', 'Ha arribat al percentatge mínim');
INSERT INTO `text` VALUES('guide-project-success-minprogress', 'en', 'You have achieved the minimum percentage');
INSERT INTO `text` VALUES('guide-project-success-noerrors', 'ca', 'Tots els camps obligatoris s''han emplenat');
INSERT INTO `text` VALUES('guide-project-success-noerrors', 'en', 'All of the required fields are filled out');
INSERT INTO `text` VALUES('guide-project-success-okfinish', 'ca', 'Pot enviar-se per a revisió');
INSERT INTO `text` VALUES('guide-project-success-okfinish', 'en', 'You can send it for reviewing');
INSERT INTO `text` VALUES('guide-project-support', 'en', 'guide-project-support');
INSERT INTO `text` VALUES('guide-project-supports', 'ca', '<strong>En aquest apartat pots especificar quines altres ajudes, a part de finançament, es necessiten per dur a terme el projecte.</strong><br><br>Poden ser tasques o accions a càrrec d''altres persones (traduccions, gestions, difusió, etc), o bé préstecs específics (de material, transport, maquinari, etc).');
INSERT INTO `text` VALUES('guide-project-supports', 'en', '<strong>In this section you will specify what other support, apart from financing, that you will need in order to complete the project.</strong><br><br>They can consist of collaboration or assistance from other people (translations, organization, promotion, etc.) or perhaps specific loans (of material, transportation, hardware, etc.)');
INSERT INTO `text` VALUES('guide-project-updates', 'ca', '<b>És molt important que els projectes mantinguin informats els seus cofinançadors i la resta de persones potencialment interessades sobre com avança la seva campanya. Des d''aquest apartat pots publicar missatges d''actualització sobre el projecte, com una espècie de blog públic.</b> A Goteo a més, una vegada s''han aconseguit els fons mínims, per a la segona ronda de cofinançament és crític explicar regularment com ha arrencat la producció, avenços, problemes, etc que permetin la major transparència possible i saber com evoluciona l''inici del projecte, per així tractar de generar més interès i comunitat al seu voltant.');
INSERT INTO `text` VALUES('guide-project-updates', 'en', '<b>It''s very important that the projects keep their co-financiers and other potentially interested people up to date about how the campaign is progressing. Use this section like a blog to publish updates about the project.</b>On Goteo, after you''ve achieved the minimum financing, and as you go into the second round, it''s essential to periodically explain how the production, progress, problems, etc. are going in order to allow the most transparency possible in order to generate even more interest and community.');
INSERT INTO `text` VALUES('guide-project-user-information', 'ca', '<strong>En aquest apartat has d''introduir les dades per a la informació pública del teu perfil d''usuari. </strong><br><br>Tant si vols presentar un projecte com incorporar-te com a cofinanciador/a, per formar part de la comunitat de Goteo et recomanem que escriguis el teu text de presentació, que afegeixis links rellevants sobre el que fas i pugis una imatge de perfil amb la qual t''identifiquis.');
INSERT INTO `text` VALUES('guide-project-user-information', 'en', '    <strong>In this section, you will enter data for the public part of your user profile. </strong><br><br>Whether you want to create a project or co-finance someone else''s, in order to join the Goteo community, we recommend that you carefully edit your presentation text, and include relevant links about what you do as well as a profile image with which you identify.');
INSERT INTO `text` VALUES('header-about-side', 'ca', 'Allò que ens mou');
INSERT INTO `text` VALUES('header-about-side', 'en', 'What inspires us');
INSERT INTO `text` VALUES('home-posts-header', 'ca', 'Al nostre blog');
INSERT INTO `text` VALUES('home-posts-header', 'en', 'On our blog');
INSERT INTO `text` VALUES('home-promotes-header', 'ca', 'Destacats');
INSERT INTO `text` VALUES('home-promotes-header', 'de', 'Ausgewählte Projekte');
INSERT INTO `text` VALUES('home-promotes-header', 'en', 'Highlighted');
INSERT INTO `text` VALUES('image-upload-fail', 'ca', 'Error en pujar la imatge');
INSERT INTO `text` VALUES('image-upload-fail', 'en', 'Error uploading image');
INSERT INTO `text` VALUES('invest-address-address-field', 'ca', 'Adreça:');
INSERT INTO `text` VALUES('invest-address-address-field', 'en', 'Address:');
INSERT INTO `text` VALUES('invest-address-country-field', 'ca', '    País:');
INSERT INTO `text` VALUES('invest-address-country-field', 'en', 'Country:');
INSERT INTO `text` VALUES('invest-address-header', 'ca', 'On vols rebre la recompensa');
INSERT INTO `text` VALUES('invest-address-header', 'en', 'Where would you like to receive the reward (only if it''s sent via the postal service)');
INSERT INTO `text` VALUES('invest-address-location-field', 'ca', 'Ciutat:');
INSERT INTO `text` VALUES('invest-address-location-field', 'en', 'City');
INSERT INTO `text` VALUES('invest-address-name-field', 'ca', 'Nom:');
INSERT INTO `text` VALUES('invest-address-name-field', 'en', 'Name:');
INSERT INTO `text` VALUES('invest-address-nif-field', 'ca', 'Número de NIF / NIE / VAT:');
INSERT INTO `text` VALUES('invest-address-nif-field', 'en', 'VAT:');
INSERT INTO `text` VALUES('invest-address-zipcode-field', 'ca', 'Codi postal:');
INSERT INTO `text` VALUES('invest-address-zipcode-field', 'en', 'Postal code:');
INSERT INTO `text` VALUES('invest-amount', 'ca', 'Quantitat');
INSERT INTO `text` VALUES('invest-amount', 'en', 'Quantity');
INSERT INTO `text` VALUES('invest-amount-error', 'ca', 'Has d''indicar l''import');
INSERT INTO `text` VALUES('invest-amount-error', 'en', 'You have to write an amount');
INSERT INTO `text` VALUES('invest-amount-tooltip', 'ca', 'Introdueix la quantitat amb que recolzaràs el projecte');
INSERT INTO `text` VALUES('invest-amount-tooltip', 'en', 'Enter the quantity with which you want to support this project');
INSERT INTO `text` VALUES('invest-anonymous', 'ca', 'Vull que la meva aportació sigui anònima \r\n');
INSERT INTO `text` VALUES('invest-anonymous', 'en', 'Please make my donation anonymous');
INSERT INTO `text` VALUES('invest-create-error', 'ca', 'Hi ha hagut algun problema en inicialitzar la transacció');
INSERT INTO `text` VALUES('invest-create-error', 'en', 'There has been a problem when processing the payment');
INSERT INTO `text` VALUES('invest-data-error', 'ca', 'No s''han rebut les dades necessàries');
INSERT INTO `text` VALUES('invest-data-error', 'en', 'Necessary data missing');
INSERT INTO `text` VALUES('invest-donation-header', 'ca', 'Introdueix les dades fiscals per al donatiu');
INSERT INTO `text` VALUES('invest-donation-header', 'en', 'Enter the fiscal information for this donation');
INSERT INTO `text` VALUES('invest-individual-header', 'ca', 'Pots renunciar a rebre recompenses per la teva aportació, o seleccionar les que igualin o estiguin per sota de l''import que hagis introduït.');
INSERT INTO `text` VALUES('invest-individual-header', 'en', 'You can decline to receive rewards for your contribution, or select those that match or are under the amount that you have entered.');
INSERT INTO `text` VALUES('invest-next_step', 'ca', 'Pas següent ');
INSERT INTO `text` VALUES('invest-next_step', 'en', 'Next step');
INSERT INTO `text` VALUES('invest-owner-error', 'ca', 'Ets l''autor del projecte, no pots aportar personalment al teu propi projecte');
INSERT INTO `text` VALUES('invest-payment-email', 'ca', 'Introdueix el teu compte de PayPal');
INSERT INTO `text` VALUES('invest-payment-email', 'en', 'Enter your PayPal user name');
INSERT INTO `text` VALUES('invest-payment_method-header', 'ca', 'Escull el mètode de pagament');
INSERT INTO `text` VALUES('invest-payment_method-header', 'en', 'Choose a method of payment');
INSERT INTO `text` VALUES('invest-paypal-error_fatal', 'ca', 'Ha ocorregut un error fatal en connectar amb PayPal. S''ha reportat la incidència, disculpa les molèsties.');
INSERT INTO `text` VALUES('invest-paypal-error_fatal', 'en', 'There has been a fatal error when connecting to PayPal. The incidence has been notified, sorry for the inconvenience.');
INSERT INTO `text` VALUES('invest-resign', 'ca', 'Renuncio a una recompensa individual, només vull ajudar al projecte \r\n');
INSERT INTO `text` VALUES('invest-resign', 'en', 'I don''t want any reward, I just want to help the project');
INSERT INTO `text` VALUES('invest-reward-none', 'ca', 'Ja no es pot escollir');
INSERT INTO `text` VALUES('invest-reward-none', 'en', 'These can no longer be chosen');
INSERT INTO `text` VALUES('invest-social-header', 'ca', 'Amb els retorns col·lectius hi guanyem tots');
INSERT INTO `text` VALUES('invest-social-header', 'en', 'With collective benefits, we all move forward');
INSERT INTO `text` VALUES('invest-tpv-error_fatal', 'ca', 'Ha ocorregut un error fatal en connectar amb el TPV. S''ha reportat la incidència, disculpa les molèsties.');
INSERT INTO `text` VALUES('invest-tpv-error_fatal', 'en', 'There has been a fatal error when checking out. The incidence has been notified, sorry for the inconvenience.');
INSERT INTO `text` VALUES('leave-email-sended', 'ca', 'T''hem enviat un email per completar el procés de baixa. Verifica també la carpeta de correu no desitjat o /Spam.');
INSERT INTO `text` VALUES('leave-email-sended', 'en', 'We have sent you an email to confirm the closure of your account. If you don''t find it right away, look in your junk or spam folder.');
INSERT INTO `text` VALUES('leave-process-completed', 'ca', 'El compte s''ha donat de baixa correctament');
INSERT INTO `text` VALUES('leave-process-completed', 'en', 'The account was closed correctly.');
INSERT INTO `text` VALUES('leave-process-fail', 'ca', 'No hem pogut completar el procés per donar-te de baixa. Per favor, contacta''ns a hola@goteo.org');
INSERT INTO `text` VALUES('leave-process-fail', 'en', 'We were not able to close the account. Please contact us at hola@goteo.org');
INSERT INTO `text` VALUES('leave-request-fail', 'ca', 'No hem trobat cap compte amb aquest email a la nostra base de dades per donar-lo de baixa');
INSERT INTO `text` VALUES('leave-request-fail', 'en', 'We didn''t find any account with that email in our database.');
INSERT INTO `text` VALUES('leave-token-incorrect', 'ca', 'El codi per a completar el procés de baixa no és vàlid');
INSERT INTO `text` VALUES('leave-token-incorrect', 'en', 'The code for completing the closure of the account is not valid.');
INSERT INTO `text` VALUES('login-access-button', 'ca', 'Entrar');
INSERT INTO `text` VALUES('login-access-button', 'en', 'Enter');
INSERT INTO `text` VALUES('login-access-header', 'ca', 'Usuari registrat');
INSERT INTO `text` VALUES('login-access-header', 'en', 'Registered user');
INSERT INTO `text` VALUES('login-access-password-field', 'ca', 'Contrasenya');
INSERT INTO `text` VALUES('login-access-password-field', 'en', 'Password');
INSERT INTO `text` VALUES('login-access-username-field', 'ca', 'Nom d''accés ');
INSERT INTO `text` VALUES('login-access-username-field', 'en', 'User name');
INSERT INTO `text` VALUES('login-banner-header', 'ca', 'Accedeix a la comunitat Goteo<br /><span class="greenblue">100% obert</span>');
INSERT INTO `text` VALUES('login-banner-header', 'de', 'Mach mit bei der Goteo Community<br /><span class="greenblue">100% offen</span>');
INSERT INTO `text` VALUES('login-banner-header', 'en', '    Get access to the <br /><span class="greenblue">100% open</span> Goteo community');
INSERT INTO `text` VALUES('login-fail', 'ca', 'Error d''accés');
INSERT INTO `text` VALUES('login-fail', 'en', 'Error');
INSERT INTO `text` VALUES('login-leave-button', 'ca', 'Donar de baixa');
INSERT INTO `text` VALUES('login-leave-button', 'en', 'Close the account');
INSERT INTO `text` VALUES('login-leave-header', 'ca', 'Cancel·lar el compte');
INSERT INTO `text` VALUES('login-leave-header', 'en', 'Close the account');
INSERT INTO `text` VALUES('login-leave-message', 'ca', 'Deixa''ns un missatge');
INSERT INTO `text` VALUES('login-leave-message', 'en', 'Leave a message');
INSERT INTO `text` VALUES('login-oneclick-header', 'ca', 'Accedeix amb només un clic');
INSERT INTO `text` VALUES('login-oneclick-header', 'en', 'Log in with a single click');
INSERT INTO `text` VALUES('login-recover-button', 'ca', 'Recuperar');
INSERT INTO `text` VALUES('login-recover-button', 'en', 'Recover');
INSERT INTO `text` VALUES('login-recover-email-field', 'ca', 'Email del compte');
INSERT INTO `text` VALUES('login-recover-email-field', 'en', 'Account email');
INSERT INTO `text` VALUES('login-recover-header', 'ca', 'Recuperar contrasenya');
INSERT INTO `text` VALUES('login-recover-header', 'en', 'Recover password');
INSERT INTO `text` VALUES('login-recover-link', 'ca', 'Recuperar contrasenya ');
INSERT INTO `text` VALUES('login-recover-link', 'en', 'Recover password');
INSERT INTO `text` VALUES('login-recover-username-field', 'ca', 'Nom d''accés ');
INSERT INTO `text` VALUES('login-recover-username-field', 'en', 'User name');
INSERT INTO `text` VALUES('login-register-button', 'ca', 'Registrar');
INSERT INTO `text` VALUES('login-register-button', 'en', 'Register');
INSERT INTO `text` VALUES('login-register-conditions', 'ca', 'Accepto les condicions d''ús de la plataforma, i dono el meu consentiment per al tractament de les meves dades personals. Per a això, el responsable del portal ha establert una <a href="/legal/privacy" target="_blank">polí­tica de privadesa</a> on es pot conèixer la finalitat que se li donaran a les dades subministrades a través d''aquest formulari, així­ com els drets que assisteixen la persona que subministra aquestes dades.');
INSERT INTO `text` VALUES('login-register-conditions', 'en', 'I accept the platforms terms of service, and give my permission for the treatment of my personal data. To that end, those responsible for the site have established a <a href="/legal/privacy" target="_blank">privacy policy</a> that states how the data will be submitted through the current form, as well as the rights that belong to the person who submits said data.');
INSERT INTO `text` VALUES('login-register-confirm-field', 'ca', 'Confirmar email');
INSERT INTO `text` VALUES('login-register-confirm-field', 'en', 'Confirm email address');
INSERT INTO `text` VALUES('login-register-confirm_password-field', 'ca', 'Confirmar contrasenya  ');
INSERT INTO `text` VALUES('login-register-confirm_password-field', 'en', 'Confirm password');
INSERT INTO `text` VALUES('login-register-email-field', 'ca', 'Email');
INSERT INTO `text` VALUES('login-register-email-field', 'en', 'Email address');
INSERT INTO `text` VALUES('login-register-header', 'ca', 'Nou usuari');
INSERT INTO `text` VALUES('login-register-header', 'en', 'New user');
INSERT INTO `text` VALUES('login-register-password-field', 'ca', 'Contrasenya');
INSERT INTO `text` VALUES('login-register-password-field', 'en', 'Password');
INSERT INTO `text` VALUES('login-register-password-minlength', 'ca', 'Mínim 6 caràcters ');
INSERT INTO `text` VALUES('login-register-password-minlength', 'en', 'At least 6 characters');
INSERT INTO `text` VALUES('login-register-userid-field', 'ca', 'Nom d''accés ');
INSERT INTO `text` VALUES('login-register-userid-field', 'en', 'User name');
INSERT INTO `text` VALUES('login-register-username-field', 'ca', 'Nom públic  ');
INSERT INTO `text` VALUES('login-register-username-field', 'en', 'Screen name');
INSERT INTO `text` VALUES('login-signin-facebook', 'ca', 'Accedeix amb Facebook');
INSERT INTO `text` VALUES('login-signin-facebook', 'en', 'Log in with Facebook');
INSERT INTO `text` VALUES('login-signin-google', 'ca', 'Accedeix amb Google');
INSERT INTO `text` VALUES('login-signin-google', 'en', 'Log in with Google');
INSERT INTO `text` VALUES('login-signin-linkedin', 'ca', 'Accedeix amb LinkedIn');
INSERT INTO `text` VALUES('login-signin-linkedin', 'en', 'Log in with LinkedIn');
INSERT INTO `text` VALUES('login-signin-myopenid', 'ca', 'Accedeix amb myOpenID');
INSERT INTO `text` VALUES('login-signin-myopenid', 'en', 'Log in with myOpenID');
INSERT INTO `text` VALUES('login-signin-openid', 'ca', 'Un altre servidor Open ID');
INSERT INTO `text` VALUES('login-signin-openid', 'en', 'Other Open ID server');
INSERT INTO `text` VALUES('login-signin-openid-go', 'ca', 'Ok');
INSERT INTO `text` VALUES('login-signin-openid-go', 'en', 'Go');
INSERT INTO `text` VALUES('login-signin-twitter', 'ca', 'Accedeix amb Twitter');
INSERT INTO `text` VALUES('login-signin-twitter', 'en', 'Log in with Twitter');
INSERT INTO `text` VALUES('login-signin-view-more', 'ca', 'Mostrar més opcions d''accés');
INSERT INTO `text` VALUES('login-signin-view-more', 'en', 'More login options');
INSERT INTO `text` VALUES('login-signin-yahoo', 'ca', 'Accedeix amb Yahoo');
INSERT INTO `text` VALUES('login-signin-yahoo', 'en', 'Log in with Yahoo');
INSERT INTO `text` VALUES('mailer-baja', 'ca', 'No vols rebre més comunicacions de Goteo? Pots donar el teu email de baixa mitjançant aquest <a href="%s">link</a>');
INSERT INTO `text` VALUES('mailer-baja', 'en', 'If you don''t want to receive more updates form Goteo.org, you can unsubscribe your email address with <a href="%s">link</a>');
INSERT INTO `text` VALUES('mailer-disclaimer', 'ca', 'Goteo és una plataforma digital per al finançament col·lectiu, col·laboració i distribució de recursos per al desenvolupament de projectes socials, culturals, educatius, tecnològics... que contribueixin a l''enfortiment del béns comuns, el codi obert i/o el coneixement lliure.');
INSERT INTO `text` VALUES('mailer-disclaimer', 'en', 'Goteo this is a translation test');
INSERT INTO `text` VALUES('mailer-sinoves', 'ca', 'Si no pots veure aquest missatge utilitza aquest <a href="%s">link</a>');
INSERT INTO `text` VALUES('mailer-sinoves', 'en', 'If you can''t see this message use this <a href="%s">link</a>');
INSERT INTO `text` VALUES('main-banner-header', 'ca', '<h2 class="message">Xarxa social per <span class="greenblue">cofinançar i col·laborar amb</span><br /> projectes creatius que fomentin els béns comuns<br /> Tens un projecte amb <span class="greenblue">ADN obert</span>?</h2><a href="/contact" class="button banner-button">Contacta''ns</a>');
INSERT INTO `text` VALUES('main-banner-header', 'de', '<h2 class="message">Das soziale Netzwerk<span class="greenblue"> zur Ko-Finanzierung und Mitarbeit </span><br />bei Projekten, die das Gemeinwohl fördern.<br /> ¿Hast du ein Projekt mit <span class="greenblue">offener DNA</span>?</h2><a href="/contact" class="button banner-button">Kontaktiere uns</a>');
INSERT INTO `text` VALUES('main-banner-header', 'en', '    <h2 class="message">Social network for <span class="greenblue">co-financing and collaborating with </span><br /> creative projects that further the common good<br /> Do you have a project with <span class="greenblue">open DNA</span>?</h2><a href="/contact" class="button banner-button">Contact us!</a>');
INSERT INTO `text` VALUES('mandatory-cost-field-amount', 'ca', 'És obligatori assignar un import als costos');
INSERT INTO `text` VALUES('mandatory-cost-field-amount', 'en', 'You must assign an amount to the expenses');
INSERT INTO `text` VALUES('mandatory-cost-field-description', 'ca', 'És obligatori posar alguna descripció als costos');
INSERT INTO `text` VALUES('mandatory-cost-field-description', 'en', 'You must add a description for the expenses');
INSERT INTO `text` VALUES('mandatory-cost-field-name', 'ca', 'És obligatori posar-li un nom al cost');
INSERT INTO `text` VALUES('mandatory-cost-field-name', 'en', 'You must label the expenses');
INSERT INTO `text` VALUES('mandatory-cost-field-task_dates', 'ca', 'És obligatori especificar les dates aproximades de la tasca');
INSERT INTO `text` VALUES('mandatory-cost-field-task_dates', 'en', 'You must specify the approximate dates for the task');
INSERT INTO `text` VALUES('mandatory-cost-field-type', 'ca', 'És obligatori seleccionar el tipus de cost');
INSERT INTO `text` VALUES('mandatory-cost-field-type', 'en', 'You have to select the type of cost');
INSERT INTO `text` VALUES('mandatory-individual_reward-field-amount', 'ca', 'És obligatori indicar l''import que permet obtenir la recompensa');
INSERT INTO `text` VALUES('mandatory-individual_reward-field-amount', 'en', 'You have to specify the amount that qualifies for the reward');
INSERT INTO `text` VALUES('mandatory-individual_reward-field-description', 'ca', 'És obligatori posar alguna descripció');
INSERT INTO `text` VALUES('mandatory-individual_reward-field-description', 'en', 'You must add a description');
INSERT INTO `text` VALUES('mandatory-individual_reward-field-icon', 'ca', 'És obligatori seleccionar el tipus de recompensa');
INSERT INTO `text` VALUES('mandatory-individual_reward-field-icon', 'en', 'You must choose the type of reward');
INSERT INTO `text` VALUES('mandatory-individual_reward-field-name', 'ca', 'És obligatori posar la recompensa');
INSERT INTO `text` VALUES('mandatory-individual_reward-field-name', 'en', 'You must enter a reward');
INSERT INTO `text` VALUES('mandatory-project-costs', 'ca', 'Ha de desglossar-se com a mínim en dos costos.');
INSERT INTO `text` VALUES('mandatory-project-costs', 'en', 'These should be broken down into at least two groups.');
INSERT INTO `text` VALUES('mandatory-project-field-about', 'ca', 'És obligatori explicar les característiques bàsiques del projecte');
INSERT INTO `text` VALUES('mandatory-project-field-about', 'en', 'You have to explain the project''s basic characteristics');
INSERT INTO `text` VALUES('mandatory-project-field-address', 'ca', 'L''adreça del/la responsable del projecte és obligatòria');
INSERT INTO `text` VALUES('mandatory-project-field-address', 'en', 'The project leaders'' address is required');
INSERT INTO `text` VALUES('mandatory-project-field-category', 'ca', 'És obligatori triar almenys una categoria pel projecte.');
INSERT INTO `text` VALUES('mandatory-project-field-category', 'en', 'You must choose at least one category for the project');
INSERT INTO `text` VALUES('mandatory-project-field-contract_birthdate', 'ca', 'És obligatori posar la data de naixement del responsable del projecte');
INSERT INTO `text` VALUES('mandatory-project-field-contract_birthdate', 'en', 'You must enter the project leader''s date of birth');
INSERT INTO `text` VALUES('mandatory-project-field-contract_email', 'ca', 'És obligatori posar l''email del/la responsable del projecte');
INSERT INTO `text` VALUES('mandatory-project-field-contract_email', 'en', 'You must enter the project leader''s email');
INSERT INTO `text` VALUES('mandatory-project-field-contract_name', 'ca', 'És obligatori posar el nom del/la responsable del projecte');
INSERT INTO `text` VALUES('mandatory-project-field-contract_name', 'en', 'You must enter the name of the project leader');
INSERT INTO `text` VALUES('mandatory-project-field-contract_nif', 'ca', 'És obligatori posar el document d''identificació del/la responsable del projecte');
INSERT INTO `text` VALUES('mandatory-project-field-contract_nif', 'en', 'You must enter the ID number for the project leader');
INSERT INTO `text` VALUES('mandatory-project-field-country', 'ca', 'El país del/la responsable del projecte és obligatori');
INSERT INTO `text` VALUES('mandatory-project-field-country', 'en', 'You must enter a country for the project leader');
INSERT INTO `text` VALUES('mandatory-project-field-description', 'ca', 'És obligatori resumir el projecte');
INSERT INTO `text` VALUES('mandatory-project-field-description', 'en', 'You have to enter a summary for the project');
INSERT INTO `text` VALUES('mandatory-project-field-entity_cif', 'ca', 'És obligatori posar el CIF de l''entitat jurídica');
INSERT INTO `text` VALUES('mandatory-project-field-entity_cif', 'en', 'You must enter the organization''s Business Number (CIF)');
INSERT INTO `text` VALUES('mandatory-project-field-entity_name', 'ca', 'És obligatori posar el nom de l''organització');
INSERT INTO `text` VALUES('mandatory-project-field-entity_name', 'en', 'You must enter the name of the organization');
INSERT INTO `text` VALUES('mandatory-project-field-entity_office', 'ca', 'És obligatori posar el càrrec que tens dins l''organització que representes');
INSERT INTO `text` VALUES('mandatory-project-field-entity_office', 'en', 'You must enter the position that you have in the organization that you represent');
INSERT INTO `text` VALUES('mandatory-project-field-goal', 'ca', 'És obligatori explicar els objectius en la descripció del projecte');
INSERT INTO `text` VALUES('mandatory-project-field-goal', 'en', 'You have to explain your goals in the project description');
INSERT INTO `text` VALUES('mandatory-project-field-image', 'ca', 'És obligatori vincular una imatge com a mínim al projecte');
INSERT INTO `text` VALUES('mandatory-project-field-image', 'en', 'You have to link at least one image to your project');
INSERT INTO `text` VALUES('mandatory-project-field-lang', 'ca', 'Has d''indicar l''idioma del projecte');
INSERT INTO `text` VALUES('mandatory-project-field-lang', 'en', 'You have to indicate a language for the project');
INSERT INTO `text` VALUES('mandatory-project-field-location', 'ca', 'És obligatori posar l''abast potencial del projecte');
INSERT INTO `text` VALUES('mandatory-project-field-location', 'en', 'You have to enter the potential reach for your project');
INSERT INTO `text` VALUES('mandatory-project-field-media', 'ca', 'Recomanem posar un vídeo per millorar la valoració del projecte a l''hora de decidir si publicar-ho o no a Goteo.');
INSERT INTO `text` VALUES('mandatory-project-field-media', 'en', 'We recommend uploading a video to facilitate the evaluation of your project by the Goteo team.');
INSERT INTO `text` VALUES('mandatory-project-field-motivation', 'ca', 'És obligatori explicar la motivació en la descripció del projecte');
INSERT INTO `text` VALUES('mandatory-project-field-motivation', 'en', 'You have to explain your motivation in the project description');
INSERT INTO `text` VALUES('mandatory-project-field-name', 'ca', 'És obligatori posar un nom al projecte');
INSERT INTO `text` VALUES('mandatory-project-field-name', 'en', 'You have to enter a name for the project');
INSERT INTO `text` VALUES('mandatory-project-field-phone', 'ca', 'El telèfon del/la responsable del projecte és obligatori');
INSERT INTO `text` VALUES('mandatory-project-field-phone', 'en', 'You must enter a phone number for the project leader');
INSERT INTO `text` VALUES('mandatory-project-field-related', 'ca', 'És obligatori explicar en la descripció del projecte l''experiència relacionada i/o l''equip amb que es compta  ');
INSERT INTO `text` VALUES('mandatory-project-field-related', 'en', 'In the project description, you must describe your project-related experience and the team that you''re planning on working with.');
INSERT INTO `text` VALUES('mandatory-project-field-residence', 'ca', 'És obligatori posar el lloc de residència del/la responsable del projecte');
INSERT INTO `text` VALUES('mandatory-project-field-residence', 'en', 'You must enter the project leader''s place of residence');
INSERT INTO `text` VALUES('mandatory-project-field-resource', 'ca', 'És obligatori especificar si comptes amb altres recursos o no');
INSERT INTO `text` VALUES('mandatory-project-field-resource', 'en', 'You must specify whether or not you have other resources to draw from');
INSERT INTO `text` VALUES('mandatory-project-field-zipcode', 'ca', 'El codi postal del/la responsable del projecte és obligatori.');
INSERT INTO `text` VALUES('mandatory-project-field-zipcode', 'en', 'A postal code for the project leader is required');
INSERT INTO `text` VALUES('mandatory-project-resource', 'ca', 'És obligatori especificar si comptes amb altres recursos');
INSERT INTO `text` VALUES('mandatory-project-resource', 'en', 'You must specify whether or not you have other resources to draw from');
INSERT INTO `text` VALUES('mandatory-project-total-costs', 'ca', 'És obligatori especificar algun cost');
INSERT INTO `text` VALUES('mandatory-project-total-costs', 'en', 'You must specify an expense');
INSERT INTO `text` VALUES('mandatory-register-field-email', 'ca', 'Has d''indicar un email');
INSERT INTO `text` VALUES('mandatory-register-field-email', 'en', 'You have to enter an email address');
INSERT INTO `text` VALUES('mandatory-social_reward-field-description', 'ca', 'És obligatori posar alguna descripció al retorn');
INSERT INTO `text` VALUES('mandatory-social_reward-field-description', 'en', 'You must enter a description for the benefit');
INSERT INTO `text` VALUES('mandatory-social_reward-field-icon', 'ca', 'És obligatori seleccionar el tipus de retorn');
INSERT INTO `text` VALUES('mandatory-social_reward-field-icon', 'en', 'You must choose the type of benefit');
INSERT INTO `text` VALUES('mandatory-social_reward-field-name', 'ca', 'És obligatori especificar el retorn');
INSERT INTO `text` VALUES('mandatory-social_reward-field-name', 'en', 'You must specify the benefit');
INSERT INTO `text` VALUES('mandatory-support-field-description', 'ca', 'És obligatori posar alguna descripció');
INSERT INTO `text` VALUES('mandatory-support-field-description', 'en', 'A description is required.');
INSERT INTO `text` VALUES('mandatory-support-field-name', 'ca', 'És obligatori posar-li un nom a la col·laboració');
INSERT INTO `text` VALUES('mandatory-support-field-name', 'en', 'You have to enter a name for the collaboration');
INSERT INTO `text` VALUES('oauth-confirm-user', 'ca', 'Vincular usuari existent');
INSERT INTO `text` VALUES('oauth-confirm-user', 'en', 'Connect with existing user');
INSERT INTO `text` VALUES('oauth-facebook-access-denied', 'ca', 'Accés des de Facebook denegat');
INSERT INTO `text` VALUES('oauth-facebook-access-denied', 'en', 'Facebook access denied');
INSERT INTO `text` VALUES('oauth-goteo-openid-sync-password', 'ca', 'Estàs intentant vincular un compte ja existent a Goteo amb un proveïdor extern. Això et permetrà entrar a Goteo amb un sol clic en el futur.<br>Aquesta primera vegada hauràs de proporcionar la contrasenya del teu compte de Goteo per confirmar la teva identitat.');
INSERT INTO `text` VALUES('oauth-goteo-openid-sync-password', 'en', 'You''re trying to connect an existing Goteo account with an external provider. That will allow you to access Goteo with a single click.<br>This time you must provide your Goteo account password in order to confirm your identity.');
INSERT INTO `text` VALUES('oauth-goteo-user-not-exists', 'ca', 'Aquest usuari no existeix a Goteo');
INSERT INTO `text` VALUES('oauth-goteo-user-not-exists', 'en', 'That user does not exist in Goteo');
INSERT INTO `text` VALUES('oauth-goteo-user-password-exists', 'ca', 'Aquest usuari ja existeix a Goteo');
INSERT INTO `text` VALUES('oauth-goteo-user-password-exists', 'en', 'That user already exists in Goteo');
INSERT INTO `text` VALUES('oauth-import-about', 'ca', 'Sobre tu');
INSERT INTO `text` VALUES('oauth-import-about', 'en', 'About you');
INSERT INTO `text` VALUES('oauth-import-facebook', 'ca', 'Link al teu compte de Facebook');
INSERT INTO `text` VALUES('oauth-import-facebook', 'en', 'Link to your Facebook account');
INSERT INTO `text` VALUES('oauth-import-location', 'ca', 'Lloc de residència');
INSERT INTO `text` VALUES('oauth-import-location', 'en', 'Place of residence');
INSERT INTO `text` VALUES('oauth-import-name', 'ca', 'Nom');
INSERT INTO `text` VALUES('oauth-import-name', 'en', 'Name');
INSERT INTO `text` VALUES('oauth-import-twitter', 'ca', 'Link al teu compte de Twitter');
INSERT INTO `text` VALUES('oauth-import-twitter', 'en', 'Link to your Twitter account');
INSERT INTO `text` VALUES('oauth-import-website', 'ca', 'Els teus llocs web');
INSERT INTO `text` VALUES('oauth-import-website', 'en', 'Your websites');
INSERT INTO `text` VALUES('oauth-linkedin-access-denied', 'ca', 'Accés des de LinkedIn denegat');
INSERT INTO `text` VALUES('oauth-linkedin-access-denied', 'en', 'Linkedin access denied');
INSERT INTO `text` VALUES('oauth-login-imported-data', 'ca', 'També s''importaran aquestes dades, pots canviar-les un cop autenticat:');
INSERT INTO `text` VALUES('oauth-login-imported-data', 'en', 'This data is also being imported, you can change it once authenticated:');
INSERT INTO `text` VALUES('oauth-login-welcome-from', 'ca', 'Benvingut/da a Goteo! Comprova el teu nom d''usuari i email per finalitzar el procés. En cas que no s''hagi pogut importar l''email o ho canviïs per un altre, rebràs un correu electrònic amb un link d''activació per comprovar la seva validesa.');
INSERT INTO `text` VALUES('oauth-login-welcome-from', 'en', 'Welcome to Goteo. Please check your username and email to finish the process. In case we could not import the email address (or if you change it for another) an email will be sent with an activation link for validation.');
INSERT INTO `text` VALUES('oauth-openid-access-denied', 'ca', 'Accés des de Open ID denegat');
INSERT INTO `text` VALUES('oauth-openid-access-denied', 'en', 'Open ID access denied');
INSERT INTO `text` VALUES('oauth-openid-not-logged', 'ca', 'Usuari desconnectat des d''Open ID');
INSERT INTO `text` VALUES('oauth-openid-not-logged', 'en', 'User disconnected from Open ID');
INSERT INTO `text` VALUES('oauth-token-request-error', 'ca', 'Ha ocorregut un error en obtenir les credencials amb el proveïdor');
INSERT INTO `text` VALUES('oauth-token-request-error', 'en', 'There has been an error when getting data from provider');
INSERT INTO `text` VALUES('oauth-twitter-access-denied', 'ca', 'Accés des de Twitter denegat');
INSERT INTO `text` VALUES('oauth-twitter-access-denied', 'en', 'Twitter access denied');
INSERT INTO `text` VALUES('oauth-unknown-provider', 'ca', 'No es pot iniciar sessió amb aquest proveïdor');
INSERT INTO `text` VALUES('oauth-unknown-provider', 'en', 'Session can''t be started with this provider');
INSERT INTO `text` VALUES('open-banner-header', 'ca', '<div class="modpo-open">OPEN</div><div class="modpo-percent">100&#37; OBERT</div><div class="modpo-whyopen">%s</div>');
INSERT INTO `text` VALUES('open-banner-header', 'de', '    <div class="modpo-open">OPEN</div><div class="modpo-percent">100% OFFEN</div><div class="modpo-whyopen">%s</div>');
INSERT INTO `text` VALUES('open-banner-header', 'en', '<div class="modpo-open">OPEN</div><div class="modpo-percent">100&#37; OPEN</div><div class="modpo-whyopen">%s</div>');
INSERT INTO `text` VALUES('overview-field-about', 'ca', 'Característiques bàsiques');
INSERT INTO `text` VALUES('overview-field-about', 'en', 'About');
INSERT INTO `text` VALUES('overview-field-categories', 'ca', 'Categories');
INSERT INTO `text` VALUES('overview-field-categories', 'en', 'Categories');
INSERT INTO `text` VALUES('overview-field-currently', 'ca', 'Estat actual');
INSERT INTO `text` VALUES('overview-field-currently', 'en', 'Current status');
INSERT INTO `text` VALUES('overview-field-description', 'ca', 'Breu descripció');
INSERT INTO `text` VALUES('overview-field-description', 'en', 'Brief description');
INSERT INTO `text` VALUES('overview-field-goal', 'ca', 'Objectius');
INSERT INTO `text` VALUES('overview-field-goal', 'en', 'Goals of the Crowdfunding Campaign');
INSERT INTO `text` VALUES('overview-field-image_gallery', 'ca', 'Imatges actuals');
INSERT INTO `text` VALUES('overview-field-image_gallery', 'en', 'Current images');
INSERT INTO `text` VALUES('overview-field-image_upload', 'ca', 'Pujar una imatge');
INSERT INTO `text` VALUES('overview-field-image_upload', 'en', 'Upload an image');
INSERT INTO `text` VALUES('overview-field-keywords', 'ca', 'Paraules clau del projecte');
INSERT INTO `text` VALUES('overview-field-keywords', 'en', 'Key words for the project');
INSERT INTO `text` VALUES('overview-field-lang', 'ca', 'Idioma original');
INSERT INTO `text` VALUES('overview-field-lang', 'en', 'Original language');
INSERT INTO `text` VALUES('overview-field-media', 'ca', 'Vídeo de presentació');
INSERT INTO `text` VALUES('overview-field-media', 'en', 'Video introduction');
INSERT INTO `text` VALUES('overview-field-media_preview', 'ca', 'Vista prèvia');
INSERT INTO `text` VALUES('overview-field-media_preview', 'en', 'Preview');
INSERT INTO `text` VALUES('overview-field-motivation', 'ca', 'Motivació i a qui va dirigit el projecte ');
INSERT INTO `text` VALUES('overview-field-motivation', 'en', 'Why this is important for you');
INSERT INTO `text` VALUES('overview-field-name', 'ca', 'Títol del projecte');
INSERT INTO `text` VALUES('overview-field-name', 'en', 'Project title');
INSERT INTO `text` VALUES('overview-field-options-currently_avanzado', 'ca', 'Avançat');
INSERT INTO `text` VALUES('overview-field-options-currently_avanzado', 'en', 'Advanced');
INSERT INTO `text` VALUES('overview-field-options-currently_finalizado', 'ca', 'Finalitzat ');
INSERT INTO `text` VALUES('overview-field-options-currently_finalizado', 'en', 'Finished');
INSERT INTO `text` VALUES('overview-field-options-currently_inicial', 'ca', 'Inicial');
INSERT INTO `text` VALUES('overview-field-options-currently_inicial', 'en', 'Starting');
INSERT INTO `text` VALUES('overview-field-options-currently_medio', 'ca', 'Mitjà');
INSERT INTO `text` VALUES('overview-field-options-currently_medio', 'en', 'Midway');
INSERT INTO `text` VALUES('overview-field-options-scope_global', 'ca', 'Global ');
INSERT INTO `text` VALUES('overview-field-options-scope_global', 'en', 'Global');
INSERT INTO `text` VALUES('overview-field-options-scope_local', 'ca', 'Local');
INSERT INTO `text` VALUES('overview-field-options-scope_local', 'en', 'Local');
INSERT INTO `text` VALUES('overview-field-options-scope_nacional', 'ca', 'Nacional');
INSERT INTO `text` VALUES('overview-field-options-scope_nacional', 'en', 'National');
INSERT INTO `text` VALUES('overview-field-options-scope_regional', 'ca', 'Regional');
INSERT INTO `text` VALUES('overview-field-options-scope_regional', 'en', 'Regional');
INSERT INTO `text` VALUES('overview-field-project_location', 'ca', 'Ubicació');
INSERT INTO `text` VALUES('overview-field-project_location', 'en', 'Location');
INSERT INTO `text` VALUES('overview-field-related', 'ca', 'Experiència prèvia i equip ');
INSERT INTO `text` VALUES('overview-field-related', 'en', 'Team and Experience');
INSERT INTO `text` VALUES('overview-field-scope', 'ca', 'Abast del projecte');
INSERT INTO `text` VALUES('overview-field-scope', 'en', 'Project reach');
INSERT INTO `text` VALUES('overview-field-subtitle', 'ca', 'Frase de resum');
INSERT INTO `text` VALUES('overview-field-subtitle', 'en', 'Summary');
INSERT INTO `text` VALUES('overview-field-usubs', 'ca', 'Carregar amb Universal Subtitles');
INSERT INTO `text` VALUES('overview-field-usubs', 'en', 'Publish with Universal Subtitles');
INSERT INTO `text` VALUES('overview-field-video', 'ca', 'Vídeo addicional sobre motivació');
INSERT INTO `text` VALUES('overview-field-video', 'en', 'Additional video about motivation');
INSERT INTO `text` VALUES('overview-fields-images-title', 'ca', 'Imatges del projecte');
INSERT INTO `text` VALUES('overview-fields-images-title', 'en', 'Images from the project');
INSERT INTO `text` VALUES('overview-main-header', 'ca', 'Descripció del projecte');
INSERT INTO `text` VALUES('overview-main-header', 'en', 'Project description');
INSERT INTO `text` VALUES('personal-field-address', 'ca', 'Adreça');
INSERT INTO `text` VALUES('personal-field-address', 'en', 'Address');
INSERT INTO `text` VALUES('personal-field-contract_birthdate', 'ca', 'Data de naixement ');
INSERT INTO `text` VALUES('personal-field-contract_birthdate', 'en', 'Date of birth');
INSERT INTO `text` VALUES('personal-field-contract_data', 'ca', 'Dades del/la responsable del projecte');
INSERT INTO `text` VALUES('personal-field-contract_data', 'en', 'Information about the project leader');
INSERT INTO `text` VALUES('personal-field-contract_email', 'ca', 'Email vinculat al projecte');
INSERT INTO `text` VALUES('personal-field-contract_email', 'en', 'Email linked to the project');
INSERT INTO `text` VALUES('personal-field-contract_entity', 'ca', 'Promotor/a del projecte');
INSERT INTO `text` VALUES('personal-field-contract_entity', 'en', 'Project promoter');
INSERT INTO `text` VALUES('personal-field-contract_entity-entity', 'ca', 'Persona jurídica (associacions, fundacions, empreses)');
INSERT INTO `text` VALUES('personal-field-contract_entity-entity', 'en', 'Legal entity (associations, foundations, businesses, etc.)');
INSERT INTO `text` VALUES('personal-field-contract_entity-person', 'ca', 'Persona física');
INSERT INTO `text` VALUES('personal-field-contract_entity-person', 'en', 'Person');
INSERT INTO `text` VALUES('personal-field-contract_name', 'ca', 'Nom i cognoms');
INSERT INTO `text` VALUES('personal-field-contract_name', 'en', 'First and last names');
INSERT INTO `text` VALUES('personal-field-contract_nif', 'ca', 'Número de NIF / NIE / VAT');
INSERT INTO `text` VALUES('personal-field-contract_nif', 'en', 'NIF / NIE / VAT number');
INSERT INTO `text` VALUES('personal-field-country', 'ca', 'País');
INSERT INTO `text` VALUES('personal-field-country', 'en', 'Country');
INSERT INTO `text` VALUES('personal-field-entity_cif', 'ca', 'CIF de l''entitat');
INSERT INTO `text` VALUES('personal-field-entity_cif', 'en', 'Organization''s Business ID Number (CIF)');
INSERT INTO `text` VALUES('personal-field-entity_name', 'ca', 'Denominació social (nom) de l''entitat');
INSERT INTO `text` VALUES('personal-field-entity_name', 'en', 'Official name of the organization');
INSERT INTO `text` VALUES('personal-field-entity_office', 'ca', 'Càrrec a l''organització');
INSERT INTO `text` VALUES('personal-field-entity_office', 'en', 'Position in the organization');
INSERT INTO `text` VALUES('personal-field-location', 'ca', 'Localitat');
INSERT INTO `text` VALUES('personal-field-location', 'en', 'City');
INSERT INTO `text` VALUES('personal-field-main_address', 'ca', 'Domicili fiscal');
INSERT INTO `text` VALUES('personal-field-main_address', 'en', 'Legal address');
INSERT INTO `text` VALUES('personal-field-phone', 'ca', 'Telèfon');
INSERT INTO `text` VALUES('personal-field-phone', 'en', 'Telephone');
INSERT INTO `text` VALUES('personal-field-post_address', 'ca', 'Domicili postal');
INSERT INTO `text` VALUES('personal-field-post_address', 'en', 'Postal address');
INSERT INTO `text` VALUES('personal-field-post_address-different', 'ca', 'Diferent');
INSERT INTO `text` VALUES('personal-field-post_address-different', 'en', 'Different');
INSERT INTO `text` VALUES('personal-field-post_address-same', 'ca', 'Igual');
INSERT INTO `text` VALUES('personal-field-post_address-same', 'en', 'Same');
INSERT INTO `text` VALUES('personal-field-zipcode', 'ca', 'Codi postal');
INSERT INTO `text` VALUES('personal-field-zipcode', 'en', 'Postal code');
INSERT INTO `text` VALUES('personal-main-header', 'ca', 'Dades personals');
INSERT INTO `text` VALUES('personal-main-header', 'en', 'Information about the project leader');
INSERT INTO `text` VALUES('preview-main-header', 'ca', 'Previsualització de dades:');
INSERT INTO `text` VALUES('preview-main-header', 'en', 'Information preview');
INSERT INTO `text` VALUES('preview-send-comment', 'ca', 'Notes addicionals per a l''administrador');
INSERT INTO `text` VALUES('preview-send-comment', 'en', 'Additional notes for the administrator');
INSERT INTO `text` VALUES('profile-about-header', 'ca', 'Sobre mi');
INSERT INTO `text` VALUES('profile-about-header', 'en', 'About me');
INSERT INTO `text` VALUES('profile-field-about', 'ca', 'Explica''ns alguna cosa sobre tu');
INSERT INTO `text` VALUES('profile-field-about', 'en', 'Tell us something about yourself');
INSERT INTO `text` VALUES('profile-field-avatar_current', 'ca', 'La teva imatge actual');
INSERT INTO `text` VALUES('profile-field-avatar_current', 'en', 'Your current image');
INSERT INTO `text` VALUES('profile-field-avatar_upload', 'ca', 'Pujar una imatge');
INSERT INTO `text` VALUES('profile-field-avatar_upload', 'en', 'Upload an image');
INSERT INTO `text` VALUES('profile-field-contribution', 'ca', 'Què pots aportar a Goteo');
INSERT INTO `text` VALUES('profile-field-contribution', 'en', 'What can you offer Goteo?');
INSERT INTO `text` VALUES('profile-field-interests', 'ca', 'Quin tipus de projecte et motiva més');
INSERT INTO `text` VALUES('profile-field-interests', 'en', 'What kind of project motivates you most?');
INSERT INTO `text` VALUES('profile-field-keywords', 'ca', 'Temes que t''interessen');
INSERT INTO `text` VALUES('profile-field-keywords', 'en', 'Topics that interest you');
INSERT INTO `text` VALUES('profile-field-location', 'ca', 'Lloc de residència habitual');
INSERT INTO `text` VALUES('profile-field-location', 'en', 'Place of residence');
INSERT INTO `text` VALUES('profile-field-name', 'ca', 'Nom d''usuari/a');
INSERT INTO `text` VALUES('profile-field-name', 'en', 'User name');
INSERT INTO `text` VALUES('profile-field-url', 'ca', 'URL');
INSERT INTO `text` VALUES('profile-field-url', 'en', 'URL');
INSERT INTO `text` VALUES('profile-field-websites', 'ca', 'Les meves pàgines web ');
INSERT INTO `text` VALUES('profile-field-websites', 'en', 'My web pages');
INSERT INTO `text` VALUES('profile-fields-image-title', 'ca', 'Imatge de perfil');
INSERT INTO `text` VALUES('profile-fields-image-title', 'en', 'Profile image');
INSERT INTO `text` VALUES('profile-fields-social-title', 'ca', 'Perfils socials');
INSERT INTO `text` VALUES('profile-fields-social-title', 'en', 'Social profiles');
INSERT INTO `text` VALUES('profile-interests-header', 'ca', 'M''interessen projectes amb finalitat de tipus...');
INSERT INTO `text` VALUES('profile-interests-header', 'en', 'I am interested in projects that are...');
INSERT INTO `text` VALUES('profile-invest_on-header', 'ca', 'Projectes que recolzo');
INSERT INTO `text` VALUES('profile-invest_on-header', 'en', 'Projects that I support');
INSERT INTO `text` VALUES('profile-invest_on-title', 'ca', 'Cofinança');
INSERT INTO `text` VALUES('profile-invest_on-title', 'en', 'Co-finances');
INSERT INTO `text` VALUES('profile-keywords-header', 'ca', 'Les meves paraules clau');
INSERT INTO `text` VALUES('profile-keywords-header', 'en', 'My key words');
INSERT INTO `text` VALUES('profile-last_worth-title', 'ca', 'Data');
INSERT INTO `text` VALUES('profile-last_worth-title', 'en', 'Date');
INSERT INTO `text` VALUES('profile-location-header', 'ca', 'La meva ubicació');
INSERT INTO `text` VALUES('profile-location-header', 'en', 'My location');
INSERT INTO `text` VALUES('profile-main-header', 'ca', 'Dades de perfil');
INSERT INTO `text` VALUES('profile-main-header', 'en', 'Profile data');
INSERT INTO `text` VALUES('profile-my_investors-header', 'ca', 'Els meus cofinançadors');
INSERT INTO `text` VALUES('profile-my_investors-header', 'en', 'My co-financiers');
INSERT INTO `text` VALUES('profile-my_projects-header', 'ca', 'Els meus projectes');
INSERT INTO `text` VALUES('profile-my_projects-header', 'en', 'My projects');
INSERT INTO `text` VALUES('profile-my_worth-header', 'ca', 'El meu cabal a Goteo');
INSERT INTO `text` VALUES('profile-my_worth-header', 'en', 'My flow on Goteo');
INSERT INTO `text` VALUES('profile-name-header', 'ca', 'Perfil de ');
INSERT INTO `text` VALUES('profile-name-header', 'en', 'Profile of');
INSERT INTO `text` VALUES('profile-sharing_interests-header', 'ca', 'Compartint interessos');
INSERT INTO `text` VALUES('profile-sharing_interests-header', 'en', 'Sharing interests');
INSERT INTO `text` VALUES('profile-social-header', 'ca', 'Social');
INSERT INTO `text` VALUES('profile-social-header', 'en', 'Social');
INSERT INTO `text` VALUES('profile-webs-header', 'ca', 'Les meves webs');
INSERT INTO `text` VALUES('profile-webs-header', 'en', 'My websites');
INSERT INTO `text` VALUES('profile-widget-button', 'ca', 'Veure perfil');
INSERT INTO `text` VALUES('profile-widget-button', 'en', 'Show profile');
INSERT INTO `text` VALUES('profile-widget-user-header', 'ca', 'Usuari');
INSERT INTO `text` VALUES('profile-widget-user-header', 'en', 'User');
INSERT INTO `text` VALUES('profile-worth-title', 'ca', 'Aporta aquí:');
INSERT INTO `text` VALUES('profile-worth-title', 'en', 'Contribute here:');
INSERT INTO `text` VALUES('profile-worthcracy-title', 'ca', 'Posició');
INSERT INTO `text` VALUES('profile-worthcracy-title', 'en', 'Position');
INSERT INTO `text` VALUES('project-collaborations-supertitle', 'ca', 'Necessitats no monetàries');
INSERT INTO `text` VALUES('project-collaborations-supertitle', 'en', 'Non-economic needs');
INSERT INTO `text` VALUES('project-collaborations-title', 'ca', 'Cercant');
INSERT INTO `text` VALUES('project-collaborations-title', 'en', 'Looking for');
INSERT INTO `text` VALUES('project-form-header', 'ca', 'Formulari');
INSERT INTO `text` VALUES('project-form-header', 'en', 'Form');
INSERT INTO `text` VALUES('project-invest-closed', 'ca', 'El projecte ja no està en campanya');
INSERT INTO `text` VALUES('project-invest-closed', 'en', 'The project page has expired');
INSERT INTO `text` VALUES('project-invest-continue', 'ca', 'Tria el mode de pagament');
INSERT INTO `text` VALUES('project-invest-continue', 'en', 'Choose a method of payment');
INSERT INTO `text` VALUES('project-invest-fail', 'ca', 'Quelcom ha fallat, si us plau prova-ho de nou ');
INSERT INTO `text` VALUES('project-invest-fail', 'en', 'Something went wrong, please try again.');
INSERT INTO `text` VALUES('project-invest-guest', 'ca', 'Convidat (no oblidis registrar-te)');
INSERT INTO `text` VALUES('project-invest-guest', 'en', 'Guest (Don''t forget to register)');
INSERT INTO `text` VALUES('project-invest-ok', 'ca', 'S''ha tramitat la teva aportació per cofinançar aquest projecte :) ');
INSERT INTO `text` VALUES('project-invest-ok', 'en', 'Your contribution to co-finance this project has been processed :)');
INSERT INTO `text` VALUES('project-invest-start', 'ca', 'Estàs a un pas de ser cofinançador/a d''aquest projecte');
INSERT INTO `text` VALUES('project-invest-start', 'en', 'You''re one step away from becoming a co-financer of this project');
INSERT INTO `text` VALUES('project-invest-thanks_mail-fail', 'ca', 'Hi ha hagut algun error en enviar el missatge d''agraïment');
INSERT INTO `text` VALUES('project-invest-thanks_mail-fail', 'en', 'There has been an error when sending the gratitude message');
INSERT INTO `text` VALUES('project-invest-thanks_mail-success', 'ca', 'Missatge d''agraïment enviat correctament');
INSERT INTO `text` VALUES('project-invest-thanks_mail-success', 'en', 'Gratitude message correctly sent');
INSERT INTO `text` VALUES('project-invest-total', 'ca', 'Total d''aportacions');
INSERT INTO `text` VALUES('project-invest-total', 'en', 'Total contributions');
INSERT INTO `text` VALUES('project-menu-home', 'ca', 'Projecte');
INSERT INTO `text` VALUES('project-menu-home', 'en', 'Project');
INSERT INTO `text` VALUES('project-menu-messages', 'ca', 'Missatges');
INSERT INTO `text` VALUES('project-menu-messages', 'en', 'Messages');
INSERT INTO `text` VALUES('project-menu-needs', 'ca', 'Necessitats');
INSERT INTO `text` VALUES('project-menu-needs', 'en', 'Needs');
INSERT INTO `text` VALUES('project-menu-supporters', 'ca', 'Cofinançadors ');
INSERT INTO `text` VALUES('project-menu-supporters', 'en', 'Co-financiers');
INSERT INTO `text` VALUES('project-menu-updates', 'ca', 'Novetats');
INSERT INTO `text` VALUES('project-menu-updates', 'en', 'News');
INSERT INTO `text` VALUES('project-messages-answer_it', 'ca', 'Respondre ');
INSERT INTO `text` VALUES('project-messages-answer_it', 'en', 'Respond');
INSERT INTO `text` VALUES('project-messages-send_direct-header', 'ca', 'Envia un missatge a l''impulsor/a del projecte');
INSERT INTO `text` VALUES('project-messages-send_direct-header', 'en', 'Send a message to this project''s promoter');
INSERT INTO `text` VALUES('project-messages-send_message-button', 'ca', 'Enviar');
INSERT INTO `text` VALUES('project-messages-send_message-button', 'en', 'Send');
INSERT INTO `text` VALUES('project-messages-send_message-header', 'ca', 'Escriu el teu missatge');
INSERT INTO `text` VALUES('project-messages-send_message-header', 'en', 'Write your message');
INSERT INTO `text` VALUES('project-messages-send_message-your_answer', 'ca', 'Escriu aquí la teva resposta');
INSERT INTO `text` VALUES('project-messages-send_message-your_answer', 'en', 'Write your answer here');
INSERT INTO `text` VALUES('project-review-confirm_mail-fail', 'ca', 'Hi ha hagut algun error en enviar el missatge de confirmació de recepció');
INSERT INTO `text` VALUES('project-review-confirm_mail-fail', 'en', 'There has been an error when sending the confirmation message');
INSERT INTO `text` VALUES('project-review-confirm_mail-success', 'ca', 'Missatge de confirmació de recepció per a revisió enviat correctament');
INSERT INTO `text` VALUES('project-review-confirm_mail-success', 'en', 'Reviewing confirmation message correctly sent');
INSERT INTO `text` VALUES('project-review-request_mail-fail', 'ca', 'Hi ha hagut algun error en enviar la sol·licitud de revisió');
INSERT INTO `text` VALUES('project-review-request_mail-fail', 'en', 'There has been an error when sending the reviewing invitation');
INSERT INTO `text` VALUES('project-review-request_mail-success', 'ca', 'Missatge de sol·licitud de revisió enviat correctament');
INSERT INTO `text` VALUES('project-review-request_mail-success', 'en', 'Reviewing invitation message correctly sent');
INSERT INTO `text` VALUES('project-rewards-header', 'ca', 'Retorn');
INSERT INTO `text` VALUES('project-rewards-header', 'en', 'Benefits');
INSERT INTO `text` VALUES('project-rewards-individual_reward-limited', 'ca', 'Recompensa limitada');
INSERT INTO `text` VALUES('project-rewards-individual_reward-limited', 'en', 'Limited reward');
INSERT INTO `text` VALUES('project-rewards-individual_reward-title', 'ca', 'Recompenses individuals');
INSERT INTO `text` VALUES('project-rewards-individual_reward-title', 'en', 'Individual rewards');
INSERT INTO `text` VALUES('project-rewards-individual_reward-units_left', 'ca', 'Queden <span class="left">%s</span> unitats');
INSERT INTO `text` VALUES('project-rewards-individual_reward-units_left', 'en', ' <span class="left">%s</span> units left');
INSERT INTO `text` VALUES('project-rewards-social_reward-title', 'ca', 'Retorn col·lectiu ');
INSERT INTO `text` VALUES('project-rewards-social_reward-title', 'en', 'Collective benefits');
INSERT INTO `text` VALUES('project-rewards-supertitle', 'ca', 'Què ofereix a canvi');
INSERT INTO `text` VALUES('project-rewards-supertitle', 'en', 'What is offered in exchange');
INSERT INTO `text` VALUES('project-share-header', 'ca', 'Comparteix aquest projecte');
INSERT INTO `text` VALUES('project-share-header', 'en', 'Share this project');
INSERT INTO `text` VALUES('project-share-pre_header', 'ca', 'Fes saber a la teva xarxa que');
INSERT INTO `text` VALUES('project-share-pre_header', 'en', 'Let your network know that');
INSERT INTO `text` VALUES('project-side-investors-header', 'ca', 'Ja han aportat');
INSERT INTO `text` VALUES('project-side-investors-header', 'en', 'Already contributed');
INSERT INTO `text` VALUES('project-spread-embed_code', 'ca', 'Codi Embed');
INSERT INTO `text` VALUES('project-spread-embed_code', 'en', 'Embedding Code');
INSERT INTO `text` VALUES('project-spread-header', 'ca', 'Difon aquest projecte');
INSERT INTO `text` VALUES('project-spread-header', 'en', 'Spread the word');
INSERT INTO `text` VALUES('project-spread-pre_widget', 'ca', 'Difon aquest projecte');
INSERT INTO `text` VALUES('project-spread-pre_widget', 'en', 'Spread the word');
INSERT INTO `text` VALUES('project-spread-widget', 'ca', 'Widget del projecte');
INSERT INTO `text` VALUES('project-spread-widget', 'en', 'Project widget');
INSERT INTO `text` VALUES('project-spread-widget_legend', 'ca', 'Còpia i enganxa el codi a la teva web o blog i ajuda a difondre aquest projecte');
INSERT INTO `text` VALUES('project-spread-widget_legend', 'en', 'Copy and paste the code in your website or blog to help spread the word about this project');
INSERT INTO `text` VALUES('project-spread-widget_title', 'ca', 'publica el widget del projecte');
INSERT INTO `text` VALUES('project-spread-widget_title', 'en', 'publish the project widget');
INSERT INTO `text` VALUES('project-support-supertitle', 'ca', 'Necessitats econòmiques');
INSERT INTO `text` VALUES('project-support-supertitle', 'en', 'Economic needs');
INSERT INTO `text` VALUES('project-view-categories-title', 'ca', 'Categories');
INSERT INTO `text` VALUES('project-view-categories-title', 'en', 'Categories');
INSERT INTO `text` VALUES('project-view-metter-days', 'ca', 'Queden');
INSERT INTO `text` VALUES('project-view-metter-days', 'en', 'Remaining');
INSERT INTO `text` VALUES('project-view-metter-got', 'ca', 'Obtingut');
INSERT INTO `text` VALUES('project-view-metter-got', 'en', 'Received');
INSERT INTO `text` VALUES('project-view-metter-investment', 'ca', 'Cofinançament ');
INSERT INTO `text` VALUES('project-view-metter-investment', 'en', 'Co-financing');
INSERT INTO `text` VALUES('project-view-metter-investors', 'ca', 'Cofinançadors ');
INSERT INTO `text` VALUES('project-view-metter-investors', 'en', 'Co-financiers');
INSERT INTO `text` VALUES('project-view-metter-minimum', 'ca', 'Mínim');
INSERT INTO `text` VALUES('project-view-metter-minimum', 'en', 'Minimum');
INSERT INTO `text` VALUES('project-view-metter-optimum', 'ca', 'Òptim  ');
INSERT INTO `text` VALUES('project-view-metter-optimum', 'en', 'Optimum');
INSERT INTO `text` VALUES('recover-email-sended', 'ca', 'T''hem enviat un email per restablir la contrasenya del teu compte. Verifica també la carpeta de correu no desitjat o /Spam.');
INSERT INTO `text` VALUES('recover-email-sended', 'en', 'We have sent you an email with instructions for recovering your password. If you don''t get it right away, be sure to check your junk or spam folders.');
INSERT INTO `text` VALUES('recover-request-fail', 'ca', 'No es pot recuperar la contrasenya de cap compte amb aquestes dades');
INSERT INTO `text` VALUES('recover-request-fail', 'en', 'We could not recover the password from any account with that data');
INSERT INTO `text` VALUES('recover-token-incorrect', 'ca', 'El codi de recuperació de contrasenya no és vàlid');
INSERT INTO `text` VALUES('recover-token-incorrect', 'en', 'The code for recovering your password is not valid');
INSERT INTO `text` VALUES('register-confirm_mail-fail', 'ca', 'Hi ha hagut algun error en enviar l''email d''activació de compte. Per favor, contacta''ns a %');
INSERT INTO `text` VALUES('register-confirm_mail-fail', 'en', 'There has been an error when sending the account activation email. Please contact us at %s');
INSERT INTO `text` VALUES('register-confirm_mail-success', 'ca', 'Missatge d''activació de compte enviat. Si no està en la teva bústia de correu, revisa la carpeta de /Spam');
INSERT INTO `text` VALUES('register-confirm_mail-success', 'en', 'Account activation message sent. If it you don''t find it on your Inbox, check your /Spam folder.');
INSERT INTO `text` VALUES('regular-admin_board', 'ca', 'Panell admin');
INSERT INTO `text` VALUES('regular-admin_board', 'en', 'Admin panel');
INSERT INTO `text` VALUES('regular-allsome', 'ca', 'tots/alguns de');
INSERT INTO `text` VALUES('regular-allsome', 'en', 'all/some of');
INSERT INTO `text` VALUES('regular-anonymous', 'ca', 'Anònim');
INSERT INTO `text` VALUES('regular-anonymous', 'en', 'Anonymous');
INSERT INTO `text` VALUES('regular-ask', 'ca', 'Preguntar');
INSERT INTO `text` VALUES('regular-ask', 'en', 'Ask');
INSERT INTO `text` VALUES('regular-banner-metter', 'ca', 'Obtingut-de-queden');
INSERT INTO `text` VALUES('regular-banner-metter', 'en', 'Obtained-of-lasting');
INSERT INTO `text` VALUES('regular-by', 'ca', 'Per:');
INSERT INTO `text` VALUES('regular-by', 'en', 'By:');
INSERT INTO `text` VALUES('regular-collaborate', 'ca', 'Col·labora ');
INSERT INTO `text` VALUES('regular-collaborate', 'en', 'Collaborate');
INSERT INTO `text` VALUES('regular-community', 'ca', 'Comunitat');
INSERT INTO `text` VALUES('regular-create', 'ca', 'Crea un projecte');
INSERT INTO `text` VALUES('regular-create', 'en', 'Create a project');
INSERT INTO `text` VALUES('regular-days', 'ca', 'dies');
INSERT INTO `text` VALUES('regular-days', 'en', 'days');
INSERT INTO `text` VALUES('regular-delete', 'ca', 'Esborrar');
INSERT INTO `text` VALUES('regular-delete', 'en', 'Erase');
INSERT INTO `text` VALUES('regular-discover', 'ca', 'Descobreix projectes');
INSERT INTO `text` VALUES('regular-discover', 'en', 'Discover projects');
INSERT INTO `text` VALUES('regular-edit', 'ca', 'Editar');
INSERT INTO `text` VALUES('regular-edit', 'en', 'Edit');
INSERT INTO `text` VALUES('regular-facebook', 'ca', 'Facebook');
INSERT INTO `text` VALUES('regular-facebook', 'en', 'Facebook');
INSERT INTO `text` VALUES('regular-facebook-url', 'ca', 'http://www.facebook.com/');
INSERT INTO `text` VALUES('regular-facebook-url', 'en', 'http://www.facebook.com/');
INSERT INTO `text` VALUES('regular-fail_mark', 'ca', 'Arxivat... ');
INSERT INTO `text` VALUES('regular-fail_mark', 'en', 'Closed');
INSERT INTO `text` VALUES('regular-faq', 'ca', 'Preguntes freqüents ');
INSERT INTO `text` VALUES('regular-faq', 'en', 'Frequently asked questions');
INSERT INTO `text` VALUES('regular-first', 'ca', 'Primera');
INSERT INTO `text` VALUES('regular-first', 'en', 'First');
INSERT INTO `text` VALUES('regular-footer-contact', 'ca', 'Contacte');
INSERT INTO `text` VALUES('regular-footer-contact', 'de', 'Kontakt');
INSERT INTO `text` VALUES('regular-footer-contact', 'en', 'Contact');
INSERT INTO `text` VALUES('regular-footer-legal', 'ca', 'Termes legals');
INSERT INTO `text` VALUES('regular-footer-legal', 'de', 'Rechtliche Bestimmungen');
INSERT INTO `text` VALUES('regular-footer-legal', 'en', 'Legal terms');
INSERT INTO `text` VALUES('regular-footer-privacy', 'ca', 'Política de privacitat');
INSERT INTO `text` VALUES('regular-footer-privacy', 'de', 'Datenschutz');
INSERT INTO `text` VALUES('regular-footer-privacy', 'en', 'Privacy policy');
INSERT INTO `text` VALUES('regular-footer-terms', 'ca', 'Condicions d´us');
INSERT INTO `text` VALUES('regular-footer-terms', 'de', 'Nutzungsbedingungen');
INSERT INTO `text` VALUES('regular-footer-terms', 'en', 'Terms of service');
INSERT INTO `text` VALUES('regular-google', 'ca', 'Google+');
INSERT INTO `text` VALUES('regular-google', 'en', 'Google+');
INSERT INTO `text` VALUES('regular-google-url', 'ca', 'https://plus.google.com/');
INSERT INTO `text` VALUES('regular-google-url', 'en', 'https://plus.google.com/');
INSERT INTO `text` VALUES('regular-gotit_mark', 'ca', 'Finançat!');
INSERT INTO `text` VALUES('regular-gotit_mark', 'en', 'Funded!');
INSERT INTO `text` VALUES('regular-go_up', 'ca', 'Pujar');
INSERT INTO `text` VALUES('regular-go_up', 'en', 'Upload');
INSERT INTO `text` VALUES('regular-header-about', 'ca', 'Sobre Goteo');
INSERT INTO `text` VALUES('regular-header-about', 'en', 'About Goteo');
INSERT INTO `text` VALUES('regular-header-blog', 'ca', 'Blog');
INSERT INTO `text` VALUES('regular-header-blog', 'en', 'Blog');
INSERT INTO `text` VALUES('regular-header-faq', 'ca', 'FAQ');
INSERT INTO `text` VALUES('regular-header-faq', 'en', 'FAQ');
INSERT INTO `text` VALUES('regular-header-glossary', 'ca', 'Principis per a una economia oberta');
INSERT INTO `text` VALUES('regular-header-glossary', 'en', 'Glossary of terms used in Goteo');
INSERT INTO `text` VALUES('regular-hello', 'ca', 'Hola');
INSERT INTO `text` VALUES('regular-hello', 'en', 'Hello');
INSERT INTO `text` VALUES('regular-home', 'ca', 'Inici');
INSERT INTO `text` VALUES('regular-home', 'en', 'Start');
INSERT INTO `text` VALUES('regular-identica', 'ca', 'Identi.ca');
INSERT INTO `text` VALUES('regular-identica', 'en', 'Identi.ca');
INSERT INTO `text` VALUES('regular-identica-url', 'ca', 'http://identi.ca/');
INSERT INTO `text` VALUES('regular-identica-url', 'en', 'http://identi.ca/');
INSERT INTO `text` VALUES('regular-im', 'ca', 'Sóc');
INSERT INTO `text` VALUES('regular-im', 'en', 'I am');
INSERT INTO `text` VALUES('regular-invest', 'ca', 'Aportar');
INSERT INTO `text` VALUES('regular-invest', 'en', 'Contribute');
INSERT INTO `text` VALUES('regular-investing', 'ca', 'Aportant');
INSERT INTO `text` VALUES('regular-investing', 'en', 'Contributing');
INSERT INTO `text` VALUES('regular-invest_it', 'ca', 'Cofinança el projecte');
INSERT INTO `text` VALUES('regular-invest_it', 'en', 'Co-finance this project');
INSERT INTO `text` VALUES('regular-keepiton', 'ca', 'Encara pots seguir aportant');
INSERT INTO `text` VALUES('regular-keepiton_mark', 'ca', 'Mínim assolit');
INSERT INTO `text` VALUES('regular-keepiton_mark', 'en', 'Minimum achieved');
INSERT INTO `text` VALUES('regular-last', 'ca', 'Darrera');
INSERT INTO `text` VALUES('regular-last', 'en', 'Last');
INSERT INTO `text` VALUES('regular-license', 'ca', 'Llicència');
INSERT INTO `text` VALUES('regular-license', 'en', 'License');
INSERT INTO `text` VALUES('regular-linkedin', 'ca', 'LinkedIn');
INSERT INTO `text` VALUES('regular-linkedin', 'en', 'LinkedIn');
INSERT INTO `text` VALUES('regular-linkedin-url', 'ca', 'http://es.linkedin.com/in/');
INSERT INTO `text` VALUES('regular-linkedin-url', 'en', 'http://es.linkedin.com/in/');
INSERT INTO `text` VALUES('regular-login', 'ca', 'Accedeix');
INSERT INTO `text` VALUES('regular-login', 'en', 'Log in');
INSERT INTO `text` VALUES('regular-logout', 'ca', 'Tancar sessió');
INSERT INTO `text` VALUES('regular-logout', 'en', 'Log out');
INSERT INTO `text` VALUES('regular-looks_for', 'ca', 'cerca:');
INSERT INTO `text` VALUES('regular-looks_for', 'en', 'looking for:');
INSERT INTO `text` VALUES('regular-main-header', 'ca', 'Goteo.org');
INSERT INTO `text` VALUES('regular-main-header', 'en', 'Goteo.org');
INSERT INTO `text` VALUES('regular-mandatory', 'ca', 'Camp obligatori!');
INSERT INTO `text` VALUES('regular-mandatory', 'en', 'Required field!');
INSERT INTO `text` VALUES('regular-media_legend', 'ca', 'Llegenda');
INSERT INTO `text` VALUES('regular-media_legend', 'en', 'Key');
INSERT INTO `text` VALUES('regular-menu', 'ca', 'Menú');
INSERT INTO `text` VALUES('regular-menu', 'en', 'Menu');
INSERT INTO `text` VALUES('regular-message_fail', 'ca', 'Hi ha hagut algun error en enviar el missatge');
INSERT INTO `text` VALUES('regular-message_fail', 'en', 'There was an error sending the message');
INSERT INTO `text` VALUES('regular-message_success', 'ca', 'Missatge enviat correctament');
INSERT INTO `text` VALUES('regular-message_success', 'en', 'Message sent correctly');
INSERT INTO `text` VALUES('regular-months', 'ca', 'mesos ');
INSERT INTO `text` VALUES('regular-months', 'en', 'months');
INSERT INTO `text` VALUES('regular-more_info', 'ca', '+ info');
INSERT INTO `text` VALUES('regular-more_info', 'en', 'More info');
INSERT INTO `text` VALUES('regular-news', 'ca', 'Notícies: ');
INSERT INTO `text` VALUES('regular-news', 'en', 'News');
INSERT INTO `text` VALUES('regular-new_project', 'ca', 'Projecte nou');
INSERT INTO `text` VALUES('regular-new_project', 'en', 'New project');
INSERT INTO `text` VALUES('regular-no', 'ca', 'No');
INSERT INTO `text` VALUES('regular-no', 'en', 'No');
INSERT INTO `text` VALUES('regular-onrun_mark', 'ca', 'En marxa!');
INSERT INTO `text` VALUES('regular-onrun_mark', 'en', 'In progress!');
INSERT INTO `text` VALUES('regular-preview', 'ca', 'Previsualitzar ');
INSERT INTO `text` VALUES('regular-preview', 'en', 'Preview');
INSERT INTO `text` VALUES('regular-projects', 'ca', 'projectes');
INSERT INTO `text` VALUES('regular-projects', 'en', 'projects');
INSERT INTO `text` VALUES('regular-published_no', 'ca', 'Esborrany');
INSERT INTO `text` VALUES('regular-published_no', 'en', 'Draft');
INSERT INTO `text` VALUES('regular-published_yes', 'ca', 'Publicat');
INSERT INTO `text` VALUES('regular-published_yes', 'en', 'Published');
INSERT INTO `text` VALUES('regular-read_more', 'ca', 'Llegir més');
INSERT INTO `text` VALUES('regular-read_more', 'en', 'Read more');
INSERT INTO `text` VALUES('regular-review_board', 'ca', 'Panell revisor');
INSERT INTO `text` VALUES('regular-review_board', 'en', 'Review panel');
INSERT INTO `text` VALUES('regular-round', 'ca', 'ª ronda');
INSERT INTO `text` VALUES('regular-round', 'en', 'Round');
INSERT INTO `text` VALUES('regular-save', 'ca', 'Desar');
INSERT INTO `text` VALUES('regular-save', 'en', 'Save');
INSERT INTO `text` VALUES('regular-search', 'ca', 'Cercar');
INSERT INTO `text` VALUES('regular-search', 'en', 'Search');
INSERT INTO `text` VALUES('regular-see_all', 'ca', 'Veure tots');
INSERT INTO `text` VALUES('regular-see_all', 'en', 'See all');
INSERT INTO `text` VALUES('regular-see_blog', 'ca', 'Blog');
INSERT INTO `text` VALUES('regular-see_blog', 'en', 'Blog');
INSERT INTO `text` VALUES('regular-see_details', 'ca', 'Veure detalls');
INSERT INTO `text` VALUES('regular-see_details', 'en', 'See details');
INSERT INTO `text` VALUES('regular-see_more', 'ca', 'Veure més');
INSERT INTO `text` VALUES('regular-see_more', 'en', 'See more');
INSERT INTO `text` VALUES('regular-send_message', 'ca', 'Enviar missatge');
INSERT INTO `text` VALUES('regular-send_message', 'en', 'Send message');
INSERT INTO `text` VALUES('regular-share-facebook', 'ca', 'Goteo al Facebook');
INSERT INTO `text` VALUES('regular-share-facebook', 'en', 'Goteo on Facebook');
INSERT INTO `text` VALUES('regular-share-rss', 'ca', 'RSS/BLOG');
INSERT INTO `text` VALUES('regular-share-rss', 'en', 'RSS/Blog');
INSERT INTO `text` VALUES('regular-share-twitter', 'ca', 'Segueix-nos a Twitter');
INSERT INTO `text` VALUES('regular-share-twitter', 'en', 'Follow us on Twitter');
INSERT INTO `text` VALUES('regular-share_this', 'ca', 'Compartir a:');
INSERT INTO `text` VALUES('regular-share_this', 'en', 'Share on:');
INSERT INTO `text` VALUES('regular-sorry', 'ca', 'Ho sentim');
INSERT INTO `text` VALUES('regular-sorry', 'en', 'We are sorry');
INSERT INTO `text` VALUES('regular-success_mark', 'ca', 'Reeixit!');
INSERT INTO `text` VALUES('regular-success_mark', 'en', 'Successful!');
INSERT INTO `text` VALUES('regular-thanks', 'ca', 'Gràcies');
INSERT INTO `text` VALUES('regular-thanks', 'en', 'Thank you');
INSERT INTO `text` VALUES('regular-total', 'ca', 'Total');
INSERT INTO `text` VALUES('regular-total', 'en', 'Total');
INSERT INTO `text` VALUES('regular-translate_board', 'ca', 'Panell traductor');
INSERT INTO `text` VALUES('regular-translate_board', 'en', 'Translation panel');
INSERT INTO `text` VALUES('regular-twitter', 'ca', 'Twitter');
INSERT INTO `text` VALUES('regular-twitter', 'en', 'Twitter');
INSERT INTO `text` VALUES('regular-twitter-url', 'ca', 'http://twitter.com/#!/');
INSERT INTO `text` VALUES('regular-twitter-url', 'en', 'http://twitter.com/#!/');
INSERT INTO `text` VALUES('regular-view_project', 'ca', 'Projecte');
INSERT INTO `text` VALUES('regular-view_project', 'en', 'See project');
INSERT INTO `text` VALUES('regular-weeks', 'ca', 'setmanes');
INSERT INTO `text` VALUES('regular-weeks', 'en', 'weeks');
INSERT INTO `text` VALUES('regular-yes', 'ca', 'Sí');
INSERT INTO `text` VALUES('regular-yes', 'en', 'Yes');
INSERT INTO `text` VALUES('review-ajax-alert', 'ca', 'Els criteris i els camps d''avaluació / millores es desen automàticament en modificar-se');
INSERT INTO `text` VALUES('review-ajax-alert', 'en', 'Evaluation / feedback fields will be automatically updated when changed');
INSERT INTO `text` VALUES('review-closed-alert', 'ca', 'Has donat per acabada aquesta revisió, no pots fer més canvis');
INSERT INTO `text` VALUES('review-closed-alert', 'en', 'You marked the review as finished, no more changes can be done.');
INSERT INTO `text` VALUES('rewards-field-individual_reward-amount', 'ca', 'Import finançat');
INSERT INTO `text` VALUES('rewards-field-individual_reward-amount', 'en', 'Amount financed');
INSERT INTO `text` VALUES('rewards-field-individual_reward-description', 'ca', 'Descripció');
INSERT INTO `text` VALUES('rewards-field-individual_reward-description', 'en', 'Description');
INSERT INTO `text` VALUES('rewards-field-individual_reward-other', 'ca', 'Especifica el tipus de recompensa');
INSERT INTO `text` VALUES('rewards-field-individual_reward-other', 'en', 'Specify the type of reward');
INSERT INTO `text` VALUES('rewards-field-individual_reward-reward', 'ca', 'Recompensa');
INSERT INTO `text` VALUES('rewards-field-individual_reward-reward', 'en', 'Reward');
INSERT INTO `text` VALUES('rewards-field-individual_reward-type', 'ca', 'Tipus de recompensa');
INSERT INTO `text` VALUES('rewards-field-individual_reward-type', 'en', 'Type of reward');
INSERT INTO `text` VALUES('rewards-field-individual_reward-units', 'ca', 'Unitats');
INSERT INTO `text` VALUES('rewards-field-individual_reward-units', 'en', 'Units');
INSERT INTO `text` VALUES('rewards-field-social_reward-description', 'ca', 'Descripció');
INSERT INTO `text` VALUES('rewards-field-social_reward-description', 'en', 'Description');
INSERT INTO `text` VALUES('rewards-field-social_reward-license', 'ca', 'Opcions de llicència');
INSERT INTO `text` VALUES('rewards-field-social_reward-license', 'en', 'Licensing options');
INSERT INTO `text` VALUES('rewards-field-social_reward-other', 'ca', 'Especifica el tipus de retorn');
INSERT INTO `text` VALUES('rewards-field-social_reward-other', 'en', 'Specify the type of benefit');
INSERT INTO `text` VALUES('rewards-field-social_reward-reward', 'ca', 'Retorn');
INSERT INTO `text` VALUES('rewards-field-social_reward-reward', 'en', 'Benefits');
INSERT INTO `text` VALUES('rewards-field-social_reward-type', 'ca', 'Tipus de retorn');
INSERT INTO `text` VALUES('rewards-field-social_reward-type', 'en', 'Type of benefit');
INSERT INTO `text` VALUES('rewards-fields-individual_reward-title', 'ca', 'Recompenses individuals');
INSERT INTO `text` VALUES('rewards-fields-individual_reward-title', 'en', 'Individual rewards');
INSERT INTO `text` VALUES('rewards-fields-social_reward-title', 'ca', 'Retorns col·lectius');
INSERT INTO `text` VALUES('rewards-fields-social_reward-title', 'en', 'Collective benefits');
INSERT INTO `text` VALUES('rewards-main-header', 'ca', 'Retorns i recompenses');
INSERT INTO `text` VALUES('rewards-main-header', 'en', 'Benefits and rewards');
INSERT INTO `text` VALUES('social-account-facebook', 'ca', 'http://www.facebook.com/pages/Goteo/268491113192109');
INSERT INTO `text` VALUES('social-account-facebook', 'en', 'http://www.facebook.com/pages/Goteo/268491113192109');
INSERT INTO `text` VALUES('social-account-google', 'ca', 'https://plus.google.com/b/116559557256583965659/');
INSERT INTO `text` VALUES('social-account-google', 'en', 'https://plus.google.com/b/116559557256583965659/');
INSERT INTO `text` VALUES('social-account-identica', 'ca', 'http://identi.ca/goteofunding');
INSERT INTO `text` VALUES('social-account-identica', 'en', 'http://identi.ca/goteofunding');
INSERT INTO `text` VALUES('social-account-linkedin', 'en', 'Goteo page at Linkedin');
INSERT INTO `text` VALUES('social-account-twitter', 'ca', 'http://twitter.com/goteofunding');
INSERT INTO `text` VALUES('social-account-twitter', 'en', 'http://twitter.com/goteofunding');
INSERT INTO `text` VALUES('step-1', 'ca', 'Perfil');
INSERT INTO `text` VALUES('step-1', 'en', 'Profile');
INSERT INTO `text` VALUES('step-2', 'ca', 'Promotor/a');
INSERT INTO `text` VALUES('step-2', 'en', 'Promoter');
INSERT INTO `text` VALUES('step-3', 'ca', 'Descripció');
INSERT INTO `text` VALUES('step-3', 'en', 'Description');
INSERT INTO `text` VALUES('step-4', 'ca', 'Costos');
INSERT INTO `text` VALUES('step-4', 'en', 'Expenses');
INSERT INTO `text` VALUES('step-5', 'ca', 'Retorn');
INSERT INTO `text` VALUES('step-5', 'en', 'Benefits');
INSERT INTO `text` VALUES('step-6', 'ca', 'Col·laboracions');
INSERT INTO `text` VALUES('step-6', 'en', 'Collaborations');
INSERT INTO `text` VALUES('step-7', 'ca', 'Previsualització');
INSERT INTO `text` VALUES('step-7', 'en', 'Preview');
INSERT INTO `text` VALUES('step-costs', 'ca', 'Pas 4: Projecte / Costos');
INSERT INTO `text` VALUES('step-costs', 'en', 'Step 4: Project/Expenses');
INSERT INTO `text` VALUES('step-overview', 'ca', 'Pas 3: Descripció del projecte');
INSERT INTO `text` VALUES('step-overview', 'en', 'Step 3: Project description');
INSERT INTO `text` VALUES('step-preview', 'ca', 'Projecte / Previsualització');
INSERT INTO `text` VALUES('step-preview', 'en', 'Project / Preview');
INSERT INTO `text` VALUES('step-rewards', 'ca', 'Pas 5: Projecte / Retorns');
INSERT INTO `text` VALUES('step-rewards', 'en', 'Step 5: Project / Benefits');
INSERT INTO `text` VALUES('step-supports', 'ca', 'Pas 6: Projecte / Col·laboracions');
INSERT INTO `text` VALUES('step-supports', 'en', 'Step 6: Project / Collaborations');
INSERT INTO `text` VALUES('step-userPersonal', 'ca', 'Pas 2: Dades personals');
INSERT INTO `text` VALUES('step-userPersonal', 'en', 'Step 2: Personal information');
INSERT INTO `text` VALUES('step-userProfile', 'ca', 'Pas 1: Usuari / Perfil');
INSERT INTO `text` VALUES('step-userProfile', 'en', 'Step 1: User / Profile');
INSERT INTO `text` VALUES('supports-field-description', 'ca', 'Descripció');
INSERT INTO `text` VALUES('supports-field-description', 'en', 'Description');
INSERT INTO `text` VALUES('supports-field-support', 'ca', 'Resum');
INSERT INTO `text` VALUES('supports-field-support', 'en', 'Summary');
INSERT INTO `text` VALUES('supports-field-type', 'ca', 'Tipus d''ajut');
INSERT INTO `text` VALUES('supports-field-type', 'en', 'Type of help');
INSERT INTO `text` VALUES('supports-fields-support-title', 'ca', 'Col·laboracions');
INSERT INTO `text` VALUES('supports-fields-support-title', 'en', 'Collaborations');
INSERT INTO `text` VALUES('supports-main-header', 'ca', 'Sol·licitud de col·laboracions');
INSERT INTO `text` VALUES('supports-main-header', 'en', 'Request for collaborations');
INSERT INTO `text` VALUES('tooltip-dashboard-user-access_data', 'ca', 'Aquestes són les teves dades actuals d''accés. L''única cosa que no es pot canviar és el login o nom d''usuari.');
INSERT INTO `text` VALUES('tooltip-dashboard-user-access_data', 'en', 'This is your current log in data. The only thing you cannot change is your username.');
INSERT INTO `text` VALUES('tooltip-dashboard-user-change_email', 'ca', 'Des d''aquí pots canviar l''adreça de correu electrònic on reps els missatges de Goteo.');
INSERT INTO `text` VALUES('tooltip-dashboard-user-change_email', 'en', 'Change the email address at which you receive messages from Goteo.');
INSERT INTO `text` VALUES('tooltip-dashboard-user-change_password', 'ca', 'Des d''aquí pots canviar la contrasenya amb que accedeixes a Goteo.');
INSERT INTO `text` VALUES('tooltip-dashboard-user-change_password', 'en', 'Change your password that you use to log in to Goteo.');
INSERT INTO `text` VALUES('tooltip-dashboard-user-confirm_email', 'ca', 'Confirma la nova adreça de correu electrònic on vols rebre els missatges de Goteo.');
INSERT INTO `text` VALUES('tooltip-dashboard-user-confirm_email', 'en', 'Confirm the new email address where you wish to receive messages from Goteo.');
INSERT INTO `text` VALUES('tooltip-dashboard-user-confirm_password', 'ca', 'Confirma la nova contrasenya amb que vols accedir a Goteo.');
INSERT INTO `text` VALUES('tooltip-dashboard-user-confirm_password', 'en', 'Confirm the new password with which you want to log in to Goteo.');
INSERT INTO `text` VALUES('tooltip-dashboard-user-new_email', 'ca', 'Indica la nova adreça de correu electrònic on vols rebre els missatges de Goteo.');
INSERT INTO `text` VALUES('tooltip-dashboard-user-new_email', 'en', 'Specify the new email address at which you wish to receive messages from Goteo.');
INSERT INTO `text` VALUES('tooltip-dashboard-user-new_password', 'ca', 'Escriu la nova contrasenya amb que vols accedir a Goteo.');
INSERT INTO `text` VALUES('tooltip-dashboard-user-new_password', 'en', 'Write the new password that you want to use to log in to Goteo');
INSERT INTO `text` VALUES('tooltip-dashboard-user-user_password', 'ca', 'Escriu la contrasenya actual amb que accedeixes a Goteo.');
INSERT INTO `text` VALUES('tooltip-dashboard-user-user_password', 'en', 'Write the current password that you use to log in to Goteo');
INSERT INTO `text` VALUES('tooltip-project-about', 'ca', 'Descriu breument el projecte de manera conceptual, tècnica o pràctica. Per exemple detallant les seves característiques de funcionament, o en quines parts consistirà. Pensa com serà una vegada acabat i tot el que la gent podrà fer amb ell.');
INSERT INTO `text` VALUES('tooltip-project-about', 'en', 'Briefly describe the project in conceptual, technical, or practical terms. For example, by detailing how it works or what it is made up of. Think about what it will be like when it''s done, and what people will be able to do with it.');
INSERT INTO `text` VALUES('tooltip-project-category', 'ca', 'Selecciona tantes categories com creguis necessari per descriure el projecte, basant-te en tot el que has descrit amunt. Mitjançant aquestes paraules clau ho podrem fer arribar a diferents usuaris de Goteo.');
INSERT INTO `text` VALUES('tooltip-project-category', 'en', 'Choose as many categories as you deem necessary in order to describe the project, based on everything you''ve written above. Your choices will help us get your project out to interested Goteo users.');
INSERT INTO `text` VALUES('tooltip-project-comment', 'ca', 'Tens dubtes o comentaris per a que els llegeixi l''administrador de Goteo? Aquest és el lloc on explicar alguna part del que has escrit de la qual no estiguis segur, per proposar millores, etc.');
INSERT INTO `text` VALUES('tooltip-project-comment', 'en', 'Do you have any doubts or comments for the Goteo administrator? This is the place to mention some part of what you have written that you''re not sure about, to suggest improvements, etc.');
INSERT INTO `text` VALUES('tooltip-project-contract_birthdate', 'ca', 'Indica la data del teu naixement. No es farà pública en cap cas, ens interessa per temes estadístics.');
INSERT INTO `text` VALUES('tooltip-project-contract_birthdate', 'en', 'Enter your date of birth. It will never be published, but rather only used for statistical purposes.');
INSERT INTO `text` VALUES('tooltip-project-contract_data', 'ca', 'Ja sigui com a persona física o bé jurídica, és necessari que algú figuri com a promotor/a de el projecte, i també per a la interlocució amb l''equip de Goteo. No ha de coincidir necessàriament amb el perfil d''usuari de l''apartat anterior.');
INSERT INTO `text` VALUES('tooltip-project-contract_data', 'en', 'Whether it''s a person or legal entity, someone has to be named as the project promoter, as well as the liaison with the Goteo team. It doesn''t have to be the same as the user profile from the previous field.');
INSERT INTO `text` VALUES('tooltip-project-contract_email', 'ca', 'Adreça de correu electrònic principal associada al projecte. Aquí rebràs les notificacions i missatges de l''equip de Goteo en relació al projecte proposat.');
INSERT INTO `text` VALUES('tooltip-project-contract_email', 'en', 'Main email address associated with the project.You will receive all the notifications and messages from the Goteo team relating to your project proposal at this address.');
INSERT INTO `text` VALUES('tooltip-project-contract_entity', 'ca', 'Selecciona "Persona física" en cas que tu siguis el/la promotor/a del projecte i et representis a tu mateix/a. Si el promotor és un grup és necessari per triar la segona opció que tingui un CIF propi, en aquest cas tria "Persona jurídica". ');
INSERT INTO `text` VALUES('tooltip-project-contract_entity', 'en', 'Choose “Person” if you are the project promoter and are representing yourself. If the promoter is a group you have to choose the second option, “Legal Entity”, in which case, you''ll also have to enter a CIF.');
INSERT INTO `text` VALUES('tooltip-project-contract_name', 'ca', 'Han de ser el teu nom i cognoms reals. Tingues en compte que figuraràs com a responsable del projecte.');
INSERT INTO `text` VALUES('tooltip-project-contract_name', 'en', 'Please use your real first name and surname. Note that you will be listed as the project leader.');
INSERT INTO `text` VALUES('tooltip-project-contract_nif', 'ca', 'El teu número de NIF o NIE amb xifres i lletres.');
INSERT INTO `text` VALUES('tooltip-project-contract_nif', 'en', 'Your NIF, NIE or VAT number with digits and the letter');
INSERT INTO `text` VALUES('tooltip-project-cost-amount', 'ca', 'Especifica l''import en euros del que consideres que implica aquest cost. No facis servir punts per a les xifres de milers ok?');
INSERT INTO `text` VALUES('tooltip-project-cost-amount', 'en', 'Specify the amount in euros for each expense. Don''t use points or commas to mark the thousands, OK?');
INSERT INTO `text` VALUES('tooltip-project-cost-cost', 'ca', 'Introdueix un títol el més descriptiu possible d''aquest cost.');
INSERT INTO `text` VALUES('tooltip-project-cost-cost', 'en', 'Enter the most descriptive title possible for this expense');
INSERT INTO `text` VALUES('tooltip-project-cost-dates', 'ca', 'Indica entre quines dates calcules que es durà a terme aquesta tasca o cobrir aquest cost. Planifica-ho començant no abans de dos mesos a partir d''ara, doncs cal considerar el termini per revisar la proposta, publicar-la si és seleccionada i els 40 dies del primer finançament.');
INSERT INTO `text` VALUES('tooltip-project-cost-dates', 'en', 'Give a time frame for when the task will be completed or the expense will be covered. Plan it by starting at least two months from today, since you have to take into account the time needed for revising the proposal, publicizing it if it is chosen, and the 40 days from the first financing. Don''t include pieces that were developed previously even though these are good to explain in your project''s description. We only want things on the schedule that still need to be done and for which you''re requesting co-financing.');
INSERT INTO `text` VALUES('tooltip-project-cost-description', 'ca', 'Explica breument en què consisteix aquest cost.');
INSERT INTO `text` VALUES('tooltip-project-cost-description', 'en', 'Briefly explain what this expense consists of.');
INSERT INTO `text` VALUES('tooltip-project-cost-required', 'ca', 'Aquest punt és molt important: en cada cost que introdueixis has de marcar si és imprescindible o bé addicional. Tots els costos marcats com a imprescindibles es sumaran donant el valor de l''import de finançament mínim per al projecte. La suma dels costos addicionals, en canvi, es podrà obtenir durant la segona ronda de finançament, després d''haver obtingut els fons imprescindibles.');
INSERT INTO `text` VALUES('tooltip-project-cost-required', 'en', 'This section is very important: for each expense that you enter, you have to indicate if it is absolutely necessary or just supplemental. All of the expenses that are marked as necessary will be added to get the full amount of minimum financing required for the project. The sum of the supplemental expenses, in contrast, can be requested during the second round of financing, after having gotten the “necessary” funds.');
INSERT INTO `text` VALUES('tooltip-project-cost-type', 'ca', 'Aquí has d''especificar el tipus de cost: vinculat a una tasca (quelcom que requereix l''habilitat o coneixements d''algú), l''obtenció de material (consumibles, matèries primeres) o bé infraestructura (espais, equips, mobiliari).');
INSERT INTO `text` VALUES('tooltip-project-cost-type', 'en', 'Here you should specify the type of expense: related to a task (something that requires someone''s skill or knowledge), obtaining material (consumables, raw materials) or infrastructure (spaces, equipment, furniture) ');
INSERT INTO `text` VALUES('tooltip-project-cost-type-material', 'ca', 'Materials necessaris per al projecte com ara eines, papereria, equips informàtics, etc.');
INSERT INTO `text` VALUES('tooltip-project-cost-type-material', 'en', 'Necessary materials for the project, like tools, office material, computer equipment, etc.');
INSERT INTO `text` VALUES('tooltip-project-cost-type-structure', 'ca', 'Inversió en costos vinculats a un local, mitjà de transport o altres infraestructures bàsiques per dur a terme el projecte. ');
INSERT INTO `text` VALUES('tooltip-project-cost-type-structure', 'en', 'Investment in costs that are linked to an office space or store front, mode of transport, or other basic infrastructure needed to bring the project to fruition.');
INSERT INTO `text` VALUES('tooltip-project-cost-type-task', 'ca', 'Tasques on invertir coneixements i/o habilitats per desenvolupar alguna part del projecte.');
INSERT INTO `text` VALUES('tooltip-project-cost-type-task', 'en', 'Tasks in which knowledge and skills can be invested in order to develop a part of the project.');
INSERT INTO `text` VALUES('tooltip-project-costs', 'ca', 'Com més precisió en el desglossament millor valorarà Goteo la informació general del projecte. ');
INSERT INTO `text` VALUES('tooltip-project-costs', 'en', 'The more precisely you break down the expenses, the better Goteo will be able to evaluate the general information about your project.');
INSERT INTO `text` VALUES('tooltip-project-currently', 'ca', 'Indica a quina fase es troba el projecte actualment respecte al seu procés de creació o execució.');
INSERT INTO `text` VALUES('tooltip-project-currently', 'en', 'Which phase is the project currently in with respect to the process of creation or execution?');
INSERT INTO `text` VALUES('tooltip-project-description', 'ca', 'Descriu el projecte amb un mínim de 80 paraules (amb menys marcarà error). Explica-ho de manera que sigui fàcil d''entendre per a qualsevol persona. Intenta donar-li un enfocament atractiu i social, resumint els seus punts forts, com ara què ho fa únic, innovador o especial.');
INSERT INTO `text` VALUES('tooltip-project-description', 'en', 'Describe the project in at least 80 words (any less will generate an error). Explain it so that it''s easy to understand for a lay person. Try to give it an attractive, social focus, summarizing its strong points that make it unique, innovative or special. ');
INSERT INTO `text` VALUES('tooltip-project-entity_cif', 'ca', 'Escriu el CIF (lletra + número) de l''organització.');
INSERT INTO `text` VALUES('tooltip-project-entity_cif', 'en', 'Enter the organization''s business number (CIF, letter plus the number)');
INSERT INTO `text` VALUES('tooltip-project-entity_name', 'ca', 'Escriu el nom de l''organització tal com apareix al seu CIF.');
INSERT INTO `text` VALUES('tooltip-project-entity_name', 'en', 'Write the name of the organization exactly as it appears in its CIF.');
INSERT INTO `text` VALUES('tooltip-project-entity_office', 'ca', 'Escriu el càrrec amb que representes l''organització (secretari/a, president/a, vocal...)');
INSERT INTO `text` VALUES('tooltip-project-entity_office', 'en', 'Enter the position with which you represent the organization (secretary, president, ...)');
INSERT INTO `text` VALUES('tooltip-project-goal', 'ca', 'Enumera les metes principals del projecte, a curt i llarg termini, en tots els aspectes que consideris important destacar. Es tracta d''una altra oportunitat per contactar i aconseguir el suport de gent que simpatitzi amb aquests objectius.');
INSERT INTO `text` VALUES('tooltip-project-goal', 'en', 'List the project''s principal goals, in the long and short term, in all aspects that you consider important. This is another opportunity to contact with and get support from the people who sympathize with those objectives.');
INSERT INTO `text` VALUES('tooltip-project-image', 'ca', 'Poden ser esquemes, captures de pantalla, fotografies, il·lustracions, storyboards, etc. (la seva llicència d''autoria ha de ser compatible amb la que seleccions a l''apartat 5). Et recomanem que siguin diverses i de bona resolució. Pots pujar tantes com vulguis!');
INSERT INTO `text` VALUES('tooltip-project-image', 'en', 'These might be outlines, screenshots, photographs, illustrations, storyboards, etc. (their authoring license should be compatible with what you choose in section 5). Use a variety, and at a decent resolution. You can upload as many as you like.');
INSERT INTO `text` VALUES('tooltip-project-image_upload', 'ca', 'ESBORRAR');
INSERT INTO `text` VALUES('tooltip-project-image_upload', 'en', 'ERASE');
INSERT INTO `text` VALUES('tooltip-project-individual_reward-amount', 'ca', 'Import a canvi del que es pot obtenir aquest tipus de recompensa. ');
INSERT INTO `text` VALUES('tooltip-project-individual_reward-amount', 'en', 'Amount for which one can receive this type of reward.');
INSERT INTO `text` VALUES('tooltip-project-individual_reward-description', 'ca', 'Explica breument en què consistirà la recompensa pels qui cofinancin amb aquest import el projecte.');
INSERT INTO `text` VALUES('tooltip-project-individual_reward-description', 'en', 'Briefly explain what the reward will consist of for those who co-finance the project at this level.');
INSERT INTO `text` VALUES('tooltip-project-individual_reward-icon-other', 'ca', 'Especifica breument en què consistirà aquest altre tipus de recompensa individual.');
INSERT INTO `text` VALUES('tooltip-project-individual_reward-icon-other', 'en', 'Briefly describe this other type of individual reward');
INSERT INTO `text` VALUES('tooltip-project-individual_reward-reward', 'ca', 'Intenta que el títol sigui el més descriptiu possible. Recorda que pots afegir més recompenses a continuació.');
INSERT INTO `text` VALUES('tooltip-project-individual_reward-reward', 'en', 'Try to make the title as descriptive as possible. Remember that you can add more rewards later.');
INSERT INTO `text` VALUES('tooltip-project-individual_reward-type', 'ca', 'Selecciona el tipus de recompensa que el projecte pot oferir a la gent que aporta aquesta quantitat.');
INSERT INTO `text` VALUES('tooltip-project-individual_reward-type', 'en', 'Select the type of reward that the project can offer to people who contribute this amount.');
INSERT INTO `text` VALUES('tooltip-project-individual_reward-units', 'ca', 'Quantitat limitada d''ítems que es poden oferir individualitzadament a canvi d''aquest import. Calcula que la suma total de totes les recompenses individuals del projecte s''apropin al pressupost mínim de finançament que has establert. També la possibilitat d''incorporar les recompenses prèvies a mesura que augmenti l''import, pots començar dient "Tot lo anterior més..." ');
INSERT INTO `text` VALUES('tooltip-project-individual_reward-units', 'en', 'Limited quantity of items that can be offered individually in exchange for this amount. Adjust the amounts so that the total sum of all of the individual rewards for the project is close to the minimum financing budget that you have created. Don''t forget that you can also incorporate the previous rewards as the amount increases, by starting with “All of the preceding, plus...”');
INSERT INTO `text` VALUES('tooltip-project-individual_rewards', 'ca', 'Aquí cal especificar la recompensa per qui recolzi el projecte, vinculada a una quantitat de diners concreta. Tria bé el que ofereixes, intenta que siguin productes/serveis atractius o enginyosos però que no generin despeses extra de producció. Si no hi ha més remei que tenir aquestes despeses extra, calcula el que costa produir aquesta recompensa (temps, materials, enviaments, etc) i ofereix un nombre limitat. Pensa que hauràs de complir amb tots aquests compromisos al final de la producció del projecte. ');
INSERT INTO `text` VALUES('tooltip-project-individual_rewards', 'en', 'Specify the reward that a supporter will receive, linked to a particular amount of money. Carefully choose what you''re offering. Try to offer attractive or ingenious products or services that don''t generate extra production expenses. If you must incur extra expenses, calculate the amount that it will cost to produce this reward (including time, materials, shipping, etc.) and offer a limited quantity. Remember that you will have to follow through with these obligations when the project is produced.');
INSERT INTO `text` VALUES('tooltip-project-keywords', 'ca', 'A mesura que introdueixis text el sistema et suggerirà paraules clau que ja han escrit altres usuaris. Aquestes categories ajuden a vincular el teu projecte amb persones afins.');
INSERT INTO `text` VALUES('tooltip-project-keywords', 'en', 'As you enter text, the system will suggest key words that other users have written. These categories help to link your project with other similar ones.');
INSERT INTO `text` VALUES('tooltip-project-lang', 'ca', 'Indica en quina llengua omples el formulari del projecte.');
INSERT INTO `text` VALUES('tooltip-project-lang', 'en', 'Indicate the language in which you''re filling out the project form.');
INSERT INTO `text` VALUES('tooltip-project-main_address', 'ca', 'Adreça fiscal de la persona o organització (segons pertoqui).');
INSERT INTO `text` VALUES('tooltip-project-main_address', 'en', 'Legal address of the person or organization (whichever applies)');
INSERT INTO `text` VALUES('tooltip-project-media', 'ca', 'Còpia i enganxa l''adreça URL d''un vídeo de presentació del projecte, publicat prèviament a Youtube o Vimeo. Aquesta part és fonamental per atreure l''atenció de potencials cofinançadors i col·laboradors, i com més original sigui millor. Et recomanem que tingui una durada d''entre 2 i 4 minuts. ');
INSERT INTO `text` VALUES('tooltip-project-media', 'en', 'Copy and paste the URL address of the project''s presentation video, previously published on YouTube or Vimeo. This is an essential step in attracting the attention of potential co-financiers and collaborators, and the more original it is, the better. Videos should last between 2 and 4 minutes.');
INSERT INTO `text` VALUES('tooltip-project-motivation', 'ca', 'Explica quins motius o circumstàncies t''han portat a idear el projecte, així com les comunitats o usuaris a qui va destinat. T''ajudarà a connectar amb persones mogudes per aquest mateix tipus d''interessos, problemàtiques o gustos.');
INSERT INTO `text` VALUES('tooltip-project-motivation', 'en', 'Explain the reasons or circumstances that led you do think of this project, as well as the communities or users to whom it is geared. It will help you connect with people who are moving in this same circle of interests, problems, and tastes.');
INSERT INTO `text` VALUES('tooltip-project-name', 'ca', 'Escull un nom per al projecte (ni massa curt, ni massa llarg :) que permeti fer-se una idea mínima de per a què serveix o en què consisteix. Pensa que apareixerà a molts llocs de la web!');
INSERT INTO `text` VALUES('tooltip-project-name', 'en', 'Enter a title for your project. The shorter the better, you can add description in the following field.');
INSERT INTO `text` VALUES('tooltip-project-nsupport', 'en', 'Advice on filling out a new collaboration proposal');
INSERT INTO `text` VALUES('tooltip-project-phone', 'ca', 'Número de telèfon mòbil o fix, amb el seu prefix de marcat.');
INSERT INTO `text` VALUES('tooltip-project-phone', 'en', 'Telephone number (mobile or landline) with prefix');
INSERT INTO `text` VALUES('tooltip-project-post_address', 'ca', 'Indica en cas necessari una adreça postal detallada.');
INSERT INTO `text` VALUES('tooltip-project-post_address', 'en', 'Indicate, if need be, a detailed postal address');
INSERT INTO `text` VALUES('tooltip-project-project_location', 'ca', 'Indica el lloc on es desenvoluparà el projecte, en quina població o poblacions es troba el seu impulsor o impulsors principals.');
INSERT INTO `text` VALUES('tooltip-project-project_location', 'en', 'Indicate where the project will be developed; in which towns or cities the principal promoters are located.');
INSERT INTO `text` VALUES('tooltip-project-related', 'ca', 'Resumeix la teva trajectòria o la del grup impulsor del projecte. Quina experiència tens relacionada amb la proposta? Amb quin equip de persones, recursos i/o infraestructures compta? ');
INSERT INTO `text` VALUES('tooltip-project-related', 'en', 'Summarize how you or the promoting group got to this point. What experience do you have that relates to the proposal? Tell us about your team, resources, and infrastructure. ');
INSERT INTO `text` VALUES('tooltip-project-resource', 'ca', 'Indica aquí si comptes amb recursos addicionals, a part dels quals sol·licites, per dur a terme el projecte: fonts de finançament, recursos propis o bé ja comptes amb materials. Pot suposar un al·licient per als potencials cofinançadors del projecte.');
INSERT INTO `text` VALUES('tooltip-project-resource', 'en', 'Note here if you are counting on additional resources, apart from those that you''re asking for, in order to carry out this project: sources of financing, your own resources, or perhaps you''ve already gathered some materials. This might be attractive to potential co-financiers of your project.');
INSERT INTO `text` VALUES('tooltip-project-schedule', 'ca', 'Visualització de com queda l''agenda de producció del teu projecte. Recorda que només has d''assenyalar les noves tasques a realitzar, no les que ja s''hagin dut a terme.');
INSERT INTO `text` VALUES('tooltip-project-schedule', 'en', 'Production calendar for your project. Remember that you should only indicate new tasks, not those that have already been completed.');
INSERT INTO `text` VALUES('tooltip-project-scope', 'ca', 'Indica l''impacte geogràfic que aspira a tenir el projecte (cada categoria inclou l''anterior). ');
INSERT INTO `text` VALUES('tooltip-project-scope', 'en', 'Specify the geographic impact that you hope this project will have (each category includes the previous one)');
INSERT INTO `text` VALUES('tooltip-project-social_reward-description', 'ca', 'Explica breument el tipus de retorn col·lectiu que oferirà o permetrà el projecte.');
INSERT INTO `text` VALUES('tooltip-project-social_reward-description', 'en', 'Briefly explain the type of collective benefit that this project will offer or allow.');
INSERT INTO `text` VALUES('tooltip-project-social_reward-icon-other', 'ca', 'Especifica breument en què consistirà aquest altre tipus de retorn col·lectiu.');
INSERT INTO `text` VALUES('tooltip-project-social_reward-icon-other', 'en', 'Briefly describe what this other collective benefit consists of');
INSERT INTO `text` VALUES('tooltip-project-social_reward-license', 'ca', 'Aquí has de seleccionar una llicència d''entre cadascuna del grup que es mostren. Et recomanem llegir-les amb calma abans de decidir, però pensa que un aspecte important Goteo és que els projectes disposin de llicències el més obertes possible.');
INSERT INTO `text` VALUES('tooltip-project-social_reward-license', 'en', 'Choose a license from the group displayed. We recommend that you read through them carefully before deciding, and keep in mind that an important factor for Goteo is that projects have the most open licences possible.');
INSERT INTO `text` VALUES('tooltip-project-social_reward-reward', 'ca', 'Intenta que el títol sigui el més descriptiu possible. Recorda que pots afegir més recompenses a continuació.');
INSERT INTO `text` VALUES('tooltip-project-social_reward-reward', 'en', 'Try to make the title as descriptive as possible. Remember that you can add more benefits later.');
INSERT INTO `text` VALUES('tooltip-project-social_reward-type', 'ca', 'Especifica el tipus de retorn: ARXIUS DIGITALS com a música, vídeo, documents de text, etc. CODI FONT de programari informàtic. DISSENYS de plànols o patrons. MANUALS en forma de kits, bussiness plans, "how tos" o receptes. SERVEIS com a tallers, cursos, assessories, accés a planes webs, bases de dades online. ');
INSERT INTO `text` VALUES('tooltip-project-social_reward-type', 'en', 'Specify the type of benefit: DIGITAL FILES like music, video, text documents, etc. SOURCE CODE of a software program, DESIGNS of layouts or patterns, MANUALS in the form of kits, business plans, how-to''s, or receipts, SERVICES like workshops, courses, consulting, website access, online databases.');
INSERT INTO `text` VALUES('tooltip-project-social_rewards', 'ca', 'Defineix el tipus de retorn o retorns del projecte als quals es podrà accedir obertament, i la llicència que els ha de regular. ');
INSERT INTO `text` VALUES('tooltip-project-social_rewards', 'en', 'Define the type of benefits from the project that can be accessed openly, and the license that should regulate them. If you have any doubts about which option to choose, or which is best in your case, <a href="http://www.goteo.org/contact" target="new">contact us</a> and we will advise you on this point.');
INSERT INTO `text` VALUES('tooltip-project-subtitle', 'ca', 'Defineix amb una frase un subtítol que acabi d''explicar en què consistirà la iniciativa, que permeti fer-se una idea mínima de per a què serveix o en què consisteix. Apareixerà al costat del títol del projecte.');
INSERT INTO `text` VALUES('tooltip-project-subtitle', 'en', 'Create a single phrase for the subtitle that explains just what your initiative is about, that gives a taste of what it''s for and what it consists of. It will appear together with the project title.');
INSERT INTO `text` VALUES('tooltip-project-support', 'en', 'Advice on editing existing collaborations');
INSERT INTO `text` VALUES('tooltip-project-support-description', 'ca', 'Explica breument en què consisteix l''ajuda que necessita el projecte, per facilitar que la gent la reconegui i s''animi a col·laborar. ');
INSERT INTO `text` VALUES('tooltip-project-support-description', 'en', 'Briefly explain the kind of help that your project needs, to help people see what is needed and encourage them to collaborate.');
INSERT INTO `text` VALUES('tooltip-project-support-support', 'ca', 'Títol descriptiu sobre la col·laboració necessària.');
INSERT INTO `text` VALUES('tooltip-project-support-support', 'en', 'Descriptive title about the needed collaboration');
INSERT INTO `text` VALUES('tooltip-project-support-type', 'ca', 'Selecciona si el projecte necessita ajuda en tasques concretes o bé préstecs (de material, infraestructura, etc). ');
INSERT INTO `text` VALUES('tooltip-project-support-type', 'en', 'Choose if the project needs help with concrete tasks or rather loans of material, infrastructure, etc.');
INSERT INTO `text` VALUES('tooltip-project-support-type-lend', 'ca', 'Préstec temporal de material necessari per al projecte, o bé de cessió d''infraestructures o espais per un període determinat. També pot implicar préstecs permanents, és a dir regals :)');
INSERT INTO `text` VALUES('tooltip-project-support-type-lend', 'en', 'Temporary loan of material needed for the project, or the loan of infrastructure or space during a given period of time. It might also imply permanent loans, that is, gifts :)');
INSERT INTO `text` VALUES('tooltip-project-support-type-task', 'ca', 'Col·laboració que requereixi una habilitat per a una tasca específica, ja sigui a distància mitjançant ordinador o bé presencialment.');
INSERT INTO `text` VALUES('tooltip-project-support-type-task', 'en', 'This collaboration requires a skill in a specific task, either via computer or in person.');
INSERT INTO `text` VALUES('tooltip-project-supports', 'ca', 'A Goteo els projectes poden rebre un altre tipus d''ajudes a més d''aportacions monetàries. Hi ha gent que potser no pugui ajudar econòmicament però sí amb el seu talent, temps, energia, etc.');
INSERT INTO `text` VALUES('tooltip-project-supports', 'en', 'In Goteo, projects can be supported in other ways besides just monetary. There are people who can''t help economically but have plenty to offer in terms of their talent, time, energy, and more.');
INSERT INTO `text` VALUES('tooltip-project-totals', 'ca', 'Aquest gràfic mostra la suma de costos imprescindibles (mínims per realitzar el projecte) i la suma de costos secundaris (import òptim) per a les dues rondes de finançament. La primera ronda és de 40 dies, per aconseguir l''import mínim imprescindible. Només si s''aconsegueix aquest volum de finançament es pot optar a la segona ronda, de 40 dies més, per arribar al pressupost òptim. A diferència de la primera, a la segona ronda s''obté tot el que s''ha recaptat (encara que no s''hagi arribat al mínim).');
INSERT INTO `text` VALUES('tooltip-project-totals', 'en', 'This graph shows the sum of essential expenses (the minimum necessary to complete the project) and the sum of secondary expenses (optimal amount) for the two rounds of financing. The first round lasts 40 days, in order to obtain the minimum essential amount. Only those that achieve this level of financing can opt to continue with the second round, for an additional 40 days, in order to achieve the optimal amount of funds. In the second round (in contrast with the first), you get everything you have raised even if you don''t reach the full goal.  ');
INSERT INTO `text` VALUES('tooltip-project-usubs', 'ca', 'Marca la casella en cas que hagis subtitulat a altres idiomes el vídeo mitjançant Universal Subtitles: http://www.universalsubtitles.org/');
INSERT INTO `text` VALUES('tooltip-project-usubs', 'en', 'Check if you subtitled the video to other languages with Universal Subtitles: http://www.universalsubtitles.org/');
INSERT INTO `text` VALUES('tooltip-project-video', 'ca', 'Considera aquí la possibilitat de publicar i enllaçar un vídeo (a Youtube o Vimeo) on expliquis breument a la càmera el perquè del teu projecte. Es tracta de quelcom que pugui complementar el vídeo principal, amb una persona que transmeti la seva necessitat o originalitat, de la manera més directa possible. Si et dóna tall parlar a càmera, també pot ser alguna persona que coneixes i segueix el projecte o la idea i pugui fer aquesta aportació com a "fan". L''empatia i necessitat de veure algú a l''altre costat del projecte és molt important para determinat tipus de cofinançadors.  ');
INSERT INTO `text` VALUES('tooltip-project-video', 'en', 'Consider publishing and linking a video (YouTube or Vimeo) where you briefly explain to the camera your vision for your project. This is something that might compliment the principal video, by letting you transmit your need or originality in the most direct way possible. If you find it hard speaking to the camera, maybe there''s someone else you know and who is following the project that might complete this step as a "fan". Feeling empathy for and seeing the person on the other side of the project is a key component for certain kinds of co-financiers.');
INSERT INTO `text` VALUES('tooltip-user-about', 'ca', 'Com a xarxa social, Goteo pretén ajudar a difondre i finançar projectes interessants entre el màxim de gent possible. Per això és important la informació que puguis compartir sobre les teves habilitats o experiència (professional, acadèmica, aficions, etc). ');
INSERT INTO `text` VALUES('tooltip-user-about', 'en', 'As a social network, Goteo attempts to help spread the word about and finance interesting projects among the largest amount of people possible. To that end, it''s important to share information about your skills and experience (professional, academic, hobbies, etc.)');
INSERT INTO `text` VALUES('tooltip-user-contribution', 'ca', 'Explica breument les teves habilitats o els àmbits on podries ajudar un projecte (traduint, difonent, testejant, programant, ensenyant, etc).');
INSERT INTO `text` VALUES('tooltip-user-contribution', 'en', 'Briefly explain your skills and the areas in which you could help (translating, marketing, testing, programming, teaching, etc.)');
INSERT INTO `text` VALUES('tooltip-user-facebook', 'ca', 'Aquesta xarxa social pot ajudar que difonguis projectes de Goteo que t''interessen entre amics i familiars.');
INSERT INTO `text` VALUES('tooltip-user-facebook', 'en', 'This social network can help you spread the word about Goteo projects that interest you with your friends and family.');
INSERT INTO `text` VALUES('tooltip-user-google', 'ca', 'La xarxa social de Google+ és molt nova però també pots indicar el teu usuari si ja la fas servir :)');
INSERT INTO `text` VALUES('tooltip-user-google', 'en', 'The Google+ social network is pretty new, but you can specify your user id if you use it already.');
INSERT INTO `text` VALUES('tooltip-user-identica', 'ca', 'Aquest canal pot ajudar que difonguis projectes de Goteo entre la comunitat propera al programari lliure.');
INSERT INTO `text` VALUES('tooltip-user-identica', 'en', 'This channel can help you spread the word about Goteo projects in the open source community.');
INSERT INTO `text` VALUES('tooltip-user-image', 'ca', 'No és obligatori que posis una fotografia en el teu perfil, però ajuda que els altres usuaris t''identifiquin.');
INSERT INTO `text` VALUES('tooltip-user-image', 'en', 'You don''t have to add a profile picture, but it does help other users to identify you.');
INSERT INTO `text` VALUES('tooltip-user-interests', 'ca', 'Indica el tipus de projectes que poden connectar amb els teus interessos per cofinançar-los i/o aportar amb altres recursos, coneixements o habilitats. Aquestes categories són transversals, pots seleccionar-ne més d''una.');
INSERT INTO `text` VALUES('tooltip-user-interests', 'en', 'Indicate the kind of projects you might be interested in co-financing or in supporting with other resources, knowledge or skills. These categories cut across boundaries; you can select more than one.');
INSERT INTO `text` VALUES('tooltip-user-keywords', 'ca', 'A mesura que introdueixis text el sistema et suggerirà paraules clau que ja han escrit altres usuaris. Aquestes categories ajuden a vincular el teu perfil amb altres persones i amb projectes concrets.');
INSERT INTO `text` VALUES('tooltip-user-keywords', 'en', 'As you enter text, the system will suggest key words that have already been created by other users. These categories help link your profile with specific other people and projects.');
INSERT INTO `text` VALUES('tooltip-user-linkedin', 'ca', 'Aquesta xarxa social pot ajudar que difonguis projectes de Goteo que t''interessen entre contactes professionals.');
INSERT INTO `text` VALUES('tooltip-user-linkedin', 'en', 'This social network can help you spread the word about interesting Goteo projects with your professional contacts.');
INSERT INTO `text` VALUES('tooltip-user-location', 'ca', 'Aquesta dada és important per poder-te vincular amb un grup local de Goteo.');
INSERT INTO `text` VALUES('tooltip-user-location', 'en', 'This information is important so that we can connect you with a local Goteo group.');
INSERT INTO `text` VALUES('tooltip-user-name', 'ca', 'El teu nom o nom d''usuari a Goteo. Ho pots canviar sempre que vulguis (atenció: no és el mateix que el login d''accés, que ja no es pot modificar).');
INSERT INTO `text` VALUES('tooltip-user-name', 'en', 'Your screen name or nickname on Goteo. You can change it whenever you like. (Note: it''s not the same as your user name, which cannot be changed.)');
INSERT INTO `text` VALUES('tooltip-user-twitter', 'ca', 'Aquesta xarxa social pot ajudar que difonguis projectes de Goteo de manera àgil i viral.');
INSERT INTO `text` VALUES('tooltip-user-twitter', 'en', 'This social network can help spread the word about Goteo projects quickly and virally.');
INSERT INTO `text` VALUES('tooltip-user-webs', 'ca', 'Indica les adreces URL de pàgines personals o d''altre tipus vinculades a tu.');
INSERT INTO `text` VALUES('tooltip-user-webs', 'en', 'Indicate the URL addresses of personal or other websites that you are associated with.');
INSERT INTO `text` VALUES('translate-home-guide', 'ca', 'Message pour le traducteur');
INSERT INTO `text` VALUES('translate-home-guide', 'en', 'Note to the translator');
INSERT INTO `text` VALUES('translate-home-guide', 'fr', 'Message pour le traducteur');
INSERT INTO `text` VALUES('user-account-inactive', 'ca', 'El compte està desactivat. Has de recuperar la contrasenya per activar-lo de nou');
INSERT INTO `text` VALUES('user-account-inactive', 'en', 'The account is deactivated.');
INSERT INTO `text` VALUES('user-activate-already-active', 'ca', 'El compte d''usuari ja es troba actiu');
INSERT INTO `text` VALUES('user-activate-already-active', 'en', 'The account is already active.');
INSERT INTO `text` VALUES('user-activate-fail', 'ca', 'Error en activar el compte d''usuari');
INSERT INTO `text` VALUES('user-activate-fail', 'en', 'Error upon activating the user account');
INSERT INTO `text` VALUES('user-activate-success', 'ca', 'El compte d''usuari s''ha activat correctament');
INSERT INTO `text` VALUES('user-activate-success', 'en', 'The account has been activated correctly.');
INSERT INTO `text` VALUES('user-changeemail-fail', 'ca', 'Error en canviar l''email');
INSERT INTO `text` VALUES('user-changeemail-fail', 'en', 'Error while changing the email');
INSERT INTO `text` VALUES('user-changeemail-success', 'ca', 'L''email s''ha canviat correctament ;)');
INSERT INTO `text` VALUES('user-changeemail-success', 'en', 'The email address has been successfully changed.');
INSERT INTO `text` VALUES('user-changeemail-title', 'ca', 'Canviar email');
INSERT INTO `text` VALUES('user-changeemail-title', 'en', 'Change email');
INSERT INTO `text` VALUES('user-changepass-confirm', 'ca', 'Confirmar nova contrasenya');
INSERT INTO `text` VALUES('user-changepass-confirm', 'en', 'Confirm new password');
INSERT INTO `text` VALUES('user-changepass-new', 'ca', 'Nova contrasenya');
INSERT INTO `text` VALUES('user-changepass-new', 'en', 'New password');
INSERT INTO `text` VALUES('user-changepass-old', 'ca', 'Contrasenya actual');
INSERT INTO `text` VALUES('user-changepass-old', 'en', 'Current password');
INSERT INTO `text` VALUES('user-changepass-title', 'ca', 'Canviar contrasenya');
INSERT INTO `text` VALUES('user-changepass-title', 'en', 'Change password');
INSERT INTO `text` VALUES('user-email-change-sended', 'ca', 'T''hem enviat un email per confirmar el teu canvi d''adreça electrònica');
INSERT INTO `text` VALUES('user-email-change-sended', 'en', 'We have sent you an email so that you can confirm the change of address');
INSERT INTO `text` VALUES('user-login-required', 'ca', 'Has d''iniciar sessió per poder interactuar amb la comunitat de Goteo');
INSERT INTO `text` VALUES('user-login-required', 'en', 'You have to sign in to interact with the Goteo community');
INSERT INTO `text` VALUES('user-login-required-access', 'ca', 'Has d''iniciar sessió o sol·licitar permisos per accedir aquesta secció');
INSERT INTO `text` VALUES('user-login-required-access', 'en', 'You have to sign in or ask for permission to get access to this section');
INSERT INTO `text` VALUES('user-login-required-to_create', 'ca', 'Has d''iniciar sessió per crear un projecte');
INSERT INTO `text` VALUES('user-login-required-to_create', 'en', 'You have to sign in to create a project');
INSERT INTO `text` VALUES('user-login-required-to_invest', 'ca', 'Has d''iniciar sessió per cofinançar un projecte');
INSERT INTO `text` VALUES('user-login-required-to_invest', 'en', 'You have to sign in to co-finance a project');
INSERT INTO `text` VALUES('user-login-required-to_message', 'ca', 'Has d''iniciar sessió per a enviar missatges');
INSERT INTO `text` VALUES('user-login-required-to_message', 'en', 'You have to sign in to send messages');
INSERT INTO `text` VALUES('user-login-required-to_see', 'ca', 'Has d''iniciar sessió per veure aquesta pàgina');
INSERT INTO `text` VALUES('user-login-required-to_see', 'en', 'You have to sign in to see this page');
INSERT INTO `text` VALUES('user-login-required-to_see-supporters', 'ca', 'Has d''iniciar sessió per veure els cofinançadors');
INSERT INTO `text` VALUES('user-login-required-to_see-supporters', 'en', 'You have to sign in to see the other co-financiers');
INSERT INTO `text` VALUES('user-message-send_personal-header', 'ca', 'Envia un missatge a un usuari');
INSERT INTO `text` VALUES('user-message-send_personal-header', 'en', 'Send a message to the user');
INSERT INTO `text` VALUES('user-password-changed', 'ca', 'Has canviat la teva contrasenya');
INSERT INTO `text` VALUES('user-password-changed', 'en', 'You have changed your password');
INSERT INTO `text` VALUES('user-personal-saved', 'ca', 'Dades personals actualitzades ');
INSERT INTO `text` VALUES('user-personal-saved', 'en', 'Updated personal information');
INSERT INTO `text` VALUES('user-prefer-saved', 'ca', 'Les teves preferències de notificació s''han desat correctament');
INSERT INTO `text` VALUES('user-prefer-saved', 'en', 'Your notification preferences were saved correctly');
INSERT INTO `text` VALUES('user-preferences-mailing', 'ca', 'Bloquejar l''enviament del newsletter');
INSERT INTO `text` VALUES('user-preferences-mailing', 'en', 'Disable newsletter subscription');
INSERT INTO `text` VALUES('user-preferences-rounds', 'ca', 'Bloquejar notificacions de progrés dels projectes que recolzo');
INSERT INTO `text` VALUES('user-preferences-rounds', 'en', 'Disable progress notifications from the projects I''m backing');
INSERT INTO `text` VALUES('user-preferences-threads', 'ca', 'Bloquejar notificacions de respostes als missatges que jo inicio ');
INSERT INTO `text` VALUES('user-preferences-threads', 'en', 'Disable answer notifications to the messages I start');
INSERT INTO `text` VALUES('user-preferences-updates', 'ca', 'Bloquejar notificacions sobre novetats dels projectes que recolzo');
INSERT INTO `text` VALUES('user-preferences-updates', 'en', 'Disable news notifications from the projects I''m backing');
INSERT INTO `text` VALUES('user-profile-saved', 'ca', 'Informació de perfil actualitzada');
INSERT INTO `text` VALUES('user-profile-saved', 'en', 'Profile updated');
INSERT INTO `text` VALUES('user-register-success', 'ca', 'L''usuari s''ha registrat correctament. A continuació rebràs un missatge de correu per activar-lo.');
INSERT INTO `text` VALUES('user-register-success', 'en', 'The user was registered correctly. We are sending you an activation email now.');
INSERT INTO `text` VALUES('user-save-fail', 'ca', 'Hi ha hagut algun problema en desar les dades');
INSERT INTO `text` VALUES('user-save-fail', 'en', 'There was a problem saving the data');
INSERT INTO `text` VALUES('validate-cost-field-dates', 'ca', 'Has d''indicar les dates d''inici i final d''aquest cost per poder valorar millor el projecte.');
INSERT INTO `text` VALUES('validate-cost-field-dates', 'en', 'Specify the beginning and ending dates for the expense in order to better evaluate the project.');
INSERT INTO `text` VALUES('validate-project-costs-any_error', 'ca', 'Manca alguna informació en el desglossament de costos');
INSERT INTO `text` VALUES('validate-project-costs-any_error', 'en', 'Some information is missing from the breakdown of the expense');
INSERT INTO `text` VALUES('validate-project-field-about', 'ca', 'L''explicació del projecte és massa curta');
INSERT INTO `text` VALUES('validate-project-field-about', 'en', 'The project description is too short');
INSERT INTO `text` VALUES('validate-project-field-costs', 'ca', 'Recomanem desglossar fins a 5 costos diferents per millorar la valoració del projecte de cara a determinar si publicar-ho a Goteo.');
INSERT INTO `text` VALUES('validate-project-field-costs', 'en', 'We recommend that you break it down into least 5 distinct expenses to make it easier to evaluate your project to see whether it should be published on Goteo.');
INSERT INTO `text` VALUES('validate-project-field-currently', 'ca', 'Indica l''estat del projecte per millorar-ne la seva valoració, de cara a determinar si publicar-ho a Goteo.');
INSERT INTO `text` VALUES('validate-project-field-currently', 'en', 'Indicate the current status of the project in order to facilitate the evaluation of the project and to determine whether it should be published on Goteo.');
INSERT INTO `text` VALUES('validate-project-field-description', 'ca', 'La descripció del projecte és massa curta');
INSERT INTO `text` VALUES('validate-project-field-description', 'en', 'The project description is too short');
INSERT INTO `text` VALUES('validate-project-individual_rewards', 'ca', 'Indica fins a 5 recompenses individuals per millorar-ne la puntuació.');
INSERT INTO `text` VALUES('validate-project-individual_rewards', 'en', 'Indicate up to 5 individual rewards in order to improve the score');
INSERT INTO `text` VALUES('validate-project-individual_rewards-any_error', 'ca', 'Manca alguna informació sobre recompenses individuals');
INSERT INTO `text` VALUES('validate-project-individual_rewards-any_error', 'en', 'Some information about individual rewards is missing.');
INSERT INTO `text` VALUES('validate-project-social_rewards', 'ca', 'És obligatori indicar com a mínim un retorn col·lectiu');
INSERT INTO `text` VALUES('validate-project-social_rewards', 'en', 'You have to specify at least one collective benefit');
INSERT INTO `text` VALUES('validate-project-social_rewards-any_error', 'ca', 'Manca alguna informació sobre retorns col·lectius');
INSERT INTO `text` VALUES('validate-project-social_rewards-any_error', 'en', 'Some information is missing about collective benefits.');
INSERT INTO `text` VALUES('validate-project-total-costs', 'ca', 'El cost òptim no pot superar en més d''un 40% el cost mínim. Has de revisar el desglossament de costos.');
INSERT INTO `text` VALUES('validate-project-total-costs', 'en', 'The optimal cost cannot exceed the minimum cost by more than 50%. You have to either raise the essential costs or lower the supplemental costs. ');
INSERT INTO `text` VALUES('validate-project-userProfile-any_error', 'ca', 'Hi ha algun error en l''adreça URL introduïda');
INSERT INTO `text` VALUES('validate-project-userProfile-any_error', 'en', 'There''s an error in the URL address');
INSERT INTO `text` VALUES('validate-project-userProfile-web', 'ca', 'És recomanable indicar alguna web');
INSERT INTO `text` VALUES('validate-project-userProfile-web', 'en', 'We recommend you list some websites');
INSERT INTO `text` VALUES('validate-project-value-contract_email', 'ca', 'L''adreça d''email no és correcta');
INSERT INTO `text` VALUES('validate-project-value-contract_email', 'en', 'The email address is incorrect.');
INSERT INTO `text` VALUES('validate-project-value-contract_nif', 'ca', 'El NIF no és correcte');
INSERT INTO `text` VALUES('validate-project-value-contract_nif', 'en', 'The NIF is incorrect');
INSERT INTO `text` VALUES('validate-project-value-description', 'ca', 'La descripció del projecte és massa curta ');
INSERT INTO `text` VALUES('validate-project-value-description', 'en', 'The project description is too short');
INSERT INTO `text` VALUES('validate-project-value-entity_cif', 'ca', 'El CIF no és vàlid');
INSERT INTO `text` VALUES('validate-project-value-entity_cif', 'en', 'The Business number (CIF) is not valid.');
INSERT INTO `text` VALUES('validate-project-value-keywords', 'ca', 'Indica un mínim de 5 paraules clau del projecte per millorar-ne la seva valoració, de cara a determinar si publicar-ho a Goteo.');
INSERT INTO `text` VALUES('validate-project-value-keywords', 'en', 'Write down at least five key words about the project that will help facilitate its evaulation by Goteo.');
INSERT INTO `text` VALUES('validate-project-value-phone', 'ca', 'El format de número de telèfon no és correcte.');
INSERT INTO `text` VALUES('validate-project-value-phone', 'en', 'The format of the telephone number is incorrect');
INSERT INTO `text` VALUES('validate-register-value-email', 'ca', 'L''email introduït no és vàlid');
INSERT INTO `text` VALUES('validate-register-value-email', 'en', 'The email address you entered is not valid');
INSERT INTO `text` VALUES('validate-social_reward-license', 'ca', 'Indicar una llicència per millorar la puntuació');
INSERT INTO `text` VALUES('validate-social_reward-license', 'en', 'Indicate a license in order to improve the score');
INSERT INTO `text` VALUES('validate-user-field-about', 'ca', 'Explica alguna cosa sobre tu, per així millorar la valoració del projecte de cara a determinar si publicar-ho a Goteo.');
INSERT INTO `text` VALUES('validate-user-field-about', 'en', 'Tell us about yourself so that we can better evaluate your project and determine its suitability for publishing on Goteo.');
INSERT INTO `text` VALUES('validate-user-field-avatar', 'ca', 'Posa una imatge de perfil per millorar la valoració del projecte de cara a determinar si publicar-ho a Goteo.');
INSERT INTO `text` VALUES('validate-user-field-avatar', 'en', 'Add a profile image to help us evaluate your project and determine whether it is suitable for publication on Goteo.');
INSERT INTO `text` VALUES('validate-user-field-contribution', 'ca', 'Explica què podries aportar a Goteo, per millorar la valoració del projecte de cara a determinar si publicar-ho en la plataforma.');
INSERT INTO `text` VALUES('validate-user-field-contribution', 'en', 'Explain what you could bring to Goteo to improve the evaluation of the project to determine whether it should be published on the platform.');
INSERT INTO `text` VALUES('validate-user-field-facebook', 'ca', 'Posa el teu compte de Facebook per millorar la valoració del projecte de cara a determinar si publicar-ho a Goteo.');
INSERT INTO `text` VALUES('validate-user-field-facebook', 'en', 'Add your Facebook user name to help us evaluate your project and whether it is suitable for publication on Goteo.');
INSERT INTO `text` VALUES('validate-user-field-interests', 'ca', 'Selecciona algun interès per millorar la valoració del projecte de cara a determinar si publicar-ho a Goteo.');
INSERT INTO `text` VALUES('validate-user-field-interests', 'en', 'Select an interest to help us evaluate your project and determine if it is suitable for publication on Goteo.');
INSERT INTO `text` VALUES('validate-user-field-keywords', 'ca', 'Indica fins a 5 paraules clau que et defineixin, per millorar la valoració del projecte de cara a determinar si publicar-ho a Goteo.');
INSERT INTO `text` VALUES('validate-user-field-keywords', 'en', 'Indicate up to 5 key words that define you, to help evaluate your project and decide if it is suitable for publication on Goteo.');
INSERT INTO `text` VALUES('validate-user-field-linkedin', 'ca', 'El camp de Linkedin no es vàlid ');
INSERT INTO `text` VALUES('validate-user-field-linkedin', 'en', 'The LinkedIn field is not valid');
INSERT INTO `text` VALUES('validate-user-field-location', 'ca', 'El lloc de residència de l''usuari no és vàlid');
INSERT INTO `text` VALUES('validate-user-field-location', 'en', 'The place of residence of the user is not valid');
INSERT INTO `text` VALUES('validate-user-field-name', 'ca', 'Posa el teu nom complert per millorar la valoració del projecte de cara a determinar si publicar-ho a Goteo.');
INSERT INTO `text` VALUES('validate-user-field-name', 'en', 'Enter your complete name to help us evaluate your project and see whether it is suitable for publishing on Goteo.');
INSERT INTO `text` VALUES('validate-user-field-twitter', 'ca', 'L''usuari de Twitter no és vàlid');
INSERT INTO `text` VALUES('validate-user-field-twitter', 'en', 'The Twitter user name is not valid');
INSERT INTO `text` VALUES('validate-user-field-web', 'ca', 'Has de posar l''adreça (URL) de la web');
INSERT INTO `text` VALUES('validate-user-field-web', 'en', 'Please enter the address (URL) of the website');
INSERT INTO `text` VALUES('validate-user-field-webs', 'ca', 'Posa la teva pàgina web per millorar la valoració del projecte de cara a determinar si publicar-ho a Goteo.');
INSERT INTO `text` VALUES('validate-user-field-webs', 'en', 'Enter the address of your website to help us evaluate your project and determine if it is suitable for publishing on Goteo.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(40) NOT NULL,
  `about` text,
  `keywords` tinytext,
  `active` tinyint(1) NOT NULL,
  `avatar` int(11) DEFAULT NULL,
  `contribution` text,
  `twitter` tinytext,
  `facebook` tinytext,
  `google` tinytext,
  `identica` tinytext,
  `linkedin` tinytext,
  `worth` int(7) DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `token` tinytext NOT NULL,
  `hide` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'No se ve publicamente',
  `confirmed` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `user`
--

INSERT INTO `user` VALUES('root', 'Super administrador', '', 'root_goteo@doukeshi.org', 'f64dd0d8c9276d87c6d0ae24c5d12571c62ecf16', '', '', 1, 91, '', '', '', '', '', '', 0, '2011-08-31 19:54:11', '2011-12-21 22:45:39', '61aa85ea9169c68babfa5b8bdb44097broot_goteo@doukeshi.org', 1, 1);
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_image`
--

DROP TABLE IF EXISTS `user_image`;
CREATE TABLE `user_image` (
  `user` varchar(50) NOT NULL,
  `image` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user`,`image`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `user_image`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_interest`
--

DROP TABLE IF EXISTS `user_interest`;
CREATE TABLE `user_interest` (
  `user` varchar(50) NOT NULL,
  `interest` int(12) NOT NULL,
  UNIQUE KEY `user_interest` (`user`,`interest`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Intereses de los usuarios';

--
-- Volcar la base de datos para la tabla `user_interest`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_lang`
--

DROP TABLE IF EXISTS `user_lang`;
CREATE TABLE `user_lang` (
  `id` varchar(50) NOT NULL,
  `lang` varchar(2) NOT NULL,
  `about` text,
  `keywords` tinytext,
  `contribution` text,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `user_lang`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_login`
--

DROP TABLE IF EXISTS `user_login`;
CREATE TABLE `user_login` (
  `user` varchar(50) NOT NULL,
  `provider` varchar(50) NOT NULL,
  `oauth_token` text NOT NULL,
  `oauth_token_secret` text NOT NULL,
  `datetime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user`,`oauth_token`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `user_login`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_personal`
--

DROP TABLE IF EXISTS `user_personal`;
CREATE TABLE `user_personal` (
  `user` varchar(50) NOT NULL,
  `contract_name` varchar(255) DEFAULT NULL,
  `contract_surname` varchar(255) DEFAULT NULL,
  `contract_nif` varchar(15) DEFAULT NULL COMMENT 'Guardar sin espacios ni puntos ni guiones',
  `contract_email` varchar(256) DEFAULT NULL,
  `phone` varchar(9) DEFAULT NULL COMMENT 'guardar sin espacios ni puntos',
  `address` tinytext,
  `zipcode` varchar(10) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Datos personales de usuario';

--
-- Volcar la base de datos para la tabla `user_personal`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_prefer`
--

DROP TABLE IF EXISTS `user_prefer`;
CREATE TABLE `user_prefer` (
  `user` varchar(50) NOT NULL,
  `updates` int(1) NOT NULL DEFAULT '0',
  `threads` int(1) NOT NULL DEFAULT '0',
  `rounds` int(1) NOT NULL DEFAULT '0',
  `mailing` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Preferencias de notificacion de usuario';

--
-- Volcar la base de datos para la tabla `user_prefer`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_review`
--

DROP TABLE IF EXISTS `user_review`;
CREATE TABLE `user_review` (
  `user` varchar(50) NOT NULL,
  `review` bigint(20) unsigned NOT NULL,
  `ready` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Ha terminado con la revision',
  PRIMARY KEY (`user`,`review`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Asignacion de revision a usuario';

--
-- Volcar la base de datos para la tabla `user_review`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_role`
--

DROP TABLE IF EXISTS `user_role`;
CREATE TABLE `user_role` (
  `user_id` varchar(50) NOT NULL,
  `role_id` varchar(50) NOT NULL,
  `node_id` varchar(50) NOT NULL,
  `datetime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `user_FK` (`user_id`),
  KEY `role_FK` (`role_id`),
  KEY `node_FK` (`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `user_role`
--

INSERT INTO `user_role` VALUES('root', 'admin', '*', NULL);
INSERT INTO `user_role` VALUES('root', 'checker', '*', NULL);
INSERT INTO `user_role` VALUES('root', 'root', '*', NULL);
INSERT INTO `user_role` VALUES('root', 'superadmin', '*', NULL);
INSERT INTO `user_role` VALUES('root', 'translator', '*', NULL);
INSERT INTO `user_role` VALUES('root', 'user', '*', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_translate`
--

DROP TABLE IF EXISTS `user_translate`;
CREATE TABLE `user_translate` (
  `user` varchar(50) NOT NULL,
  `project` varchar(50) NOT NULL,
  `ready` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Ha terminado con la traduccion',
  PRIMARY KEY (`user`,`project`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Asignacion de traduccion a usuario';

--
-- Volcar la base de datos para la tabla `user_translate`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_web`
--

DROP TABLE IF EXISTS `user_web`;
CREATE TABLE `user_web` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(50) NOT NULL,
  `url` tinytext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Webs de los usuarios' AUTO_INCREMENT=757 ;

--
-- Volcar la base de datos para la tabla `user_web`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `worthcracy`
--

DROP TABLE IF EXISTS `worthcracy`;
CREATE TABLE `worthcracy` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `amount` int(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Niveles de meritocracia' AUTO_INCREMENT=6 ;

--
-- Volcar la base de datos para la tabla `worthcracy`
--

INSERT INTO `worthcracy` VALUES(1, 'Fan', 25);
INSERT INTO `worthcracy` VALUES(2, 'Patrocinador/a', 100);
INSERT INTO `worthcracy` VALUES(3, 'Apostador/a', 500);
INSERT INTO `worthcracy` VALUES(4, 'Abonad@', 1000);
INSERT INTO `worthcracy` VALUES(5, 'Visionari@', 3000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `worthcracy_lang`
--

DROP TABLE IF EXISTS `worthcracy_lang`;
CREATE TABLE `worthcracy_lang` (
  `id` int(2) unsigned NOT NULL,
  `lang` varchar(2) NOT NULL,
  `name` tinytext NOT NULL,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `worthcracy_lang`
--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
