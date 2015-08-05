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
		Goteo\Library\Message,
        Goteo\Model;

    class Invests {

        public static function process ($action = 'list', $id = null, $filters = array()) {

            $node = isset($_SESSION['admin_node']) ? $_SESSION['admin_node'] : \GOTEO_NODE;
            
            // métodos de pago
            $methods = Model\Invest::methods();
            // estados del proyecto
            $status = Model\Project::status();
            // estados de aporte
            $investStatus = Model\Invest::status();
            // listado de proyectos
            $projects = Model\Invest::projects(false, $node);
            // usuarios cofinanciadores
            $users = Model\Invest::users(true);
            // campañas que tienen aportes
            $calls = Model\Invest::calls();
            // extras
            $types = array(
                'donative' => 'Solo los donativos',
                'anonymous' => 'Solo los anónimos',
                'manual' => 'Solo los manuales',
                'campaign' => 'Solo con riego',
            );


            // detalles del aporte
            if ($action == 'details') {

                $invest = Model\Invest::get($id);
                $project = Model\Project::get($invest->project);
                $userData = Model\User::get($invest->user);

                if (!empty($invest->droped)) {
                    $droped = Model\Invest::get($invest->droped);
                } else {
                    $droped = null;
                }

                if ($project->node != $node) {
                    throw new Redirection('/admin/invests');
                }

                return new View(
                    'view/admin/index.html.php',
                    array(
                        'folder' => 'invests',
                        'file' => 'details',
                        'invest' => $invest,
                        'project' => $project,
                        'user' => $userData,
                        'status' => $status,
                        'investStatus' => $investStatus,
                        'droped' => $droped,
                        'calls' => $calls
                    )
                );
            }

            // listado de aportes
            if ($filters['filtered'] == 'yes') {

                if (!empty($filters['calls']))
                    $filters['types'] = '';

                $list = Model\Invest::getList($filters, $node, 999);
            } else {
                $list = array();
            }

             $viewData = array(
                    'folder' => 'invests',
                    'file' => 'list',
                    'list'          => $list,
                    'filters'       => $filters,
                    'projects'      => $projects,
                    'users'         => $users,
                    'calls'         => $calls,
                    'methods'       => $methods,
                    'types'         => $types,
                    'investStatus'  => $investStatus
                );

            return new View(
                'view/admin/index.html.php',
                $viewData
            );

        }

    }

}
