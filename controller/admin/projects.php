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
		Goteo\Library\Text,
		Goteo\Library\Feed,
        Goteo\Library\Message,
        Goteo\Library\Mail,
		Goteo\Library\Template,
        Goteo\Model;

    class Projects {

        public static function process ($action = 'list', $id = null, $filters = array()) {
            
            $log_text = null;
            $errors = array();

            // multiples usos
            $nodes = array();

            if ($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['id'])) {

                $projData = Model\Project::get($_POST['id']);
                if (empty($projData->id)) {
                    Message::Error('El proyecto '.$_POST['id'].' no existe');
                    throw new Redirection('/admin/projects/images/'.$id);
                }

                if (isset($_POST['save-dates'])) {
                    $fields = array(
                        'created',
                        'updated',
                        'published',
                        'success',
                        'closed',
                        'passed'
                        );

                    $set = '';
                    $values = array(':id' => $projData->id);

                    foreach ($fields as $field) {
                        if ($set != '') $set .= ", ";
                        $set .= "`$field` = :$field ";
                        if (empty($_POST[$field]) || $_POST[$field] == '0000-00-00')
                            $_POST[$field] = null;

                        $values[":$field"] = $_POST[$field];
                    }

                    try {
                        $sql = "UPDATE project SET " . $set . " WHERE id = :id";
                        if (Model\Project::query($sql, $values)) {
                            $log_text = 'El admin %s ha <span class="red">tocado las fechas</span> del proyecto '.$projData->name.' %s';
                        } else {
                            $log_text = 'Al admin %s le ha <span class="red">fallado al tocar las fechas</span> del proyecto '.$projData->name.' %s';
                        }
                    } catch(\PDOException $e) {
                        Message::Error(Text::_("No se ha guardado correctamente. "). $e->getMessage());
                    }
                } elseif (isset($_POST['save-accounts'])) {

                    $accounts = Model\Project\Account::get($projData->id);
                    $accounts->bank = $_POST['bank'];
                    $accounts->bank_owner = $_POST['bank_owner'];
                    $accounts->paypal = $_POST['paypal'];
                    $accounts->paypal_owner = $_POST['paypal_owner'];
                    if ($accounts->save($errors)) {
                        Message::Info(Text::_('Se han actualizado las cuentas del proyecto ').$projData->name);
                    } else {
                        Message::Error(implode('<br />', $errors));
                    }

                } elseif ($action == 'images') {
                    
                    $todook = true;
                    
                    if (!empty($_POST['move'])) {
                        $direction = $_POST['action'];
                        Model\Project\Image::$direction($id, $_POST['move'], $_POST['section']);
                    }
                    
                    foreach ($_POST as $key=>$value) {
                        $parts = explode('_', $key);
                        
                        if ($parts[1] == 'image' && in_array($parts[0], array('section', 'url'))) {
                            if (Model\Project\Image::update($id, $parts[2], $parts[0], $value)) {
                                // OK
                            } else {
                                $todook = false;
                                Message::Error(Text::_('No se ha podido actualizar campo')." {$parts[0]} -> {$value}");
                            }
                        }
                    }
                    
                    if ($todook) {
                        Message::Info(Text::_('Se han actualizado los datos'));
                    }
                    
                    throw new Redirection('/admin/projects/images/'.$id);
                    
                } elseif ($action == 'rebase') {
                    
                    $todook = true;
                    
                    if ($_POST['proceed'] == 'rebase' && !empty($_POST['newid'])) {

                        $newid = $_POST['newid'];

                        // pimero miramos que no hay otro proyecto con esa id
                        $test = Model\Project::getMini($newid);
                        if ($test->id == $newid) {
                            Message::Error(Text::_('Ya hay un proyecto con ese Id.'));
                            throw new Redirection('/admin/projects/rebase/'.$id);
                        }

                        if ($projData->status >= 3 && $_POST['force'] != 1) {
                            Message::Error(Text::_('El proyecto no está ni en Edición ni en Revisión, no se modifica nada.'));
                            throw new Redirection('/admin/projects/rebase/'.$id);
                        }

                        if ($projData->rebase($newid)) {
                            Message::Info(Text::_('Verificar el proyecto').' -> <a href="'.SITE_URL.'/project/'.$newid.'" target="_blank">'.$projData->name.'</a>');
                            throw new Redirection('/admin/projects');
                        } else {
                            Message::Info(Text::_('Ha fallado algo en el rebase, verificar el proyecto').' -> <a href="'.SITE_URL.'/project/'.$projData->id.'" target="_blank">'.$projData->name.' ('.$id.')</a>');
                            throw new Redirection('/admin/projects/rebase/'.$id);
                        }

                        
                    }
                    
                }

            }

            /*
             * switch action,
             * proceso que sea,
             * redirect
             *
             */
            if (isset($id)) {
                $project = Model\Project::get($id);
            }
            switch ($action) {
                case 'review':
                    // pasar un proyecto a revision
                    if ($project->ready($errors)) {
                        $redir = '/admin/reviews/add/'.$project->id;
                        $log_text = 'El admin %s ha pasado el proyecto %s al estado <span class="red">Revision</span>';
                    } else {
                        $log_text = 'Al admin %s le ha fallado al pasar el proyecto %s al estado <span class="red">Revision</span>';
                    }
                    break;
                case 'publish':
                    // poner un proyecto en campa�a
                    if ($project->publish($errors)) {
                        $log_text = 'El admin %s ha pasado el proyecto %s al estado <span class="red">en Campa�a</span>';
                    } else {
                        $log_text = 'Al admin %s le ha fallado al pasar el proyecto %s al estado <span class="red">en Campa�a</span>';
                    }
                    break;
                case 'cancel':
                    // descartar un proyecto por malo
                    if ($project->cancel($errors)) {
                        $log_text = 'El admin %s ha pasado el proyecto %s al estado <span class="red">Descartado</span>';
                    } else {
                        $log_text = 'Al admin %s le ha fallado al pasar el proyecto %s al estado <span class="red">Descartado</span>';
                    }
                    break;
                case 'enable':
                    // si no esta en edicion, recuperarlo
                    if ($project->enable($errors)) {
                        $log_text = 'El admin %s ha pasado el proyecto %s al estado <span class="red">Edicion</span>';
                    } else {
                        $log_text = 'Al admin %s le ha fallado al pasar el proyecto %s al estado <span class="red">Edicion</span>';
                    }
                    break;
                case 'fulfill':
                    // marcar que el proyecto ha cumplido con los retornos colectivos
                    if ($project->satisfied($errors)) {
                        $log_text = 'El admin %s ha pasado el proyecto %s al estado <span class="red">Retorno cumplido</span>';
                    } else {
                        $log_text = 'Al admin %s le ha fallado al pasar el proyecto %s al estado <span class="red">Retorno cumplido</span>';
                    }
                    break;
                case 'unfulfill':
                    // dar un proyecto por financiado manualmente
                    if ($project->rollback($errors)) {
                        $log_text = 'El admin %s ha pasado el proyecto %s al estado <span class="red">Financiado</span>';
                    } else {
                        $log_text = 'Al admin %s le ha fallado al pasar el proyecto %s al estado <span class="red">Financiado</span>';
                    }
                    break;
            }

            if (isset($log_text)) {
                // Evento Feed
                $log = new Feed();
                $log->setTarget($project->id);
                $log->populate(Text::_('Cambio estado/fechas/cuentas/nodo de un proyecto desde el admin'), '/admin/projects',
                    \vsprintf($log_text, array(
                    Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                    Feed::item('project', $project->name, $project->id)
                )));
                $log->doAdmin('admin');

                Message::Info($log->html);
                if (!empty($errors)) {
                    Message::Error(implode('<br />', $errors));
                }

                if ($action == 'publish') {
                    // si es publicado, hay un evento publico
                    $log->populate($project->name, '/project/'.$project->id, Text::html('feed-new_project'), $project->gallery[0]->id);
                    $log->doPublic('projects');
                }

                unset($log);

                if (empty($redir)) {
                    throw new Redirection('/admin/projects/list');
                } else {
                    throw new Redirection($redir);
                }
            }

            if ($action == 'report') {
                // informe financiero
                // Datos para el informe de transacciones correctas
                $Data = Model\Invest::getReportData($project->id, $project->status, $project->round, $project->passed);

                return new View(
                    'view/admin/index.html.php',
                    array(
                        'folder' => 'projects',
                        'file' => 'report',
                        'project' => $project,
                        'Data' => $Data
                    )
                );
            }

            if ($action == 'dates') {
                // cambiar fechas
                return new View(
                    'view/admin/index.html.php',
                    array(
                        'folder' => 'projects',
                        'file' => 'dates',
                        'project' => $project
                    )
                );
            }

            if ($action == 'accounts') {

                $accounts = Model\Project\Account::get($project->id);

                // cambiar fechas
                return new View(
                    'view/admin/index.html.php',
                    array(
                        'folder' => 'projects',
                        'file' => 'accounts',
                        'project' => $project,
                        'accounts' => $accounts
                    )
                );
            }

            if ($action == 'images') {
                
                // imagenes
                $images = array();
                
                // secciones
                $sections = Model\Project\Image::sections();
                foreach ($sections as $sec=>$secName) {
                    $secImages = Model\Project\Image::get($project->id, $sec);
                    foreach ($secImages as $img) {
                        $images[$sec][] = $img;
                    }
                }

                return new View(
                    'view/admin/index.html.php',
                    array(
                        'folder' => 'projects',
                        'file' => 'images',
                        'project' => $project,
                        'images' => $images,
                        'sections' => $sections
                    )
                );
            }

            if ($action == 'move') {
                // cambiar el nodo
                return new View(
                    'view/admin/index.html.php',
                    array(
                        'folder' => 'projects',
                        'file' => 'move',
                        'project' => $project,
                        'nodes' => $nodes
                    )
                );
            }


            if ($action == 'rebase') {
                // cambiar la id
                return new View(
                    'view/admin/index.html.php',
                    array(
                        'folder' => 'projects',
                        'file' => 'rebase',
                        'project' => $project
                    )
                );
            }


            // Rechazo express
            if ($action == 'reject') {
                if (empty($project)) {
                    Message::Error(Text::_('No hay proyecto sobre el que operar'));
                } else {
                    // Obtenemos la plantilla para asunto y contenido
                    $template = Template::get(40);
                    // Sustituimos los datos
                    $subject = str_replace('%PROJECTNAME%', $project->name, $template->title);
                    $search  = array('%USERNAME%', '%PROJECTNAME%');
                    $replace = array($project->user->name, $project->name);
                    $content = \str_replace($search, $replace, $template->text);
                    // iniciamos mail
                    $mailHandler = new Mail();
                    $mailHandler->to = $project->user->email;
                    $mailHandler->toName = $project->user->name;
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
                }

                throw new Redirection('/admin/projects/list');
            }


            if (!empty($filters['filtered'])) {
                $projects = Model\Project::getList($filters, $_SESSION['admin_node']);
            } else {
                $projects = array();
            }
            $status = Model\Project::status();
            $categories = Model\Project\Category::getAll();
            //@CONTRACTSYS
            $calls = array();
            // la lista de nodos la hemos cargado arriba
            $orders = array(
                'name' => Text::_('Nombre'),
                'updated' => Text::_('Enviado a revision')
            );

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'projects',
                    'file' => 'list',
                    'projects' => $projects,
                    'filters' => $filters,
                    'status' => $status,
                    'categories' => $categories,
//                    'contracts' => $contracts,
                    'calls' => $calls,
                    'nodes' => $nodes,
                    'orders' => $orders
                )
            );
            
        }

    }

}
