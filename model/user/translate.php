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

namespace Goteo\Model\User {

    use Goteo\Core\ACL,
        Goteo\Model;

    class Translate extends \Goteo\Core\Model {

        public
            $user,
            $type,
            $item,
            $ready;

        // tipos de contenidos que se traducen
        public static
            $types = array('project');

        /*
         *  Para conseguir una instancia de traduccion
         *
        public static function get ($user, $type, $item) {

            if (!in_array($type, self::$types)) {
                return false;
            }

            $query = static::query("
                SELECT *
                FROM    user_translate
                WHERE type = :type
                AND item = :item
                AND user = :user
                ", array(':type' => $type, ':item'=>$item, ':user'=>$user));

            $translate =  $query->fetchObject(__CLASS__);

            if ($translate instanceof \Goteo\Model\User\Translate){
                return $translate;
            } else {
                return false;
            }
        }
         * 
         */
        

        /**
         * Lo usamos para conseguir el tipo de ese item
         * @param varchar(50) $item
         * @return string $type ('project', 'call') or false if not one
         */
	 	public static function get ($id) {
            $array = array ();
            try {
                $query = static::query("SELECT DISTINCT(type) FROM user_translate WHERE item = ?", array($id));
                $types = $query->fetchAll();
                foreach ($types as $type) {
                    $array[] = $type[0];
                }

                if (count($array) !== 1) {
                    return false;
                } else {
                    return $array[0];
                }
                
            } catch(\PDOException $e) {
				throw new \Goteo\Core\Exception($e->getMessage());
            }
        }

        /**
         * Get the translations for a user
         * @param varcahr(50) $id  user identifier
         * @return array of items
         */
	 	public static function getMine ($id, $type = null) {
            $array = array ();
            try {

                $sql = "SELECT type, item FROM user_translate WHERE user = :user";
                $values = array(':user'=>$id);
                
                if (in_array($type, self::$types)) {
                    $sql .= " AND type = :type";
                    $values[':type'] = $type;
                } else {
                    return false;
                }

                $query = static::query($sql, $values);
                $translates = $query->fetchAll(\PDO::FETCH_ASSOC);
                foreach ($translates as $item) {
                    switch ($item['type']) {
                        case 'project':
                            $array[] = Model\Project::getMini($item['item']);
                            break;
                        default:
                            continue;
                    }
                }

                return $array;
            } catch(\PDOException $e) {
				throw new \Goteo\Core\Exception($e->getMessage());
            }
		}

        // shortcuts para getMine
	 	public static function getMyProjects ($id) {
            return self::getMine($id, 'project');
        }

	 	public static function getMyCalls ($id) {
            return self::getMine($id, 'call');
        }

	 	public static function getMyNodes ($id) {
            return self::getMine($id, 'node');
        }


