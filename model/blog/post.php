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

namespace Goteo\Model\Blog {

    use \Goteo\Model\Project\Media,
        \Goteo\Model\Image,
        \Goteo\Model\Project,
        \Goteo\Model\User,
        \Goteo\Library\Text,
        \Goteo\Library\Message;

    class Post extends \Goteo\Core\Model {

        public
            $id,
            $blog,
            $title,
            $text,
            $image,
            $media,
            $legend,
            $date,
            $publish,
            $home,
            $footer,
            $author,
            $owner,
            $tags = array(),
            $gallery = array(), // array de instancias image de post_image
            $num_comments = 0,
            $comments = array();

        /*
         *  Devuelve datos de una entrada
         */
        public static function get ($id, $lang = null) {
                $query = static::query("
                    SELECT
                        post.id as id,
                        post.blog as blog,
                        IFNULL(post_lang.title, post.title) as title,
                        IFNULL(post_lang.text, post.text) as text,
                        IFNULL(post_lang.legend, post.legend) as legend,
                        post.image as `image`,
                        IFNULL(post_lang.media, post.media) as `media`,
                        post.date as `date`,
                        DATE_FORMAT(post.date, '%d | %m | %Y') as fecha,
                        post.allow as allow,
                        post.publish as publish,
                        post.home as home,
                        post.footer as footer,
                        post.author as author,
                        CONCAT(blog.type, '-', blog.owner) as owner
                    FROM    post
                    INNER JOIN blog
                        ON  blog.id = post.blog
                    LEFT JOIN post_lang
                        ON  post_lang.id = post.id
                        AND post_lang.lang = :lang
                    WHERE post.id = :id
                    ", array(':id' => $id, ':lang'=>$lang));

                $post = $query->fetchObject(__CLASS__);

                // video
                if (isset($post->media)) {
                    $post->media = new Media($post->media);
                }

                // galeria
                $post->gallery = Image::getAll($id, 'post');
                $post->image = $post->gallery[0];

                $post->comments = Post\Comment::getAll($id);
                $post->num_comments = count($post->comments);

                //tags
                $post->tags = Post\Tag::getAll($id);

                // autor
                if (!empty($post->author)) $post->user = User::getMini($post->author);
                
                return $post;
        }

        /*
         * Lista de entradas
         * de mas nueva a mas antigua
         * // si es portada son los que se meten por la gestion de entradas en portada que llevan el tag 1 'Portada'
         */
        public static function getAll ($blog = null, $limit = null, $published = true) {
            $list = array();

            $values = array(':lang'=>\LANG);
            
            $sql = "
                SELECT
                    post.id as id,
                    post.blog as blog,
                    blog.type as type,
                    blog.owner as owner,
                    IFNULL(post_lang.title, post.title) as title,
                    IFNULL(post_lang.text, post.text) as `text`,
                    IFNULL(post_lang.legend, post.legend) as `legend`,
                    post.image as `image`,
                    IFNULL(post_lang.media, post.media) as `media`,
                    DATE_FORMAT(post.date, '%d-%m-%Y') as date,
                    DATE_FORMAT(post.date, '%d | %m | %Y') as fecha,
                    post.publish as publish,
                    post.home as home,
                    post.footer as footer,
                    post.author as author,
                    blog.type as owner_type,
                    blog.owner as owner_id
                FROM    post
                INNER JOIN blog
                    ON  blog.id = post.blog
                LEFT JOIN post_lang
                    ON  post_lang.id = post.id
                    AND post_lang.lang = :lang
                ";
            if (!empty($blog)) {
                $sql .= " WHERE post.blog = :blog
                ";
                $values[':blog'] = $blog;
            } else {
                $sql .= " WHERE blog.type = 'node'
                ";
            }
            // solo las entradas publicadas
            if ($published) {
                $sql .= " AND post.publish = 1
                ";
                // @NODESYS
                /*
                if (empty($blog)) {
                $sql .= " AND blog.owner IN (SELECT id FROM node WHERE active = 1)
                    AND blog.owner != 'testnode'
                ";
                }
                */
            }
            $sql .= "ORDER BY post.date DESC, post.id DESC
                ";
            if (!empty($limit)) {
                $sql .= "LIMIT $limit";
            }

            $query = static::query($sql, $values);
                
            foreach ($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $post) {
                // galeria
                $post->gallery = Image::getAll($post->id, 'post');
                $post->image = $post->gallery[0];

                // video
                if (!empty($post->media)) {
                    $post->media = new Media($post->media);
                }
                
                $post->num_comments = Post\Comment::getCount($post->id);

                $post->tags = Post\Tag::getAll($post->id);

                // reconocimiento de enlaces y saltos de linea
//                $post->text = nl2br(Text::urlink($post->text));

                // datos del autor
                switch ($post->owner_type) {
                    case 'project':
                        $proj_blog = Project::getMini($post->owner_id);
                        $post->author = $proj_blog->owner;
                        $post->user   = $proj_blog->user;
                        $post->owner_name = $proj_blog->name;
                        break;

                    case 'node':
                        $post->user   = User::getMini($post->author);
                        // @NODESYS
                        //$node_blog = Node::get($post->owner_id);
                        $post->owner_name = $post->user->name;
                        break;
                }

                $list[$post->id] = $post;
            }

            return $list;
        }

        /*
         * Lista de entradas filtradas
         *  por tag
         * de mas nueva a mas antigua
         */
        public static function getList ($filters = array(), $published = true) {

            $values = array(':lang'=>\LANG);

            $list = array();

            $sql = "
                SELECT
                    post.id as id,
                    post.blog as blog,
                    IFNULL(post_lang.title, post.title) as title,
                    IFNULL(post_lang.text, post.text) as `text`,
                    IFNULL(post_lang.legend, post.legend) as `legend`,
                    post.image as `image`,
                    post.media as `media`,
                    DATE_FORMAT(post.date, '%d-%m-%Y') as fecha,
                    post.publish as publish,
                    post.home as home,
                    post.footer as footer,
                    post.author as author,
                    blog.type as owner_type,
                    blog.owner as owner_id
                FROM    post
                INNER JOIN blog
                    ON  blog.id = post.blog
                LEFT JOIN post_lang
                    ON  post_lang.id = post.id
                    AND post_lang.lang = :lang
                ";

            if (in_array($filters['show'], array('all', 'home', 'footer'))) {
                $sql .= " WHERE blog.id IS NOT NULL
                ";
            } elseif ($filters['show'] == 'updates') {
                $sql .= " WHERE blog.type = 'project'
                ";
            } else {
                $sql .= " WHERE blog.type = 'node'
                ";
            }

            if (!empty($filters['blog'])) {
                $sql .= " AND post.blog = :blog
                ";
                $values[':blog'] = $filters['blog'];
            }

            if (!empty($filters['tag'])) {
                $sql .= " AND post.id IN (SELECT post FROM post_tag WHERE tag = :tag)
                ";
                $values[':tag'] = $filters['tag'];
            }

            if (!empty($filters['author'])) {
                $sql .= " AND post.author = :author
                ";
                $values[':author'] = $filters['author'];
            }

            // solo las publicadas
            if ($published || $filters['show'] == 'published') {
                $sql .= " AND post.publish = 1
                ";
                if (empty($filters['blog'])) {
                $sql .= " AND blog.owner IN (SELECT id FROM node WHERE active = 1)
                    AND blog.owner != 'testnode'
                ";
                }
            }

            // solo las del propio blog
            if ($filters['show'] == 'owned') {
                $sql .= " AND blog.owner = :node
                ";
                $values[':node'] = $filters['node'];
            }

            // solo las de la portada
            if ($filters['show'] == 'home') {
                if ($filters['node'] == \GOTEO_NODE) {
                    $sql .= " AND post.home = 1
                    ";
                } else {
                    $sql .= " AND post.id IN (SELECT post FROM post_node WHERE node = :node)
                    ";
                    $values[':node'] = $filters['node'];
                }
            }

            if ($filters['show'] == 'footer') {
                if ($filters['node'] == \GOTEO_NODE) {
                    $sql .= " AND post.footer = 1
                    ";
                }
            }

            $sql .= "
                ORDER BY post.date DESC, post.id DESC
                ";

            $query = static::query($sql, $values);

            foreach ($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $post) {
                // galeria
                $post->gallery = Image::getAll($post->id, 'post');
                $post->image = $post->gallery[0];

                // video
                if (isset($post->media)) {
                    $post->media = new Media($post->media);
                }

                $post->num_comments = Post\Comment::getCount($post->id);

                // datos del autor del  post
                switch ($post->owner_type) {
                    case 'project':
                        $proj_blog = Project::getMini($post->owner_id);
                        $post->author = $proj_blog->owner;
                        $post->user   = $proj_blog->user;
                        $post->owner_name = $proj_blog->name;
                        break;

                    case 'node':
                        $post->user   = User::getMini($post->author);
                        // @NODESYS
                        // $node_blog = Node::get($post->owner_id);
                        $post->owner_name = $post->user->name;
                        break;
                }

                $list[$post->id] = $post;
            }

            return $list;
        }

        public function validate (&$errors = array()) { 
            if (empty($this->title))
                $errors['title'] = 'Falta tÃ­tulo';

            if (empty($this->text))
                $errors['text'] = 'Falta texto';

            if (empty($this->date))
                $errors['date'] = 'Falta fecha';

            if (empty($errors))
                return true;
            else
                return false;
        }

        public function save (&$errors = array()) {
            if (empty($this->blog)) return false;

            $fields = array(
                'id',
                'blog',
                'title',
                'text',
                'media',
                'legend',
                'date',
                'allow',
                'publish',
                'home',
                'footer',
                'author'
                );

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

                // Luego la imagen
                if (!empty($this->id) && is_array($this->image) && !empty($this->image['name'])) {
                    $image = new Image($this->image);
                    if ($image->save($errors)) {
                        $this->gallery[] = $image;
//                        $this->image = $image->id;

                        /**
                         * Guarda la relaciÃ³n NM en la tabla 'post_image'.
                         */
                        if(!empty($image->id)) {
                            self::query("REPLACE post_image (post, image) VALUES (:post, :image)", array(':post' => $this->id, ':image' => $image->id));
                        }
                    }
                    else {
                        Message::Error(Text::get('image-upload-fail') . implode(', ', $errors));
                    }
                }

                // y los tags, si hay
                if (!empty($this->id) && is_array($this->tags)) {
                    static::query('DELETE FROM post_tag WHERE post= ?', $this->id);
                    foreach ($this->tags as $tag) {
                        $new = new Post\Tag(
                                array(
                                    'post' => $this->id,
                                    'tag' => $tag
                                )
                            );
                        $new->assign($errors);
                        unset($new);
                    }
                }

                return true;
            } catch(\PDOException $e) {
                $errors[] = Text::_("No se ha guardado correctamente. ") . $e->getMessage();
                return false;
            }
        }

