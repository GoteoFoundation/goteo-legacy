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
        Goteo\Model;

    class Glossary extends \Goteo\Core\Controller {
        
        public function index () {

            // Términos por página
            $tpp = 5;

            // indice de letras
            $index = array();

            // sacamos todo el glosario
            $glossary = Model\Glossary::getAll();

            //recolocamos los post para la paginacion
            $p = 1;
            $page = 1;
            $posts = array();
            foreach ($glossary as $id=>$post) {

                // tratar el texto para las entradas
                $post->text = str_replace(array('%SITE_URL%'), array(SITE_URL), $post->text);
                
                $posts[] = $post;

                // y la inicial en el indice
                $letra = \strtolower($post->title[0]);
                $index[$letra][] = (object) array(
                    'title' => $post->title,
                    'url'   => '/glossary/?page='.$page.'#term' . $post->id
                );

                $p++;
                if ($p > $tpp) {
                    $p = 1;
                    $page++;
                }
            }

            return new View(
                'view/glossary/index.html.php',
                array(
                    'tpp'   => $tpp,
                    'index' => $index,
                    'posts' => $posts
                )
             );

        }
        
    }
    
}