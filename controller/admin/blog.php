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
        Goteo\Model;

    class Blog {

        public static function process ($action = 'list', $id = null, $filters = array()) {

            $errors = array();

            $node = (empty($_SESSION['admin_node'])) ? \GOTEO_NODE : $_SESSION['admin_node'];

            $blog = Model\Blog::get($node, 'node');
            if (!$blog instanceof \Goteo\Model\Blog) {
                $blog = new Model\Blog(array('type'=>'node', 'owner'=>$node, 'active'=>1));
                if ($blog->save($errors)) {
                    Message::Info('Se ha inicializado su espacio de blog');
                } else {
                    Message::Error('No tiene espacio de blog, contacte con nosotros');
                    throw new Redirection('/admin');
                }
            } elseif (!$blog->active) {
                Message::Error('Lo sentimos, la gestión de blog esta desactivada');
                throw new Redirection('/admin');
            }

            // primero comprobar que tenemos blog
            if (!$blog instanceof Model\Blog) {
                Message::Error('No se ha encontrado ningún blog, contacte con nosotros');
                throw new Redirection('/admin');
            }

            $url = '/admin/blog';

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if (empty($_POST['blog'])) {
                        Message::Error('Hemos perdido de vista su blog!!!');
                        throw new Redirection('/admin/blog');
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
                        'allow',
                        'author'
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
                    
                    // si tenemos un nuevio tag hay que añadirlo
                    if(!empty($_POST['new-tag_save']) && !empty($_POST['new-tag'])) {

                        // grabar el tag en la tabla de tag,
                        $new_tag = new Model\Blog\Post\Tag(array(
                            'id' => '',
                            'name' => $_POST['new-tag']
                        ));

                        if ($new_tag->save($errors)) {
                            $post->tags[] = $new_tag->id; // asignar al post
                        } else {
                            Message::Error(implode('<br />', $errors));
                        }

                        $editing = true; // seguir editando
                    }


                    /// este es el único save que se lanza desde un metodo process_
                    if ($post->save($errors)) {
                        if ($action == 'edit') {
                            Message::Info('La entrada se ha actualizado correctamente');
                        } else {
                            Message::Info('Se ha añadido una nueva entrada');
                            $id = $post->id;
                        }
                        $action = $editing ? 'edit' : 'list';

                        if ((bool) $post->publish) {
                            // Evento Feed
                            $log = new Feed();
                            $log->setTarget('goteo', 'blog');
                            $log->populate('nueva entrada blog Goteo (admin)', '/admin/blog',
                                \vsprintf('El admin %s ha %s en el blog Goteo la entrada "%s"', array(
                                Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                Feed::item('relevant', 'Publicado'),
                                Feed::item('blog', $post->title, $post->id)
                            )));
                            $log->doAdmin('admin');

                            // evento público
                            $log->unique = true;
                            $log->populate($post->title, '/blog/'.$post->id, Text::recorta($post->text, 250), $post->gallery[0]->id);
                            $log->doPublic('goteo');

                            unset($log);
                        } else {
                            //sino lo quitamos
                            \Goteo\Core\Model::query("DELETE FROM feed WHERE url = '/blog/{$post->id}' AND scope = 'public' AND type = 'goteo'");
                        }

                    } else {
                        Message::Error('Ha habido algun problema al guardar los datos:<br />' . \implode('<br />', $errors));
                    }
            }

            switch ($action)  {
                case 'list':
                    // lista de entradas
                    // obtenemos los datos
                    $filters['node'] = $node;
                    $show = array(
                        'all' => 'Todas las entradas existentes',
                        'published' => 'Solamente las publicadas en el blog',
                        'owned' => 'Solamente las del propio nodo',
                        'home' => 'Solamente las de portada',
                        'entries' => 'Solamente las de cierto nodo',
                        'updates' => 'Solamente las de proyectos'
                    );

                    // filtro de blogs de proyectos/nodos
                    switch ($filters['show']) {
                        case 'updates':
                            $blogs = Model\Blog::getListProj();
                            break;

                        case 'entries':
                            $blogs = Model\Blog::getListNode();
                            break;
                    }

                    if ( !in_array($filters['show'], array('entries', 'updates')) || !isset($blogs[$filters['blog']]) ) {
                        unset($filters['blog']);
                    }

                    $posts = Model\Blog\Post::getList($filters, false);
                    $homes = Model\Post::getList('home', $node);
                    $footers = Model\Post::getList('footer', $node);

                    if ($node == \GOTEO_NODE) {
                        $show['footer'] = 'Solamente las del footer';
                    }

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'blog',
                            'file' => 'list',
                            'posts' => $posts,
                            'filters' => $filters,
                            'show' => $show,
                            'blogs' => $blogs,
                            'homes' => $homes,
                            'footers' => $footers,
                            'node' => $node
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
                                'tags' => array(),
                                'author' => $_SESSION['user']->id
                            )
                        );

                    $message = 'Añadiendo una nueva entrada';

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'blog',
                            'file' => 'edit',
                            'action' => 'add',
                            'post' => $post,
                            'tags' => Model\Blog\Post\Tag::getAll(),
                            'message' => $message
                        )
                    );
                    break;
                case 'edit':
                    if (empty($id)) {
                        Message::Error('No se ha encontrado la entrada');
                        throw new Redirection('/admin/blog');
                        break;
                    } else {
                        $post = Model\Blog\Post::get($id);

                        if (!$post instanceof Model\Blog\Post) {
                            Message::Error('La entrada esta corrupta, contacte con nosotros.');
                            $action = 'list';
                            break;
                        } elseif ($node != \GOTEO_NODE && $post->owner_type == 'node' && $post->owner_id != $node) {
                            Message::Error('No puedes editar esta entrada.');
                            throw new Redirection('/admin/blog/list');
                        }
                    }

                    $message = 'Editando una entrada existente';

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'blog',
                            'file' => 'edit',
                            'action' => 'edit',
                            'post' => $post,
                            'tags' => Model\Blog\Post\Tag::getAll(),
                            'message' => $message
                        )
                    );
                    break;
                case 'remove':
                    // eliminar una entrada
                    $tempData = Model\Blog\Post::get($id);
                    if ($node != \GOTEO_NODE && $tempData->owner_type == 'node' && $tempData->owner_id != $node ) {
                        Message::Error('No puedes eliminar esta entrada.');
                        throw new Redirection('/admin/blog');
                    }
                    if (Model\Blog\Post::delete($id)) {
                        // Evento Feed
                        $log = new Feed();
                        $log->setTarget('goteo', 'blog');
                        $log->populate('Quita entrada de blog (admin)', '/admin/blog',
                            \vsprintf('El admin %s ha %s la entrada "%s" del blog de Goteo', array(
                                Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                Feed::item('relevant', 'Quitado'),
                                Feed::item('blog', $tempData->title)
                        )));
                        $log->doAdmin('admin');
                        unset($log);

                        Message::Info('Entrada eliminada');
                    } else {
                        Message::Error('No se ha podido eliminar la entrada');
                    }
                    throw new Redirection('/admin/blog/list');
                    break;

                // acciones portada
                case 'reorder':
                    // lista de entradas en portada
                    // obtenemos los datos
                    $posts = Model\Post::getAll('home', $node);

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'blog',
                            'file' => 'order',
                            'posts' => $posts
                        )
                    );
                    break;
                case 'up':
                    if ($node != \GOTEO_NODE) {
                        Model\Post::up_node($id, $node);
                    } else {
                        Model\Post::up($id, 'home');
                    }
                    throw new Redirection('/admin/blog/reorder');
                    break;
                case 'down':
                    if ($node != \GOTEO_NODE) {
                        Model\Post::up_node($id, $node);
                    } else {
                        Model\Post::down($id, 'home');
                    }
                    throw new Redirection('/admin/blog/reorder');
                    break;
                case 'add_home':
                    // siguiente orden
                    if ($node != \GOTEO_NODE) {
                        $next = Model\Post::next_node($node);
                        $data = (object) array('post' => $id, 'node' => $node, 'order' => $next);
                        if (Model\Post::update_node($data, $errors)) {
                            Message::Info('Entrada colocada en la portada correctamente');
                        } else {
                            Message::Error('Ha habido algun problema:<br />' . \implode('<br />', $errors));
                        }
                    } else {
                        $next = Model\Post::next('home');
                        $post = new Model\Post(array(
                            'id' => $id,
                            'order' => $next,
                            'home' => 1
                        ));

                        if ($post->update($errors)) {
                            Message::Info('Entrada colocada en la portada correctamente');
                        } else {
                            Message::Error('Ha habido algun problema:<br />' . \implode('<br />', $errors));
                        }
                    }
                    throw new Redirection('/admin/blog/list');
                    break;
                case 'remove_home':
                    // se quita de la portada solamente
                    $ok = false;
                    if ($node != \GOTEO_NODE) {
                        $ok = Model\Post::remove_node($id, $node);
                    } else {
                        $ok = Model\Post::remove($id, 'home');
                    }
                    if ($ok) {
                        Message::Info('Entrada quitada de la portada correctamente');
                    } else {
                        Message::Error('No se ha podido quitar esta entrada de la portada');
                    }
                    throw new Redirection('/admin/blog/list');
                    break;

                // acciones footer (solo para superadmin y admins de goteo
                case 'footer':
                    if ($node == \GOTEO_NODE) {
                        // lista de entradas en el footer
                        // obtenemos los datos
                        $posts = Model\Post::getAll('footer');

                        return new View(
                            'view/admin/index.html.php',
                            array(
                                'folder' => 'blog',
                                'file' => 'footer',
                                'posts' => $posts
                            )
                        );
                    } else {
                        throw new Redirection('/admin/blog/list');
                    }
                    break;
                case 'up_footer':
                    if ($node == \GOTEO_NODE) {
                        Model\Post::up($id, 'footer');
                        throw new Redirection('/admin/blog/footer');
                    } else {
                        throw new Redirection('/admin/blog');
                    }
                    break;
                case 'down_footer':
                    if ($node == \GOTEO_NODE) {
                        Model\Post::down($id, 'footer');
                        throw new Redirection('/admin/blog/footer');
                    } else {
                        throw new Redirection('/admin/blog');
                    }
                    break;
                case 'add_footer':
                    if ($node == \GOTEO_NODE) {
                        // siguiente orden
                        $next = Model\Post::next('footer');
                        $post = new Model\Post(array(
                            'id' => $id,
                            'order' => $next,
                            'footer' => 1
                        ));

                        if ($post->update($errors)) {
                            Message::Info('Entrada colocada en el footer correctamente');
                        } else {
                            Message::Error('Ha habido algun problema:<br />' . \implode('<br />', $errors));
                        }
                    }
                    throw new Redirection('/admin/blog');
                    break;
                case 'remove_footer':
                    if ($node == \GOTEO_NODE) {
                        // se quita del footer solamente
                        if (Model\Post::remove($id, 'footer')) {
                            Message::Info('Entrada quitada del footer correctamente');
                        } else {
                            Message::Error('No se ha podido quitar esta entrada del footer');
                        }
                    }
                    throw new Redirection('/admin/blog/list');
                    break;
            }

        }

    }

}
