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

    use Goteo\Library\Text;

    class Icon extends \Goteo\Core\Model {

        public
            $id,
            $name,
            $description,
            $group,  // agrupación de iconos 'social' = Retornos colectivos    'individual' = Recompensas individuales
            $licenses; // licencias relacionadas con este tipo de retorno (solo para retornos colectivos)

        /*
         *  Devuelve datos de un icono
         */
        public static function get ($id) {
                $query = static::query("
                    SELECT
                        icon.id as id,
                        IFNULL(icon_lang.name, icon.name) as name,
                        IFNULL(icon_lang.description, icon.description) as description,
                        icon.group as `group`,
                        icon.group as `order`
                    FROM    icon
                    LEFT JOIN  icon_lang
                        ON  icon_lang.id = icon.id
                        AND icon_lang.lang = :lang
                    WHERE icon.id = :id
                    ", array(':id' => $id, ':lang'=>\LANG));
                $icon = $query->fetchObject(__CLASS__);

                return $icon;
        }

        /*
         * Lista de iconos de recompensa
         */
        public static function getAll ($group = '') {

            $values = array(':lang'=>\LANG);

            $icons = array();

            $sql = "
                SELECT
                    icon.id as id,
                    IFNULL(icon_lang.name, icon.name) as name,
                    IFNULL(icon_lang.description, icon.description) as description,
                    icon.group as `group`
                FROM    icon
                LEFT JOIN  icon_lang
                    ON  icon_lang.id = icon.id
                    AND icon_lang.lang = :lang
                ";

            if ($group != '') {
                // de un grupo o de todos
                $sql .= " WHERE icon.group = :group OR icon.group IS NULL OR icon.group = ''";
                $values[':group'] = $group;
            }

            $sql .= " ORDER BY `order` ASC, name ASC";

            $query = static::query($sql, $values);

            foreach ($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $icon) {
                if ($group == 'social') {
                    $icon->licenses = License::getAll($icon->id);
                }
                $icons[$icon->id] = $icon;
            }

            return $icons;
        }

        /*
         * Lista de iconos que se usen en proyectos 
         */
        public static function getList ($group = '') {

            $values = array(':lang'=>\LANG);

            $icons = array();

            $sql = "
                SELECT
                    icon.id,
                    IFNULL(icon_lang.name, icon.name) as name
                FROM    icon
                LEFT JOIN  icon_lang
                    ON  icon_lang.id = icon.id
                    AND icon_lang.lang = :lang
                INNER JOIN reward
                    ON icon.id = reward.icon
                ";

            if ($group != '') {
                // de un grupo o de todos
                $sql .= " WHERE icon.group = :group OR icon.group IS NULL OR icon.group = ''";
                $values[':group'] = $group;
            }

            $sql .= "
                GROUP BY icon.id
                ORDER BY icon.name ASC
                ";

            $query = static::query($sql, $values);

            foreach ($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $icon) {
                $icons[$icon->id] = $icon;
            }

            return $icons;
        }

        public function validate (&$errors = array()) { 
            if (empty($this->name))
                $errors[] = 'Falta nombre';
                //Text::get('mandatory-icon-name');

            if (empty($errors))
                return true;
            else
                return false;
        }

        public function save (&$errors = array()) {
            if (!$this->validate($errors)) return false;

            if (empty($this->group)) {
                $this->group = null;
            }

            $fields = array(
                'id',
                'name',
                'description',
                'group',
                'order'
                );

            $set = '';
            $values = array();

            foreach ($fields as $field) {
                if ($set != '') $set .= ", ";
                $set .= "`$field` = :$field ";
                $values[":$field"] = $this->$field;
            }

            try {
                $sql = "REPLACE INTO icon SET " . $set;
                self::query($sql, $values);
                if (empty($this->id)) $this->id = self::insertId();

                return true;
            } catch(\PDOException $e) {
                $errors[] = "No se ha guardado correctamente. " . $e->getMessage();
                return false;
            }
        }

        /*
         * Para quitar un icono
         */
        public static function delete ($id) {
            
            $sql = "DELETE FROM icon WHERE id = :id";
            if (self::query($sql, array(':id'=>$id))) {
                return true;
            } else {
                return false;
            }

        }

        public static function groups () {
            return array(
                'social' => 'Retorno colectivo',
                'individual' => 'Recompensa individual'
            );
        }


    }
    
}