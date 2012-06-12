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

    class Account extends \Goteo\Core\Model {

        public
            $project,
            $bank,
            $paypal;


        /**
         * Get the accounts for a project
         * @param varcahr(50) $id  Project identifier
         * @return array of accounts
         */
	 	public static function get ($id) {

            try {
                $query = static::query("SELECT project, bank, paypal FROM project_account WHERE project = ?", array($id));
                $accounts = $query->fetchObject(__CLASS__);
                if (!empty($accounts)) {
                    return $accounts;
                } else {
                    $accounts = new Account();
                    $accounts->project = $id;
                    return $accounts;
                }
            } catch(\PDOException $e) {
				throw new \Goteo\Core\Exception($e->getMessage());
            }
		}

		public function validate(&$errors = array()) {
            // Estos son errores que no permiten continuar
            if (empty($this->project)) {
                $errors[] = 'No hay ningun proyecto al que asignar cuentas';
                //Text::get('validate-account-noproject');
                return false;
            }

            return true;
        }

		public function save (&$errors = array()) {
            if (!$this->validate($errors)) return false;

			try {
	            $sql = "REPLACE INTO project_account (project, bank, paypal) VALUES(:project, :bank, :paypal)";
                $values = array(':project'=>$this->project, ':bank'=>$this->bank, ':paypal'=>$this->paypal);
				self::query($sql, $values);
				return true;
			} catch(\PDOException $e) {
				$errors[] = "Las cuentas no se han asignado correctamente. Por favor, revise los datos." . $e->getMessage();
                return false;
			}

		}

	}
    
}