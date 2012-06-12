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

    use Goteo\Core\Model;

	/*
	 * Clase para cosas de localizaciones
	 * Para sacar las existentes, las coincidencias proyecto-usuario, las de gmaps, y geo-localizacion
	 */
    class Location {

        /**
         * Metodo para sacar las que hay en proyectos
         * @return array strings
         */
		public static function getList () {

            $results = array();

            $sql = "SELECT distinct(project_location) as location
                    FROM project
                    WHERE status > 2
                    ORDER BY location ASC";

            try {
                $query = Model::query($sql);
                foreach ($query->fetchAll(\PDO::FETCH_CLASS) as $item) {
                    $results[md5($item->location)] = $item->location;
                }
                return $results;
            } catch (\PDOException $e) {
                throw new Exception('Fallo la lista de localizaciones');
            }
		}

	}

}