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

    class Criteria {

        public static function process ($action = 'list', $id = null, $filters = array()) {

            $sections = Model\Criteria::sections();

            if (!isset($sections[$filters['section']])) {
                unset($filters['section']);
            }

            $errors = array();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                // instancia
                $criteria = new Model\Criteria(array(
                    'id' => $_POST['id'],
                    'section' => $_POST['section'],
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'order' => $_POST['order'],
                    'move' => $_POST['move']
                ));

				if ($criteria->save($errors)) {
                    switch ($_POST['action']) {
                        case 'add':
                            Message::Info('Criterio añadido correctamente');
                            break;
                        case 'edit':
                            Message::Info('Criterio editado correctamente');
                            break;
                    }
				} else {
                    Message::Error(implode('<br />', $errors));
                    
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'criteria',
                            'file' => 'edit',
                            'action' => $_POST['action'],
                            'criteria' => $criteria,
                            'sections' => $sections
                        )
                    );
				}
			}


            switch ($action) {
                case 'up':
                    Model\Criteria::up($id);
                    break;
                case 'down':
                    Model\Criteria::down($id);
                    break;
                case 'add':
                    $next = Model\Criteria::next($filters['section']);

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'criteria',
                            'file' => 'edit',
                            'action' => 'add',
                            'criteria' => (object) array('section' => $filters['section'], 'order' => $next, 'cuantos' => $next),
                            'sections' => $sections
                        )
                    );
                    break;
                case 'edit':
                    $criteria = Model\Criteria::get($id);

                    $cuantos = Model\Criteria::next($criteria->section);
                    $criteria->cuantos = ($cuantos -1);

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'criteria',
                            'file' => 'edit',
                            'action' => 'edit',
                            'criteria' => $criteria,
                            'sections' => $sections
                        )
                    );
                    break;
                case 'remove':
                    Model\Criteria::delete($id);
                    break;
            }

            $criterias = Model\Criteria::getAll($filters['section']);

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'criteria',
                    'file' => 'list',
                    'criterias' => $criterias,
                    'sections' => $sections,
                    'filters' => $filters
                )
            );
            
        }

    }

}
