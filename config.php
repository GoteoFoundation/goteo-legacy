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

define('PEAR', GOTEO_PATH . 'library' . '/' . 'pear' . '/');
if (function_exists('ini_set')) {
    ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . PEAR);
} else {
    throw new Exception("No puedo añadir las librerías PEAR al include_path.");
}

// environment: local, beta, real
define("GOTEO_ENV", "local");

$configPath = __DIR__.'/config/';

/**
 * Load config files spe
 */
switch ($_SERVER['SERVER_NAME']) {
    // Local envirnonment config files
    case 'localhost':
    case 'local.goteo.com':
        $envPath = __DIR__.'/config/local/';
        break;

    // Live envirnoment config files
    case 'goteo.com':
    case 'www.goteo.com':
    default:
        $envPath = __DIR__.'/config/';
        break;
}

$configFiles = ['app', 'mail', 'database', 'apis', 'meta'];
foreach ($configFiles as $configFile) {
    if (file_exists($envPath.$configFile.'.php')) {
        require $envPath.$configFile.'.php';
    } else {
        require $configPath.$configFile.'.php';
    }
}

if (file_exists('tmp-settings.php'))
    require 'tmp-settings.php';
else {
    // Temporary behaviours
    define('DEVGOTEO_LOCAL', false); // backwards compatibility
    define('GOTEO_MAINTENANCE', null); // to show the maintenance page
    define('GOTEO_EASY', null); // to take user overload easy
	define('GOTEO_FREE', true); // used somewhere...
}
