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

                if ($blog->node == \GOTEO_NODE) {
                    $blog->posts = Blog\Post::getAll();
                } elseif (!empty($blog->id)) {
                    $blog->posts = Blog\Post::getAll($blog->id);
                } else {
                    $blog->posts = array();
                }

            return $blog;
        }

        /*
         *  Listado simple de blogs de proyecto
         */
        public static function getListProj () {

            $list = array();

            $query = static::query("
                SELECT
                    blog.id as id,
                    project.name as name
                FROM    blog
                INNER JOIN post
                    ON post.blog = blog.id
                INNER JOIN project
                    ON project.id = blog.owner
                WHERE blog.type = 'project'
                ");

            foreach ($query->fetchAll(\PDO::FETCH_OBJ) as $item) {
                $list[$item->id] = $item->name;
            }

            return $list;
        }

        /*
         *  Listado simple de blogs de nodo
         */
        public static function getListNode () {

            $list = array();

            $query = static::query("
                SELECT
                    blog.id as id,
                    node.name as name
                FROM    blog
                INNER JOIN post
                    ON post.blog = blog.id
                INNER JOIN node
                    ON node.id = blog.owner
                WHERE blog.type = 'node'
                ");

            foreach ($query->fetchAll(\PDO::FETCH_OBJ) as $item) {
                $list[$item->id] = $item->name;
            }

            return $list;
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
                if (self::query($sql, $values)) {
                    if (empty($this->id)) $this->id = self::insertId();
                    return true;
                } else {
                    return false;
                }
            } catch(\PDOException $e) {
                $errors[] = Text::_("No se ha guardado correctamente. ") . $e->getMessage();
                return false;
            }
        }

        /*
         *  Para saber si un proyecto tiene novedades publicadas
         */
        public static function hasUpdates ($project) {
            $sql = "
                    SELECT
                        COUNT(post.id) as num
                    FROM post
                    INNER JOIN blog
                        ON  post.blog = blog.id
                        AND blog.type = 'project'
                        AND blog.owner = :project
                    WHERE post.publish = 1
                    ";

                $query = static::query($sql, array(':project' => $project));
                $num = $query->fetchColumn(0);
                return ($num > 0);
        }
        
    }
    
}