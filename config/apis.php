<?php

/******************************************************
OAUTH APP's Secrets
*******************************************************/
if (!defined('OAUTH_LIBS')) {
    define ('OAUTH_LIBS', GOTEO_PATH . 'library' . DIRECTORY_SEPARATOR . 'oauth' . DIRECTORY_SEPARATOR . 'SocialAuth.php');
}

//Amazon Web Services Credentials
define("AWS_KEY", "--------------");
define("AWS_SECRET", "----------------------------------");
define("AWS_REGION", "-----------");

//clave de Amazon SNS para recopilar bounces automaticamente: 'arn:aws:sns:us-east-1:XXXXXXXXX:amazon-ses-bounces'
//la URL de informacion debe ser: goteo_url.tld/aws-sns.php
define('AWS_SNS_CLIENT_ID', 'XXXXXXXXX');
define('AWS_SNS_REGION', 'us-east-1');
define('AWS_SNS_BOUNCES_TOPIC', 'amazon-ses-bounces');
define('AWS_SNS_COMPLAINTS_TOPIC', 'amazon-ses-complaints');


//S3 bucket (if you set FILE_HANDLER to s3)
define("AWS_S3_BUCKET", "static.example.com");
define("AWS_S3_PREFIX", "");

//bucket para logs (if you set LOG_HANDLER to s3)
define("AWS_S3_LOG_BUCKET", "bucket");
define("AWS_S3_LOG_PREFIX", "applogs/");


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
