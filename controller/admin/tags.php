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
		Goteo\Library\Text,
		Goteo\Library\Message,
		Goteo\Library\Feed,
        Goteo\Model;

    class Tags {

        public static function process ($action = 'list', $id = null) {

            $model = 'Goteo\Model\Blog\Post\Tag';
            $url = '/admin/tags';

            $errors = array();

            switch ($action) {
                case 'add':
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'base',
                            'file' => 'edit',
                            'data' => (object) array(),
                            'form' => array(
                                'action' => "$url/edit/",
                                'submit' => array(
                                    'name' => 'update',
                                    'label' => Text::_('Añadir')
                                ),
                                'fields' => array (
                                    'id' => array(
                                        'label' => '',
                                        'name' => 'id',
                                        'type' => 'hidden'

                                    ),
                                    'name' => array(
                                        'label' => Text::_('Tag'),
                                        'name' => 'name',
                                        'type' => 'text'
                                    )
                                )

                            )
                        )
                    );

                    break;
                case 'edit':

                    // gestionar post
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {

                        $errors = array();

                        // instancia
                        $item = new $model(array(
                            'id' => $_POST['id'],
                            'name' => $_POST['name']
                        ));

                        if ($item->save($errors)) {
                            Message::Info(Text::_('El tag ha sido actualizado'));
                            throw new Redirection($url);
                        } else {
                            Message::Error(Text::_('No se ha guardado correctamente').implode('<br />', $errors));
                        }
                    } else {
                        $item = $model::get($id);
                    }

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'base',
                            'file' => 'edit',
                            'data' => $item,
                            'form' => array(
                                'action' => "$url/edit/$id",
                                'submit' => array(
                                    'name' => 'update',
                                    'label' => Text::get('regular-save')
                                ),
                                'fields' => array (
                                    'id' => array(
                                        'label' => '',
                                        'name' => 'id',
                                        'type' => 'hidden'

                                    ),
                                    'name' => array(
                                        'label' => Text::_('Tag'),
                                        'name' => 'name',
                                        'type' => 'text'
                                    )
                                )

                            )
                        )
                    );

                    break;
                case 'remove':
                    if ($model::delete($id)) {
                        throw new Redirection($url);
                    }
                    break;
            }

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'base',
                    'file' => 'list',
                    'model' => 'tag',
                    'addbutton' => Text::_('Nuevo tag'),
                    'data' => $model::getList(1),
                    'columns' => array(
                        'edit' => '',
                        'name' => Text::_('Tag'),
                        'used' => Text::_('Entradas'),
                        'translate' => '',
                        'remove' => ''
                    ),
                    'url' => "$url"
                )
            );
            
        }

    }

}
