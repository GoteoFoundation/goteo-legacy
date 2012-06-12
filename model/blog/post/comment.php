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

    use Goteo\Library\Text,
        Goteo\Library\Feed;

    class Comment extends \Goteo\Core\Model {

        public
            $id,
            $post,
            $date,
            $text,
            $user,
            $timeago;

        /*
         *  Devuelve datos de una comentario
         */
        public static function get ($id) {
                $query = static::query("
                    SELECT
                        id,
                        post,
                        date,
                        text,
                        user
                    FROM    comment
                    WHERE id = :id
                    ", array(':id' => $id));

                $comment = $query->fetchObject(__CLASS__);

                // reconocimiento de enlaces y saltos de linea
                $comment->text = nl2br(Text::urlink($comment->text));

                return $comment;
        }

        /*
         * Lista de comentarios
         */
        public static function getAll ($post) {

            $list = array();

            $sql = "
                SELECT
                    comment.id,
                    comment.post,
                    DATE_FORMAT(comment.date, '%d | %m | %Y') as date,
                    comment.date as timer,
                    comment.text,
                    comment.user
                FROM    comment
                INNER JOIN user
                    ON  user.id = comment.user
                    AND (user.hide = 0 OR user.hide IS NULL)
                WHERE comment.post = ?
                ORDER BY comment.date ASC, comment.id ASC
                ";
            
            $query = static::query($sql, array($post));
                
            foreach ($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $comment) {
                $comment->user = \Goteo\Model\User::getMini($comment->user);

                // reconocimiento de enlaces y saltos de linea
                $comment->text = nl2br(Text::urlink($comment->text));

                //hace tanto
                $comment->timeago = Feed::time_ago($comment->timer);


                $list[$comment->id] = $comment;
            }

            return $list;
        }

        /*
         * Lista de comentarios en el blog
         */
        public static function getList($blog, $limit = null) {

            $list = array();

            $sql = "
                SELECT
                    comment.id,
                    comment.post,
                    DATE_FORMAT(comment.date, '%d | %m | %Y') as date,
                    comment.text,
                    comment.user
                FROM    comment
                INNER JOIN user
                    ON  user.id = comment.user
                    AND (user.hide = 0 OR user.hide IS NULL)
                WHERE comment.post IN (SELECT id FROM post WHERE blog = ?)
                ORDER BY `date` DESC, comment.id DESC
                ";
            if (!empty($limit)) {
                $sql .= "LIMIT $limit";
            }

            $query = static::query($sql, array($blog));

            foreach ($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $comment) {
                
                $comment->user = \Goteo\Model\User::getMini($comment->user);

                // reconocimiento de enlaces y saltos de linea
                $comment->text = nl2br(Text::urlink($comment->text));

                $list[$comment->id] = $comment;
            }

            return $list;
        }

        /*
         *  Devuelve cuantos comentarios tiene una entrada
         */
        public static function getCount ($post) {
                $query = static::query("
                    SELECT
                        COUNT(comment.id) as cuantos
                    FROM    comment
                    INNER JOIN user
                        ON  user.id = comment.user
                        AND (user.hide = 0 OR user.hide IS NULL)
                    WHERE comment.post = :post
                    ", array(':post' => $post));

                $count = $query->fetchObject();

                return (int) $count->cuantos;
        }

        public function validate (&$errors = array()) { 
            if (empty($this->text))
                $errors[] = 'Falta texto';
                //Text::get('mandatory-comment-text');

            if (empty($errors))
                return true;
            else
                return false;
        }

        public function save (&$errors = array()) {
            if (!$this->validate($errors)) return false;

            $fields = array(
                'id',
                'post',
                'date',
                'text',
                'user'
                );

            $set = '';
            $values = array();

            foreach ($fields as $field) {
                if ($set != '') $set .= ", ";
                $set .= "`$field` = :$field ";
                $values[":$field"] = $this->$field;
            }

            try {
                $sql = "REPLACE INTO comment SET " . $set;
                self::query($sql, $values);
                if (empty($this->id)) $this->id = self::insertId();

                return true;
            } catch(\PDOException $e) {
                $errors[] = "No se ha guardado correctamente. " . $e->getMessage();
                return false;
            }
        }

        /*
         * Para quitar un comentario
         */
        public static function delete ($id) {
            
            $sql = "DELETE FROM comment WHERE id = :id";
            if (self::query($sql, array(':id'=>$id))) {
                return true;
            } else {
                return false;
            }

        }

    }
    
}