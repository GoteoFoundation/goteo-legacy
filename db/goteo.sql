SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acl`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `acl`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banner`
--

CREATE TABLE `banner` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `node` varchar(50) NOT NULL,
  `project` varchar(50) DEFAULT NULL,
  `order` smallint(5) unsigned NOT NULL DEFAULT '1',
  `image` int(10) DEFAULT NULL,
  `active` int(1) NOT NULL DEFAULT '0',
  `title` tinytext,
  `description` text,
  `url` tinytext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Proyectos en banner superior';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banner_lang`
--

CREATE TABLE `banner_lang` (
  `id` bigint(20) unsigned NOT NULL,
  `lang` varchar(2) NOT NULL,
  `title` tinytext,
  `description` text,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `blog`
--

CREATE TABLE `blog` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL,
  `owner` varchar(50) NOT NULL COMMENT 'la id del proyecto o nodo',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Blogs de nodo o proyecto';

--
-- Volcar la base de datos para la tabla `blog`
--

INSERT INTO `blog` VALUES(1, 'node', 'goteo', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campaign`
--

CREATE TABLE `campaign` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `category`
--

CREATE TABLE `category` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext,
  `description` text,
  `order` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Categorias de los proyectos';

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

CREATE TABLE `comment` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post` bigint(20) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `text` text NOT NULL,
  `user` varchar(50) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Comentarios';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cost`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Desglose de costes de proyectos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cost_lang`
--

CREATE TABLE `cost_lang` (
  `id` int(20) NOT NULL,
  `lang` varchar(2) NOT NULL,
  `cost` tinytext,
  `description` text,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `criteria`
--

CREATE TABLE `criteria` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `section` varchar(50) NOT NULL DEFAULT 'node',
  `title` tinytext,
  `description` text,
  `order` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Criterios de puntuación';

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

CREATE TABLE `criteria_lang` (
  `id` bigint(20) unsigned NOT NULL,
  `lang` varchar(2) NOT NULL,
  `title` tinytext,
  `description` text,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `faq`
--

CREATE TABLE `faq` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `node` varchar(50) NOT NULL,
  `section` varchar(50) NOT NULL DEFAULT 'node',
  `title` tinytext,
  `description` text,
  `order` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Preguntas frecuentes';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `faq_lang`
--

CREATE TABLE `faq_lang` (
  `id` bigint(20) unsigned NOT NULL,
  `lang` varchar(2) NOT NULL,
  `title` tinytext,
  `description` text,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `feed`
--

CREATE TABLE `feed` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` tinytext NOT NULL,
  `url` tinytext,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `scope` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `html` text NOT NULL,
  `image` int(10) DEFAULT NULL,
  `target_type` varchar(10) DEFAULT NULL COMMENT 'tipo de objetivo',
  `target_id` varchar(50) DEFAULT NULL COMMENT 'registro objetivo',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `scope` (`scope`),
  KEY `type` (`type`),
  KEY `target_type` (`target_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Log de eventos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `glossary`
--

CREATE TABLE `glossary` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` tinytext,
  `text` longtext COMMENT 'texto de la entrada',
  `media` tinytext,
  `legend` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Entradas para el glosario';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `glossary_image`
--

CREATE TABLE `glossary_image` (
  `glossary` bigint(20) NOT NULL,
  `image` int(10) unsigned NOT NULL,
  PRIMARY KEY (`glossary`,`image`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `glossary_lang`
--

CREATE TABLE `glossary_lang` (
  `id` bigint(20) unsigned NOT NULL,
  `lang` varchar(2) NOT NULL,
  `title` tinytext,
  `text` longtext,
  `legend` text,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `home`
--

CREATE TABLE `home` (
  `item` varchar(10) NOT NULL,
  `type` varchar(5) DEFAULT 'main' COMMENT 'lateral o central',
  `node` varchar(50) NOT NULL,
  `order` smallint(5) unsigned NOT NULL DEFAULT '1',
  UNIQUE KEY `item_node` (`item`,`node`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Elementos en portada';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `icon`
--

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

CREATE TABLE `image` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `size` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `image`
--

INSERT INTO `image` VALUES(1, 'avatar.png', 'image/png', 1469);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `info`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Entradas about';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `info_image`
--

CREATE TABLE `info_image` (
  `info` bigint(20) NOT NULL,
  `image` int(10) unsigned NOT NULL,
  PRIMARY KEY (`info`,`image`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `info_lang`
--

CREATE TABLE `info_lang` (
  `id` bigint(20) unsigned NOT NULL,
  `lang` varchar(2) NOT NULL,
  `title` tinytext,
  `text` longtext,
  `legend` text,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invest`
--

CREATE TABLE `invest` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(50) NOT NULL,
  `project` varchar(50) NOT NULL,
  `account` varchar(256) NOT NULL,
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
  `issue` int(1) DEFAULT NULL COMMENT 'Problemas con el cobro del aporte',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Aportes monetarios a proyectos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invest_address`
--

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invest_detail`
--

CREATE TABLE `invest_detail` (
  `invest` bigint(20) NOT NULL,
  `type` varchar(30) NOT NULL,
  `log` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `invest_type` (`invest`,`type`),
  KEY `invest` (`invest`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalles de los aportes';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invest_reward`
--

CREATE TABLE `invest_reward` (
  `invest` bigint(20) unsigned NOT NULL,
  `reward` bigint(20) unsigned NOT NULL,
  `fulfilled` tinyint(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `invest` (`invest`,`reward`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Recompensas elegidas al aportar';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lang`
--

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
INSERT INTO `lang` VALUES('de', 'Deutsch', 0, 'GRM', 'de_DE');
INSERT INTO `lang` VALUES('el', 'Greek', 0, 'ελληνικά', 'el_GR');
INSERT INTO `lang` VALUES('en', 'English', 1, 'ENG', 'en_GB');
INSERT INTO `lang` VALUES('es', 'Español', 1, 'ES', 'es_ES');
INSERT INTO `lang` VALUES('eu', 'Euskara', 0, 'EUSK', 'eu_ES');
INSERT INTO `lang` VALUES('fr', 'Français', 0, 'FRA', 'fr_FR');
INSERT INTO `lang` VALUES('gl', 'Galego', 0, 'GAL', 'gl_ES');
INSERT INTO `lang` VALUES('it', 'Italiano', 0, 'ITA', 'it_IT');
INSERT INTO `lang` VALUES('nl', 'Dutch', 1, 'NL', 'nl_NL');
INSERT INTO `lang` VALUES('pl', 'Polski', 0, 'POL', 'pl_PL');
INSERT INTO `lang` VALUES('pt', 'Português', 0, 'PORT', 'pt_PT');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `license`
--

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

CREATE TABLE `mail` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` tinytext NOT NULL,
  `html` longtext NOT NULL,
  `template` int(20) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Contenido enviado por email para el -si no ves-';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `message`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Mensajes de usuarios en proyecto';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `message_lang`
--

CREATE TABLE `message_lang` (
  `id` int(20) NOT NULL,
  `lang` varchar(2) NOT NULL,
  `message` text,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `news`
--

CREATE TABLE `news` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` tinytext NOT NULL,
  `description` text COMMENT 'Entradilla',
  `url` tinytext NOT NULL,
  `order` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Noticias en la cabecera';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `news_lang`
--

CREATE TABLE `news_lang` (
  `id` bigint(20) unsigned NOT NULL,
  `lang` varchar(2) NOT NULL,
  `title` tinytext,
  `description` text,
  `url` tinytext,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `node`
--

CREATE TABLE `node` (
  `id` varchar(50) NOT NULL,
  `name` varchar(256) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Nodos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `page`
--

CREATE TABLE `page` (
  `id` varchar(50) NOT NULL,
  `name` tinytext NOT NULL,
  `description` text,
  `url` tinytext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Páginas institucionales';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `page_lang`
--

CREATE TABLE `page_lang` (
  `id` varchar(50) NOT NULL,
  `lang` varchar(2) NOT NULL,
  `name` tinytext NOT NULL,
  `description` text,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `page_node`
--

CREATE TABLE `page_node` (
  `page` varchar(50) NOT NULL,
  `node` varchar(50) NOT NULL,
  `lang` varchar(2) NOT NULL,
  `name` tinytext,
  `description` text,
  `content` longtext,
  UNIQUE KEY `page` (`page`,`node`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Contenidos de las paginas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `post`
--

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
  `author` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Entradas para la portada';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `post_image`
--

CREATE TABLE `post_image` (
  `post` bigint(20) NOT NULL,
  `image` int(10) unsigned NOT NULL,
  PRIMARY KEY (`post`,`image`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `post_lang`
--

CREATE TABLE `post_lang` (
  `id` bigint(20) unsigned NOT NULL,
  `lang` varchar(2) NOT NULL,
  `title` tinytext,
  `text` longtext,
  `legend` text,
  `media` tinytext,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `post_tag`
--

CREATE TABLE `post_tag` (
  `post` bigint(20) unsigned NOT NULL,
  `tag` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`post`,`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tags de las entradas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `project`
--

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
  `reward` text,
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `project_account`
--

CREATE TABLE `project_account` (
  `project` varchar(50) NOT NULL,
  `bank` tinytext,
  `bank_owner` tinytext,
  `paypal` tinytext,
  `paypal_owner` tinytext,
  `allowpp` int(1) DEFAULT NULL,
  PRIMARY KEY (`project`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Cuentas bancarias de proyecto';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `project_category`
--

CREATE TABLE `project_category` (
  `project` varchar(50) NOT NULL,
  `category` int(12) NOT NULL,
  UNIQUE KEY `project_category` (`project`,`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Categorias de los proyectos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `project_image`
--

CREATE TABLE `project_image` (
  `project` varchar(50) NOT NULL,
  `image` int(10) unsigned NOT NULL,
  `section` varchar(50) DEFAULT NULL,
  `url` tinytext,
  `order` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`project`,`image`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `project_lang`
--

CREATE TABLE `project_lang` (
  `id` varchar(50) NOT NULL,
  `lang` varchar(2) NOT NULL,
  `description` text,
  `motivation` text,
  `video` varchar(256) DEFAULT NULL,
  `about` text,
  `goal` text,
  `related` text,
  `reward` text,
  `keywords` tinytext,
  `media` varchar(255) DEFAULT NULL,
  `subtitle` tinytext,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promote`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Proyectos destacados';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promote_lang`
--

CREATE TABLE `promote_lang` (
  `id` bigint(20) unsigned NOT NULL,
  `lang` varchar(2) NOT NULL,
  `title` tinytext,
  `description` text,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purpose`
--

CREATE TABLE `purpose` (
  `text` varchar(50) NOT NULL,
  `purpose` text NOT NULL,
  `html` tinyint(1) DEFAULT NULL COMMENT 'Si el texto lleva formato html',
  `group` varchar(50) NOT NULL DEFAULT 'general' COMMENT 'Agrupacion de uso',
  PRIMARY KEY (`text`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Explicación del propósito de los textos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `review`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Revision para evaluacion de proyecto';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `review_comment`
--

CREATE TABLE `review_comment` (
  `review` bigint(20) unsigned NOT NULL,
  `user` varchar(50) NOT NULL,
  `section` varchar(50) NOT NULL,
  `evaluation` text,
  `recommendation` text,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`review`,`user`,`section`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Comentarios de revision';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `review_score`
--

CREATE TABLE `review_score` (
  `review` bigint(20) unsigned NOT NULL,
  `user` varchar(50) NOT NULL,
  `criteria` bigint(20) unsigned NOT NULL,
  `score` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`review`,`user`,`criteria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Puntuacion por citerio';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reward`
--

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
  `url` tinytext COMMENT 'Localización del Retorno cumplido',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Retornos colectivos e individuales';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reward_lang`
--

CREATE TABLE `reward_lang` (
  `id` int(20) NOT NULL,
  `lang` varchar(2) NOT NULL,
  `reward` tinytext,
  `description` text,
  `other` tinytext,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role`
--

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

CREATE TABLE `sponsor` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `url` tinytext,
  `image` int(10) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Patrocinadores';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `support`
--

CREATE TABLE `support` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project` varchar(50) NOT NULL,
  `support` tinytext,
  `description` text,
  `type` varchar(50) DEFAULT NULL,
  `thread` bigint(20) unsigned DEFAULT NULL COMMENT 'De la tabla message',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Colaboraciones';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `support_lang`
--

CREATE TABLE `support_lang` (
  `id` int(20) NOT NULL,
  `lang` varchar(2) NOT NULL,
  `support` tinytext,
  `description` text,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tag`
--

CREATE TABLE `tag` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tags de blogs (de nodo)';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tag_lang`
--

CREATE TABLE `tag_lang` (
  `id` bigint(20) unsigned NOT NULL,
  `lang` varchar(2) NOT NULL,
  `name` tinytext,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `task`
--

CREATE TABLE `task` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `node` varchar(50) NOT NULL,
  `text` text NOT NULL,
  `url` tinytext,
  `done` varchar(50) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Tareas pendientes de admin';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `template`
--

CREATE TABLE `template` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `group` varchar(50) NOT NULL DEFAULT 'general' COMMENT 'Agrupación de uso',
  `purpose` tinytext NOT NULL,
  `title` tinytext NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Plantillas emails automáticos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `template_lang`
--

CREATE TABLE `template_lang` (
  `id` bigint(20) unsigned NOT NULL,
  `lang` varchar(2) NOT NULL,
  `title` tinytext,
  `text` text,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `text`
--

CREATE TABLE `text` (
  `id` varchar(50) NOT NULL,
  `lang` varchar(2) NOT NULL DEFAULT 'es',
  `text` text NOT NULL,
  PRIMARY KEY (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Textos multi-idioma';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(60) NOT NULL,
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
  `lang` varchar(2) DEFAULT NULL,
  `node` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `user`
--

INSERT INTO `user` VALUES('root', 'Sysadmin', '', '', '$1$L0HUgg5m$BFHWQWxOD/L3ekMBEIMVU.', '', '', 1, 91, '', '', '', '', '', '', 0, '', '', '', 1, 1, 'es', 'goteo');
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_image`
--

CREATE TABLE `user_image` (
  `user` varchar(50) NOT NULL,
  `image` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user`,`image`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_interest`
--

CREATE TABLE `user_interest` (
  `user` varchar(50) NOT NULL,
  `interest` int(12) NOT NULL,
  UNIQUE KEY `user_interest` (`user`,`interest`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Intereses de los usuarios';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_lang`
--

CREATE TABLE `user_lang` (
  `id` varchar(50) NOT NULL,
  `lang` varchar(2) NOT NULL,
  `about` text,
  `keywords` tinytext,
  `contribution` text,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_login`
--

CREATE TABLE `user_login` (
  `user` varchar(50) NOT NULL,
  `provider` varchar(50) NOT NULL,
  `oauth_token` text NOT NULL,
  `oauth_token_secret` text NOT NULL,
  `datetime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user`,`oauth_token`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_personal`
--

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_prefer`
--

CREATE TABLE `user_prefer` (
  `user` varchar(50) NOT NULL,
  `updates` int(1) NOT NULL DEFAULT '0',
  `threads` int(1) NOT NULL DEFAULT '0',
  `rounds` int(1) NOT NULL DEFAULT '0',
  `mailing` int(1) NOT NULL DEFAULT '0',
  `email` int(1) NOT NULL DEFAULT '0',
  `tips` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Preferencias de notificacion de usuario';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_review`
--

CREATE TABLE `user_review` (
  `user` varchar(50) NOT NULL,
  `review` bigint(20) unsigned NOT NULL,
  `ready` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Ha terminado con la revision',
  PRIMARY KEY (`user`,`review`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Asignacion de revision a usuario';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_role`
--

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

-- --------------------------------------------------------
INSERT INTO `user_role` VALUES('root', 'checker', '*', NULL);
INSERT INTO `user_role` VALUES('root', 'root', '*', NULL);
INSERT INTO `user_role` VALUES('root', 'superadmin', '*', NULL);
INSERT INTO `user_role` VALUES('root', 'translator', '*', NULL);
INSERT INTO `user_role` VALUES('root', 'user', '*', NULL);


--
-- Estructura de tabla para la tabla `user_translang`
--

CREATE TABLE `user_translang` (
  `user` varchar(50) NOT NULL,
  `lang` varchar(2) NOT NULL,
  PRIMARY KEY (`user`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Idiomas de traductores';

-- --------------------------------------------------------

INSERT INTO `user_translang` (`user`, `lang`) VALUES
('root', 'ca'),
('root', 'de'),
('root', 'el'),
('root', 'en'),
('root', 'es'),
('root', 'eu'),
('root', 'fr'),
('root', 'gl'),
('root', 'it'),
('root', 'nl'),
('root', 'pt');

--
-- Estructura de tabla para la tabla `user_translate`
--

CREATE TABLE `user_translate` (
  `user` varchar(50) NOT NULL,
  `type` varchar(10) NOT NULL COMMENT 'Tipo de contenido',
  `item` varchar(50) NOT NULL COMMENT 'id del contenido',
  `ready` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Ha terminado con la traduccion',
  PRIMARY KEY (`user`,`type`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Asignacion de traduccion a usuario';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_web`
--

CREATE TABLE `user_web` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(50) NOT NULL,
  `url` tinytext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Webs de los usuarios';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `worthcracy`
--

CREATE TABLE `worthcracy` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `amount` int(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Niveles de meritocracia';

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

CREATE TABLE `worthcracy_lang` (
  `id` int(2) unsigned NOT NULL,
  `lang` varchar(2) NOT NULL,
  `name` tinytext NOT NULL,
  UNIQUE KEY `id_lang` (`id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
