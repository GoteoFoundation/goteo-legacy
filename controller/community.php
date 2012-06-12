<?php
/*
 *  Copyright (C) 2012 Platoniq y Fundación Fuentes Abiertas (see README for details)
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

    use Goteo\Library\Page,
        Goteo\Library\Feed,
        Goteo\Core\View,
        Goteo\Model\User\Interest,
        Goteo\Model\Invest;

    class Community extends \Goteo\Core\Controller {

        public function index ($show = 'activity', $category = null) {

            $page = Page::get('community');

            $items = array();
            $shares = array();

            if (!in_array($show, array('sharemates', 'activity'))) $show = 'activity';

            $viewData = array(
                    'description' => $page->description,
                    'show' => $show
                );

            switch ($show) {

                // compartiendo intereses global
                case 'sharemates':
                    $categories = Interest::getAll();

                    foreach ($categories as $catId => $catName) {
                        $gente = Interest::shareAll($catId);
                        if (count($gente) == 0) continue;
                        $shares[$catId] = $gente;
                    }

                    $viewData['category'] = $category;
                    $viewData['categories'] = $categories;
                    $viewData['shares'] = $shares;

                    // top ten cofinanciadores en Goteo
                    $projects = Invest::projects(true);

                    $investors = array();
                    foreach ($projects as $projectId=>$projectName) {

                        foreach (Invest::investors($projectId) as $key=>$investor) {
                            if (\array_key_exists($investor->user, $investors)) {
                                // ya est� en el array, quiere decir que cofinancia este otro proyecto
                                // , a�adir uno, sumar su aporte, actualizar la fecha
                                ++$investors[$investor->user]->projects;
                                $investors[$investor->user]->amount += $investor->amount;
                                $investors[$investor->user]->date = $investor->date;
                            } else {
                                $investors[$investor->user] = (object) array(
                                    'user' => $investor->user,
                                    'name' => $investor->name,
                                    'projects' => 1,
                                    'avatar' => $investor->avatar,
                                    'worth' => $investor->worth,
                                    'amount' => $investor->amount,
                                    'date' => $investor->date
                                );
                            }
                        }
                    }

                    $viewData['investors'] = $investors;

                    break;

                // feed p�blico
                case 'activity':
                    
                    $items = array();

                    $items['goteo']     = Feed::getAll('goteo', 'public');
                    $items['projects']  = Feed::getAll('projects', 'public');
                    $items['community'] = Feed::getAll('community', 'public');

                    $viewData['items'] = $items;

                    break;
            }

            return new View('view/community.html.php', $viewData);

        }

    }

}