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


namespace Goteo\Model\Blog {

    use \Goteo\Model\Project\Media,
        \Goteo\Model\Image,
        \Goteo\Library\Text;

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
                        post.footer as footer
                    FROM    post
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

                return $post;
        }

        /*
         * Lista de entradas
         * de mas nueva a mas antigua
         * // si es portada son los que se meten por la gestion de entradas en portada que llevan el tag 1 'Portada'
         */
        public static function getAll ($blog, $limit = null, $published = true) {

            $list = array();

            $sql = "
                SELECT
                    post.id as id,
                    post.blog as blog,
                    IFNULL(post_lang.title, post.title) as title,
                    IFNULL(post_lang.text, post.text) as `text`,
                    IFNULL(post_lang.legend, post.legend) as `legend`,
                    post.image as `image`,
                    IFNULL(post_lang.media, post.media) as `media`,
                    DATE_FORMAT(post.date, '%d-%m-%Y') as date,
                    DATE_FORMAT(post.date, '%d | %m | %Y') as fecha,
                    post.publish as publish,
                    post.home as home,
                    post.footer as footer
                FROM    post
                LEFT JOIN post_lang
                    ON  post_lang.id = post.id
                    AND post_lang.lang = :lang
                WHERE post.blog = :blog
                ";
            if ($published) {
                $sql .= " AND post.publish = 1
                ";
            }
            $sql .= "ORDER BY post.date DESC, post.id DESC
                ";
            if (!empty($limit)) {
                $sql .= "LIMIT $limit";
            }
            
            $query = static::query($sql, array(':blog'=>$blog, ':lang'=>\LANG));
                
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

                $list[$post->id] = $post;
            }

            return $list;
        }

        /*
         * Lista de entradas filtradas por tag
         * de mas nueva a mas antigua
         */
        public static function getList ($blog, $tag, $published = true) {

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
                    DATE_FORMAT(post.date, '%d-%m-%Y') as date,
                    DATE_FORMAT(post.date, '%d-%m-%Y') as fecha,
                    post.publish as publish,
                    post.home as home,
                    post.footer as footer
                FROM    post
                LEFT JOIN post_lang
                    ON  post_lang.id = post.id
                    AND post_lang.lang = :lang
                INNER JOIN post_tag
                    ON post_tag.post = post.id
                    AND post_tag.tag = :tag
                WHERE post.blog = :blog
                ";
            if ($published) {
                $sql .= " AND post.publish = 1
                ";
            }
            $sql .= "
                ORDER BY date DESC, post.id DESC
                ";

            $query = static::query($sql, array(':blog'=>$blog, ':tag'=>$tag, ':lang'=>\LANG));

            foreach ($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $post) {
                // galeria
                $post->gallery = Image::getAll($post->id, 'post');
                $post->image = $post->gallery[0];

                // video
                if (isset($post->media)) {
                    $post->media = new Media($post->media);
                }

                $post->num_comments = Post\Comment::getCount($post->id);

                $list[$post->id] = $post;
            }

            return $list;
        }

        public function validate (&$errors = array()) { 
            if (empty($this->title))
                $errors['title'] = 'Falta título';

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
//            if (!$this->validate($errors)) return false;

            // @TODO poner la imagen principal

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
                'footer'
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
                         * Guarda la relación NM en la tabla 'post_image'.
                         */
                        if(!empty($image->id)) {
                            self::query("REPLACE post_image (post, image) VALUES (:post, :image)", array(':post' => $this->id, ':image' => $image->id));
                        }
                    }
//                    else { $this->image = ''; }
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
                $errors[] = "No se ha guardado correctamente. " . $e->getMessage();
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
                $errors[] = "No se ha guardado correctamente. " . $e->getMessage();
                return false;
            }
        }

        /*
         * Para quitar una entrada
         */
        public static function delete ($id) {
            
            $sql = "DELETE FROM post WHERE id = :id";
            if (self::query($sql, array(':id'=>$id))) {

                // que elimine tambien sus imágenes
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