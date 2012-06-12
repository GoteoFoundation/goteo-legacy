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

    use Goteo\Library\Text,
        Goteo\Model\Project,
        Goteo\Model\Image,
        Goteo\Library\Check;

    class Banner extends \Goteo\Core\Model {

        public
            $id,
            $node,
            $project,
            $image,
            $order;

        /*
         *  Devuelve datos de un banner de proyecto
         */
        public static function get ($project, $node = \GOTEO_NODE) {
                $query = static::query("
                    SELECT  
                        banner.id as id,
                        banner.node as node,
                        banner.project as project,
                        project.name as name,
                        banner.image as image,
                        banner.order as `order`
                    FROM    banner
                    INNER JOIN project
                        ON project.id = banner.project
                    WHERE banner.project = :project
                    AND banner.node = :node
                    ", array(':project'=>$project, ':node'=>$node));
                $banner = $query->fetchObject(__CLASS__);

                $banner->image = Image::get($banner->image);



                return $banner;
        }

        /*
         * Lista de proyectos en banners
         */
        public static function getAll ($node = \GOTEO_NODE) {

            // estados
            $status = Project::status();

            $banners = array();

            $query = static::query("
                SELECT
                    banner.id as id,
                    banner.project as project,
                    project.name as name,
                    project.status as status,
                    banner.image as image,
                    banner.order as `order`
                FROM    banner
                INNER JOIN project
                    ON project.id = banner.project
                WHERE banner.node = :node
                ORDER BY `order` ASC
                ", array(':node' => $node));
            
            foreach($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $banner) {
                $banner->image = Image::get($banner->image);
                $banner->status = $status[$banner->status];
                $banners[] = $banner;
            }

            return $banners;
        }

        /*
         * Lista de proyectos disponibles para destacar
         */
        public static function available ($current = null, $node = \GOTEO_NODE) {

            if (!empty($current)) {
                $sqlCurr = " AND banner.project != '$current'";
            } else {
                $sqlCurr = "";
            }

            $query = static::query("
                SELECT
                    project.id as id,
                    project.name as name,
                    project.status as status
                FROM    project
                WHERE status > 2
                AND status < 6
                AND project.id NOT IN (SELECT project FROM banner WHERE banner.node = :node{$sqlCurr} )
                ORDER BY name ASC
                ", array(':node' => $node));

            return $query->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
        }


        public function validate (&$errors = array()) {
            /*
            if (empty($this->node))
                $errors[] = 'Falta nodo';
                //Text::get('mandatory-banner-node');
*/
            if (empty($this->project))
                $errors[] = 'Falta proyecto';
                //Text::get('validate-banner-noproject');

            if (empty($this->image))
                $errors[] = 'Falta imagen';
                //Text::get('validate-banner-noproject');

            if (empty($errors))
                return true;
            else
                return false;
        }

        public function save (&$errors = array()) {
            if (!$this->validate($errors)) return false;

            // Imagen de fondo de banner
            if (is_array($this->image) && !empty($this->image['name'])) {
                $image = new Image($this->image);
                if ($image->save()) {
                    $this->image = $image->id;
                }
            }

            $fields = array(
                'node',
                'project',
                'image',
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
                $sql = "REPLACE INTO banner SET " . $set;
                self::query($sql, $values);
                if (empty($this->id)) $this->id = self::insertId();

                return true;
            } catch(\PDOException $e) {
                $errors[] = "No se ha guardado correctamente. " . $e->getMessage();
                return false;
            }
        }

        /*
         * Para quitar un proyecto banner
         */
        public static function delete ($project, $node = \GOTEO_NODE) {
            
            $sql = "DELETE FROM banner WHERE project = :project AND node = :node";
            if (self::query($sql, array(':project'=>$project, ':node'=>$node))) {
                return true;
            } else {
                return false;
            }

        }

        /*
         * Para que un proyecto salga antes  (disminuir el order)
         */
        public static function up ($project, $node = \GOTEO_NODE) {
            $extra = array (
                    'node' => $node
                );
            return Check::reorder($project, 'up', 'banner', 'project', 'order', $extra);
        }

        /*
         * Para que un proyecto salga despues  (aumentar el order)
         */
        public static function down ($project, $node = \GOTEO_NODE) {
            $extra = array (
                    'node' => $node
                );
            return Check::reorder($project, 'down', 'banner', 'project', 'order', $extra);
        }

        /*
         *
         */
        public static function next ($node = \GOTEO_NODE) {
            $query = self::query('SELECT MAX(`order`) FROM banner WHERE node = :node'
                , array(':node'=>$node));
            $order = $query->fetchColumn(0);
            return ++$order;

        }


    }
    
}