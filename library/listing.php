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
        Goteo\Core\Exception;

	/*
	 * Clase para dividir una lista de proyectos en agruopaciones de 3 o 2 proyectos
	 */
    class Listing {

        public static function get($projects = array(), $each = 3) {

                $g = 1;
                $c = 1;
                foreach ($projects as $k=>$project) {
                    // al grupo
                    $list[$g]['items'][] = $project;

                    // cada 3 mientras no sea el ultimo
                    if (($c % $each) == 0 && $c<count($projects)) {
                        $list[$g]['prev'] = ($g-1);
                        $list[$g]['next'] = ($g+1);
                        $g++;
                    }
                    $c++;
                }

                $list[1]['prev']  = $g;
                $list[$g]['prev'] = $g == 1 ? 1 : ($g-1);
                $list[$g]['next'] = 1;

                return $list;
        }

	}
}