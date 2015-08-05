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
		Goteo\Library\Text,
		Goteo\Library\Page;

    class Pages {

        static public function _node_pages() {
            return array('about', 'contact', 'press', 'service'); 
        }


        public static function process ($action = 'list', $id = null) {

            $node = isset($_SESSION['admin_node']) ? $_SESSION['admin_node'] : \GOTEO_NODE;

            $errors = array();

            switch ($action) {
                case 'add':
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $page = new Page();
                        $page->id = $_POST['id'];
                        $page->name = $_POST['name'];
                        if ($page->add($errors)) {

                            Message::Info('La página <strong>'.$page->name. '</strong> se ha creado correctamente, se puede editar ahora.');

                            throw new Redirection("/admin/pages/edit/{$page->id}");
                        } else {
                            Message::Error('No se ha creado bien '. implode('<br />', $errors));
                            throw new Redirection("/admin/pages/add");
                        }
                    }

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'pages',
                            'file' => 'add'
                        )
                     );
                    break;

                case 'edit':
                    if ($node != \GOTEO_NODE && !in_array($id, static::_node_pages())) {
                        Message::Info('No puedes gestionar la página <strong>'.$id.'</strong>');
                        throw new Redirection("/admin/pages");
                    }
                    // si estamos editando una página
                    $page = Page::get($id, $node, \GOTEO_DEFAULT_LANG);

                    // si llega post, vamos a guardar los cambios
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $page->name = $_POST['name'];
                        $page->description = $_POST['description'];
                        $page->content = $_POST['content'];
                        if ($page->save($errors)) {

                            // Evento Feed
                            $log = new Feed();
                            if ($node != \GOTEO_NODE && in_array($id, static::_node_pages())) {
                                $log->setTarget($node, 'node');
                            }
                            $log->populate(Text::_('modificacion de página institucional (admin)'), '/admin/pages',
                                \vsprintf("El admin %s ha %s la página institucional %s", array(
                                Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                Feed::item('relevant', 'Modificado'),
                                Feed::item('relevant', $page->name, $page->url)
                            )));
                            $log->doAdmin('admin');
                            unset($log);

                            Message::Info('La página '.$page->name. ' se ha actualizado correctamente');

                            throw new Redirection("/admin/pages");
                        } else {
                            Message::Error(implode('<br />', $errors));
                        }
                    }


                    // sino, mostramos para editar
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'pages',
                            'file' => 'edit',
                            'page' => $page
                        )
                     );
                    break;

                case 'list':
                    // si estamos en la lista de páginas
                    $pages = Page::getList($node);

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'pages',
                            'file' => 'list',
                            'pages' => $pages,
                            'node' => $node
                        )
                    );
                    break;
            }

        }

    }

}
