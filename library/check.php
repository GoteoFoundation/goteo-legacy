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

	/*
	 * Clase para verificar valores
	 * Nif (DNI, NIE o pasaporte) - X0000000A
     * VAT (de los 27 UE)
	 * Mail - xxxx.xxxx@xxx.xxxx.xxxx
	 * Words - minimo de x palabras
	 *
	 */
    class Check {

		/*
		 * Debe validar nif, nie, pasaporte
		 */
		public static function nif ($value) {

			// quitamos puntos y guiones
			$value = str_replace(array('_', '.', ' ', '-', ','), '', $value);

			$value = strtoupper($value);
			for ($i = 0; $i < 9; $i++) {
				$num[$i] = substr($value, $i, 1);
			}

			//si no tiene un formato valido devuelve error
			if (!preg_match('/((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)/', $value)) {
				return false;
			}

			//comprobacion de NIFs estandar
			if (preg_match('/(^[0-9]{8}[A-Z]{1}$)/', $value)) {
				if ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr($value, 0, 8) % 23, 1)) {
					return true;
				} else {
					return false;
				}
			}

			//algoritmo para comprobacion de codigos tipo CIF
			$suma = $num[2] + $num[4] + $num[6];
			for ($i = 1; $i < 8; $i += 2) {
				$suma += substr((2 * $num[$i]), 0, 1) + substr((2 * $num[$i]), 1, 1);
			}
			$n = 10 - substr($suma, strlen($suma) - 1, 1);

			//comprobacion de NIFs especiales (se calculan como CIFs o como NIFs)
			if (preg_match('/^[KLM]{1}/', $value)) {
				if ($num[8] == chr(64 + $n) || $num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr($value, 1, 8) % 23, 1)) {
					return true;
				} else {
					return false;
				}
			}

			//comprobacion de CIFs
			if (preg_match('/^[ABCDEFGHJNPQRSUVW]{1}/', $value)) {
				if ($num[8] == chr(64 + $n) || $num[8] == substr($n, strlen($n) - 1, 1)) {
					return true;
				} else {
					return false;
				}
			}

			//comprobacion de NIEs
			//T
			if (preg_match('/^[T]{1}/', $value)) {
				if ($num[8] == preg_match('/^[T]{1}[A-Z0-9]{8}$/', $value)) {
					return true;
				} else {
					return false;
				}
			}

			//XYZ
			if (preg_match('/^[XYZ]{1}/', $value)) {
				if ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr(str_replace(array('X', 'Y', 'Z'), array('0', '1', '2'), $value), 0, 8) % 23, 1)) {
					return true;
				} else {
					return false;
				}
			}
			//si todavia no se ha verificado devuelve error
			return false;
		}

		/*
		 * Validación del numero VAT para los 27 paises de la UE
		 */
		public static function vat ($value) {

			// quitamos puntos y guiones
			$value = str_replace(array('_', '.', ' ', '-', ',', '\\', '+', '*'), '', $value);

			$value = strtoupper($value);

            $vats = array();
            $vats[] = '(AT)?U[0-9]{8}';
            $vats[] = '(BE)?[0]?[0-9]{9}';
            $vats[] = '(BG)?[0-9]{9,10}';
            $vats[] = '(CY)?[0-9]{8}[A-Z]';
            $vats[] = '(CZ)?[0-9]{8,10}';
            $vats[] = '(EE|EL|GR|DE|PT)?[0-9]{9}';
            $vats[] = '(FR)?[0-9A-Z]{2}[0-9]{9}';
            $vats[] = '(FI|HU|LU|MT|SI|DK)?[0-9]{8}';
            $vats[] = '(IE)?[0-9][0-9A-Z][0-9]{5}[A-Z]';
            $vats[] = '(IT|LV)?[0-9]{11}';
            $vats[] = '(LT)?([0-9]{9}|[0-9]{12})';
            $vats[] = '(NL)?[0-9]{9}B[0-9]{2}';
            $vats[] = '(PL|SK)?[0-9]{10}';
            $vats[] = '(RO)?[0-9]{2,10}';
            $vats[] = '(SE)?[0-9]{12}';
            $vats[] = '(ES)?([0-9A-Z][0-9]{7}[A-Z])|([A-Z][0-9]{7}[0-9A-Z])';
            $vats[] = '(GB)?([1-9][0-9]{2}[0-9]{4}[0-9]{2})|([1-9][0-9]{2}[0-9]{4}[0-9]{2}[0-9]{3})|((GD|HA)[0-9]{3})';

            $expr = '/^('.implode($vats, '|').')$/';

			if (preg_match($expr, $value)) {
				return true;
			}

            return false;
        }


		/**
		 * Valida una dirección de correo.
		 *
		 * Extraido de:
		 * 	http://www.linuxjournal.com/article/9585
		 *
		 * @param type string	$value	E-mail
		 * @return type bool
		 */
		public static function mail ($value) {
			// First, we check that there's one @ symbol,
			// and that the lengths are right.
			if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $value)) {
			    // Email invalid because wrong number of characters
			    // in one section or wrong number of @ symbols.
			    return false;
			}
			// Split it into sections to make life easier
			$email_array = explode("@", $value);
			$local_array = explode(".", $email_array[0]);
			for ($i = 0; $i < sizeof($local_array); $i++) {
			    if(!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
			        return false;
			    }
			}
			// Check if domain is IP. If not,
			// it should be valid domain name
			if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
			    $domain_array = explode(".", $email_array[1]);
			    if (sizeof($domain_array) < 2) {
			        return false; // Not enough parts to domain
			    }
			    for ($i = 0; $i < sizeof($domain_array); $i++) {
			        if(!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$",
			        $domain_array[$i])) {
			            return false;
			        }
			    }
			}
			return true;
		}

		/**
		 * Valida una contraseña.
		 *
		 * @param type string	$value	Contraseña
		 * @return type bool
		 */
		public static function password ($value) {
		    if(strlen($value)<6) {
		        return false;
		    }
		    return true;
		}

		public static function words ($value, $number) {

			$words = explode(' ', $value);

			if (count($words) >= $number)
				return true;
			else
				return false;
		}

		public static function phone ($value) {

			$value = str_replace(array('_', '.', ' ', '-', ',', '+', '(', ')'), '', $value);

			// si no son numeros, chof
			if (preg_match('/^\d{9,14}$/', $value) > 0)
				return true;
			else
				return false;
		}

        public static function country() {
            //@TODO Sacar el pais de la ip ip2country de ode.google or something
            return 'España';
        }

        /*
         * Metodo para reordenar una tabla moviendo uno de sus registros
         * Necesita tener un campo de orden actualizable, por defecto `order`
         * Puede tener en cuenta que el registro tiene una seccion/categoria/agrupacion
         *
         */
        public static function reorder($idReg, $updown, $table, $idField = 'id', $orderField = 'order', $extra = array()) {

            //uso el modelo core para hacer los querys
            $model = '\Goteo\Core\Model';
            $regs = array();

            // ojo con el campos extra para no pisar otros tegistros
            $sqlSec = '';
            $and = 'WHERE';
            foreach ($extra as $campo=>$valor) {
                $sqlSec .= " $and `{$campo}` = '{$valor}'";
                $and = 'AND';
            }
            //sacar de la tabla ordenando y poniendo en array de 10 en 10
            $sql = "SELECT `{$idField}` FROM {$table} {$sqlSec} ORDER  BY `{$orderField}` ASC";
            if ($query = $model::query($sql)) {
                $order = 10;
                while ($row = $query->fetchObject()) {
                    $regs[$row->$idField] = $order;
                    $order+=10;
                }

                //al elemento target cambiarle segun 'up'-5  'down'+5
                if ($updown == 'up') {
                    $regs[$idReg] -= 15;
                } elseif ($updown == 'down') {
                    $regs[$idReg] += 15;
                }
                //reordenar array
                \asort($regs);

                // hacer updates segun el nuevo orden en una transaccion
                try {
                    $model::query("START TRANSACTION");
                    $order = 1;
                    foreach ($regs as $id=>$ordenquenoponemos) {
                        $sql = "UPDATE {$table} SET `{$orderField}`=:order WHERE {$idField} = :id";
                        $query = $model::query($sql, array(':order'=>$order, ':id'=>$id));
                        $order++;
                    }
                    $model::query("COMMIT");

                    return true;
                } catch(\PDOException $e) {
                    return false;
                }
            }

        }


        }

}