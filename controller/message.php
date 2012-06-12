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
		Goteo\Library\Feed,
        Goteo\Library\Mail,
        Goteo\Library\Template,
        Goteo\Library\Text;

    class Message extends \Goteo\Core\Controller {

        public function index ($project = null) {
            if (empty($project))
                throw new Redirection('/discover', Redirection::PERMANENT);

			if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['message'])) {

                $projectData = Model\Project::getMini($project);

                if ($projectData->status < 3) {
                    \Goteo\Library\Message::Error(Text::get('project-messages-closed'));
                    throw new Redirection("/project/{$project}");
                }

                $message = new Model\Message(array(
                    'user' => $_SESSION['user']->id,
                    'project' => $project,
                    'thread' => $_POST['thread'],
                    'message' => $_POST['message']
                ));

                if ($message->save($errors)) {

                    /*
                     * Evento Feed
                     */
                    $log = new Feed();
                    $log->title = 'usuario escribe mensaje/respuesta en Mensajes del proyecto';
                    $log->url = '/admin/projects';
                    $log->type = 'user';

                    if (empty($_POST['thread'])) {
                        // nuevo hilo
                        $log_text = '%s ha creado un tema en %s del proyecto %s';
                        $log_items = array(
                            Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                            Feed::item('message', Text::get('project-menu-messages'), $projectData->id.'/messages#message'.$message->id),
                            Feed::item('project', $projectData->name, $projectData->id)
                        );
                        $log->html = \vsprintf($log_text, $log_items);
                    } else {
                        // respuesta
                        $log_text = '%s ha respondido en %s del proyecto %s';
                        $log_items = array(
                            Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                            Feed::item('message', Text::get('project-menu-messages'), $projectData->id.'/messages#message'.$message->id),
                            Feed::item('project', $projectData->name, $projectData->id)
                        );
                        $log->html = \vsprintf($log_text, $log_items);
                    }

                    $log->add($errors);

                    // y el mensaje público
                    $log->title = $_SESSION['user']->name;
                    $log->url = '/user/profile/'.$_SESSION['user']->id;
                    $log->image = $_SESSION['user']->avatar->id;
                    $log->scope = 'public';
                    $log->type = 'community';

                    if (empty($_POST['thread'])) {
                        $log->html = Text::html('feed-messages-new_thread',
                                            Feed::item('message', Text::get('project-menu-messages'), $projectData->id.'/messages#message'.$message->id),
                                            Feed::item('project', $projectData->name, $projectData->id)
                                            );
                    } else {
                        $log->html = Text::html('feed-messages-response',
                                            Feed::item('message', Text::get('project-menu-messages'), $projectData->id.'/messages#message'.$message->id),
                                            Feed::item('project', $projectData->name, $projectData->id)
                                            );
                    }

                    $log->add($errors);

                    unset($log);

                    if (!empty($_POST['thread'])) {
                        // aqui el owner es el autor del mensaje thread
                        $thread = Model\Message::get($_POST['thread']);

                        // Si no tiene estas notiicaciones bloqueadas en sus preferencias
                        $sql = "
                            SELECT user_prefer.threads
                            FROM user_prefer
                            WHERE user = :user
                            ";
                        $query = Model\Project::query($sql, array(':user' => $thread->user->id));
                        $spam = $query->fetchColumn(0);
                        if (!$spam) {
                            // Mail al autor del thread
                            // Obtenemos la plantilla para asunto y contenido
                            $template = Template::get(12);

                            // Sustituimos los datos
                            $subject = str_replace('%PROJECTNAME%', $projectData->name, $template->title);

                            $response_url = SITE_URL . '/user/profile/' . $_SESSION['user']->id . '/message';
                            $project_url = SITE_URL . '/project/' . $projectData->id . '/messages#message'.$message->id;

                            $search  = array('%MESSAGE%', '%OWNERNAME%', '%USERNAME%', '%PROJECTNAME%', '%PROJECTURL%', '%RESPONSEURL%');
                            $replace = array($_POST['message'], $thread->user->name, $_SESSION['user']->name, $projectData->name, $project_url, $response_url);
                            $content = \str_replace($search, $replace, $template->text);

                            $mailHandler = new Mail();

                            $mailHandler->to = $thread->user->email;
                            $mailHandler->toName = $thread->user->name;
                            $mailHandler->subject = $subject;
                            $mailHandler->content = $content;
                            $mailHandler->html = true;
                            $mailHandler->template = $template->id;
                            $mailHandler->send($errors);

                            unset($mailHandler);
                        }
                    } else {
                        // mensaje al autor del proyecto
                        // Obtenemos la plantilla para asunto y contenido
                        $template = Template::get(30);

                        // Sustituimos los datos
                        $subject = str_replace('%PROJECTNAME%', $projectData->name, $template->title);

                        $response_url = SITE_URL . '/user/profile/' . $_SESSION['user']->id . '/message';
                        $project_url = SITE_URL . '/project/' . $projectData->id . '/messages#message'.$message->id;

                        $search  = array('%MESSAGE%', '%OWNERNAME%', '%USERNAME%', '%PROJECTNAME%', '%PROJECTURL%', '%RESPONSEURL%');
                        $replace = array($_POST['message'], $projectData->user->name, $_SESSION['user']->name, $projectData->name, $project_url, $response_url);
                        $content = \str_replace($search, $replace, $template->text);

                        $mailHandler = new Mail();

                        $mailHandler->to = $projectData->user->email;
                        $mailHandler->toName = $projectData->user->name;
                        $mailHandler->subject = $subject;
                        $mailHandler->content = $content;
                        $mailHandler->html = true;
                        $mailHandler->template = $template->id;
                        $mailHandler->send($errors);

                        unset($mailHandler);
                    }


                }
			}

            throw new Redirection("/project/{$project}/messages#message".$message->id, Redirection::TEMPORARY);
        }

        public function edit ($id, $project) {

            if (isset($_POST['message'])) {
                $message = Model\Message::get($id);
                $message->user = $message->user->id;
                $message->message = ($_POST['message']);

                $message->save();
            }

            throw new Redirection("/project/{$project}/messages", Redirection::TEMPORARY);
        }

        public function delete ($id, $project) {

            Model\Message::get($id)->delete();

            throw new Redirection("/project/{$project}/messages", Redirection::TEMPORARY);
        }

        /*
         * Este metodo envia un mensaje interno
         */
        public function direct ($project = null) {
            if (empty($project))
                throw new Redirection('/discover', Redirection::PERMANENT);

            if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['message'])) {

                // sacamos el mail del responsable del proyecto
                $project = Model\Project::getMini($project);
                $ownerData = Model\User::getMini($project->owner);

                $msg_content = \nl2br(\strip_tags($_POST['message']));

                // Obtenemos la plantilla para asunto y contenido
                $template = Template::get(3);

                // Sustituimos los datos
                // En el asunto: %PROJECTNAME% por $project->name
                $subject = str_replace('%PROJECTNAME%', $project->name, $template->title);

                $response_url = SITE_URL . '/user/profile/' . $_SESSION['user']->id . '/message';

                // En el contenido:  nombre del autor -> %OWNERNAME% por $project->contract_name
                // el mensaje que ha escrito el productor -> %MESSAGE% por $msg_content
                // nombre del usuario que ha aportado -> %USERNAME% por $_SESSION['user']->name
                // nombre del proyecto -> %PROJECTNAME% por $project->name
                // url de la plataforma -> %SITEURL% por SITE_URL
                $search  = array('%MESSAGE%', '%OWNERNAME%', '%USERNAME%', '%PROJECTNAME%', '%SITEURL%', '%RESPONSEURL%');
                $replace = array($msg_content, $ownerData->name, $_SESSION['user']->name, $project->name, SITE_URL, $response_url);
                $content = \str_replace($search, $replace, $template->text);
                
                $mailHandler = new Mail();

                $mailHandler->to = $ownerData->email;
                $mailHandler->toName = $ownerData->name;
                // blind copy a goteo desactivado durante las verificaciones
//              $mailHandler->bcc = 'comunicaciones@goteo.org';
                $mailHandler->subject = $subject;
                $mailHandler->content = $content;
                $mailHandler->html = true;
                $mailHandler->template = $template->id;
                if ($mailHandler->send($errors)) {
                    // ok
                    \Goteo\Library\Message::Info(Text::get('regular-message_success'));
                } else {
                    \Goteo\Library\Message::Info(Text::get('regular-message_fail') . '<br />' . implode(', ', $errors));
                }

                unset($mailHandler);
			}

            throw new Redirection("/project/{$project->id}/messages", Redirection::TEMPORARY);
        }

        /*
         * Este metodo envia un mensaje personal
         */
        public function personal ($user = null) {
            if (empty($user))
                throw new Redirection('/community', Redirection::PERMANENT);

            if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['message'])) {

                // sacamos el mail del responsable del proyecto
                $user = Model\User::get($user);

                if (!$user instanceof Model\User) {
                    throw new Redirection('/', Redirection::TEMPORARY);
                }

                $msg_content = \nl2br(\strip_tags($_POST['message']));


                // Obtenemos la plantilla para asunto y contenido
                $template = Template::get(4);

                // Sustituimos los datos
                // En el asunto: %USERNAME% por $_SESSION['user']->name
                $subject = str_replace('%USERNAME%', $_SESSION['user']->name, $template->title);

                $response_url = SITE_URL . '/user/profile/' . $_SESSION['user']->id . '/message';
                $profile_url = SITE_URL."/user/profile/{$user->id}/sharemates";
                // En el contenido:  nombre del destinatario -> %TONAME% por $user->name
                // el mensaje que ha escrito el usuario -> %MESSAGE% por $msg_content
                // nombre del usuario -> %USERNAME% por $_SESSION['user']->name
                // url del perfil -> %PROFILEURL% por ".SITE_URL."/user/profile/{$user->id}/sharemates"
                $search  = array('%MESSAGE%','%TONAME%',  '%USERNAME%', '%PROFILEURL%', '%RESPONSEURL%');
                $replace = array($msg_content, $user->name, $_SESSION['user']->name, $profile_url, $response_url);
                $content = \str_replace($search, $replace, $template->text);


                $mailHandler = new Mail();

                $mailHandler->to = $user->email;
                $mailHandler->toName = $user->name;
                // blind copy a goteo desactivado durante las verificaciones
//                $mailHandler->bcc = 'comunicaciones@goteo.org';
                $mailHandler->subject = $subject;
                $mailHandler->content = $content;
                $mailHandler->html = true;
                $mailHandler->template = $template->id;
                if ($mailHandler->send($errors)) {
                    // ok
                    \Goteo\Library\Message::Info(Text::get('regular-message_success'));
                } else {
                    \Goteo\Library\Message::Info(Text::get('regular-message_fail') . '<br />' . implode(', ', $errors));
                }

                unset($mailHandler);
			}

            throw new Redirection("/user/profile/{$user->id}", Redirection::TEMPORARY);
        }

        /*
         * Metodo para publicar un comentario en un post
         */
        public function post ($post, $project = null) {

			if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['message'])) {
                $comment = new Model\Blog\Post\Comment(array(
                    'user' => $_SESSION['user']->id,
                    'post' => $post,
                    'date' => date('Y-m-d H:i:s'),
                    'text' => $_POST['message']
                ));

                if ($comment->save($errors)) {
                    // a ver los datos del post
                    $postData = Model\Blog\Post::get($post);
                    /*
                     * Evento Feed
                     */
                    $log = new Feed();
                    $log->title = 'usuario escribe comentario en blog/novedades';
                    $log->url = '/admin/projects';
                    $log->type = 'user';

                    if (!empty($project)) {
                        $projectData = Model\Project::getMini($project);
                        $log_text = '%s ha escrito un %s en la entrada "%s" en las %s del proyecto %s';
                        $log_items = array(
                            Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                            Feed::item('message', 'Comentario'),
                            Feed::item('update-comment', $postData->title, $projectData->id.'/updates/'.$postData->id.'#comment'.$comment->id),
                            Feed::item('update-comment', 'Novedades', $projectData->id.'/updates/'),
                            Feed::item('project', $projectData->name, $projectData->id)
                        );
                    } else {
                        $log_text = '%s ha escrito un %s en la entrada "%s" del blog de %s';
                        $log_items = array(
                            Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                            Feed::item('message', 'Comentario'),
                            Feed::item('blog', $postData->title, $postData->id.'#comment'.$comment->id),
                            Feed::item('blog', 'Goteo', '/')
                        );
                    }

                    $log->html = \vsprintf($log_text, $log_items);
                    $log->add($errors);

                    // y el mensaje público
                    $log->title = $_SESSION['user']->name;
                    $log->url = '/user/profile/'.$_SESSION['user']->id;
                    $log->image = $_SESSION['user']->avatar->id;
                    $log->scope = 'public';
                    $log->type = 'community';

                    if (!empty($project)) {
                        $projectData = Model\Project::getMini($project);
                        $log->html = Text::html('feed-updates-comment',
                                            Feed::item('update-comment', $postData->title, $projectData->id.'/updates/'.$postData->id.'#comment'.$comment->id),
                                            Feed::item('update-comment', 'Novedades', $projectData->id.'/updates/'),
                                            Feed::item('project', $projectData->name, $projectData->id)
                                            );
                    } else {
                        $log->html = Text::html('feed-blog-comment',
                                            Feed::item('blog', $postData->title, $postData->id.'#comment'.$comment->id),
                                            Feed::item('blog', 'Goteo', '/')
                                            );
                    }

                    $log->add($errors);

                    unset($log);

                    //Notificación al autor del proyecto
                    // Obtenemos la plantilla para asunto y contenido
                    $template = Template::get(31);

                    // Sustituimos los datos
                    $subject = str_replace('%PROJECTNAME%', $projectData->name, $template->title);

                    $response_url = SITE_URL . '/user/profile/' . $_SESSION['user']->id . '/message';
                    $project_url = SITE_URL . '/project/' . $projectData->id . '/updates/'.$postData->id.'#comment'.$comment->id;

                    $search  = array('%MESSAGE%', '%OWNERNAME%', '%USERNAME%', '%PROJECTNAME%', '%PROJECTURL%', '%RESPONSEURL%');
                    $replace = array($_POST['message'], $projectData->user->name, $_SESSION['user']->name, $projectData->name, $project_url, $response_url);
                    $content = \str_replace($search, $replace, $template->text);

                    $mailHandler = new Mail();

                    $mailHandler->to = $projectData->user->email;
                    $mailHandler->toName = $projectData->user->name;
                    $mailHandler->subject = $subject;
                    $mailHandler->content = $content;
                    $mailHandler->html = true;
                    $mailHandler->template = $template->id;
                    $mailHandler->send($errors);

                    unset($mailHandler);

                } else {
                    // error
                }
			}

            if (!empty($project)) {
                throw new Redirection("/project/{$project}/updates/{$post}#comment".$comment->id, Redirection::TEMPORARY);
            } else {
                throw new Redirection("/blog/{$post}#comment".$comment->id, Redirection::TEMPORARY);
            }
        }

    }

}