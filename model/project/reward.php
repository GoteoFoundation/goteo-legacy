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

    use \Goteo\Model\Icon,
        \Goteo\Model\License;

    class Reward extends \Goteo\Core\Model {

        public
            $id,
			$project,
			$reward,
			$description,
			$type = 'social',
			$icon,
            $other, // para el icono de otro, texto que diga el tipo
			$license,
			$amount,
			$units,
            $taken = 0, // recompensas comprometidas por aporte
            $none; // si no quedan unidades de esta recompensa

	 	public static function get ($id) {
            try {
                $query = static::query("SELECT * FROM reward WHERE id = :id", array(':id' => $id));
                return $query->fetchObject(__CLASS__);
            } catch(\PDOException $e) {
                throw new \Goteo\Core\Exception($e->getMessage());
            }
		}

		public static function getAll ($project, $type = 'social', $lang = null, $fulfilled = null, $icon = null) {
            try {
                $array = array();

                $values = array(
                    ':project' => $project,
                    ':type' => $type,
                    ':lang' => $lang
                );

                $sqlFilter = "";
                if (!empty($fulfilled)) {
                    $sqlFilter .= "    AND reward.fulsocial = :fulfilled";
                    $values[':fulfilled'] = $fulfilled == 'ok' ? 1 : 0;
                }
                if (!empty($icon)) {
                    $sqlFilter .= "    AND reward.icon = :icon";
                    $values[':icon'] = $icon;
                }

                $sql = "SELECT
                            reward.id as id,
                            reward.project as project,
                            IFNULL(reward_lang.reward, reward.reward) as reward,
                            IFNULL(reward_lang.description, reward.description) as description,
                            reward.type as type,
                            reward.icon as icon,
                            reward.other as other,
                            reward.license as license,
                            reward.amount as amount,
                            reward.units as units,
                            reward.fulsocial as fulsocial,
                            IFNULL(icon_lang.name, icon.name) as icon_name
                        FROM    reward
                        LEFT JOIN icon
                            ON icon.id = reward.icon
                        LEFT JOIN reward_lang
                            ON  reward_lang.id = reward.id
                            AND reward_lang.lang = :lang
                        LEFT JOIN icon_lang
                            ON  icon_lang.id = icon.id
                            AND icon_lang.lang = :lang
                        WHERE   project = :project
                            AND type= :type
                        $sqlFilter
                        ";

                $sql .= " ORDER BY reward.id ASC";

				$query = self::query($sql, $values);
				foreach ($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $item ) {

                    if ($item->icon == 'other' && !empty($item->other)) {
                        $item->icon_name = $item->other;
                    }

                    $array[$item->id] = $item;
                }
				return $array;
			} catch (\PDOException $e) {
                throw new \Goteo\Core\Exception($e->getMessage());
			}
		}

		public function validate(&$errors = array()) {
            // Estos son errores que no permiten continuar
            if (empty($this->project))
                $errors[] = 'No hay proyecto al que asignar la recompensa/rettorno';
                //Text::get('validate-reward-noproject');
/*
            if (empty($this->reward))
                $errors[] = 'No hay nombre de recompensa/retorno';
                //Text::get('validate-reward-name');

            if (empty($this->type))
                $errors[] = 'No hay tipo de recompensa/retorno';
                //Text::get('validate-reward-description');
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
				'reward',
				'description',
				'type',
				'icon',
				'other',
				'license',
				'amount',
				'units'
				);

			$set = '';
			$values = array();

			foreach ($fields as $field) {
				if ($set != '') $set .= ", ";
				$set .= "$field = :$field ";
				$values[":$field"] = $this->$field;
			}

			try {
				$sql = "REPLACE INTO reward SET " . $set;
				self::query($sql, $values);
            	if (empty($this->id)) $this->id = self::insertId();
        		return true;
			} catch(\PDOException $e) {
				$errors[] = "El retorno {$this->reward} no se ha grabado correctamente. Por favor, revise los datos." . $e->getMessage();
                return false;
			}
		}

		public function saveLang (&$errors = array()) {

			$fields = array(
				'id'=>'id',
				'lang'=>'lang',
				'reward'=>'reward_lang',
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
				$sql = "REPLACE INTO reward_lang SET " . $set;
				self::query($sql, $values);
            	
        		return true;
			} catch(\PDOException $e) {
				$errors[] = "El retorno {$this->reward} no se ha grabado correctamente. Por favor, revise los datos." . $e->getMessage();
                return false;
			}
		}

		/**
		 * Quitar un retorno de un proyecto
		 *
		 * @param varchar(50) $project id de un proyecto
		 * @param INT(12) $id  identificador de la tabla reward
		 * @param array $errors
		 * @return boolean
		 */
		public function remove (&$errors = array()) {
			$values = array (
				':project'=>$this->project,
				':id'=>$this->id,
			);

            try {
                self::query("DELETE FROM reward WHERE id = :id AND project = :project", $values);
				return true;
			} catch(\PDOException $e) {
				$errors[] = 'No se ha podido quitar el retorno '. $this->id. '. ' . $e->getMessage();
                //Text::get('remove-reward-fail');
                return false;
			}
		}

        /**
         * Calcula y actualiza las unidades de recompensa comprometidas por aporte
         * @param void
         * @return numeric
         */
        public function getTaken () {

            // cuantas de esta recompensa en aportes no cancelados
            $sql = "SELECT
                        COUNT(invest_reward.reward) as taken
                    FROM invest_reward
                    INNER JOIN invest
                        ON invest.id = invest_reward.invest
                        AND (invest.status = 0 OR invest.status = 1)
                        AND invest.project = :project
                    WHERE invest_reward.reward = :reward
                ";

            $values = array(
                ':project' => $this->project,
                ':reward' => $this->id
            );

            $query = self::query($sql, $values);
            if ($taken = $query->fetchColumn(0)) {
                return $taken;
            } else {
                return 0;
            }
        }

		public static function icons($type = 'social') {
            $list  = array();
            
            $icons = Icon::getAll($type);

            foreach ($icons as $icon) {
                $list[$icon->id] = $icon;
            }

            return $list;
		}

		public static function licenses() {
            $list  = array();

            $licenses = License::getAll();

            foreach ($licenses as $license) {
                $list[$license->id] = $license->name;
            }

            return $list;
		}

	}

}