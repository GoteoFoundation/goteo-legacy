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

namespace Goteo\Library {

    use Goteo\Model\Invest,
        Goteo\Model\Project,
        Goteo\Model\User,
        Goteo\Library\Feed,
        Goteo\Core\Redirection;

	/*
	 * Clase para usar los adaptive payments de paypal
	 */
    class Paypal {

        /**
         * @param object invest instancia del aporte: id, usuario, proyecto, cuenta, cantidad
         *
         * Método para crear un preapproval para un aporte
         * va a mandar al usuario a paypal para que confirme
         *
         * @TODO poner límite máximo de dias a lo que falte para los 40/80 dias para evitar las cancelaciones
         */
        public static function preapproval($invest, &$errors = array()) {
            if (\GOTEO_FREE) {
                return false;
            }
        }


        /*
         *  Metodo para ejecutar pago (desde cron)
         * Recibe parametro del aporte (id, cuenta, cantidad)
         *
         * Es un pago encadenado, la comision del 8% a Goteo y el resto al proyecto
         *
         */
        public static function pay($invest, &$errors = array()) {
            if (\GOTEO_FREE) {
                return false;
            }
        }


        /*
         *  Metodo para ejecutar pago secundario (desde cron/dopay)
         * Recibe parametro del aporte (id, cuenta, cantidad)
         */
        public static function doPay($invest, &$errors = array()) {
            if (\GOTEO_FREE) {
                return false;
            }
        }


        /*
         * Llamada a paypal para obtener los detalles de un preapproval
         */
        public static function preapprovalDetails ($key, &$errors = array()) {
            if (\GOTEO_FREE) {
                return false;
            }
        }

        /*
         * Llamada a paypal para obtener los detalles de un cargo
         */
        public static function paymentDetails ($key, &$errors = array()) {
            if (\GOTEO_FREE) {
                return false;
            }
        }


        /*
         * Llamada para cancelar un preapproval (si llega a los 40 sin conseguir el mínimo)
         * recibe la instancia del aporte
         */
        public static function cancelPreapproval ($invest, &$errors = array()) {
            if (\GOTEO_FREE) {
                return false;
            }
        }

	}
	
}