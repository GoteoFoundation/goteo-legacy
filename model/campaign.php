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


namespace Goteo\Model {
    
    class Campaign extends \Goteo\Core\Model {

        public
            $id,
            $name,
            $description;

        /*
         *  Devuelve datos de una campaña
         */
        public static function get ($id) {
                $query = static::query("
                    SELECT
                        id,
                        name,
                        description
                    FROM    campaign
                    WHERE id = :id
                    ", array(':id' => $id));
                $campaign = $query->fetchObject(__CLASS__);

                return $campaign;
        }

        /*
         * Lista de campañas
         */
        public static function getAll () {

            $campaigns = array();

            $sql = "
                SELECT
                    campaign.id as id,
                    campaign.name as name
                FROM    campaign";

            $sql .= " ORDER BY name ASC";

            $query = static::query($sql);

            foreach ($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $campaign) {
                $campaigns[$campaign->id] = $campaign->name;
            }

            return $campaigns;
        }

        /*
         * Lista de campañas activas o disponibles o algo así
         */
        public static function getList () {

            $campaigns = array();

            $sql = "
                SELECT
                    campaign.id,
                    campaign.name,
                    (   SELECT
                        COUNT(invest.id)
                        FROM invest
                        WHERE invest.campaign = campaign.id
                        AND (invest.status = 0 OR invest.status = 1)
                    ) as used
                FROM    campaign
                ORDER BY campaign.name ASC
                ";

            $query = static::query($sql);

            foreach ($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $campaign) {
                $campaigns[$campaign->id] = $campaign;
            }

            return $campaigns;
        }

        public function validate (&$errors = array()) {
            if (empty($this->name))
                $errors[] = 'Falta nombre';
                //Text::get('mandatory-campaign-name');

            if (empty($errors))
                return true;
            else
                return false;
        }

        public function save (&$errors = array()) {
            if (!$this->validate($errors)) return false;

            $fields = array(
                'id',
                'name',
                'description'
                );

            $set = '';
            $values = array();

            foreach ($fields as $field) {
                if ($set != '') $set .= ", ";
                $set .= "`$field` = :$field ";
                $values[":$field"] = $this->$field;
            }

            try {
                $sql = "REPLACE INTO campaign SET " . $set;
                self::query($sql, $values);
                if (empty($this->id)) $this->id = self::insertId();

                return true;
            } catch(\PDOException $e) {
                $errors[] = "No se ha guardado correctamente. " . $e->getMessage();
                return false;
            }
        }

        /*
         * Para quitar un campaigno
         */
        public static function delete ($id) {

            $sql = "DELETE FROM campaign WHERE id = :id";
            if (self::query($sql, array(':id'=>$id))) {
                return true;
            } else {
                return false;
            }

        }

    }
    
}