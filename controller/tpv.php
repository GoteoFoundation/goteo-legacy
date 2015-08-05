<?php
/*
 *  Copyright (C) 2012 Platoniq y Fundación Goteo (see README for details)
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

namespace Goteo\Controller {

    use Goteo\Model\Invest,
        Goteo\Model\Project,
        Goteo\Model\User,
        Goteo\Core\Error,
		Goteo\Library\Feed,
		Goteo\Library\Text,
        Goteo\Core\Redirection;

    require_once 'library/paypal/stub.php'; // sí, uso el stub del SDK paypal
    require_once 'library/paypal/log.php'; // sí, uso el log del SDK paypal
    
    class Tpv extends \Goteo\Core\Controller {

		/* Kept as example */
        public static $errcode = array(
            '0' => 'Operación aprobada',
            '1' => 'COMUNICACION ON-LINE INCORRECTA',
            '2' => 'ERROR AL CALCULAR FIRMA',
            '5' => 'ERROR. Error en el SELECT COMERCIOS <%d>',
            '6' => 'ERROR. Faltan campos obligatorios',
            '7' => 'ERROR. MerchantID inexistente <%d>',
            '9' => 'ERROR. No se pudo conectar a ORACLE <%d>',
            '10' => 'ERROR. Tarjeta errónea',
            '12' => 'FIRMA: %s-%s',
            '13' => 'OPERACION INCORRECTA',
            '14' => 'ERROR. Error en el SELECT OPERACIONES <%d>',
            '15' => 'ERROR. Operación inexistente <%d>',
            '16' => 'ERROR. Operación ya anulada <%d>',
            '17' => 'ERROR AL OBTENER CLAVE',
            '18' => 'ERROR. El ETILL no acepta el pedido',
            '19' => 'ERROR. Datos no numéricos',
            '20' => 'ERROR. Datos no alfa-numéricos',
            '21' => 'ERROR en el calculo del MAC',
            '22' => 'ERROR en el calculo del MAC [%s - %s][cadena:%s]',
            '23' => 'ERROR. Usuario o password no valido.',
            '24' => 'ERROR. Tipo de moneda no valido. La operación debe realizarse en Euros.',
            '25' => 'ERROR. Importe no Integer.',
            '26' => 'ERROR. Operación no realizable 100.',
            '27' => 'ERROR. Formato CVV2/CVC2 no valido.',
            '28' => 'ERROR. Debe especificar el CVV2/CVC2 de su tarjeta.',
            '29' => 'ERROR. CVV2 no Integer.',
            '30' => 'ERROR. En estos momentos no es posible continuar sin cvc2/cvv2',
            '31' => 'ERROR. ERROR en la operatoria del comercio.',
            '32' => 'ERROR. Tipo de moneda no valido. La operación debe realizarse en Euros.',
            '33' => 'ERROR. El comercio solo puede realizar pagos en Euros',
            '34' => 'ERROR. Moneda o conversión no valida para esta tarjeta.[%d]',
            '35' => 'ERROR. Moneda o conversión no valida.[%d]',
            '36' => 'ERROR. Conversión a Euros no válida [%s][%s].',
            '37' => 'ERROR. El comercio no dispone de esta opción.',
            '38' => 'ERROR. Respuesta Errónea del Gestor de operaciones. [%d][%s].',
            '39' => 'ERROR. No es posible continuar con la preautorizacion.',
            '40' => 'ERROR. Error de comunicaciones Lu´s. No es posible finalizar la operación.',
            '41' => 'ERROR. TimeOut SEP. No es posible finalizar la operación.',
            '42' => 'ERROR. SEP devuelve un 20 ERROR. No es posible finalizar la operación.',
            '43' => 'ERROR. Error inesperado. No es posible finalizar la operación [%d].',
            '44' => 'ERROR. Respuesta Errónea de SEP. No es posible finalizar la operación.',
            '45' => 'ERROR. No es posible continuar con la preautorización.',
            '46' => 'ERROR. Error en el proceso de Autentificación. No retroceda en el navegador. Debe volver al comercio y reintentar el pago.',
            '47' => 'ERROR. Entidad no disponible. Inténtelo dentro de unos minutos',
            '48' => 'ERROR. Error en el proceso de Autentificación. Respuesta PAREQ no valida [%d]. No retroceda en el navegador. Debe volver al comercio y reintentar el pago.',
            '49' => 'ERROR. Error en el proceso de Autentificación. Respuesta PAREQ de su entidad no valida: %s,TXSTATUS',
            '50' => 'ERROR. Fallo en el proceso de Autentificación. Es necesario una identificación positiva para finalizar el proceso de compra: %s,TXSTATUS',
            '51' => 'ERROR. Fallo en el proceso de Autentificación. El comercio no acepta pagos no seguros: %s. Póngase en contacto con la entidad emisora de su tarjeta.,TXSTATUS',
            '52' => 'ERROR. En estos momentos no es posible iniciar un pago seguro',
            '53' => 'ERROR. Comercio seguro. Su tarjeta no admite autentificación y no puede operar en este comercio [%s]. Póngase en contacto con la entidad emisora de su tarjeta.',
            '54' => 'ERROR. No es posible iniciar un pago seguro y el importe supera el máximo permitido (%f <= %s). [Resultado: %s]',
            '55' => 'ERROR. En este momento no es posible iniciar un pago seguro. [Resultado: %s]',
            '56' => 'ERROR. No es posible iniciar un pago seguro y el importe supera el máximo permitido (%f <= %s). [Resultado: %s]',
            '57' => 'ERROR. En este momento no es posible iniciar un pago seguro y el importe supera el máximo permitido (%f <= %s). [Resultado: %s]',
            '58' => 'ERROR. En este momento no es posible iniciar un pago seguro. [Resultado: %s]',
            '59' => 'ERROR. El comercio tiene un filtro que no permite esta operación.',
            '60' => 'ERROR. El Comercio solo admite pago seguro. Necesita autentificarse para continuar.',
            '61' => 'ERROR. Operación segura no permitida. Importe (%14.2f) mayor del limite establecido (%14.2f).',
            '62' => 'ERROR. El comercio tiene un filtro que no permite esta operación.(Filtro2:%d)',
            '63' => 'ERROR. El comercio no acepta pagos Visa no autentificados. Póngase en contacto con su entidad para activar este tipo de pago.',
            '64' => 'ERROR. El comercio no acepta pagos MasterCard no autentificado. Póngase en contacto con su entidad para activar este tipo de pago.',
            '65' => 'ERROR. El comercio no acepta pagos no autentificados. Póngase en contacto con su entidad para activar este tipo de pago.',
            '66' => 'ERROR. Error de proceso. El comercio no acepta pagos no autentificados. Póngase en contacto con su entidad para activar este tipo de pago.',
            '67' => 'ERROR. Operación segura no autorizada. Importe (%14.2f) mayor del limite establecido (%14.2f).',
            '68' => 'ERROR. Respuesta Errónea del Gestor de operaciones. Operación anulada [%s].Gestor: [%d][%s].',
            '69' => 'ERROR. Operatoria UCAF no valida. Póngase en contacto con su comercio o caja.',
            '100' => 'Tarjeta no válida (en negativos)',
            '101' => 'Tarjeta caducada',
            '104' => 'Tarjeta no válida (electrón)',
            '106' => 'Tarjeta no válida (reintentos de PIN)',
            '111' => 'Número de tarjeta mal tecleado (check)',
            '112' => 'Tarjeta no válida (se exige PIN)',
            '114' => 'No admitida la forma de pago solicitada',
            '116' => 'Saldo insuficiente',
            '118' => 'Tarjeta no válida (no existente en ficheros)',
            '120' => 'Tarjeta no válida en este comercio',
            '121' => 'Disponible sobrepasado',
            '123' => 'Número máximo de operaciones superado',
            '125' => 'La tarjeta todavía no es operativa',
            '180' => 'Tarjeta no soportada por el sistema',
            '190' => 'Operación no realizable (resto de casos)',
            '400' => 'Anulación aceptada',
            '480' => 'Anulación por TO aceptada sin encontrar la operación original',
            '900' => 'Devolución aceptada',
            '904' => 'Operación no realizable (error de formato)',
            '908' => 'Tarjeta desconocida',
            '909' => 'Operación no realizable (error de sistema)',
            '912' => 'Su entidad no está disponible',
            '913' => 'Operación no realizable (clave duplicada)',
            '914' => 'No existe la operación a anular',
            '930' => 'Operación no realizable (caja merchant no válida)',
            '931' => 'Operación no realizable (comercio no dado de alta)',
            '932' => 'Operación no realizable (bin merchant no existe)',
            '933' => 'Operación no realizable (sector desconocido)',
            '940' => 'Ya recibida una anulación',
            '944' => 'Operación no realizable (sesión no válida)',
            '948' => 'Operación no realizable (fecha/hora inválida)',
            '950' => 'Devolución no aceptada',
            '999' => 'Operación no realizable (resto de casos)'
        );

        public function index () {
            throw new Redirection('/', Error::BAD_REQUEST);
        }
        
		/* Tratamiento de comunicaciones B2B */
        public function comunication () {
            @mail(\GOTEO_FAIL_MAIL, 'Comunicacion online', 'Este GET<pre>' . print_r($_GET, 1) . '</pre> y este POST:<pre>' . print_r($_POST, 1) . '</pre>');
            
            if (isset($_POST['__expected_field___'])) {
                $_POST['invest'] = $id = $_POST['__expected_field___'];
                
                $invest = Invest::get($id);

                $userData = User::getMini($invest->user);
                $projectData = Project::getMini($invest->project);

                $response = '';
                foreach ($_POST as $n => $v) {
                    $response .= "{$n}:'{$v}'; ";
                }

                $conf = array('mode' => 0600, 'timeFormat' => '%X %x');
                $logger = &\Log::singleton('file', 'logs/'.date('Ymd').'_invest.log', 'caller', $conf);

                $logger->log("response: $response");
                $logger->log('##### END TPV ['.$id.'] '.date('d/m/Y').' '.$_POST['__expected_field___'].'#####');
                $logger->close();


                if (!empty($_POST['__expected_field___'])) {

                    try {
                        $tpvRef = $_POST['__expected_field___'];
                        $tpvAut = $_POST['__expected_field___'];

                        $values = array(
                            ':id' => $invest->id,
                            ':payment' => $tpvRef,
                            ':transaction' => $tpvAut,
                            ':charged' => date('Y-m-d')
                        );

                        $sql = "UPDATE  invest
                                SET
                                    status = 1,
                                    payment = :payment,
                                    charged = :charged,
                                    transaction = :transaction
                                WHERE id = :id";
                        if (Invest::query($sql, $values)) {
                            Invest::setDetail($invest->id, 'tpv-response', 'La comunicación online del tpv se a completado correctamente. Proceso controller/tpv');
                        } else {
                            @mail(\GOTEO_FAIL_MAIL, 'Error db en comunicacion online', 'En la grabación de referencia, num auth. y estado. Ha fallado: '.$sql.' '.print_r($values, 1).'<hr /><pre>' . print_r($invest, 1) . '</pre>');
                        }

                        // CALLSYS
						
                    } catch (\PDOException $e) {
                        @mail(\GOTEO_FAIL_MAIL, 'Error db en comunicacion online', 'En la grabación de referencia, num auth. y estado. Ha dado un PDO::Exception<br /><pre>' . print_r($invest, 1) . '</pre>');
                    }
                    $_POST['result'] = 'Transaccion ok';

                    $log_text = "%s ha aportado %s al proyecto %s mediante TPV";
                    $doPublic = true;

                    echo '$*$OKY$*$';
                } else {

                    $Cerr = (string) $_POST['Codigo_error'];
                    $errTxt = self::$errcode[$Cerr];
                    Invest::setDetail($invest->id, 'tpv-response-error', 'El tpv ha comunicado el siguiente Codigo error: '.$Cerr.' - '.$errTxt.'. El aporte a quedado \'En proceso\'. Proceso controller/tpv');
                    @mail(\GOTEO_FAIL_MAIL, 'Error en TPV', 'Codigo error: '.$Cerr.' '.$errTxt.'<br /><pre>' . print_r($_POST, 1) . '</pre>');
                    $invest->cancel('ERR '.$Cerr);
                    $_POST['result'] = 'Fail';

                    $log_text = 'Ha habido un <span class="red">ERROR de TPV (Codigo: '.$Cerr.' '.$errTxt.')</span> en el aporte de %s de %s al proyecto %s mediante TPV';
                    $doPublic = false;
                }

                // Evento Feed
                $log = new Feed();
                $log->setTarget($projectData->id);
                $log->populate('Aporte TPV', '/admin/invests',
                    \vsprintf($log_text, $log_items = array(
                        Feed::item('user', $userData->name, $userData->id),
                        Feed::item('money', $invest->amount.' &euro;'),
                        Feed::item('project', $projectData->name, $projectData->id))
                    ));
                $log->doAdmin('money');

                if ($doPublic) {
                    // evento público
                    $log_html = Text::html('feed-invest',
                                        Feed::item('money', $invest->amount.' &euro;'),
                                        Feed::item('project', $projectData->name, $projectData->id));
                    if ($invest->anonymous) {
                        $log->populate(Text::get('regular-anonymous'), '/user/profile/anonymous', $log_html, 1);
                    } else {
                        $log->populate($userData->name, '/user/profile/'.$userData->id, $log_html, $userData->avatar->id);
                    }
                    $log->doPublic('community');
                }
                unset($log);
            } else {
                echo 'Se esperaban recibir datos de comunicación online del TPV.';
                @mail(\GOTEO_FAIL_MAIL, 'Comunicacion online sin datos', 'Este GET<pre>' . print_r($_GET, 1) . '</pre> y este POST:<pre>' . print_r($_POST, 1) . '</pre>');
//                throw new Redirection('/', Error::BAD_REQUEST);
            }

            die;
        }

        public function simulacrum () {
            echo 'Simulacrum<br />';
            @mail(\GOTEO_FAIL_MAIL, 'Test request', 'Recibido Este GET<pre>' . print_r($_GET, 1) . '</pre> y este POST:<pre>' . print_r($_POST, 1) . '</pre>');
            die;
        }

    }
    
}