        /**
         * Metodo para sacar los contenidos disponibles para traducir
         * @param varcahr(50) $id  user identifier
         * @return array of items
         */
	 	public static function getAvailables ($type = 'project', $node = null) {

            if (!in_array($type, self::$types)) {
                return false;
            }

            $array = array ();
            try {
                $values = array();

                if ($type == 'node') {
                    $sql = "SELECT id, name FROM `{$type}`";
                } else {
                    $sql = "SELECT id, name FROM `{$type}` WHERE translate = 0 AND (status > 1  OR (status = 1 AND id NOT REGEXP '[0-9a-f]{5,40}'))";
                    if ($type != 'call' && !empty($node)) {
                        $sql .= " AND node = :node";
                        $values[':node'] = $node;
                    }
                }
                $query = static::query($sql, $values);
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
            if (empty($this->type) || !in_array($this->type, self::$types))
                $errors[] = 'No hay tipo de contenido o no el tipo no esta habilitado';

            if (empty($this->id))
                $errors[] = Text::_('No hay una traducción para asignar');

            if (empty($this->user))
                $errors[] = Text::_('No hay ningun usuario al que asignar');

            //cualquiera de estos errores hace fallar la validación
            if (!empty($errors))
                return false;
            else
                return true;
        }

		public function save (&$errors = array()) {
            if (!$this->validate($errors)) return false;

            $values = array(
                    ':user'=>$this->user,
                    ':type'=>$this->type,
                    ':item'=>$this->item
                );

			try {
	            $sql = "REPLACE INTO user_translate (user, type, item) VALUES(:user, :type, :item)";
				if (self::query($sql, $values)) {
                    ACL::allow('/translate/'.$this->type.'/'.$this->item.'/*', '*', 'translator', $this->user);
    				return true;
                } else {
                    $errors[] = 'No se ha creado el registro `user_translate`';
                    return false;
                }
			} catch(\PDOException $e) {
				$errors[] = Text::_('No se ha guardado correctamente. ') .$e->getMessage();
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
            $values = array(
                    ':user'=>$this->user,
                    ':type'=>$this->type,
                    ':item'=>$this->item
                );

            try {
                if (self::query("DELETE FROM user_translate WHERE type = :type AND item = :item AND user = :user", $values)) {
                    ACL::deny('/translate/'.$this->type.'/'.$this->item.'/*', '*', 'translator', $this->user);    				return true;
                } else {
                    return false;
                }
			} catch(\PDOException $e) {
                $errors[] = Text::_('No se ha guardado correctamente. ') . $e->getMessage();
                return false;
			}
		}

        /*
         * Dar por lista una traducción
         *
        */
		public function ready (&$errors = array()) {
            $values = array(
                    ':user'=>$this->user,
                    ':type'=>$this->type,
                    ':item'=>$this->item
                );

            try {
                if (self::query("UPDATE user_translate SET ready = 1 WHERE type = :type AND item = :item AND user = :user", $values)) {
    				return true;
                }
			} catch(\PDOException $e) {
                $errors[] = 'No se ha podido marcar la traduccion ' . $this->type .':'. $this->item . ' del usuario ' . $this->user . ' como lista. ' . $e->getMessage();
                //Text::get('review-set_ready-fail');
			}
            
            return false;
		}

        /*
         * Reabrir una traduccion
        */
		public function unready (&$errors = array()) {
            $values = array(
                    ':user'=>$this->user,
                    ':type'=>$this->type,
                    ':item'=>$this->item
                );

            try {
                if (self::query("UPDATE user_translate SET ready = 0 WHERE type = :type AND item = :item AND user = :user", $values)) {
    				return true;
                }
			} catch(\PDOException $e) {
                $errors[] = 'No se ha podido reabrir la traduccion ' . $this->type .':'. $this->item . ' del usuario ' . $this->user . '. ' . $e->getMessage();
			}
            
            return false;
		}

        
        /*
         * Lista de usuarios que tienen asignada cierta traduccion
         *
         * //, user_review.ready as ready
         */
        public static function translators ($item, $type = 'project') {

            if (!in_array($type, self::$types)) {
                return false;
            }

             $array = array ();
            try {
               $sql = "SELECT 
                            DISTINCT(user) as id
                        FROM user_translate
                        WHERE type = :type
                        AND item = :item
                        ";
                $query = static::query($sql, array(':type'=>$type, ':item'=>$item));
                foreach ($query->fetchAll(\PDO::FETCH_ASSOC) as $row) {

                    // nombre i avatar
                    $user = \Goteo\Model\User::getMini($row['id']);

                    $array[$row['id']] = $user->name;
                }

                return $array;
            } catch(\PDOException $e) {
				throw new \Goteo\Core\Exception($e->getMessage());
            }
        }

        /*
         * Devuelve true o false si es legal que este usuario haga algo con esta revision
         */
        public static function is_legal ($user, $item, $type = 'project') {

            if (!in_array($type, self::$types)) {
                return false;
            }
            
            $sql = "SELECT user FROM user_translate WHERE user = :user AND type = :type AND item = :item";
            $values = array(
                ':user' => $user,
                ':type' => $type,
                ':item' => $item
            );
            $query = static::query($sql, $values);
            $legal = $query->fetchObject();
            if ($legal->user == $user) {
                return true;
            } else {
                return false;
            }
        }

        /*
         * Para obtener los idiomas de traducciÃ³n habilitados para este usuario
         */
	 	public static function getLangs ($id) {
            $array = array ();

            $sql = "SELECT user_translang.lang as id, name
                FROM user_translang
                INNER JOIN lang
                    ON lang.id = user_translang.lang
                WHERE user_translang.user = :user";
            $values = array(':user'=>$id);

            $query = static::query($sql, $values);
            $langs = $query->fetchAll(\PDO::FETCH_OBJ);
            foreach ($langs as $lang) {
                $array[$lang->id] = $lang->name;
            }

            return $array;
		}


	}
    
}