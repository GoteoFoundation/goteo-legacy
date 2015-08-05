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
		Goteo\Library\Feed;

    class Texts {

        public static function process ($action = 'list', $id = null, $filters = array()) {
            

            // valores de filtro
            $groups    = Text::groups();

            // metemos el todos
            \array_unshift($groups, Text::_('Todas las agrupaciones'));

 //@fixme temporal hasta pasar las agrupaciones a tabal o arreglar en el list.html.php
            // I dont know if this must serve in default lang or in current navigation lang
            $data = Text::getAll($filters, 'original');
            foreach ($data as $key=>$item) {
                $data[$key]->group = $groups[$item->group];
            }

            switch ($action) {
                case 'list':
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'texts',
                            'file' => 'list',
                            'data' => $data,
                            'columns' => array(
                                'edit' => '',
                                'text' => Text::_('Texto'),
                                'group' => Text::_('Agrupación')
                            ),
                            'url' => '/admin/texts',
                            'filters' => array(
                                'filtered' => $filters['filtered'],
                                'group' => array(
                                        'label'   => Text::_('Filtrar por agrupación:'),
                                        'type'    => 'select',
                                        'options' => $groups,
                                        'value'   => $filters['group']
                                    ),
                                'text' => array(
                                        'label'   => Text::_('Buscar texto:'),
                                        'type'    => 'input',
                                        'options' => null,
                                        'value'   => $filters['text']
                                    )
                                /*,
                                'idfilter' => array(
                                        'label'   => 'Id:',
                                        'type'    => 'input',
                                        'options' => null,
                                        'value'   => $filters['idfilter']
                                    )*/
                            )
                        )
                    );

                    break;
                case 'edit':

                    // gestionar post
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {

                        $errors = array();

                        $id = $_POST['id'];
                        $text = $_POST['text'];

                        $data = array(
                            'id' => $id,
                            'text' => $_POST['text']
                        );

                        if (Text::update($data, $errors)) {
                            Message::Info(Text::_('El texto ha sido actualizado'));
                            throw new Redirection("/admin/texts");
                        } else {
                            Message::Error(implode('<br />', $errors));
                        }
                    } else {
                        //@TODO: this must get the text in the GOTEO_DEFAULT_LANG or it will be overwrited
                        $text = Text::getPurpose($id);
                        // Julian Canaves  23 nov 2013
                        // right now getPurpose gets the spanish text. 
                        // In future this spanish text will be moved to the `Text` table 
                        //  and the `Purpose` table will distribute to database text or to gettext
                        //  and there will be no hardcoded strings
                        //  and will be all happy, fun and joy
                    }

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'texts',
                            'file' => 'edit',
                            'data' => (object) array (
                                'id' => $id,
                                'text' => $text
                            ),
                            'form' => array(
                                'action' => '/admin/texts/edit/'.$id,
                                'submit' => array(
                                    'name' => 'update',
                                    'label' => Text::_('Aplicar')
                                ),
                                'fields' => array (
                                    'idtext' => array(
                                        'label' => '',
                                        'name' => 'id',
                                        'type' => 'hidden',
                                        'properties' => '',

                                    ),
                                    'newtext' => array(
                                        'label' => Text::_('Texto'),
                                        'name' => 'text',
                                        'type' => 'textarea',
                                        'properties' => 'cols="100" rows="6"',

                                    )
                                )

                            )
                        )
                    );

                    break;
                default:
                    throw new Redirection("/admin");
            }
            
        }

    }

}
