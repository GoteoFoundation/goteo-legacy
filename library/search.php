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


namespace Goteo\Library {

    use Goteo\Core\Model,
        Goteo\Model\Project;

	/*
	 * Clase para realizar búsquedas de proyectos
	 *
	 */
    class Search {

        /**
         * Metodo para buscar un textxto entre todos los contenidos de texto de un proyecto
         * @param string $value
         * @return array results
         */
		public static function text ($value) {

            $results = array();

            $values = array(':text'=>"%$value%");

            $sql = "SELECT id
                    FROM project
                    WHERE status > 2
                    AND (name LIKE :text
                        OR description LIKE :text
                        OR motivation LIKE :text
                        OR about LIKE :text
                        OR goal LIKE :text
                        OR related LIKE :text
                        OR keywords LIKE :text
                        OR location LIKE :text
                        )
                    ORDER BY name ASC";

            try {
                $query = Model::query($sql, $values);
                foreach ($query->fetchAll(\PDO::FETCH_CLASS) as $match) {
                    $results[] = Project::getMedium($match->id);
                }
                return $results;
            } catch (\PDOException $e) {
                throw new Exception('Fallo la sentencia de busqueda');
            }
		}

        /**
         * Metodo para realizar una busqueda por parametros
         * @param array multiple $params 'category', 'location', 'reward'
         * @return array results
         */
		public static function params ($params) {

            $results = array();
            $where   = array();
            $values  = array();

            if (!empty($params['category'])) {
                $where[] = 'AND id IN (
                                    SELECT distinct(project)
                                    FROM project_category
                                    WHERE category IN ('. implode(', ', $params['category']) . ')
                                )';
            }

            if (!empty($params['location'])) {
                $where[] = 'AND MD5(project_location) IN ('. implode(', ', $params['location']) .')';
            }

            if (!empty($params['reward'])) {
                $where[] = 'AND id IN (
                                    SELECT DISTINCT(project)
                                    FROM reward
                                    WHERE icon IN ('. implode(', ', $params['reward']) . ')
                                    )';
            }

            if (!empty($params['query'])) {
                $where[] = ' AND (name LIKE :text
                                OR description LIKE :text
                                OR motivation LIKE :text
                                OR about LIKE :text
                                OR goal LIKE :text
                                OR related LIKE :text
                                OR keywords LIKE :text
                                OR location LIKE :text
                            )';
                $values[':text'] = "%{$params['query']}%";
            }

            $sql = "SELECT id
                    FROM project
                    WHERE status > 2
                    ";
            
            if (!empty($where)) {
                $sql .= implode (' ', $where);
            }

            $sql .= "ORDER BY name ASC";

//            echo "$sql<br />";

            try {
                $query = Model::query($sql, $values);
                foreach ($query->fetchAll(\PDO::FETCH_CLASS) as $match) {
                    $results[] = Project::getMedium($match->id);
                }
                return $results;
            } catch (\PDOException $e) {
                throw new Exception('Fallo la sentencia de busqueda');
            }
		}

	}

}