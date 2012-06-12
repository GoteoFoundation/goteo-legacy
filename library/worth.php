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

    use Goteo\Core\Model;

    class Worth {
		
        /*
         * Devuelve el nombre de un nivel por id
         */
		public static function get ($id) {

            $values = array(':id'=>$id, ':lang' => \LANG);
            $sql = "SELECT
                        worthcracy.id as id,
                        IFNULL(worthcracy_lang.name, worthcracy.name) as name
                    FROM worthcracy
                    LEFT JOIN worthcracy_lang
                        ON  worthcracy_lang.id = worthcracy.id
                        AND worthcracy_lang.lang = :lang
                    WHERE worthcracy.id = :id
                    ";

            $query = Model::query($sql, $values);
            $level = $query->fetchObject();
            if (!empty($level->name))
                return $level->name;

            return false;
		}

        /*
         * Devuelve datos apra gestionar
         */
		public static function getAdmin ($id) {

            $values = array(':id'=>$id);
            $sql = "SELECT
                        worthcracy.id as id,
                        worthcracy.name as name,
                        worthcracy.amount as amount
                    FROM worthcracy
                    WHERE worthcracy.id = :id
                    ";

            $query = Model::query($sql, $values);
            return $query->fetchObject();
		}

        /*
         * Devuelve los niveles de meritocracia
         */
		public static function getAll () {
            $array = array();
            $values = array(':lang' => \LANG);
            $sql = "SELECT
                        worthcracy.id as id,
                        IFNULL(worthcracy_lang.name, worthcracy.name) as name,
                        worthcracy.amount as amount
                    FROM worthcracy
                    LEFT JOIN worthcracy_lang
                        ON  worthcracy_lang.id = worthcracy.id
                        AND worthcracy_lang.lang = :lang
                    ORDER BY worthcracy.amount ASC
                    ";

            $query = Model::query($sql, $values);
            foreach ( $query->fetchAll(\PDO::FETCH_CLASS) as $worth) {
                $array[$worth->id] = $worth;
            }
            return $array;
		}

		/*
		 *  Esto se usa para actualizar datos en cualquier tabla de contenido
		 */
		public static function save($data, &$errors = array()) {

            if (empty($data)) {
                $errors[] = "Sin datos";
                return false;
            }
            if (empty($data['name']) || empty($data['amount']) || empty($data['id'])) {
                $errors[] = "No se guardar sin nombre y cantidad";
                return false;
            }

  			try {
                $values = array(
                    ':id' => $data['id'],
                    ':name' => $data['name'],
                    ':amount' => $data['amount']
                );

				$sql = "REPLACE INTO worthcracy SET `id` = :id, `name` = :name, `amount` = :amount ";
				if (Model::query($sql, $values)) {
                    return true;
                } else {
                    $errors[] = "Ha fallado $sql con <pre>" . print_r($values, 1) . "</pre>";
                    return false;
                }

			} catch(\PDOException $e) {
                $errors[] = 'Error sql al grabar el nivel de meritocracia. ' . $e->getMessage();
                return false;
			}

		}


        /*
         * Devuelve el importe para el siguiente nivel
         * @TODO tener en cuenta el nivel actual
         */
		public static function abitmore ($amount) {

            if (!is_numeric($amount))
                return $amount;

            $values = array(':amount'=>$amount, ':lang' => \LANG);
            $sql = "SELECT
                        IFNULL(worthcracy_lang.name, worthcracy.name) as name,
                        worthcracy.amount as amount
                    FROM worthcracy
                    LEFT JOIN worthcracy_lang
                        ON  worthcracy_lang.id = worthcracy.id
                        AND worthcracy_lang.lang = :lang
                    WHERE worthcracy.amount > :amount
                    ";

            $query = Model::query($sql, $values);
			$next = $query->fetchObject();
            $abit = $next->amount - $amount; //cuanto para el siguiente nivel
            
			return array('amount'=>$abit, 'name'=>$next->name);
		}

        /*
         * Devuelve el nombre de un nivel por importe acumulado
         */
		public static function reach ($amount) {
            if (!is_numeric($amount))
                return false;
            
            $values = array(':amount'=>$amount, ':lang' => \LANG);
            $sql = "SELECT
                        worthcracy.id as id,
                        IFNULL(worthcracy_lang.name, worthcracy.name) as name
                    FROM worthcracy
                    LEFT JOIN worthcracy_lang
                        ON  worthcracy_lang.id = worthcracy.id
                        AND worthcracy_lang.lang = :lang
                    WHERE worthcracy.amount <= :amount
                    ORDER BY worthcracy.amount DESC
                    LIMIT 1
                    ";

            $query = Model::query($sql, $values);
            return $query->fetchObject();
		}

	}
	
}