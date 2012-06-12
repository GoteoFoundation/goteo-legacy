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

    use Goteo\Library\Check,
        Goteo\Library\Text;

    class Criteria extends \Goteo\Core\Model {

        public
            $id,
            $section,
            $title,
            $description,
            $order;

        /*
         *  Devuelve datos de un destacado
         */
        public static function get ($id) {
                $query = static::query("
                    SELECT
                        criteria.id as id,
                        criteria.section as section,
                        IFNULL(criteria_lang.title, criteria.title) as title,
                        IFNULL(criteria_lang.description, criteria.description) as description,
                        criteria.order as `order`
                    FROM    criteria
                    LEFT JOIN criteria_lang
                        ON  criteria_lang.id = criteria.id
                        AND criteria_lang.lang = :lang
                    WHERE criteria.id = :id
                    ", array(':id' => $id, ':lang'=>\LANG));
                $criteria = $query->fetchObject(__CLASS__);

                return $criteria;
        }

        /*
         * Lista de proyectos destacados
         */
        public static function getAll ($section = 'project') {

            $query = static::query("
                SELECT
                    criteria.id as id,
                    criteria.section as section,
                    IFNULL(criteria_lang.title, criteria.title) as title,
                    IFNULL(criteria_lang.description, criteria.description) as description,
                    criteria.order as `order`
                FROM    criteria
                LEFT JOIN criteria_lang
                    ON  criteria_lang.id = criteria.id
                    AND criteria_lang.lang = :lang
                WHERE criteria.section = :section
                ORDER BY `order` ASC, title ASC
                ", array(':section' => $section, ':lang'=>\LANG));
            
            return $query->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
        }

        public function validate (&$errors = array()) { 
            if (empty($this->section))
                $errors[] = 'Falta seccion';
                //Text::get('mandatory-criteria-section');

            if (empty($this->title))
                $errors[] = 'Falta título';
                //Text::get('mandatory-criteria-title');

            if (empty($errors))
                return true;
            else
                return false;
        }

        public function save (&$errors = array()) {
            if (!$this->validate($errors)) return false;

            $fields = array(
                'id',
                'section',
                'title',
                'description',
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
                $sql = "REPLACE INTO criteria SET " . $set;
                self::query($sql, $values);
                if (empty($this->id)) $this->id = self::insertId();

                $extra = array(
                    'section' => $this->section
                );
                Check::reorder($this->id, $this->move, 'criteria', 'id', 'order', $extra);

                return true;
            } catch(\PDOException $e) {
                $errors[] = "No se ha guardado correctamente. " . $e->getMessage();
                return false;
            }
        }

        /*
         * Para quitar una pregunta
         */
        public static function delete ($id) {
            
            $sql = "DELETE FROM criteria WHERE id = :id";
            if (self::query($sql, array(':id'=>$id))) {
                return true;
            } else {
                return false;
            }

        }

        /*
         * Para que una pregunta salga antes  (disminuir el order)
         */
        public static function up ($id) {
            $query = static::query("SELECT section FROM criteria WHERE id = ?", array($id));
            $criteria = $query->fetchObject();
            $extra = array(
                'section' => $criteria->section
            );
            return Check::reorder($id, 'up', 'criteria', 'id', 'order', $extra);
        }

        /*
         * Para que un proyecto salga despues  (aumentar el order)
         */
        public static function down ($id) {
            $query = static::query("SELECT section FROM criteria WHERE id = ?", array($id));
            $criteria = $query->fetchObject();
            $extra = array(
                'section' => $criteria->section
            );
            return Check::reorder($id, 'down', 'criteria', 'id', 'order', $extra);
        }

        /*
         * Orden para añadirlo al final
         */
        public static function next ($section = 'project') {
            $query = self::query('SELECT MAX(`order`) FROM criteria WHERE section = :section'
                , array(':section'=>$section));
            $order = $query->fetchColumn(0);
            return ++$order;

        }

        public static function sections () {
            return array(
                'project' => Text::get('criteria-project-section-header'),
                'owner' => Text::get('criteria-owner-section-header'),
                'reward' => Text::get('criteria-reward-section-header')
            );
        }


    }
    
}