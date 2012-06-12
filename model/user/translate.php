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


namespace Goteo\Model\User {

    use Goteo\Model;

    class Translate extends \Goteo\Core\Model {

        public
            $id,
            $user;

	 	public static function get ($id) {
            return true;
        }

        /**
         * Get the translations for a user
         * @param varcahr(50) $id  user identifier
         * @return array of reviews identifiers
         */
	 	public static function getMine ($id, $lang = null) {
            $array = array ();
            try {
                $query = static::query("SELECT project FROM user_translate WHERE user = ?", array($id));
                $translates = $query->fetchAll();
                foreach ($translates as $item) {
                    $array[] = Model\Project::get($item[0], $lang);
                }

                return $array;
            } catch(\PDOException $e) {
				throw new \Goteo\Core\Exception($e->getMessage());
            }
		}

        /**
         * Metodo para sacar los proyectos disponibles apra traducir
         * @param varcahr(50) $id  user identifier
         * @return array of reviews identifiers
         */
	 	public static function getAvailables () {
            $array = array ();
            try {
                $query = static::query("SELECT id, name FROM project WHERE status > 1 AND translate = 0");
                $avail = $query->fetchAll(\PDO::FETCH_OBJ);
                foreach ($avail as $item) {
                    $array[] = $item;
                }

                return $array;
            } catch(\PDOException $e) {
				throw new \Goteo\Core\Exception($e->getMessage());
            }
		}

		public function validate(&$errors = array()) {
            // Estos son errores que no permiten continuar
            if (empty($this->id))
                $errors[] = 'No hay una traducción para asignar';

            if (empty($this->user))
                $errors[] = 'No hay ningun usuario al que asignar';

            //cualquiera de estos errores hace fallar la validación
            if (!empty($errors))
                return false;
            else
                return true;
        }

		public function save (&$errors = array()) {
            if (!$this->validate($errors)) return false;

            $values = array(':user'=>$this->user, ':project'=>$this->id);

			try {
	            $sql = "REPLACE INTO user_translate (user, project) VALUES(:user, :project)";
				self::query($sql, $values);
				return true;
			} catch(\PDOException $e) {
				$errors[] = "La traducción {$this->id} no se ha asignado correctamente. Por favor, revise el metodo User\Translate->save." . $e->getMessage();
				return false;
			}

		}

		/**
		 * Quitarle una traducción al usuario
		 *
		 * @param varchar(50) $user id del usuario
		 * @param INT(12) $id  identificador de la tabla project
		 * @param array $errors 
		 * @return boolean
		 */
		public function remove (&$errors = array()) {
			$values = array (
				':user'=>$this->user,
				':project'=>$this->id,
			);

            try {
                self::query("DELETE FROM user_translate WHERE project = :project AND user = :user", $values);
				return true;
			} catch(\PDOException $e) {
                $errors[] = 'No se ha podido desasignar la traduccion ' . $this->id . ' del usuario ' . $this->user . ' ' . $e->getMessage();
                //Text::get('remove-review-fail');
                return false;
			}
		}

        /*
         * Dar por lista una traducción
         *
		public function ready (&$errors = array()) {
			$values = array (
				':user'=>$this->user,
				':project'=>$this->id,
			);

            try {
                self::query("UPDATE user_translate SET ready = 1 WHERE project = :project AND user = :user", $values);

                // recalcular puntuacion global de la revision
                Model\Review::recount($this->id, $errors);

				return true;
			} catch(\PDOException $e) {
                $errors[] = 'No se ha podido marcar la traduccion ' . $this->id . ' del usuario ' . $this->user . ' como lista. ' . $e->getMessage();
                //Text::get('review-set_ready-fail');
                return false;
			}
		}

        *
         * Reabrir una traduccion
         *
		public function unready (&$errors = array()) {
			$values = array (
				':user'=>$this->user,
				':project'=>$this->id,
			);

            try {
                self::query("UPDATE user_translate SET ready = 0 WHERE project = :project AND user = :user", $values);

                // recalcular puntuacion global de la revision
                Model\Review::recount($this->id, $errors);

				return true;
			} catch(\PDOException $e) {
                $errors[] = 'No se ha podido reabrir la revision ' . $this->id . ' del usuario ' . $this->user . '. ' . $e->getMessage();
                //Text::get('review-set_unready-fail');
                return false;
			}
		}
*/
        
        /*
         * Lista de usuarios que tienen asignada cierta traduccion
         *
         * //, user_review.ready as ready
         */
        public static function translators ($project) {
             $array = array ();
            try {
               $sql = "SELECT 
                            DISTINCT(user_translate.user) as id
                        FROM user_translate
                        WHERE user_translate.project = :id
                        ";
                $query = static::query($sql, array(':id'=>$project));
                foreach ($query->fetchAll(\PDO::FETCH_ASSOC) as $item) {

                    // nombre i avatar
                    $user = \Goteo\Model\User::getMini($item['id']);

                    $array[$item['id']] = $user->name;
                }

                return $array;
            } catch(\PDOException $e) {
				throw new \Goteo\Core\Exception($e->getMessage());
            }
        }

        /*
         * Devuelve true o false si es legal que este usuario haga algo con esta revision
         */
        public static function is_legal ($user, $project) {
            $sql = "SELECT user, translate FROM user_translate WHERE user = :user AND project = :project";
            $values = array(
                ':user' => $user,
                ':project' => $project
            );
            $query = static::query($sql, $values);
            $legal = $query->fetchObject();
            if ($legal->user == $user && $legal->project == $project) {
                return true;
            } else {
                return false;
            }
        }

	}
    
}