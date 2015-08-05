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
        Goteo\Model\Home,
        Goteo\Model\Project,
        Goteo\Model\Banner,
        Goteo\Model\Post,  // esto son entradas en portada o en footer
        Goteo\Model\Promote,
        Goteo\Model\User,
        Goteo\Model\Icon,
        Goteo\Model\Category,
        Goteo\Library\Text,
        Goteo\Library\Feed,
        Goteo\Library\Page; // para sacar el contenido de about

    class Index extends \Goteo\Core\Controller {
        
        public function index () {

            if (isset($_GET['error'])) {
                throw new \Goteo\Core\Error('418', Text::html('fatal-error-teapot'));
            }

            // orden de los elementos en portada
            $order = Home::getAll();

            // si estamos en easy mode, quitamos el feed
            if (defined('GOTEO_EASY') && \GOTEO_EASY === true && isset($order['feed'])) {
                unset($order['feed']);
            }
            
            // entradas de blog
            if (isset($order['posts'])) {
                // entradas en portada
                $posts     = Post::getAll();
            }

            // Proyectos destacados
            if (isset($order['promotes'])) {
                $promotes  = Promote::getAll(true);

                foreach ($promotes as $key => &$promo) {
                    try {
                        $promo->projectData = Project::getMedium($promo->project, LANG);
                    } catch (\Goteo\Core\Error $e) {
                        unset($promotes[$key]);
                    }
                }
            }

            // actividad reciente
            if (isset($order['feed'])) {
                $feed = array();

                $feed['goteo']     = Feed::getAll('goteo', 'public', 15);
                $feed['projects']  = Feed::getAll('projects', 'public', 15);
                $feed['community'] = Feed::getAll('community', 'public', 15);
            }
            
            // Banners siempre
            $banners   = Banner::getAll(true);

            foreach ($banners as $id => &$banner) {
                
                if (!empty($banner->project)) {
                    try {
                        $banner->project = Project::get($banner->project, LANG);
                    } catch (\Goteo\Core\Error $e) {
                        unset($banners[$id]);
                    }
                }
                
            }

            return new View('view/index.html.php',
                array(
                    'banners'  => $banners,
                    'posts'    => $posts,
                    'promotes' => $promotes,
                    'feed'     => $feed,
                    'order'    => $order
                )
            );
            
        }
        
    }
    
}