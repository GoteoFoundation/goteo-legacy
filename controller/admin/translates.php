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
        Goteo\Library\Mail,
		Goteo\Library\Template,
		Goteo\Library\Message,
        Goteo\Model;

    class Translates {

        public static function process ($action = 'list', $id = null, $filters = array()) {

            $node = isset($_SESSION['admin_node']) ? $_SESSION['admin_node'] : \GOTEO_NODE;
            
            $errors  = array();

            switch ($action) {
                case 'add':
                    // proyectos que están más allá de edición y con traducción deshabilitada
                    $availables = Model\User\Translate::getAvailables('project', $_SESSION['admin_node']);
                    if (empty($availables)) {
                        Message::Error(Text::_('No hay más proyectos disponibles para traducir'));
                        throw new Redirection('/admin/translates');
                    }

                case 'edit':
                case 'assign':
                case 'unassign':
                case 'send':

                    // a ver si tenemos proyecto
                    if (empty($id) && !empty($_POST['project'])) {
                        $id = $_POST['project'];
                    }

                    if (!empty($id)) {
                        $project = Model\Project::getMini($id);
                    } elseif ($action != 'add') {
                        Message::Error(Text::_('No hay proyecto sobre el que operar'));
                        throw new Redirection('/admin/translates');
                    }

                    // asignar o desasignar
                    // la id de revision llega en $id
                    // la id del usuario llega por get
                    $user = $_GET['user'];
                    if (!empty($user)) {
                        $userData = Model\User::getMini($user);

                        $assignation = new Model\User\Translate(array(
                            'item' => $project->id,
                            'type' => 'project',
                            'user' => $user
                        ));

                        switch ($action) {
                            case 'assign': // se la ponemos
                                $what = Text::_('Asignado');
                                if ($assignation->save($errors)) {
                                    Message::Info(Text::_('Traducción asignada correctamente'));
                                    throw new Redirection('/admin/translates/edit/'.$project->id);
                                } else {
                                    Message::Error(Text::_('No se ha guardado correctamente. ').implode(', ', $errors));
                                }
                                break;
                            case 'unassign': // se la quitamos
                                $what = Text::_('Desasignado');
                                if ($assignation->remove($errors)) {
                                    Message::Info(Text::_('Traducción desasignada correctamente'));
                                    throw new Redirection('/admin/translates/edit/'.$project->id);
                            } else {
                                    Message::Error(Text::_('No se ha guardado correctamente. ').implode(', ', $errors));
                                }
                                break;
                        }

                        if (empty($errors)) {
                            // Evento Feed
                            $log = new Feed();
                            $log->setTarget($userData->id, 'user');
                            $log->populate($what . ' traduccion (admin)', '/admin/translates',
                                \vsprintf('El admin %s ha %s a %s la traducción del proyecto %s', array(
                                    Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                    Feed::item('relevant', $what),
                                    Feed::item('user', $userData->name, $userData->id),
                                    Feed::item('project', $project->name, $project->id)
                            )));
                            $log->doAdmin('admin');
                            unset($log);
                        }

                        $action = 'edit';
                    }
                    // fin asignar o desasignar

                    // añadir o actualizar
                    // se guarda el idioma original y si la traducción está abierta o cerrada
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {

                        if (empty($id)) {
                            Message::Error(Text::_('Hemos perdido de vista el proyecto'));
                            throw new Redirection('/admin/translates');
                        }

                        // ponemos los datos que llegan
                        $sql = "UPDATE project SET lang = :lang, translate = 1 WHERE id = :id";
                        if (Model\Project::query($sql, array(':lang'=>$_POST['lang'], ':id'=>$id))) {
                            if ($action == 'add') {
                                Message::Info('El proyecto '.$project->name.' se ha habilitado para traducir');
                            } else {
                                Message::Info(Text::_('Datos de traducción actualizados'));
                            }

                            if ($action == 'add') {
                                // Evento Feed
                                $log = new Feed();
                                $log->setTarget($project->id);
                                $log->populate(Text::_('proyecto habilitado para traducirse (admin)'), '/admin/translates',
                                    \vsprintf('El admin %s ha %s la traducción del proyecto %s', array(
                                        Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                        Feed::item('relevant', 'Habilitado'),
                                        Feed::item('project', $project->name, $project->id)
                                )));
                                $log->doAdmin('admin');
                                unset($log);

                                throw new Redirection('/admin/translates/edit/'.$project->id);
                            } else {
                                throw new Redirection('/admin/translates');
                            }
                        } else {
                            if ($action == 'add') {
                                Message::Error(Text::_('Ha fallado al habilitar la traducción del proyecto ') . $project->name);
                            } else {
                                Message::Error(Text::_('Ha fallado al actualizar los datos de la traducción'));
                            }
                        }
                    }

                    if ($action == 'send') {
                        // Informar al autor de que la traduccion está habilitada
                        // Obtenemos la plantilla para asunto y contenido
                        $template = Template::get(26);
                        // Sustituimos los datos
                        $subject = str_replace('%PROJECTNAME%', $project->name, $template->title);
                        $search  = array('%OWNERNAME%', '%PROJECTNAME%', '%SITEURL%');
                        $replace = array($project->user->name, $project->name, SITE_URL);
                        $content = \str_replace($search, $replace, $template->text);
                        // iniciamos mail
                        $mailHandler = new Mail();
                        $mailHandler->to = $project->user->email;
                        $mailHandler->toName = $project->user->name;
                        // blind copy a goteo desactivado durante las verificaciones
            //              $mailHandler->bcc = 'comunicaciones@goteo.org';
                        $mailHandler->subject = $subject;
                        $mailHandler->content = $content;
                        $mailHandler->html = true;
                        $mailHandler->template = $template->id;
                        if ($mailHandler->send()) {
                            Message::Info('Se ha enviado un email a <strong>'.$project->user->name.'</strong> a la dirección <strong>'.$project->user->email.'</strong>');
                        } else {
                            Message::Error('Ha fallado al enviar el mail a <strong>'.$project->user->name.'</strong> a la dirección <strong>'.$project->user->email.'</strong>');
                        }
                        unset($mailHandler);
                        $action = 'edit';
                    }


                    $project->translators = Model\User\Translate::translators($id);
                    $translators = Model\User::getAll(array('role'=>'translator'));
                    // añadimos al dueño del proyecto en el array de traductores
                    array_unshift($translators, $project->user);


                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'translates',
                            'file'   => 'edit',
                            'action' => $action,
                            'availables' => $availables,
                            'translators' => $translators,
                            'project'=> $project
                        )
                    );

                    break;
                case 'close':
                    // la sentencia aqui mismo
                    // el campo translate del proyecto $id a false
                    $sql = "UPDATE project SET translate = 0 WHERE id = :id";
                    if (Model\Project::query($sql, array(':id'=>$id))) {
                        Message::Info('La traducción del proyecto '.$project->name.' se ha finalizado');

                        Model\Project::query("DELETE FROM user_translate WHERE type = 'project' AND item = :id", array(':id'=>$id));

                        // Evento Feed
                        $log = new Feed();
                        $log->setTarget($project->id);
                        $log->populate(Text::_('traducción finalizada (admin)'), '/admin/translates',
                            \vsprintf('El admin %s ha dado por %s la traducción del proyecto %s', array(
                                Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                Feed::item('relevant', 'Finalizada'),
                                Feed::item('project', $project->name, $project->id)
                        )));
                        $log->doAdmin('admin');
                        unset($log);

                    } else {
                        Message::Error(Text::_('Falló al finalizar la traducción'));
                    }
                    break;
            }

            $projects = Model\Project::getTranslates($filters, $node);
            $owners = Model\User::getOwners();
            $translators = Model\User::getAll(array('role'=>'translator'));

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'translates',
                    'file' => 'list',
                    'projects' => $projects,
                    'filters' => $filters,
                    'fields'  => array('owner', 'translator'),
                    'owners' => $owners,
                    'translators' => $translators
                )
            );
            
        }

    }

}
