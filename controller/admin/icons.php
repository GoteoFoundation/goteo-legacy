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

    class Icons {

        public static function process ($action = 'list', $id = null, $filters = array()) {

            $groups = Model\Icon::groups();
            
            $errors = array();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                // instancia
                $icon = new Model\Icon(array(
                    'id' => $_POST['id'],
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'order' => $_POST['order'],
                    'group' => empty($_POST['group']) ? null : $_POST['group']
                ));

				if ($icon->save($errors)) {
                    switch ($_POST['action']) {
                        case 'add':
                            Message::Info('Nuevo tipo añadido correctamente');
                            break;
                        case 'edit':
                            Message::Info('Tipo editado correctamente');

                            // Evento Feed
                            $log = new Feed();
                            $log->populate('modificacion de tipo de retorno/recompensa (admin)', '/admin/icons',
                                \vsprintf("El admin %s ha %s el tipo de retorno/recompensa %s", array(
                                    Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                    Feed::item('relevant', 'Modificado'),
                                    Feed::item('project', $icon->name)
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
                            'folder' => 'icons',
                            'file' => 'edit',
                            'action' => $_POST['action'],
                            'icon' => $icon,
                            'groups' => $groups
                        )
                    );
				}
			}

            switch ($action) {
                case 'edit':
                    $icon = Model\Icon::get($id);

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'icons',
                            'file' => 'edit',
                            'action' => 'edit',
                            'icon' => $icon,
                            'groups' => $groups
                        )
                    );
                    break;
            }

            $icons = Model\Icon::getAll($filters['group']);
            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'icons',
                    'file' => 'list',
                    'icons' => $icons,
                    'groups' => $groups,
                    'filters' => $filters
                )
            );
            
        }

    }

}
