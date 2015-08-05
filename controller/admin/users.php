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
		Goteo\Library\Template,
        Goteo\Library\Message,
        Goteo\Library\i18n\Lang,
        Goteo\Model;

    class Users {

        public static function _manageSubAct() {
            return array(
                "ban" => array (
                    'sql' => "UPDATE user SET active = 0 WHERE id = :user",
                    'log' => Text::_("Desactivado")
                    ),
                "unban" => array (
                    'sql' => "UPDATE user SET active = 1 WHERE id = :user",
                    'log' => Text::_("Activado")
                    ),
                "show" => array (
                    'sql' => "UPDATE user SET hide = 0 WHERE id = :user",
                    'log' => Text::_("Mostrado")
                    ),
                "hide" => array (
                    'sql' => "UPDATE user SET hide = 1 WHERE id = :user",
                    'log' => Text::_("Ocultado")
                    ),
                "checker" => array (
                    'sql' => "REPLACE INTO user_role (user_id, role_id) VALUES (:user, 'checker')",
                    'log' => Text::_("Hecho revisor")
                    ),
                "nochecker" => array (
                    'sql' => "DELETE FROM user_role WHERE role_id = 'checker' AND user_id = :user",
                    'log' => Text::_("Quitado de revisor")
                    ),
                "translator" => array (
                    'sql' => "REPLACE INTO user_role (user_id, role_id) VALUES (:user, 'translator')",
                    'log' => Text::_("Hecho traductor")
                    ),
                "notranslator" => array (
                    'sql' => "DELETE FROM user_role WHERE role_id = 'translator' AND user_id = :user",
                    'log' => Text::_("Quitado de traductor")
                    ),
                "admin" => array (
                    'sql' => "REPLACE INTO user_role (user_id, role_id) VALUES (:user, 'admin')",
                    'log' => Text::_("Hecho admin")
                    ),
                "noadmin" => array (
                    'sql' => "DELETE FROM user_role WHERE role_id = 'admin' AND user_id = :user",
                    'log' => Text::_("Quitado de admin")
                    )
            );
        }
        
        
        
        
        
        public static function process ($action = 'list', $id = null, $filters = array(), $subaction = '') {

            // @NODESYS
            $nodes = array();

            // @NODESYS
            $node = \GOTEO_NODE;

            $errors = array();

            switch ($action)  {
                case 'add':

                    // si llega post: creamos
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                        // para crear se usa el mismo método save del modelo, hay que montar el objeto
                        $user = new Model\User();
                        $user->userid = $_POST['userid'];
                        $user->name = $_POST['name'];
                        $user->email = $_POST['email'];
                        $user->password = $_POST['password'];
                        $user->node = !empty($_POST['node']) ? $_POST['node'] : \GOTEO_NODE;
                        if (isset($_SESSION['admin_node']) && $user->node != $_SESSION['admin_node']) {
                            $user->node = $_SESSION['admin_node'];
                        }
                        $user->save($errors);

                        if(empty($errors)) {
                          // mensaje de ok y volvemos a la lista de usuarios
                          Message::Info(Text::get('user-register-success'));
                          throw new Redirection('/admin/users/manage/'.$user->id);
                        } else {
                            // si hay algun error volvemos a poner los datos en el formulario
                            $data = $_POST;
                            Message::Error(implode('<br />', $errors));
                        }
                    }

                    // vista de crear usuario
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'users',
                            'file' => 'add',
                            'data'=>$data,
                            'nodes' => $nodes
                        )
                    );

                    break;
                case 'edit':

                    $user = Model\User::get($id);

                    // si llega post: actualizamos
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $tocado = array();
                        // para crear se usa el mismo método save del modelo, hay que montar el objeto
                        if (!empty($_POST['email'])) {
                            $user->email = $_POST['email'];
                            $tocado[] = Text::_('el email');
                        }
                        if (!empty($_POST['password'])) {
                            $user->password = $_POST['password'];
                            $tocado[] = Text::_('la contraseña');
                        }

                        if(!empty($tocado) && $user->update($errors)) {

                            // Evento Feed
                            $log = new Feed();
                            $log->setTarget($user->id, 'user');
                            $log->populate(Text::_('Operación sobre usuario'), '/admin/users', \vsprintf('El admin %s ha %s del usuario %s', array(
                                Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                Feed::item('relevant', 'Tocado ' . implode (' y ', $tocado)),
                                Feed::item('user', $user->name, $user->id)
                            )));
                            $log->doAdmin('user');
                            unset($log);

                            // mensaje de ok y volvemos a la lista de usuarios
                            Message::Info(Text::_('Datos actualizados'));
                            throw new Redirection('/admin/users');

                        } else {
                            // si hay algun error volvemos a poner los datos en el formulario
                            $data = $_POST;
                            Message::Error(Text::_('No se ha guardado correctamente. ').implode('<br />', $errors));
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
                            'nodes'=>$nodes
                        )
                    );

                    break;
                case 'manage':

                    // si llega post: ejecutamos + mensaje + seguimos editando

                    // operación y acción para el feed
                    $mngSa = static::_manageSubAct();
                    
                    $sql = $mngSa[$subaction]['sql'];
                    $log_action = $mngSa[$subaction]['log'];
                    
                    if (!empty($sql)) {

                        $user = Model\User::getMini($id);

                        if (Model\User::query($sql, array(':user'=>$id))) {

                            // mensaje de ok y volvemos a la gestion del usuario
//                            Message::Info('Ha <strong>' . $log_action . '</strong> al usuario <strong>'.$user->name.'</strong> CORRECTAMENTE');
                            $log_text = 'El admin %s ha %s al usuario %s';

                            // procesos adicionales
                            switch ($subaction) {
                                case 'admin':
                                case 'noadmin':
                                    // @NODESYS : this admin/noadmin subactions are here for NODESYS module extra
                                    break;

                                case 'translator':
                                    // le ponemos todos los idiomas (excepto el español)
                                    $sql = "INSERT INTO user_translang (user, lang) SELECT '{$id}' as user, id as lang FROM `lang` WHERE id != 'es'";
                                    Model\User::query($sql);
                                    break;

                                case 'notranslator':
                                    // quitamos los idiomas
                                    $sql = "DELETE FROM user_translang WHERE user = :user";
                                    Model\User::query($sql, array(':user'=>$id));
                                    break;
                            }


                        } else {

                            // mensaje de error y volvemos a la gestion del usuario
                            Message::Error('Ha FALLADO cuando ha <strong>' . $log_action . '</strong> al usuario <strong>'.$id.'</strong>');
                            $log_text = 'Al admin %s le ha <strong>FALLADO</strong> cuando ha %s al usuario %s';

                        }

                        // Evento Feed
                        $log = new Feed();
                        $log->setTarget($user->id, 'user');
                        $log->populate(Text::_('Operación sobre usuario'), '/admin/users',
                            \vsprintf($log_text, array(
                                Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                Feed::item('relevant', $log_action),
                                Feed::item('user', $user->name, $user->id)
                        )));
                        $log->doAdmin('user');
                        unset($log);

                        throw new Redirection('/admin/users/manage/'.$id);
                    }

                    $user = Model\User::get($id);

                    $viewData = array(
                            'folder' => 'users',
                            'file' => 'manage',
                            'user'=>$user,
                            'nodes'=>$nodes
                        );

                    $viewData['roles'] = Model\User::getRolesList();
                    $viewData['langs'] = Lang::getAll();
                    // quitamos el español
                    unset($viewData['langs']['es']);

                    // vista de gestión de usuario
                    return new View(
                        'view/admin/index.html.php',
                        $viewData
                    );


                    break;

                // aplicar idiomas
                case 'translang':

                    if (!isset($_POST['user'])) {
                        Message::Error(Text::_('Hemos perdido de vista al usuario'));
                        throw new Redirection('/admin/users');
                    } else {
                        $user = $_POST['user'];
                    }

                    $sql = "DELETE FROM user_translang WHERE user = :user";
                    Model\User::query($sql, array(':user'=>$user));

                    $anylang = false;
                    foreach ($_POST as $key => $value) {
                        if (\substr($key, 0, \strlen('lang_')) == 'lang_')  {
                            $sql = "INSERT INTO user_translang (user, lang) VALUES (:user, :lang)";
                            if (Model\User::query($sql, array(':user'=>$user, ':lang'=>$value))) {
                                $anylang = true;
                            }
                        }
                    }

                    if (!$anylang) {
                        Message::Error(Text::_('No se ha seleccionado ningún idioma, este usuario tendrá problemas en su panel de traducción!'));
                    } else {
                        Message::Info(Text::_('Se han aplicado al traductor los idiomas seleccionados'));
                    }

                    throw new Redirection('/admin/users/manage/'.$user);

                    break;
                case 'impersonate':

                    $user = Model\User::get($id);

                    // vista de acceso a suplantación de usuario
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'users',
                            'file'   => 'impersonate',
                            'user'   => $user,
                            'nodes'=>$nodes
                        )
                    );

                    break;
                case 'move':
                    $user = Model\User::get($id);
                    
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $values = array(':id' => $id, ':node' => $_POST['node']);
                        try {
                            $sql = "UPDATE user SET node = :node WHERE id = :id";
                            if (Model\User::query($sql, $values)) {
                                $log_text = 'El admin %s ha <span class="red">movido</span> el usuario %s al nodo %s';
                            } else {
                                $log_text = 'Al admin %s le ha <span class="red">fallado al mover</span> el usuario %s al nodo %s';
                            }
                            // Evento Feed
                            $log = new Feed();
                            $log->setTarget($user->id, 'user');
                            $log->populate('User cambiado de nodo (admin)', '/admin/users',
                                \vsprintf($log_text, array(
                                    Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                                    Feed::item('user', $user->name, $user->id),
                                    Feed::item('user', $nodes[$_POST['node']])
                            )));
                            Message::Error($log->html);
                            $log->doAdmin('user');
                            unset($log);

                            throw new Redirection('/admin/users');

                        } catch(\PDOException $e) {
                            Message::Error("Ha fallado! " . $e->getMessage());
                        }
                    }

                    // vista de acceso a suplantación de usuario
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'users',
                            'file'   => 'move',
                            'user'   => $user,
                            'nodes' => $nodes
                        )
                    );

                    break;

                case 'list':
                default:
                    if (!empty($filters['filtered'])) {
                        $users = Model\User::getAll($filters, $node);
                    } else {
                        $users = array();
                    }
                    $status = array(
                                'active' => Text::_('Activo'),
                                'inactive' => Text::_('Inactivo')
                            );
                    $interests = Model\User\Interest::getAll();
                    $roles = Model\User::getRolesList();
                    $roles['user'] = Text::_('Solo usuario');
                    $types = array(
                        'creators' => Text::_('Impulsores'), // que tienen algun proyecto 
                        'investors' => Text::_('Cofinanciadores'), // que han aportado a algun proyecto en campaña, financiado, archivado o caso de éxito
                        'supporters' => Text::_('Colaboradores') // que han enviado algun mensaje en respuesta a un mensaje de colaboración
                        // hay demasiados de estos... 'lurkers' => 'Mirones'
                    );
                    $orders = array(
                        'created' => Text::_('Fecha de alta'),
                        'name' => Text::_('Alias'),
                        'id' => Text::_('User'),
                        'amount' => Text::_('Cantidad'),
                        'projects' => Text::_('Proyectos')
                    );
                    // proyectos con aportes válidos
                    $projects = Model\Invest::projects(true, $node);

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'users',
                            'file' => 'list',
                            'users'=>$users,
                            'filters' => $filters,
                            'status' => $status,
                            'interests' => $interests,
                            'roles' => $roles,
                            'types' => $types,
                            'nodes' => $nodes,
                            'projects' => $projects,
                            'orders' => $orders
                        )
                    );
                break;
            }
            
        }

    }

}
