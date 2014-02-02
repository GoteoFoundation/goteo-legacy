<?php
/**
* Este es el proceso que envia un email al usuario especificado
* version linea de comandos
**/
if (PHP_SAPI !== 'cli') {
    die("Acceso solo por linea de comandos!");
}
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
ini_set("display_errors",1);
//system timezone
date_default_timezone_set("Europe/Madrid");

use Goteo\Core\Resource,
    Goteo\Core\Error,
    Goteo\Core\Redirection,
    Goteo\Core\Model,
    Goteo\Library\Feed,
    Goteo\Library\Mail,
    Goteo\Library\Sender;

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


// set Lang
define('LANG', 'es');

$debug = true;

$id = $argv[1];
if(empty($id)) {
	die("Se necesita un identificador de sender como argumento del script!");
}

$list = array();

$sql = "SELECT
        mailer_send.id,
        mailer_send.user,
        mailer_send.name,
        mailer_send.email,
        mailer_content.id as mailing_id
    FROM mailer_send
    RIGHT JOIN mailer_content ON mailer_content.id=mailer_send.mailing AND mailer_content.active=1
    WHERE mailer_send.id = ?
    AND mailer_send.sended IS NULL
    AND mailer_send.blocked IS NULL
    ";

if ($query = Model::query($sql, array($id))) {
	$user = $query->fetchObject();
}
if(!is_object($user)) {
	die("No se ha encontrado un usuario válido para el mailer_send.id=$id\n");
}

//si estamos aqui sabemos que el usuari es valido i el mailing tambien
if($debug) echo "dbg: Fecha inicio " .date("Y-m-d H:i:s"). "\n";
// cogemos el siguiente envío a tratar

$mailing = Sender::getSending($user->mailing_id);

// print_r($mailing);
// si no está activa fin
if (!$mailing->active) {
    die("Mailing {$user->mailing_id} inactivo!\n");
}

// cogemos el contenido y la plantilla desde el historial
$query = Model::query('SELECT html, template FROM mail WHERE id = ?', array($mailing->mail));
$data = $query->fetch(\PDO::FETCH_ASSOC);
$content = $data['html'];
$template = $data['template'];
if (empty($content)) {
    die("Mailing {$user->mailing_id} sin contenido!\n");
}

if($debug) echo "dbg: Bloqueando registro {$user->id} ({$user->email}) mailing: {$user->mailing_id}\n";

//bloquear usuario
Model::query("UPDATE mailer_send SET blocked = 1 WHERE id = '{$user->id}' AND mailing =  '{$user->mailing_id}'");

//enviar email
$itime = microtime(true);
try {
    $mailHandler = new Mail($debug);

    // reply, si es especial
    if (!empty($mailing->reply)) {
        $mailHandler->reply = $mailing->reply;
        if (!empty($mailing->reply_name)) {
            $mailHandler->replyName = $mailing->reply_name;
        }
    }

    $mailHandler->to = \trim($user->email);
    $mailHandler->toName = $user->name;
    $mailHandler->subject = $mailing->subject;
    $mailHandler->content = str_replace(
        array('%USERID%', '%USEREMAIL%', '%USERNAME%'),
        array($user->user, $user->email, $user->name),
        $content);
    $mailHandler->html = true;
    $mailHandler->template = $template;
    $mailHandler->massive = true;

    $errors = array();

    if ($mailHandler->send($errors)) {

        // Envio correcto
        Model::query("UPDATE mailer_send SET sended = 1, datetime = NOW() WHERE id = '{$user->id}' AND mailing =  '{$user->mailing_id}'");
        if ($debug) echo "dbg: Enviado OK a $user->email\n";

    } else {

        // falló al enviar
        $sql = "UPDATE mailer_send
        SET sended = 0 , error = ? , datetime = NOW()
        WHERE id = '{$user->id}' AND mailing =  '{$user->mailing_id}'
        ";
        Model::query($sql, array(implode(',', $errors)));
        if ($debug) echo "dbg: Fallo ERROR a {$user->email} ".implode(',', $errors)."\n";
    }

    unset($mailHandler);

    // tiempo de ejecución
    $now = (microtime(true) - $itime);
    if ($debug) echo "dbg: Tiempo de envio: $now segundos\n";


} catch (phpmailerException $e) {
    die ($e->errorMessage());
}

//desbloquear usuario
if($debug) echo "dbg: Desbloqueando registro {$user->id} ({$user->email}) mailing: {$user->mailing_id}\n";
Model::query("UPDATE mailer_send SET blocked = NULL WHERE id = '{$user->id}' AND mailing =  '{$user->mailing_id}'");


