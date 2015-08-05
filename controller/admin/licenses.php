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
		Goteo\Library\Message,
		Goteo\Library\Feed,
        Goteo\Model;

    class Licenses {

        public static function process ($action = 'list', $id = null, $filters = array()) {

            // agrupaciones de mas a menos abertas
            $groups = Model\License::groups();

            // tipos de retorno para asociar
            $icons = Model\Icon::getAll('social');


            $errors = array();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                // objeto
                $license = new Model\License(array(
                    'id' => $_POST['id'],
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'url' => $_POST['url'],
                    'group' => $_POST['group'],
                    'order' => $_POST['order'],
                    'icons' => $_POST['icons']
                ));

				if ($license->save($errors)) {
                    switch ($_POST['action']) {
                        case 'add':
                            Message::Info('Licencia añadida correctamente');
                            break;
                        case 'edit':
                            Message::Info('Licencia editada correctamente');

                            // Evento Feed
                            $log = new Feed();
                            $log->populate('modificacion de licencia (admin)', '/admin/licenses',
                                \vsprintf("El admin %s ha %s la licencia %s", array(
                                    Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                    Feed::item('relevant', 'Modificado'),
                                    Feed::item('project', $license->name)
                            )));
                            $log->doAdmin('admin');
                            unset($log);

                            break;
                    }
				}
				else {
                    Message::Error(implode('<br />', $errors));

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'licenses',
                            'file' => 'edit',
                            'action'  => $_POST['action'],
                            'license' => $license,
                            'icons'   => $icons,
                            'groups'  => $groups
                        )
                    );
				}
			}

            switch ($action) {
                case 'up':
                    Model\License::up($id);
                    break;
                case 'down':
                    Model\License::down($id);
                    break;
                case 'add':
                    $next = Model\License::next();

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'licenses',
                            'file' => 'edit',
                            'action' => 'add',
                            'license' => (object) array('order' => $next, 'icons' => array()),
                            'icons' => $icons,
                            'groups' => $groups
                        )
                    );
                    break;
                case 'edit':
                    $license = Model\License::get($id);

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'licenses',
                            'file' => 'edit',
                            'action' => 'edit',
                            'license' => $license,
                            'icons' => $icons,
                            'groups' => $groups
                        )
                    );
                    break;
                case 'remove':
    //                Model\License::delete($id);
                    break;
            }

            $licenses = Model\License::getAll($filters['icon'], $filters['group']);

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'licenses',
                    'file' => 'list',
                    'licenses' => $licenses,
                    'filters'  => $filters,
                    'groups' => $groups,
                    'icons'    => $icons
                )
            );
            
        }

    }

}
