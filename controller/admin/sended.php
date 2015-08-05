<?php
/*
 *  Copyright (C) 2012 Platoniq y Fundación Goteo (see README for details)
 *  This file is part of Goteo.
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

namespace Goteo\Controller\Admin {

    use Goteo\Core\View,
        Goteo\Core\Redirection,
        Goteo\Core\Error,
		Goteo\Library\Feed,
		Goteo\Library\Template,
		Goteo\Library\Mail;

    class Sended {

        public static function process ($action = 'list', $id = null, $filters = array()) {
            $templates = Template::getAllMini();
            $nodes = array();
            $node = isset($_SESSION['admin_node']) ? $_SESSION['admin_node'] : \GOTEO_NODE;

            if ($filters['filtered'] == 'yes'){
                $sended = Mail::getSended($filters, $node);
            } else {
                $sended = array();
            }

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'sended',
                    'file' => 'list',
                    'filters' => $filters,
                    'templates' => $templates,
                    'nodes' => $nodes,
                    'sended' => $sended
                )
            );
            
        }

    }

}
