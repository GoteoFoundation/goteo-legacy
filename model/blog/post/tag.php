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


namespace Goteo\Model\Blog\Post {

    class Tag extends \Goteo\Core\Model {

        public
            $id,
            $name,
            $blog;

        /*
         *  Devuelve datos de una comentario
         */
        public static function get ($id) {
                $query = static::query("
                    SELECT
                        tag.id as id,
                        IFNULL(tag_lang.name, tag.name) as name,
                        tag.blog as blog
                    FROM    tag
                    LEFT JOIN tag_lang
                        ON  tag_lang.id = tag.id
                        AND tag_lang.lang = :lang
                    WHERE tag.id = :id
                    ", array(':id' => $id, ':lang'=>\LANG));

                return $query->fetchObject(__CLASS__);
        }

        /*
         * Lista de tags
         * de un post si recibe el parametro
         */
        public static function getAll ($post = null) {

            $list = array();

            $values = array(':lang'=>\LANG);

            $sql = "
                SELECT
                    tag.id as id,
                    IFNULL(tag_lang.name, tag.name) as name
                FROM    tag
                LEFT JOIN tag_lang
                    ON  tag_lang.id = tag.id
                    AND tag_lang.lang = :lang
                ";
            
            if (!empty($post)) {
                $sql .= "INNER JOIN post_tag
                    ON tag.id = post_tag.tag
                    AND post_tag.post = :post
                    ";
                $values[':post'] = $post;
            }

            $sql .= "ORDER BY tag.name ASC";
            
            $query = static::query($sql, $values);
                
            foreach ($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $tag) {
                $list[$tag->id] = $tag->name;
            }

            return $list;
        }

        /*
         * Lista de tags para gestión
         * de un post si recibe el parametro
         */
        public static function getList ($blog) {

            $list = array();

            $sql = "
                SELECT
                    tag.id as id,
                    IFNULL(tag_lang.name, tag.name) as name,
                    (   SELECT
                        COUNT(post_tag.post)
                        FROM post_tag
                        WHERE post_tag.tag = tag.id
                    ) as used
                FROM    tag
                LEFT JOIN tag_lang
                    ON  tag_lang.id = tag.id
                    AND tag_lang.lang = :lang
                WHERE tag.blog = $blog
                ORDER BY tag.name ASC";

            $query = static::query($sql, array(':lang'=>\LANG));

            foreach ($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $tag) {
                $list[$tag->id] = $tag;
            }

            return $list;
        }


        public function validate (&$errors = array()) { 
            if (empty($this->name))
                $errors[] = 'Falta nombre';
                //Text::get('validate-tag-name');

            if (empty($errors))
                return true;
            else
                return false;
        }

        // para añadir un nuevo tag al blog
        public function save (&$errors = array()) {
            if (!$this->validate($errors)) return false;

            $fields = array(
                'id',
                'name',
                'blog'
                );

            $set = '';
            $values = array();

            foreach ($fields as $field) {
                if ($set != '') $set .= ", ";
                $set .= "`$field` = :$field ";
                $values[":$field"] = $this->$field;
            }

            try {
                $sql = "REPLACE INTO tag SET " . $set;
                self::query($sql, $values);
                if (empty($this->id)) $this->id = self::insertId();

                return true;
            } catch(\PDOException $e) {
                $errors[] = "No se ha guardado correctamente. " . $e->getMessage();
                return false;
            }
        }

        // para añadir un nuevo tag al post
        public function assign (&$errors = array()) {

            $fields = array(
                'tag',
                'post'
                );

            $set = '';
            $values = array();

            foreach ($fields as $field) {
                if ($set != '') $set .= ", ";
                $set .= "`$field` = :$field ";
                $values[":$field"] = $this->$field;
            }

            try {
                $sql = "REPLACE INTO post_tag SET " . $set;
                self::query($sql, $values);
                if (empty($this->id)) $this->id = self::insertId();

                return true;
            } catch(\PDOException $e) {
                $errors[] = "No se ha guardado correctamente. " . $e->getMessage();
                return false;
            }
        }

        /*
         * Para eliminar un tag
         */
        public static function delete ($id) {
            
            $sql = "DELETE FROM tag WHERE id = :id";
            if (self::query($sql, array(':id'=>$id))) {
                return true;
            } else {
                return false;
            }

        }

        /*
         * Para quitar un tag de un post
         */
        public static function remove ($tag) {

            $sql = "DELETE FROM post_tag WHERE tag = :tag";
            if (self::query($sql, array(':tag'=>$tag))) {
                return true;
            } else {
                return false;
            }

        }

    }
    
}