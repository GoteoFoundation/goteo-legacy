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


namespace Goteo\Model\Project {

    use Goteo\Library\Text;

    class Support extends \Goteo\Core\Model {

        public
            $id,
			$project,
			$support,
			$description,
			$type = 'task',
            $thread;

	 	public static function get ($id) {
            try {
                $query = static::query("SELECT * FROM support WHERE id = :id", array(':id' => $id));
                return $query->fetchObject(__CLASS__);
            } catch(\PDOException $e) {
                throw new \Goteo\Core\Exception($e->getMessage());
            }
		}

		public static function getAll ($project, $lang = null) {
            try {
                $array = array();
                $sql = "SELECT
                            support.id as id,
                            support.project as project,
                            support.type as type,
                            IFNULL(support_lang.support, support.support) as support,
                            IFNULL(support_lang.description, support.description) as description,
                            support.thread as thread
                        FROM support
                        LEFT JOIN support_lang
                            ON  support_lang.id = support.id
                            AND support_lang.lang = :lang
                        WHERE support.project = :project
                        ORDER BY support.id ASC
                        ";

				$query = self::query($sql, array(':project'=>$project, ':lang'=>$lang));
				foreach ($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $item ) {
                    $array[$item->id] = $item;
                }
				return $array;
            } catch(\PDOException $e) {
                throw new \Goteo\Core\Exception($e->getMessage());
            }
		}

		public function validate(&$errors = array()) {
            // Estos son errores que no permiten continuar
            if (empty($this->project))
                $errors[] = 'No hay proyecto al que asignar la colaboración';
                //Text::get('validate-collaboration-noproject');
/*
            if (empty($this->support))
                $errors[] = 'No hay colaboración';
                //Text::get('validate-collaboration-name');

            if (!isset($this->description))
                $errors[] = 'No hay descripción de la colaboración';
                //Text::get('validate-collaboration-description');

            if (empty($this->type))
                $errors[] = 'No hay tipo de colaboración';
                //Text::get('validate-collaboration-type');
*/
            //cualquiera de estos errores hace fallar la validación
            if (!empty($errors))
                return false;
            else
                return true;
        }

		public function save (&$errors = array()) {
            if (!$this->validate($errors)) return false;

			$fields = array(
				'id',
				'project',
				'support',
				'type',
				'description',
                'thread'
				);

			$set = '';
			$values = array();

			foreach ($fields as $field) {
				if ($set != '') $set .= ", ";
				$set .= "$field = :$field ";
				$values[":$field"] = $this->$field;
			}

			try {
				$sql = "REPLACE INTO support SET " . $set;
				self::query($sql, $values);
    			if (empty($this->id)) $this->id = self::insertId();
				return true;
			} catch(\PDOException $e) {
				$errors[] = "La colaboración {$data['support']} no se ha grabado correctamente. Por favor, revise los datos." . $e->getMessage();
                return false;
			}
		}

		public function saveLang (&$errors = array()) {
			$fields = array(
				'id'=>'id',
				'lang'=>'lang',
				'support'=>'support_lang',
				'description'=>'description_lang'
				);

			$set = '';
			$values = array();

			foreach ($fields as $field=>$ffield) {
				if ($set != '') $set .= ", ";
				$set .= "$field = :$field ";
				$values[":$field"] = $this->$ffield;
			}

			try {
				$sql = "REPLACE INTO support_lang SET " . $set;
				self::query($sql, $values);
    			
				return true;
			} catch(\PDOException $e) {
				$errors[] = "La colaboración {$data['support']} no se ha grabado correctamente. Por favor, revise los datos." . $e->getMessage();
                return false;
			}
		}

		/**
		 * Quitar una colaboracion de un proyecto
		 *
		 * @param varchar(50) $project id de un proyecto
		 * @param INT(12) $id  identificador de la tabla support
		 * @param array $errors
		 * @return boolean
		 */
		public function remove (&$errors = array()) {
			$values = array (
				':project'=>$this->project,
				':id'=>$this->id,
			);

            try {
                self::query("DELETE FROM support WHERE id = :id AND project = :project", $values);

                //quitar el mensaje
                self::query("DELETE FROM message WHERE id = ?", array($this->thread));


				return true;
			} catch (\PDOException $e) {
                $errors[] = 'No se ha podido quitar la colaboracion del proyecto ' . $this->project . ' ' . $e->getMessage();
                //Text::get('remove-collaboration-fail');
                return false;
			}
		}

		public static function types() {
			return array(
				'task'=>Text::get('cost-type-task'),
				'lend'=>Text::get('cost-type-lend')
			);

		}

	}

}