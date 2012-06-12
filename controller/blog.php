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
        Goteo\Library\Text,
        Goteo\Library\Message,
        Goteo\Model;

    class Blog extends \Goteo\Core\Controller {
        
        public function index ($post = null) {

            if (!empty($post)) {
                $show = 'post';
                // -- Mensaje azul molesto para usuarios no registrados
                if (empty($_SESSION['user'])) {
                    $_SESSION['jumpto'] = '/blog/' .  $post;
                    Message::Info(Text::get('user-login-required'));
                }
            } else {
                $show = 'list';
            }

            // sacamos su blog
            $blog = Model\Blog::get(\GOTEO_NODE, 'node');

            if (isset($_GET['tag'])) {
                $tag = Model\Blog\Post\Tag::get($_GET['tag']);
                if (!empty($tag->id)) {
                    $blog->posts = Model\Blog\Post::getList($blog->id, $tag->id);
                }
            }

            if (isset($post) && !isset($blog->posts[$post]) && $_GET['preview'] != $_SESSION['user']->id) {
                throw new \Goteo\Core\Redirection('/blog');
            }

            // segun eso montamos la vista
            return new View(
                'view/blog/index.html.php',
                array(
                    'blog' => $blog,
                    'show' => $show,
                    'tag'  => $tag,
                    'post' => $post,
                    'owner' => \GOTEO_NODE
                )
             );

        }
        
    }
    
}