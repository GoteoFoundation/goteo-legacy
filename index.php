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

use Goteo\Core\Resource,
    Goteo\Core\Error,
	Goteo\Core\Registry,
    Goteo\Core\Redirection,
    Goteo\Core\ACL,
    Goteo\Library\Text,
    Goteo\Library\Message,
	Goteo\Library\i18n\Locale,
    Goteo\Library\i18n\Lang;


if( !file_exists("config.php") ) {
	$msg = "This instance of Goteo doesn't seem to be configured, please read the deployment guide, configure and try again.";
	error_log($msg);
	echo "<div id='failure'><h1>{$msg}</h1></div>";
	die;
}

require_once 'config.php';
require_once 'core/common.php';
require_once 'library/i18n/Locale.php';
require_once 'library/i18n/Lang.php';

/*
 * Pagina de en mantenimiento
 */
if (GOTEO_MAINTENANCE === true && $_SERVER['REQUEST_URI'] != '/about/maintenance' 
     && !isset($_POST['Num_operacion'])
    ) {
    header('Location: /about/maintenance');
}

// Include path
//set_include_path(GOTEO_PATH . PATH_SEPARATOR . '.');

// Autoloader
spl_autoload_register(

    function ($cls) {
		//echo "Trying to autoload {$cls}...";
        $file = __DIR__ . '/' . implode('/', explode('\\', strtolower(substr($cls, 6)))) . '.php';
        $file = realpath($file);

        if ($file === false) {

            // Try in library
//            $file = __DIR__ . '/library/' . implode('/', explode('\\', strtolower(substr($cls, 6)))) . '.php';
            $file = __DIR__ . '/library/' . strtolower($cls) . '.php';
//            die($cls . ' - ' . $file); //Si uso Text::get(id) no lo pilla
        }

        if ($file !== false) {
			//echo "Autoloading {$file}...";
            include $file;
        }

    }

);

// Error handler
set_error_handler (

    function ($errno, $errstr, $errfile, $errline, $errcontext) {
        // @todo Insert error into buffer
//        echo "Error:  {$errno}, {$errstr}, {$errfile}, {$errline}, {$errcontext}<br />";
        //throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

);

//@NODESYS
    define('NODE_ID', GOTEO_NODE);
/**
 * Sesión.
 */
session_name('goteo');
session_start();

$config = array('locale' => array(
    'gettext_root' => dirname(__FILE__).'/locale',
    'gettext_domain' => GOTEO_GETTEXT_DOMAIN,
    'gettext_bypass_caching' => GOTEO_GETTEXT_BYPASS_CACHING,
));

// set Lang
Lang::set();
// change current locale
$locale_name = Lang::locale();
$locale = new Locale($config['locale']);
// avoid Fatal Error if $local_name is empty.
if ($locale_name)
	$locale->set($locale_name);
else
	$locale->set('en_GB');
Registry::set('locale', $locale);


// Get URI without query string
$uri = strtok($_SERVER['REQUEST_URI'], '?');

// Get requested segments
$segments = preg_split('!\s*/+\s*!', $uri, -1, \PREG_SPLIT_NO_EMPTY);

// Normalize URI
$uri = '/' . implode('/', $segments);

try {
    // Check permissions on requested URI
    if (!ACL::check($uri)) {
        Message::Info(Text::get('user-login-required-access'));

        //si es un cron (ejecutandose) con los parámetros adecuados, no redireccionamos
        if (strpos($uri, 'cron') !== false && strcmp($_GET[md5(CRON_PARAM)], md5(CRON_VALUE)) === 0) {
            define('CRON_EXEC', true);
        } else {
            throw new Redirection("/user/login/?return=".rawurlencode($uri));
        }
    }

    // Get controller name
    if (!empty($segments) && class_exists("Goteo\\Controller\\{$segments[0]}")) {
        // Take first segment as controller
        $controller = array_shift($segments);
    } else {
        $controller = 'index';
    }

    // Continue
    try {

        $class = new ReflectionClass("Goteo\\Controller\\{$controller}");

        if (!empty($segments) && $class->hasMethod($segments[0])) {
            $method = array_shift($segments);
        } else {
            // Try default method
            $method = 'index';
        }

        // ReflectionMethod
        $method = $class->getMethod($method);

        // Number of params defined in method
        $numParams = $method->getNumberOfParameters();
        // Number of required params
        $reqParams = $method->getNumberOfRequiredParameters();
        // Given params
        $gvnParams = count($segments);

        if ($gvnParams >= $reqParams && (!($gvnParams > $numParams && $numParams <= $reqParams))) {

            // Try to instantiate
            $instance = $class->newInstance();

            // Start output buffer
            ob_start();

            // Invoke method
            $result = $method->invokeArgs($instance, $segments);

            if ($result === null) {
                // Get buffer contents
                $result = ob_get_contents();
            }

            ob_end_clean();

            if ($result instanceof Resource\MIME) {
                header("Content-type: {$result->getMIME()}");
            }

            echo $result;

            // Farewell
            die;

        }

    } catch (\ReflectionException $e) {}

    throw new Error(Error::NOT_FOUND);

} catch (Redirection $redirection) {
    $url = $redirection->getURL();
    $code = $redirection->getCode();
    header("Location: {$url}");

} catch (Error $error) {

    include "view/error.html.php";

} catch (Exception $exception) {

    // Default error (500)
    include "view/error.html.php";
}
