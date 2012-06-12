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

    use \Goteo\Model\Project\Media,
        \Goteo\Model\Image;

    class Blog extends \Goteo\Core\Model {

        public
            $id,
            $type,
            $owner,
            $project,
            $node,
            $posts = array(),
            $active;

        /*
         *  Para conseguir el ide del blog de un proyecto o de un nodo
         *  Devuelve datos de un blog
         */
        public static function get ($owner, $type = 'project') {
                $query = static::query("
                    SELECT
                        id,
                        type,
                        owner,
                        active
                    FROM    blog
                    WHERE owner = :owner
                    AND type = :type
                    ", array(':owner' => $owner, ':type' => $type));
                
                $blog =  $query->fetchObject(__CLASS__);
                switch ($blog->type) {
                    case 'node':
                        $blog->node = $blog->owner;
                        break;
                    case 'project':
                        $blog->project = $blog->owner;
                        break;
                }
                $blog->posts = Blog\Post::getAll($blog->id);
                return $blog;
        }

        public function validate (&$errors = array()) {
            return true;
        }

        /*
         *  Para cuando se publica un proyecto o se crea un nodo
         */
        public function save (&$errors = array()) {
            if (!$this->validate($errors)) return false;

            $fields = array(
                'id',
                'type',
                'owner',
                'active'
                );

            $set = '';
            $values = array();

            foreach ($fields as $field) {
                if ($set != '') $set .= ", ";
                $set .= "`$field` = :$field ";
                $values[":$field"] = $this->$field;
            }

            try {
                $sql = "REPLACE INTO blog SET " . $set;
                self::query($sql, $values);
                if (empty($this->id)) $this->id = self::insertId();

                return true;
            } catch(\PDOException $e) {
                $errors[] = "No se ha guardado correctamente. " . $e->getMessage();
                return false;
            }
        }

    }
    
}