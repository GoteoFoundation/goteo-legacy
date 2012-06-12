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

	use Goteo\Core\Redirection,
        Goteo\Core\Error,
        Goteo\Core\View,
		Goteo\Model,
        Goteo\Library\Feed,
        Goteo\Library\Text,
        Goteo\Library\Message,
        Goteo\Library\Listing;

	class User extends \Goteo\Core\Controller {

	    /**
	     * Atajo al perfil de usuario.
	     * @param string $id   Nombre de usuario
	     */
		public function index ($id, $show = '') {
		    throw new Redirection('/user/profile/' .  $id . '/' . $show, Redirection::PERMANENT);
		}

        /**
         * Inicio de sesión.
         * Si no se le pasan parámetros carga el tpl de identificación.
         *
         * @param string $username Nombre de usuario
         * @param string $password Contraseña
         */
        public function login () {

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['login'])) {
                $username = \strtolower($_POST['username']);
                $password = $_POST['password'];
                if (false !== ($user = (\Goteo\Model\User::login($username, $password)))) {
                    $_SESSION['user'] = $user;
                    if (!empty($_POST['return'])) {
                        throw new Redirection($_POST['return']);
                    } elseif (!empty($_SESSION['jumpto'])) {
                        $jumpto = $_SESSION['jumpto'];
                        unset($_SESSION['jumpto']);
                        throw new Redirection($jumpto);
                    } else {
                        throw new Redirection('/dashboard');
                    }
                }
                else {
                    Message::Error(Text::get('login-fail'));
                }
            }

            return new View ('view/user/login.html.php');

        }

        /**
         * Cerrar sesión.
         */
        public function logout() {
            if (isset($_COOKIE[session_name()])) {
                setcookie(session_name(), '', time()-42000, '/');
            }
            session_destroy();
            throw new Redirection('/');
            die;
        }
        /**
         * Registro de usuario.
         */
        public function register () {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                foreach ($_POST as $key=>$value) {
                    $_POST[$key] = trim($value);
                }

            	$errors = array();

				if (strcmp($_POST['email'], $_POST['remail']) !== 0) {
					$errors['remail'] = Text::get('error-register-email-confirm');
				}
				if(strcmp($_POST['password'], $_POST['rpassword']) !== 0) {
					$errors['rpassword'] = Text::get('error-register-password-confirm');
				}

				$user = new Model\User();
				$user->userid = $_POST['userid'];
				$user->name = $_POST['username'];
				$user->email = $_POST['email'];
				$user->password = $_POST['password'];
				$user->active = true;

				$user->save($errors);

				if(empty($errors)) {
				  Message::Info(Text::get('user-register-success'));
				  Message::Info('Tus datos de acceso son Usuario: <strong>'.$user->id.'</strong> Contraseña: <strong>'.$_POST['password'].'</strong>');
                  
                  throw new Redirection('/user/login');
				} else {
					foreach ($errors as $field=>$text) {
						Message::Error($text);
					}
				}
			}
			return new View (
				'view/user/login.html.php',
				array(
					'errors' => $errors
				)
			);
        }

		/**
		 * Registro de usuario desde oauth
		 */
		public function oauth_register() {
            if (\GOTEO_FREE) {
                return false;
            }
		}
        /**
         * Registro de usuario a traves de Oauth (libreria HybridOauth, openid, facebook, twitter, etc).
         */
        public function oauth () {
            if (\GOTEO_FREE) {
                return false;
            }
        }
         /**
         * Modificación perfil de usuario.
         * Metodo Obsoleto porque esto lo hacen en el dashboard
         */
        public function edit () {
            $user = $_SESSION['user'];

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			    $errors = array();
                // E-mail
                if($_POST['change_email']) {
                    if(empty($_POST['user_nemail'])) {
                        $errors['email'] = Text::get('error-user-email-empty');
                    }
                    elseif(!\Goteo\Library\Check::mail($_POST['user_nemail'])) {
                        $errors['email'] = Text::get('error-user-email-invalid');
                    }
                    elseif(empty($_POST['user_remail'])) {
                        $errors['email']['retry'] = Text::get('error-user-email-empty');
                    }
                    elseif (strcmp($_POST['user_nemail'], $_POST['user_remail']) !== 0) {
                        $errors['email']['retry'] = Text::get('error-user-email-confirm');
                    }
                    else {
                        $user->email = $_POST['user_nemail'];
                    }
                }
                // Contraseña
                if($_POST['change_password']) {
                    /*
                     * Quitamos esta verificacion porque los usuarios que acceden mediante servicio no tienen contraseña
                     *
                    if(empty($_POST['user_password'])) {
                        $errors['password'] = Text::get('error-user-password-empty');
                    }
                    else
                    */
                    if(!Model\User::login($user->id, $_POST['user_password'])) {
                        $errors['password'] = Text::get('error-user-wrong-password');
                    }
                    elseif(empty($_POST['user_npassword'])) {
                        $errors['password']['new'] = Text::get('error-user-password-empty');
                    }
                    elseif(!\Goteo\Library\Check::password($_POST['user_npassword'])) {
                        $errors['password']['new'] = Text::get('error-user-password-invalid');
                    }
                    elseif(empty($_POST['user_rpassword'])) {
                        $errors['password']['retry'] = Text::get('error-user-password-empty');
                    }
                    elseif(strcmp($_POST['user_npassword'], $_POST['user_rpassword']) !== 0) {
                        $errors['password']['retry'] = Text::get('error-user-password-confirm');
                    }
                    else {
                        $user->password = $_POST['user_npassword'];
                    }
                }
                // Avatar
                if(!empty($_FILES['user_avatar']['name'])) {
                    $user->avatar = $_FILES['user_avatar'];
                }

                // tratar si quitan la imagen
                if (!empty($_POST['avatar-' . $user->avatar->id .  '-remove'])) {
                    $user->avatar->remove('user');
                    $user->avatar = '';
                }

                // Perfil público
                $user->name = $_POST['user_name'];
                $user->about = $_POST['user_about'];
                $user->keywords = $_POST['user_keywords'];
                $user->contribution = $_POST['user_contribution'];
                $user->twitter = $_POST['user_twitter'];
                $user->facebook = $_POST['user_facebook'];
                $user->linkedin = $_POST['user_linkedin'];
                // Intereses
                $user->interests = $_POST['user_interests'];
                // Páginas Web
                if(!empty($_POST['user_webs']['remove'])) {
                    $user->webs = array('remove' => $_POST['user_webs']['remove']);
                }
                elseif(!empty($_POST['user_webs']['add']) && !empty($_POST['user_webs']['add'][0]) ) {
                    $user->webs = array('add' => $_POST['user_webs']['add']);
                }
                else {
                    $user->webs = array('edit', $_POST['user_webs']['edit']);
                }
                if($user->save($errors)) {
                    // Refresca la sesión.
                    $user = Model\User::flush();
                    if (isset($_POST['save'])) {
                        throw new Redirection('/dashboard');
                    } else {
                        throw new Redirection('/user/edit');
                    }
                }
			}

            return new View (
                'view/user/edit.html.php',
                array(
                    'user' => $user,
                    'errors' => $errors
                )
            );
        }

        /**
         * Perfil público de usuario.
         *
         * @param string $id    Nombre de usuario
         */
        public function profile ($id, $show = 'profile', $category = null) {

            if (!in_array($show, array('profile', 'investors', 'sharemates', 'message'))) {
                $show = 'profile';
            }

            $user = Model\User::get($id, LANG);

            if (!$user instanceof Model\User || $user->hide) {
                throw new Redirection('/', Redirection::PERMANENT);
            }

            //--- para usuarios públicos---
            if (empty($_SESSION['user'])) {
                // la subpágina de mensaje también está restringida
                if ($show == 'message') {
                    $_SESSION['jumpto'] = '/user/profile/' .  $id . '/message';
                    Message::Info(Text::get('user-login-required-to_message'));
                    throw new Redirection("/user/login");
                }


                // a menos que este perfil sea de un impulsor, no pueden verlo
//                $owners = Model\User::getOwners() ;
//                if (!isset($owners[$id])) {
                    $_SESSION['jumpto'] = '/user/profile/' .  $id . '/' . $show;
                    Message::Info(Text::get('user-login-required-to_see'));
                    throw new Redirection("/user/login");
//                }

                /*
                // subpágina de cofinanciadores
                if ($show == 'investors') {
                    Message::Info(Text::get('user-login-required-to_see-supporters'));
                    throw new Redirection('/user/profile/' .  $id);
                }
                */

            }
            //--- el resto pueden seguir ---

            $viewData = array();
            $viewData['user'] = $user;

            $projects = Model\Project::ofmine($id, true);

            //mis cofinanciadores
            // array de usuarios con:
            //  foto, nombre, nivel, cantidad a mis proyectos, fecha ultimo aporte, nº proyectos que cofinancia
            $investors = array();
            foreach ($projects as $kay=>$project) {

                // quitamos los caducados
                if ($project->status == 0) {
                    unset ($projects[$kay]);
                    continue;
                }

                foreach (Model\Invest::investors($project->id) as $key=>$investor) {
                    if (\array_key_exists($investor->user, $investors)) {
                        // ya está en el array, quiere decir que cofinancia este otro proyecto
                        // , añadir uno, sumar su aporte, actualizar la fecha
                        ++$investors[$investor->user]->projects;
                        $investors[$investor->user]->amount += $investor->amount;
                        $investors[$investor->user]->date = $investor->date;
                    } else {
                        $investors[$investor->user] = (object) array(
                            'user' => $investor->user,
                            'name' => $investor->name,
                            'projects' => 1,
                            'avatar' => $investor->avatar,
                            'worth' => $investor->worth,
                            'amount' => $investor->amount,
                            'date' => $investor->date
                        );
                    }
                }
            }

            $viewData['investors'] = $investors;

            // comparten intereses
            $viewData['shares'] = Model\User\Interest::share($id, $category);
            if ($show == 'sharemates' && empty($viewData['shares'])) {
                $show = 'profile';
            }

            if (!empty($category)) {
                $viewData['category'] = $category;
            }

            // proyectos que cofinancio
            $invested = Model\User::invested($id, true);

            // agrupacion de proyectos que cofinancia y proyectos suyos
            $viewData['lists'] = array();
            if (!empty($invested)) {
                $viewData['lists']['invest_on'] = Listing::get($invested, 2);
            }
            if (!empty($projects)) {
                $viewData['lists']['my_projects'] = Listing::get($projects, 2);
            }

            return new View ('view/user/'.$show.'.html.php', $viewData);
        }

        /**
         * Activación usuario.
         *
         * @param type string	$token
         */
        public function activate($token) {
            $query = Model\User::query('SELECT id FROM user WHERE token = ?', array($token));
            if($id = $query->fetchColumn()) {
                $user = Model\User::get($id);
                if(!$user->confirmed) {
                    $user->confirmed = true;
                    $user->active = true;
                    if($user->save($errors)) {
                        Message::Info(Text::get('user-activate-success'));
                        $_SESSION['user'] = $user;

                        /*
                         * Evento Feed
                         */
                        $log = new Feed();
                        $log->title = 'nuevo usuario registrado (confirmado)';
                        $log->url = '/admin/users';
                        $log->type = 'user';
                        $log->html = Text::html('feed-new_user', Feed::item('user', $user->name, $user->id));
                        $log->add($errors);

                        // evento público
                        $log->title = $user->name;
                        $log->url = null;
                        $log->scope = 'public';
                        $log->type = 'community';
                        $log->add($errors);

                        unset($log);

                    }
                    else {
                        Message::Error($errors);
                    }
                }
                else {
                    Message::Info(Text::get('user-activate-already-active'));
                }
            }
            else {
                Message::Error(Text::get('user-activate-fail'));
            }
            throw new Redirection('/dashboard');
        }

        /**
         * Cambiar dirección de correo.
         *
         * @param type string	$token
         */
        public function changeemail($token) {
            $token = base64_decode($token);
            if(count(explode('¬', $token)) > 1) {
                $query = Model\User::query('SELECT id FROM user WHERE token = ?', array($token));
                if($id = $query->fetchColumn()) {
                    $user = Model\User::get($id);
                    $user->email = $token;
                    $errors = array();
                    if($user->save($errors)) {
                        Message::Info(Text::get('user-changeemail-success'));

                        // Refresca la sesión.
                        Model\User::flush();
                    }
                    else {
                        Message::Error($errors);
                    }
                }
                else {
                    Message::Error(Text::get('user-changeemail-fail'));
                }
            }
            else {
                Message::Error(Text::get('user-changeemail-fail'));
            }
            throw new Redirection('/dashboard');
        }

        /**
         * Recuperacion de contraseña
         * - Si no llega nada, mostrar formulario para que pongan su username y el email correspondiente
         * - Si llega post es una peticion, comprobar que el username y el email que han puesto son válidos
         *      si no lo son, dejarlos en el formulario y mensaje de error
         *      si son válidos, enviar email con la url y mensaje de ok
         *
         * - Si llega un hash, verificar y darle acceso hasta su dashboard /profile/access para que la cambien
         *
         * @param string $token     Codigo
         */
        public function recover ($token = null) {

            // si el token mola, logueo este usuario y lo llevo a su dashboard
            if (!empty($token)) {
                $token = base64_decode($token);
                $parts = explode('¬', $token);
                if(count($parts) > 1) {
                    $query = Model\User::query('SELECT id FROM user WHERE email = ? AND token = ?', array($parts[1], $token));
                    if($id = $query->fetchColumn()) {
                        if(!empty($id)) {
                            // el token coincide con el email y he obtenido una id
                            Model\User::query('UPDATE user SET active = 1 WHERE id = ?', array($id));
                            $user = Model\User::get($id);
                            $_SESSION['user'] = $user;
                            $_SESSION['recovering'] = $user->id;
                            throw new Redirection('/dashboard/profile/access/recover#password');
                        }
                    }
                }

                $error = Text::get('recover-token-incorrect');
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['recover'])) {
                $username = $_POST['username'];
                $email    = $_POST['email'];
                if ((!empty($username) || !empty($email)) && Model\User::recover($username, $email)) {
                    $message = Text::get('recover-email-sended');
                    unset($_POST['username']);
                    unset($_POST['email']);
                }
                else {
                    $error = Text::get('recover-request-fail');
                }
            }

            return new View (
                'view/user/recover.html.php',
                array(
                    'error'   => $error,
                    'message' => $message
                )
            );

        }

        /**
         * Darse de baja
         * - Si no llega nada, mostrar formulario para que pongan el email de su cuenta
         * - Si llega post es una peticion, comprobar que el email que han puesto es válido
         *      si no es, dejarlos en el formulario y mensaje de error
         *      si es válido, enviar email con la url y mensaje de ok
         *
         * - Si llega un hash, verificar y dar de baja la cuenta (desactivar y ocultar)
         *
         * @param string $token     Codigo
         */
        public function leave ($token = null) {

            // si el token mola, lo doy de baja
            if (!empty($token)) {
                $token = base64_decode($token);
                $parts = explode('¬', $token);
                if(count($parts) > 1) {
                    $query = Model\User::query('SELECT id FROM user WHERE email = ? AND token = ?', array($parts[1], $token));
                    if($id = $query->fetchColumn()) {
                        if(!empty($id)) {
                            // el token coincide con el email y he obtenido una id
                            if (Model\User::cancel($id)) {
                                Message::Info(Text::get('leave-process-completed'));
                                throw new Redirection('/user/login');
                            } else {
                                Message::Error(Text::get('leave-process-fail'));
                                throw new Redirection('/user/login');
                            }
                        }
                    }
                }

                $error = Text::get('leave-token-incorrect');
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['leaving'])) {
                if (Model\User::leaving($_POST['email'], $_POST['reason'])) {
                    $message = Text::get('leave-email-sended');
                    unset($_POST['email']);
                    unset($_POST['reason']);
                }
                else {
                    $error = Text::get('leave-request-fail');
                }
            }

            return new View (
                'view/user/leave.html.php',
                array(
                    'error'   => $error,
                    'message' => $message
                )
            );

        }

    }

}