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

    use Goteo\Model;

    class Ws extends \Goteo\Core\Controller {
        
        public function get_home_post($id) {
            $Post = Model\Post::get($id);

            header ('HTTP/1.1 200 Ok');
            echo <<< EOD
<h3>{$Post->title}</h3>
<div class="embed">{$Post->media->getEmbedCode()}</div>
<div class="description">{$Post->text}</div>
EOD;
            die;
        }

        public function get_faq_order($section) {
            $next = Model\Faq::next($section);

            header ('HTTP/1.1 200 Ok');
            echo $next;
            die;
        }

        public function get_criteria_order($section) {
            $next = Model\Criteria::next($section);

            header ('HTTP/1.1 200 Ok');
            echo $next;
            die;
        }

        public function set_review_criteria($user, $review) {
            // comprobar que tiene asignada esta revision
            if (Model\User\Review::is_legal($user, $review)) {

                $score = new Model\User\Review (array (
                                'user'   => $user,
                                'id' => $review
                ));

                $parts = explode('-', $_POST['campo']);
                if ($parts[0] == 'criteria') {
                    $criteria = $parts[1];
                } else {
                    header ('HTTP/1.1 400 Bad request');
                    die;
                }
                $value = $_POST['valor'];

                // puntuamos
                if ($score->setScore($criteria, $value)) {
                    $result = 'Ok';
                } else {
                    $result = 'fail';
                }

                // recalculamos
                $new_score = $score->recount();

                header ('HTTP/1.1 200 Ok');
                echo $new_score->score.'/'.$new_score->max;
                /*
                echo "Usuario: $user<br />";
                echo "Revision: $review<br />";
                echo "Criterio: {$criteria}<br />";
                echo "Valor: {$value}<br />";
                echo "Resulta: $result<br />";
                echo "<pre>".print_r($new_score, 1)."</pre>";
                 *
                 */
                die;
            } else {
                header ('HTTP/1.1 403 Forbidden');
                die;
            }
        }

        public function set_review_comment($user, $review) {
            // comprobar que tiene asignada esta revision
            if (Model\User\Review::is_legal($user, $review)) {

                $comment = new Model\User\Review (array (
                                'user'   => $user,
                                'id' => $review
                ));
                
                $parts = explode('-', $_POST['campo']);
                if (in_array($parts[0], array('project', 'owner', 'reward')) &&
                    in_array($parts[1], array('evaluation', 'recommendation'))) {
                    $section = $parts[0];
                    $field   = $parts[1];

                    $text = $_POST['valor'];

                    if ($comment->setComment($section, $field, $text)) {
                        $result = 'Grabado';
                    } else {
                        $result = 'Error';
                    }

                    header ('HTTP/1.1 200 Ok');
                    echo $result;
                    die;

                } else {
                    header ('HTTP/1.1 400 Bad request');
                    die;
                }

            } else {
                header ('HTTP/1.1 403 Forbidden');
                die;
            }
        }


        public function get_template_content($id) {
            $Template = \Goteo\Library\Template::get($id);

            header ('HTTP/1.1 200 Ok');
            echo $Template->title . '#$#$#' . $Template->text;
            die;
        }


    }
    
}