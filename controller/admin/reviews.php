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
		Goteo\Library\Feed,
		Goteo\Library\Message,
		Goteo\Library\Text,
        Goteo\Model;

    class Reviews {

        public static function process ($action = 'list', $id = null, $filters = array()) {

            $node = isset($_SESSION['admin_node']) ? $_SESSION['admin_node'] : \GOTEO_NODE;
            
            $errors  = array();

            switch ($action) {
                case 'add':
                case 'edit':

                    // el get se hace con el id del proyecto
                    $review = Model\Review::get($id);

                    $project = Model\Project::getMini($review->project);

                    if (empty($id) || ($action == 'edit' && !$review instanceof Model\Review)) {
                        Message::Error(Text::_('Hemos perdido de vista el proyecto o la revisión'));
                        throw new Redirection('/admin/reviews');
                    }

                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {

                        // instancia
                        $review->id         = $_POST['id'];
                        $review->project    = $_POST['project'];
                        $review->to_checker = $_POST['to_checker'];
                        $review->to_owner   = $_POST['to_owner'];

                        if ($review->save($errors)) {
                            switch ($action) {
                                case 'add':
                                    Message::Info(Text::_('Revisión iniciada correctamente'));

                                    // Evento Feed
                                    $log = new Feed();
                                    $log->setTarget($project->id);
                                    $log->populate(Text::_('valoración iniciada (admin)'), '/admin/reviews',
                                        \vsprintf('El admin %s ha %s la valoración de %s', array(
                                            Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                            Feed::item('relevant', Text::_('Iniciado')),
                                            Feed::item('project', $project->name, $project->id)
                                    )));
                                    $log->doAdmin('admin');
                                    unset($log);

                                    throw new Redirection('/admin/reviews/?project='.  urlencode($project->id));
                                    break;
                                case 'edit':
                                    Message::Info(Text::_('Datos editados correctamente'));
                                    throw new Redirection('/admin/reviews');
                                    break;
                            }
                        } else {
                            Message::Error(Text::_('No se han podido grabar los datos. '), implode(', ', $errors));
                        }
                    }

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'reviews',
                            'file'   => 'edit',
                            'action' => $action,
                            'review' => $review,
                            'project'=> $project
                        )
                    );

                    break;
                case 'close':
                    // el get se hace con el id del proyecto
                    $review = Model\Review::getData($id);

                    // marcamos la revision como completamente cerrada
                    if (Model\Review::close($id, $errors)) {
                        Message::Info(Text::_('La revisión se ha cerrado'));

                        // Evento Feed
                        $log = new Feed();
                        $log->setTarget($review->project);
                        $log->populate(Text::_('valoración finalizada (admin)'), '/admin/reviews',
                            \vsprintf('El admin %s ha dado por %s la valoración de %s', array(
                                Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                Feed::item('relevant', Text::_('Finalizada')),
                                Feed::item('project', $review->name, $review->project)
                        )));
                        $log->doAdmin('admin');
                        unset($log);

                    } else {
                        Message::Error(Text::_('La revisión no se ha podido cerrar. ').implode(', ', $errors));
                    }
                    throw new Redirection('/admin/reviews');
                    break;
                case 'unready':
                    // se la reabrimos para que pueda seguir editando
                    // la id de revision llega en $id
                    // la id del usuario llega por get
                    $user = $_GET['user'];
                    if (!empty($user)) {
                        $user_rev = new Model\User\Review(array(
                            'id' => $id,
                            'user' => $user
                        ));
                        $user_rev->unready($errors);
                        if (!empty($errors)) {
                            Message::Error(implode(', ', $errors));
                        }
                    }
                    throw new Redirection('/admin/reviews');
                    break;
                case 'assign':
                    // asignamos la revision a este usuario
                    // la id de revision llega en $id
                    // la id del usuario llega por get
                    $user = $_GET['user'];
                    if (!empty($user)) {
                        $assignation = new Model\User\Review(array(
                            'id' => $id,
                            'user' => $user
                        ));
                        if ($assignation->save($errors)) {

                            $userData = Model\User::getMini($user);
                            $reviewData = Model\Review::getData($id);

                            Message::Info(Text::_('Revisión asignada correctamente'));

                            // Evento Feed
                            $log = new Feed();
                            $log->setTarget($userData->id, 'user');
                            $log->populate(Text::_('asignar revision (admin)'), '/admin/reviews',
                                \vsprintf('El admin %s ha %s a %s la revisión de %s', array(
                                    Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                    Feed::item('relevant', Text::_('Asignado')),
                                    Feed::item('user', $userData->name, $userData->id),
                                    Feed::item('project', $reviewData->name, $reviewData->project)
                            )));
                            $log->setTarget($userData->id, 'user');
                            $log->doAdmin('admin');
                            unset($log);

                        } else {
                            Message::Error(implode(', ', $errors));
                        }
                    }
                    throw new Redirection('/admin/reviews');
                    break;
                case 'unassign':
                    // se la quitamos a este revisor
                    // la id de revision llega en $id
                    // la id del usuario llega por get
                    $user = $_GET['user'];
                    if (!empty($user)) {
                        $assignation = new Model\User\Review(array(
                            'id' => $id,
                            'user' => $user
                        ));
                        if ($assignation->remove($errors)) {

                            $userData = Model\User::getMini($user);
                            $reviewData = Model\Review::getData($id);

                            Message::Info(Text::_('Revisión desasignada correctamente'));

                            // Evento Feed
                            $log = new Feed();
                            $log->setTarget($userData->id, 'user');
                            $log->populate(Text::_('Desasignar revision (admin)'), '/admin/reviews',
                                \vsprintf('El admin %s ha %s a %s la revisión de %s', array(
                                    Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                    Feed::item('relevant', Text::_('Desasignado')),
                                    Feed::item('user', $userData->name, $userData->id),
                                    Feed::item('project', $reviewData->name, $reviewData->project)
                            )));
                            $log->doAdmin('admin');
                            unset($log);

                        } else {
                            Message::Error(implode(', ', $errors));
                        }
                    }
                    throw new Redirection('/admin/reviews');
                    break;
                case 'report':
                    // mostramos los detalles de revision
                    // ojo que este id es la id del proyecto, no de la revision
                    $review = Model\Review::get($id);
                    $review = Model\Review::getData($review->id);

                    $evaluation = array();

                    foreach ($review->checkers as $user=>$user_data) {
                        $evaluation[$user] = Model\Review::getEvaluation($review->id, $user);
                    }


                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'reviews',
                            'file' => 'report',
                            'review'     => $review,
                            'evaluation' => $evaluation
                        )
                    );
                    break;
            }

            // si hay proyecto filtrado, no filtramos estado
            if (!empty($filters['project'])) unset($filters['status']);

            $list = Model\Review::getList($filters, $node);
            $projects = Model\Review::getProjects($node);
            $status = array(
                'unstarted' => Text::_('No iniciada'),
                'open' => Text::_('Abierta'),
                'closed' => Text::_('Cerrada')
            );
            $checkers = Model\User::getAll(array('role'=>'checker'));

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'reviews',
                    'file' => 'list',
                    'list' => $list,
                    'filters' => $filters,
                    'projects' => $projects,
                    'status' => $status,
                    'checkers' => $checkers
                )
            );
            
        }

    }

}
