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

    use Goteo\Core\ACL,
        Goteo\Core\Error,
        Goteo\Core\Redirection,
        Goteo\Core\View,
        Goteo\Model,
        Goteo\Library\Message,
        Goteo\Library\Feed,
        Goteo\Library\Page,
        Goteo\Library\Mail,
        Goteo\Library\Text;

    class Review extends \Goteo\Core\Controller {

        /*
         *  Muy guarro para poder moverse mientras desarrollamos
         */
        public function index () {

            $page = Page::get('review');

            $message = \str_replace('%USER_NAME%', $_SESSION['user']->name, $page->content);

            $user = $_SESSION['user'];

            $reviews = Model\Review::assigned($user->id);
            // si no hay proyectos asignados no tendria que estar aqui
            if (count($reviews) == 0) {
                $message = 'No tienes asignada ninguna revisión de proyectos';
            }
            
            return new View (
                'view/review/index.html.php',
                array(
                    'message' => $message,
                    'menu'    => self::menu(),
                    'section' => 'activity',
                    'option'  => 'summary',
                    'reviews' => $reviews
                )
            );

        }

        /*
         * Sección, Mi actividad
         * Opciones:
         *      'summary' resumen de los proyectos que tengo actualmente asignados para revisar
         * 
         */
        public function activity ($option = 'summary', $action = 'list') {

            $user = $_SESSION['user'];

            $reviews = Model\Review::assigned($user->id);
            // si no hay proyectos asignados no tendria que estar aqui
            if (count($reviews) == 0) {
                $message = 'No tienes asignada ninguna revisión de proyectos';
            }

            // resumen de los proyectos que tengo actualmente asignados
            return new View (
                'view/review/index.html.php',
                array(
                    'menu'    => self::menu(),
                    'message' => $message,
                    'section' => __FUNCTION__,
                    'option'  => $option,
                    'action'  => $action,
                    'reviews' => $reviews,
                    'errors'  => $errors,
                    'success' => $success
                )
            );

        }

        /*
         * Seccion, Mis revisiones asignadas
         * Opciones:
         *      'summary' resumen de la revision del proyecto de trabajo: comentarios de administrador para esta revision y enlaces del proyecto
         *      'evaluate' marcar los puntos de criterios y comentarios de evaluacion y mejoras
         *                  para cada seccion de criterios
         *      'comments' resumen de comentarios de todos los revisores
         *
         */
        public function reviews ($option = 'summary', $action = 'list', $id = null) {
            
            $user    = $_SESSION['user'];

            $errors = array();

            $reviews = Model\Review::assigned($user->id);

            // si no hay proyectos asignados no tendria que estar aqui
            if (count($reviews) == 0) {
                throw new Redirection('/review/activity');
            }

            $review = $_SESSION['review'];

            if ($action == 'ready' && !empty($id)) {
                $ready = new Model\User\Review(array(
                                    'user' => $user->id,
                                    'id'   => $id
                                ));
                if ($ready->ready($errors)) {
                    $message = 'Se ha dado por terminada tu revisión';
                    $review = Model\Review::getData($review->id);

                    /*
                     * Evento Feed
                     */
                    $log = new Feed();
                    $log->title = 'revisión cerrada (revisor)';
                    $log->url = '/review/reviews';
                    $log->type = 'admin';
                    $log_text = 'El revisor %s ha %s la revisión de %s';
                    $log_items = array(
                        Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                        Feed::item('relevant', 'Finalizado'),
                        Feed::item('project', $review->name, $review->project)
                    );
                    $log->html = \vsprintf($log_text, $log_items);
                    $log->add($errors);

                    unset($log);

                }
            }

            if (empty($review)) {
                $review = $reviews[0];
            }

            if ($action == 'select' && !empty($_POST['review'])) {
                // otra revisión de trabajo
                $review = Model\Review::getData($_POST['review']);
            } elseif ($action == 'open' && !empty($id)) {
                // otra revisión de trabajo por url
                $review = Model\Review::getData($id);
            }

            $_SESSION['review'] = $review;

            if ($option == 'evaluate') {
                //Text::get
                if ($review->ready == 1) {
                    Message::Info(Text::get('review-closed-alert'));
                } else {
                    Message::Info(Text::get('review-ajax-alert'));
                }
            }

            // view data basico para esta seccion
            $viewData = array(
                    'menu'    => self::menu(),
                    'message' => $message,
                    'section' => __FUNCTION__,
                    'option'  => $option,
                    'action'  => $action,
                    'reviews' => $reviews,
                    'review'  => $review,
                    'errors'  => $errors,
                    'success' => $success
                );

            if ($option == 'evaluate' || $option == 'report') {
                $viewData['evaluation'] = Model\Review::getEvaluation($review->id, $user->id);
            }

            return new View ('view/review/index.html.php', $viewData);
        }

        /*
         * Seccion, Mi historial
         * Opciones:
         *      'summary' resumen de los revisados anteriormente
         *
         */
        public function history ($option = 'summary', $id = null) {

            // tratamos el post segun la opcion y la acion

            // sacamos las revisiones realizadas

            $user = $_SESSION['user'];

            $reviews = Model\Review::history($user->id);
            // si no hay proyectos asignados no tendria que estar aqui
            if (count($reviews) == 0) {
                $message = 'No hay revisiones anteriores';
            }

            $viewData = array(
                    'menu'    => self::menu(),
                    'message' => $message,
                    'section' => __FUNCTION__,
                    'option'  => $option,
                    'action'  => $action,
                    'reviews' => $reviews,
                    'errors'  => $errors,
                    'success' => $success
                );

            if ($option == 'details') {
                if (!empty($id)) {
                    $viewData['review']     = $reviews[$id];
                    $viewData['evaluation'] = Model\Review::getEvaluation($id, $user->id);
                } else {
                    $viewData['options'] = 'summary';
                }
            }

            return new View (
                'view/review/index.html.php',
                $viewData
            );
        }


        private static function menu() {
            // todos los textos del menu review
            $menu = array(
                'activity' => array(
                    'label'   => 'Mi actividad',
                    'options' => array (
                        'summary' => 'Resumen'
                    )
                ),
                'reviews' => array(
                    'label' => 'Mis revisiones',
                    'options' => array (
                        'summary'  => 'Resumen',
                        'evaluate' => 'Evaluar',
                        'report'   => 'Informe'
                    )
                ),
                'history' => array(
                    'label'   => 'Mi historial',
                    'options' => array (
                        'summary'  => 'Listado'
                    )
                )
            );

            return $menu;

        }



        }

}