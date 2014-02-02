-- registro de contenido a enviar, fecha que se inició el envío
CREATE TABLE `mailer_content` (
`id` int(1) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`active` int(1) NOT NULL DEFAULT 1 ,
`mail` int(20) NOT NULL ,
`subject` TEXT NOT NULL,
`content` LONGTEXT NOT NULL,
`datetime` timestamp default CURRENT_TIMESTAMP
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT = 'Contenido a enviar';

-- Tabla para marcar los enviados
CREATE TABLE `mailer_send` (
  `id` SERIAL NOT NULL auto_increment,
  `user` varchar(50) collate utf8_general_ci NOT NULL,
  `email` varchar(256) collate utf8_general_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `datetime` timestamp default CURRENT_TIMESTAMP,
  `sended` int(1) default NULL,
  `error` text collate utf8_general_ci
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT = 'Destinatarios pendientes y realizados';

-- alter
ALTER TABLE `mailer_content` ADD `blocked` INT( 1 ) NULL;

-- para tener una cola de envios
ALTER TABLE `mailer_content` CHANGE `id` `id` INT( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `mailer_send` ADD `mailing` INT( 20 ) UNSIGNED NOT NULL COMMENT 'Id de mailer_content' AFTER `id` ,
ADD INDEX ( `mailing` );

-- blockeo individual
ALTER TABLE `mailer_send` ADD `blocked` INT( 1 ) NULL;

-- para el reply
ALTER TABLE `mailer_content` ADD `reply` VARCHAR( 255 ) NULL DEFAULT NULL COMMENT 'Email remitente',
ADD `reply_name` TEXT NULL DEFAULT NULL COMMENT 'Nombre remitente';

-- tabla para control de envios
CREATE TABLE IF NOT EXISTS `mailer_limit` (
  `date` date NOT NULL COMMENT 'Día ',
  `num` int(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Cuantos',
  PRIMARY KEY (`date`)
) ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT='Para limitar el número de envios diarios';

-- cambio para funcionamiento en 24 horas
ALTER TABLE `mailer_limit` CHANGE `date` `hora` TIME NOT NULL COMMENT 'Hora envio', ADD COLUMN `modified` TIMESTAMP NOT NULL AFTER `num`;

-- tabla de control de envios (blacklist)
CREATE TABLE `mailer_control` (
  `email` char(150) NOT NULL,
  `bounces` int(10) unsigned NOT NULL,
  `complaints` int(10) unsigned NOT NULL,
  `action` enum('allow','deny') DEFAULT 'allow',
  `last_reason` char(255) DEFAULT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT = 'Lista negra para bounces y complaints';
