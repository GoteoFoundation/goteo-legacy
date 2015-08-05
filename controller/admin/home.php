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

namespace Goteo\Controller\Admin {

    use Goteo\Core\View,
        Goteo\Core\Redirection,
        Goteo\Core\Error,
		Goteo\Library\Message,
		Goteo\Library\Feed,
        Goteo\Model;

    class Home {

        public static function process ($action = 'list', $id = null, $filters = array(), $type = 'main') {

            //@NODESYS
            $node = \GOTEO_NODE;
            $type = 'main';

            $errors = array();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                // instancia
                $item = new Model\Home(array(
                    'item' => $_POST['item'],
                    'type' => $_POST['type'],
                    'node' => $node,
                    'order' => $_POST['order'],
                    'move' => 'down'
                ));

				if ($item->save($errors)) {
				} else {
                    Message::Error(implode('<br />', $errors));
                }
			}


            switch ($action) {
                case 'remove':
                    Model\Home::delete($id, $node, $type);
                    throw new Redirection('/admin/home');
                    break;
                case 'up':
                    Model\Home::up($id, $node, $type);
                    throw new Redirection('/admin/home');
                    break;
                case 'down':
                    Model\Home::down($id, $node, $type);
                    throw new Redirection('/admin/home');
                    break;
                case 'add':
                    $next = Model\Home::next($node, 'main');
                    $availables = Model\Home::available($node);

                    if (empty($availables)) {
                        Message::Info(Text::_('Todos los elementos disponibles ya estan en portada'));
                        throw new Redirection('/admin/home');
                        break;
                    }
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'home',
                            'file' => 'add',
                            'action' => 'add',
                            'home' => (object) array('node'=>$node, 'order'=>$next, 'type'=>'main'),
                            'availables' => $availables
                        )
                    );
                    break;
                case 'addside':
                    $next = Model\Home::next($node, 'side');
                    $availables = Model\Home::availableSide($node);

                    if (empty($availables)) {
                        Message::Info(Text::_('Todos los elementos laterales disponibles ya estan en portada'));
                        throw new Redirection('/admin/home');
                        break;
                    }
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'home',
                            'file' => 'add',
                            'action' => 'add',
                            'home' => (object) array('node'=>$node, 'order'=>$next, 'type'=>'side'),
                            'availables' => $availables
                        )
                    );
                    break;
            }

            $viewData = array(
                'folder' => 'home',
                'file' => 'list'
            );

            $viewData['items'] = Model\Home::getAll($node);

            /* Para añadir nuevos desde la lista */
            $viewData['availables'] = Model\Home::available($node);
            $viewData['new'] = (object) array('node'=>$node, 'order'=>Model\Home::next($node, 'main'), 'type'=>'main');

            // laterales
            $viewData['side_items'] = Model\Home::getAllSide($node);
            $viewData['side_availables'] = Model\Home::availableSide($node);
            $viewData['side_new'] = (object) array('node'=>$node, 'order'=>Model\Home::next($node, 'side'), 'type'=>'side');

            return new View('view/admin/index.html.php', $viewData);
            
        }

    }

}
