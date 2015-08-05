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

    class Promote {

        public static function process ($action = 'list', $id = null, $filters = array(), $flag = null) {

            $errors = array();

            $node = isset($_SESSION['admin_node']) ? $_SESSION['admin_node'] : \GOTEO_NODE;

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                // objeto
                $promo = new Model\Promote(array(
                    'id' => $id,
                    'node' => $node,
                    'project' => $_POST['project'],
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'order' => $_POST['order'],
                    'active' => $_POST['active']
                ));

				if ($promo->save($errors)) {
                    switch ($_POST['action']) {
                        case 'add':
                            Message::Info('Proyecto destacado correctamente');

                            $projectData = Model\Project::getMini($_POST['project']);

                            if ($node == \GOTEO_NODE) {
                                // Evento Feed
                                $log = new Feed();
                                $log->setTarget($projectData->id);
                                $log->populate('nuevo proyecto destacado en portada (admin)', '/admin/promote',
                                    \vsprintf('El admin %s ha %s el proyecto %s', array(
                                        Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                        Feed::item('relevant', 'Destacado en portada', '/'),
                                        Feed::item('project', $projectData->name, $projectData->id)
                                )));
                                $log->doAdmin('admin');
                                unset($log);
                            }

                            break;
                        case 'edit':
                            Message::Info('Destacado actualizado correctamente');
                            break;
                    }

                    throw new Redirection('/admin/promote');
				}
				else {

                    Message::Error(implode(', ', $errors));

                    switch ($_POST['action']) {
                        case 'add':
                            return new View(
                                'view/admin/index.html.php',
                                array(
                                    'folder' => 'promote',
                                    'file' => 'edit',
                                    'action' => 'add',
                                    'promo' => $promo
                                )
                            );
                            break;
                        case 'edit':
                            return new View(
                                'view/admin/index.html.php',
                                array(
                                    'folder' => 'promote',
                                    'file' => 'edit',
                                    'action' => 'edit',
                                    'promo' => $promo
                                )
                            );
                            break;
                    }
				}
			}

            switch ($action) {
                case 'active':
                    $set = $flag == 'on' ? true : false;
                    Model\Promote::setActive($id, $set);
                    throw new Redirection('/admin/promote');
                    break;
                case 'up':
                    Model\Promote::up($id, $node);
                    throw new Redirection('/admin/promote');
                    break;
                case 'down':
                    Model\Promote::down($id, $node);
                    throw new Redirection('/admin/promote');
                    break;
                case 'remove':
                    if (Model\Promote::delete($id)) {
                        Message::Info('Destacado quitado correctamente');
                    } else {
                        Message::Error('No se ha podido quitar el destacado');
                    }
                    throw new Redirection('/admin/promote');
                    break;
                case 'add':
                    // siguiente orden
                    $next = Model\Promote::next($node);

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'promote',
                            'file' => 'edit',
                            'action' => 'add',
                            'promo' => (object) array('order' => $next, 'node'=>$node)
                        )
                    );
                    break;
                case 'edit':
                    $promo = Model\Promote::get($id);

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'promote',
                            'file' => 'edit',
                            'action' => 'edit',
                            'promo' => $promo
                        )
                    );
                    break;
            }


            $promoted = Model\Promote::getAll(false, $node);

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'promote',
                    'file' => 'list',
                    'promoted' => $promoted
                )
            );
            
        }

    }

}
