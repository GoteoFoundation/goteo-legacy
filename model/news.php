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

    class News extends \Goteo\Core\Model {

        public
            $id,
            $title,
            $url,
            $order;

        /*
         *  Devuelve datos de un destacado
         */
        public static function get ($id) {
                $sql = static::query("
                    SELECT
                        news.id as id,
                        IFNULL(news_lang.title, news.title) as title,
                        IFNULL(news_lang.description, news.description) as description,
                        news.url as url,
                        news.order as `order`
                    FROM news
                    LEFT JOIN news_lang
                        ON  news_lang.id = news.id
                        AND news_lang.lang = :lang
                    WHERE news.id = :id
                    ", array(':id' => $id, ':lang'=>\LANG));
                $news = $sql->fetchObject(__CLASS__);

                return $news;
        }

        /*
         * Lista de noticias
         */
        public static function getAll ($highlights = false) {

            $list = array();

            $sql = static::query("
                SELECT
                    news.id as id,
                    IFNULL(news_lang.title, news.title) as title,
                    IFNULL(news_lang.description, news.description) as description,
                    news.url as url,
                    news.order as `order`
                FROM news
                LEFT JOIN news_lang
                    ON  news_lang.id = news.id
                    AND news_lang.lang = :lang
                ORDER BY `order` ASC, title ASC
                ", array(':lang'=>\LANG));
            
            foreach ($sql->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $item) {
                if ($highlights) {
                    $item->title = Text::recorta($item->title, 80);
                }
                $list[] = $item;
            }

            return $list;
        }

        public function validate (&$errors = array()) { 
            if (empty($this->title))
                $errors[] = 'Falta título';
                //Text::get('mandatory-news-title');

            if (empty($this->url))
                $errors[] = 'Falta url';
                //Text::get('mandatory-news-url');

            if (empty($errors))
                return true;
            else
                return false;
        }

        public function save (&$errors = array()) {
            if (!$this->validate($errors)) return false;

            $fields = array(
                'id',
                'title',
                'description',
                'url',
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
                $sql = "REPLACE INTO news SET " . $set;
                self::query($sql, $values);
                if (empty($this->id)) $this->id = self::insertId();

                Check::reorder($this->id, 'up', 'news');

                return true;
            } catch(\PDOException $e) {
                $errors[] = "No se ha guardado correctamente. " . $e->getMessage();
                return false;
            }
        }

        /*
         * Para quitar una pregunta
         */
        public static function delete ($id) {
            
            $sql = "DELETE FROM news WHERE id = :id";
            if (self::query($sql, array(':id'=>$id))) {
                return true;
            } else {
                return false;
            }

        }

        /*
         * Para que una pregunta salga antes  (disminuir el order)
         */
        public static function up ($id) {
            return Check::reorder($id, 'up', 'news');
        }

        /*
         * Para que un proyecto salga despues  (aumentar el order)
         */
        public static function down ($id) {
            return Check::reorder($id, 'down', 'news');
        }

        /*
         * Orden para añadirlo al final
         */
        public static function next () {
            $sql = self::query('SELECT MAX(`order`) FROM news');
            $order = $sql->fetchColumn(0);
            return ++$order;

        }

    }
    
}