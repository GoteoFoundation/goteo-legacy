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
        \Goteo\Model\Image,
        \Goteo\Library\Text;

    class Glossary extends \Goteo\Core\Model {

        public
            $id,
            $title,
            $text,
            $image,
            $media,
            $legend,
            $gallery = array(); // array de instancias image de glossary_image

        /*
         *  Devuelve datos de una entrada
         */
        public static function get ($id) {
                $query = static::query("
                    SELECT
                        glossary.id as id,
                        IFNULL(glossary_lang.title, glossary.title) as title,
                        IFNULL(glossary_lang.text, glossary.text) as text,
                        IFNULL(glossary_lang.legend, glossary.legend) as legend,
                        glossary.media as `media`
                    FROM    glossary
                    LEFT JOIN glossary_lang
                        ON  glossary_lang.id = glossary.id
                        AND glossary_lang.lang = :lang
                    WHERE glossary.id = :id
                    ", array(':id' => $id, ':lang'=>\LANG));

                $glossary = $query->fetchObject(__CLASS__);

                // video
                if (isset($glossary->media)) {
                    $glossary->media = new Media($glossary->media);
                }

                // galeria
                $glossary->gallery = Image::getAll($id, 'glossary');
                $glossary->image = $glossary->gallery[0];

                return $glossary;
        }

        /*
         * Lista de entradas por orden alfabético
         */
        public static function getAll () {

            $list = array();

            $sql = "
                SELECT
                    glossary.id as id,
                    IFNULL(glossary_lang.title, glossary.title) as title,
                    IFNULL(glossary_lang.text, glossary.text) as `text`,
                    IFNULL(glossary_lang.legend, glossary.legend) as `legend`,
                    glossary.media as `media`
                FROM    glossary
                LEFT JOIN glossary_lang
                    ON  glossary_lang.id = glossary.id
                    AND glossary_lang.lang = :lang
                ";
            $sql .= " ORDER BY title ASC
                ";
            
            $query = static::query($sql, array(':lang'=>\LANG));
                
            foreach ($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $glossary) {
                // galeria
                $glossary->gallery = Image::getAll($glossary->id, 'glossary');
                $glossary->image = $glossary->gallery[0];

                // video
                if (!empty($glossary->media)) {
                    $glossary->media = new Media($glossary->media);
                }
                
                $list[$glossary->id] = $glossary;
            }

            return $list;
        }

        public function validate (&$errors = array()) { 
            if (empty($this->title))
                $errors['title'] = 'Falta título';

            if (empty($this->text))
                $errors['text'] = 'Falta texto';

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
                'text',
                'legend',
                'media'
                );

            $values = array();

            foreach ($fields as $field) {
                if ($set != '') $set .= ", ";
                $set .= "`$field` = :$field ";
                $values[":$field"] = $this->$field;
            }

            try {
                $sql = "REPLACE INTO glossary SET " . $set;
                self::query($sql, $values);
                if (empty($this->id)) $this->id = self::insertId();

                // Luego la imagen
                if (!empty($this->id) && is_array($this->image) && !empty($this->image['name'])) {
                    $image = new Image($this->image);
                    if ($image->save($errors)) {
                        $this->gallery[] = $image;

                        /**
                         * Guarda la relación NM en la tabla 'glossary_image'.
                         */
                        if(!empty($image->id)) {
                            self::query("REPLACE glossary_image (glossary, image) VALUES (:glossary, :image)", array(':glossary' => $this->id, ':image' => $image->id));
                        }
                    }
                }

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
            
            $sql = "DELETE FROM glossary WHERE id = :id";
            if (self::query($sql, array(':id'=>$id))) {

                // que elimine tambien sus imágenes
                $sql = "DELETE FROM glossary_image WHERE glossary = :id";
                self::query($sql, array(':id'=>$id));

                return true;
            } else {
                return false;
            }

        }

    }
    
}