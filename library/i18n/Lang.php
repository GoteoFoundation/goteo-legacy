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

        static public function set($force = null) {
            //echo 'Session: ' . $_SESSION['lang'] . '<br />';
            //echo 'Get: ' . $_GET['lang'] . '<br />';
            //definido por navegador =
            $nav = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            // si lo estamos forzando
            if (isset($force)) {
                $_SESSION['lang'] = $force;
            } elseif (isset($_GET['lang'])) {
                // si lo estan cambiando, ponemos el que llega
                setcookie("goteo_lang", $_GET['lang'], time() + 3600 * 24 * 365);
                $_SESSION['lang'] = $_GET['lang'];
            } elseif (empty($_SESSION['lang'])) {
                //primero miramos si tiene cookie
                if (isset($_COOKIE['goteo_lang'])) {
                    $_SESSION['lang'] = $_COOKIE['goteo_lang'];
                } elseif ($nav != 'es' && self::is_active($nav)) {
                    // si el definido por navegador no es español y está activo
                    $_SESSION['lang'] = $nav;
                } else {
                    $_SESSION['lang'] = defined('NODE_DEFAULT_LANG') ? \NODE_DEFAULT_LANG : \GOTEO_DEFAULT_LANG;
                }
            }
            // establecemos la constante
            define('LANG', $_SESSION['lang']);

            //echo 'New Session: ' . $_SESSION['lang'] . '<br />';
            //echo 'Const: ' . LANG . '<br />';
        }

        static public function locale() {
            $sql = "SELECT locale FROM lang WHERE id = :id";
            $query = Model::query($sql, array(':id' => \LANG));
            return $query->fetchColumn();
        }

        /*
         * Para sacar lista de idiomas
         */
        public static function projectLangs($id) {
            $array = array();

            $sql = "SELECT
                        lang.id,
                        lang.name
                    FROM lang
                    WHERE lang.id IN (
                        SELECT
                            lang
                        FROM project_lang
                        WHERE id = :id
                        )
                    OR lang.id IN (
                        SELECT
                            project.lang
                        FROM project
                        WHERE project.id = :id
                        )
                    ";
            $sql .= "ORDER BY lang.id ASC";

//            die(str_replace(':id', "'$id'", $sql));

            $query = Model::query($sql, array(':id' => $id));
            foreach ($query->fetchAll(\PDO::FETCH_OBJ) as $lang) {
                $array[$lang->id] = $lang->name;
            }
            return $array;
        }

        
        
    } //class

} //ns