<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
ini_set("display_errors",1);

//system timezone
date_default_timezone_set("Europe/Madrid");

use Goteo\Core\Resource,
    Goteo\Core\Error,
    Goteo\Core\Redirection,
    Goteo\Core\Model,
    Goteo\Library\Mail,
    Goteo\Library\AmazonSns;

require_once 'config.php';
require_once 'core/common.php';
// Autoloader
spl_autoload_register(

    function ($cls) {

        $file = __DIR__ . '/' . implode('/', explode('\\', strtolower(substr($cls, 6)))) . '.php';
        $file = realpath($file);

        if ($file === false) {

            // Try in library
            $file = __DIR__ . '/library/' . strtolower($cls) . '.php';
        }

        if ($file !== false) {
            include $file;
        }

    }

);

try {

    $contents = file_get_contents('php://input');
    file_put_contents("logs/aws-sns-input.log", $contents);

    if (!$contents)
        throw new Exception('No se ha recibido información');

    $contentsJson = json_decode($contents);

    if (!$contentsJson)
        throw new Exception('La entrada no tiene un código JSON válido');

    if (!AmazonSns::verify($contentsJson, AWS_SNS_CLIENT_ID, AWS_SNS_REGION, array(AWS_SNS_BOUNCES_TOPIC, AWS_SNS_COMPLAINTS_TOPIC)))
        throw new Exception('Petición incorrecta');

    if ($contentsJson->Type == 'SubscriptionConfirmation') {
        //suscribimos (esto solo debe pasar cuando se configura una nueva URL de notificacion)
        file_get_contents($contentsJson->SubscribeURL);
    }
    elseif ($contentsJson->Type == 'Notification') {
        $msg = json_decode($contentsJson->Message);
        //Si es un bounce, lo añadimos, pero solo bloqueamos si es permanente
        if($msg->notificationType == 'Bounce') {
            foreach($msg->bounce->bouncedRecipients as $ob) {
                $block = false;
                if($msg->bounce->bounceType == 'Permanent') $block = true;
                Mail::addBounce($ob->emailAddress, $ob->diagnosticCode, $block);
            }
        }
        //si es un complaint, añadimos y bloqueamos
        if($msg->notificationType == 'Complaint') {
            foreach($msg->complaint->complainedRecipients as $ob) {
                Mail::addComplaint($ob->emailAddress, $msg->complaint->complaintFeedbackType);
            }
        }
    }
}
catch (Exception $e) {
    file_put_contents("logs/aws-sns-errors.log",$e->getMessage());
}
