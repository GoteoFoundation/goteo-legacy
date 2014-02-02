<?php
/**
* Este es el proceso que va procesando envios masivos
* version linea de comandos
**/

if (PHP_SAPI !== 'cli') {
    die("Acceso solo por linea de comandos!");
}
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
ini_set("display_errors",1);


define("MAIL_MAX_RATE", 14); // envios por segundo máximos
define("MAIL_MAX_CONCURRENCY", 50); //numero máximo de procesos simultaneos para enviar mail (pero no se llegará a esta cifra si el ratio de envios es mayor que MAIL_MAX_RATE)
define("PHP_CLI", "/usr/bin/php"); //ruta al ejecutable PHP
define("LOCK_FILE", sys_get_temp_dir() . '/' . basename(__FILE__) . '.lock');
// set Lang
define('LANG', 'es');

// Comprueba que no se este ejecutando
exec("ps x | grep " . escapeshellarg(escapeshellcmd(basename(__FILE__))) . " | grep -v grep | awk '{ print $1 }'", $commands);

if (count($commands)>1) {
    //echo `ps x`;
    die("Ya existe una copia de " . basename(__FILE__) . " en ejecución!\n");
}

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

// Limite para sender, (deja margen para envios individuales)
$LIMIT = (defined("GOTEO_MAIL_SENDER_QUOTA") ? GOTEO_MAIL_SENDER_QUOTA : 40000);

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

$debug = true;
$fail = false;

// check the limit
if (!Mail::checkLimit(null, false, $LIMIT)) {
    die("LIMIT REACHED\n");
}

$itime = microtime(true);
$total_users = 0;

// cogemos el siguiente envío a tratar
$mailing = Sender::getSending();
// si no está activa fin
if (!$mailing->active) {
    if($debug) echo "INACTIVE\n";
    die;
}
if ($mailing->blocked) {
    if ($debug) echo "dbg: BLOQUEADO!\n";
    $fail = true;
}

// voy a parar aquí, antes del bloqueo
if ($debug) echo "dbg: mailing:\n=====\n".print_r($mailing,1)."\n=====\n";

