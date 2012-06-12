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
        Goteo\Library\Text,
        Goteo\Library\Template,
        Goteo\Library\Listing;

    class Dashboard extends \Goteo\Core\Controller {

        public function index ($section = null) {
            throw new Redirection('/dashboard/activity');
        }

        /*
         * Sección, Mi actividad
         * Opciones:
         *      'projects' los proyectos del usuario y a los que ha aportado,
         *      'comunity' relacion con la comunidad
         * 
         */
        public function activity ($option = 'summary', $action = 'view') {

            // quitamos el stepped para que no nos lo coja para el siguiente proyecto que editemos
            if (isset($_SESSION['stepped'])) {
                unset($_SESSION['stepped']);
            }
            
            $user = $_SESSION['user'];
            $status = Model\Project::status();

            // agrupacion de proyectos que cofinancia y proyectos suyos
            $lists = array();
            // mis proyectos
            $projects = Model\Project::ofmine($user->id);
            if (!empty($projects)) {
                $lists['my_projects'] = Listing::get($projects);
            }
            // proyectos que cofinancio
            $invested = Model\User::invested($user->id, false);
            if (!empty($invested)) {
                $lists['invest_on'] = Listing::get($invested);
            }

            foreach ($projects as $project) {

                // compruebo que puedo editar mis proyectos
                if (!ACL::check('/project/edit/'.$project->id)) {
                    ACL::allow('/project/edit/'.$project->id, '*', 'user', $user);
                }

                // y borrarlos
                if (!ACL::check('/project/delete/'.$project->id)) {
                    ACL::allow('/project/delete/'.$project->id, '*', 'user', $user);
                }
            }

            if ($option == 'summary') {
                $page = Page::get('dashboard');
                $message = \str_replace('%USER_NAME%', $_SESSION['user']->name, $page->content);
            } else {
                $message = null;
            }

            return new View (
                'view/dashboard/index.html.php',
                array(
                    'menu'    => self::menu(),
                    'section' => __FUNCTION__,
                    'option'  => $option,
                    'action'  => $action,
                    'message' => $message,
                    'lists'   => $lists,
                    'status'  => $status,
                    'errors'  => $errors,
                    'success' => $success
                )
            );

        }

        /*
         * Seccion, Mi perfil
         * Opciones:
         *      'public' perfil público (paso 1), 
         *      'personal' datos personales (paso 2),
         *      'access' configuracion (cambio de email y contraseña)
         *
         */
        public function profile ($option = 'profile', $action = 'edit') {

            // tratamos el post segun la opcion y la acion
            $user = $_SESSION['user'];

            if ($option == 'public') {
                throw new Redirection('/user/profile/'.$user->id);
            }

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $log_action = null;

			    $errors = array();
                switch ($option) {
                    // perfil publico
                    case 'profile':
                        // tratar la imagen y ponerla en la propiedad avatar
                        // __FILES__

                        $fields = array(
                            'user_name'=>'name',
                            'user_location'=>'location',
                            'user_avatar'=>'avatar',
                            'user_about'=>'about',
                            'user_keywords'=>'keywords',
                            'user_contribution'=>'contribution',
                            'user_facebook'=>'facebook',
                            'user_google'=>'google',
                            'user_twitter'=>'twitter',
                            'user_identica'=>'identica',
                            'user_linkedin'=>'linkedin'
                        );

                        foreach ($fields as $fieldPost=>$fieldTable) {
                            if (isset($_POST[$fieldPost])) {
                                $user->$fieldTable = $_POST[$fieldPost];
                            }
                        }

                        // Avatar
                        if(!empty($_FILES['avatar_upload']['name'])) {
                            $user->avatar = $_FILES['avatar_upload'];
                        }

                        // tratar si quitan la imagen
                        if (!empty($_POST['avatar-' . $user->avatar->id .  '-remove'])) {
                            $user->avatar->remove('user');
                            $user->avatar = '';
                        }

                        $user->interests = $_POST['user_interests'];

                        //tratar webs existentes
                        foreach ($user->webs as $i => &$web) {
                            // luego aplicar los cambios

                            if (isset($_POST['web-'. $web->id . '-url'])) {
                                $web->url = $_POST['web-'. $web->id . '-url'];
                            }

                            //quitar las que quiten
                            if (!empty($_POST['web-' . $web->id .  '-remove'])) {
                                unset($user->webs[$i]);
                            }

                        }

                        //tratar nueva web
                        if (!empty($_POST['web-add'])) {
                            $user->webs[] = new Model\User\Web(array(
                                'url'   => 'http://'
                            ));
                        }

                        /// este es el único save que se lanza desde un metodo process_
                        if ($user->save($errors)) {
                            Message::Info(Text::get('user-profile-saved'));
                            $user = Model\User::flush();

//                            $log_action = 'Modificado su información de perfil'; //feed admin
                        }
                    break;
                    
                    // datos personales
                    case 'personal':
                        // campos que guarda este paso
                        $fields = array(
                            'contract_name',
                            'contract_nif',
                            'phone',
                            'address',
                            'zipcode',
                            'location',
                            'country'
                        );

                        $personalData = array();

                        foreach ($fields as $field) {
                            if (isset($_POST[$field])) {
                                $personalData[$field] = $_POST[$field];
                            }
                        }

                        // actualizamos estos datos en los personales del usuario
                        if (!empty ($personalData)) {
                            if (Model\User::setPersonal($user->id, $personalData, true, $errors)) {
                                Message::Info(Text::get('user-personal-saved'));

                                $log_action = 'Modificado sus datos personales'; //feed admin
                            }
                        }
                    break;

                    //cambio de email y contraseña
                    case 'access':
                        // E-mail
                        if(!empty($_POST['user_nemail']) || !empty($_POST['user_remail'])) {
                            if(empty($_POST['user_nemail'])) {
                                $errors['email'] = Text::get('error-user-email-empty');
                            }
                            elseif(!\Goteo\Library\Check::mail($_POST['user_nemail'])) {
                                $errors['email'] = Text::get('error-user-email-invalid');
                            }
                            elseif(empty($_POST['user_remail'])) {
                                $errors['email_retry'] = Text::get('error-user-email-empty');
                            }
                            elseif (strcmp($_POST['user_nemail'], $_POST['user_remail']) !== 0) {
                                $errors['email_retry'] = Text::get('error-user-email-confirm');
                            }
                            else {
                                $user->email = $_POST['user_nemail'];
                                unset($_POST['user_nemail']);
                                unset($_POST['user_remail']);
                                $success[] = Text::get('user-email-change-sended');

                                $log_action = 'Cambiado su email'; //feed admin
                            }
                        }
                        // Contraseña
                        if(!empty($_POST['user_npassword']) ||!empty($_POST['user_rpassword'])) {
                    // Ya no checkeamos más la contraseña actual (ni en recover ni en normal)
                    // porque los usuarios que acceden mediante servicio no tienen contraseña
                            /*
                            if(!isset($_SESSION['recovering']) && empty($_POST['user_password'])) {
                                $errors['password'] = Text::get('error-user-password-empty');
                            }
                            elseif(!isset($_SESSION['recovering']) && !Model\User::login($user->id, $_POST['user_password'])) {
                                $errors['password'] = Text::get('error-user-wrong-password');
                            }
                            else
                            */
                            if(empty($_POST['user_npassword'])) {
                                $errors['password_new'] = Text::get('error-user-password-empty');
                            }
                            elseif(!\Goteo\Library\Check::password($_POST['user_npassword'])) {
                                $errors['password_new'] = Text::get('error-user-password-invalid');
                            }
                            elseif(empty($_POST['user_rpassword'])) {
                                $errors['password_retry'] = Text::get('error-user-password-empty');
                            }
                            elseif(strcmp($_POST['user_npassword'], $_POST['user_rpassword']) !== 0) {
                                $errors['password_retry'] = Text::get('error-user-password-confirm');
                            }
                            else {
                                $user->password = $_POST['user_npassword'];
                                unset($_POST['user_password']);
                                unset($_POST['user_npassword']);
                                unset($_POST['user_rpassword']);
                                $success[] = Text::get('user-password-changed');

                                $log_action = 'Cambiado su contraseña'; //feed admin
                            }
                        }
                        if(empty($errors) && $user->save($errors)) {
                            // Refresca la sesión.
                            $user = Model\User::flush();
                            if (isset($_SESSION['recovering'])) unset($_SESSION['recovering']);
                        } else {
                            $errors[] = Text::get('user-save-fail');
                        }
                    break;

                    // preferencias de notificación
                    case 'preferences':
                        // campos de preferencias
                        $fields = array(
                            'updates',
                            'threads',
                            'rounds',
                            'mailing'
                        );

                        $preferences = array();

                        foreach ($fields as $field) {
                            if (isset($_POST[$field])) {
                                $preferences[$field] = $_POST[$field];
                            }
                        }

                        // actualizamos estos datos en los personales del usuario
                        if (!empty ($preferences)) {
                            if (Model\User::setPreferences($user->id, $preferences, $errors)) {
                                Message::Info(Text::get('user-prefer-saved'));
                                $log_action = 'Modificado las preferencias de notificación'; //feed admin
                            }
                        }
                    break;

                }

                if (!empty($log_action)) {
                        /*
                         * Evento Feed
                         */
                        $log = new Feed();
                        $log->title = 'usuario '.$log_action.' (dashboard)';
                        $log->url = '/admin/users';
                        $log->type = 'user';
                        $log_text = '%s ha %s desde su dashboard';
                        $log_items = array(
                            Feed::item('user', $user->name, $user->id),
                            Feed::item('relevant', $log_action)
                        );
                        $log->html = \vsprintf($log_text, $log_items);
                        $log->add($errors);

                        unset($log);
                }

			}

            $viewData = array(
                    'menu'    => self::menu(),
                    'section' => __FUNCTION__,
                    'option'  => $option,
                    'action'  => $action,
                    'errors'  => $errors,
                    'success' => $success,
                    'user'    => $user
                );

                switch ($option) {
                    case 'profile':
                        $viewData['interests'] = Model\User\Interest::getAll();

                        if ($_POST) {
                            foreach ($_POST as $k => $v) {
                                if (!empty($v) && preg_match('/web-(\d+)-edit/', $k, $r)) {
                                    $viewData[$k] = true;
                                    break;
                                }
                            }
                        }

                        if (!empty($_POST['web-add'])) {
                            $last = end($user->webs);
                            if ($last !== false) {
                                $viewData["web-{$last->id}-edit"] = true;
                            }
                        }
                        break;
                    case 'personal':
                        $viewData['personal'] = Model\User::getPersonal($user->id);
                        break;
                    case 'preferences':
                        $viewData['preferences'] = Model\User::getPreferences($user->id);
                        break;
                    case 'access':
                        // si es recover, en contraseña actual tendran que poner el username
                        if ($action == 'recover') {
                            $viewData['message'] = Text::get('dashboard-password-recover-advice');
                        }
                        break;
                }


            return new View (
                'view/dashboard/index.html.php',
                $viewData
            );
        }


        /*
         * Seccion, Mis proyectos
         * Opciones:
         *      'actualizaciones' blog del proyecto (ahora son como mensajes),
         *      'editar colaboraciones' para modificar los mensajes de colaboraciones (no puede editar el proyecto y ya estan publicados)
         *      'widgets' ofrece el código para poner su proyecto en otras páginas (vertical y horizontal)
         *      'licencia' el acuerdo entre goteo y el usuario, licencia cc-by-nc-nd, enlace al pdf
         *      'gestionar retornos' resumen recompensas/cofinanciadores/conseguido  y lista de cofinanciadores y recompensas esperadas
         *      'pagina publica' enlace a la página pública del proyecto
         *
         */
        public function projects ($option = 'summary', $action = 'list', $id = null) {
            
            $user    = $_SESSION['user'];

            $errors = array();

            $projects = Model\Project::ofmine($user->id);

            // si no hay proyectos no tendria que estar aqui
            if (!empty($projects)) {
                // compruebo permisos
                foreach ($projects as $proj) {

                    // compruebo que puedo editar mis proyectos
                    if (!ACL::check('/project/edit/'.$proj->id)) {
                        ACL::allow('/project/edit/'.$proj->id, '*', 'user', $user);
                    }

                    // y borrarlos
                    if (!ACL::check('/project/delete/'.$proj->id)) {
                        ACL::allow('/project/delete/'.$proj->id, '*', 'user', $user);
                    }
                }
            }

            if ($action == 'select' && !empty($_POST['project'])) {
                // otro proyecto de trabajo
                $project = Model\Project::get($_POST['project']);
            } else {
                // si tenemos ya proyecto, mantener los datos actualizados
                if (!empty($_SESSION['project']->id)) {
                    $project = Model\Project::get($_SESSION['project']->id);
                }
            }

            if (empty($project) && !empty($projects)) {
                $project = $projects[0];
            }

            // aqui necesito tener un proyecto de trabajo,
            // si no hay ninguno ccoge el último
            if ($project instanceof  \Goteo\Model\Project) {
                $_SESSION['project'] = $project;
            } else {
                unset($project);
                $option = 'summary';
            }

            // tenemos proyecto de trabajo, comprobar si el proyecto esta en estado de tener blog
            if ($option == 'updates' && $project->status < 3) {
                $errors[] = Text::get('dashboard-project-blog-wrongstatus');
                $action = 'none';
            } elseif ($option == 'updates') {
                // solo cargamos el blog en la gestion de updates
                $blog = Model\Blog::get($project->id);
                if (!$blog instanceof \Goteo\Model\Blog) {
                    $blog = new Model\Blog(
                            array(
                                'id' => '',
                                'type' => 'project',
                                'owner' => $project->id,
                                'active' => true,
                                'project' => $project->id,
                                'posts' => array()
                            )
                        );
                    if (!$blog->save($errors)) {
                        $errors[] = Text::get('dashboard-project-blog-fail');
                        $option = 'summary';
                        $action = 'none';
                    }
                } elseif (!$blog->active) {
                        $errors[] = Text::get('dashboard-project-blog-inactive');
                        $action = 'none';
                    }

                // primero comprobar que tenemos blog
                if (!$blog instanceof Model\Blog) {
                    $errors[] = Text::get('dashboard-project-updates-noblog');
                    $option = 'summary';
                    $action = 'none';
                    break;
                }

            }



			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                
                switch ($option) {
                    // gestionar retornos
                    case 'rewards':
                        // segun action
                        switch ($action) {
                            // filtro
                            case 'filter':
                                $filter = $_POST['filter'];
                                $order  = $_POST['order'];
                            break;
                        
                            // procesar marcas
                            case 'process':
                                $filter = $_POST['filter'];
                                $order  = $_POST['order'];
                                // todos los checkboxes
                                $fulfill = array();
                                // se marcan con Model/Invest con el id del aporte y el id de la recompensa
                                // estos son ful_reward-[investid]-[rewardId]
                                // no se pueden descumplir porque viene sin value (un admin en todo caso?)
                                // o cuando sea con ajax @FIXME
                                foreach ($_POST as $key=>$value) {
                                    $parts = explode('-', $key);
                                    if ($parts[0] == 'ful_reward') {
                                        Model\Invest::setFulfilled($parts[1], $parts[2]);
                                    }
                                }
                            break;

                            // enviar mensaje
                            case 'message':
                                $filter = $_POST['filter'];
                                $order  = $_POST['order'];

                                if (empty($_POST['message'])) {
                                    $errors[] = Text::get('dashboard-investors-mail-text-required');
                                    break;
                                } else {
                                    $msg_content = \strip_tags($_POST['message']);
                                    $msg_content = nl2br($msg_content);
                                }

                                if (!empty($_POST['msg_all'])) {
                                    // si a todos
                                    $who = array();
                                    foreach (Model\Invest::investors($project->id, false, true) as $user=>$investor) {
                                        if (!in_array($user, $who)) {
                                            $who[$user] = $investor->user;
                                        }
                                    }
                                } else {
                                    $msg_rewards = array();
                                    // estos son msg_reward-[rewardId]
                                    foreach ($_POST as $key=>$value) {
                                        $parts = explode('-', $key);
                                        if ($parts[0] == 'msg_reward' && $value == 1) {
                                            $msg_rewards[] = $parts[1];
                                        }
                                    }

                                    $who = array();
                                    // para cada recompensa
                                    foreach ($msg_rewards as $reward) {
                                        foreach (Model\Invest::choosed($reward) as $user) {
                                            if (!in_array($user, $who)) {
                                                $who[] = $user;
                                            }
                                        }
                                    }
                                }

                                if (count($who) == 0) {
                                    $errors[] = Text::get('dashboard-investors-mail-nowho');
                                    break;
                                }

                                // obtener contenido
                                // segun destinatarios
                                $allsome = explode('/', Text::get('regular-allsome'));
                                $enviandoa = !empty($_POST['msg_all']) ? $allsome[0] : $allsome[1];
                                Message::Info(Text::get('dashboard-investors-mail-sendto', $enviandoa)) ;

                                // Obtenemos la plantilla para asunto y contenido
                                $template = Template::get(2);
                                
                                // Sustituimos los datos
                                $subject = str_replace('%PROJECTNAME%', $project->name, $template->title);

                                $search  = array('%MESSAGE%', '%PROJECTNAME%', '%PROJECTURL%');
                                $replace = array($msg_content, $project->name, SITE_URL."/project/".$project->id);
                                $content = \str_replace($search, $replace, $template->text);

                                foreach ($who as $userId) {
                                    $errors = array();
                                    //me cojo su email y lo meto en un array para enviar solo con una instancia de Mail
                                    $data = Model\User::getMini($userId);

                                    // iniciamos el objeto mail
                                    $mailHandler = new Mail();
                                    $mailHandler->to = $data->email;
                                    $mailHandler->toName = $data->name;
                                    // blind copy a goteo desactivado durante las verificaciones
//                                    $mailHandler->bcc = 'comunicaciones@goteo.org';
                                    $mailHandler->subject = $subject;
                                    $mailHandler->content = str_replace('%NAME%', $data->name, $content);
                                    // esto tambien es pruebas
                                    $mailHandler->html = true;
                                    $mailHandler->template = $template->id;
                                    if ($mailHandler->send($errors)) {
                                        Message::Info(Text::get('dashboard-investors-mail-sended', $data->name, $data->email));
                                    } else {
                                        Message::Error(Text::get('dashboard-investors-mail-fail', $data->name, $data->email) . ' : '. implode (', ', $errors));

                                    }

                                    unset($mailHandler);
                                }
                                

                            break;
                        }
                        // fin segun action
                    break;

                    // contrato
                    case 'contract':
                        if ($action == 'save') {
                            $accounts = Model\Project\Account::get($project->id);
                            $accounts->bank = $_POST['bank'];
                            $accounts->paypal = $_POST['paypal'];
                            if ($accounts->save($errors)) {

                                $success[] = 'Cuentas actualizadas';

                                /*
                                 * Evento Feed
                                 */
                                $log = new Feed();
                                $log->title = 'usuario cambia las cuentas de su proyecto (dashboard)';
                                $log->url = '/admin/projects';
                                $log->type = 'user';
                                $log_text = '%s ha modificado la cuenta bancaria/paypal del proyecto %s';
                                $log_items = array(
                                    Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                    Feed::item('project', $project->name, $project->id)
                                );
                                $log->html = \vsprintf($log_text, $log_items);
                                $log->add($errors);

                                unset($log);
                            }
                        }
                        break;

                    // colaboraciones
                    case 'supports':
                        if ($action == 'save') {
                            // tratar colaboraciones existentes
                            foreach ($project->supports as $key => $support) {

                                // quitar las colaboraciones marcadas para quitar
                                if (!empty($_POST["support-{$support->id}-remove"])) {
                                    unset($project->supports[$key]);
                                    continue;
                                }

                                if (isset($_POST['support-' . $support->id . '-support'])) {
                                    $support->support = $_POST['support-' . $support->id . '-support'];
                                    $support->description = $_POST['support-' . $support->id . '-description'];
                                    $support->type = $_POST['support-' . $support->id . '-type'];

                                    if (!empty($support->thread)) {
                                        // actualizar ese mensaje
                                        $msg = Model\Message::get($support->thread);
                                        $msg->date = date('Y-m-d');
                                        $msg->message = "{$support->support}: {$support->description}";
                                        $msg->blocked = true;
                                        $msg->save();
                                    } else {
                                        // grabar nuevo mensaje
                                        $msg = new Model\Message(array(
                                            'user'    => $project->owner,
                                            'project' => $project->id,
                                            'date'    => date('Y-m-d'),
                                            'message' => "{$support->support}: {$support->description}",
                                            'blocked' => true
                                            ));
                                        if ($msg->save()) {
                                            // asignado a la colaboracion como thread inicial
                                            $support->thread = $msg->id;

                                            /*
                                             * Evento Feed
                                             */
                                            $log = new Feed();
                                            $log->title = 'usuario pone una nueva colaboracion en su proyecto (dashboard)';
                                            $log->url = '/admin/projects';
                                            $log->type = 'user';
                                            $log_text = '%s ha publicado una nueva %s en el proyecto %s, con el título "%s"';
                                            $log_items = array(
                                                Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                                Feed::item('message', 'Colaboración'),
                                                Feed::item('project', $project->name, $project->id),
                                                Feed::item('update', $support->support, $project->id.'/messages#message'.$msg->id)
                                            );
                                            $log->html = \vsprintf($log_text, $log_items);
                                            $log->add($errors);

                                            // evento público, si el proyecto es público
                                            if ($project->status > 2) {
                                                $log->title = $_SESSION['user']->name;
                                                $log->url = '/user/profile/'.$_SESSION['user']->id;
                                                $log->image = $_SESSION['user']->avatar->id;
                                                $log->scope = 'public';
                                                $log->type = 'community';
                                                $log->html = Text::html('feed-new_support',
                                                                Feed::item('project', $project->name, $project->id),
                                                                Feed::item('update', $support->support, $project->id.'/messages#message'.$msg->id)
                                                                );
                                                $log->add($errors);
                                            }
                                            unset($log);

                                        }
                                    }

                                }

                            }

                            // añadir nueva colaboracion (no hacemos lo del mensaje porque esta sin texto)
                            if (!empty($_POST['support-add'])) {

                                $new_support = new Model\Project\Support(array(
                                    'project'       => $project->id,
                                    'support'       => 'Nueva colaboración',
                                    'type'          => 'task',
                                    'description'   => ''
                                ));

                                if ($new_support->save($errors)) {

                                    $project->supports[] = $new_support;
                                    $_POST['support-'.$new_support->id.'-edit'] = true;

                                } else {
                                    $project->supports[] = new Model\Project\Support(array(
                                        'project'       => $project->id,
                                        'support'       => 'Nueva colaboración',
                                        'type'          => 'task',
                                        'description'   => ''
                                    ));
                                }
                            }

                            // guardamos los datos que hemos tratado y los errores de los datos
                            $project->save($errors);
                        }

                    break;

                    case 'updates':
                        if (empty($_POST['blog'])) {
                            break;
                        }

                        $editing = false;

                        if (!empty($_POST['id'])) {
                            $post = Model\Blog\Post::get($_POST['id']);
                        } else {
                            $post = new Model\Blog\Post();
                        }
                        // campos que actualizamos
                        $fields = array(
                            'id',
                            'blog',
                            'title',
                            'text',
                            'image',
                            'media',
                            'legend',
                            'date',
                            'publish',
                            'allow'
                        );

                        foreach ($fields as $field) {
                            $post->$field = $_POST[$field];
                        }

                        // tratar la imagen y ponerla en la propiedad image
                        if(!empty($_FILES['image_upload']['name'])) {
                            $post->image = $_FILES['image_upload'];
                            $editing = true;
                        }

                        // tratar las imagenes que quitan
                        foreach ($post->gallery as $key=>$image) {
                            if (!empty($_POST["gallery-{$image->id}-remove"])) {
                                $image->remove('post');
                                unset($post->gallery[$key]);
                                if ($post->image == $image->id) {
                                    $post->image = '';
                                }
                                $editing = true;
                            }
                        }

                        if (!empty($post->media)) {
                            $post->media = new Model\Project\Media($post->media);
                        }

                        // el blog de proyecto no tiene tags?¿?
                        // $post->tags = $_POST['tags'];

                        /// este es el único save que se lanza desde un metodo process_
                        if ($post->save($errors)) {
                            $id = $post->id;
                            if ($action == 'edit') {
                                $success[] = Text::get('dashboard-project-updates-saved');
                            } else {
                                $success[] = Text::get('dashboard-project-updates-inserted');
                            }
                            $action = $editing ? 'edit' : 'list';

                            // si ha marcado publish, grabamos evento de nueva novedad en proyecto
                            if ((bool) $post->publish) {
                                /*
                                 * Evento Feed
                                 */
                                $log = new Feed();
                                $log->title = 'usuario publica una novedad en su proyecto (dashboard)';
                                $log->url = '/admin/projects';
                                $log->type = 'user';
                                $log_text = '%s ha publicado un nuevo post en %s sobre el proyecto %s, con el título "%s"';
                                $log_items = array(
                                    Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                    Feed::item('blog', Text::get('project-menu-updates')),
                                    Feed::item('project', $project->name, $project->id),
                                    Feed::item('update', $post->title, $project->id.'/updates/'.$post->id)
                                );
                                $log->html = \vsprintf($log_text, $log_items);
                                $log->add($errors);

                                // evento público
                                $log->unique = true;
                                $log->title = $post->title;
                                $log->url = '/project/'.$project->id.'/updates/'.$post->id;
                                $log->image = $post->gallery[0]->id;
                                $log->scope = 'public';
                                $log->type = 'projects';
                                $log->html = Text::html('feed-new_update',
                                                Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                                Feed::item('blog', Text::get('project-menu-updates')),
                                                Feed::item('project', $project->name, $project->id)
                                                );
                                $log->add($errors);

                                unset($log);
                            }

                        } else {
                            $errors[] = Text::get('dashboard-project-updates-fail');
                        }
                        break;
                }
            }

            if ($option == 'updates') {
                // segun la accion
                switch ($action) {
                    case 'none' :
                        break;
                    case 'add':
                        $post = new Model\Blog\Post(
                                array(
                                    'blog' => $blog->id,
                                    'date' => date('Y-m-d'),
                                    'publish' => false,
                                    'allow' => true
                                )
                            );

                        break;
                    case 'edit':
                        if (empty($id)) {
                            $errors[] = Text::get('dashboard-project-updates-nopost');
                            $action = 'list';
                            break;
                        } else {
                            $post = Model\Blog\Post::get($id);

                            if (!$post instanceof Model\Blog\Post) {
                                $errors[] = Text::get('dashboard-project-updates-postcorrupt');
                                $action = 'list';
                                break;
                            }
                        }

                        break;
                    case 'delete':
                        $post = Model\Blog\Post::get($id);
                        if ($post->delete($id)) {
                            $success[] = Text::get('dashboard-project-updates-deleted');
                        } else {
                            $errors[] = Text::get('dashboard-project-updates-delete_fail');
                        }
                        $posts = Model\Blog\Post::getAll($blog->id, null, false);
                        $action = 'list';

                        break;
                    default:
                        $posts = Model\Blog\Post::getAll($blog->id, null, false);
                        $action = 'list';
                        break;
                }

            }



            // view data basico para esta seccion
            $viewData = array(
                    'menu'    => self::menu(),
                    'section' => __FUNCTION__,
                    'option'  => $option,
                    'action'  => $action,
                    'projects'=> $projects,
                    'errors'  => $errors,
                    'success' => $success
                );


            switch ($option) {
                // gestionar retornos
                case 'rewards':
                    // recompensas ofrecidas
                    $viewData['rewards'] = Model\Project\Reward::getAll($_SESSION['project']->id, 'individual', LANG);
                    // aportes para este proyecto
                    $viewData['invests'] = Model\Invest::getAll($_SESSION['project']->id);
                    // ver por (esto son orden y filtros)
                    $viewData['filter'] = $filter;
                    $viewData['order'] = $order;
                break;

                // editar colaboraciones
                case 'supports':
                    $viewData['types'] = Model\Project\Support::types();

                    if ($_POST) {
                        foreach ($_POST as $k => $v) {
                            if (!empty($v) && preg_match('/support-(\d+)-edit/', $k, $r)) {
                                $viewData[$k] = true;
                                break;
                            }
                        }
                    }

                    if (!empty($_POST['support-add'])) {
                        $last = end($project->supports);
                        if ($last !== false) {
                            $viewData['support-'.$last->id.'-edit'] = true;
                        }
                    }

                    $project->supports = Model\Project\Support::getAll($_SESSION['project']->id);
                break;

                // publicar actualizaciones
                case 'updates':
                    $viewData['blog'] = $blog;
                    $viewData['posts'] = $posts;
                    $viewData['post'] = $post;
                    break;
            }

            $viewData['project'] = $project;

            return new View ('view/dashboard/index.html.php', $viewData);
        }

        /*
         * Seccion, Mis traducciones
         * Opciones:
         *      'profile'  <-- ojo, con esto se traduce la informacion del usuario
         *      'overview'
         *      'costs'
         *      'rewards'
         *      'supports'
         *      'updates'
         *
         */
        public function translates ($option = 'overview', $action = 'list', $id = null) {

            $user    = $_SESSION['user'];

            $errors = array();

            $langs = \Goteo\Library\Lang::getAll();
            
            if ($action == 'select' && !empty($_POST['lang'])) {
                $_SESSION['translate_project_lang'] = $_POST['lang'];
            } elseif (empty($_SESSION['translate_project_lang'])) {
                $_SESSION['translate_project_lang'] = 'en';
            }

            $projects = Model\User\Translate::getMine($user->id, $_SESSION['translate_project_lang']);

            if ($action == 'select' && !empty($_POST['project'])) {
                // otro proyecto de trabajo
                $project = Model\Project::get($_POST['project'], $_SESSION['translate_project_lang']);
            } elseif (!empty($_SESSION['translate_project']->id)) {
                // si tenemos ya proyecto, mantener los datos actualizados
                $project = Model\Project::get($_SESSION['translate_project']->id, $_SESSION['translate_project_lang']);
            } elseif (!empty($projects)) {
                $project = $projects[0];
            }

            // aqui necesito tener un proyecto de trabajo,
            // si no hay ninguno ccoge el último
            if (!empty($project)) {
                $_SESSION['translate_project'] = $project;
                $project->lang_name = $langs[$project->lang]->name;
                unset($langs[$project->lang]);
            } else {
                $option = 'profile';
                unset($langs['es']);
            }


            if ($option == 'updates') {
                // sus novedades
                $blog = Model\Blog::get($project->id);
                if ($action != 'edit') {
                    $action = 'list';
                }
            }


            // tratar lo que llega por post
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                switch ($option) {
                    case 'profile':
                        if ($action == 'save') {
                            $user = Model\User::get($_POST['id'], $_SESSION['translate_project_lang']);
                            $user->about_lang = $_POST['about'];
                            $user->keywords_lang = $_POST['keywords'];
                            $user->contribution_lang = $_POST['contribution'];
                            $user->lang = $_SESSION['translate_project_lang'];
                            $user->saveLang($errors);
                        }
                    break;

                    case 'overview':
                        if ($action == 'save') {
                            $project->description_lang = $_POST['description'];
                            $project->motivation_lang = $_POST['motivation'];
                            $project->video_lang = $_POST['video'];
                            $project->about_lang = $_POST['about'];
                            $project->goal_lang = $_POST['goal'];
                            $project->related_lang = $_POST['related'];
                            $project->keywords_lang = $_POST['keywords'];
                            $project->media_lang = $_POST['media'];
                            $project->subtitle_lang = $_POST['subtitle'];
                            $project->lang_lang = $_SESSION['translate_project_lang'];
                            $project->saveLang($errors);
                        }
                    break;

                    case 'costs':
                        if ($action == 'save') {
                            foreach ($project->costs as $key => $cost) {
                                if (isset($_POST['cost-' . $cost->id . '-cost'])) {
                                    $cost->cost_lang = $_POST['cost-' . $cost->id . '-cost'];
                                    $cost->description_lang = $_POST['cost-' . $cost->id .'-description'];
                                    $cost->lang = $_SESSION['translate_project_lang'];
                                    $cost->saveLang($errors);
                                }
                            }
                        }
                    break;

                    case 'rewards':
                        if ($action == 'save') {
                            foreach ($project->social_rewards as $k => $reward) {
                                if (isset($_POST['social_reward-' . $reward->id . '-reward'])) {
                                    $reward->reward_lang = $_POST['social_reward-' . $reward->id . '-reward'];
                                    $reward->description_lang = $_POST['social_reward-' . $reward->id . '-description'];
                                    $reward->lang = $_SESSION['translate_project_lang'];
                                    $reward->saveLang($errors);
                                }
                            }
                            foreach ($project->individual_rewards as $k => $reward) {
                                if (isset($_POST['individual_reward-' . $reward->id .'-reward'])) {
                                    $reward->reward_lang = $_POST['individual_reward-' . $reward->id .'-reward'];
                                    $reward->description_lang = $_POST['individual_reward-' . $reward->id . '-description'];
                                    $reward->lang = $_SESSION['translate_project_lang'];
                                    $reward->saveLang($errors);
                                }

                            }
                        }
                    break;

                    case 'supports':
                        if ($action == 'save') {
                            // tratar colaboraciones existentes
                            foreach ($project->supports as $key => $support) {
                                if (isset($_POST['support-' . $support->id . '-support'])) {
                                    // guardamos los datos traducidos
                                    $support->support_lang = $_POST['support-' . $support->id . '-support'];
                                    $support->description_lang = $_POST['support-' . $support->id . '-description'];
                                    $support->lang = $_SESSION['translate_project_lang'];
                                    $support->saveLang($errors);
                                    
                                    // actualizar el Mensaje correspondiente, solamente actualizar
                                    $msg = Model\Message::get($support->thread);
                                    $msg->message_lang = "{$support->support_lang}: {$support->description_lang}";
                                    $msg->lang = $_SESSION['translate_project_lang'];
                                    $msg->saveLang($errors);
                                }
                            }
                        }
                    break;

                    case 'updates':
                        if (empty($_POST['blog']) || empty($_POST['id'])) {
                            break;
                        }

                        $post = Model\Blog\Post::get($_POST['id']);

                        $post->title_lang = $_POST['title'];
                        $post->text_lang = $_POST['text'];
                        $post->media_lang = $_POST['media'];
                        $post->legend_lang = $_POST['legend'];
                        $post->lang = $_SESSION['translate_project_lang'];
                        $post->saveLang($errors);

                        $action = 'edit';
                    break;
                }
            }

            // view data basico para esta seccion
            $viewData = array(
                    'menu'    => self::menu(),
                    'section' => __FUNCTION__,
                    'option'  => $option,
                    'action'  => $action,
                    'langs'=> $langs,
                    'projects'=> $projects,
                    'errors'  => $errors,
                    'success' => $success
                );


            switch ($option) {
                case 'profile':
                    if ($action == 'own') {
                        $viewData['user'] = Model\User::get($user->id, $_SESSION['translate_project_lang']);
                        $viewData['ownprofile'] = true;
                        break;
                    }

                    if ($project instanceof \Goteo\Model\Project) {
                        $viewData['user'] = Model\User::get($project->owner, $_SESSION['translate_project_lang']);
                    } else {
                        $viewData['user'] = Model\User::get($user->id, $_SESSION['translate_project_lang']);
                        $viewData['noowner'] = true;
                    }
                break;

                case 'overview':
                break;

                case 'costs':
                    if ($_POST) {
                        foreach ($_POST as $k => $v) {
                            if (!empty($v) && preg_match('/cost-(\d+)-edit/', $k, $r)) {
                                $viewData[$k] = true;
                            }
                        }
                    }
                break;

                case 'rewards':
                    if ($_POST) {
                        foreach ($_POST as $k => $v) {
                            if (!empty($v) && preg_match('/((social)|(individual))_reward-(\d+)-edit/', $k)) {
                                $viewData[$k] = true;
                                break;
                            }
                        }
                    }


                break;

                case 'supports':
                    if ($_POST) {
                        foreach ($_POST as $k => $v) {
                            if (!empty($v) && preg_match('/support-(\d+)-edit/', $k, $r)) {
                                $viewData[$k] = true;
                                break;
                            }
                        }
                    }
                break;

                // publicar actualizaciones
                case 'updates':

                    $viewData['blog'] = $blog;

                    if ($action == 'edit') {
                        $post = Model\Blog\Post::get($id, $_SESSION['translate_project_lang']);
                        $viewData['post'] = $post;
                    } else {
                        $posts = array();
                        foreach ($blog->posts as $post) {
                            $posts[] = Model\Blog\Post::get($post->id, $_SESSION['translate_project_lang']);
                        }
                        $viewData['posts'] = $posts;
                    }
                break;
            }

            $viewData['project'] = $project;

            return new View ('view/dashboard/index.html.php', $viewData);
        }

        /*
         * Salto al admin
         *
         */
        public function admin ($option = 'board') {
            if (ACL::check('/admin')) {
                throw new Redirection('/admin', Redirection::TEMPORARY);
            } else {
                throw new Redirection('/dashboard', Redirection::TEMPORARY);
            }
        }

        /*
         * Salto al panel de revisor
         *
         */
        public function review ($option = 'board') {
            if (ACL::check('/review')) {
                throw new Redirection('/review', Redirection::TEMPORARY);
            } else {
                throw new Redirection('/dashboard', Redirection::TEMPORARY);
            }
        }

        /*
         * Salto al panel de traductor
         *
         */
        public function translate ($option = 'board') {
            if (ACL::check('/translate')) {
                throw new Redirection('/translate', Redirection::TEMPORARY);
            } else {
                throw new Redirection('/dashboard', Redirection::TEMPORARY);
            }
        }

        private static function menu() {
            // todos los textos del menu dashboard
            $menu = array(
                'activity' => array(
                    'label'   => Text::get('dashboard-menu-activity'),
                    'options' => array (
                        'summary' => Text::get('dashboard-menu-activity-summary')
                    )
                ),
                'profile' => array(
                    'label'   => Text::get('dashboard-menu-profile'),
                    'options' => array (
                        'profile'  => Text::get('dashboard-menu-profile-profile'),
                        'personal' => Text::get('dashboard-menu-profile-personal'),
                        'access'   => Text::get('dashboard-menu-profile-access'),
                        'preferences' => Text::get('dashboard-menu-profile-preferences'),
                        'public'   => Text::get('dashboard-menu-profile-public')
                    )
                ),
                'projects' => array(
                    'label' => Text::get('dashboard-menu-projects'),
                    'options' => array (
                        'summary'  => Text::get('dashboard-menu-projects-summary'),
                        'updates'  => Text::get('dashboard-menu-projects-updates'),
                        'widgets'  => Text::get('dashboard-menu-projects-widgets'),
                        'contract' => Text::get('dashboard-menu-projects-contract'), 
                        'rewards'  => Text::get('dashboard-menu-projects-rewards'),
                        'supports' => Text::get('dashboard-menu-projects-supports')
                    )
                )
            );

            // si tiene algun proyecto para traducir
            $translates = Model\User\Translate::query("SELECT COUNT(project) FROM user_translate WHERE user = ?", array($_SESSION['user']->id));
            if ($translates->fetchColumn(0) > 0) {
                $menu['translates'] = array(
                    'label' => Text::get('dashboard-menu-translates'),
                    'options' => array (
                        'profile'  => Text::get('step-1'),
                        'overview' => Text::get('step-3'),
                        'costs'    => Text::get('step-4'),
                        'rewards'  => Text::get('step-5'),
                        'supports' => Text::get('step-6'),
                        'updates'  => Text::get('project-menu-updates')
                    )
                );
            } else {
                $menu['translates'] = array(
                    'label' => Text::get('dashboard-menu-translates'),
                    'options' => array (
                        'profile'  => Text::get('step-1')
                    )
                );
            }

            // si tiene permiso para ir al admin
            if (ACL::check('/admin')) {
                $menu['admin'] = array(
                    'label'   => Text::get('dashboard-menu-admin_board'),
                    'options' => array(
                        'board' => Text::get('dashboard-menu-admin_board')
                    )
                );
            }

            // si tiene permiso para ir a las revisiones
            if (ACL::check('/review')) {
                $menu['review'] = array(
                    'label'   => Text::get('dashboard-menu-review_board'),
                    'options' => array(
                        'board' => Text::get('dashboard-menu-review_board')
                    )
                );
            }

            // si tiene permiso para ir a las traducciones
            if (ACL::check('/translate')) {
                $menu['translate'] = array(
                    'label'   => Text::get('dashboard-menu-translate_board'),
                    'options' => array(
                        'board' => Text::get('dashboard-menu-translate_board')
                    )
                );
            }

            return $menu;

        }



        }

}