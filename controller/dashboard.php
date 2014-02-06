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
        Goteo\Core\Redirection,
        Goteo\Core\View,
        Goteo\Model,
        Goteo\Library\Message,
        Goteo\Library\Feed,
        Goteo\Library\Page,
        Goteo\Library\Text;

    class Dashboard extends \Goteo\Core\Controller {

        public function index() {
            throw new Redirection('/dashboard/activity');
        }

        /*
         * Sección, Mi actividad
         * Opciones:
         *      'summary' portada y proyectos del usuario 
         */
        public function activity($option = 'summary', $action = 'view') {

            // quitamos el stepped para que no nos lo coja para el siguiente proyecto que editemos
            if (isset($_SESSION['stepped'])) {
                unset($_SESSION['stepped']);
            }

            $user = $_SESSION['user'];
            
            $viewData = array(
                                'menu' => self::menu(),
                                'section' => __FUNCTION__,
                                'option' => $option,
                                'action' => $action
                            );
            
            // portada
            if ($option == 'summary') {
                $page = Page::get('dashboard');
                $viewData['message'] = \str_replace('%USER_NAME%', $_SESSION['user']->name, $page->content);
                $viewData['lists']   = Dashboard\Activity::projList($user);
                $viewData['status']  = Model\Project::status();
            }

            //@TODO: if ($option == 'wall') Dashboard\Activity::wall($user);


            // si es un salto a otro panel
            if (in_array($option, array('admin', 'review', 'translate'))) {
                if (ACL::check('/'.$option)) {
                    throw new Redirection('/'.$option, Redirection::TEMPORARY);
                } else {
                    throw new Redirection('/dashboard', Redirection::TEMPORARY);
                }
            }
            
            return new View('view/dashboard/index.html.php', $viewData);
        }

        /*
         * Seccion, Mi perfil
         * Opciones:
         *      'public' perfil público (paso 1),
         *      'personal' datos personales (paso 2),
         *      'access' configuracion (cambio de email y contraseña)
         *
         */

        public function profile($option = 'profile', $action = 'edit') {

            // tratamos el post segun la opcion y la acion
            $user = $_SESSION['user'];

            // salto al perfil público
            if ($option == 'public') throw new Redirection('/user/profile/' . $user->id);

            // vip/recomendador tiene una imagen adicional
            $vip = ($option == 'profile' && isset($user->roles['vip'])) ? Model\User\Vip::get($user->id) : null;

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $log_action = null;
                $errors = array();
                
                switch ($option) {
                    // perfil publico
                    case 'profile':
                        Dashboard\Profile::process_profile($user, $vip, $errors, $log_action);
                        break;

                    // datos personales
                    case 'personal':
                        Dashboard\Profile::process_personal($user->id, $errors, $log_action);
                        break;

                    //cambio de email y contraseña
                    case 'access':
                        Dashboard\Profile::process_access($user, $errors, $log_action);
                        break;

                    // preferencias de notificación
                    case 'preferences':
                        Dashboard\Profile::process_preferences($user->id, $errors, $log_action);
                        break;
                }

                if (!empty($log_action)) {
                    // Evento Feed
                    $log = new Feed();
                    $log->setTarget($user->id, 'user');
                    $log->populate('usuario ' . $log_action . ' (dashboard)', '/admin/users', \vsprintf('%s ha %s desde su dashboard', array(
                                Feed::item('user', $user->name, $user->id),
                                Feed::item('relevant', $log_action)
                            )));
                    $log->doAdmin('user');
                    unset($log);
                }
            }

            $viewData = array(
                'menu' => self::menu(),
                'section' => __FUNCTION__,
                'option' => $option,
                'action' => $action,
                'errors' => $errors,
                'user' => $user
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

                    if (isset($user->roles['vip'])) {
                        $viewData['vip'] = Model\User\Vip::get($user->id);
                    }

                    break;
                case 'personal':
                    $viewData['personal'] = Model\User::getPersonal($user->id);
                    break;
                case 'access':
                    // si es recover, en contraseña actual tendran que poner el username
                    if ($action == 'recover') {
                        $viewData['message'] = Text::get('dashboard-password-recover-advice');
                    }
                    break;
                case 'preferences':
                    $viewData['preferences'] = Model\User::getPreferences($user->id);
                    break;
            }


            return new View('view/dashboard/index.html.php', $viewData);
        }

        /*
         * Seccion, Mis proyectos
         * Opciones:
         *      'actualizaciones' blog del proyecto (ahora son como mensajes),
         *      'editar colaboraciones' para modificar los mensajes de colaboraciones (no puede editar el proyecto y ya estan publicados)
         *      'widgets' ofrece el código para poner su proyecto en otras páginas (vertical y horizontal)
         *      'licencia' el acuerdo entre goteo y el usuario, licencia cc-by-nc-nd, enlace al pdf
         *      'gestionar retornos' resumen recompensas/cofinanciadores/conseguido  y lista de cofinanciadores y recompensas esperadas
         *      'participantes' para comunicarse con los participantes en mensajes
         *      'pagina publica' enlace a la página pública del proyecto
         * 
         *      NEW: 'analytics' grafico de evolución de recaudación del proyecto
         *
         */
        public function projects($option = 'summary', $action = 'list', $id = null) {

            $user = $_SESSION['user'];

            $errors = array();

            // verificación de proyectos y proyecto de trabajo
            list($project, $projects) = Dashboard\Projects::verifyProject($user, $action);

            // teniendo proyecto de trabajo, comprobar si el proyecto esta en estado de tener blog
            if ($option == 'updates') $blog = Dashboard\Projects::verifyBlog($project);

            // sacaexcel de cofinanciadores
            if ($option == 'rewards' && $action == 'table') {
                $response = new \Goteo\Controller\Sacaexcel;
                return $response->index('investors', $project->id);
            }
            
            // ojo si no tiene retornos  
            if ($option == 'commons' && empty($project->social_rewards)) {
                Message::Error('Este proyecto no tiene retornos colectivos');
                throw new Redirection('/dashboard/projects/');
            }
            
            
            // procesamiento de formularios
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                switch ($option) {
                    // gestionar retornos (o mensaje a los mensajeros)
                    case 'messegers':
                    case 'rewards':
                        // sacamos del post el filtro y el orden
                        if ($action == 'filter') {
                            $_SESSION['dashboard-rewards-filter'] = (isset($_POST['filter'])) ? $_POST['filter'] : $_SESSION['dashboard-rewards-filter'];
                            $_SESSION['dashboard-rewards-order']  = (isset($_POST['order']))  ?  $_POST['order'] : $_SESSION['dashboard-rewards-order'];
                        }
                        
                        //procesamos el envio de mails
                        if ($action == 'message') {
                            Dashboard\Projects::process_mailing($option, $project);
                            // y lo devolvemos a donde estaba
                            throw new Redirection('/dashboard/projects/' . $option);
                        }
                        break;

                    // colaboraciones
                    case 'supports':
                        if ($action == 'save') $project = Dashboard\Projects::process_supports($project, $errors);
                        break;

                    case 'updates':
                        // verificación: si no llega blog correcto no lo procesamos
                        if (empty($_POST['blog']) || $_POST['blog'] != $blog->id) throw new Redirection('/dashboard/projects/summary');
                        
                        list($action, $id) = Dashboard\Projects::process_updates($action, $project, $errors);
                        break;
                }
            }

            // SubControlador para add, edit, delete y list  
            // devuelve $post en las acciones add y edit y $posts en delete y list
            // maneja por referencia $action, $posts y $errors
            if ($option == 'updates') {
                list($post, $posts) = Dashboard\Projects::prepare_updates($action, $id, $blog->id);
            }

            
            // view data basico para esta seccion
            $viewData = array(
                'menu' => self::menu(),
                'section' => __FUNCTION__,
                'option' => $option,
                'action' => $action,
                'projects' => $projects,
                'errors' => $errors
            );


            switch ($option) {
                case 'summary':
                    // los datos json de invests y visitors_data
                    $viewData['data'] = Dashboard\Projects::graph($project->id);
                    break;

                // gestionar recompensas
                case 'rewards':
                    // recompensas ofrecidas
                    $viewData['rewards'] = Model\Project\Reward::getAll($project->id, 'individual', LANG);
                    // aportes para este proyecto
                    $viewData['invests'] = Model\Invest::getAll($project->id);
                    // ver por (esto son orden y filtros)
                    $viewData['filter'] = $_SESSION['dashboard-rewards-filter'];
                    $viewData['order'] = $_SESSION['dashboard-rewards-order'];
                    break;

                // gestionar retornos
                case 'commons':
                    $icons = Model\Icon::getAll('social');
                    foreach ($icons as $key => $icon) {
                        $icons[$key] = $icon->name;
                    }
                    $viewData['icons'] = $icons;
                    break;

                // listar mensajeadores
                case 'messegers':
                    $viewData['messegers'] = Model\Message::getMessegers($project->id);
                    break;

                // editar colaboraciones
                case 'supports':
                    $viewData['types'] = Model\Project\Support::types();

                    // para mantener registros desplegados
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
                            $viewData['support-' . $last->id . '-edit'] = true;
                        }
                    }

                    $project->supports = Model\Project\Support::getAll($project->id);
                    break;

                // publicar actualizaciones
                case 'updates':
                    $viewData['blog'] = $blog;
                    $viewData['posts'] = $posts;
                    $viewData['post'] = $post;
                    break;

            }

            $viewData['project'] = $project;

            return new View('view/dashboard/index.html.php', $viewData);
        }
        // Fin de la sección Mis proyectos
        
        

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

        public function translates($option = 'overview', $action = 'list', $id = null) {

            $user = $_SESSION['user'];

            $errors = array();

            $langs = \Goteo\Library\i18n\Lang::getAll();

            if ($action == 'lang' && !empty($_POST['lang'])) {
                $_SESSION['translate_lang'] = $_POST['lang'];
            } elseif (empty($_SESSION['translate_lang'])) {
                $_SESSION['translate_lang'] = 'en';
            }

            $projects = Model\User\Translate::getMyProjects($user->id);

            // al seleccionar controlamos: translate_type
            if ($action == 'select' && !empty($_POST['type'])) {
                unset($_SESSION['translate_project']); // quitamos el proyecto de traducción

                $type = $_POST['type'];
                if (!empty($_POST[$type])) {
                    $_SESSION['translate_type'] = $type;
                    $_SESSION['translate_' . $type] = $_POST[$type];
                } else {
                    $_SESSION['translate_type'] = 'profile';
                }
            }

            // view data basico para esta seccion
            $viewData = array(
                'menu' => self::menu(),
                'section' => __FUNCTION__,
                'option' => $option,
                'action' => $action,
                'langs' => $langs,
                'projects' => $projects,
                'errors' => $errors,
                'success' => $success
            );

            // aqui, segun lo que este traduciendo, necesito tener un proyecto de trabajo, una convocatoria o mi perfil personal
            switch ($_SESSION['translate_type']) {
                case 'project':
                    try {
                        // si lo que tenemos en sesion no es una instancia de proyecto (es una id de proyecto)
                        if ($_SESSION['translate_project'] instanceof Model\Project) {
                            $project = Model\Project::get($_SESSION['translate_project']->id, $_SESSION['translate_lang']);
                        } else {
                            $project = Model\Project::get($_SESSION['translate_project'], $_SESSION['translate_lang']);
                        }
                    } catch (\Goteo\Core\Error $e) {
                        $project = null;
                    }

                    if (!$project instanceof Model\Project) {
                        Message::Error('Ha fallado al cargar los datos del proyecto');
                        $_SESSION['translate_type'] = 'profile';
                        throw new Redirection('/dashboard/translates');
                    }

                    $_SESSION['translate_project'] = $project;
                    $project->lang_name = $langs[$project->lang]->name;
                    unset($viewData['langs'][$project->lang]); // quitamos el idioma original
//// Control de traduccion de proyecto
                    if ($option == 'updates') {
                        // sus novedades
                        $blog = Model\Blog::get($project->id);
                        if ($action != 'edit') {
                            $action = 'list';
                        }
                    }

                    // tratar lo que llega por post para guardar los datos
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                        switch ($option) {
                            case 'profile':
                                if ($action == 'save') {
                                    $user = Model\User::get($_POST['id'], $_SESSION['translate_lang']);
                                    $user->about_lang = $_POST['about'];
                                    $user->keywords_lang = $_POST['keywords'];
                                    $user->contribution_lang = $_POST['contribution'];
                                    $user->lang = $_SESSION['translate_lang'];
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
                                    $project->reward_lang = $_POST['reward'];
                                    $project->keywords_lang = $_POST['keywords'];
                                    $project->media_lang = $_POST['media'];
                                    $project->subtitle_lang = $_POST['subtitle'];
                                    $project->lang_lang = $_SESSION['translate_lang'];
                                    $project->saveLang($errors);
                                }
                                break;

                            case 'costs':
                                if ($action == 'save') {
                                    foreach ($project->costs as $key => $cost) {
                                        if (isset($_POST['cost-' . $cost->id . '-cost'])) {
                                            $cost->cost_lang = $_POST['cost-' . $cost->id . '-cost'];
                                            $cost->description_lang = $_POST['cost-' . $cost->id . '-description'];
                                            $cost->lang = $_SESSION['translate_lang'];
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
                                            $reward->other_lang = $_POST['social_reward-' . $reward->id . '-other'];
                                            $reward->lang = $_SESSION['translate_lang'];
                                            $reward->saveLang($errors);
                                        }
                                    }
                                    foreach ($project->individual_rewards as $k => $reward) {
                                        if (isset($_POST['individual_reward-' . $reward->id . '-reward'])) {
                                            $reward->reward_lang = $_POST['individual_reward-' . $reward->id . '-reward'];
                                            $reward->description_lang = $_POST['individual_reward-' . $reward->id . '-description'];
                                            $reward->other_lang = $_POST['individual_reward-' . $reward->id . '-other'];
                                            $reward->lang = $_SESSION['translate_lang'];
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
                                            $support->lang = $_SESSION['translate_lang'];
                                            $support->saveLang($errors);

                                            // actualizar el Mensaje correspondiente, solamente actualizar
                                            $msg = Model\Message::get($support->thread);
                                            $msg->message_lang = "{$support->support_lang}: {$support->description_lang}";
                                            $msg->lang = $_SESSION['translate_lang'];
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
                                $post->lang = $_SESSION['translate_lang'];
                                $post->saveLang($errors);

                                $action = 'edit';
                                break;
                        }
                    }

                    switch ($option) {
                        case 'profile':
                            $viewData['user'] = Model\User::get($project->owner, $_SESSION['translate_lang']);
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
                                $post = Model\Blog\Post::get($id, $_SESSION['translate_lang']);
                                $viewData['post'] = $post;
                            } else {
                                $posts = array();
                                foreach ($blog->posts as $post) {
                                    $posts[] = Model\Blog\Post::get($post->id, $_SESSION['translate_lang']);
                                }
                                $viewData['posts'] = $posts;
                            }
                            break;
                    }

                    $viewData['project'] = $project;
//// FIN Control de traduccion de proyecto
                    break;

                default: // profile
                    $viewData['option'] = 'profile';
                    unset($langs['es']);

                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                        if ($action == 'save') {
                            $user = Model\User::get($_POST['id'], $_SESSION['translate_lang']);
                            $user->about_lang = $_POST['about'];
                            $user->keywords_lang = $_POST['keywords'];
                            $user->contribution_lang = $_POST['contribution'];
                            $user->lang = $_SESSION['translate_lang'];
                            $user->saveLang($errors);
                        }
                    }

                    $viewData['user'] = Model\User::get($user->id, $_SESSION['translate_lang']);
            }

            if (!empty($errors)) {
                Message::Error('HA HABIDO ERRORES: <br />' . implode('<br />', $errors));
            }

            return new View('view/dashboard/index.html.php', $viewData);
        }


        private static function menu() {
            // todos los textos del menu dashboard
            $menu = array(
                'activity' => array(
                    'label' => Text::get('dashboard-menu-activity'),
                    'options' => array(
                        'summary' => Text::get('dashboard-menu-activity-summary')
                    )
                ),
                'profile' => array(
                    'label' => Text::get('dashboard-menu-profile'),
                    'options' => array(
                        'profile' => Text::get('dashboard-menu-profile-profile'),
                        'personal' => Text::get('dashboard-menu-profile-personal'),
                        'access' => Text::get('dashboard-menu-profile-access'),
                        'preferences' => Text::get('dashboard-menu-profile-preferences'),
                        'public' => Text::get('dashboard-menu-profile-public')
                    )
                ),
                'projects' => array(
                    'label' => Text::get('dashboard-menu-projects'),
                    'options' => array(
                        'summary' => Text::get('dashboard-menu-projects-summary'),
                        'updates' => Text::get('dashboard-menu-projects-updates'),
                        'supports' => Text::get('dashboard-menu-projects-supports'),
                        'rewards' => Text::get('dashboard-menu-projects-rewards'),
                        'messegers' => Text::get('dashboard-menu-projects-messegers'),
                        'commons' => Text::get('dashboard-menu-projects-commons')
                    )
                )
            );

            // segun lo que este traduciendo
            if ($_SESSION['translate_type'] == 'project') {
                // si esta traduciendo un proyecto
                $menu['translates'] = array(
                    'label' => Text::get('dashboard-menu-translates'),
                    'options' => array(
                        'profile' => Text::get('step-1'),
                        'overview' => Text::get('step-3'),
                        'costs' => Text::get('step-4'),
                        'rewards' => Text::get('step-5'),
                        'supports' => Text::get('step-6'),
                        'updates' => Text::get('project-menu-updates')
                    )
                );
            } else {
                // si está traduciendo su perfil
                $menu['translates'] = array(
                    'label' => Text::get('dashboard-menu-translates'),
                    'options' => array(
                        'profile' => Text::get('step-1')
                    )
                );
            }

            // si tiene permiso para ir al admin
            if (ACL::check('/admin')) 
                $menu['activity']['options']['admin'] = Text::get('dashboard-menu-admin_board');

            // si tiene permiso para ir a las revisiones
            if (ACL::check('/review')) 
                $menu['activity']['options']['review'] = Text::get('dashboard-menu-review_board');

            // si tiene permiso para ir a las traducciones
            if (ACL::check('/translate')) 
                $menu['activity']['options']['translate'] = Text::get('dashboard-menu-translate_board');
            
            return $menu;
        }

    }

}