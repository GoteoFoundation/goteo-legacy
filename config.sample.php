<?php
/*
 *  Copyright (C) 2012 Platoniq y Fundación Fuentes Abiertas (see README for details)
 *	This file is part of Goteo.
 *
 *  Goteo is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  Goteo is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with Goteo.  If not, see <http://www.gnu.org/licenses/agpl.txt>.
 *
 */

define('GOTEO_PATH', __DIR__ . DIRECTORY_SEPARATOR);
if (function_exists('ini_set')) {
    ini_set('include_path', GOTEO_PATH . PATH_SEPARATOR . '.');
} else {
    throw new Exception("No puedo añadir la API GOTEO al include_path.");
}

// Nodo actual
define('GOTEO_NODE', 'goteo');

define('PEAR', GOTEO_PATH . 'library' . '/' . 'pear' . '/');
if (function_exists('ini_set')) {
    ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . PEAR);
} else {
    throw new Exception("No puedo añadir las librerías PEAR al include_path.");
}

if (!defined('PHPMAILER_CLASS')) {
    define ('PHPMAILER_CLASS', GOTEO_PATH . 'library' . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'class.phpmailer.php');
}
if (!defined('PHPMAILER_LANGS')) {
    define ('PHPMAILER_LANGS', GOTEO_PATH . 'library' . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR);
}
if (!defined('PHPMAILER_SMTP')) {
    define ('PHPMAILER_SMTP', GOTEO_PATH . 'library' . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'class.smtp.php');
}
if (!defined('PHPMAILER_POP3')) {
    define ('PHPMAILER_POP3', GOTEO_PATH . 'library' . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'class.pop3.php');
}

// Metadata
define('GOTEO_META_TITLE', 'Goteo.org  Crowdfunding the commons');
define('GOTEO_META_DESCRIPTION', 'Red social de financiacion colectiva');
define('GOTEO_META_KEYWORDS', 'crowdfunding, procomun, commons, social, network, financiacion colectiva, cultural, creative commons, proyectos abiertos, open source, free software, licencias libres');
define('GOTEO_META_AUTHOR', 'Onliners Web Development');
define('GOTEO_META_COPYRIGHT', 'Fundación Fuentes Abiertas');

// Database
define('GOTEO_DB_DRIVER', 'mysql');
define('GOTEO_DB_HOST', 'localhost');
define('GOTEO_DB_PORT', 3306);
define('GOTEO_DB_CHARSET', 'UTF-8');
define('GOTEO_DB_SCHEMA', 'goteo');
define('GOTEO_DB_USERNAME', 'root');
define('GOTEO_DB_PASSWORD', 'root');

//Uploads i catxe
define('GOTEO_DATA_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR);

// Mail
define('GOTEO_MAIL_FROM', 'noreply@goteo.org');
define('GOTEO_MAIL_NAME', 'Goteo.org');
define('GOTEO_MAIL_TYPE', 'mail');
define('GOTEO_MAIL_SMTP_AUTH', true);
define('GOTEO_MAIL_SMTP_SECURE', 'ssl');
define('GOTEO_MAIL_SMTP_HOST', '');
define('GOTEO_MAIL_SMTP_PORT', 465);
define('GOTEO_MAIL_SMTP_USERNAME', '');
define('GOTEO_MAIL_SMTP_PASSWORD', '');

define('GOTEO_MAIL', 'hola@goteo.org');

$config['locale'] = array(
	// default interface language
	'default_language' => 'en',
	// root directory of language files (relative to root of Goteo install)
	'gettext_root' => 'locale',
	// name of the gettext .po file (used for admin only texts at the moment)
	'gettext_domain' => 'messages',
	// gettext files are cached, to reload a new one requires to restart Apache which is stupid (and annoying while
	//	developing) this setting tells the langueage code to bypass caching by using a clever file-renaming
	// mechanism described in http://blog.ghost3k.net/articles/php/11/gettext-caching-in-php
	'gettext_bypass_caching' => false,
	// use php implementation (true) or apache module (false)?
	// See this blogpost to understand why using the apache module is not a good idea
	// unles you really know what you are doing
	// http://blog.spinningkid.info/?p=2025
	'gettext_use_php_implementation' => true
);

// Language
define('GOTEO_DEFAULT_LANG', $config['locale']['default_language']);

// url
define('SITE_URL', 'http://localhost:8080/');
define('SRC_URL', 'http://localhost:8080/');

// Cron params
define('CRON_PARAM', '');
define('CRON_VALUE', '');

// Código liberado
define('GOTEO_FREE', true);
