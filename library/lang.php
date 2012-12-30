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
						define($_SESSION['lang'], LANG);
		}

		static public function locale () {
			$sql = "SELECT locale FROM lang WHERE id = :id";
			$query = Model::query($sql, array(':id' => \LANG));
			return $query->fetchColumn();
    }

		static protected function gettextBinaryExists($locale, $domain) {
			return \file_exists("locale/{$locale}/LC_MESSAGES/{$domain}.mo");
		}
		
		static protected function localeExists($locale, $domain) {
			return \file_exists("locale/{$locale}/LC_MESSAGES/{$domain}.po");
		}
		
		static public function gettextSupported() {
			return function_exists("gettext");
		}

		/**
		 * Use the php.mo library to compile gettext .po files if the binary doesn't exist.
		 *
		 * @return true/false on success/failure
		 */
		static private function compileLanguageFile($locale, $domain) {
				return \phpmo_convert( "locale/{$locale}/LC_MESSAGES/{$domain}.po" );
		}

		/**
		 * bypass gettext caching by using a clever file-renaming 
		 * mechanism described in http://blog.ghost3k.net/articles/php/11/gettext-caching-in-php
		 */
		static protected function spawnUncachedDomain($locale, $domain) {
			// path to the .MO file that we should monitor
			$filename = "locale/{$locale}/LC_MESSAGES/{$domain}.mo";
			$mtime = \filemtime($filename); // check its modification time
			// our new unique .MO file
			$filename_new = "locale/{$locale}/LC_MESSAGES/{$domain}_{$mtime}.mo"; 

			if (!\file_exists($filename_new)) {  // check if we have created it before
	      // if not, create it now, by copying the original
	      \copy($filename,$filename_new);
				//error_log("creating new domain {$filename_new}");
			}
			// compute the new domain name
			$domain_new = "{$domain}_{$mtime}";
			
			return $domain_new;
		} 
		
		/**
		 * Set gettext configuration to be used by PHP.
		 *
		 * @param $locale the string that determines the current locale (e.g. en_GB)
		 * @param $domain the filename for the .po file used by gettext to load messages from
		 */
		static public function gettext($locale, $domain) {
			if( !Lang::gettextSupported() ) { 
				error_log("ERROR - GETTEXT not supported on this server, everything will appar in spanish"); 
				return; 
			}
			
			\setlocale(\LC_TIME, $locale);
			\putenv("LANG={$locale}");
			\setlocale(LC_ALL, $locale);
			
			if( Lang::localeExists($locale, $domain) ) {
				// determine if the language binary file exists, if not try to generate it automatically
				if( !Lang::gettextBinaryExists($locale, $domain) ) {
					Lang::compileLanguageFile($locale, $domain);
					//error_log("compiling missing language file binary");
				}

				// generate a new uncached domain file if caching bypass featured is enabled
				if(true == \GOTEO_GETTEXT_BYPASS_CACHING) {	
					$domain = Lang::spawnUncachedDomain($locale, $domain);
					//error_log("bypassing gettext caching");
				}
			
				// configure settext domain
				\bindtextdomain($domain, "locale");
				\bind_textdomain_codeset($domain, 'UTF-8');
				\textdomain($domain);
			} else {
				error_log("WARNING - Locale is not installed ${locale}");
			}
		}
	} // class


} // ns