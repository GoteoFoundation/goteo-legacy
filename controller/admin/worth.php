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
        Goteo\Library\Worth as WorthLib;

    class Worth {

        public static function process ($action = 'list', $id = null) {

            $errors = array();

            if ($_SERVER['REQUEST_METHOD'] == 'POST' && $action == 'edit') {

                // instancia
                $data = array(
                    'id' => $_POST['id'],
                    'name' => $_POST['name'],
                    'amount' => $_POST['amount']
                );

				if (WorthLib::save($data, $errors)) {
                    $action = 'list';
                    Message::Info(Text::_('Nivel de meritocracia modificado'));

                    // Evento Feed
                    $log = new Feed();
                    $log->populate(Text::_('Nivel de meritocracia modificado'), '/admin/worth',
                        \vsprintf("El admin %s ha %s el nivel de meritocrácia %s", array(
                            Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                            Feed::item('relevant', 'Modificado'),
                            Feed::item('project', $icon->name)
                    )));
                    $log->doAdmin('admin');
                    unset($log);
				}
				else {
                    Message::Error(Text::_('No se ha guardado correctamente. ').implode('<br />', $errors));

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'worth',
                            'file' => 'edit',
                            'action' => 'edit',
                            'worth' => (object) $data
                        )
                    );
				}
			}

            switch ($action) {
                case 'edit':
                    $worth = WorthLib::getAdmin($id);

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'worth',
                            'file' => 'edit',
                            'action' => 'edit',
                            'worth' => $worth
                        )
                    );
                    break;
            }

            $worthcracy = WorthLib::getAll();

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'worth',
                    'file' => 'list',
                    'worthcracy' => $worthcracy
                )
            );
            
        }

    }

}
