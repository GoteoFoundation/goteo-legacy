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

    use Goteo\Core\View,
        Goteo\Model\Project,
        Goteo\Model\Banner,
        Goteo\Model\Post,
        Goteo\Model\Promote,
        Goteo\Library\Text;

    class Index extends \Goteo\Core\Controller {
        
        public function index () {

            if (isset($_GET['error'])) {
                throw new \Goteo\Core\Error('418', Text::html('fatal-error-teapot'));
            }

            // hay que sacar los que van en portada de su blog (en cuanto aclaremos lo de los nodos)
            $posts    = Post::getList();
            $promotes = Promote::getAll(true);
            $banners  = Banner::getAll();

            foreach ($posts as $id=>$title) {
                $posts[$id] = Post::get($id);
            }

                foreach ($promotes as $key => &$promo) {
                    try {
                        $promo->projectData = Project::get($promo->project, LANG);
                    } catch (\Goteo\Core\Error $e) {
                        unset($promotes[$key]);
                    }
                }

                foreach ($banners as $id => &$banner) {
                    try {
                        $banner->project = Project::get($banner->project, LANG);
                    } catch (\Goteo\Core\Error $e) {
                        unset($banners[$id]);
                    }
                }

            $post = isset($_GET['post']) ? $_GET['post'] : reset($posts)->id;

            return new View('view/index.html.php',
                array(
                    'banners'  => $banners,
                    'posts'    => $posts,
                    'promotes' => $promotes
                )
            );
            
        }
        
    }
    
}