        public function saveLang (&$errors = array()) {

            $fields = array(
                'id'=>'id',
                'lang'=>'lang',
                'title'=>'title_lang',
                'text'=>'text_lang',
                'media'=>'media_lang',
                'legend'=>'legend_lang'
                );

            $values = array();

            foreach ($fields as $field=>$ffield) {
                if ($set != '') $set .= ", ";
                $set .= "`$field` = :$field ";
                $values[":$field"] = $this->$ffield;
            }

            try {
                $sql = "REPLACE INTO post_lang SET " . $set;
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
        public static function delete ($id) {
            
            $sql = "DELETE FROM post WHERE id = :id";
            if (self::query($sql, array(':id'=>$id))) {

                // que elimine tambien sus imÃ¡genes
                $sql = "DELETE FROM post_image WHERE post = :id";
                self::query($sql, array(':id'=>$id));

                return true;
            } else {
                return false;
            }

        }

        /*
         *  Para saber si una entrada permite comentarios
         */
        public static function allowed ($id) {
                $query = static::query("
                    SELECT
                        allow
                    FROM    post
                    WHERE id = :id
                    ", array(':id' => $id));

                $post = $query->fetchObject(__CLASS__);

                if ($post->allow > 0) {
                    return true;
                } else {
                    return false;
                }
        }

    }
    
}