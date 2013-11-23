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


namespace Goteo\Library\i18n {

	require_once 'library/php-mo/php-mo.php';  // external library to compile .po gettext files on the fly

	use Goteo\Core\Model;

	/*
	 * Clase para sacar textos estáticos de la tabla text
	 *  (por ahora utilizar gettext no nos compensa, quizás más adelante)
	 *
	 */
    class Lang {
		
		static public function get ($id = \GOTEO_DEFAULT_LANG) {
            $sql = "SELECT
                        id, name,
                        IFNULL(short, name) as short
                    FROM lang WHERE id = :id
                    ";
			$query = Model::query($sql, array(':id' => $id));
			return $query->fetchObject();
		}

        /*
         * Devuelve los idiomas
         */
		public static function getAll ($activeOnly = false) {
            $array = array();

            $sql = "SELECT
                        id, name,
                        IFNULL(short, name) as short
                    FROM lang
                    ";
            if ($activeOnly) {
                $sql .= "WHERE active = 1
                    ";
            }
            $sql .= "ORDER BY id ASC";

			$query = Model::query($sql);
            foreach ( $query->fetchAll(\PDO::FETCH_CLASS) as $lang) {
                $array[$lang->id] = $lang;
            }
            return $array;
		}


		/*
		 *  Esto se usara para la gestión de idiomas
         * aunque quizas no haya gestión de idiomas
		 */
		public function save($data, &$errors = array()) {
			if (!is_array($data) ||
				empty($data['id']) ||
				empty($data['name']) ||
				empty($data['active'])) {
					return false;
			}

			if (Model::query("REPLACE INTO lang (id, name, active) VALUES (:id, :name, :active)", array(':id' => $data['id'], ':name' => $data['name'], ':active' => $data['active']))) {
				return true;
			}
			else {
				$errors[] = 'Error al insertar los datos ' . \trace($data);
				return false;
			}
		}

		static public function is_active ($id) {
			$query = Model::query("SELECT id FROM lang WHERE id = :id AND active = 1", array(':id' => $id));
            if ($query->fetchObject()->id == $id) {
                return true;
            } else {
                return false;
            }
		}

        /*
         * Establece el idioma de visualización de la web
         */
		static public function set () {
            // si lo estan cambiando, ponemos el que llega
            if (isset($_GET['lang'])) {
/*                // si está activo, sino default
 *
 *  Aunque no esté activo!!
 *
                if (Lang::is_active($_GET['lang'])) {
 *
 */
                    $_SESSION['lang'] = $_GET['lang'];
   /*             } else {
                    $_SESSION['lang'] = \GOTEO_DEFAULT_LANG;
                }
    * 
    */
            } elseif (empty($_SESSION['lang'])) {
                // si no hay uno de session ponemos el default
                $_SESSION['lang'] = \GOTEO_DEFAULT_LANG;
            }
            // establecemos la constante
            define('LANG', $_SESSION['lang']);
		}

		static public function locale () {
			$sql = "SELECT locale FROM lang WHERE id = :id";
			$query = Model::query($sql, array(':id' => \LANG));
			return $query->fetchColumn();
		}

	} // class


} // ns