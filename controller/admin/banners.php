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

    class Banners {

        public static function process ($action = 'list', $id = null, $filters = array(), $flag = null) {

            $errors = array();

            $node = isset($_SESSION['admin_node']) ? $_SESSION['admin_node'] : \GOTEO_NODE;

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                // objeto
                $banner = new Model\Banner(array(
                    'id' => $_POST['id'],
                    'node' => $node,
                    'project' => $_POST['project'],
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'url' => $_POST['url'],
                    'order' => $_POST['order'],
                    'active' => $_POST['active']
                ));

                // imagen
                if(!empty($_FILES['image']['name'])) {
                    $banner->image = $_FILES['image'];
                } else {
                    $banner->image = $_POST['prev_image'];
                }

				if ($banner->save($errors)) {
                    Message::Info('Datos guardados');

                    if ($_POST['action'] == 'add') {
                        $projectData = Model\Project::getMini($_POST['project']);

                        // Evento Feed
                        $log = new Feed();
                        $log->setTarget($projectData->id);
                        $log->populate('nuevo banner de proyecto destacado en portada (admin)', '/admin/promote',
                            \vsprintf('El admin %s ha %s', array(
                            Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                            Feed::item('relevant', 'Publicado un banner', '/')
                        )));
                        $log->doAdmin('admin');
                        unset($log);
                    }

                    throw new Redirection('/admin/banners');
				}
				else {
                    Message::Error(implode('<br />', $errors));
                    
                    switch ($_POST['action']) {
                        case 'add':
                            return new View(
                                'view/admin/index.html.php',
                                array(
                                    'folder' => 'banners',
                                    'file' => 'edit',
                                    'action' => 'add',
                                    'banner' => $banner,
                                    'status' => $status
                                )
                            );
                            break;
                        case 'edit':
                            return new View(
                                'view/admin/index.html.php',
                                array(
                                    'folder' => 'banners',
                                    'file' => 'edit',
                                    'action' => 'edit',
                                    'banner' => $banner
                                )
                            );
                            break;
                    }
				}
			}

            switch ($action) {
                case 'active':
                    $set = $flag == 'on' ? true : false;
                    Model\Banner::setActive($id, $set);
                    throw new Redirection('/admin/banners');
                    break;
                case 'up':
                    Model\Banner::up($id, $node);
                    throw new Redirection('/admin/banners');
                    break;
                case 'down':
                    Model\Banner::down($id, $node);
                    throw new Redirection('/admin/banners');
                    break;
                case 'remove':
                    if (Model\Banner::delete($id)) {
                        Message::Info('Banner quitado correctamente');
                    } else {
                        Message::Error('No se ha podido quitar el banner');
                    }
                    throw new Redirection('/admin/banners');
                    break;
                case 'add':
                    // siguiente orden
                    $next = Model\Banner::next($node);

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'banners',
                            'file' => 'edit',
                            'action' => 'add',
                            'banner' => (object) array('order' => $next),
                            'status' => $status
                        )
                    );
                    break;
                case 'edit':
                    $banner = Model\Banner::get($id);

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'banners',
                            'file' => 'edit',
                            'action' => 'edit',
                            'banner' => $banner
                        )
                    );
                    break;
            }


            $bannered = Model\Banner::getAll(false, $node);

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'banners',
                    'file' => 'list',
                    'bannered' => $bannered,
                    'node' => $node
                )
            );
            
        }

    }

}
