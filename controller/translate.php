<?php
/*
 *  Copyright (C) 2012 Platoniq y Fundación Goteo (see README for details)
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
	    Goteo\Library\Feed,
	    Goteo\Library\Message,
	    Goteo\Library\Text,
	    Goteo\Library\Page,
	    Goteo\Library\Content,
		Goteo\Library\i18n\Lang;

	class Translate extends \Goteo\Core\Controller {

        /*
         * Para traducir contenidos de nodo, especial: $action = id del nodo; $id = tabla, $auxAction = action, $contentId = registro
         */
        public function index ($table = '', $action = 'list', $id = null, $auxAction = 'list', $contentId = null) {

            $_SESSION['user']->translangs = Model\User\Translate::getLangs($_SESSION['user']->id);
            if (empty($_SESSION['user']->translangs)) {
                Message::Error(Text::_('No tienes ningún idioma, contacta con el administrador'));
                throw new Redirection('/dashboard');
            }

            if (empty($_SESSION['translate_lang']) || !isset($_SESSION['user']->translangs[$_SESSION['translate_lang']])) {
                if (count($_SESSION['user']->translangs) > 1 && isset($_SESSION['user']->translangs['en'])) {
                    $_SESSION['translate_lang'] = 'en';
                } else {
                    $_SESSION['translate_lang'] = current(array_keys($_SESSION['user']->translangs));
                }
            }

            if ($table == '') {
                return new View('view/translate/index.html.php', array('menu'=>self::menu()));
            }

            // para el breadcrumbs segun el contenido
            $section = ($table == 'news' || $table == 'promote') ? 'home' : 'contents';

            // muy especial para traducción de nodo
            if ($table == 'node') {
                $BC = self::menu(array(
                    'section' => 'node',
                    'node' => $action,
                    'option' => $id,
                    'action' => $auxAction,
                    'id' => $contentId
                ));
            } else {
                $BC = self::menu(array(
                    'section' => $section,
                    'option' => $table,
                    'action' => $action,
                    'id' => $id
                ));
            }

            define('ADMIN_BCPATH', $BC);

            $errors = array();

            // la operación según acción
            switch($table)  {
                case 'texts':
                    // comprobamos los filtros
                    $filters = array();
                    $fields = array('group', 'text', 'pending');
                    if (!isset($_GET['pending'])) $_GET['pending'] = 0;
                    foreach ($fields as $field) {
                        if (isset($_GET[$field])) {
                            $filters[$field] = $_GET[$field];
                            $_SESSION['translate_filters']['texts'][$field] = (string) $_GET[$field];
                        } elseif (!empty($_SESSION['translate_filters']['texts'][$field])) {
                            // si no lo tenemos en el get, cogemos de la sesion pero no lo pisamos
                            $filters[$field] = $_SESSION['translate_filters']['texts'][$field];
                        }
                    }

                    $filter = "?group={$filters['group']}&text={$filters['text']}&pending={$filters['pending']}";

                    // si llega post, vamos a guardar los cambios
                    if ($action == 'edit' && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {

                        if (Text::save(array(
                                        'id'   => $id,
                                        'text' => $_POST['text'],
                                        'lang' => $_POST['lang']
                                    ), $errors)) {

                            // Evento Feed
                            /*
                            $log = new Feed();
                            $log->populate('texto traducido (traductor)', '/translate/texts',
                                \vsprintf('El traductor %s ha %s el texto %s al %s', array(
                                    Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                    Feed::item('relevant', 'Traducido'),
                                    Feed::item('blog', $id),
                                    Feed::item('relevant', Lang::get($_POST['lang'])->name)
                            )));
                            $log->doAdmin('admin');
                            unset($log);
                            */
                            
                            Message::Info('Texto <strong>'.$id.'</strong> traducido correctamente al <strong>'.Lang::get($_POST['lang'])->name.'</strong>');

                            throw new Redirection("/translate/texts/$filter&page=".$_GET['page']);
                        } else {
                            // Evento Feed
                            $log = new Feed();
                            $log->setTarget($_SESSION['user']->id, 'user');
                            $log->populate('texto traducido (traductor)', '/translate/texts',
                                \vsprintf('Al traductor %s  le ha %s el texto %s al %s', array(
                                    Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                    Feed::item('relevant', 'Fallado al traducir'),
                                    Feed::item('blog', $id),
                                    Feed::item('relevant', Lang::get($_POST['lang'])->name)
                            )));
                            $log->doAdmin('admin');
                            unset($log);

                            Message::Error('Ha habido algun ERROR al traducir el Texto <strong>'.$id.'</strong> al <strong>'.Lang::get($_POST['lang'])->name.'</strong><br />' . implode('<br />', $errors));
                        }
                    }

                    // sino, mostramos la lista
                    return new View(
                        'view/translate/index.html.php',
                        array(
                            'section' => 'texts',
                            'action'  => $action,
                            'id'      => $id,
                            'filter' => $filter,
                            'filters' => $filters,
                            'errors'  => $errors
                        )
                     );
                    break;

                case 'node':
                    // parametros especiales
                    $node = $action;
                    $action = $auxAction;
                    $contentTable = $id;

                    // si llega post, vamos a guardar los cambios
                    if ($action == 'edit' && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {

                        switch ($contentTable) {
                            case 'banner':
                                if (Content::save(array(
                                                'id'   => $contentId,
                                                'table' => $contentTable,
                                                'title' => $_POST['title'],
                                                'description' => $_POST['description'],
                                                'lang' => $_POST['lang']
                                            ), $errors)) {
                                    Message::Info('El Banner <strong>'.$contentId.'</strong> del nodo <strong>'.$node.'</strong> traducido correctamente al <strong>'.Lang::get($_POST['lang'])->name.'</strong>');
                                    throw new Redirection("/translate/node/$node/$contentTable/list");
                                } else {
                                    Message::Error('Ha habido algun ERROR al traducir el Banner <strong>'.$contentId.'</strong> del nodo <strong>'.$node.'</strong> al <strong>'.Lang::get($_POST['lang'])->name.'</strong><br />' . implode('<br />', $errors));
                                }
                                break;
                            case 'page':
                                $page = Page::get($contentId, $node);
                                if ($page->update(
                                        $contentId, $_POST['lang'], $node,
                                        $_POST['name'], $_POST['description'], $_POST['content'],
                                        $errors)) {
                                    Message::Info('La página <strong>'.$contentId.'</strong> del nodo <strong>'.$node.'</strong> traducido correctamente al <strong>'.Lang::get($_POST['lang'])->name.'</strong>');
                                    throw new Redirection("/translate/node/$node/$contentTable/list");
                                } else {
                                    Message::Error('Ha habido algun ERROR al traducir la página <strong>'.$contentId.'</strong> del nodo <strong>'.$node.'</strong> al <strong>'.Lang::get($_POST['lang'])->name.'</strong><br />' . implode('<br />', $errors));
                                }
                                break;
                            case 'post':
                                if (Content::save(array(
                                                'id'   => $contentId,
                                                'table' => $contentTable,
                                                'title' => $_POST['title'],
                                                'text' => $_POST['text'],
                                                'legend' => $_POST['legend'],
                                                'lang' => $_POST['lang']
                                            ), $errors)) {
                                    Message::Info('La entrada <strong>'.$contentId.'</strong> del nodo <strong>'.$node.'</strong> traducido correctamente al <strong>'.Lang::get($_POST['lang'])->name.'</strong>');
                                    throw new Redirection("/translate/node/$node/$contentTable/list");
                                } else {
                                    Message::Error('Ha habido algun ERROR al traducir la Entrada <strong>'.$contentId.'</strong> del nodo <strong>'.$node.'</strong> al <strong>'.Lang::get($_POST['lang'])->name.'</strong><br />' . implode('<br />', $errors));
                                }
                                break;
                            default:
                                $node = Model\Node::get($node);
                                $node->lang_lang = $_SESSION['translate_lang'];
                                $node->subtitle_lang = $_POST['subtitle'];
                                $node->description_lang = $_POST['description'];
                                if ($node->updateLang($errors)) {
                                    Message::Info('La Descripción del nodo <strong>'.$node->id.'</strong> traducido correctamente al <strong>'.Lang::get($_POST['lang'])->name.'</strong>');
                                    throw new Redirection("/translate/node/$node->id");
                                } else {
                                    Message::Error('Ha habido algun ERROR al traducir la Descripción del nodo <strong>'.$node->id.'</strong> al <strong>'.Lang::get($_POST['lang'])->name.'</strong><br />' . implode('<br />', $errors));
                                }
                                
                        }

                        return new View(
                            'view/translate/index.html.php',
                            array(
                                'section' => 'node',
                                'action'  => 'edit_'.$contentTable,
                                'option'  => $contentTable,
                                'id'      => $contentId,
                                'node'    => $node
                            )
                         );

                    } elseif ($action == 'edit') {
                        return new View(
                            'view/translate/index.html.php',
                            array(
                                'section' => 'node',
                                'action'  => 'edit_'.$contentTable,
                                'option'  => $contentTable,
                                'id'      => $contentId,
                                'node'    => $node
                            )
                         );
                    } elseif ($contentTable == 'data') {
                        return new View(
                            'view/translate/index.html.php',
                            array(
                                'section' => 'node',
                                'action'  => 'edit_'.$contentTable,
                                'option'  => $contentTable,
                                'id'      => $node,
                                'node'    => $node
                            )
                         );
                    } else {
                        // sino, mostramos la lista
                        return new View(
                            'view/translate/index.html.php',
                            array(
                                'section' => 'node',
                                'action'  => 'list_'.$contentTable,
                                'option'  => $contentTable,
                                'node'    => $node
                            )
                         );
                    }

                    break;
                case 'pages':
                    // si llega post, vamos a guardar los cambios
                    if ($action == 'edit' && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {
                        if (Page::update($id, $_POST['lang'], $_POST['node'], $_POST['name'], $_POST['description'], $_POST['content'], $errors)) {

                            Message::Info('Contenido de la Pagina <strong>'.$id.'</strong> traducido correctamente al <strong>'.Lang::get($_POST['lang'])->name.'</strong>');

                            throw new Redirection("/translate/pages");
                        } else {
                            // Evento Feed
                            $log = new Feed();
                            $log->setTarget($_SESSION['user']->id, 'user');
                            $log->populate('pagina traducida (traductor)', '/translate/pages',
                                \vsprintf('Al traductor %s le ha %s la página %s del nodo %s al %s', array(
                                Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                Feed::item('relevant', 'Fallado al traducir'),
                                Feed::item('blog', $id),
                                Feed::item('blog', $_POST['node']),
                                Feed::item('relevant', Lang::get($_POST['lang'])->name)
                            )));
                            $log->doAdmin('admin');
                            unset($log);

                            Message::Error('Ha habido algun ERROR al traducir el contenido de la pagina <strong>'.$id.'</strong> al <strong>'.Lang::get($_POST['lang'])->name.'</strong><br />' . implode('<br />', $errors));
                        }
                    }


                    // sino, mostramos la lista
                    return new View(
                        'view/translate/index.html.php',
                        array(
                            'section' => 'pages',
                            'action' => $action,
                            'id' => $id,
                            'errors'=>$errors
                        )
                     );
                    break;
                default:
                    // comprobamos los filtros
                    $filters = array();
                    $fields = array('type', 'text', 'pending');
                    if (!isset($_GET['pending'])) $_GET['pending'] = 0;
                    foreach ($fields as $field) {
                        if (isset($_GET[$field])) {
                            $filters[$field] = $_GET[$field];
                            $_SESSION['translate_filters']['contents'][$field] = (string) $_GET[$field];
                        } elseif (!empty($_SESSION['translate_filters']['contents'][$field])) {
                            // si no lo tenemos en el get, cogemos de la sesion pero no lo pisamos
                            $filters[$field] = $_SESSION['translate_filters']['contents'][$field];
                        }
                    }

                    $filter = "?type={$filters['type']}&text={$filters['text']}&pending={$filters['pending']}";

                    // si llega post, vamos a guardar los cambios
                    if ($action == 'edit' && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {

                        if (!in_array($table, \array_keys(Content::_tables()))) {
                            $errors[] = "Tabla $table desconocida";
                            break;
                        }

                        if (Content::save($_POST, $errors)) {

                            // Evento Feed
                            /*
                            $log = new Feed();
                            $log->populate('contenido traducido (traductor)', '/translate/'.$table,
                                \vsprintf('El traductor %s ha %s el contenido del registro %s de la tabla %s al %s', array(
                                Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                Feed::item('relevant', 'Traducido'),
                                Feed::item('blog', $id),
                                Feed::item('blog', $table),
                                Feed::item('relevant', Lang::get($_SESSION['translate_lang'])->name)
                            )));
                            $log->doAdmin('admin');
                            unset($log);
                            */

                            Message::Info('Contenido del registro <strong>'.$id.'</strong> de la tabla <strong>'.$table.'</strong> traducido correctamente al <strong>'.Lang::get($_POST['lang'])->name.'</strong>');

                            if (isset($_SESSION['translate_node'])) {
                                throw new Redirection('/dashboard/translates/'.$table.'s');
                            }

                            throw new Redirection("/translate/$table/$filter&page=".$_GET['page']);
                        } else {
                            // Evento Feed
                            $log = new Feed();
                            $log->setTarget($_SESSION['user']->id, 'user');
                            $log->populate('contenido traducido (traductor)', '/translate/'.$table,
                                \vsprintf('El traductor %s le ha %s el contenido del registro %s de la tabla %s al %s', array(
                                Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                Feed::item('relevant', 'Fallado al traducir'),
                                Feed::item('blog', $id),
                                Feed::item('blog', $table),
                                Feed::item('relevant', Lang::get($_SESSION['translate_lang'])->name)
                            )));
                            $log->doAdmin('admin');
                            unset($log);

                            Message::Error('Ha habido algun ERROR al traducir el contenido del registro <strong>'.$id.'</strong> de la tabla <strong>'.$table.'</strong> al <strong>'.Lang::get($_POST['lang'])->name.'</strong><br />' . implode('<br />', $errors));
                        }
                    }

                    // sino, mostramos la lista
                    return new View(
                        'view/translate/index.html.php',
                        array(
                            'section' => 'contents',
                            'action'  => $action,
                            'table'  => $table,
                            'id'      => $id,
                            'filter' => $filter,
                            'filters' => $filters,
                            'errors'  => $errors
                        )
                     );
            }

            // si no pasa nada de esto, a la portada
            return new View('view/translate/index.html.php', array('menu'=>self::menu()));
        }

        public function select ($section = '', $action = '', $id = null, $extraAction = null, $extraId = null) {

            $_SESSION['translate_lang'] = isset($_POST['lang']) ? $_POST['lang'] : null;

            if (!empty($section) && !empty($action)) {

                if ($section == 'node') {
                    throw new Redirection("/translate/$section/$action/$id/$extraAction/$extraId");
                }

                $filter = "?type={$_GET['type']}&text={$_GET['text']}";

                throw new Redirection("/translate/$section/$action/$id/$filter&page=".$_GET['page']);
            } else {
                return new View('view/translate/index.html.php', array('menu'=>self::menu()));
            }
        }

        /*
         *  Menu de secciones, opciones, acciones y config para el panel Translate
         *
         *  ojo! cambian las options para ser directamente el nombre de la tabla menos para textos y contenidos de página
         * cambian tambien las actions solo list y edit (que es editar la traducción)
         */
        private static function menu($BC = array()) {

            // si el breadcrumbs no es un array vacio,
            //   devolveremos el contenido html para pintar el camino de migas de pan
            //   con enlaces a lo anterior

            $menu = array(
                'contents' => array(
                    'label'   => Text::_('Gestión de Textos y Traducciones'),
                    'options' => array (
                        'banner' => array(
                            'label' => Text::_('Banners'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Traduciendo Banner'), 'item' => true)
                            )
                        ),
                        'post' => array(
                            'label' => Text::_('Blog'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Traduciendo Entrada'), 'item' => true)
                            )
                        ),
                        'texts' => array(
                            'label' => Text::_('Textos interficie'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Traduciendo Texto'), 'item' => true)
                            )
                        ),
                        'faq' => array(
                            'label' => Text::_('FAQs'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Traduciendo Pregunta'), 'item' => true)
                            )
                        ),
                        'pages' => array(
                            'label' => Text::_('Contenidos institucionales'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Traduciendo contenido de Página'), 'item' => true)
                            )
                        ),
                        'category' => array(
                            'label' => Text::_('Categorias e Intereses'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Traduciendo Categoría'), 'item' => true)
                            )
                        ),
                        'license' => array(
                            'label' => Text::_('Licencias'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Traduciendo Licencia'), 'item' => true)
                            )
                        ),
                        'icon' => array(
                            'label' => Text::_('Tipos de Retorno'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Traduciendo Tipo'), 'item' => true)
                            )
                        ),
                        'tag' => array(
                            'label' => Text::_('Tags de blog'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Traduciendo Tag'), 'item' => true)
                            )
                        ),
                        'criteria' => array(
                            'label' => Text::_('Criterios de revisión'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Traduciendo Criterio'), 'item' => true)
                            )
                        ),
                        'template' => array(
                            'label' => Text::_('Plantillas de email'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Traduciendo Plantilla'), 'item' => true)
                            )
                        ),
                        'glossary' => array(
                            'label' => Text::_('Glosario'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Traduciendo Término'), 'item' => true)
                            )
                        ),
                        'info' => array(
                            'label' => Text::_('Ideas about'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Traduciendo Idea'), 'item' => true)
                            )
                        ),
                        'worthcracy' => array(
                            'label' => Text::_('Meritocracia'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Traduciendo Nivel'), 'item' => true)
                            )
                        )
                    )
                ),
                'home' => array(
                    'label'   => Text::_('Portada'),
                    'options' => array (
                        'news' => array(
                            'label' => Text::_('Micronoticias'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Traduciendo Micronoticia'), 'item' => true)
                            )
                        ),
                        'promote' => array(
                            'label' => Text::_('Proyectos destacados'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Traduciendo Destacado'), 'item' => true)
                            )
                        ),
                        'patron' => array(
                            'label' => Text::_('Proyectos recomendados'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Traduciendo recomendado'), 'item' => true)
                            )
                        )
                    )
                ),
                'node' => array(
                    'label'   => Text::_('Nodo'),
                    'options' => array (
                        'data' => array(
                            'label' => Text::_('Descripción'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Traduciendo'), 'item' => false)
                            )
                        ),
                        'banner' => array(
                            'label' => Text::_('Banners'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Traduciendo banner'), 'item' => true)
                            )
                        ),
                        'post' => array(
                            'label' => Text::_('Blog'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Traduciendo entrada'), 'item' => true)
                            )
                        ),
                        'page' => array(
                            'label' => Text::_('Páginas institucionales'),
                            'actions' => array(
                                'list' => array('label' => Text::_('Listando'), 'item' => false),
                                'edit' => array('label' => Text::_('Traduciendo página'), 'item' => true)
                            )
                        )
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
                        $BC['action'] = '';
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
                if (!empty($BC['option']) && isset($menu[$BC['section']]['options'][$BC['option']])) {
                    $option = $menu[$BC['section']]['options'][$BC['option']];
                    if ($BC['action'] == 'list') {
                        $path = " &gt; <strong>{$option['label']}</strong>";
                    } else {
                        if (!empty($BC['node'])) {
                            $path = ' &gt; <a href="/translate/node/'.$BC['node'].'/'.$BC['option'].'">'.$option['label'].'</a>'.$path;
                        } else {
                            $path = ' &gt; <a href="/translate/'.$BC['option'].''.$BC['filter'].'">'.$option['label'].'</a>'.$path;
                        }
                    }
                }

                if (empty($BC['option'])) {
                    if (!empty($BC['node'])) {
                        $path = 'Traduciendo nodo <strong>'.$BC['node'].'</strong>';
                    } else {
                        $path = '<strong>Traductor</strong>';
                    }
                } else {
                    if (!empty($BC['node'])) {
                        $path = '<a href="/translate/node/'.$BC['node'].'">Traduciendo nodo <strong>'.$BC['node'].'</strong></a>' . $path;
                    } else {
                        $path = '<a href="/translate">Traductor</a>' . $path;
                    }
                }
                
                return $path;
            }


        }


	}

}
