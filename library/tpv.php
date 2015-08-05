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

namespace Goteo\Library {

    use Goteo\Model\Invest,
        Goteo\Model\Project,
        Goteo\Core\Redirection;

// this library depends on bank system. Contact us for development services or make it work somehow.
	/*
	 * Clase para usar la pasarela de pago
	 */
    class Tpv {

        /*
         * para ceca no hay preapproval, es el cargo directamente
         */
        public static function preapproval($invest, &$errors = array()) {
            return static::pay($invest, $errors);
        }

        public static function pay($invest, &$errors = array()) {
            if (\GOTEO_FREE) {
                $errors[] = 'Bank not implemented. Contact us for development services or make it work somehow';
                return false;
            }


			try {
                $project = Project::getMini($invest->project);

                // preparo codigo y cantidad
                $token  = $invest->id . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9);
                $amount = $invest->amount * 100;

                // Guardar el codigo de preaproval en el registro de aporte (para confirmar o cancelar)
                $invest->setPreapproval($token);
                // mandarlo al tpv
                $urlTPV = TPV_REDIRECT_URL;
                $data = '';
                $MsgStr = '';
                foreach ($datos as $n => $v) {
                    $data .= '<input name="'.$n.'" type="hidden" value="'.$v.'" />';
                    $MsgStr .= "{$n}:'{$v}'; ";
                }

                $conf = array('mode' => 0600, 'timeFormat' => '%X %x');
                $logger = &\Log::singleton('file', 'logs/'.date('Ymd').'_invest.log', 'caller', $conf);

                $logger->log('##### TPV ['.$invest->id.'] '.date('d/m/Y').' User:'.$_SESSION['user']->id.'#####');

                $logger->log("Charge request: $MsgStr");
                $logger->close();

                Invest::setDetail($invest->id, 'tpv-conection', 'Ha iniciado la comunicacion con el tpv, operacion numero ' . $token . '. Proceso libary/tpv::pay');

                echo '<html><head><title>Goteo.org</title></head><body><form action="'.$urlTPV.'" method="post" id="form_tpv" enctype="application/x-www-form-urlencoded">'.$data.'</form><script type="text/javascript">document.getElementById("form_tpv").submit();</script></body></html>';
                return true;
			}
			catch(Exception $ex) {
                Invest::setDetail($invest->id, 'tpv-conection-fail', 'Ha fallado la comunicacion con el tpv. Proceso libary/tpv::pay');
                $errors[] = 'Error fatal en la comunicacion con el TPV, se ha reportado la incidencia. Disculpe las molestias.';
                @mail(\GOTEO_FAIL_MAIL, 'Error fatal en comunicacion TPV Sermepa', 'ERROR en ' . __FUNCTION__ . '<br />' . $ex->getMessage());
                return false;
			}
            
        }

        public static function cancelPreapproval ($invest, &$errors = array(), $fail = false) {
            return static::cancelPay($invest, $errors, $fail);
        }
        public static function cancelPay($invest, &$errors = array(), $fail = false) {
            if (\GOTEO_FREE) {
                $errors[] = 'Bank not implemented. Contact us for development services or make it work somehow';
                return false;
            }

			try {
                if (empty($invest->payment)) {
                    $invest->cancel($fail);
                    return true;
                }

                //echo \trace($datos);

                    return false;
			}
			catch(Exception $ex) {
                Invest::setDetail($invest->id, 'tpv-cancel-conection-fail', 'Ha fallado la comunicacion con el tpv al anular la operacion. Proceso libary/tpv::cancelPay');
                $errors[] = 'Error fatal en la comunicación con el TPV, se ha reportado la incidencia. Disculpe las molestias.';
                @mail(\GOTEO_FAIL_MAIL, 'Error fatal en comunicacion TPV Sermepa', 'ERROR en ' . __FUNCTION__ . '<br /><pre>' . print_r($handler, 1) . '</pre>');
                return false;
			}

        }

	}
	
}