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

    class Task extends \Goteo\Core\Model {

        public
        $id,
        $node,
        $text,
        $url,
        $datetime,
        $done = null;

        /**
         * Obtener los datos de una tarea
         */
        static public function get($id) {
            try {
                $query = static::query("SELECT * FROM task WHERE id = ?", array($id));
                $item = $query->fetchObject(__CLASS__);
                if (!empty($item->done)) {
                    $item->user = \Goteo\Model\User::getMini($item->done);
                }
                return $item;
            } catch (\PDOException $e) {
                throw new \Goteo\Core\Exception($e->getMessage());
            }
        }

        /**
         * Lista de tareas
         *
         * @param  bool $visible    true|false
         * @return mixed            Array de objetos de tareas
         */
        public static function getAll($filters = array(), $node = null, $undoneOnly = false) {

            $values = array();

            $list = array();

            $sqlFilter = "";
            $and = " WHERE";
            if (!empty($filters['done'])) {
                if ($filters['done'] == 'done') {
                    $sqlFilter .= "$and done IS NOT NULL";
                    $and = " AND";
                } else {
                    $sqlFilter .= "$and done IS NULL";
                    $and = " AND";
                }
            }
            if (!empty($filters['user'])) {
                $sqlFilter .= "$and done = :user";
                $values[':user'] = $filters['user'];
                $and = " AND";
            }
            if (!empty($filters['node'])) {
                $sqlFilter .= "$and node = :node";
                $values[':node'] = $filters['node'];
                $and = " AND";
            } elseif (!empty($node)) {
                $sqlFilter .= "$and node = :node";
                $values[':node'] = $node;
                $and = " AND";
            }
            if ($undoneOnly) {
                $sqlFilter .= "$and (done IS NULL OR done = '')";
                $and = " AND";
            }

            $sql = "SELECT *
                    FROM task
                    $sqlFilter
                    ORDER BY datetime DESC
                    ";

            echo $sql . '<br />';

            $query = self::query($sql, $values);
            foreach ($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $item) {
                if (!empty($item->done)) {
                    $item->user = \Goteo\Model\User::getMini($item->done);
                }
                $list[] = $item;
            }
            return $list;
        }

        /**
         * Guardar.
         * @param   type array  $errors     Errores devueltos pasados por referencia.
         * @return  type bool   true|false
         */
        public function save(&$errors = array()) {
            if (!$this->validate())
                return false;

            $values = array(':id' => $this->id, ':node' => $this->node, ':text' => $this->text, ':url' => $this->url, ':done' => $this->done);

            try {
                $sql = "REPLACE INTO task (id, node, text, url, done) VALUES(:id, :node, :text, :url, :done)";
                self::query($sql, $values);
                return true;
            } catch (\PDOException $e) {
                $errors[] = "HA FALLADO!!! " . $e->getMessage();
                return false;
            }
        }

        /**
         * Validar.
         * @param   type array  $errors     Errores devueltos pasados por referencia.
         * @return  type bool   true|false
         */
        public function validate(&$errors = array()) {
            if (empty($this->node)) {
                $this->node = \GOTEO_NODE;
            }
            return true;
        }

        /*
         * Guarda solo si no hay una tarea con esa url
         */
        public function saveUnique(&$errors = array()) {
            if (empty($this->node)) {
                $this->node = \GOTEO_NODE;
            }

            $query = static::query("SELECT id FROM task WHERE url = :url", array(':url'=>$this->url));
            $exists = $query->fetchColumn();
            if (!empty($exists)) {
                // ya existe
                return true;
            } else {
                return $this->save($errors);
            }
        }

        /**
         * Este método marca el usuario en el campo Done
         */
        public function setDone(&$errors = array()) {

            $values = array(':id' => $this->id, ':done' => $_SESSION['user']->id);

            try {
                $sql = "UPDATE task SET `done` = :done WHERE id = :id";
                if (self::query($sql, $values)) {
                    return true;
                } else {
                    $errors[] = 'Algo ha fallado';
                    return false;
                }
            } catch (\PDOException $e) {
                $errors[] = "HA FALLADO!!! " . $e->getMessage();
                return false;
            }
        }

        /**
         * Este método marca el usuario en el campo Done
         */
        public function remove(&$errors = array()) {

            $values = array(':id' => $this->id);

            try {
                $sql = "DELETE FROM task WHERE id = :id";
                if (self::query($sql, $values)) {
                    return true;
                } else {
                    $errors[] = 'Algo ha fallado';
                    return false;
                }
            } catch (\PDOException $e) {
                $errors[] = "HA FALLADO!!! " . $e->getMessage();
                return false;
            }
        }

    }

}