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

    class Faq extends \Goteo\Core\Model {

        public
            $id,
            $node,
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
                        faq.id as id,
                        faq.node as node,
                        faq.section as section,
                        IFNULL(faq_lang.title, faq.title) as title,
                        IFNULL(faq_lang.description, faq.description) as description,
                        faq.order as `order`
                    FROM faq
                    LEFT JOIN faq_lang
                        ON  faq_lang.id = faq.id
                        AND faq_lang.lang = :lang
                    WHERE faq.id = :id
                    ", array(':id' => $id, ':lang'=>\LANG));
                $faq = $query->fetchObject(__CLASS__);

                return $faq;
        }

        /*
         * Lista de proyectos destacados
         */
        public static function getAll ($section = 'node') {

            $values = array(':section' => $section);

            $sql = "
                SELECT
                    faq.id as id,
                    faq.node as node,
                    faq.section as section,";

            if (\LANG != 'es') {
                $sql .= "
                    faq_lang.title as title,
                    faq_lang.description as description,";
            } else {
                $sql .= "
                    faq.title as title,
                    faq.description as description,";
            }

            $sql .= "
                    faq.order as `order`
                FROM faq";

            if (\LANG != 'es') {
                $sql .= "
                INNER JOIN faq_lang
                    ON  faq_lang.id = faq.id
                    AND faq_lang.lang = :lang
                ";
                $values[':lang'] = \LANG;
            }

            $sql .= "
                WHERE faq.section = :section
                ORDER BY `order` ASC, title ASC
                ";

            $query = static::query($sql, $values);
            
            return $query->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
        }

        public function validate (&$errors = array()) { 
            if (empty($this->node))
                $errors[] = 'Falta nodo';
                //Text::get('mandatory-faq-node');

            if (empty($this->section))
                $errors[] = 'Falta seccion';
                //Text::get('mandatory-faq-section');

            if (empty($this->title))
                $errors[] = 'Falta título';
                //Text::get('mandatory-faq-title');

            if (empty($errors))
                return true;
            else
                return false;
        }

        public function save (&$errors = array()) {
            if (!$this->validate($errors)) return false;

            $fields = array(
                'id',
                'node',
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
                $sql = "REPLACE INTO faq SET " . $set;
                self::query($sql, $values);
                if (empty($this->id)) $this->id = self::insertId();

                $extra = array(
                    'section' => $this->section,
                    'node' => $this->node
                );
                Check::reorder($this->id, $this->move, 'faq', 'id', 'order', $extra);

                return true;
            } catch(\PDOException $e) {
                $errors[] = "No se ha guardado correctamente. " . $e->getMessage();
                return false;
            }
        }

        /*
         * Para quitar una pregunta
         */
        public static function delete ($id, $node = \GOTEO_NODE) {
            
            $sql = "DELETE FROM faq WHERE id = :id AND node = :node";
            if (self::query($sql, array(':id'=>$id, ':node'=>$node))) {
                return true;
            } else {
                return false;
            }

        }

        /*
         * Para que una pregunta salga antes  (disminuir el order)
         */
        public static function up ($id, $node = \GOTEO_NODE) {
            $query = static::query("SELECT section FROM faq WHERE id = ?", array($id));
            $faq = $query->fetchObject();
            $extra = array(
                'section' => $faq->section,
                'node' => $node
            );
            return Check::reorder($id, 'up', 'faq', 'id', 'order', $extra);
        }

        /*
         * Para que un proyecto salga despues  (aumentar el order)
         */
        public static function down ($id, $node = \GOTEO_NODE) {
            $query = static::query("SELECT section FROM faq WHERE id = ?", array($id));
            $faq = $query->fetchObject();
            $extra = array(
                'section' => $faq->section,
                'node' => $node
            );
            return Check::reorder($id, 'down', 'faq', 'id', 'order', $extra);
        }

        /*
         * Orden para añadirlo al final
         */
        public static function next ($section = 'node', $node = \GOTEO_NODE) {
            $query = self::query('SELECT MAX(`order`) FROM faq WHERE section = :section AND node = :node'
                , array(':section'=>$section, ':node'=>$node));
            $order = $query->fetchColumn(0);
            return ++$order;

        }

        public static function sections () {
            return array(
                'node' => Text::get('faq-main-section-header'),
                'project' => Text::get('faq-project-section-header'),
                'sponsor' => Text::get('faq-sponsor-section-header'),
                'investors' => Text::get('faq-investors-section-header'),
                'nodes' => Text::get('faq-nodes-section-header')
            );
        }

        public static function colors () {
            return array(
                'node' => '#808285',
                'project' => '#20b3b2',
                'sponsor' => '#96238f',
                'investors' => '#0c4e99',
                'nodes' => '#8f8f8f'
            );
        }


    }
    
}