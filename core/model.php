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


namespace Goteo\Core {

	use Goteo\Core\Error;

    abstract class Model {

        /**
         * Constructor.
         */
        public function __construct () {
            if (\func_num_args() >= 1) {
                $data = \func_get_arg(0);
                if (is_array($data) || is_object($data)) {
                    foreach ($data as $k => $v) {
                        $this->$k = $v;
                    }
                }
            }
        }

        /**
         * Obtener.
         * @param   type mixed  $id     Identificador
         * @return  type object         Objeto
         */
        abstract static public function get ($id);

        /**
		 * Guardar.
         * @param   type array  $errors     Errores devueltos pasados por referencia.
         * @return  type bool   true|false
         */
         abstract public function save (&$errors = array());

        /**
         * Validar.
         * @param   type array  $errors     Errores devueltos pasados por referencia.
         * @return  type bool   true|false
         */
        abstract public function validate (&$errors = array());

        /**
         * Consulta.
         * Devuelve un objeto de la clase PDOStatement
         * http://www.php.net/manual/es/class.pdostatement.php
         *
         * @param   type string $query      Consulta SQL
         * @param   type array  $params     ParÃ¡metros
         * $return  type object PDOStatement
         */
        public static function query ($query, $params = null) {

            static $db = null;

            if ($db === null) {
                $db = new DB;
            }

            $params = func_num_args() === 2 && is_array($params) ? $params : array_slice(func_get_args(), 1);

            // ojo que el stripslashes jode el contenido blob al grabar las imagenes
            if (\get_magic_quotes_gpc ()) {
                foreach ($params as $key => $value) {
                    if ($key != ':content') {
                        $params[$key] = \stripslashes(\stripslashes($value));
                    }
                }
            }

            $result = $db->prepare($query);

            try {

                $result->execute($params);

                return $result;

            } catch (\PDOException $e) {
                throw new Exception("Error PDO: " . \trace($e));
            }

        }

        /**
         * Devuelve el id autoincremental generado en la Ãºltima consulta, si se
         * ha generado uno.
         *
         * @return  int Id de `AUTO_INCREMENT` o `0`, si la Ãºltima consulta no
         *          ha generado ninguna valor autoincremental.
         */
        public static function insertId() {

            try {
                return static::query("SELECT LAST_INSERT_ID();")->fetchColumn();
            } catch (\Exception $e) {
                return 0;
            }
        }

		/**
		 * Formato.
		 * Formatea una cadena para ser usada como id varchar(50)
		 *
		 * @param string $value
		 * @return string $id
		 */
		public static function idealiza ($value) {
			$id = trim(strtolower($value));
			// Acentos
            $table = array(
                'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
                'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
                'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
                'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
                'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
                'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'ý'=>'y',
                'þ'=>'b', 'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r', 'ª' => 'a', 'º' => 'o',
                '!' => '', '¡' => '', '?' => '', '¿' => '', '@' => '', '^' => '', '|' => '', '#' => '', '~' => '',
                '%' => '', '$' => '', '*' => '', '+' => '', '-' => '', '`' => ''
            );

            $id = strtr($id, $table);

            // Separadores
			$id = preg_replace("/[\s\,\(\)\[\]\:\;\_\/\"\'\{\}]+/", "-", $id);
			$id = substr($id, 0, 50);

			return $id;
		}

    }

}