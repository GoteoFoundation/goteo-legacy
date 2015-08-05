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

namespace Goteo\Controller {

    use Goteo\Core\View,
        Goteo\Model,
        Goteo\Core\Redirection,
        Goteo\Library\Text,
        Goteo\Library\Message,
        Goteo\Library\Listing;

    class Discover extends \Goteo\Core\Controller {
    
        static public function _types() {
             return array(
                    'popular',
                    'recent',
                    'success',
                    'outdate',
                    'archive'
                );
        }

        /*
         * Descubre proyectos, página general
         */
        public function index () {

            $types = static::_types();

            $viewData = array(
                'lists' => array()
            );


            // cada tipo tiene sus grupos
            foreach ($types as $type) {
                $projects = Model\Project::published($type);
                if (empty($projects)) continue;
                $viewData['lists'][$type] = Listing::get($projects);
            }

            return new View(
                'view/discover/index.html.php',
                $viewData
             );

        }

        /*
         * Descubre proyectos, resultados de búsqueda
         */
        public function results ($category = null) {

            $message = '';
            $results = null;

            // si recibimos categoria por get emulamos post con un parametro 'category'
            if (!empty($category)) {
                $_POST['category'][] = $category;
            }
            
			if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['query']) && !isset($category)) {
                $errors = array();

                $params['query'] = \strip_tags($_GET['query']); // busqueda de texto

                $results = \Goteo\Library\Search::text($params['query']);

			} elseif (($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['searcher']) || !empty($category))) {

                // vamos montando $params con los 3 parametros y las opciones marcadas en cada uno
                $params = array('category'=>array(), 'location'=>array(), 'reward'=>array());

                foreach ($params as $param => $empty) {
                    foreach ($_POST[$param] as $key => $value) {
                        if ($value == 'all') {
                            $params[$param] = array();
                            break;
                        }
                        $params[$param][] = "'{$value}'";
                    }
                }

                $params['query'] = \strip_tags($_POST['query']);

                // para cada parametro, si no hay ninguno es todos los valores
                $results = \Goteo\Library\Search::params($params);

            } else {
                throw new Redirection('/discover', Redirection::PERMANENT);
            }

            return new View(
                'view/discover/results.html.php',
                array(
                    'message' => $message,
                    'results' => $results,
                    'params'  => $params
                )
             );

        }
        
        /*
         * Descubre proyectos, ver todos los de un tipo
         */
        public function view ($type = 'all') {

            $types = static::_types();
            $types[] = 'all';
            if (!in_array($type, $types)) {
                throw new Redirection('/discover');
            }

            $viewData = array();

            // segun el tipo cargamos el título de la página
            $viewData['title'] = Text::get('discover-group-'.$type.'-header');

            // segun el tipo cargamos la lista
            if (isset($_GET['list'])) {
                $viewData['list']  = Model\Project::published($type, null, true);

                return new View(
                    'view/discover/list.html.php',
                    $viewData
                 );
            } else {

                $projects = Model\Project::published($type);
                $viewData['list'] = $projects;

                return new View(
                    'view/discover/view.html.php',
                    $viewData
                 );

            }

        }
    }
    
}
