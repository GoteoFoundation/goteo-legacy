CREATE TABLE `mailer_content` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `active` int(1) NOT NULL DEFAULT '1',
  `mail` int(20) NOT NULL,
  `subject` text NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `blocked` int(1) DEFAULT NULL,
  `reply` varchar(255) DEFAULT NULL COMMENT 'Email remitente',
  `reply_name` text COMMENT 'Nombre remitente',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Contenido a enviar';

CREATE TABLE `mailer_control` (
  `email` char(150) NOT NULL,
  `bounces` int(10) unsigned NOT NULL,
  `complaints` int(10) unsigned NOT NULL,
  `action` enum('allow','deny') DEFAULT 'allow',
  `last_reason` char(255) DEFAULT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Lista negra para bounces y complaints';

CREATE TABLE `mailer_limit` (
  `hora` time NOT NULL COMMENT 'Hora envio',
  `num` int(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Cuantos',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`hora`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Para limitar el número de envios diarios';

CREATE TABLE `mailer_send` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mailing` int(20) unsigned NOT NULL COMMENT 'Id de mailer_content',
  `user` varchar(50) NOT NULL,
  `email` varchar(256) NOT NULL,
  `name` varchar(100) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sended` int(1) DEFAULT NULL,
  `error` text,
  `blocked` int(1) DEFAULT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `mailing` (`mailing`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Destinatarios pendientes y realizados';
