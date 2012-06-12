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

    use Goteo\Model\Project\Media,
        Goteo\Model\Image,
        Goteo\Library\Check;

    class Post extends \Goteo\Core\Model {

        public
            $id,
            $title,
            $text,
            $image,
            $gallery = array(), // array de instancias image de post_image
            $media,
            $order;

        /*
         *  Devuelve datos de una entrada
         */
        public static function get ($id) {
                $query = static::query("
                    SELECT
                        post.id as id,
                        IFNULL(post_lang.title, post.title) as title,
                        IFNULL(post_lang.text, post.text) as `text`,
                        post.blog as blog,
                        post.image as image,
                        post.media as `media`,
                        DATE_FORMAT(post.date, '%d | %m | %Y') as fecha,
                        post.order as `order`
                    FROM    post
                    LEFT JOIN post_lang
                        ON  post_lang.id = post.id
                        AND post_lang.lang = :lang
                    WHERE post.id = :id
                    ", array(':id' => $id, ':lang'=>\LANG));

                $post = $query->fetchObject(__CLASS__);
                
                // galeria
                $post->gallery = Image::getAll($id, 'post');
                $post->image = $post->gallery[0];
                
                $post->media = new Media($post->media);

                return $post;

        }

        /*
         * Lista de entradas
         */
        public static function getAll ($position = 'home', $blog = 1) {

            if (!in_array($position, array('home', 'footer'))) {
                $position = 'home';
            }

            $list = array();

            $sql = "
                SELECT
                    post.id as id,
                    post.blog as blog,
                    IFNULL(post_lang.title, post.title) as title,
                    IFNULL(post_lang.text, post.text) as `text`,
                    post.image as `image`,
                    post.media as `media`,
                    post.order as `order`,
                    DATE_FORMAT(post.date, '%d-%m-%Y') as date,
                    DATE_FORMAT(post.date, '%d | %m | %Y') as fecha,
                    post.publish as publish,
                    post.home as home,
                    post.footer as footer
                FROM    post
                LEFT JOIN post_lang
                    ON  post_lang.id = post.id
                    AND post_lang.lang = :lang
                WHERE   post.blog = $blog
                AND     post.$position = 1
                ORDER BY `order` ASC, title ASC
                ";
            
            $query = static::query($sql, array(':lang'=>\LANG));
                
            foreach ($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $post) {
                // galeria
                $post->gallery = Image::getAll($post->id, 'post');
                $post->image = $post->gallery[0];

                $post->media = new Media($post->media);

                $post->type = $post->home == 1 ? 'home' : 'footer';

                $list[$post->id] = $post;
            }

            return $list;
        }

        /*
         * Entradas en portada o pie
         */
        //@FIXME essse blog a pi�on!
        public static function getList ($position = 'home', $blog = 1) {

            if (!in_array($position, array('home', 'footer'))) {
                $position = 'home';
            }

            $list = array();

            $sql = "
                SELECT
                    post.id as id,
                    IFNULL(post_lang.title, post.title) as title,
                    post.order as `order`
                FROM    post
                LEFT JOIN post_lang
                    ON  post_lang.id = post.id
                    AND post_lang.lang = :lang
                WHERE   post.blog = $blog
                AND     post.$position = 1
                AND     post.publish = 1
                ORDER BY `order` ASC, title ASC
                ";

            $query = static::query($sql, array(':lang'=>\LANG));

            foreach ($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $post) {
                $list[$post->id] = $post->title;
            }

            return $list;
        }

        public function validate (&$errors = array()) { 
            if (empty($this->title))
                $errors[] = 'Falta t�tulo';
                //Text::get('mandatory-post-title');

            if (empty($errors))
                return true;
            else
                return false;
        }

        public function save (&$errors = array()) {
            if (!$this->validate($errors)) return false;

            $fields = array(
                'id',
                'blog',
                'title',
                'text',
                'media',
                'legend',
                'order',
                'publish',
                'home',
                'footer'
                );

            $set = '';
            $values = array();

            foreach ($fields as $field) {
                if ($set != '') $set .= ", ";
                $set .= "`$field` = :$field ";
                $values[":$field"] = $this->$field;
            }

            try {
                $sql = "REPLACE INTO post SET " . $set;
                self::query($sql, $values);
                if (empty($this->id)) $this->id = self::insertId();

                return true;
            } catch(\PDOException $e) {
                $errors[] = "No se ha guardado correctamente. " . $e->getMessage();
                return false;
            }
        }

        public function update (&$errors = array()) {
            if (!$this->id) return false;

            $fields = array(
                'order',
                'home',
                'footer'
                );

            $set = '';
            $values = array(':id'=>$this->id);

            foreach ($fields as $field) {
                if (!isset ($this->$field))
                    continue;
                
                if ($set != '') $set .= ", ";
                $set .= "`$field` = :$field ";
                $values[":$field"] = $this->$field;
            }

            if ($set == '') return false;

            try {
                $sql = "UPDATE post SET " . $set . " WHERE post.id = :id";
                self::query($sql, $values);

                return true;
            } catch(\PDOException $e) {
                $errors[] = "No se ha guardado correctamente. " . $e->getMessage();
                return false;
            }
        }

        /*
         * Para quitar una entrada
         */
        public static function remove ($id, $from = null) {
            
            if (!in_array($from, array('home', 'footer'))) {
                return false;
            }

            $sql = "UPDATE post SET `$from`=0, `order`=NULL WHERE id = :id";
            if (self::query($sql, array(':id'=>$id))) {
                return true;
            } else {
                return false;
            }

        }

        /*
         * Para que salga antes  (disminuir el order)
         */
        //@FIXME essse blog a pi�on!
        public static function up ($id, $type = 'home') {
            $extra = array (
                    $type => 1,
                    'blog' => 1
                );
            return Check::reorder($id, 'up', 'post', 'id', 'order', $extra);
        }

        /*
         * Para que un proyecto salga despues  (aumentar el order)
         */
        //@FIXME essse blog a pi�on!
        public static function down ($id, $type = 'home') {
            $extra = array (
                    $type => 1,
                    'blog' => 1
                );
            return Check::reorder($id, 'down', 'post', 'id', 'order', $extra);
        }

        /*
         * Orden para a�adirlo al final
         */
        public static function next ($type = 'home') {
            $query = self::query('SELECT MAX(`order`) FROM post WHERE '.$type.'=1');
            $order = $query->fetchColumn(0);
            return ++$order;

        }

    }
    
}