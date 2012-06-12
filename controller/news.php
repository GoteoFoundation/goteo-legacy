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
        Goteo\Core\View,
        Goteo\Model;

    class News extends \Goteo\Core\Controller {

        public function index () {

            $page = Page::get('news');
            $news = Model\News::getAll();

            return new View(
                'view/news.html.php',
                array(
                    'name' => $page->name,
                    'title' => $page->description,
                    'content' => $page->content,
                    'news' => $news
                )
             );

        }

    }

}