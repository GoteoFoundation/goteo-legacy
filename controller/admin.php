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
        Goteo\Library\i18n\Lang,
        Goteo\Library\Page,
        Goteo\Library\Mail,
        Goteo\Library\Template,
        Goteo\Library\Message,
        Goteo\Library\Newsletter,
        Goteo\Library\Worth;

    class Admin extends \Goteo\Core\Controller {

        // Array de usuarios con permisos especiales
        static public function _supervisors() {
            return array(
                'supervisor' => array(
                    // paneles de admin permitidos
                    'texts',
                    'faq',
                    'pages',
                    'licenses',
                    'icons',
                    'tags',
                    'criteria',
                    'templates',
                    'glossary'
                )
            );
        }

        // Array de los gestores que existen
        static public function _options() {

            return array(
                'accounts' => array(
                    'label' => Text::_('Gestión de aportes'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listando'), 'item' => false),
                        'details' => array('label' => Text::_('Detalles del aporte'), 'item' => true),
                        'update' => array('label' => Text::_('Cambiando el estado al aporte'), 'item' => true),
                        'add' => array('label' => Text::_('Aporte manual'), 'item' => false),
                        'move' => array('label' => Text::_('Reubicando el aporte'), 'item' => true),
                        'execute' => array('label' => Text::_('Ejecución del cargo'), 'item' => true),
                        'cancel' => array('label' => Text::_('Cancelando aporte'), 'item' => true),
                        'report' => array('label' => Text::_('Informe de proyecto'), 'item' => true),
                        'viewer' => array('label' => Text::_('Viendo logs'), 'item' => false)
                    ),
                    'filters' => array('id' => '', 'methods' => '', 'investStatus' => 'all', 'projects' => '', 'name' => '', 'calls' => '', 'review' => '', 'types' => '', 'date_from' => '', 'date_until' => '', 'issue' => 'all', 'procStatus' => 'all', 'amount' => '')
                ),
                'banners' => array(
                    'label' => Text::_('Banners'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listando'), 'item' => false),
                        'add' => array('label' => Text::_('Nuevo Banner'), 'item' => false),
                        'edit' => array('label' => Text::_('Editando Banner'), 'item' => true),
                        'translate' => array('label' => Text::_('Traduciendo Banner'), 'item' => true)
                    )
                ),
                'blog' => array(
                    'label' => Text::_('Blog'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listando'), 'item' => false),
                        'add' => array('label' => Text::_('Nueva Entrada'), 'item' => false),
                        'edit' => array('label' => Text::_('Editando Entrada'), 'item' => true),
                        'translate' => array('label' => Text::_('Traduciendo Entrada'), 'item' => true),
                        'reorder' => array('label' => Text::_('Ordenando las entradas en Portada'), 'item' => false),
                        'footer' => array('label' => Text::_('Ordenando las entradas en el Footer'), 'item' => false)
                    ),
                    'filters' => array('show' => 'owned', 'blog' => '')
                ),
                'categories' => array(
                    'label' => Text::_('Categorías'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listando'), 'item' => false),
                        'add' => array('label' => Text::_('Nueva Categoría'), 'item' => false),
                        'edit' => array('label' => Text::_('Editando Categoría'), 'item' => true),
                        'translate' => array('label' => Text::_('Traduciendo Categoría'), 'item' => true),
                        'keywords' => array('label' => Text::_('Palabras clave'), 'item' => false)
                    )
                ),
                'criteria' => array(
                    'label' => Text::_('Criterios de revisión'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listando'), 'item' => false),
                        'add' => array('label' => Text::_('Nuevo Criterio'), 'item' => false),
                        'edit' => array('label' => Text::_('Editando Criterio'), 'item' => true),
                        'translate' => array('label' => Text::_('Traduciendo Criterio'), 'item' => true)
                    ),
                    'filters' => array('section' => 'project')
                ),
                'faq' => array(
                    'label' => Text::_('FAQs'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listando'), 'item' => false),
                        'add' => array('label' => Text::_('Nueva Pregunta'), 'item' => false),
                        'edit' => array('label' => Text::_('Editando Pregunta'), 'item' => true),
                        'translate' => array('label' => Text::_('Traduciendo Pregunta'), 'item' => true)
                    ),
                    'filters' => array('section' => 'node')
                ),
            'home' => array(
                'label' => Text::_('Elementos en portada'),
                'actions' => array(
                    'list' => array('label' => Text::_('Gestionando'), 'item' => false)
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
                'icons' => array(
                    'label' => Text::_('Tipos de Retorno'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listando'), 'item' => false),
                        'edit' => array('label' => Text::_('Editando Tipo'), 'item' => true),
                        'translate' => array('label' => Text::_('Traduciendo Tipo'), 'item' => true)
                    ),
                    'filters' => array('group' => '')
                ),
                'invests' => array(
                    'label' => Text::_('Aportes'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listando'), 'item' => false),
                        'details' => array('label' => Text::_('Detalles del aporte'), 'item' => true)
                    ),
                    'filters' => array('methods' => '', 'status' => 'all', 'investStatus' => 'all', 'projects' => '', 'name' => '', 'calls' => '', 'types' => '')
                ),
                'licenses' => array(
                    'label' => Text::_('Licencias'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listando'), 'item' => false),
                        'edit' => array('label' => Text::_('Editando Licencia'), 'item' => true),
                        'translate' => array('label' => Text::_('Traduciendo Licencia'), 'item' => true)
                    ),
                    'filters' => array('group' => '', 'icon' => '')
                ),
                'mailing' => array(
                    'label' => Text::_('Comunicaciones'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Seleccionando destinatarios'), 'item' => false),
                        'edit' => array('label' => Text::_('Escribiendo contenido'), 'item' => false),
                        'send' => array('label' => Text::_('Comunicación enviada'), 'item' => false)
                    ),
                    'filters' => array('project' => '', 'type' => '', 'status' => '-1', 'method' => '', 'interest' => '', 'role' => '', 'name' => '', 'donant' => '',
                    )
                ),
                'news' => array(
                    'label' => Text::_('Micronoticias'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listando'), 'item' => false),
                        'add' => array('label' => Text::_('Nueva Micronoticia'), 'item' => false),
                        'edit' => array('label' => Text::_('Editando Micronoticia'), 'item' => true),
                        'translate' => array('label' => Text::_('Traduciendo Micronoticia'), 'item' => true)
                    )
            ),
            'newsletter' => array(
                'label' => _('Boletín'),
                'actions' => array(
                    'list' => array('label' => _('Estado del envío automático'), 'item' => false),
                    'init' => array('label' => _('Iniciando un nuevo boletín'), 'item' => false),
                    'init' => array('label' => _('Viendo listado completo'), 'item' => true)
                )
                ),
                'pages' => array(
                    'label' => Text::_('Páginas'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listando'), 'item' => false),
                        'edit' => array('label' => Text::_('Editando Página'), 'item' => true),
                        'add' => array('label' => Text::_('Nueva Página'), 'item' => true),
                        'translate' => array('label' => Text::_('Traduciendo Página'), 'item' => true)
                    )
                ),
                'projects' => array(
                    'label' => Text::_('Gestión de proyectos'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listando'), 'item' => false),
                        'dates' => array('label' => Text::_('Fechas del proyecto'), 'item' => true),
                        'accounts' => array('label' => Text::_('Cuentas del proyecto'), 'item' => true),
                        'images' => array('label' => Text::_('Imágenes del proyecto'), 'item' => true),
                        'move' => array('label' => Text::_('Moviendo a otro Nodo el proyecto'), 'item' => true),
                        'assign' => array('label' => Text::_('Asignando a una Convocatoria el proyecto'), 'item' => true),
                        'report' => array('label' => Text::_('Informe Financiero del proyecto'), 'item' => true),
                        'rebase' => array('label' => Text::_('Cambiando Id de proyecto'), 'item' => true)
                    ),
                    'filters' => array('status' => '-1', 'category' => '', 'proj_name' => '', 'name' => '', 'node' => '', 'called' => '', 'order' => '')
                ),
                'promote' => array(
                    'label' => Text::_('Proyectos destacados'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listando'), 'item' => false),
                        'add' => array('label' => Text::_('Nuevo Destacado'), 'item' => false),
                        'edit' => array('label' => Text::_('Editando Destacado'), 'item' => true),
                        'translate' => array('label' => Text::_('Traduciendo Destacado'), 'item' => true)
                    )
                ),
                'recent' => array(
                    'label' => Text::_('Actividad reciente'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listando'), 'item' => false)
                    )
                ),
                'reviews' => array(
                    'label' => Text::_('Revisiones'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listando'), 'item' => false),
                        'add' => array('label' => Text::_('Iniciando briefing'), 'item' => false),
                        'edit' => array('label' => Text::_('Editando briefing'), 'item' => true),
                        'report' => array('label' => Text::_('Informe'), 'item' => true)
                    ),
                    'filters' => array('project' => '', 'status' => 'open', 'checker' => '')
                ),
                'rewards' => array(
                    'label' => Text::_('Recompensas'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listando'), 'item' => false),
                        'edit' => array('label' => Text::_('Gestionando recompensa'), 'item' => true)
                    ),
                    'filters' => array('project' => '', 'name' => '', 'status' => '')
                ),
                'sended' => array(
                    'label' => Text::_('Historial envíos'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Emails enviados'), 'item' => false)
                    ),
                    'filters' => array('user' => '', 'template' => '', 'node' => '', 'date_from' => '', 'date_until' => '')
                ),
                'sponsors' => array(
                    'label' => Text::_('Apoyos institucionales'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listando'), 'item' => false),
                        'add' => array('label' => Text::_('Nuevo Patrocinador'), 'item' => false),
                        'edit' => array('label' => Text::_('Editando Patrocinador'), 'item' => true)
                    )
                ),
                'tags' => array(
                    'label' => Text::_('Tags de blog'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listando'), 'item' => false),
                        'add' => array('label' => Text::_('Nuevo Tag'), 'item' => false),
                        'edit' => array('label' => Text::_('Editando Tag'), 'item' => true),
                        'translate' => array('label' => Text::_('Traduciendo Tag'), 'item' => true)
                    )
                ),
                'templates' => array(
                    'label' => Text::_('Plantillas de email'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listando'), 'item' => false),
                        'edit' => array('label' => Text::_('Editando Plantilla'), 'item' => true),
                        'translate' => array('label' => Text::_('Traduciendo Plantilla'), 'item' => true)
                    ),
                    'filters' => array('group' => '', 'name' => '')
                ),
                'texts' => array(
                    'label' => Text::_('Textos interficie'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listando'), 'item' => false),
                        'edit' => array('label' => Text::_('Editando Original'), 'item' => true),
                        'translate' => array('label' => Text::_('Traduciendo Texto'), 'item' => true)
                    ),
                    'filters' => array('group' => '', 'text' => '')
                ),
                'translates' => array(
                    'label' => Text::_('Traducciones de proyectos'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listando'), 'item' => false),
                        'add' => array('label' => Text::_('Habilitando traducción'), 'item' => false),
                        'edit' => array('label' => Text::_('Asignando traducción'), 'item' => true)
                    ),
                    'filters' => array('owner' => '', 'translator' => '')
                ),
                'users' => array(
                    'label' => Text::_('Gestión de usuarios'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listando'), 'item' => false),
                        'add' => array('label' => Text::_('Creando Usuario'), 'item' => true),
                        'edit' => array('label' => Text::_('Editando Usuario'), 'item' => true),
                        'manage' => array('label' => Text::_('Gestionando Usuario'), 'item' => true),
                        'impersonate' => array('label' => Text::_('Suplantando al Usuario'), 'item' => true),
                        'move' => array('label' => Text::_('Moviendo a otro Nodo el usuario '), 'item' => true)
                    ),
                    'filters' => array('interest' => '', 'role' => '', 'node' => '', 'id' => '', 'name' => '', 'order' => '', 'project' => '', 'type' => '')
                ),
                'worth' => array(
                    'label' => Text::_('Niveles de meritocracia'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listando'), 'item' => false),
                        'edit' => array('label' => Text::_('Editando Nivel'), 'item' => true)
                    )
                )
            );
        }

        // preparado para index unificado
        public function index($option = 'index', $action = 'list', $id = null, $subaction = null) {
            if ($option == 'index') {
                $BC = self::menu(array('option' => $option, 'action' => null, 'id' => null));
                define('ADMIN_BCPATH', $BC);
                $tasks = Model\Task::getAll(array(), null, true);
                return new View('view/admin/index.html.php', array('tasks' => $tasks));
            } else {
                $BC = self::menu(array('option' => $option, 'action' => $action, 'id' => $id));
                define('ADMIN_BCPATH', $BC);
                $SubC = 'Goteo\Controller\Admin' . \chr(92) . \ucfirst($option);
                return $SubC::process($action, $id, self::setFilters($option), $subaction);
            }
        }

        // Para marcar tareas listas (solo si tiene módulo Tasks implementado)
        public function done($id) {
            $errors = array();
            if (!empty($id) && isset($_SESSION['user']->id)) {
                $task = Model\Task::get($id);
                if ($task->setDone($errors)) {
                    Message::Info('La tarea se ha marcado como realizada');
                } else {
                    Message::Error(implode('<br />', $errors));
                }
            } else {
                Message::Error('Faltan datos');
            }
            throw new Redirection('/admin');
        }


        /*
         * Menu de secciones, opciones, acciones y config para el panel Admin
         */

        public static function menu($BC = array()) {

            $admin_label = Text::_('Admin');

            $options = static::_options();
            $supervisors = static::_supervisors();

            // El menu del panel admin dependerá del rol del usuario que accede
            // Superadmin = todo
            // Admin = contenidos de Nodo
            // Supervisor = menus especiales
            if (isset($supervisors[$_SESSION['user']->id])) {
                $menu = self::setMenu('supervisor', $_SESSION['user']->id);
            } elseif (isset($_SESSION['user']->roles['admin'])) {
                $menu = self::setMenu('admin', $_SESSION['user']->id);
            } else {
                $menu = self::setMenu('superadmin', $_SESSION['user']->id);
            }

            // si el breadcrumbs no es un array vacio,
            // devolveremos el contenido html para pintar el camino de migas de pan
            // con enlaces a lo anterior
            if (empty($BC)) {
                return $menu;
            } else {

                // a ver si puede estar aqui!
                if ($BC['option'] != 'index') {
                    $puede = false;
                    foreach ($menu as $sCode => $section) {
                        if (isset($section['options'][$BC['option']])) {
                            $puede = true;
                            break;
                        }
                    }

                    if (!$puede) {
                        Message::Error(Text::get('admin-no_permission', $options[$BC['option']]['label']));
                        throw new Redirection('/admin');
                    }
                }

                // Los últimos serán los primeros
                $path = '';

                // si el BC tiene Id, accion sobre ese registro
                // si el BC tiene Action
                if (!empty($BC['action']) && $BC['action'] != 'list') {

                    // si es una accion no catalogada, mostramos la lista
                    if (!in_array($BC['action'], array_keys($options[$BC['option']]['actions']))) {
                        $BC['action'] = '';
                        $BC['id'] = null;
                    }

                    $action = $options[$BC['option']]['actions'][$BC['action']];
                    // si es de item , añadir el id (si viene)
                    if ($action['item'] && !empty($BC['id'])) {
                        $path = " &gt; <strong>{$action['label']}</strong> {$BC['id']}";
                    } else {
                        $path = " &gt; <strong>{$action['label']}</strong>";
                    }
                }

                // si el BC tiene Option, enlace a la portada de esa gestión (a menos que sea laaccion por defecto)
                if (!empty($BC['option']) && isset($options[$BC['option']])) {
                    $option = $options[$BC['option']];
                    if ($BC['action'] == 'list') {
                        $path = " &gt; <strong>{$option['label']}</strong>";
                    } else {
                        $path = ' &gt; <a href="/admin/' . $BC['option'] . '">' . $option['label'] . '</a>' . $path;
                    }
                }

                // si el BC tiene section, facil, enlace al admin
                if ($BC['option'] == 'index') {
                    $path = "<strong>{$admin_label}</strong>";
                } else {
                    $path = '<a href="/admin">' . $admin_label . '</a>' . $path;
                }

                return $path;
            }
        }

        /*
         * Si no tenemos filtros para este gestor los cogemos de la sesion
         */

        private static function setFilters($option) {

            $options = static::_options();

            // arary de fltros para el sub controlador
            $filters = array();

            if (isset($_GET['reset']) && $_GET['reset'] == 'filters') {
                unset($_SESSION['admin_filters'][$option]);
                unset($_SESSION['admin_filters']['main']);
                foreach ($options[$option]['filters'] as $field => $default) {
                    $filters[$field] = $default;
                }
                return $filters;
            }

            // si hay algun filtro
            $filtered = false;

            // filtros de este gestor:
            // para cada uno tenemos el nombre del campo y el valor por defecto
            foreach ($options[$option]['filters'] as $field => $default) {
                if (isset($_GET[$field])) {
                    // si lo tenemos en el get, aplicamos ese a la sesión y al array
                    $filters[$field] = (string) $_GET[$field];
                    $_SESSION['admin_filters'][$option][$field] = (string) $_GET[$field];
                    if (($option == 'reports' && $field == 'user')
                            || ($option == 'projects' && $field == 'user')
                            || ($option == 'users' && $field == 'name')
                            || ($option == 'accounts' && $field == 'name')
                            || ($option == 'rewards' && $field == 'name')) {

                        $_SESSION['admin_filters']['main']['user_name'] = (string) $_GET[$field];
                    }
                    $filtered = true;
                } elseif (!empty($_SESSION['admin_filters'][$option][$field])) {
                    // si no lo tenemos en el get, cogemos de la sesion pero no lo pisamos
                    $filters[$field] = $_SESSION['admin_filters'][$option][$field];
                    $filtered = true;
                } else {
                    // a ver si tenemos un filtro equivalente
                    switch ($option) {
                        case 'projects':
                            if ($field == 'name' && !empty($_SESSION['admin_filters']['main']['user_name'])) {
                                $filters['name'] = $_SESSION['admin_filters']['main']['user_name'];
                                $filtered = true;
                            }
                            break;
                        case 'users':
                            if ($field == 'name' && !empty($_SESSION['admin_filters']['main']['user_name'])) {
                                $filters['name'] = $_SESSION['admin_filters']['main']['user_name'];
                                $filtered = true;
                            }
                            break;
                        case 'accounts':
                            if ($field == 'name' && !empty($_SESSION['admin_filters']['main']['user_name'])) {
                                $filters['name'] = $_SESSION['admin_filters']['main']['user_name'];
                                $filtered = true;
                            }
                            break;
                        case 'rewards':
                            if ($field == 'name' && !empty($_SESSION['admin_filters']['main']['user_name'])) {
                                $filters['name'] = $_SESSION['admin_filters']['main']['user_name'];
                                $filtered = true;
                            }
                            break;
                    }

                    // si no tenemos en sesion, ponemos el valor por defecto
                    if (empty($filters[$field])) {
                        $filters[$field] = $default;
                    }
                }
            }

            if ($filtered) {
                $filters['filtered'] = 'yes';
            }

            return $filters;
        }

        /*
         * Diferentes menus para diferentes perfiles
         */

        public static function setMenu($role, $user = null) {

            $options = static::_options();
            $supervisors = static::_supervisors();

            $labels = array();
            $labels['contents'] = Text::_('Contenidos');
            $labels['projects'] = Text::_('Proyectos');
            $labels['users'] = Text::_('Usuarios');
            $labels['home'] = Text::_('Portada');
            $labels['texts'] = Text::_('Textos y Traducciones');
            $labels['services'] = Text::_('Servicios');

            switch ($role) {
                case 'supervisor':
                    $menu = array(
                        'contents' => array(
                            'label' => $labels['contents'],
                            'options' => array()
                        )
                    );

                    foreach ($supervisors[$user] as $opt) {
                        $menu['contents']['options'][$opt] = $options[$opt];
                    }

                    break;
                case 'admin':
                    $menu = array(
                        'contents' => array(
                            'label' => $labels['contents'],
                            'options' => array(
                                'pages' => $options['pages'], // páginas institucionales del nodo
                                'blog' => $options['blog'], // entradas del blog
                                'banners' => $options['banners']    // banners del nodo
                            )
                        ),
                        'projects' => array(
                            'label' => $labels['projects'],
                            'options' => array(
                                'projects' => $options['projects'], // proyectos del nodo
                                'reviews' => $options['reviews'], // revisiones de proyectos del nodo
                                'translates' => $options['translates'], // traducciones de proyectos del nodo
                                'invests' => $options['invests'], // gestión de aportes avanzada
                            )
                        ),
                        'users' => array(
                            'label' => $labels['users'],
                            'options' => array(
                                'users' => $options['users'], // usuarios asociados al nodo
                                'mailing' => $options['mailing'], // comunicaciones del nodoc on sus usuarios / promotores
                                'sended' => $options['sended'], // historial de envios realizados por el nodo,
                                'tasks' => $options['tasks']  // gestión de tareas
                            )
                        ),
                        'home' => array(
                            'label' => $labels['home'],
                            'options' => array(
                                'home' => $options['home'], // elementos en portada
                                'promote' => $options['promote'], // seleccion de proyectos destacados
                                'blog' => $options['blog'], // entradas de blog (en la gestion de blog)
                                'sponsors' => $options['sponsors'], // patrocinadores del nodo
                                'recent' => $options['recent'] // feed admin
                            )
                        )
                    );

                    break;
                case 'superadmin':
                    $menu = array(
                        'contents' => array(
                            'label' => $labels['texts'],
                            'options' => array(
                                'blog' => $options['blog'],
                                'texts' => $options['texts'],
                                'faq' => $options['faq'],
                                'pages' => $options['pages'],
                                'categories' => $options['categories'],
                                'licenses' => $options['licenses'],
                                'icons' => $options['icons'],
                                'tags' => $options['tags'],
                                'criteria' => $options['criteria'],
                                'templates' => $options['templates'],
                                'glossary' => $options['glossary'],
                            )
                        ),
                        'projects' => array(
                            'label' => $labels['projects'],
                            'options' => array(
                                'projects' => $options['projects'],
                                'accounts' => $options['accounts'],
                                'reviews' => $options['reviews'],
                                'translates' => $options['translates'],
                                'rewards' => $options['rewards'],
                            )
                        ),
                        'users' => array(
                            'label' => $labels['users'],
                            'options' => array(
                                'users' => $options['users'],
                                'worth' => $options['worth'],
                                'mailing' => $options['mailing'],
                                'sended' => $options['sended'],
                                'tasks' => $options['tasks']
                            )
                        ),
                        'home' => array(
                            'label' => $labels['home'],
                            'options' => array(
                                'news' => $options['news'],
                                'banners' => $options['banners'],
                                'blog' => $options['blog'],
                                'promote' => $options['promote'],
                                'footer' => $options['footer'],
                                'recent' => $options['recent'],
                                'home' => $options['home']
                            )
                        ),
                        'sponsors' => array(
                            'label' => $labels['services'],
                            'options' => array(
                                'newsletter' => $options['newsletter'],
                                'sponsors' => $options['sponsors'],
                                'tasks' => $options['tasks']  // gestión de tareas
                            )
                        )
                    );
                    break;
            }

            return $menu;
        }

    }

}
