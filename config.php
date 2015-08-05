<?php
/*
 *  Copyright (C) 2012 Platoniq y Fundacion Goteo (see README for details)
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
    throw new Exception("Cant add GOTEO_PATH to the include_path.");
}

// Nodo actual
define('GOTEO_NODE', 'goteo');

define('PEAR', GOTEO_PATH . 'library' . '/' . 'pear' . '/');
if (function_exists('ini_set')) {
    ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . PEAR);
} else {
    throw new Exception("Cant add PEAR libraries to the include_path.");
}

/******************************************************
PhpMailer constants
*******************************************************/
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

/******************************************************
OAUTH APP's Secrets
*******************************************************/
if (!defined('OAUTH_LIBS')) {
    define ('OAUTH_LIBS', GOTEO_PATH . 'library' . DIRECTORY_SEPARATOR . 'oauth' . DIRECTORY_SEPARATOR . 'SocialAuth.php');
}

// Uploads and cache
define('GOTEO_DATA_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR);

/**
 * If exists, loads settings for local environment
 * If not, loat settings for live environment
**/
if (file_exists('local-settings.php')) // ignored for local settings
    require 'local-settings.php';
elseif (file_exists('live-settings.php')) // versioned 
    require 'live-settings.php';
else
    die(<<<EOF
Setting file <strong>local-settings.php</strong> is not found, crete this file on the document root.<br />
Use the following code with the correct values for your goteo instance.<br />
<pre>
&lt;?php
// Metadata
define('GOTEO_META_TITLE', '--meta-title--');
define('GOTEO_META_DESCRIPTION', '--meta-description--');
define('GOTEO_META_KEYWORDS', '--keywords--');
define('GOTEO_META_AUTHOR', '--author--');
define('GOTEO_META_COPYRIGHT', '--copyright--');

//Amazon Web Services Credentials
define("AWS_KEY", "--------------");
define("AWS_SECRET", "----------------------------------");
define("AWS_REGION", "-----------");

//Mail management: ses (amazon), phpmailer (php library)
define("MAIL_HANDLER", "phpmailer");

// Database
define('GOTEO_DB_DRIVER', 'mysql');
define('GOTEO_DB_HOST', 'localhost');
define('GOTEO_DB_PORT', 3306);
define('GOTEO_DB_CHARSET', 'UTF-8');
define('GOTEO_DB_SCHEMA', 'db-schema');
define('GOTEO_DB_USERNAME', 'db-username');
define('GOTEO_DB_PASSWORD', 'db-password');

// Mail
define('GOTEO_MAIL_FROM', 'noreply@example.com');
define('GOTEO_MAIL_NAME', 'example.com');
define('GOTEO_MAIL_TYPE', 'smtp'); // mail, sendmail or smtp
define('GOTEO_MAIL_SMTP_AUTH', true);
define('GOTEO_MAIL_SMTP_SECURE', 'ssl');
define('GOTEO_MAIL_SMTP_HOST', 'smtp--host');
define('GOTEO_MAIL_SMTP_PORT', '--portnumber--');
define('GOTEO_MAIL_SMTP_USERNAME', 'smtp-usermail');
define('GOTEO_MAIL_SMTP_PASSWORD', 'smtp-password');

define('GOTEO_MAIL', 'info@example.com');
define('GOTEO_CONTACT_MAIL', 'info@example.com');
define('GOTEO_FAIL_MAIL', 'fail@example.com');
define('GOTEO_LOG_MAIL', 'sitelog@example.com');

/* This is to send mailing by Amazon SES*/
//Quota limit, 24 hours
define('GOTEO_MAIL_QUOTA', 50000);
//Quota limit for newsletters, 24 hours
define('GOTEO_MAIL_SENDER_QUOTA', round(GOTEO_MAIL_QUOTA * 0.8));
// Amazon SNS keys to get bounces automatically: 'arn:aws:sns:us-east-1:XXXXXXXXX:amazon-ses-bounces'
define('AWS_SNS_CLIENT_ID', 'XXXXXXXXX');
define('AWS_SNS_REGION', 'us-east-1');
define('AWS_SNS_BOUNCES_TOPIC', 'amazon-ses-bounces');
define('AWS_SNS_COMPLAINTS_TOPIC', 'amazon-ses-complaints');

/**
 * The locale options are used to define regional settings for
 * things such as language, local legal forms, etc.
 */
&#36;config&#91;'locale'&#93; = array(
    // default interface language
    'default_language' => 'en',
    // root directory of language files (relative to root of Goteo install)
    'gettext_root' => 'locale',
    // name of the gettext .po file (used for admin only texts at the moment)
    'gettext_domain' => 'messages',
    // gettext files are cached, to reload a new one requires to restart Apache which is stupid (and annoying while
    //        developing) this setting tells the langueage code to bypass caching by using a clever file-renaming
    // mechanism described in http://blog.ghost3k.net/articles/php/11/gettext-caching-in-php
    'gettext_bypass_caching' => false,
    // use php implementation (true) or apache module (false)?
    // See this blogpost to understand why using the apache module is not a good idea
    // unles you really know what you are doing
    // http://blog.spinningkid.info/?p=2025
    'gettext_use_php_implementation' => true,

    // Social Security Number (or personal iscal number depending on country)
    'social_number_required' => false, // is this an absolute must?
    'function_validate_social_number' => 'Check::nif', // if it is, which function should we call to validate it? This may take into account local variations

    // VAT validation configuration
    'vat_required' => false, // is it an absolute must?
    'function_validate_vat' => 'Check::vat', // if it is, which function should we call to validate it? This may take into account local variations
);

/**
 * The options array contains configuration settings for optional features
 * such as enhanced privacy.
 */
&#36;config&#91;'options'&#93; = array(
    // avoid all trackers (e.g. facebook, google and all other JS based tracking APIs)
    'enhanced-privacy' => true
);
// Language
define('GOTEO_DEFAULT_LANG', &#36;config&#91;'locale'&#93;&#91;'default_language'&#93;);

// url (this will change on Goteo v3)
define('SITE_URL', 'http://example.com'); // endpoint url
define('SRC_URL', 'http://example.com');  // host for statics
define('SEC_URL', 'http://example.com');  // with SSL certified

//Sessions
//session handler: php, dynamodb
define("SESSION_HANDLER", "php");

//Files management: s3, file
define("FILE_HANDLER", "file");

//Log file management: s3, file
define("LOG_HANDLER", "file");

// environment: local, beta, real
define("GOTEO_ENV", "local");

//S3 bucket (if you set FILE_HANDLER to s3)
define("AWS_S3_BUCKET", "static.example.com");
define("AWS_S3_PREFIX", "");

//bucket para logs (if you set LOG_HANDLER to s3)
define("AWS_S3_LOG_BUCKET", "bucket");
define("AWS_S3_LOG_PREFIX", "applogs/");

// Cron params (for cron processes using wget)
define('CRON_PARAM', '--------------');
define('CRON_VALUE', '--------------');


/****************************************************
Paypal constants (sandbox)
* Must set cretentials on library/paypal/paypal_config.php as well
****************************************************/
define('PAYPAL_REDIRECT_URL', '---Sandbox/Production-url-----https://www.sandbox.paypal.com/webscr&cmd=');
define('PAYPAL_DEVELOPER_PORTAL', '--developper-domain--');
define('PAYPAL_DEVICE_ID', '--domain--');
define('PAYPAL_APPLICATION_ID', '--PayPal-app-Id---');
define('PAYPAL_BUSINESS_ACCOUNT', '--mail-like-paypal-account--');
define('PAYPAL_IP_ADDRESS', '127.0.0.1');

/****************************************************
TPV [Bank Name] (depends on your bank)
****************************************************/
define('TPV_MERCHANT_CODE', 'xxxxxxxxx');
define('TPV_REDIRECT_URL', '--bank-rest-api-url--');
define('TPV_ENCRYPT_KEY', 'xxxxxxxxx');

/*
Any other payment system configuration should be setted here
*/

/****************************************************
Social Services constants  (needed to login-with on the controller/user and library/oauth)
****************************************************/
// Credentials Facebook app
define('OAUTH_FACEBOOK_ID', '-----------------------------------'); //
define('OAUTH_FACEBOOK_SECRET', '-----------------------------------'); //

// Credentials Twitter app
define('OAUTH_TWITTER_ID', '-----------------------------------'); //
define('OAUTH_TWITTER_SECRET', '-----------------------------------'); //

// Credentials Linkedin app
define('OAUTH_LINKEDIN_ID', '-----------------------------------'); //
define('OAUTH_LINKEDIN_SECRET', '-----------------------------------'); //

// Un secreto inventado cualquiera para encriptar los emails que sirven de secreto en openid
define('OAUTH_OPENID_SECRET','-----------------------------------');

// recaptcha ( to be used in /contact form )
define('RECAPTCHA_PUBLIC_KEY','-----------------------------------');
define('RECAPTCHA_PRIVATE_KEY','-----------------------------------');

/****************************************************
Google Analytics
****************************************************/
define('GOTEO_ANALYTICS_TRACKER', "<script type=\"text/javascript\">
__your_tracking_js_code_goes_here___
</script>
");
?&gt;
</pre>
EOF
);

// this is for compatibility with previous version
if (file_exists('tmp-settings.php'))
    require 'tmp-settings.php';
else {
    // Temporary behaviours
    define('DEVGOTEO_LOCAL', false); // backwards compatibility
    define('GOTEO_MAINTENANCE', null); // to show the maintenance page
    define('GOTEO_EASY', null); // to take user overload easy
	define('GOTEO_FREE', true); // used somewhere...
}
