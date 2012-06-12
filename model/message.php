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

    use Goteo\Library\Text,
        Goteo\Library\Feed;

    class Message extends \Goteo\Core\Model {

        public
            $id,
            $user,
            $project,
            $thread, // hilo al que contesta, si es NULL es un hilo y tendrá respuestas ( o no)
            $date, // timestamp del momento en que se creó el mensaje
            $message, // el texto del mensaje en si
            $responses = array(), // array de instancias mensaje que son respuesta a este
            $blocked = 0, //no se puede editar ni borrar (es un mensaje thread de colaboracion)
            $closed = 0, // no se puede responder
            $timeago;

        /*
         *  Devuelve datos de un mensaje
         */
        public static function get ($id) {
                $query = static::query("
                    SELECT  *
                    FROM    message
                    WHERE   id = :id
                    ", array(':id' => $id));
                $message = $query->fetchObject(__CLASS__);
                
                // datos del usuario
                $message->user = User::getMini($message->user);

                // reconocimiento de enlaces y saltos de linea
                $message->message = nl2br(Text::urlink($message->message));

                //hace tanto
                $message->timeago = Feed::time_ago($message->date);

                if (empty($message->thread)) {
                    $query = static::query("
                        SELECT  *
                        FROM  message
                        WHERE thread = ?
                        ", array($id));

                    foreach ($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $response) {

                        //hace tanto
                        $response->timeago = Feed::time_ago($response->date);

                        $message->responses[] = $response;
                    }

                }

                return $message;
        }

        /*
         * Lista de hilos de un proyecto
         */
        public static function getAll ($project, $lang = null) {

            $messages = array();

            $query = static::query("
                SELECT
                    message.id as id,
                    message.user as user,
                    message.project as project,
                    message.thread as thread,
                    message.date as date,
                    IFNULL(message_lang.message, message.message) as message,
                    message.blocked as blocked,
                    message.closed as closed
                FROM  message
                LEFT JOIN message_lang
                    ON  message_lang.id = message.id
                    AND message_lang.lang = :lang
                WHERE   message.project = :project
                AND     message.thread IS NULL
                ORDER BY date ASC, id ASC
                ", array(':project'=>$project, ':lang'=>$lang));
            foreach ($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $message) {
                // datos del usuario
                $message->user = User::getMini($message->user);
                
                // reconocimiento de enlaces y saltos de linea
                $message->message = nl2br(Text::urlink($message->message));

                //hace tanto
                $message->timeago = Feed::time_ago($message->date);

                $query = static::query("
                    SELECT  id
                    FROM  message
                    WHERE thread = ?
                    ORDER BY date ASC, id ASC
                    ", array($message->id));

                foreach ($query->fetchAll(\PDO::FETCH_CLASS) as $response) {
                    $message->responses[] = self::get($response->id);
                }
                



                $messages[] = $message;
            }

            return $messages;
        }


        public function validate (&$errors = array()) { 
            if (empty($this->user))
                $errors[] = 'Falta usuario';
                //Text::get('mandatory-message-user');

            if (empty($this->project))
                $errors[] = 'Falta proyecto';
                //Text::get('validate-message-noproject');

            if (empty($this->message))
                $errors[] = 'Falta texto';
                //Text::get('mandatory-message-text');

            if (empty($errors))
                return true;
            else
                return false;
        }

        public function save (&$errors = array()) {
            if (!$this->validate($errors)) return false;

            if (\is_object($this->user)) {
                $this->user = $this->user->id;
            }

            $fields = array(
                'id',
                'user',
                'project',
                'thread',
                'message',
                'blocked',
                'closed'
                );

            $set = '';
            $values = array();

            foreach ($fields as $field) {
                if (!empty($this->$field)) {
                    if ($set != '') $set .= ", ";
                    $set .= "`$field` = :$field ";
                    $values[":$field"] = $this->$field;
                }
            }

            try {
                $sql = "REPLACE INTO message SET " . $set;
                self::query($sql, $values);
                if (empty($this->id)) $this->id = self::insertId();

                return true;
            } catch(\PDOException $e) {
                $errors[] = "El mensaje no se ha grabado correctamente. Por favor, inténtelo de nuevo." . $e->getMessage();
                return false;
            }
        }

        public function saveLang (&$errors = array()) {
            $fields = array(
                'id'=>'id',
                'lang'=>'lang',
                'message'=>'message_lang'
                );

            $set = '';
            $values = array();

            foreach ($fields as $field=>$ffield) {
                if (!empty($this->$ffield)) {
                    if ($set != '') $set .= ", ";
                    $set .= "`$field` = :$field ";
                    $values[":$field"] = $this->$ffield;
                }
            }

            try {
                $sql = "REPLACE INTO message_lang SET " . $set;
                self::query($sql, $values);

                return true;
            } catch(\PDOException $e) {
                $errors[] = "El mensaje no se ha grabado correctamente. Por favor, inténtelo de nuevo." . $e->getMessage();
                return false;
            }
        }

        /*
         * Para que el admin pueda borrar mensajes que no aporten nada
         */
        public function delete () {

            if ($this->blocked == 1) {
                return false;
            }

            $sql = "DELETE FROM message WHERE id = ?";
            if (self::query($sql, array($this->id))) {
                if (empty($this->thread) && is_array($this->responses)) {
                    foreach ($this->responses as $response) {
                        if ($response instanceof Message) {
                            $response->delete();
                        }
                    }
                }
                return true;
            } else {
                return false;
            }

        }

    }
    
}