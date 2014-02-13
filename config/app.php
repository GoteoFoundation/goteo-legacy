<?php

// Nodo actual
define('GOTEO_NODE', 'goteo');

//Upload and cache directory path
define('GOTEO_DATA_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR);

// Language
define('GOTEO_DEFAULT_LANG', 'en');
// name of the gettext .po file (used for admin only texts at the moment)
define('GOTEO_GETTEXT_DOMAIN', 'messages');
// gettext files are cached, to reload a new one requires to restart Apache which is stupid (and annoying while 
//	developing) this setting tells the langueage code to bypass caching by using a clever file-renaming 
// mechanism described in http://blog.ghost3k.net/articles/php/11/gettext-caching-in-php
define('GOTEO_GETTEXT_BYPASS_CACHING', true);

// url
define('SITE_URL', 'http://example.com'); // endpoint url
define('SRC_URL',  'http://example.com');  // host for statics
define('SEC_URL',  'http://example.com');  // with SSL certified

//Sessions
//session handler: php, dynamodb
define("SESSION_HANDLER", "php");

//Files management: s3, file
define("FILE_HANDLER", "file");

//Log file management: s3, file
define("LOG_HANDLER", "file");

// Cron params (for cron processes using wget)
define('CRON_PARAM', '--------------');
define('CRON_VALUE', '--------------');

define('GOTEO_ANALYTICS_TRACKER', 'UA-XXXXX-X');
