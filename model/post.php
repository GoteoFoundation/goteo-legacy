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

    use Goteo\Model\Project\Media,
        Goteo\Model\Image,
        Goteo\Model\Project,
        Goteo\Model\User,
        Goteo\Model\Node,
        Goteo\Library\Check;

    class Post extends \Goteo\Core\Model {

        public
            $id,
            $title,
            $text,
            $image,
            $gallery = array(), // array de instancias image de post_image
            $media,
            $author,
            $order,
            $node;  // las entradas en portada para nodos se guardan en la tabla post_node con unos metodos alternativos

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
                        post.author as author,
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

                // video
                $post->media = new Media($post->media);

                // autor
                if (!empty($post->author)) $post->user = User::getMini($post->author);

                return $post;

        }

        /*
         * Lista de entradas
         */
        public static function getAll ($position = 'home', $node = \GOTEO_NODE) {

            if (!in_array($position, array('home', 'footer'))) {
                $position = 'home';
            }

            $list = array();

            $values = array(':lang'=>\LANG);

            if ($node == \GOTEO_NODE || empty($node)) {
                // portada goteo, sacamos todas las de blogs tipo nodo
                // que esten marcadas en la tabla post
                $sqlFilter = " WHERE post.$position = 1
                    AND post.publish = 1
                    ";
                $sqlField = "post.order as `order`,";

            } else {
                // portada nodo, igualmente las entradas de blogs tipo nodo
                // perosolo la que esten en la tabla de entradas en portada de ese nodo
                $sqlFilter = " WHERE post.id IN (SELECT post FROM post_node WHERE node = :node)
                    AND post.publish = 1
                    ";
                $values[':node'] = $node;

                $sqlField = "(SELECT `order` FROM post_node WHERE node = :node AND post = post.id) as `order`,";
            }

            $sql = "
                SELECT
                    post.id as id,
                    post.blog as blog,
                    IFNULL(post_lang.title, post.title) as title,
                    IFNULL(post_lang.text, post.text) as `text`,
                    post.image as `image`,
                    post.media as `media`,
                    $sqlField
                    DATE_FORMAT(post.date, '%d-%m-%Y') as date,
                    DATE_FORMAT(post.date, '%d | %m | %Y') as fecha,
                    post.publish as publish,
                    post.author as author,
                    post.home as home,
                    post.footer as footer,
                    blog.type as owner_type,
                    blog.owner as owner_id
                FROM    post
                INNER JOIN blog
                    ON  blog.id = post.blog
                LEFT JOIN post_lang
                    ON  post_lang.id = post.id
                    AND post_lang.lang = :lang
                $sqlFilter
                ORDER BY `order` ASC, title ASC
                ";
            
            $query = static::query($sql, $values);
                
            foreach ($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $post) {
                // galeria
                $post->gallery = Image::getAll($post->id, 'post');
                $post->image = $post->gallery[0];

                $post->media = new Media($post->media);

                $post->type = $post->home == 1 ? 'home' : 'footer';
                
                // datos del autor
                switch ($post->owner_type) {
                    case 'project':
                        $proj_blog = Project::getMini($post->owner_id);
                        $post->author = $proj_blog->owner;
                        $post->user   = $proj_blog->user;
                        $post->owner_name = $proj_blog->name;
                        $sql = "UPDATE post SET author = '.$proj_blog->owner.' WHERE post.id = ?";
                        self::query($sql, array($post->id));
                        break;

                    case 'node':
                        $post->user   = User::getMini($post->author);
                        // (Nodesys)
                        break;
                }

                $list[$post->id] = $post;
            }

            return $list;
        }

        /*
         * Entradas en portada o pie
         */
        public static function getList ($position = 'home', $node = \GOTEO_NODE) {

            if (!in_array($position, array('home', 'footer'))) {
                $position = 'home';
            }

            $list = array();

            $values = array(':lang'=>\LANG);

            if ($node == \GOTEO_NODE || empty($node)) {
                // portada goteo, sacamos todas las de blogs tipo nodo
                // que esten marcadas en la tabla post
                $sqlFilter = " WHERE post.$position = 1
                ";

            } else {
                // portada nodo, igualmente las entradas de blogs tipo nodo
                // perosolo la que esten en la tabla de entradas en portada de ese nodo
                $sqlFilter = " WHERE post.id IN (SELECT post FROM post_node WHERE node = :node)
                    ";
                $values[':node'] = $node;
            }


            $sql = "
                SELECT
                    post.id as id,
                    IFNULL(post_lang.title, post.title) as title,
                    post.order as `order`
                FROM    post
                INNER JOIN blog
                    ON  blog.id = post.blog
                LEFT JOIN post_lang
                    ON  post_lang.id = post.id
                    AND post_lang.lang = :lang
                $sqlFilter
                ORDER BY `order` ASC, title ASC
                ";

            $query = static::query($sql, $values);

            foreach ($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $post) {
                $list[$post->id] = $post->title;
            }

            return $list;
        }

        public function validate (&$errors = array()) { 
            if (empty($this->title))
                $errors[] = Text::_('Falta título');

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
                'footer',
                'author'
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
                $errors[] = Text::_("No se ha guardado correctamente. ") . $e->getMessage();
                return false;
            }
        }

        /*
         *  Actualizar una entrada en portada
         * si es de nodo se guarda en otra tabla con el metodo update_node
         */
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

            if ($set == '') {
                $errors[] = Text::_('Sin datos');
                return false;
            }

            try {
                $sql = "UPDATE post SET " . $set . " WHERE post.id = :id";
                self::query($sql, $values);

                return true;
            } catch(\PDOException $e) {
                $errors[] = Text::_("No se ha guardado correctamente. ") . $e->getMessage();
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
        public static function up ($id, $type = 'home') {
            $extra = array (
                    $type => 1
                );
            return Check::reorder($id, 'up', 'post', 'id', 'order', $extra);
        }

        /*
         * Para que un proyecto salga despues  (aumentar el order)
         */
        public static function down ($id, $type = 'home') {
            $extra = array (
                    $type => 1
                );
            return Check::reorder($id, 'down', 'post', 'id', 'order', $extra);
        }

        /*
         * Orden para aÃ±adirlo al final
         */
        public static function next ($type = 'home') {
            $query = self::query('SELECT MAX(`order`) FROM post WHERE '.$type.'=1');
            $order = $query->fetchColumn(0);
            return ++$order;

        }


        /****************************************************
        * Variantes de los metodos para las portadas de nodo *
         ****************************************************/
        /*
         *  Actualizar una entrada en portada
         */
        public function update_node ($data, &$errors = array()) {
            if (!$data->post || !$data->node) return false;

            $fields = array(
                'post',
                'node',
                'order'
                );

            $set = '';
            $values = array();

            foreach ($fields as $field) {
                if ($set != '') $set .= ", ";
                $set .= "`$field` = :$field ";
                $values[":$field"] = $data->$field;
            }

            if ($set == '') {
                $errors[] = 'Sin datos';
                return false;
            }

            try {
                $sql = "REPLACE INTO post_node SET " . $set;
                self::query($sql, $values);

                return true;
            } catch(\PDOException $e) {
                $errors[] = "Ha fallado!!! " . $e->getMessage();
                return false;
            }
        }

        /*
         * Para quitar una entrada
         */
        public static function remove_node ($post, $node) {

            $values = array(':post'=>$post, ':node'=>$node);
            $sql = "DELETE FROM post_node WHERE post = :post AND node = :node";
            if (self::query($sql, $values)) {
                return true;
            } else {
                return false;
            }

        }

        /*
         * Para que salga antes  (disminuir el order)
         */
        public static function up_node ($post, $node) {
            $extra = array (
                    'node' => $node
                );
            return Check::reorder($post, 'up', 'post_node', 'post', 'order', $extra);
        }

        /*
         * Para que un proyecto salga despues  (aumentar el order)
         */
        public static function down_node ($post, $node) {
            $extra = array (
                    'node' => $node
                );
            return Check::reorder($post, 'down', 'post_node', 'post', 'order', $extra);
        }

        /*
         * Orden para aÃ±adirlo al final
         */
        public static function next_node ($node) {
            $query = self::query('SELECT MAX(`order`) FROM post_node WHERE node = :node', array(':node'=>$node));
            $order = $query->fetchColumn(0);
            return ++$order;

        }

    }
    
}