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
        Goteo\Core\View,
        Goteo\Core\Redirection,
        Goteo\Model,
	    Goteo\Library\Text,
		Goteo\Library\Feed,
		Goteo\Library\Lang,
        Goteo\Library\Paypal,
        Goteo\Library\Tpv,
        Goteo\Library\Page,
        Goteo\Library\Mail,
        Goteo\Library\Template,
        Goteo\Library\Message,
        Goteo\Library\Worth;

	class Admin extends \Goteo\Core\Controller {

        public function index () {
            return new View('view/admin/index.html.php', array('menu'=>self::menu()));
        }

        public function select () {

            $_SESSION['translator_lang'] = isset($_POST['lang']) ? $_POST['lang'] : null;

            return new View('view/admin/index.html.php', array('menu'=>self::menu()));
        }

        public function feed () {

            $BC = self::menu(array(
                'section' => 'home',
                'option' => __FUNCTION__,
                'action' => 'list'
            ));

            define('ADMIN_BCPATH', $BC);

            return new View('view/admin/feed.html.php');
        }


        /*
         * Gestión de páginas institucionales
         */
		public function pages ($action = 'list', $id = null) {

            $BC = self::menu(array(
                'section' => 'contents',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id
            ));

            define('ADMIN_BCPATH', $BC);

            $errors = array();

            switch ($action) {
                case 'edit':
                    // si estamos editando una página
                    $page = Page::get($id);

                    // si llega post, vamos a guardar los cambios
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $page->name = $_POST['name'];
                        $page->description = $_POST['description'];
                        $page->content = $_POST['content'];
                        if ($page->save($errors)) {

                            /*
                             * Evento Feed
                             */
                            $log = new Feed();
                            $log->title = 'modificacion de página institucional (admin)';
                            $log->url = '/admin/pages';
                            $log->type = 'admin';
                            $log_text = "El admin %s ha %s la página institucional %s";
                            $log_items = array(
                                Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                Feed::item('relevant', 'Modificado'),
                                Feed::item('relevant', $page->name, $page->url)
                            );
                            $log->html = \vsprintf($log_text, $log_items);
                            $log->add($errors);
                            unset($log);

                            throw new Redirection("/admin/pages");
                        }
                    }


                    // sino, mostramos para editar
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'pages',
                            'file' => 'edit',
                            'page' => $page,
                            'errors'=>$errors
                        )
                     );
                    break;
                case 'list':
                    // si estamos en la lista de páginas
                    $pages = Page::getAll();

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'pages',
                            'file' => 'list',
                            'pages' => $pages
                        )
                    );
                    break;
            }

		}

		public function texts ($action = 'list', $id = null) {

            $BC = self::menu(array(
                'section' => 'contents',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id
            ));

            define('ADMIN_BCPATH', $BC);

            // no cache para textos
            define('GOTEO_ADMIN_NOCACHE', true);

            // comprobamos los filtros
            $filters = array();
            $fields = array('idfilter', 'group', 'text');
            foreach ($fields as $field) {
                if (isset($_GET[$field])) {
                    $filters[$field] = $_GET[$field];
                }
            }

            $filter = "?idfilter={$filters['idfilter']}&group={$filters['group']}&text={$filters['text']}";
            
            // valores de filtro
            $idfilters = Text::filters();
            $groups    = Text::groups();

            // metemos el todos
            \array_unshift($idfilters, 'Todos los textos');
            \array_unshift($groups, 'Todas las agrupaciones');

 //@fixme temporal hasta pasar las agrupaciones a tabal o arreglar en el list.html.php
            $data = Text::getAll($filters, 'original');
            foreach ($data as $key=>$item) {
                $data[$key]->group = $groups[$item->group];
            }

            switch ($action) {
                case 'list':
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'texts',
                            'file' => 'list',
                            'data' => $data,
                            'columns' => array(
                                'edit' => '',
                                'text' => Text::_('Texto'),
                                'group' => Text::_('Agrupación')
                            ),
                            'url' => '/admin/texts',
                            'filters' => array(
                                'idfilter' => array(
                                        'label'   => Text::_('Filtrar por tipo:'),
                                        'type'    => 'select',
                                        'options' => $idfilters,
                                        'value'   => $filters['idfilter']
                                    ),
                                'group' => array(
                                        'label'   => Text::_('Filtrar por agrupación:'),
                                        'type'    => 'select',
                                        'options' => $groups,
                                        'value'   => $filters['group']
                                    ),
                                'text' => array(
                                        'label'   => Text::_('Buscar texto:'),
                                        'type'    => 'input',
                                        'options' => null,
                                        'value'   => $filters['text']
                                    )
                            ),
                            'errors' => $errors
                        )
                    );

                    break;

                case 'edit':

                    // gestionar post
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {

                        $errors = array();

                        $id = $_POST['id'];
                        $text = $_POST['text'];

                        $data = array(
                            'id' => $id,
                            'text' => $_POST['text']
                        );

                        if (Text::update($data, $errors)) {
                            throw new Redirection("/admin/texts/$filter");
                        }
                    } else {
                        $text = Text::getPurpose($id);
                    }

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'texts',
                            'file' => 'edit',
                            'data' => (object) array (
                                'id' => $id,
                                'text' => $text
                            ),
                            'form' => array(
                                'action' => '/admin/texts/edit/'.$id.'/'.$filter,
                                'submit' => array(
                                    'name' => 'update',
                                    'label' => Text::_('Aplicar')
                                ),
                                'fields' => array (
                                    'idtext' => array(
                                        'label' => Text::_(''),
                                        'name' => 'id',
                                        'type' => 'hidden',
                                        'properties' => '',

                                    ),
                                    'newtext' => array(
                                        'label' => Text::_('Texto'),
                                        'name' => 'text',
                                        'type' => 'textarea',
                                        'properties' => 'cols="100" rows="6"',

                                    )
                                )

                            ),
                            'errors' => $errors
                        )
                    );

                    break;
                default:
                    throw new Redirection("/admin");
            }
		}

        /*
         * Gestión de plantillas para emails automáticos
         */
		public function templates ($action = 'list', $id = null) {

            $BC = self::menu(array(
                'section' => 'contents',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id
            ));

            define('ADMIN_BCPATH', $BC);

            $errors = array();

            switch ($action) {
                case 'edit':
                    // si estamos editando una plantilla
                    $template = Template::get($id);

                    // si llega post, vamos a guardar los cambios
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $template->title = $_POST['title'];
                        $template->text  = $_POST['text'];
                        if ($template->save($errors))
                            throw new Redirection("/admin/templates");
                    }


                    // sino, mostramos para editar
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'templates',
                            'file' => 'edit',
                            'template' => $template,
                            'errors'=>$errors
                        )
                     );
                    break;
                case 'list':
                    // si estamos en la lista de páginas
                    $templates = Template::getAll();

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'templates',
                            'file' => 'list',
                            'templates' => $templates
                        )
                    );
                    break;
            }

		}

        /*
         *  Lista de proyectos
         */
        public function projects($action = 'list', $id = null) {

            $log_text = null;

            $BC = self::menu(array(
                'section' => 'projects',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id
            ));

            define('ADMIN_BCPATH', $BC);

            $filters = array();
            $fields = array('status', 'category', 'owner', 'name', 'order');
            foreach ($fields as $field) {
                if (isset($_GET[$field])) {
                    $filters[$field] = $_GET[$field];
                }
            }

            if (!isset($filters['status'])) $filters['status'] = -1;

            $errors = array();


            if ($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['id'])) {

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
                    $values = array(':id' => $_POST['id']);

                    foreach ($fields as $field) {
                        if ($set != '') $set .= ", ";
                        $set .= "`$field` = :$field ";
                        if (empty($_POST[$field]) || $_POST[$field] == '0000-00-00')
                            $_POST[$field] = null;
                        
                        $values[":$field"] = $_POST[$field];
                    }

                    if ($set == '') {
                        break;
                    }

                    try {
                        $sql = "UPDATE project SET " . $set . " WHERE id = :id";
                        if (Model\Project::query($sql, $values)) {
                            $log_text = Text::_('El admin %s ha <span class="red">tocado las fechas</span> del proyecto %s');
                        } else {
                            $log_text = Text::_('Al admin %s le ha <span class="red">fallado al tocar las fechas</span> del proyecto %s');
                        }
                    } catch(\PDOException $e) {
                        $errors[] = _("No se ha guardado correctamente. ") . $e->getMessage();
                    }
                } elseif (isset($_POST['save-accounts'])) {

                    $accounts = Model\Project\Account::get($_POST['id']);
                    $accounts->bank = $_POST['bank'];
                    $accounts->paypal = $_POST['paypal'];
                    if ($accounts->save($errors)) {
                        $errors[] = Text::_('Se han actualizado las cuentas del proyecto ').$_POST['id'];
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
                        $log_text = Text::_('El admin %s ha pasado el proyecto %s al estado <span class="red">Revisión</span>');
                    } else {
                        $log_text = Text::_('Al admin %s le ha fallado al pasar el proyecto %s al estado <span class="red">Revisión</span>');
                    }
                    break;
                case 'publish':
                    // poner un proyecto en campaña
                    if ($project->publish($errors)) {
                        $log_text = Text::_('El admin %s ha pasado el proyecto %s al estado <span class="red">en Campaña</span>');
                    } else {
                        $log_text = Text::_('Al admin %s le ha fallado al pasar el proyecto %s al estado <span class="red">en Campaña</span>');
                    }
                    break;
                case 'cancel':
                    // descartar un proyecto por malo
                    if ($project->cancel($errors)) {
                        $log_text = Text::_('El admin %s ha pasado el proyecto %s al estado <span class="red">Descartado</span>');
                    } else {
                        $log_text = Text::_('Al admin %s le ha fallado al pasar el proyecto %s al estado <span class="red">Descartado</span>');
                    }
                    break;
                case 'enable':
                    // si no está en edición, recuperarlo
                    if ($project->enable($errors)) {
                        $log_text = Text::_('El admin %s ha pasado el proyecto %s al estado <span class="red">Edición</span>');
                    } else {
                        $log_text = Text::_('Al admin %s le ha fallado al pasar el proyecto %s al estado <span class="red">Edición</span>');
                    }
                    break;
                case 'complete':
                    // dar un proyecto por financiado manualmente
                    if ($project->succeed($errors)) {
                        $log_text = Text::_('El admin %s ha pasado el proyecto %s al estado <span class="red">Financiado</span>');
                    } else {
                        $log_text = Text::_('Al admin %s le ha fallado al pasar el proyecto %s al estado <span class="red">Financiado</span>');
                    }
                    break;
                case 'fulfill':
                    // marcar que el proyecto ha cumplido con los retornos colectivos
                    if ($project->satisfied($errors)) {
                        $log_text = Text::_('El admin %s ha pasado el proyecto %s al estado <span class="red">Retorno cumplido</span>');
                    } else {
                        $log_text = Text::_('Al admin %s le ha fallado al pasar el proyecto %s al estado <span class="red">Retorno cumplido</span>');
                    }
                    break;
            }

            if (isset($log_text)) {
                /*
                 * Evento Feed
                 */
                $log = new Feed();
                $log->title = Text::_('Cambio estado/fechas de un proyecto desde el admin');
                $log->url = '/admin/projects';
                $log->type = 'admin';
                $log_items = array(
                    Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                    Feed::item('project', $project->name, $project->id)
                );
                $log->html = \vsprintf($log_text, $log_items);
                $log->add($errors);

                Message::Info($log->html);

                if ($action == 'publish') {
                    // si es publicado, hay un evento público
                    $log->title = $project->name;
                    $log->url = '/project/'.$project->id;
                    $log->image = $project->gallery[0]->id;
                    $log->scope = 'public';
                    $log->type = 'projects';
                    $log->html = Text::html('feed-new_project');
                    $log->add($errors);
                }

                unset($log);

                throw new Redirection('/admin/projects/list');
            }

            if ($action == 'dates') {
                // cambiar fechas
                return new View(
                    'view/admin/index.html.php',
                    array(
                        'folder' => 'projects',
                        'file' => 'dates',
                        'project' => $project,
                        'filters' => $filters,
                        'errors' => $errors
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
                        'accounts' => $accounts,
                        'filters' => $filters,
                        'errors' => $errors
                    )
                );
            }


            $projects = Model\Project::getList($filters);
            $status = Model\Project::status();
            $categories = Model\Project\Category::getAll();
            $owners = Model\User::getOwners();
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
                    'owners' => $owners,
                    'orders' => $orders,
                    'errors' => $errors
                )
            );
        }

        /*
         *  Revision de proyectos
         */
        public function reviews($action = 'list', $id = null) {

            $BC = self::menu(array(
                'section' => 'projects',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id
            ));

            define('ADMIN_BCPATH', $BC);

            $filters = array();
            $fields = array('status', 'checker');
            foreach ($fields as $field) {
                if (isset($_GET[$field])) {
                    $filters[$field] = $_GET[$field];
                }
            }

            $filter = "?status={$filters['status']}&checker={$filters['checker']}";

            $success = array();
            $errors  = array();

            switch ($action) {
                case 'add':
                case 'edit':

                    // el get se hace con el id del proyecto
                    $review = Model\Review::get($id);

                    $project = Model\Project::getMini($review->project);

                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {

                        // instancia
                        $review->id         = $_POST['id'];
                        $review->project    = $_POST['project'];
                        $review->to_checker = $_POST['to_checker'];
                        $review->to_owner   = $_POST['to_owner'];

                        if ($review->save($errors)) {
                            switch ($action) {
                                case 'add':
                                    $success[] = Text::_('Revisión iniciada correctamente');

                                    /*
                                     * Evento Feed
                                     */
                                    $log = new Feed();
                                    $log->title = Text::_('valoración iniciada (admin)');
                                    $log->url = '/admin/reviews';
                                    $log->type = 'admin';
                                    $log_text = Text::_('El admin %s ha %s la valoración de %s');
                                    $log_items = array(
                                        Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                        Feed::item('relevant', 'Iniciado'),
                                        Feed::item('project', $project->name, $project->id)
                                    );
                                    $log->html = \vsprintf($log_text, $log_items);
                                    $log->add($errors);

                                    unset($log);

                                    break;
                                case 'edit':
                                    $success[] = Text::_('Datos editados correctamente');
                                    break;
                            }
                            
                            throw new Redirection('/admin/reviews/' . $filter);
                        }
                    }
                    
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'reviews',
                            'file'   => 'edit',
                            'action' => $action,
                            'review' => $review,
                            'project'=> $project,
                            'success'=> $success,
                            'errors' => $errors
                        )
                    );

                    break;
                case 'close':
                    // el get se hace con el id del proyecto
                    $review = Model\Review::getData($id);

                    // marcamos la revision como completamente cerrada
                    if (Model\Review::close($id, $errors)) {
                        $message = Text::_('La revisión se ha cerrado');

                        /*
                         * Evento Feed
                         */
                        $log = new Feed();
                        $log->title = Text::_('valoración finalizada (admin)');
                        $log->url = '/admin/reviews';
                        $log->type = 'admin';
                        $log_text = Text::_('El admin %s ha dado por %s la valoración de %s');
                        $log_items = array(
                            Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                            Feed::item('relevant', Text::_('Finalizada')),
                            Feed::item('project', $review->name, $review->project)
                        );
                        $log->html = \vsprintf($log_text, $log_items);
                        $log->add($errors);

                        unset($log);

                    }
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
                    }
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

                            /*
                             * Evento Feed
                             */
                            $log = new Feed();
                            $log->title = Text::_('asignar revision (admin)');
                            $log->url = '/admin/reviews';
                            $log->type = 'admin';
                            $log_text = Text::_('El admin %s ha %s a %s la revisión de %s');
                            $log_items = array(
                                Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                Feed::item('relevant', Text::_('Asignado')),
                                Feed::item('user', $userData->name, $userData->id),
                                Feed::item('project', $reviewData->name, $reviewData->project)
                            );
                            $log->html = \vsprintf($log_text, $log_items);
                            $log->add($errors);

                            unset($log);

                        }
                    }
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

                            /*
                             * Evento Feed
                             */
                            $log = new Feed();
                            $log->title = Text::_('asignar revision (admin)');
                            $log->url = '/admin/reviews';
                            $log->type = 'admin';
                            $log_text = Text::_('El admin %s ha %s a %s la revisión de %s');
                            $log_items = array(
                                Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                Feed::item('relevant', Text::_('Desasignado')),
                                Feed::item('user', $userData->name, $userData->id),
                                Feed::item('project', $reviewData->name, $reviewData->project)
                            );
                            $log->html = \vsprintf($log_text, $log_items);
                            $log->add($errors);

                            unset($log);

                        }
                    }
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

            $projects = Model\Review::getList($filters);
            $status = array(
                'open' => Text::_('Abiertas'),
                'closed' => Text::_('Cerradas')
            );
            $checkers = Model\User::getAll(array('role'=>'checker'));

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'reviews',
                    'file' => 'list',
                    'message' => $message,
                    'projects' => $projects,
                    'filters' => $filters,
                    'status' => $status,
                    'checkers' => $checkers,
                    'errors' => $errors
                )
            );
        }

        /*
         *  Traducciones de proyectos
         */
        public function translates($action = 'list', $id = null) {

            $BC = self::menu(array(
                'section' => 'projects',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id
            ));

            define('ADMIN_BCPATH', $BC);

            $filters = array();
            $fields = array('owner', 'translator');
            foreach ($fields as $field) {
                if (isset($_GET[$field])) {
                    $filters[$field] = $_GET[$field];
                }
            }

            $filter = "?owner={$filters['owner']}&translator={$filters['translator']}";

            $success = array();
            $errors  = array();

            switch ($action) {
                case 'add':
                    // proyectos que están más allá de edición y con traducción deshabilitada
                    $availables = Model\User\Translate::getAvailables();
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
                            'id' => $project->id,
                            'user' => $user
                        ));

                        switch ($action) {
                            case 'assign': // se la ponemos
                                $assignation->save($errors);
                                $what = Text::_('Asignado');
                                break;
                            case 'unassign': // se la quitamos
                                $assignation->remove($errors);
                                $what = Text::_('Desasignado');
                                break;
                        }

                        if (empty($errors)) {
                            /*
                             * Evento Feed
                             */
                            $log = new Feed();
                            $log->title = $what . Text::_(' traduccion (admin)');
                            $log->url = '/admin/reviews';
                            $log->type = 'admin';
                            $log_text = Text::_('El admin %s ha %s a %s la traducción del proyecto %s');
                            $log_items = array(
                                Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                Feed::item('relevant', $what),
                                Feed::item('user', $userData->name, $userData->id),
                                Feed::item('project', $project->name, $project->id)
                            );
                            $log->html = \vsprintf($log_text, $log_items);
                            $log->add($errors);

                            unset($log);

                        }

                        $action = 'edit';
                    }
                    // fin asignar o desasignar

                    // añadir o actualizar
                    // se guarda el idioma original y si la traducción está abierta o cerrada
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {

                        echo \trace($_POST);

                        // ponemos los datos que llegan
                        $sql = "UPDATE project SET lang = :lang, translate = 1 WHERE id = :id";
                        if (Model\Project::query($sql, array(':lang'=>$_POST['lang'], ':id'=>$id))) {
                            $success[] = ($action == 'add') ? Text::_('El proyecto ').$project->name.Text::_(' se ha habilitado para traducir') : Text::_('Datos de traducción actualizados');

                            if ($action == 'add') {
                                /*
                                 * Evento Feed
                                 */
                                $log = new Feed();
                                $log->title = Text::_('proyecto habilitado para traducirse (admin)');
                                $log->url = '/admin/translates';
                                $log->type = 'admin';
                                $log_text = Text::_('El admin %s ha %s la traducción del proyecto %s');
                                $log_items = array(
                                    Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                    Feed::item('relevant', Text::_('Habilitado')),
                                    Feed::item('project', $project->name, $project->id)
                                );
                                $log->html = \vsprintf($log_text, $log_items);
                                $log->add($errors);

                                unset($log);

                                $action = 'edit';
                            }
                        } else {
                            $errors[] = Text::_('Ha fallado al habilitar la traducción del proyecto ') . $project->name;
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
                            $success[] = Text::_('Se ha enviado un email a').'<strong>'.$project->user->name.'</strong>'.Text::_('a la dirección').'<strong>'.$project->user->email.'</strong>';
                        } else {
                            $errors[] = Text::_('Ha fallado informar a').' <strong>'.$project->user->name.'</strong> '.Text::_('de la posibilidad de traducción de su proyecto');
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
                            'filters' => $filters,
                            'availables' => $availables,
                            'translators' => $translators,
                            'project'=> $project,
                            'success' => $success,
                            'errors' => $errors
                        )
                    );

                    break;
                case 'close':
                    // la sentencia aqui mismo
                    // el campo translate del proyecto $id a false
                    $sql = "UPDATE project SET translate = 0 WHERE id = :id";
                    if (Model\Project::query($sql, array(':id'=>$id))) {
                        $success[] = Text::_('La traducción del proyecto ').$project->name.Text::_(' se ha finalizado');

                        Model\Project::query("DELETE FROM user_translate WHERE project = :id", array(':id'=>$id));

                        /*
                         * Evento Feed
                         */
                        $log = new Feed();
                        $log->title = Text::_('traducción finalizada (admin)');
                        $log->url = '/admin/translates';
                        $log->type = 'admin';
                        $log_text = Text::_('El admin %s ha dado por %s la traducción de %s');
                        $log_items = array(
                            Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                            Feed::item('relevant', 'Finalizada'),
                            Feed::item('project', $project->name, $project->id)
                        );
                        $log->html = \vsprintf($log_text, $log_items);
                        $log->add($errors);

                        unset($log);
                    } else {
                        $errors[] = Text::_('Falló al finalizar la traducción');
                    }
                    break;
            }

            $projects = Model\Project::getTranslates($filters);
            $owners = Model\User::getOwners();
            $translators = Model\User::getAll(array('role'=>'translator'));

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'translates',
                    'file' => 'list',
                    'projects' => $projects,
                    'filters' => $filters,
                    'owners' => $owners,
                    'translators' => $translators,
                    'success' => $success,
                    'errors' => $errors
                )
            );
        }

        /*
         * proyectos destacados
         */
        public function promote($action = 'list', $id = null, $flag = null) {

            $BC = self::menu(array(
                'section' => 'home',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id
            ));

            define('ADMIN_BCPATH', $BC);

            $errors = array();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                // objeto
                $promo = new Model\Promote(array(
                    'id' => $id,
                    'node' => \GOTEO_NODE,
                    'project' => $_POST['project'],
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'order' => $_POST['order'],
                    'active' => $_POST['active']
                ));

				if ($promo->save($errors)) {
                    switch ($_POST['action']) {
                        case 'add':
                            $success[] = Text::_('Proyecto destacado correctamente');

                            $projectData = Model\Project::getMini($_POST['project']);

                            /*
                             * Evento Feed
                             */
                            $log = new Feed();
                            $log->title = Text::_('nuevo proyecto destacado en portada (admin)');
                            $log->url = '/admin/promote';
                            $log->type = 'admin';
                            $log_text = Text::_('El admin %s ha %s el proyecto %s');
                            $log_items = array(
                                Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                Feed::item('relevant', Text::_('Destacado en portada'), '/'),
                                Feed::item('project', $projectData->name, $projectData->id)
                            );
                            $log->html = \vsprintf($log_text, $log_items);
                            $log->add($errors);

                            unset($log);

                            break;
                        case 'edit':
                            $success[] = Text::_('Destacado actualizado correctamente');
                            break;
                    }
				}
				else {
                    switch ($_POST['action']) {
                        case 'add':
                            return new View(
                                'view/admin/index.html.php',
                                array(
                                    'folder' => 'promote',
                                    'file' => 'edit',
                                    'action' => 'add',
                                    'promo' => $promo,
                                    'status' => $status,
                                    'errors' => $errors
                                )
                            );
                            break;
                        case 'edit':
                            return new View(
                                'view/admin/index.html.php',
                                array(
                                    'folder' => 'promote',
                                    'file' => 'edit',
                                    'action' => 'edit',
                                    'promo' => $promo,
                                    'errors' => $errors
                                )
                            );
                            break;
                    }
				}
			}

            switch ($action) {
                case 'active':
                    $set = $flag == 'on' ? true : false;
                    Model\Promote::setActive($id, $set);
                    /*
                    {
                        $res = ($set) ? 'publicado' : 'oculto';
                        $success[] = "El proyecto ahora está " . $res;
                    } else {
                        $res = ($set) ? 'publicar' : 'ocultar';
                        $errors[] = "Falló al " . $res . " el proyecto";
                    }
                     *
                     */
                    break;
                case 'up':
                    Model\Promote::up($id);
                    break;
                case 'down':
                    Model\Promote::down($id);
                    break;
                case 'remove':
                    if (Model\Promote::delete($id)) {
                        $projectData = Model\Project::getMini($id);

                        /*
                         * Evento Feed
                         */
                        $log = new Feed();
                        $log->title = Text::_('proyecto quitado portada (admin)');
                        $log->url = '/admin/promote';
                        $log->type = 'admin';
                        $log_text = Text::_('El admin %s ha %s el proyecto %s');
                        $log_items = array(
                            Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                            Feed::item('relevant', Text::_('Quitado de la portada')),
                            Feed::item('project', $projectData->name, $projectData->id)
                        );
                        $log->html = \vsprintf($log_text, $log_items);
                        $log->add($errors);

                        unset($log);

                        $success[] = Text::_('Proyecto quitado correctamente');
                    }
                    break;
                case 'add':
                    // siguiente orden
                    $next = Model\Promote::next();

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'promote',
                            'file' => 'edit',
                            'action' => 'add',
                            'promo' => (object) array('order' => $next),
                            'status' => $status
                        )
                    );
                    break;
                case 'edit':
                    $promo = Model\Promote::get($id);

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'promote',
                            'file' => 'edit',
                            'action' => 'edit',
                            'promo' => $promo
                        )
                    );
                    break;
            }


            $promoted = Model\Promote::getAll();

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'promote',
                    'file' => 'list',
                    'promoted' => $promoted,
                    'errors' => $errors,
                    'success' => $success
                )
            );
        }

        /*
         * Banners
         */
        public function banners($action = 'list', $id = null) {

            $BC = self::menu(array(
                'section' => 'home',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id
            ));

            define('ADMIN_BCPATH', $BC);

            $errors = array();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                // objeto
                $banner = new Model\Banner(array(
                    'node' => \GOTEO_NODE,
                    'project' => $_POST['project'],
                    'order' => $_POST['order']
                ));

                // imagen
                if(!empty($_FILES['image']['name'])) {
                    $banner->image = $_FILES['image'];
                } else {
                    $banner->image = $_POST['prev_image'];
                }

				if ($banner->save($errors)) {
                    $success[] = Text::_('Datos guardados');

                    if ($_POST['action'] == 'add') {
                        $projectData = Model\Project::getMini($_POST['project']);

                        /*
                         * Evento Feed
                         */
                        $log = new Feed();
                        $log->title = Text::_('nuevo banner de proyecto destacado en portada (admin)');
                        $log->url = '/admin/promote';
                        $log->type = 'admin';
                        $log_text = Text::_('El admin %s ha %s del proyecto %s');
                        $log_items = array(
                            Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                            Feed::item('relevant', Text::_('Publicado un banner'), '/'),
                            Feed::item('project', $projectData->name, $projectData->id)
                        );
                        $log->html = \vsprintf($log_text, $log_items);
                        $log->add($errors);

                        unset($log);
                    }

				}
				else {
                    switch ($_POST['action']) {
                        case 'add':
                            return new View(
                                'view/admin/index.html.php',
                                array(
                                    'folder' => 'banners',
                                    'file' => 'edit',
                                    'action' => 'add',
                                    'banner' => $banner,
                                    'status' => $status,
                                    'errors' => $errors
                                )
                            );
                            break;
                        case 'edit':
                            return new View(
                                'view/admin/index.html.php',
                                array(
                                    'folder' => 'banners',
                                    'file' => 'edit',
                                    'action' => 'edit',
                                    'banenr' => $banner,
                                    'errors' => $errors
                                )
                            );
                            break;
                    }
				}
			}

            switch ($action) {
                case 'up':
                    Model\Banner::up($id);
                    break;
                case 'down':
                    Model\Banner::down($id);
                    break;
                case 'remove':
                    if (Model\Banner::delete($id)) {
                        $projectData = Model\Project::getMini($id);

                        /*
                         * Evento Feed
                         */
                        $log = new Feed();
                        $log->title = Text::_('banner de proyecto quitado portada (admin)');
                        $log->url = '/admin/promote';
                        $log->type = 'admin';
                        $log_text = Text::_('El admin %s ha %s del proyecto %s');
                        $log_items = array(
                            Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                            Feed::item('relevant', Text::_('Quitado el banner'), '/'),
                            Feed::item('project', $projectData->name, $projectData->id)
                        );
                        $log->html = \vsprintf($log_text, $log_items);
                        $log->add($errors);

                        unset($log);

                    }
                    break;
                case 'add':
                    // siguiente orden
                    $next = Model\Banner::next();

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'banners',
                            'file' => 'edit',
                            'action' => 'add',
                            'banner' => (object) array('order' => $next),
                            'status' => $status
                        )
                    );
                    break;
                case 'edit':
                    $banner = Model\Banner::get($id);

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'banners',
                            'file' => 'edit',
                            'action' => 'edit',
                            'banner' => $banner
                        )
                    );
                    break;
            }


            $bannered = Model\Banner::getAll();

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'banners',
                    'file' => 'list',
                    'bannered' => $bannered,
                    'errors' => $errors,
                    'success' => $success
                )
            );
        }

        /*
         * preguntas frecuentes
         */
        public function faq($action = 'list', $id = null) {

            // secciones
            $sections = Model\Faq::sections();
            if (isset($_GET['filter']) && array_key_exists($_GET['filter'], $sections)) {
                $filter = $_GET['filter'];
            } else {
                $filter = 'node';
            }

            $BC = self::menu(array(
                'section' => 'contents',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id,
                'filter' => '?filter=' . $filter
            ));

            define('ADMIN_BCPATH', $BC);

            $errors = array();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                // instancia
                $faq = new Model\Faq(array(
                    'id' => $_POST['id'],
                    'node' => \GOTEO_NODE,
                    'section' => $_POST['section'],
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'order' => $_POST['order'],
                    'move' => $_POST['move']
                ));

				if ($faq->save($errors)) {
                    switch ($_POST['action']) {
                        case 'add':
                            $success = Text::_('Pregunta añadida correctamente');
                            break;
                        case 'edit':
                            $success = Text::_('Pregunta editado correctamente');
                            break;
                    }
				}
				else {
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'faq',
                            'file' => 'edit',
                            'action' => $_POST['action'],
                            'faq' => $faq,
                            'filter' => $filter,
                            'sections' => $sections,
                            'errors' => $errors
                        )
                    );
				}
			}


            switch ($action) {
                case 'up':
                    Model\Faq::up($id);
                    break;
                case 'down':
                    Model\Faq::down($id);
                    break;
                case 'add':
                    $next = Model\Faq::next($filter);

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'faq',
                            'file' => 'edit',
                            'action' => 'add',
                            'faq' => (object) array('section' => $filter, 'order' => $next, 'cuantos' => $next),
                            'filter' => $filter,
                            'sections' => $sections
                        )
                    );
                    break;
                case 'edit':
                    $faq = Model\Faq::get($id);

                    $cuantos = Model\Faq::next($faq->section);
                    $faq->cuantos = ($cuantos -1);

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'faq',
                            'file' => 'edit',
                            'action' => 'edit',
                            'faq' => $faq,
                            'filter' => $filter,
                            'sections' => $sections
                        )
                    );
                    break;
                case 'remove':
                    Model\Faq::delete($id);
                    break;
            }

            $faqs = Model\Faq::getAll($filter);

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'faq',
                    'file' => 'list',
                    'faqs' => $faqs,
                    'sections' => $sections,
                    'filter' => $filter,
                    'errors' => $errors,
                    'success' => $success
                )
            );
        }

        /*
         * criterios de puntuación Goteo
         */
        public function criteria($action = 'list', $id = null) {

            // secciones
            $sections = Model\Criteria::sections();
            if (isset($_GET['filter']) && array_key_exists($_GET['filter'], $sections)) {
                $filter = $_GET['filter'];
            } else {
                $filter = 'project';
            }

            $BC = self::menu(array(
                'section' => 'contents',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id,
                'filter' => '?filter=' . $filter
            ));

            define('ADMIN_BCPATH', $BC);

            $errors = array();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                // instancia
                $criteria = new Model\Criteria(array(
                    'id' => $_POST['id'],
                    'section' => $_POST['section'],
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'order' => $_POST['order'],
                    'move' => $_POST['move']
                ));

				if ($criteria->save($errors)) {
                    switch ($_POST['action']) {
                        case 'add':
                            $success = Text::_('Criterio añadido correctamente');
                            break;
                        case 'edit':
                            $success = Text::_('Criterio editado correctamente');
                            break;
                    }
				}
				else {
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'criteria',
                            'file' => 'edit',
                            'action' => $_POST['action'],
                            'criteria' => $criteria,
                            'filter' => $filter,
                            'sections' => $sections,
                            'errors' => $errors
                        )
                    );
				}
			}


            switch ($action) {
                case 'up':
                    Model\Criteria::up($id);
                    break;
                case 'down':
                    Model\Criteria::down($id);
                    break;
                case 'add':
                    $next = Model\Criteria::next($filter);

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'criteria',
                            'file' => 'edit',
                            'action' => 'add',
                            'criteria' => (object) array('section' => $filter, 'order' => $next, 'cuantos' => $next),
                            'filter' => $filter,
                            'sections' => $sections
                        )
                    );
                    break;
                case 'edit':
                    $criteria = Model\Criteria::get($id);

                    $cuantos = Model\Criteria::next($criteria->section);
                    $criteria->cuantos = ($cuantos -1);

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'criteria',
                            'file' => 'edit',
                            'action' => 'edit',
                            'criteria' => $criteria,
                            'filter' => $filter,
                            'sections' => $sections
                        )
                    );
                    break;
                case 'remove':
                    Model\Criteria::delete($id);
                    break;
            }

            $criterias = Model\Criteria::getAll($filter);

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'criteria',
                    'file' => 'list',
                    'criterias' => $criterias,
                    'sections' => $sections,
                    'filter' => $filter,
                    'errors' => $errors,
                    'success' => $success
                )
            );
        }

        /*
         * Tipos de Retorno/Recompensa (iconos)
         */
        public function icons($action = 'list', $id = null) {

            // grupos
            $groups = Model\Icon::groups();
            if (isset($_GET['filter']) && array_key_exists($_GET['filter'], $groups)) {
                $filter = $_GET['filter'];
            } else {
                $filter = '';
            }

            $BC = self::menu(array(
                'section' => 'contents',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id,
                'filter' => !empty($filter) ? '?filter=' . $filter : ''
            ));

            define('ADMIN_BCPATH', $BC);

            $errors = array();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                // instancia
                $icon = new Model\Icon(array(
                    'id' => $_POST['id'],
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'order' => $_POST['order'],
                    'group' => empty($_POST['group']) ? null : $_POST['group']
                ));

				if ($icon->save($errors)) {
                    switch ($_POST['action']) {
                        case 'add':
                            $success = Text::_('Nuevo tipo añadido correctamente');
                            break;
                        case 'edit':
                            $success = Text::_('Tipo editado correctamente');

                            /*
                             * Evento Feed
                             */
                            $log = new Feed();
                            $log->title = Text::_('modificacion de tipo de retorno/recompensa (admin)');
                            $log->url = '/admin/icons';
                            $log->type = 'admin';
                            $log_text = _("El admin %s ha %s el tipo de retorno/recompensa %s");
                            $log_items = array(
                                Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                Feed::item('relevant', 'Modificado'),
                                Feed::item('project', $icon->name)
                            );
                            $log->html = \vsprintf($log_text, $log_items);
                            $log->add($errors);
                            unset($log);

                            break;
                    }
				}
				else {
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'icons',
                            'file' => 'edit',
                            'action' => $_POST['action'],
                            'icon' => $icon,
                            'filter' => $filter,
                            'groups' => $groups,
                            'errors' => $errors
                        )
                    );
				}
			}

            switch ($action) {
                case 'add':
/*
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'icons',
                            'file' => 'edit',
                            'action' => 'add',
                            'icon' => (object) array('group' => ''),
                            'groups' => $groups
                        )
                    );
 *
 */
                    break;
                case 'edit':
                    $icon = Model\Icon::get($id);

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'icons',
                            'file' => 'edit',
                            'action' => 'edit',
                            'icon' => $icon,
                            'filter' => $filter,
                            'groups' => $groups
                        )
                    );
                    break;
                case 'remove':
    //                Model\Icon::delete($id);
                    break;
            }

            $icons = Model\Icon::getAll($filter);

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'icons',
                    'file' => 'list',
                    'icons' => $icons,
                    'groups' => $groups,
                    'filter' => $filter,
                    'errors' => $errors,
                    'success' => $success
                )
            );
        }

        /*
         * Licencias
         */
        public function licenses($action = 'list', $id = null) {

            if (isset($_GET['filters'])) {
                foreach (\unserialize($_GET['filters']) as $field=>$value) {
                    $filters[$field] = $value;
                }
            } else {
                $filters = array();
            }

            $fields = array('group', 'icon');
            foreach ($fields as $field) {
                if (isset($_GET[$field])) {
                    $filters[$field] = $_GET[$field];
                }
            }

            $BC = self::menu(array(
                'section' => 'contents',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id,
                'filter' => !empty($filters) ? '?filter=' . serialize($filters) : ''
            ));

            define('ADMIN_BCPATH', $BC);

            // agrupaciones de mas a menos abertas
            $groups = Model\License::groups();

            // tipos de retorno para asociar
            $icons = Model\Icon::getAll('social');


            $errors = array();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                // objeto
                $license = new Model\License(array(
                    'id' => $_POST['id'],
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'url' => $_POST['url'],
                    'group' => $_POST['group'],
                    'order' => $_POST['order'],
                    'icons' => $_POST['icons']
                ));

				if ($license->save($errors)) {
                    switch ($_POST['action']) {
                        case 'add':
                            $success = Text::_('Licencia añadida correctamente');
                            break;
                        case 'edit':
                            $success = Text::_('Licencia editada correctamente');

                            /*
                             * Evento Feed
                             */
                            $log = new Feed();
                            $log->title = Text::_('modificacion de licencia (admin)');
                            $log->url = '/admin/licenses';
                            $log->type = 'admin';
                            $log_text = _("El admin %s ha %s la licencia %s");
                            $log_items = array(
                                Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                Feed::item('relevant', 'Modificado'),
                                Feed::item('project', $license->name)
                            );
                            $log->html = \vsprintf($log_text, $log_items);
                            $log->add($errors);
                            unset($log);

                            break;
                    }
				}
				else {
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'licenses',
                            'file' => 'edit',
                            'action'  => $_POST['action'],
                            'license' => $license,
                            'filters' => $filters,
                            'icons'   => $icons,
                            'groups'  => $groups,
                            'errors'  => $errors
                        )
                    );
				}
			}

            switch ($action) {
                case 'up':
                    Model\License::up($id);
                    break;
                case 'down':
                    Model\License::down($id);
                    break;
                case 'add':
                    $next = Model\License::next();

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'licenses',
                            'file' => 'edit',
                            'action' => 'add',
                            'license' => (object) array('order' => $next, 'icons' => array()),
                            'icons' => $icons,
                            'groups' => $groups
                        )
                    );
                    break;
                case 'edit':
                    $license = Model\License::get($id);

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'licenses',
                            'file' => 'edit',
                            'action' => 'edit',
                            'license' => $license,
                            'filters' => $filters,
                            'icons' => $icons,
                            'groups' => $groups
                        )
                    );
                    break;
                case 'remove':
    //                Model\License::delete($id);
                    break;
            }

            $licenses = Model\License::getAll($filters['icon'], $filters['group']);

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'licenses',
                    'file' => 'list',
                    'licenses' => $licenses,
                    'filters'  => $filters,
                    'groups' => $groups,
                    'icons'    => $icons,
                    'errors' => $errors,
                    'success' => $success
                )
            );
        }

        /*
         * posts para portada
         */
        public function posts($action = 'list', $id = null) {

            $BC = self::menu(array(
                'section' => 'home',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id
            ));

            define('ADMIN_BCPATH', $BC);

            $errors = array();

            if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'add') {

                // esto es para añadir una entrada en la portada
                

                // objeto
                $post = new Model\Post(array(
                    'id' => $_POST['post'],
                    'order' => $_POST['order'],
                    'home' => $_POST['home']
                ));

				if ($post->update($errors)) {
                    $success[] = Text::_('Entrada colocada en la portada correctamente');
				}
				else {
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'posts',
                            'file' => 'add',
                            'action' => 'add',
                            'post' => $post,
                            'errors' => $errors
                        )
                    );
				}
			}


            switch ($action) {
                case 'up':
                    Model\Post::up($id, 'home');
                    break;
                case 'down':
                    Model\Post::down($id, 'home');
                    break;
                case 'add':
                    // siguiente orden
                    $next = Model\Post::next('home');

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'posts',
                            'file' => 'add',
                            'action' => 'add',
                            'post' => (object) array('order' => $next)
                        )
                    );
                    break;
                case 'edit':
                    throw new Redirection('/admin/blog');
                    break;
                case 'remove':
                    // se quita de la portada solamente
                    Model\Post::remove($id, 'home');
                    break;
            }

            $posts = Model\Post::getAll('home');

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'posts',
                    'file' => 'list',
                    'posts' => $posts,
                    'errors' => $errors,
                    'success' => $success
                )
            );
        }

        /*
         * posts para pie
         */
        public function footer($action = 'list', $id = null) {

            $BC = self::menu(array(
                'section' => 'home',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id
            ));

            define('ADMIN_BCPATH', $BC);

            $errors = array();

            if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'add') {

                // objeto
                $post = new Model\Post(array(
                    'id' => $_POST['post'],
                    'order' => $_POST['order'],
                    'footer' => $_POST['footer']
                ));

				if ($post->update($errors)) {
                    $success[] = Text::_('Entrada colocada en el footer correctamente');
				}
				else {
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'footer',
                            'file' => 'add',
                            'action' => 'add',
                            'post' => $post,
                            'errors' => $errors
                        )
                    );
				}
			}


            switch ($action) {
                case 'up':
                    Model\Post::up($id, 'footer');
                    break;
                case 'down':
                    Model\Post::down($id, 'footer');
                    break;
                case 'add':
                    // siguiente orden
                    $next = Model\Post::next('footer');

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'footer',
                            'file' => 'add',
                            'action' => 'add',
                            'post' => (object) array('order' => $next)
                        )
                    );
                    break;
                case 'edit':
                    throw new Redirection('/admin/blog');
                    break;
                case 'remove':
                    Model\Post::remove($id, 'footer');
                    break;
            }

            $posts = Model\Post::getAll('footer');

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'footer',
                    'file' => 'list',
                    'posts' => $posts,
                    'errors' => $errors,
                    'success' => $success
                )
            );
        }

        /*
         *  Gestión de categorias de proyectos
         *  Si no la usa nadie se puede borrar
         */
        public function categories($action = 'list', $id = null) {

            $BC = self::menu(array(
                'section' => 'contents',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id
            ));

            define('ADMIN_BCPATH', $BC);

            $model = 'Goteo\Model\Category';
            $url = '/admin/categories';

            $errors = array();

            switch ($action) {
                case 'add':
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'base',
                            'file' => 'edit',
                            'data' => (object) array(),
                            'form' => array(
                                'action' => "$url/edit/",
                                'submit' => array(
                                    'name' => 'update',
                                    'label' => Text::_('Añadir')
                                ),
                                'fields' => array (
                                    'id' => array(
                                        'label' => Text::_(''),
                                        'name' => 'id',
                                        'type' => 'hidden'

                                    ),
                                    'name' => array(
                                        'label' => Text::_('Categoría'),
                                        'name' => 'name',
                                        'type' => 'text'
                                    ),
                                    'description' => array(
                                        'label' => Text::_('Descripción'),
                                        'name' => 'description',
                                        'type' => 'textarea',
                                        'properties' => 'cols="100" rows="2"',

                                    )
                                )

                            )
                        )
                    );

                    break;
                case 'edit':

                    // gestionar post
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {

                        $errors = array();

                        // instancia
                        $item = new $model(array(
                            'id' => $_POST['id'],
                            'name' => $_POST['name'],
                            'description' => $_POST['description']
                        ));

                        if ($item->save($errors)) {
                            throw new Redirection($url);
                        }
                    } else {
                        $item = $model::get($id);
                    }

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'base',
                            'file' => 'edit',
                            'data' => $item,
                            'form' => array(
                                'action' => "$url/edit/$id",
                                'submit' => array(
                                    'name' => 'update',
                                    'label' => Text::get('regular-save')
                                ),
                                'fields' => array (
                                    'id' => array(
                                        'label' => '',
                                        'name' => 'id',
                                        'type' => 'hidden'

                                    ),
                                    'name' => array(
                                        'label' => Text::_('Categoría'),
                                        'name' => 'name',
                                        'type' => 'text'
                                    ),
                                    'description' => array(
                                        'label' => Text::_('Descripción'),
                                        'name' => 'description',
                                        'type' => 'textarea',
                                        'properties' => 'cols="100" rows="2"',

                                    )
                                )

                            ),
                            'errors' => $errors
                        )
                    );

                    break;
                case 'up':
                    $model::up($id);
                    break;
                case 'down':
                    $model::down($id);
                    break;
                case 'remove':
                    if ($model::delete($id)) {
                        throw new Redirection($url);
                    }
                    break;
            }

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'base',
                    'file' => 'list',
                    'model' => 'category',
                    'addbutton' => Text::_('Nueva categoría'),
                    'data' => $model::getAll(),
                    'columns' => array(
                        'edit' => '',
                        'name' => Text::_('Categoría'),
                        'numProj' => Text::_('Proyectos'),
                        'numUser' => Text::_('Usuarios'),
                        'order' => Text::_('Prioridad'),
                        'translate' => '',
                        'up' => '',
                        'down' => '',
                        'translate' => '',
                        'remove' => ''
                    ),
                    'url' => "$url",
                    'errors' => $errors
                )
            );
        }

        /*
         *  Gestión de tags de blog
         *  Si no lo usa ningun post se puede borrar
         */
        public function tags($action = 'list', $id = null) {

            $BC = self::menu(array(
                'section' => 'contents',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id
            ));

            define('ADMIN_BCPATH', $BC);

            $model = 'Goteo\Model\Blog\Post\Tag';
            $url = '/admin/tags';

            $errors = array();

            switch ($action) {
                case 'add':
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'base',
                            'file' => 'edit',
                            'data' => (object) array(),
                            'form' => array(
                                'action' => "$url/edit/",
                                'submit' => array(
                                    'name' => 'update',
                                    'label' => Text::_('Añadir')
                                ),
                                'fields' => array (
                                    'id' => array(
                                        'label' => '',
                                        'name' => 'id',
                                        'type' => 'hidden'

                                    ),
                                    'name' => array(
                                        'label' => Text::_('Tag'),
                                        'name' => 'name',
                                        'type' => 'text'
                                    )
                                )

                            )
                        )
                    );

                    break;
                case 'edit':

                    // gestionar post
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {

                        $errors = array();

                        // instancia
                        $item = new $model(array(
                            'id' => $_POST['id'],
                            'name' => $_POST['name'],
                            'blog' => 1
                        ));

                        if ($item->save($errors)) {
                            throw new Redirection($url);
                        }
                    } else {
                        $item = $model::get($id);
                    }

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'base',
                            'file' => 'edit',
                            'data' => $item,
                            'form' => array(
                                'action' => "$url/edit/$id",
                                'submit' => array(
                                    'name' => 'update',
                                    'label' => Text::get('regular-save')
                                ),
                                'fields' => array (
                                    'id' => array(
                                        'label' => Text::_(''),
                                        'name' => 'id',
                                        'type' => 'hidden'

                                    ),
                                    'name' => array(
                                        'label' => Text::_('Tag'),
                                        'name' => 'name',
                                        'type' => 'text'
                                    )
                                )

                            ),
                            'errors' => $errors
                        )
                    );

                    break;
                case 'remove':
                    if ($model::delete($id)) {
                        throw new Redirection($url);
                    }
                    break;
            }

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'base',
                    'file' => 'list',
                    'model' => 'tag',
                    'addbutton' => Text::_('Nuevo tag'),
                    'data' => $model::getList(1),
                    'columns' => array(
                        'edit' => '',
                        'name' => 'Tag',
                        'used' => 'Entradas',
                        'translate' => '',
                        'remove' => ''
                    ),
                    'url' => "$url",
                    'errors' => $errors
                )
            );
        }

        /*
         *  administración de usuarios para superadmin
         */
        public function users($action = 'list', $id = null, $subaction = '') {

            $filters = array();
            $fields = array('status', 'interest', 'role', 'name', 'order');
            foreach ($fields as $field) {
                if (isset($_GET[$field])) {
                    $filters[$field] = $_GET[$field];
                }
            }

            $BC = self::menu(array(
                'section' => 'users',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id,
                'filter' => !empty($filters) ? "?status={$filters['status']}&interest={$filters['interest']}" : ''
            ));

            define('ADMIN_BCPATH', $BC);

            $errors = array();

            switch ($action)  {
                case 'add':

                    // si llega post: creamos
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $errors = array();

                        // para crear se usa el mismo método save del modelo, hay que montar el objeto
                        $user = new Model\User();
                        $user->userid = $_POST['userid'];
                        $user->name = $_POST['name'];
                        $user->email = $_POST['email'];
                        $user->password = $_POST['password'];
                        $user->save($errors);

                        if(empty($errors)) {
                          // mensaje de ok y volvemos a la lista de usuarios
                          Message::Info(Text::get('user-register-success'));
                          throw new Redirection('/admin/users');
                        } else {
                            // si hay algun error volvemos a poner los datos en el formulario
                            $data = $_POST;
                        }
                    }

                    // vista de crear usuario
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'users',
                            'file' => 'add',
                            'data'=>$data,
                            'errors'=>$errors
                        )
                    );

                    break;
                case 'edit':

                    $user = Model\User::get($id);

                    // si llega post: actualizamos
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $errors = array();

                        $tocado = array();
                        // para crear se usa el mismo método save del modelo, hay que montar el objeto
                        if (!empty($_POST['email'])) {
                            $user->email = $_POST['email'];
                            $tocado[] = 'el email';
                        }
                        if (!empty($_POST['password'])) {
                            $user->password = $_POST['password'];
                            $tocado[] = 'la contraseña';
                        }

                        if(!empty($tocado) && $user->update($errors)) {

                            /*
                             * Evento Feed
                             */
                            $log = new Feed();
                            $log->title = Text::_('Operación sobre usuario (admin)');
                            $log->url = '/admin/users';
                            $log->type = 'user';
                            $log_text = Text::_('El admin %s ha %s del usuario %s');
                            $log_items = array(
                                Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                Feed::item('relevant', 'Tocado ' . implode (' y ', $tocado)),
                                Feed::item('user', $user->name, $user->id)
                            );
                            $log->html = \vsprintf($log_text, $log_items);
                            $log->add($errors);

                            unset($log);


                            // mensaje de ok y volvemos a la lista de usuarios
                            Message::Info(Text::_('Datos actualizados'));
                            throw new Redirection('/admin/users');
                            
                        } else {
                            // si hay algun error volvemos a poner los datos en el formulario
                            $data = $_POST;
                        }
                    }

                    // vista de editar usuario
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'users',
                            'file' => 'edit',
                            'user'=>$user,
                            'data'=>$data,
                            'errors'=>$errors
                        )
                    );

                    break;
                case 'manage':

                    // si llega post: ejecutamos + mensaje + seguimos editando

                    // operación y acción para el feed
                    $sql = '';
                    switch ($subaction)  {
                        case 'ban':
                            $sql = "UPDATE user SET active = 0 WHERE id = :user";
                            $log_action = Text::_('Desactivado');
                            break;
                        case 'unban':
                            $sql = "UPDATE user SET active = 1 WHERE id = :user";
                            $log_action = Text::_('Activado');
                            break;
                        case 'show':
                            $sql = "UPDATE user SET hide = 0 WHERE id = :user";
                            $log_action = Text::_('Mostrado');
                            break;
                        case 'hide':
                            $sql = "UPDATE user SET hide = 1 WHERE id = :user";
                            $log_action = Text::_('Ocultado');
                            break;
                        case 'checker':
                            $sql = "REPLACE INTO user_role (user_id, role_id) VALUES (:user, 'checker')";
                            $log_action = Text::_('Hecho revisor');
                            break;
                        case 'nochecker':
                            $sql = "DELETE FROM user_role WHERE role_id = 'checker' AND user_id = :user";
                            $log_action = Text::_('Quitado de revisor');
                            break;
                        case 'translator':
                            $sql = "REPLACE INTO user_role (user_id, role_id) VALUES (:user, 'translator')";
                            $log_action = Text::_('Hecho traductor');
                            break;
                        case 'notranslator':
                            $sql = "DELETE FROM user_role WHERE role_id = 'translator' AND user_id = :user";
                            $log_action = Text::_('Quitado de traductor');
                            break;
                        case 'admin':
                            $sql = "REPLACE INTO user_role (user_id, role_id) VALUES (:user, 'admin')";
                            $log_action = Text::_('Hecho admin');
                            break;
                        case 'noadmin':
                            $sql = "DELETE FROM user_role WHERE role_id = 'admin' AND user_id = :user";
                            $log_action = Text::_('Quitado de admin');
                            break;
                    }


                    if (!empty($sql)) {

                        $user = Model\User::getMini($id);

                        if (Model\User::query($sql, array(':user'=>$id))) {
                            
                            // mensaje de ok y volvemos a la gestion del usuario
                            $msgi = _("Ha <strong>%s</strong> al usuario <strong>%s</strong> CORRECTAMENTE");
                            $msgi = sprintf($msgi, $log_action, $user->name);
                            Message::Info( $msgi );
                            $log_text = Text::_('El admin %s ha %s al usuario %s');

                        } else {

                            // mensaje de error y volvemos a la gestion del usuario
                            $msgi = _("Ha FALLADO cuando ha <strong>%s</strong> al usuario <strong>%s</strong>");
                            $msgi = sprintf( $msgi, $log_action, $id);
                            Message::Error( $msgi );
                            $log_text = _("Al admin %s le ha <strong>FALLADO</strong> cuando ha %s al usuario %s");

                        }

                        /*
                         * Evento Feed
                         */
                        $log = new Feed();
                        $log->title = Text::_('Operación sobre usuario (admin)');
                        $log->url = '/admin/users';
                        $log->type = 'user';
                        $log_items = array(
                            Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                            Feed::item('relevant', $log_action),
                            Feed::item('user', $user->name, $user->id)
                        );
                        $log->html = \vsprintf($log_text, $log_items);
                        $log->add($errors);

                        unset($log);

                        throw new Redirection('/admin/users/manage/'.$id);
                    }

                    $user = Model\User::get($id);


                    // vista de gestión de usuario
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'users',
                            'file' => 'manage',
                            'user'=>$user,
                            'errors'=>$errors,
                            'success'=>$success
                        )
                    );


                    break;
                case 'impersonate':

                    $user = Model\User::get($id);

                    // vista de acceso a suplantación de usuario
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'users',
                            'file'   => 'impersonate',
                            'user'   => $user
                        )
                    );

                    break;
                /*
                case 'send':
                    // obtenemos los usuarios que siguen teniendo su email como contraseña
                    $workshoppers = Model\User::getWorkshoppers();

                    if (empty($workshoppers)) {
                        $errors[] = 'Ningún usuario tiene su email como contraseña, podemos cambiar la funcionalidad de este botón!';
                    } else {

                        // Obtenemos la plantilla para asunto y contenido
                        $template = Template::get(27);

                        foreach ($workshoppers as $fellow) {
                            $err = array();
                            // iniciamos mail
                            $mailHandler = new Mail();
                            $mailHandler->to = $fellow->email;
                            $mailHandler->toName = $fellow->name;
                            // blind copy a goteo desactivado durante las verificaciones
                //              $mailHandler->bcc = 'comunicaciones@goteo.org';
                            $mailHandler->subject = $template->title;
                            // substituimos los datos
                            $search  = array('%USERNAME%', '%USERID%', '%USEREMAIL%', '%SITEURL%');
                            $replace = array($fellow->name, $fellow->id, $fellow->email, SITE_URL);
                            $mailHandler->content = \str_replace($search, $replace, $template->text);
                            $mailHandler->html = true;
                            $mailHandler->template = $template->id;
                            if ($mailHandler->send($err)) {
                                $errors[] = 'Se ha enviado OK! a <strong>'.$fellow->name.'</strong> a la dirección <strong>'.$fellow->email.'</strong>';
                            } else {
                                $errors[] = 'Ha FALLADO! al enviar a <strong>'.$fellow->name.'</strong>. Ha dado este error: '. implode(',', $err);
                            }
                            unset($mailHandler);
                        }


                    }
*/
                
                case 'list':
                default:
                    $users = Model\User::getAll($filters);
                    $status = array(
                                'active' => Text::_('Activo'),
                                'inactive' => Text::_('Inactivo')
                            );
                    $interests = Model\User\Interest::getAll();
                    $roles = array(
                        'admin' => Text::_('Administrador'),
                        'checker' => Text::_('Revisor'),
                        'translator' => Text::_('Traductor')
                    );
                    $orders = array(
                        'created' => Text::_('Fecha de alta'),
                        'name' => Text::_('Nombre')
                    );

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'users',
                            'file' => 'list',
                            'users'=>$users,
                            'filters' => $filters,
                            'name' => $name,
                            'status' => $status,
                            'interests' => $interests,
                            'roles' => $roles,
                            'orders' => $orders,
                            'errors' => $errors
                        )
                    );
                    break;
            }
        }

        /*
         *  Gestión de aportes a proyectos
         */
        public function invests($action = 'list', $id = null) {

            $BC = self::menu(array(
                'section' => 'accounting',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id
            ));

            define('ADMIN_BCPATH', $BC);

            $errors = array();

           // reubicando aporte,
           if ($action == 'move') {

                // el aporte original
                $original = Model\Invest::get($id);
                $userData = Model\User::getMini($original->user);
                $projectData = Model\Project::getMini($original->project);

                //el original tiene que ser de tpv o cash y estar como 'cargo ejecutado'
                if ($original->method == 'paypal' || $original->status != 1) {
                    Message::Error(Text::_('No se puede reubicar este aporte!'));
                    throw new Redirection('/admin/invests');
                }


                // generar aporte manual y caducar el original
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['move']) ) {

                    // si falta proyecto, error
                    
                    $projectNew = $_POST['project'];

                    // @TODO a saber si le toca dinero de alguna convocatoria
                    $campaign = null;

                    $invest = new Model\Invest(
                        array(
                            'amount'    => $original->amount,
                            'user'      => $original->user,
                            'project'   => $projectNew,
                            'account'   => $userData->email,
                            'method'    => 'cash',
                            'status'    => '1',
                            'invested'  => date('Y-m-d'),
                            'charged'   => $original->charged,
                            'anonymous' => $original->anonymous,
                            'resign'    => $original->resign,
                            'admin'     => $_SESSION['user']->id,
                            'campaign'  => $campaign
                        )
                    );
                    //@TODO si el proyecto seleccionado

                    if ($invest->save($errors)) {

                        //recompensas que le tocan (si no era resign)
                        if (!$original->resign) {
                            // sacar recompensas
                            $rewards = Model\Project\Reward::getAll($projectNew, 'individual');
                            
                            foreach ($rewards as $rewId => $rewData) {
                                $invest->setReward($rewId); //asignar
                            }
                        }

                        // cambio estado del aporte original a 'Reubicado' (no aparece en cofinanciadores)
                        // si tuviera que aparecer lo marcaríamos como caducado
                        if ($original->setStatus('5')) {
                            /*
                             * Evento Feed
                             */
                            $log = new Feed();
                            $log->title = Text::_('Aporte reubicado');
                            $log->url = '/admin/invests';
                            $log->type = 'money';
                            $log_text = _("%s ha aportado %s al proyecto %s en nombre de %s");
                            $log_items = array(
                                Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                Feed::item('money', $_POST['amount'].' &euro;'),
                                Feed::item('project', $projectData->name, $projectData->id),
                                Feed::item('user', $userData->name, $userData->id)
                            );
                            $log->html = \vsprintf($log_text, $log_items);
                            $log->add($errors);
                            unset($log);

                            Message::Info(Text::_('Aporte reubicado correctamente'));
                            throw new Redirection('/admin/invests');
                        } else {
							$msgi = _("A fallado al cambiar el estado del aporte original (%s)");
                            $errors[] = sprintf($msgi, $original->id);
                        }
                    } else{
                        $errors[] = Text::_('Ha fallado algo al reubicar el aporte');
                    }

                }

                $viewData = array(
                    'folder' => 'invests',
                    'file' => 'move',
                    'original' => $original,
                    'user'     => $userData,
                    'project'  => $projectData,
                    'errors'   => $errors
                );

                return new View(
                    'view/admin/index.html.php',
                    $viewData
                );

                // fin de la historia dereubicar
           }

            // aportes manuales, cargamos la lista completa de usuarios, proyectos y campañas
           if ($action == 'add') {
               
                // listado de proyectos existentes
                $projects = Model\Project::getAll();
                // usuarios
                $users = Model\User::getAllMini();
                // campañas
                $campaigns = Model\Campaign::getAll();

                // generar aporte manual
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add']) ) {

                    $userData = Model\User::getMini($_POST['user']);
                    $projectData = Model\Project::getMini($_POST['project']);

                    $invest = new Model\Invest(
                        array(
                            'amount'    => $_POST['amount'],
                            'user'      => $userData->id,
                            'project'   => $projectData->id,
                            'account'   => $userData->email,
                            'method'    => 'cash',
                            'status'    => '1',
                            'invested'  => date('Y-m-d'),
                            'charged'   => date('Y-m-d'),
                            'anonymous' => $_POST['anonymous'],
                            'resign'    => 1,
                            'admin'     => $_SESSION['user']->id,
                            'campaign'  => $_POST['campaign']
                        )
                    );

                    if ($invest->save($errors)) {
                        /*
                         * Evento Feed
                         */
                        $log = new Feed();
                        $log->title = Text::_('Aporte manual');
                        $log->url = '/admin/invests';
                        $log->type = 'money';
                        $log_text = _("%s ha aportado %s al proyecto %s en nombre de %s");
                        $log_items = array(
                            Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                            Feed::item('money', $_POST['amount'].' &euro;'),
                            Feed::item('project', $projectData->name, $projectData->id),
                            Feed::item('user', $userData->name, $userData->id)
                        );
                        $log->html = \vsprintf($log_text, $log_items);
                        $log->add($errors);
                        unset($log);
                        
                        Message::Info( Text::_('Aporte manual creado correctamente') );
                        throw new Redirection('/admin/invests');
                    } else{
                        $errors[] = Text::_('Ha fallado algo al crear el aporte manual');
                    }

                }

                 $viewData = array(
                        'folder' => 'invests',
                        'file' => 'add',
                        'users'         => $users,
                        'projects'      => $projects,
                        'campaigns'     => $campaigns,
                        'errors'        => $errors
                    );

                return new View(
                    'view/admin/index.html.php',
                    $viewData
                );

                // fin de la historia

           } else {

               // sino, cargamos los filtros
                $filters = array();
                $fields = array('filtered', 'methods', 'status', 'investStatus', 'projects', 'users', 'campaigns', 'types');
                foreach ($fields as $field) {
                    $filters[$field] = (string) $_GET[$field];
                }

                if (!isset($filters['status'])) $filters['status'] = 'all';
                if (!isset($filters['investStatus'])) $filters['status'] = 'all';


                // métodos de pago
                $methods = Model\Invest::methods();
                // estados del proyecto
                $status = Model\Project::status();
                // estados de aporte
                $investStatus = Model\Invest::status();
                // listado de proyectos
                $projects = Model\Invest::projects();
                // usuarios cofinanciadores
                $users = Model\Invest::users(true);
                // campañas que tienen aportes
                $campaigns = Model\Invest::campaigns();
                // extras
                $types = array(
                    'donative' => Text::_('Solo los donativos'),
                    'anonymous' => Text::_('Solo los anónimos'),
                    'manual' => Text::_('Solo los manuales'),
                    'campaign' => Text::_('Solo los de Bolsa'),
                );

           }

            // Informe de la financiación de un proyecto
            if ($action == 'report') {
                // estados de aporte
                $project = Model\Project::get($id);
                if (!$project instanceof Model\Project) {
                    Message::Error(Text::_('Instancia de proyecto no valida'));
                    throw new Redirection('/admin/invests');
                }
                $invests = Model\Invest::getAll($id);
                $users  = Model\Invest::investors($id, false, true);

                // Datos para el informe de transacciones correctas
                $reportData = Model\Invest::getReportData($project->id, $project->status, $project->round, $project->passed);

                return new View(
                    'view/admin/index.html.php',
                    array(
                        'folder' => 'invests',
                        'file' => 'report',
                        'invests' => $invests,
                        'project' => $project,
                        'status' => $status,
                        'users' => $users,
                        'investStatus' => $investStatus,
                        'reportData' => $reportData
                    )
                );
            }

            if (in_array($action, array('details', 'cancel', 'execute')) ) {
                $invest = Model\Invest::get($id);
                $project = Model\Project::get($invest->project);
                $userData = Model\User::get($invest->user);
            }

            // cancelar aporte antes de ejecución, solo aportes no cargados
            if ($action == 'cancel') {

                if ($project->status > 3 && $project->status < 6) {
                    $errors[] = Text::_('No debería poderse cancelar un aporte cuando el proyecto ya está financiado. Si es imprescindible, hacerlo desde el panel de paypal o tpv');
                    break;
                }

                switch ($invest->method) {
                    case 'paypal':
                        $err = array();
                        if (Paypal::cancelPreapproval($invest, $err)) {
                            $errors[] = Text::_('Preaproval paypal cancelado.');
                            $log_text = _("El admin %s ha cancelado aporte y preapproval de %s de %s mediante PayPal (id: %s) al proyecto %s del dia %s");
                        } else {
                            $txt_errors = implode('; ', $err);
                            $errors[] = Text::_('Fallo al cancelar el preapproval en paypal: ') . $txt_errors;
                            $log_text = _("El admin %s ha fallado al cancelar el aporte de %s de %s mediante PayPal (id: %s) al proyecto %s del dia %s. <br />Se han dado los siguientes errores: ").$txt_errors;
                            if ($invest->cancel()) {
                                $errors[] = Text::_('Aporte cancelado');
                            } else{
                                $errors[] = Text::_('Fallo al cancelar el aporte');
                            }
                        }
                        break;
                    case 'tpv':
                        $err = array();
                        if (Tpv::cancelPreapproval($invest, $err)) {
                            $txt_errors = implode('; ', $err);
                            $errors[] = Text::_('Aporte cancelado correctamente. ') . $txt_errors;
                            $log_text = _("El admin %s ha anulado el cargo tpv de %s de %s mediante TPV (id: %s) al proyecto %s del dia %s");
                        } else {
                            $txt_errors = implode('; ', $err);
                            $errors[] = Text::_('Fallo en la operación. ') . $txt_errors;
                            $log_text = _("El admin %s ha fallado al solicitar la cancelación del cargo tpv de %s de %s mediante TPV (id: %s) al proyecto %s del dia %s. <br />Se han dado los siguientes errores: $txt_errors");
                        }
                        break;
                    case 'cash':
                        if ($invest->cancel()) {
                            $log_text = _("El admin %s ha cancelado aporte manual de %s de %s (id: %s) al proyecto %s del dia %s");
                            $errors[] = Text::_('Aporte cancelado');
                        } else{
                            $log_text = _("El admin %s ha fallado al cancelar el aporte manual de %s de %s (id: %s) al proyecto %s del dia %s. ");
                            $errors[] = Text::_('Fallo al cancelar el aporte');
                        }
                        break;
                }

                /*
                 * Evento Feed
                 */
                $log = new Feed();
                $log->title = Text::_('Cargo cancelado (admin)');
                $log->url = '/admin/invests';
                $log->type = 'system';
                $log_items = array(
                    Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                    Feed::item('user', $userData->name, $userData->id),
                    Feed::item('money', $invest->amount.' &euro;'),
                    Feed::item('system', $invest->id),
                    Feed::item('project', $project->name, $project->id),
                    Feed::item('system', date('d/m/Y', strtotime($invest->invested)))
                );
                $log->html = \vsprintf($log_text, $log_items);
                $log->add($errors);
                unset($log);
            }

            // ejecutar cargo ahora!!, solo aportes no ejecutados
            // si esta pendiente, ejecutar el cargo ahora (como si fuera final de ronda), deja pendiente el pago secundario
            if ($action == 'execute' && $invest->status == 0) {
                switch ($invest->method) {
                    case 'paypal':
                        // a ver si tiene cuenta paypal
                        $projectAccount = Model\Project\Account::get($invest->project);

                        if (empty($projectAccount->paypal)) {
                            $errors[] = Text::_('El proyecto no tiene cuenta paypal!!, ponersela en la seccion Contrato del dashboard del autor');
                            $log_text = null;
                            // Erroraco!
                            /*
                             * Evento Feed
                             */
                            $log = new Feed();
                            $log->title = Text::_('proyecto sin cuenta paypal (admin)');
                            $log->url = '/admin/projects';
                            $log->type = 'project';
                            $log_text = Text::_('El proyecto %s aun no ha puesto su %s !!!');
                            $log_items = array(
                                Feed::item('project', $project->name, $project->id),
                                Feed::item('relevant', Text::_('cuenta PayPal'))
                            );
                            $log->html = \vsprintf($log_text, $log_items);
                            $log->add($errors);

                            unset($log);
                            
                            break;
                        }

                        $invest->account = $projectAccount->paypal;
                        if (Paypal::pay($invest, $errors)) {
                            $errors[] = Text::_('Cargo paypal correcto');
                            $log_text = _("El admin %s ha ejecutado el cargo a %s por su aporte de %s mediante PayPal (id: %s) al proyecto %s del dia %s");
                            $invest->status = 1;
                        } else {
                            $txt_errors = implode('; ', $errors);
                            $errors[] = Text::_('Fallo al ejecutar cargo paypal: ') . $txt_errors;
                            $log_text = _("El admin %s ha fallado al ejecutar el cargo a %s por su aporte de %s mediante PayPal (id: %s) al proyecto %s del dia %s. <br />Se han dado los siguientes errores: $txt_errors");
                        }
                        break;
                    case 'tpv':
                        if (Tpv::pay($invest, $errors)) {
                            $errors[] = Text::_('Cargo sermepa correcto');
                            $log_text = _("El admin %s ha ejecutado el cargo a %s por su aporte de %s mediante TPV (id: %s) al proyecto %s del dia %s");
                            $invest->status = 1;
                        } else {
                            $txt_errors = implode('; ', $errors);
                            $errors[] = Text::_('Fallo al ejecutar cargo sermepa: ') . $txt_errors;
                            $log_text = _("El admin %s ha fallado al ejecutar el cargo a %s por su aporte de %s mediante TPV (id: %s) al proyecto %s del dia %s <br />Se han dado los siguientes errores: $txt_errors");
                        }
                        break;
                    case 'cash':
                        $invest->setStatus('1');
                        $errors[] = Text::_('Aporte al contado, nada que ejecutar.');
                        $log_text = _("El admin %s ha dado por ejecutado el aporte manual a nombre de %s por la cantidad de %s (id: %s) al proyecto %s del dia %s");
                        $invest->status = 1;
                        break;
                }

                if (!empty($log_text)) {
                    /*
                     * Evento Feed
                     */
                    $log = new Feed();
                    $log->title = Text::_('Cargo ejecutado (admin)');
                    $log->url = '/admin/invests';
                    $log->type = 'system';
                    $log_items = array(
                        Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                        Feed::item('user', $userData->name, $userData->id),
                        Feed::item('money', $invest->amount.' &euro;'),
                        Feed::item('system', $invest->id),
                        Feed::item('project', $project->name, $project->id),
                        Feed::item('system', date('d/m/Y', strtotime($invest->invested)))
                    );
                    $log->html = \vsprintf($log_text, $log_items);
                    $log->add($errors);
                    unset($log);
                }
            }



            // detalles del aporte
            if (in_array($action, array('details', 'cancel', 'execute')) ) {

                $invest = Model\Invest::get($id);

                return new View(
                    'view/admin/index.html.php',
                    array(
                        'folder' => 'invests',
                        'file' => 'details',
                        'invest' => $invest,
                        'project' => $project,
                        'user' => $userData,
                        'status' => $status,
                        'investStatus' => $investStatus,
                        'campaign' => $campaigns[$invest->campaign],
                        'errors' => $errors
                    )
                );
            }

            // listado de aportes
            if ($filters['filtered'] == 'yes') {
                $list = Model\Invest::getList($filters);
            } else {
                $list = array();
            }

             $viewData = array(
                    'folder' => 'invests',
                    'file' => 'list',
                    'list'          => $list,
                    'filters'       => $filters,
                    'users'         => $users,
                    'projects'      => $projects,
                    'campaigns'     => $campaigns,
                    'methods'       => $methods,
                    'types'         => $types,
                    'status'        => $status,
                    'investStatus'  => $investStatus,
                    'errors'        => $errors
                );

            return new View(
                'view/admin/index.html.php',
                $viewData
            );

        }

        /*
         *  Gestión transacciones (tpv/paypal)
         *  solo proyectos en campaña o financiados
         * 
         */
        public function accounts($action = 'list', $id = null) {

            $BC = self::menu(array(
                'section' => 'accounting',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id
            ));

            define('ADMIN_BCPATH', $BC);

            $errors = array();

            // visor de logs
            if ($action == 'viewer') {
                return new View(
                    'view/admin/index.html.php',
                    array(
                        'folder' => 'accounts',
                        'file' => 'viewer'
                    )
                );
            }

            // cargamos los filtros
            $filters = array();
            $fields = array('filtered', 'methods', 'investStatus', 'projects', 'users', 'campaigns', 'review', 'date_from', 'date_until');
            foreach ($fields as $field) {
                $filters[$field] = (string) $_GET[$field];
            }

            if (!isset($filters['investStatus'])) $filters['investStatus'] = 'all';

            // tipos de aporte
            $methods = Model\Invest::methods();
            // estados del proyecto
            $status = Model\Project::status();
            // estados de aporte
            $investStatus = Model\Invest::status();
            // listado de proyectos
            $projects = Model\Invest::projects();
            // usuarios cofinanciadores
            $users = Model\Invest::users(true);
            // campañas que tienen aportes
            $campaigns = Model\Invest::campaigns();

            // filtros de revisión de proyecto
            $review = array(
                'collect' => Text::_('Recaudado'),
                'paypal'  => Text::_('Rev. PayPal'),
                'tpv'     => Text::_('Rev. TPV'),
                'online'  => Text::_('Pagos Online')
            );


            /// detalles de una transaccion
            if ($action == 'details') {
                $invest = Model\Invest::get($id);
                $project = Model\Project::get($invest->project);
                $userData = Model\User::get($invest->user);
                return new View(
                    'view/admin/index.html.php',
                    array(
                        'folder' => 'accounts',
                        'file' => 'details',
                        'invest'=>$invest,
                        'project'=>$project,
                        'user'=>$userData,
                        'details'=>$details,
                        'status'=>$status,
                        'investStatus'=>$investStatus
                    )
                );
            }

            // listado de aportes
            if ($filters['filtered'] == 'yes') {
                $list = Model\Invest::getList($filters);
            } else {
                $list = array();
            }

             $viewData = array(
                    'folder' => 'accounts',
                    'file' => 'list',
                    'list'          => $list,
                    'filters'       => $filters,
                    'users'         => $users,
                    'projects'      => $projects,
                    'campaigns'     => $campaigns,
                    'review'        => $review,
                    'methods'       => $methods,
                    'status'        => $status,
                    'investStatus'  => $investStatus,
                    'errors'        => $errors
                );

            return new View(
                'view/admin/index.html.php',
                $viewData
            );

        }


        /*
         * Gestión de retornos, por ahora en el admin pero es una gestión para los responsables de proyectos
         * Proyectos financiados, puede marcar un retorno cumplido
         */
        public function rewards($action = 'list', $id = null) {

            $filters = array();
            $fields = array('status', 'icon');
            foreach ($fields as $field) {
                if (isset($_GET[$field])) {
                    $filters[$field] = $_GET[$field];
                }
            }

            $BC = self::menu(array(
                'section' => 'projects',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id,
                'filter' => !empty($filters) ? "?status={$filters['status']}&icon={$filters['icon']}" : ''
            ));

            define('ADMIN_BCPATH', $BC);

            $errors = array();

            switch ($action)  {
                case 'fulfill':
                    $sql = "UPDATE reward SET fulsocial = 1 WHERE type= 'social' AND id = ?";
                    Model\Project\Reward::query($sql, array($id));
                    break;
                case 'unfill':
                    $sql = "UPDATE reward SET fulsocial = 0 WHERE id = ?";
                    Model\Project\Reward::query($sql, array($id));
                    break;
            }

            $projects = Model\Project::published('success');

            foreach ($projects as $kay=>&$project) {
                $project->social_rewards = Model\Project\Reward::getAll($project->id, 'social', LANG, $filters['status'], $filters['icon']);
            }

            $status = array(
                        'nok' => Text::_('Pendiente'),
                        'ok'  => Text::_('Cumplido')
                        
                    );
            $icons = Model\Icon::getAll('social');
            foreach ($icons as $key => $icon) {
                $icons[$key] = $icon->name;
            }

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'base',
                    'file' => 'rewards',
                    'projects'=>$projects,
                    'filters' => $filters,
                    'status' => $status,
                    'icons' => $icons,
                    'errors' => $errors
                )
            );


        }

        /*
         * Gestión de entradas de blog
         */
        public function blog ($action = 'list', $id = null) {
            
            $errors = array();

            $blog = Model\Blog::get(\GOTEO_NODE, 'node');
            if (!$blog instanceof \Goteo\Model\Blog) {
                $errors[] = Text::_('No tiene espacio de blog, Contacte con nosotros');
                $action = 'list';
            } else {
                if (!$blog->active) {
                    $errors[] = Text::_('Lo sentimos, el blog para este nodo esta desactivado');
                    $action = 'list';
                }
            }

            // primero comprobar que tenemos blog
            if (!$blog instanceof Model\Blog) {
                $errors[] = Text::_('No se ha encontrado ningún blog para este nodo');
                $action = 'list';
            }

            $url = '/admin/blog';

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
                        'home',
                        'footer',
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

                    $post->tags = $_POST['tags'];

                    /// este es el único save que se lanza desde un metodo process_
                    if ($post->save($errors)) {
                        if ($action == 'edit') {
                            $success[] = Text::_('La entrada se ha actualizado correctamente');
                            ////Text::get('dashboard-project-updates-saved');
                        } else {
                            $success[] = Text::_('Se ha añadido una nueva entrada');
                            ////Text::get('dashboard-project-updates-inserted');
                            $id = $post->id;
                        }
                        $action = $editing ? 'edit' : 'list';

                        if ((bool) $post->publish) {
                            /*
                             * Evento Feed
                             */
                            $log = new Feed();
                            $log->title = Text::_('nueva entrada blog Goteo (admin)');
                            $log->url = '/admin/blog';
                            $log->type = 'admin';
                            $log_text = Text::_('El admin %s ha %s en el blog Goteo la entrada "%s"');
                            $log_items = array(
                                Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                Feed::item('relevant', 'Publicado'),
                                Feed::item('blog', $post->title, $post->id)
                            );
                            $log->html = \vsprintf($log_text, $log_items);
                            $log->add($errors);

                            // evento público
                            $log->unique = true;
                            $log->title = $post->title;
                            $log->url = '/blog/'.$post->id;
                            $log->image = $post->gallery[0]->id;
                            $log->scope = 'public';
                            $log->type = 'goteo';
                            $log->html = Text::recorta($post->text, 250);
                            $log->add($errors);

                            unset($log);
                        } else {
                            //sino lo quitamos
                            \Goteo\Core\Model::query("DELETE FROM feed WHERE url = '/blog/{$post->id}' AND scope = 'public' AND type = 'goteo'");
                        }

                    } else {
                        $errors[] = Text::_('Ha habido algun problema al guardar los datos');
                        ////Text::get('dashboard-project-updates-fail');
                    }
            }

            $BC = self::menu(array(
                'section' => 'contents',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id
            ));

            define('ADMIN_BCPATH', $BC);

            switch ($action)  {
                case 'remove':
                    // eliminar una entrada
                    $tempData = Model\Blog\Post::get($id);
                    if (Model\Blog\Post::delete($id)) {
                        /*
                         * Evento Feed
                         */
                        $log = new Feed();
                        $log->title = Text::_('entrada quitada (admin)');
                        $log->url = '/admin/blog';
                        $log->type = 'admin';
                        $log_text = Text::_('El admin %s ha %s la entrada "%s" del blog de Goteo');
                        $log_items = array(
                            Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                            Feed::item('relevant', Text::_('Quitado')),
                            Feed::item('blog', $tempData->title)
                        );
                        $log->html = \vsprintf($log_text, $log_items);
                        $log->add($errors);

                        unset($log);

                        unset($blog->posts[$id]);
                        $success[] = Text::_('Entrada eliminada');
                    } else {
                        $errors[] = Text::_('No se ha podido eliminar la entrada');
                    }
                    // no break para que continue con list
                case 'list':
                    // lista de entradas
                    // obtenemos los datos
                    $posts = Model\Blog\Post::getAll($blog->id, null, false);

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'blog',
                            'file' => 'list',
                            'posts' => $posts,
                            'errors' => $errors,
                            'success' => $success
                        )
                    );
                    break;
                case 'add':
                    // nueva entrada con wisiwig
                    // obtenemos datos basicos
                    $post = new Model\Blog\Post(
                            array(
                                'blog' => $blog->id,
                                'date' => date('Y-m-d'),
                                'publish' => false,
                                'allow' => true,
                                'tags' => array()
                            )
                        );

                    $message = Text::_('Añadiendo una nueva entrada');

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'blog',
                            'file' => 'edit',
                            'action' => 'add',
                            'post' => $post,
                            'tags' => Model\Blog\Post\Tag::getAll(),
                            'message' => $message,
                            'errors' => $errors,
                            'success' => $success
                        )
                    );
                    break;
                case 'edit':
                    if (empty($id)) {
                        throw new Redirection('/admin/blog');
//                        $errors[] = 'No se ha encontrado la entrada';
                        //Text::get('dashboard-project-updates-nopost');
//                        $action = 'list';
                        break;
                    } else {
                        $post = Model\Blog\Post::get($id);

                        if (!$post instanceof Model\Blog\Post) {
                            $errors[] = Text::_('La entrada esta corrupta, contacte con nosotros.');
                            //Text::get('dashboard-project-updates-postcorrupt');
                            $action = 'list';
                            break;
                        }
                    }

                    $message = Text::_('Editando una entrada existente');

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'blog',
                            'file' => 'edit',
                            'action' => 'edit',
                            'post' => $post,
                            'tags' => Model\Blog\Post\Tag::getAll(),
                            'message' => $message,
                            'errors' => $errors,
                            'success' => $success
                        )
                    );
                    break;
            }

        }

        /*
         * Gestión de términos del Glosario
         */
        public function glossary ($action = 'list', $id = null) {

            $errors = array();

            $url = '/admin/glossary';

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    $editing = false;

                    if (!empty($_POST['id'])) {
                        $post = Model\Glossary::get($_POST['id']);
                    } else {
                        $post = new Model\Glossary();
                    }
                    // campos que actualizamos
                    $fields = array(
                        'id',
                        'title',
                        'text',
                        'media',
                        'legend'
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
                            $image->remove('glossary');
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

                    /// este es el único save que se lanza desde un metodo process_
                    if ($post->save($errors)) {
                        if ($action == 'edit') {
                            $success[] = Text::_('El término se ha actualizado correctamente');
                        } else {
                            $success[] = Text::_('Se ha añadido un nuevo término');
                            $id = $post->id;
                        }
                        $action = $editing ? 'edit' : 'list';
                    } else {
                        $errors[] = Text::_('Ha habido algun problema al guardar los datos');
                    }
            }

            $BC = self::menu(array(
                'section' => 'contents',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id
            ));

            define('ADMIN_BCPATH', $BC);

            switch ($action)  {
                case 'remove':
                    // eliminar un término
                    if (Model\Glossary::delete($id)) {
                        $success[] = Text::_('Término eliminado');
                    } else {
                        $errors[] = Text::_('No se ha podido eliminar el término');
                    }
                    break;
                case 'add':
                    // nueva entrada con wisiwig
                    // obtenemos datos basicos
                    $post = new Model\Glossary();

                    $message = Text::_('Añadiendo un nuevo término');

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'glossary',
                            'file' => 'edit',
                            'action' => 'add',
                            'post' => $post,
                            'message' => $message,
                            'errors' => $errors,
                            'success' => $success
                        )
                    );
                    break;
                case 'edit':
                    if (empty($id)) {
                        throw new Redirection('/admin/glossary');
                        break;
                    } else {
                        $post = Model\Glossary::get($id);

                        if (!$post instanceof Model\Glossary) {
                            $errors[] = Text::_('La entrada esta corrupta, contacte con nosotros.');
                            //Text::get('dashboard-project-updates-postcorrupt');
                            $action = 'list';
                            break;
                        }
                    }

                    $message = Text::_('Editando un término existente');

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'glossary',
                            'file' => 'edit',
                            'action' => 'edit',
                            'post' => $post,
                            'message' => $message,
                            'errors' => $errors,
                            'success' => $success
                        )
                    );
                    break;
            }

            // lista de términos
            $posts = Model\Glossary::getAll();

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'glossary',
                    'file' => 'list',
                    'posts' => $posts,
                    'errors' => $errors,
                    'success' => $success
                )
            );

        }

        /*
         * Gestión de entradas de info
         */
        public function info ($action = 'list', $id = null) {

            $errors = array();

            $url = '/admin/info';

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    $editing = false;

                    if (!empty($_POST['id'])) {
                        $post = Model\Info::get($_POST['id']);
                    } else {
                        $post = new Model\Info();
                    }
                    // campos que actualizamos
                    $fields = array(
                        'id',
                        'node',
                        'title',
                        'text',
                        'media',
                        'legend',
                        'publish',
                        'order'
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
                            $image->remove('info');
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

                    /// este es el único save que se lanza desde un metodo process_
                    if ($post->save($errors)) {
                        if ($action == 'edit') {
                            $success[] = Text::_('La entrada se ha actualizado correctamente');

                            if ((bool) $post->publish) {
                                $log_action = Text::_('Publicado');
                            } else {
                                $log_action = Text::_('Modificado');
                            }

                        } else {
                            $success[] = Text::_('Se ha añadido una nueva entrada');
                            $id = $post->id;
                            $log_action = Text::_('Añadido');
                        }
                        $action = $editing ? 'edit' : 'list';

                        /*
                         * Evento Feed
                         */
                        $log = new Feed();
                        $log->title = Text::_('modificacion de idea about (admin)');
                        $log->url = '/admin/info';
                        $log->type = 'admin';
                        $log_text = Text::_('El admin %s ha %s la Idea de fuerza "%s"');
                        $log_items = array(
                            Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                            Feed::item('relevant', $log_action),
                            Feed::item('relevant', $post->title, '/about#info'.$post->id)
                        );
                        $log->html = \vsprintf($log_text, $log_items);
                        $log->add($errors);
                        unset($log);

                    } else {
                        $errors[] = Text::_('Ha habido algun problema al guardar los datos');
                    }
            }

            $BC = self::menu(array(
                'section' => 'contents',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id
            ));

            define('ADMIN_BCPATH', $BC);

            switch ($action)  {
                case 'up':
                    Model\Info::up($id);
                    break;
                case 'down':
                    Model\Info::down($id);
                    break;
                case 'remove':
                    $tempData = Model\Info::get($id);
                    // eliminar un término
                    if (Model\Info::delete($id)) {
                        $success[] = Text::_('Entrada eliminada');

                        /*
                         * Evento Feed
                         */
                        $log = new Feed();
                        $log->title = Text::_('quitar de idea about (admin)');
                        $log->url = '/admin/info';
                        $log->type = 'admin';
                        $log_text = Text::_('El admin %s ha %s la Idea de fuerza "%s"');
                        $log_items = array(
                            Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                            Feed::item('relevant', Text::_('Eliminado')),
                            Feed::item('relevant', $tempData->title)
                        );
                        $log->html = \vsprintf($log_text, $log_items);
                        $log->add($errors);
                        unset($log);

                    } else {
                        $errors[] = Text::_('No se ha podido eliminar la entrada');
                    }
                    break;
                case 'add':
                    // nueva entrada con wisiwig
                    // obtenemos datos basicos
                    if (!$post instanceof Model\Info) {
                        $post = new Model\Info();
                    }

                    $message = Text::_('Añadiendo una nueva entrada');

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'info',
                            'file' => 'edit',
                            'action' => 'add',
                            'post' => $post,
                            'message' => $message,
                            'errors' => $errors,
                            'success' => $success
                        )
                    );
                    break;
                case 'edit':
                    if (empty($id)) {
                        throw new Redirection('/admin/info');
                        break;
                    } else {
                        $post = Model\Info::get($id);

                        if (!$post instanceof Model\Info) {
                            $errors[] = Text::_('La entrada esta corrupta, contacte con nosotros.');
                            //Text::get('dashboard-project-updates-postcorrupt');
                            $action = 'list';
                            break;
                        }
                    }

                    $message = Text::_('Editando una entrada existente');

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'info',
                            'file' => 'edit',
                            'action' => 'edit',
                            'post' => $post,
                            'message' => $message,
                            'errors' => $errors,
                            'success' => $success
                        )
                    );
                    break;
            }

            // lista de términos
            $posts = Model\Info::getAll();

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'info',
                    'file' => 'list',
                    'posts' => $posts,
                    'errors' => $errors,
                    'success' => $success
                )
            );

        }


        /*
         *  Gestión de noticias
         */
        public function news($action = 'list', $id = null) {

            $BC = self::menu(array(
                'section' => 'home',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id
            ));

            define('ADMIN_BCPATH', $BC);

            $model = 'Goteo\Model\News';
            $url = '/admin/news';

            $errors = array();

            switch ($action) {
                case 'add':
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'base',
                            'file' => 'edit',
                            'data' => (object) array('order' => $model::next()),
                            'form' => array(
                                'action' => "$url/edit/",
                                'submit' => array(
                                    'name' => 'update',
                                    'label' => Text::_('Añadir')
                                ),
                                'fields' => array (
                                    'id' => array(
                                        'label' => '',
                                        'name' => 'id',
                                        'type' => 'hidden'

                                    ),
                                    'title' => array(
                                        'label' => Text::_('Noticia'),
                                        'name' => 'title',
                                        'type' => 'text',
                                        'properties' => 'size="100" maxlength="100"'
                                    ),
                                    'description' => array(
                                        'label' => Text::_('Entradilla'),
                                        'name' => 'description',
                                        'type' => 'textarea',
                                        'properties' => 'cols="100" rows="2"'
                                    ),
                                    'url' => array(
                                        'label' => Text::_('Enlace'),
                                        'name' => 'url',
                                        'type' => 'text',
                                        'properties' => 'size=100'
                                    ),
                                    'order' => array(
                                        'label' => Text::_('Posición'),
                                        'name' => 'order',
                                        'type' => 'text'
                                    )
                                )

                            )
                        )
                    );

                    break;
                case 'edit':

                    // gestionar post
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {

                        $errors = array();

                        // instancia
                        $item = new $model(array(
                            'id'          => $_POST['id'],
                            'title'       => $_POST['title'],
                            'description' => $_POST['description'],
                            'url'         => $_POST['url'],
                            'order'       => $_POST['order']
                        ));

                        if ($item->save($errors)) {

                            if (empty($_POST['id'])) {
                                /*
                                 * Evento Feed
                                 */
                                $log = new Feed();
                                $log->title = Text::_('nueva micronoticia (admin)');
                                $log->url = '/admin/news';
                                $log->type = 'admin';
                                $log_text = Text::_('El admin %s ha %s la micronoticia "%s"');
                                $log_items = array(
                                    Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                    Feed::item('relevant', Text::_('Publicado')),
                                    Feed::item('news', $_POST['title'], '#news'.$item->id)
                                );
                                $log->html = \vsprintf($log_text, $log_items);
                                $log->add($errors);

                                unset($log);
                            }

                            throw new Redirection($url);
                        }
                    } else {
                        $item = $model::get($id);
                    }

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'base',
                            'file' => 'edit',
                            'data' => $item,
                            'form' => array(
                                'action' => "$url/edit/$id",
                                'submit' => array(
                                    'name' => 'update',
                                    'label' => Text::get('regular-save')
                                ),
                                'fields' => array (
                                    'id' => array(
                                        'label' => '',
                                        'name' => 'id',
                                        'type' => 'hidden'

                                    ),
                                    'title' => array(
                                        'label' => Text::_('Noticia'),
                                        'name' => 'title',
                                        'type' => 'text',
                                        'properties' => 'size="100"  maxlength="80"'
                                    ),
                                    'description' => array(
                                        'label' => Text::_('Entradilla'),
                                        'name' => 'description',
                                        'type' => 'textarea',
                                        'properties' => 'cols="100" rows="2"'
                                    ),
                                    'url' => array(
                                        'label' => Text::_('Enlace'),
                                        'name' => 'url',
                                        'type' => 'text',
                                        'properties' => 'size=100'
                                    ),
                                    'order' => array(
                                        'label' => Text::_('Posición'),
                                        'name' => 'order',
                                        'type' => 'text'
                                    )
                                )

                            ),
                            'errors' => $errors
                        )
                    );

                    break;
                case 'up':
                    $model::up($id);
                    break;
                case 'down':
                    $model::down($id);
                    break;
                case 'remove':
                    $tempData = $model::get($id);
                    if ($model::delete($id)) {
                        /*
                         * Evento Feed
                         */
                        $log = new Feed();
                        $log->title = Text::_('micronoticia quitada (admin)');
                        $log->url = '/admin/news';
                        $log->type = 'admin';
                        $log_text = Text::_('El admin %s ha %s la micronoticia "%s"');
                        $log_items = array(
                            Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                            Feed::item('relevant', 'Quitado'),
                            Feed::item('blog', $tempData->title)
                        );
                        $log->html = \vsprintf($log_text, $log_items);
                        $log->add($errors);

                        unset($log);

                        throw new Redirection($url);
                    }
                    break;
            }

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'base',
                    'file' => 'list',
                    'model' => 'news',
                    'addbutton' => Text::_('Nueva noticia'),
                    'data' => $model::getAll(),
                    'columns' => array(
                        'edit' => '',
                        'title' => Text::_('Noticia'),
                        'url' => Text::_('Enlace'),
                        'order' => Text::_('Posición'),
                        'up' => '',
                        'down' => '',
                        'translate' => '',
                        'remove' => ''
                    ),
                    'url' => "$url",
                    'errors' => $errors
                )
            );
        }

        /*
         *  Gestión de patrocinadores
         */
        public function sponsors($action = 'list', $id = null) {

            $BC = self::menu(array(
                'section' => 'sponsors',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id
            ));

            define('ADMIN_BCPATH', $BC);

            $model = 'Goteo\Model\Sponsor';
            $url = '/admin/sponsors';

            $errors = array();

            switch ($action) {
                case 'add':
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'base',
                            'file' => 'edit',
                            'data' => (object) array('order' => $model::next() ),
                            'form' => array(
                                'action' => "$url/edit/",
                                'submit' => array(
                                    'name' => 'update',
                                    'label' => Text::_('Añadir')
                                ),
                                'fields' => array (
                                    'id' => array(
                                        'label' => Text::_(''),
                                        'name' => 'id',
                                        'type' => 'hidden'

                                    ),
                                    'name' => array(
                                        'label' => Text::_('Patrocinador'),
                                        'name' => 'name',
                                        'type' => 'text'
                                    ),
                                    'url' => array(
                                        'label' => Text::_('Enlace'),
                                        'name' => 'url',
                                        'type' => 'text',
                                        'properties' => 'size=100'
                                    ),
                                    'image' => array(
                                        'label' => Text::_('Logo'),
                                        'name' => 'image',
                                        'type' => 'image'
                                    ),
                                    'order' => array(
                                        'label' => Text::_('Posición'),
                                        'name' => 'order',
                                        'type' => 'text'
                                    )
                                )

                            )
                        )
                    );

                    break;
                case 'edit':

                    // gestionar post
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                        $errors = array();

                        // instancia
                        $item = new $model(array(
                            'id' => $_POST['id'],
                            'name' => $_POST['name'],
                            'url' => $_POST['url'],
                            'order' => $_POST['order']
                        ));

                        // tratar la imagen y ponerla en la propiedad image
                        if(!empty($_FILES['image']['name'])) {
                            $item->image = $_FILES['image'];
                        }

                        // tratar si quitan la imagen
                        $current = $_POST['image']; // la acual
                        if (isset($_POST['image-' . $current .  '-remove'])) {
                            $image = Model\Image::get($current);
                            $image->remove('sponsor');
                            $item->image = '';
                            $removed = true;
                        }

                        if ($item->save($errors)) {
                            throw new Redirection($url);
                        }
                    } else {
                        $item = $model::get($id);
                    }

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'base',
                            'file' => 'edit',
                            'data' => $item,
                            'form' => array(
                                'action' => "$url/edit/$id",
                                'submit' => array(
                                    'name' => 'update',
                                    'label' => Text::get('regular-save')
                                ),
                                'fields' => array (
                                    'id' => array(
                                        'label' => Text::_(''),
                                        'name' => 'id',
                                        'type' => 'hidden'

                                    ),
                                    'name' => array(
                                        'label' => Text::_('Patrocinador'),
                                        'name' => 'name',
                                        'type' => 'text'
                                    ),
                                    'url' => array(
                                        'label' => Text::_('Enlace'),
                                        'name' => 'url',
                                        'type' => 'text',
                                        'properties' => 'size=100'
                                    ),
                                    'image' => array(
                                        'label' => Text::_('Logo'),
                                        'name' => 'image',
                                        'type' => 'image'
                                    ),
                                    'order' => array(
                                        'label' => Text::_('Posición'),
                                        'name' => 'order',
                                        'type' => 'text'
                                    )
                                )

                            ),
                            'errors' => $errors
                        )
                    );

                    break;
                case 'up':
                    $model::up($id);
                    break;
                case 'down':
                    $model::down($id);
                    break;
                case 'remove':
                    if ($model::delete($id)) {
                        throw new Redirection($url);
                    }
                    break;
            }

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'base',
                    'file' => 'list',
                    'addbutton' => Text::_('Nuevo patrocinador'),
                    'data' => $model::getAll(),
                    'columns' => array(
                        'edit' => '',
                        'name' => Text::_('Patrocinador'),
                        'url' => Text::_('Enlace'),
                        'image' => Text::_('Imagen'),
                        'order' => Text::_('Posición'),
                        'up' => '',
                        'down' => '',
                        'remove' => ''
                    ),
                    'url' => "$url",
                    'errors' => $errors
                )
            );
        }

        /*
         *  Gestión de campañas
         */
        public function campaigns($action = 'list', $id = null) {

            $BC = self::menu(array(
                'section' => 'sponsors',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id
            ));

            define('ADMIN_BCPATH', $BC);

            $model = 'Goteo\Model\Campaign';
            $url = '/admin/campaigns';

            $errors = array();

            switch ($action) {
                case 'add':
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'base',
                            'file' => 'edit',
                            'data' => (object) array(),
                            'form' => array(
                                'action' => "$url/edit/",
                                'submit' => array(
                                    'name' => 'update',
                                    'label' => Text::_('Añadir')
                                ),
                                'fields' => array (
                                    'id' => array(
                                        'label' => Text::_(''),
                                        'name' => 'id',
                                        'type' => 'hidden'

                                    ),
                                    'name' => array(
                                        'label' => Text::_('Campaña'),
                                        'name' => 'name',
                                        'type' => 'text'
                                    ),
                                    'description' => array(
                                        'label' => Text::_('Descripción'),
                                        'name' => 'description',
                                        'type' => 'textarea',
                                        'properties' => 'cols="100" rows="2"'
                                    )
                                )

                            )
                        )
                    );

                    break;
                case 'edit':

                    // gestionar post
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {

                        $errors = array();

                        // instancia
                        $item = new $model(array(
                            'id' => $_POST['id'],
                            'name' => $_POST['name'],
                            'description' => $_POST['description']
                        ));

                        if ($item->save($errors)) {
                            throw new Redirection($url);
                        }
                    } else {
                        $item = $model::get($id);
                    }

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'base',
                            'file' => 'edit',
                            'data' => $item,
                            'form' => array(
                                'action' => "$url/edit/$id",
                                'submit' => array(
                                    'name' => 'update',
                                    'label' => Text::get('regular-save')
                                ),
                                'fields' => array (
                                    'id' => array(
                                        'label' => Text::_(''),
                                        'name' => 'id',
                                        'type' => 'hidden'

                                    ),
                                    'name' => array(
                                        'label' => Text::_('Campaña'),
                                        'name' => 'name',
                                        'type' => 'text'
                                    ),
                                    'description' => array(
                                        'label' => Text::_('Descripción'),
                                        'name' => 'description',
                                        'type' => 'textarea',
                                        'properties' => 'cols="100" rows="2"'
                                    )
                                )

                            ),
                            'errors' => $errors
                        )
                    );

                    break;
                case 'remove':
                    if ($model::delete($id)) {
                        throw new Redirection($url);
                    }
                    break;
            }

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'base',
                    'file' => 'list',
                    'addbutton' => Text::_('Nueva campaña'),
                    'data' => $model::getList(),
                    'columns' => array(
                        'edit' => '',
                        'name' => Text::_('Campaña'),
                        'used' => Text::_('Aportes'),
                        'remove' => ''
                    ),
                    'url' => "$url",
                    'errors' => $errors
                )
            );
        }

        /*
         *  Gestión de nodos
         */
        public function nodes($action = 'list', $id = null) {

            $BC = self::menu(array(
                'section' => 'sponsors',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id
            ));

            define('ADMIN_BCPATH', $BC);

            $model = 'Goteo\Model\Node';
            $url = '/admin/nodes';

            $errors = array();

            switch ($action) {
                case 'add':
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'base',
                            'file' => 'edit',
                            'data' => (object) array(),
                            'form' => array(
                                'action' => "$url/edit/",
                                'submit' => array(
                                    'name' => 'update',
                                    'label' => Text::_('Añadir')
                                ),
                                'fields' => array (
                                    'id' => array(
                                        'label' => Text::_(''),
                                        'name' => 'id',
                                        'type' => 'hidden'

                                    ),
                                    'name' => array(
                                        'label' => Text::_('Campaña'),
                                        'name' => 'name',
                                        'type' => 'text'
                                    ),
                                    'description' => array(
                                        'label' => Text::_('Descripción'),
                                        'name' => 'description',
                                        'type' => 'textarea',
                                        'properties' => 'cols="100" rows="2"'
                                    )
                                )

                            )
                        )
                    );

                    break;
                case 'edit':

                    // gestionar post
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {

                        $errors = array();

                        // instancia
                        $item = new $model(array(
                            'id' => $_POST['id'],
                            'name' => $_POST['name'],
                            'description' => $_POST['description']
                        ));

                        if ($item->save($errors)) {
                            throw new Redirection($url);
                        }
                    } else {
                        $item = $model::get($id);
                    }

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'base',
                            'file' => 'edit',
                            'data' => $item,
                            'form' => array(
                                'action' => "$url/edit/$id",
                                'submit' => array(
                                    'name' => 'update',
                                    'label' => Text::get('regular-save')
                                ),
                                'fields' => array (
                                    'id' => array(
                                        'label' => Text::_(''),
                                        'name' => 'id',
                                        'type' => 'hidden'

                                    ),
                                    'name' => array(
                                        'label' => Text::_('Campaña'),
                                        'name' => 'name',
                                        'type' => 'text'
                                    ),
                                    'description' => array(
                                        'label' => Text::_('Descripción'),
                                        'name' => 'description',
                                        'type' => 'textarea',
                                        'properties' => 'cols="100" rows="2"'
                                    )
                                )

                            ),
                            'errors' => $errors
                        )
                    );

                    break;
                case 'remove':
                    if ($model::delete($id)) {
                        throw new Redirection($url);
                    }
                    break;
            }

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'base',
                    'file' => 'list',
                    'addbutton' => Text::_('Nuevo nodo'),
                    'data' => $model::getList(),
                    'columns' => array(
                        'edit' => '',
                        'name' => Text::_('Campaña'),
                        'used' => Text::_('Aportes'),
                        'remove' => ''
                    ),
                    'url' => "$url",
                    'errors' => $errors
                )
            );
        }


        /*
         * Comunicaciones con los usuarios mediante mailing
         */
        public function mailing($action = 'list', $id = null) {

            $BC = self::menu(array(
                'section' => 'users',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id
            ));

            define('ADMIN_BCPATH', $BC);

            $errors = array();

            // Valores de filtro
//            $projects = Model\Project::getAll();
            $interests = Model\User\Interest::getAll();
            $status = Model\Project::status();
            $methods = Model\Invest::methods();
            $types = array(
                'investor' => Text::_('Cofinanciadores'),
                'owner' => Text::_('Autores'),
                'user' => Text::_('Usuarios')
            );
            $roles = array(
                'admin' => Text::_('Administrador'),
                'checker' => Text::_('Revisor'),
                'translator' => Text::_('Traductor')
            );

            // una variable de sesion para mantener los datos de todo esto
            if (!isset($_SESSION['mailing'])) {
                $_SESSION['mailing'] = array();
            }

            if (!isset($_SESSION['mailing']['filters']['status']))
                $_SESSION['mailing']['filters']['status'] = -1;

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                switch ($action) {
                    case 'edit':

                        $_SESSION['mailing']['receivers'] = array();

                        $values = array();
                        $sqlFields  = '';
                        $sqlInner  = '';
                        $sqlFilter = '';


                        // Han elegido filtros
                        $filters = array(
                            'project'  => $_POST['project'],
                            'type'     => $_POST['type'],
                            'status'   => $_POST['status'],
                            'method'   => $_POST['method'],
                            'interest' => $_POST['interest'],
                            'role'     => $_POST['role'],
                            'name'     => $_POST['name'],
                            'workshopper' => $_POST['workshopper']
                        );

                        $_SESSION['mailing']['filters'] = $filters;

                        // cargamos los destiantarios
                        //----------------------------
                        // por tipo de usuario
                        switch ($filters['type']) {
                            case 'investor':
                                $sqlInner .= "INNER JOIN invest
                                        ON invest.user = user.id
                                        AND (invest.status = 0 OR invest.status = 1 OR invest.status = 3 OR invest.status = 4)
                                    INNER JOIN project
                                        ON project.id = invest.project
                                        ";
                                $sqlFields .= ", project.name as project";
                                $sqlFields .= ", project.id as projectId";
                                break;
                            case 'owner':
                                $sqlInner .= "INNER JOIN project
                                        ON project.owner = user.id
                                        ";
                                $sqlFields .= ", project.name as project";
                                $sqlFields .= ", project.id as projectId";
                                break;
                            default :
                                break;
                        }
                        $_SESSION['mailing']['filters_txt'] = 'los <strong>' . $types[$filters['type']] . '</strong> ';

                        if (!empty($filters['project']) && !empty($sqlInner)) {
                            $sqlFilter .= " AND project.name LIKE (:project) ";
                            $values[':project'] = '%'.$filters['project'].'%';
                            $_SESSION['mailing']['filters_txt'] .= Text::_('de proyectos que su nombre contenga').' <strong>\'' . $filters['project'] . '\'</strong> ';
                        } elseif (empty($filters['project']) && !empty($sqlInner)) {
                            $_SESSION['mailing']['filters_txt'] .= Text::_('de cualquier proyecto');
                        }

                        if (isset($filters['status']) && $filters['status'] > -1 && !empty($sqlInner)) {
                            $sqlFilter .= "AND project.status = :status ";
                            $values[':status'] = $filters['status'];
                            $_SESSION['mailing']['filters_txt'] .= Text::_('en estado').' <strong>' . $status[$filters['status']] . '</strong> ';
                        } elseif ($filters['status'] < 0 && !empty($sqlInner)) {
                            $_SESSION['mailing']['filters_txt'] .= Text::_('en cualquier estado');
                        }

                        if ($filters['type'] == 'investor') {
                            if (!empty($filters['method']) && !empty($sqlInner)) {
                                $sqlFilter .= "AND invest.method = :method ";
                                $values[':method'] = $filters['method'];
                                $_SESSION['mailing']['filters_txt'] .= Text::_('mediante').' <strong>' . $methods[$filters['method']] . '</strong> ';
                            } elseif (empty($filters['method']) && !empty($sqlInner)) {
                                $_SESSION['mailing']['filters_txt'] .= Text::_('mediante cualquier metodo');
                            }
                        }

                        if (!empty($filters['interest'])) {
                            $sqlInner .= "INNER JOIN user_interest
                                    ON user_interest.user = user.id
                                    AND user_interest.interest = :interest
                                    ";
                            $values[':interest'] = $filters['interest'];
                            $_SESSION['mailing']['filters_txt'] .= Text::_('interesados en fin').' <strong>' . $interests[$filters['interest']] . '</strong> ';
                        }

                        if (!empty($filters['role'])) {
                            $sqlInner .= "INNER JOIN user_role
                                    ON user_role.user_id = user.id
                                    AND user_role.role_id = :role
                                    ";
                            $values[':role'] = $filters['role'];
                            $_SESSION['mailing']['filters_txt'] .= Text::_('que sean').' <strong>' . $roles[$filters['role']] . '</strong> ';
                        }

                        if (!empty($filters['name'])) {
                            $sqlFilter .= " AND ( user.name LIKE (:name) OR user.email LIKE (:name) ) ";
                            $values[':name'] = '%'.$filters['name'].'%';
                            $_SESSION['mailing']['filters_txt'] .= Text::_('que su nombre o email contenga').' <strong>\'' . $filters['name'] . '\'</strong> ';
                        }

                        if (!empty($filters['workshopper'])) {
                            $sqlFilter .= " AND user.password = SHA1(user.email) ";
                            $_SESSION['mailing']['filters_txt'] .= Text::_('que su contraseña sea igual que su email');
                        }

                        $sql = "SELECT
                                    user.id as id,
                                    user.name as name,
                                    user.email as email
                                    $sqlFields
                                FROM user
                                $sqlInner
                                WHERE user.id != 'root'
                                AND user.active = 1
                                $sqlFilter
                                GROUP BY user.id
                                ORDER BY user.name ASC
                                ";

//                        echo '<pre>'.$sql . '<br />'.print_r($values, 1).'</pre>';

                        if ($query = Model\User::query($sql, $values)) {
                            foreach ($query->fetchAll(\PDO::FETCH_OBJ) as $receiver) {
                                $_SESSION['mailing']['receivers'][$receiver->id] = $receiver;
                            }
                        } else {
                            $_SESSION['mailing']['errors'][] = Text::_('Fallo el SQL!!!!!').' <br />' . $sql . '<pre>'.print_r($values, 1).'</pre>';
                        }

                        // si no hay destinatarios, salta a la lista con mensaje de error
                        if (empty($_SESSION['mailing']['receivers'])) {
                            $_SESSION['mailing']['errors'][] = Text::_('No se han encontrado destinatarios para ') . $_SESSION['mailing']['filters_txt'];

                            throw new Redirection('/admin/mailing/list');
                        }

                        // si hay, mostramos el formulario de envio
                        return new View(
                            'view/admin/index.html.php',
                            array(
                                'folder'    => 'mailing',
                                'file'      => 'edit',
                                'filters'   => $_SESSION['mailing']['filters'],
//                                'projects'  => $projects,
                                'interests' => $interests,
                                'status'    => $status,
                                'types'     => $types,
                                'roles'     => $roles
                            )
                        );

                        break;
                    case 'send':
                        // Enviando contenido recibido a destinatarios recibidos
                        $users = array();
                        foreach ($_POST as $key=>$value) {
                            $matches = array();
                            \preg_match('#receiver_(\w+)#', $key, $matches);
//                            echo \trace($matches);
                            if (!empty($matches[1]) && !empty($_SESSION['mailing']['receivers'][$matches[1]]->email)) {
                                $users[] = $matches[1];
                            }
                        }

//                        $content = nl2br($_POST['content']);
                        $content = $_POST['content'];
                        $subject = $_POST['subject'];

                        // ahora, envio, el contenido a cada usuario
                        foreach ($users as $usr) {

                            $tmpcontent = \str_replace(
                                array('%USERID%', '%USEREMAIL%', '%USERNAME%', '%SITEURL%', '%PROJECTID%', '%PROJECTNAME%', '%PROJECTURL%'),
                                array(
                                    $usr,
                                    $_SESSION['mailing']['receivers'][$usr]->email,
                                    $_SESSION['mailing']['receivers'][$usr]->name,
                                    SITE_URL,
                                    $_SESSION['mailing']['receivers'][$usr]->projectId,
                                    $_SESSION['mailing']['receivers'][$usr]->project,
                                    SITE_URL.'/project/'.$_SESSION['mailing']['receivers'][$usr]->projectId
                                ),
                                $content);


                            $mailHandler = new Mail();

                            $mailHandler->to = $_SESSION['mailing']['receivers'][$usr]->email;
                            $mailHandler->toName = $_SESSION['mailing']['receivers'][$usr]->name;
                            // blind copy a goteo desactivado durante las verificaciones
            //              $mailHandler->bcc = 'comunicaciones@goteo.org';
                            $mailHandler->subject = $subject;
                            $mailHandler->content = '<br />'.$tmpcontent.'<br />';
                            $mailHandler->html = true;
                            $mailHandler->template = 11;
                            if ($mailHandler->send($errors)) {
                                $_SESSION['mailing']['receivers'][$usr]->ok = true;
                            } else {
                                $_SESSION['mailing']['receivers'][$usr]->ok = false;
                            }

                            unset($mailHandler);
                        }

                        /*
                         * Evento Feed
                         *
                        $log = new Feed();
                        $log->title = 'mailing a usuarios (admin)';
                        $log->url = '/admin/mailing';
                        $log->type = 'admin';
                        $log_text = "El admin %s ha %s una comunicación a usuarios";
                        $log_items = array(
                            Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                            Feed::item('relevant', 'Enviado')
                        );
                        $log->html = \vsprintf($log_text, $log_items);
                        $log->add($errors);
                        unset($log);
*/
                        return new View(
                            'view/admin/index.html.php',
                            array(
                                'folder'    => 'mailing',
                                'file'      => 'send',
                                'content'   => $content,
//                                'projects'  => $projects,
                                'interests' => $interests,
                                'status'    => $status,
                                'methods'   => $methods,
                                'types'     => $types,
                                'roles'     => $roles,
                                'users'     => $users,
                                'errors'    => $errors,
                                'success'   => $success
                            )
                        );

                        break;
                }
			}

            $errors = $_SESSION['mailing']['errors'];
            unset($_SESSION['mailing']['errors']);

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder'    => 'mailing',
                    'file'      => 'list',