if (!$fail) {
    if ($debug) echo "dbg: bloqueo este registro\n";
    Model::query('UPDATE mailer_content SET blocked = 1 WHERE id = ?', array($mailing->id));

    // cargamos los destinatarios
    $users = Sender::getRecipients($mailing->id, null); //sin limite de usuarios! los queremos todos, el script va por cli sin limite de tiempo

    $total_users = count($users);

    // si no quedan pendientes, grabamos el feed y desactivamos
    if (empty($users)) {

        if ($debug) echo "dbg: No hay destinatarios\n";

        // Desactivamos
        Model::query('UPDATE mailer_content SET active = 0 WHERE id = ?', array($mailing->id));

        // evento feed
        $log = new Feed();
        $log->populate('Envio masivo (cron)', '/admin/mailing/newsletter', 'Se ha completado el envio masivo con asunto "'.$mailing->subject.'"');
        $log->doAdmin('system');
        unset($log);

        if ($debug) echo 'dbg: Se ha completado el envio masivo '.$mailing->id."\n";
    } else {

        // destinatarios
        if ($debug) echo "dbg: Enviamos a $total_users usuarios \n";

        //limpiar logs
        for($i=0; $i<MAIL_MAX_CONCURRENCY; $i++) {
            @unlink(__DIR__ . "/logs/cli-sendmail-$i.log");
        }

        if ($debug) echo "dbg: Comienza a enviar\n";

        $current_rate = 0;
        $current_concurrency = $increment = 2;

        $i=0;
        while($i<$total_users) {
            // comprueba la quota para los envios que se van a hacer

            if (!Mail::checkLimit(null, false, $LIMIT)) {
                if ($debug) echo "dbg: Se ha alcanzado el límite máximo de ".GOTEO_MAIL_SENDER_QUOTA." de envíos diarios! Lo dejamos para mañana\n";
                $total_users = $i; //para los calculos posteriores
                break;
            }

            $pids = array();
            $stime = microtime(true);

            for($j=0; $j<$current_concurrency; $j++) {

                if($j + $i >= $total_users) break;
                $user = $users[$i + $j];
                //envio delegado
                $cmd = PHP_CLI;
                $config = get_cfg_var('cfg_file_path');
                if($config) $cmd .= " -c $config";

                $log = __DIR__ .  "/logs/cli-sendmail-$j.log";
                //para descartar el mensaje:
                //$log = "/dev/null";
                $cmd .= " -f " . __DIR__ . "/cli-sendmail.php";

                $cmd .= " ".escapeshellarg($user->id);
                $cmd .= " >> $log 2>&1 & echo $!";

                // if($debug) echo "dbg: ejecutando comando:\n$cmd\n";
                $pid = trim(shell_exec($cmd));
                $pids[$pid] = $user->id;
                if($debug) echo "Proceso lanzado con el PID $pid para el envio a {$user->email}\n";

            }

            //Espera a que acaben los procesos de envio antes de continuar
            do {
                //espera un segundo
                sleep(1);
                $check_processes = false;
                $processing = array();
                foreach($pids as $pid => $user_id) {
                    if(check_pid($user_id, $pid)) {
                        $processing[] = $pid;
                        $check_processes = true; //hay un proceso activo, habra que esperar
                    }
                }
                if($processing) {
                    echo "PIDs ".implode(",", $processing). " en proceso, esperamos...\n";
                }
            } while($check_processes);

            $process_time = microtime(true) - $stime;
            $current_rate  = round($j / $process_time,2);

            //No hace falta incrementar la quota de envio pues ya se hace en Mail::Send()
            $rest = Mail::checkLimit(null, true, $LIMIT);
            if($debug) echo "Quota de envío restante para hoy: $rest emails, Quota diaria para mailing: ".GOTEO_MAIL_SENDER_QUOTA."\n";
            if($debug) echo "Envios por segundo: $current_rate - Ratio máximo: ".MAIL_MAX_RATE."\n";

            //aumentamos la concurrencia si el ratio es menor que el 75% de máximo
            if($current_rate < MAIL_MAX_RATE*0.75 && $current_concurrency < MAIL_MAX_CONCURRENCY) {
                $current_concurrency += 2;
                if($debug) echo "Ratio de envio actual menor a un 75% del máximo, aumentamos concurrencia a $current_concurrency\n";
            }

            //disminuimos la concurrencia si llegamos al 90% del ratio máximo
            if($current_rate > MAIL_MAX_RATE*0.9) {
                $wait_time = ceil($current_rate - MAIL_MAX_RATE*0.9);
                $current_concurrency--;
                if($debug) echo "Ratio de envio actual mayor a un 90% del máximo, esperamos $wait_time segundos, disminuimos concurrencia a $current_concurrency\n";
                sleep($wait_time);
            }

            $i += $increment;
            $increment = $current_concurrency;
        } //end while

    }

    if ($debug) echo "dbg: desbloqueo este registro\n";
    Model::query('UPDATE mailer_content SET blocked = 0 WHERE id = ?', array($mailing->id));

} else {
    if ($debug) echo "dbg: FALLO\n";
}

if ($debug) echo "dbg: FIN, tiempo de ejecución total " . round(microtime(true) - $itime, 2) . " segundos para enviar $total_users emails, ratio medio " . round($total_users/(microtime(true) - $itime),2) . " emails/segundo\n";

// limpiamos antiguos procesados
Sender::cleanOld();

/**
 * Comprueba si se está ejecutando un proceso cli-sendmail.php con un pid determinado
 * @param  [type] $pid [description]
 * @return [type]      [description]
 */
function check_pid($args, $pid=null) {
    $filter = escapeshellarg(escapeshellcmd(__DIR__ . "/cli-sendmail.php $args"));
    $order = "ps x | grep $filter | grep -v grep | awk '{ print $1 }'";
    // $order = "pgrep -f $filter";
    $lines = shell_exec($order);
    if($lines) {
        // echo "[$pid] ";print_r($lines);print_r($order);
        if($pid) {
            $lines = array_map('trim', explode("\n", $lines));
            if(in_array($pid, $lines)) {
                return true;
            }
        }
        else {
            return true;
        }
    }
}