//                    'projects'  => $projects,
                    'interests' => $interests,
                    'status'    => $status,
                    'methods'   => $methods,
                    'types'     => $types,
                    'roles'     => $roles,
                    'filters'   => $_SESSION['mailing']['filters'],
                    'errors'    => $errors
                )
            );
        }

        /*
         *  historial de emails enviados
         */
        public function sended($action = 'list') {

            $filters = array();
            $fields = array('user', 'template');
            foreach ($fields as $field) {
                if (isset($_GET[$field])) {
                    $filters[$field] = $_GET[$field];
                }
            }

            $BC = self::menu(array(
                'section' => 'users',
                'option' => __FUNCTION__,
                'action' => $action
            ));

            define('ADMIN_BCPATH', $BC);

            $templates = Template::getAllMini();

            $sended = Mail::getSended($filters);

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'sended',
                    'file' => 'list',
                    'filters' => $filters,
                    'templates' => $templates,
                    'sended' => $sended
                )
            );
        }

        /*
         * Niveles de meritocracia
         */
        public function worth($action = 'list', $id = null) {

            $BC = self::menu(array(
                'section' => 'users',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id
            ));

            define('ADMIN_BCPATH', $BC);

            $errors = array();

            if ($_SERVER['REQUEST_METHOD'] == 'POST' && $action == 'edit') {

                // instancia
                $data = array(
                    'id' => $_POST['id'],
                    'name' => $_POST['name'],
                    'amount' => $_POST['amount']
                );

				if (Worth::save($data, $errors)) {
                    $action = 'list';
                    $success[] = Text::_('Nivel de meritocracia modificado');

                    /*
                     * Evento Feed
                     */
                    $log = new Feed();
                    $log->title = Text::_('modificacion de meritocracia (admin)');
                    $log->url = '/admin/worth';
                    $log->type = 'admin';
                    $log_text = _("El admin %s ha %s el nivel de meritocrácia %s");
                    $log_items = array(
                        Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                        Feed::item('relevant', Text::_('Modificado')),
                        Feed::item('project', $icon->name)
                    );
                    $log->html = \vsprintf($log_text, $log_items);
                    $log->add($errors);
                    unset($log);
				}
				else {
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'worth',
                            'file' => 'edit',
                            'action' => 'edit',
                            'worth' => (object) $data,
                            'errors' => $errors
                        )
                    );
				}
			}

            switch ($action) {
                case 'edit':
                    $worth = Worth::getAdmin($id);

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'worth',
                            'file' => 'edit',
                            'action' => 'edit',
                            'worth' => $worth
                        )
                    );
                    break;
            }

            $worthcracy = Worth::getAll();

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'worth',
                    'file' => 'list',
                    'worthcracy' => $worthcracy,
                    'errors' => $errors,
                    'success' => $success
                )
            );
        }

        /*
         * Conteo de palabras
         */
        public function wordcount($action = 'list', $id = null) {

            $BC = self::menu(array(
                'section' => 'contents',
                'option' => __FUNCTION__,
                'action' => $action,
                'id' => $id
            ));

            define('ADMIN_BCPATH', $BC);

            $wordcount = array();

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'base',
                    'file' => 'wordcount',
                    'wordcount' => $wordcount
                )
            );
        }


        /*
         * Menu de secciones, opciones, acciones y config para el panel Admin
         *
         */
        private static function menu($BC = array()) {

            // si el breadcrumbs no es un array vacio,
            //   devolveremos el contenido html para pintar el camino de migas de pan
            //   con enlaces a lo anterior

            $menu = array(
                'contents' => array(
                    'label'   => Text::_('Gestión de Textos y Traducciones'),
                    'options' => array (
                        'blog' => array(
                            'label' => Text::_('Blog'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'add'  => array('label' => Text::_('Nueva Entrada'), 'item' => false),
                                'edit' => array('label' => Text::_('Editando Entrada'), 'item' => true),
                                'translate' => array('label' => Text::_('Traduciendo Entrada'), 'item' => true)
                            )
                        ),
                        'texts' => array(
                            'label' => Text::_('Textos interficie'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Editando Original'), 'item' => true),
                                'translate' => array('label' => Text::_('Traduciendo Texto'), 'item' => true)
                            )
                        ),
                        'faq' => array(
                            'label' => Text::_('FAQs'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'add'  => array('label' => Text::_('Nueva Pregunta'), 'item' => false),
                                'edit' => array('label' => Text::_('Editando Pregunta'), 'item' => true),
                                'translate' => array('label' => Text::_('Traduciendo Pregunta'), 'item' => true)
                            )
                        ),
                        'pages' => array(
                            'label' => Text::_('Páginas institucionales'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Editando Página'), 'item' => true),
                                'translate' => array('label' => Text::_('Traduciendo Página'), 'item' => true)
                            )
                        ),
                        'categories' => array(
                            'label' => Text::_('Categorias e Intereses'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'add'  => array('label' => Text::_('Nueva Categoría'), 'item' => false),
                                'edit' => array('label' => Text::_('Editando Categoría'), 'item' => true),
                                'translate' => array('label' => Text::_('Traduciendo Categoría'), 'item' => true)
                            )
                        ),
                        'licenses' => array(
                            'label' => Text::_('Licencias'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Editando Licencia'), 'item' => true),
                                'translate' => array('label' => Text::_('Traduciendo Licencia'), 'item' => true)
                            )
                        ),
                        'icons' => array(
                            'label' => Text::_('Tipos de Retorno'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Editando Tipo'), 'item' => true),
                                'translate' => array('label' => Text::_('Traduciendo Tipo'), 'item' => true)
                            )
                        ),
                        'tags' => array(
                            'label' => Text::_('Tags de blog'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'add'  => array('label' => Text::_('Nuevo Tag'), 'item' => false),
                                'edit' => array('label' => Text::_('Editando Tag'), 'item' => true),
                                'translate' => array('label' => Text::_('Traduciendo Tag'), 'item' => true)
                            )
                        ),
                        'criteria' => array(
                            'label' => Text::_('Criterios de revisión'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'add'  => array('label' => Text::_('Nuevo Criterio'), 'item' => false),
                                'edit' => array('label' => Text::_('Editando Criterio'), 'item' => true),
                                'translate' => array('label' => Text::_('Traduciendo Criterio'), 'item' => true)
                            )
                        ),
                        'templates' => array(
                            'label' => Text::_('Plantillas de email'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Editando Plantilla'), 'item' => true),
                                'translate' => array('label' => Text::_('Traduciendo Plantilla'), 'item' => true)
                            )
                        ),
                        'glossary' => array(
                            'label' => Text::_('Glosario'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Editando Término'), 'item' => true),
                                'translate' => array('label' => Text::_('Traduciendo Término'), 'item' => true)
                            )
                        ),
                        'info' => array(
                            'label' => Text::_('Ideas about'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Editando Idea'), 'item' => true),
                                'translate' => array('label' => Text::_('Traduciendo Idea'), 'item' => true)
                            )
                        ),
                        'wordcount' => array(
                            'label' => Text::_('Conteo de palabras'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false)
                            )
                        )
                    )
                ),
                'projects' => array(
                    'label'   => Text::_('Gestión de proyectos'),
                    'options' => array (
                        'projects' => array(
                            'label' => Text::_('Listado de proyectos'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'dates' => array('label' => Text::_('Cambiando las fechas del proyecto '), 'item' => true),
                                'accounts' => array('label' => Text::_('Gestionando las cuentas del proyecto '), 'item' => true)
                            )
                        ),
                        'reviews' => array(
                            'label' => Text::_('Revisiones'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'add'  => array('label' => Text::_('Iniciando briefing'), 'item' => false),
                                'edit' => array('label' => Text::_('Editando briefing'), 'item' => true),
                                'report' => array('label' => Text::_('Informe'), 'item' => true)
                            )
                        ),
                        'translates' => array(
                            'label' => Text::_('Traducciones'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'add'  => array('label' => Text::_('Habilitando traducción'), 'item' => false),
                                'edit' => array('label' => Text::_('Asignando traducción'), 'item' => true)
                            )
                        ),
                        'rewards' => array(
                            'label' => Text::_('Gestión de retornos colectivos cumplidos'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false)
                            )
                        )
                    )
                ),
                'users' => array(
                    'label'   => Text::_('Gestión de usuarios'),
                    'options' => array (
                        'users' => array(
                            'label' => Text::_('Listado de usuarios'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'add' => array('label' => Text::_('Creando Usuario'), 'item' => true),
                                'edit' => array('label' => Text::_('Editando Usuario'), 'item' => true),
                                'manage' => array('label' => Text::_('Gestionando Usuario'), 'item' => true),
                                'impersonate' => array('label' => Text::_('Suplantando al Usuario'), 'item' => true)
                            )
                        ),
                        'worth' => array(
                            'label' => Text::_('Niveles de meritocracia'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Editando Nivel'), 'item' => true)
                            )
                        ),
                        'mailing' => array(
                            'label' => Text::_('Comunicaciones'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Seleccionando destinatarios'), 'item' => false),
                                'edit' => array('label' => Text::_('Escribiendo contenido'), 'item' => false),
                                'send' => array('label' => Text::_('Comunicación enviada'), 'item' => false)
                            )
                        ),
                        'sended' => array(
                            'label' => Text::_('Historial envios'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Emails enviados'), 'item' => false)
                            )
                        )/*,
                        'useradd' => array(
                            'label' => 'Creación de usuarios',
                            'actions' => array(
                                'add'  => array('label' => 'Nuevo Usuario', 'item' => false)
                            )
                        ),
                        'usermod' => array(
                            'label' => 'Gestión de roles y nodos de Usuarios',
                            'actions' => array(
                                'list' => array('label' => 'Listando', 'item' => false),
                                'edit' => array('label' => 'Editando roles y nodos de Usuario', 'item' => true)
                            )
                        )*/
                    )
                ),
                'accounting' => array(
                    'label'   => Text::_('Gestión de aportes y transacciones'),
                    'options' => array (
                        'invests' => array(
                            'label' => Text::_('Aportes a Proyectos'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'add'  => array('label' => Text::_('Aporte manual'), 'item' => false),
                                'move'  => array('label' => Text::_('Reubicando el aporte'), 'item' => true),
                                'details' => array('label' => Text::_('Detalles del aporte'), 'item' => true),
                                'execute' => array('label' => Text::_('Ejecución del cargo ahora mismo'), 'item' => true),
                                'cancel' => array('label' => Text::_('Cancelando aporte'), 'item' => true),
                                'report' => array('label' => Text::_('Informe de proyecto'), 'item' => true)
                            )
                        ),
                        'accounts' => array(
                            'label' => Text::_('Transacciones económicas'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'details' => array('label' => Text::_('Detalles de la transacción'), 'item' => true),
                                'viewer' => array('label' => Text::_('Viendo logs'), 'item' => false)
                            )
                        )/*,
                        'credits' => array(
                            'label' => 'Gestión de crédito',
                            'actions' => array(
                                'list' => array('label' => 'Listando', 'item' => false),
                                'add'  => array('label' => 'Nuevo ', 'item' => false),
                                'edit' => array('label' => 'Editando Tag', 'item' => true),
                                'translate' => array('label' => 'Traduciendo Tag', 'item' => true)
                            )
                        )*/
                    )
                ),
                'home' => array(
                    'label'   => Text::_('Portada'),
                    'options' => array (
                        'news' => array(
                            'label' => Text::_('Micronoticias'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'add'  => array('label' => Text::_('Nueva Micronoticia'), 'item' => false),
                                'edit' => array('label' => Text::_('Editando Micronoticia'), 'item' => true),
                                'translate' => array('label' => Text::_('Traduciendo Micronoticia'), 'item' => true)
                            )
                        ),
                        'banners' => array(
                            'label' => Text::_('Banners'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'add'  => array('label' => Text::_('Nuevo Banner'), 'item' => false),
                                'edit' => array('label' => Text::_('Editando Banner'), 'item' => true),
                                'translate' => array('label' => Text::_('Traduciendo Banner'), 'item' => true)
                            )
                        ),
                        'posts' => array(
                            'label' => Text::_('Carrusel de blog'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Ordenando'), 'item' => false),
                                'add'  => array('label' => Text::_('Colocando Entrada en la portada'), 'item' => false)
                            )
                        ),
                        'promote' => array(
                            'label' => Text::_('Proyectos destacados'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'add'  => array('label' => Text::_('Nuevo Destacado'), 'item' => false),
                                'edit' => array('label' => Text::_('Editando Destacado'), 'item' => true),
                                'translate' => array('label' => Text::_('Traduciendo Destacado'), 'item' => true)
                            )
                        ),
                        'footer' => array(
                            'label' => Text::_('Entradas en el footer'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Ordenando'), 'item' => false),
                                'add'  => array('label' => Text::_('Colocando Entrada en el footer'), 'item' => false)
                            )
                        ),
                        'feed' => array(
                            'label' => Text::_('Actividad reciente'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false)
                            )
                        )
                    )
                ),
                'sponsors' => array(
                    'label'   => Text::_('Convocatorias de patrocinadores'),
                    'options' => array (
                        'sponsors' => array(
                            'label' => Text::_('Apoyos institucionales'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'add'  => array('label' => Text::_('Nuevo Patrocinador'), 'item' => false),
                                'edit' => array('label' => Text::_('Editando Patrocinador'), 'item' => true)
                            )
                        ),
                        'campaigns' => array(
                            'label' => Text::_('Gestión de campañas'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'add'  => array('label' => Text::_('Nueva Campaña'), 'item' => false),
                                'edit' => array('label' => Text::_('Editando Campaña'), 'item' => true),
                                'report' => array('label' => Text::_('Informe de estado de la Campaña'), 'item' => true)
                            )
                        )/*,
                        'nodes' => array(
                            'label' => 'Gestión de Nodos',
                            'actions' => array(
                                'list' => array('label' => 'Listando', 'item' => false),
                                'add'  => array('label' => 'Nuevo Nodo', 'item' => false),
                                'edit' => array('label' => 'Editando Nodo', 'item' => true)
                            )
                        )*/
                    )
                )
            );

            if (empty($BC)) {
                return $menu;
            } else {
                // Los últimos serán los primeros
                $path = '';
                
                // si el BC tiene Id, accion sobre ese registro
                // si el BC tiene Action
                if (!empty($BC['action'])) {

                    // si es una accion no catalogada, mostramos la lista
                    if (!in_array(
                            $BC['action'],
                            array_keys($menu[$BC['section']]['options'][$BC['option']]['actions'])
                        )) {
                        $BC['action'] = 'list';
                        $BC['id'] = null;
                    }

                    $action = $menu[$BC['section']]['options'][$BC['option']]['actions'][$BC['action']];
                    // si es de item , añadir el id (si viene)
                    if ($action['item'] && !empty($BC['id'])) {
                        $path = " &gt; <strong>{$action['label']}</strong> {$BC['id']}";
                    } else {
                        $path = " &gt; <strong>{$action['label']}</strong>";
                    }
                }

                // si el BC tiene Option, enlace a la portada de esa gestión
                if (!empty($BC['option'])) {
                    $option = $menu[$BC['section']]['options'][$BC['option']];
                    $path = ' &gt; <a href="/admin/'.$BC['option'].''.$BC['filter'].'">'.$option['label'].'</a>'.$path;
                }

                // si el BC tiene section, facil, enlace al admin
                if (!empty($BC['section'])) {
                    $section = $menu[$BC['section']];
                    $path = '<a href="/admin#'.$BC['section'].'">'.$section['label'].'</a>' . $path;
                }
                return $path;
            }


        }


	}

}
