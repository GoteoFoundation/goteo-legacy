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


namespace Goteo\Model {

	use Goteo\Core\Redirection,
        Goteo\Library\Text,
        Goteo\Model\Image,
        Goteo\Library\Template,
        Goteo\Library\Mail,
        Goteo\Library\Check,
        Goteo\Library\Message;

	class User extends \Goteo\Core\Model {

        public
            $id = false,
            $userid, // para el login name al registrarse
            $email,
            $password, // para gestion de super admin
            $name,
            $location,
            $avatar = false,
            $about,
            $contribution,
            $keywords,
            $active,  // si no activo, no puede loguear
            $confirmed,  // si no ha confirmado el email
            $hide, // si oculto no aparece su avatar en ninguna parte (pero sus aportes cuentan)
            $facebook,
            $google,
            $twitter,
            $identica,
            $linkedin,
            $created,
            $modified,
            $interests = array(),
            $webs = array(),
            $roles = array();

        /**
         * Sobrecarga de métodos 'setter'.
         *
         * @param type string	$name
         * @param type string	$value
         */
        public function __set ($name, $value) {
	        if($name == "token") {
	            $this->$name = $this->setToken($value);
	        }
            $this->$name = $value;
        }

        /**
         * Sobrecarga de métodos 'getter'.
         *
         * @param type string $name
         * @return type mixed
         */
        public function __get ($name) {
            if($name == "token") {
	            return $this->getToken();
	        }
	        if($name == "support") {
	            return $this->getSupport();
	        }
	        if($name == "worth") {
	            return $this->getWorth();
	        }
            return $this->$name;
        }

        /**
         * Guardar usuario.
         * Guarda los valores de la instancia del usuario en la tabla.
         *
         * @param type array	$errors     	   Errores devueltos pasados por referencia.
         * @param type array	$skip_validations  Crea el usuario aunque estos campos no sean correctos
         *                                         password, active
         * @return type bool	true|false
         */
        public function save (&$errors = array(),$skip_validations = array()) {
            if($this->validate($errors,$skip_validations)) {
                // Nuevo usuario.
                if(empty($this->id)) {
                    $insert = true;
                    $data[':id'] = $this->id = static::idealiza($this->userid);
                    $data[':name'] = $this->name;
                    $data[':location'] = $this->location;
                    $data[':email'] = $this->email;
                    $data[':token'] = $token = md5(uniqid());
                    if(!in_array('password',$skip_validations)) $data[':password'] = sha1($this->password);
                    $data[':created'] = date('Y-m-d H:i:s');
                    $data[':active'] = true;
                    $data[':confirmed'] = false;

                    // Rol por defecto.
                    if (!empty($this->id)) {
                        static::query('REPLACE INTO user_role (user_id, role_id, node_id) VALUES (:user, :role, :node);', array(
                            ':user' => $this->id,
                            ':role' => 'user',
                            ':node' => '*',
                        ));
                    }

					//active = 1 si no se quiere comprovar
					if(in_array('active',$skip_validations) && $this->active) $data[':active'] = 1;
					else {
						// Obtenemos la plantilla para asunto y contenido
						$template = Template::get(5);

						// Sustituimos los datos
						$subject = $template->title;

						// En el contenido:
						$search  = array('%USERNAME%', '%USERID%', '%ACTIVATEURL%');
						$replace = array($this->name, $this->id, SITE_URL . '/user/activate/' . $token);
						$content = \str_replace($search, $replace, $template->text);

						// Activación
						$mail = new Mail();
						$mail->to = $this->email;
						$mail->toName = $this->name;
						$mail->subject = $subject;
						$mail->content = $content;
						$mail->html = false;
						$mail->template = $template->id;
						if ($mail->send($errors)) {
							Message::Info(Text::get('register-confirm_mail-success'));
						} else {
							Message::Error(Text::get('register-confirm_mail-fail', GOTEO_MAIL));
							Message::Error(implode('<br />', $errors));
						}
					}
                }
                else {
                    $data[':id'] = $this->id;

                    // E-mail
                    if(!empty($this->email)) {
                        if(count($tmp = explode('¬', $this->email)) > 1) {
                            $data[':email'] = $tmp[1];
                            $data[':token'] = null;
                        }
                        else {
                            $query = self::query('SELECT email FROM user WHERE id = ?', array($this->id));
                            if($this->email !== $query->fetchColumn()) {
                                $this->token = md5(uniqid()) . '¬' . $this->email;
                            }
                        }
                    }

                    // Contraseña
                    if(!empty($this->password)) {
                        $data[':password'] = sha1($this->password);
                        static::query('DELETE FROM user_login WHERE user= ?', $this->id);
                    }

                    if(!is_null($this->active)) {
                        $data[':active'] = $this->active;
                    }

                    if(!is_null($this->confirmed)) {
                        $data[':confirmed'] = $this->confirmed;
                    }

                    if(!is_null($this->hide)) {
                        $data[':hide'] = $this->hide;
                    }

                    // Avatar
                    if (is_array($this->avatar) && !empty($this->avatar['name'])) {
                        $image = new Image($this->avatar);
                        $image->save();
                        $data[':avatar'] = $image->id;

                        /**
                         * Guarda la relación NM en la tabla 'user_image'.
                         */
                        if(!empty($image->id)) {
                            self::query("REPLACE user_image (user, image) VALUES (:user, :image)", array(':user' => $this->id, ':image' => $image->id));
                        }
                    }

                    // Perfil público
                    if(isset($this->name)) {
                        $data[':name'] = $this->name;
                    }

                    // Dónde está
                    if(isset($this->location)) {
                        $data[':location'] = $this->location;
                    }

                    if(isset($this->about)) {
                        $data[':about'] = $this->about;
                    }

                    if(isset($this->keywords)) {
                        $data[':keywords'] = $this->keywords;
                    }

                    if(isset($this->contribution)) {
                        $data[':contribution'] = $this->contribution;
                    }

                    if(isset($this->facebook)) {
                        $data[':facebook'] = $this->facebook;
                    }

                    if(isset($this->google)) {
                        $data[':google'] = $this->google;
                    }

                    if(isset($this->twitter)) {
                        $data[':twitter'] = $this->twitter;
                    }

                    if(isset($this->identica)) {
                        $data[':identica'] = $this->identica;
                    }

                    if(isset($this->linkedin)) {
                        $data[':linkedin'] = $this->linkedin;
                    }

                    // Intereses
                    $interests = User\Interest::get($this->id);
                    if(!empty($this->interests)) {
                        foreach($this->interests as $interest) {
                            if(!in_array($interest, $interests)) {
                                $_interest = new User\Interest();
                                $_interest->id = $interest;
                                $_interest->user = $this->id;
                                $_interest->save($errors);
                                $interests[] = $_interest;
                            }
                        }
                    }
                    foreach($interests as $key => $interest) {
                        if(!in_array($interest, $this->interests)) {
                            $_interest = new User\Interest();
                            $_interest->id = $interest;
                            $_interest->user = $this->id;
                            $_interest->remove($errors);
                        }
                    }

                    // Webs
                    static::query('DELETE FROM user_web WHERE user= ?', $this->id);
                    if (!empty($this->webs)) {
                        foreach ($this->webs as $web) {
                            if ($web instanceof User\Web) {
                                $web->user = $this->id;
                                $web->save($errors);
                            }
                        }
                    }
                }

                try {
                    // Construye SQL.
                    if(isset($insert) && $insert == true) {
                        $query = "INSERT INTO user (";
                        foreach($data AS $key => $row) {
                            $query .= substr($key, 1) . ", ";
                        }
                        $query = substr($query, 0, -2) . ") VALUES (";
                        foreach($data AS $key => $row) {
                            $query .= $key . ", ";
                        }
                        $query = substr($query, 0, -2) . ")";
                    }
                    else {
                        $query = "UPDATE user SET ";
                        foreach($data AS $key => $row) {
                            if($key != ":id") {
                                $query .= substr($key, 1) . " = " . $key . ", ";
                            }
                        }
                        $query = substr($query, 0, -2) . " WHERE id = :id";
                    }
                    // Ejecuta SQL.
                    return self::query($query, $data);
            	} catch(\PDOException $e) {
                    $errors[] = "Error al actualizar los datos del usuario: " . $e->getMessage();
                    return false;
    			}
            }
            return false;
        }

		public function saveLang (&$errors = array()) {

			$fields = array(
				'id'=>'id',
				'lang'=>'lang',
				'about'=>'about_lang',
				'keywords'=>'keywords_lang',
				'contribution'=>'contribution_lang'
				);

			$set = '';
			$values = array();

			foreach ($fields as $field=>$ffield) {
				if ($set != '') $set .= ", ";
				$set .= "`$field` = :$field ";
				$values[":$field"] = $this->$ffield;
			}

			try {
				$sql = "REPLACE INTO user_lang SET " . $set;
				self::query($sql, $values);
            	
				return true;
			} catch(\PDOException $e) {
                $errors[] = "El usuario {$this->id} no se ha grabado correctamente. Por favor, revise los datos." . $e->getMessage();
                return false;
			}
		}

        /**
         * Validación de datos de usuario.
         *
         * @param type array $errors               Errores devueltos pasados por referencia.
         * @param type array	$skip_validations  Crea el usuario aunque estos campos no sean correctos
         *                                         password, active
         * @return bool true|false
         */
        public function validate (&$errors = array(), $skip_validations = array()) {
            // Nuevo usuario.
            if(empty($this->id)) {
                // Nombre de usuario (id)
                if(empty($this->userid)) {
                    $errors['userid'] = Text::get('error-register-userid');
                }
                else {
                    $id = self::idealiza($this->userid);
                    $query = self::query('SELECT id FROM user WHERE id = ?', array($id));
                    if($query->fetchColumn()) {
                        $errors['userid'] = Text::get('error-register-user-exists');
                    }
                }

                if(empty($this->name)) {
                    $errors['username'] = Text::get('error-register-username');
                }

                // E-mail
                if (empty($this->email)) {
                    $errors['email'] = Text::get('mandatory-register-field-email');
                } elseif (!Check::mail($this->email)) {
                    $errors['email'] = Text::get('validate-register-value-email');
                } else {
                    $query = self::query('SELECT email FROM user WHERE email = ?', array($this->email));
                    if($query->fetchObject()) {
                        $errors['email'] = Text::get('error-register-email-exists');
                    }
                }

                // Contraseña
                if(!in_array('password',$skip_validations))  {
					if(!empty($this->password)) {
						if(!Check::password($this->password)) {
							$errors['password'] = Text::get('error-register-invalid-password');
						}
					}
					else {
						$errors['password'] = Text::get('error-register-pasword-empty');
					}
				}
                return empty($errors);
            }
            // Modificar usuario.
            else {
                if(!empty($this->email)) {
                    if(count($tmp = explode('¬', $this->email)) > 1) {
                        if($this->email !== $this->token) {
                            $errors['email'] = Text::get('error-user-email-token-invalid');
                        }
                    }
                    elseif(!Check::mail($this->email)) {
                        $errors['email'] = Text::get('error-user-email-invalid');
                    }
                    else {
                        $query = self::query('SELECT id FROM user WHERE email = ?', array($this->email));
                        if($found = $query->fetchColumn()) {
                            if($this->id !== $found) {
                                $errors['email'] = Text::get('error-user-email-exists');
                            }
                        }
                    }
                }
                if(!empty($this->password)) {
                    if(!Check::password($this->password)) {
                        $errors['password'] = Text::get('error-user-password-invalid');
                    }
                }

                if (is_array($this->avatar) && !empty($this->avatar['name'])) {
                    $image = new Image($this->avatar);
                    $_err = array();
                    $image->validate($_err);
                    $errors['avatar'] = $_err['image'];
                }
            }

            if (\str_replace(Text::get('regular-facebook-url'), '', $this->facebook) == '') $this->facebook = '';
            if (\str_replace(Text::get('regular-google-url'), '', $this->google) == '') $this->google = '';
            if (\str_replace(Text::get('regular-twitter-url'), '', $this->twitter) == '') $this->twitter = '';
            if (\str_replace(Text::get('regular-identica-url'), '', $this->identica) == '') $this->identica = '';
            if (\str_replace(Text::get('regular-linkedin-url'), '', $this->linkedin) == '') $this->linkedin = '';



            return (empty($errors['email']) && empty($errors['password']));
        }

        /**
         * Este método actualiza directamente los campos de email y contraseña de un usuario (para gestión de superadmin)
         */
        public function update (&$errors = array()) {
            if(!empty($this->password)) {
                if(!Check::password($this->password)) {
                    $errors['password'] = Text::get('error-user-password-invalid');
                }
            }
            if(!empty($this->email)) {
                if(!Check::mail($this->email)) {
                    $errors['email'] = Text::get('error-user-email-invalid');
                }
                else {
                    $query = self::query('SELECT id FROM user WHERE email = ?', array($this->email));
                    if($found = $query->fetchColumn()) {
                        if($this->id !== $found) {
                            $errors['email'] = Text::get('error-user-email-exists');
                        }
                    }
                }
            }

            if (!empty($errors['email']) || !empty($errors['password'])) {
                return false;
            }

            $set = '';
            $values = array(':id'=>$this->id);

            if (!empty($this->email)) {
                if ($set != '') $set .= ", ";
                $set .= "`email` = :email ";
                $values[":email"] = $this->email;
            }

            if (!empty($this->password)) {
                if ($set != '') $set .= ", ";
                $set .= "`password` = :password ";
                $values[":password"] = sha1($this->password);
            }

            if ($set == '') return false;

            try {
                $sql = "UPDATE user SET " . $set . " WHERE id = :id";
                self::query($sql, $values);

                return true;
            } catch(\PDOException $e) {
                $errors[] = "No se ha guardado correctamente. " . $e->getMessage();
                return false;
            }

        }


        /**
         * Usuario.
         *
         * @param string $id    Nombre de usuario
         * @return obj|false    Objeto de usuario, en caso contrario devolverÃ¡ 'false'.
         */
        public static function get ($id, $lang = null) {
            try {
                if (!$lang == 'es') $lang = null;

                $sql = "
                    SELECT
                        user.id as id,
                        user.email as email,
                        user.name as name,
                        user.location as location,
                        user.avatar as avatar,
                        IFNULL(user_lang.about, user.about) as about,
                        IFNULL(user_lang.contribution, user.contribution) as contribution,
                        IFNULL(user_lang.keywords, user.keywords) as keywords,
                        user.facebook as facebook,
                        user.google as google,
                        user.twitter as twitter,
                        user.identica as identica,
                        user.linkedin as linkedin,
                        user.active as active,
                        user.confirmed as confirmed,
                        user.hide as hide,
                        user.created as created,
                        user.modified as modified
                    FROM user
                    LEFT JOIN user_lang
                        ON  user_lang.id = user.id
                        AND user_lang.lang = :lang
                    WHERE user.id = :id
                    ";

                $query = static::query($sql, array(':id' => $id, ':lang' => $lang));
                $user = $query->fetchObject(__CLASS__);

                if (!$user instanceof  \Goteo\Model\User) {
                    throw new \Goteo\Core\Error('404', Text::html('fatal-error-user'));
                }

                $user->roles = $user->getRoles();
                $user->avatar = Image::get($user->avatar);
                if (empty($user->avatar->id) || !$user->avatar instanceof Image) {
                    $user->avatar = Image::get(1);
                }
                $user->interests = User\Interest::get($id);
                $user->webs = User\Web::get($id);
                return $user;
            } catch(\PDOException $e) {
                return false;
            }
        }

        // version mini de get para sacar nombre i mail
        public static function getMini ($id) {
            try {
                $query = static::query("
                    SELECT
                        id,
                        name,
                        avatar,
                        email
                    FROM user
                    WHERE id = :id
                    ", array(':id' => $id));
                $user = $query->fetchObject(); // stdClass para qno grabar accidentalmente y machacar todo

                $user->avatar = Image::get($user->avatar);
                if (empty($user->avatar->id) || !$user->avatar instanceof Image) {
                    $user->avatar = Image::get(1);
                }

                return $user;
            } catch(\PDOException $e) {
                return false;
            }
        }

        /**
         * Lista de usuarios.
         *
         * @param  bool $visible    true|false
         * @return mixed            Array de objetos de usuario activos|todos.
         */
        public static function getAll ($filters = array()) {
            $users = array();

            $sqlFilter = "";
            if (!empty($filters['name'])) {
                $sqlFilter .= " AND ( name LIKE ('%{$filters['name']}%') OR email LIKE ('%{$filters['name']}%') )";
            }
            if (!empty($filters['status'])) {
                $active = $filters['status'] == 'active' ? '1' : '0';
                $sqlFilter .= " AND active = '$active'";
            }
            if (!empty($filters['interest'])) {
                $sqlFilter .= " AND id IN (
                    SELECT user
                    FROM user_interest
                    WHERE interest = {$filters['interest']}
                    ) ";
            }
            if (!empty($filters['role'])) {
                $sqlFilter .= " AND id IN (
                    SELECT user_id
                    FROM user_role
                    WHERE role_id = '{$filters['role']}'
                    ) ";
            }
            if (!empty($filters['posted'])) {
                /*
                 * Si ha enviado algun mensaje o comentario
                $sqlFilter .= " AND id IN (
                    SELECT user
                    FROM message
                    WHERE interest = {$filters['interest']}
                    ) ";
                 *
                 */
            }

            //el Order
            switch ($filters['order']) {
                case 'name':
                    $sqlOrder .= " ORDER BY name ASC";
                break;
                default:
                    $sqlOrder .= " ORDER BY created DESC";
                break;
            }

            $sql = "SELECT
                        id,
                        name,
                        email,
                        active,
                        hide,
                        DATE_FORMAT(created, '%d/%m/%Y %H:%i:%s') as register_date
                    FROM user
                    WHERE id != 'root'
                        $sqlFilter
                   $sqlOrder
                    ";



            $query = self::query($sql, array($node));
            foreach ($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $user) {

                $query = static::query("
                    SELECT
                        role_id
                    FROM user_role
                    WHERE user_id = :id
                    ", array(':id' => $user->id));
                foreach ($query->fetchAll(\PDO::FETCH_CLASS) as $role) {
                    if ($role->role_id == 'checker') {
                        $user->checker = true;
                    }
                    if ($role->role_id == 'translator') {
                        $user->translator = true;
                    }
                    if ($role->role_id == 'admin') {
                        $user->admin = true;
                    }
                }

                $users[] = $user;
            }
            return $users;
        }

        /*
         * Listado simple de todos los usuarios
         */
        public static function getAllMini() {

            $list = array();

            $query = static::query("
                SELECT
                    user.id as id,
                    user.name as name
                FROM    user
                ORDER BY user.name ASC
                ");

            foreach ($query->fetchAll(\PDO::FETCH_CLASS) as $item) {
                $list[$item->id] = $item->name;
            }

            return $list;
        }

        /*
         * Listado simple de los usuarios que han creado proyectos
         */
        public static function getOwners() {

            $list = array();

            $query = static::query("
                SELECT
                    user.id as id,
                    user.name as name
                FROM    user
                INNER JOIN project
                    ON project.owner = user.id
                ORDER BY user.name ASC
                ");

            foreach ($query->fetchAll(\PDO::FETCH_CLASS) as $item) {
                $list[$item->id] = $item->name;
            }

            return $list;
        }

        /*
         * Listado id-nombre-email de los usuarios que siguen teniendo su email como contraseña
        public static function getWorkshoppers() {

            $list = array();

            $query = static::query("
                SELECT
                    user.id as id,
                    user.name as name,
                    user.email as email
                FROM    user
                WHERE BINARY password = SHA1(user.email)
                ORDER BY user.name ASC
                ");

            foreach ($query->fetchAll(\PDO::FETCH_CLASS) as $item) {
                $list[] = $item;
            }

            return $list;
        }
         */

		/**
		 * Validación de usuario.
		 *
		 * @param string $username Nombre de usuario
		 * @param string $password Contraseña
		 * @return obj|false Objeto del usuario, en caso contrario devolverá 'false'.
		 */
		public static function login ($username, $password) {
            $query = self::query("
                    SELECT
                        id
                    FROM user
                    WHERE BINARY id = :username
                    AND BINARY password = :password",
				array(
					':username' => trim($username),
					':password' => sha1($password)
				)
			);

			if($row = $query->fetch()) {
			    $user = static::get($row['id']);
			    if($user->active) {
			        return $user;
			    } else {
			        Message::Error(Text::get('user-account-inactive'));
			    }
			}
			return false;
		}

		/**
		 * Comprueba si el usuario está identificado.
		 *
		 * @return boolean
		 */
		public static function isLogged () {
			return !empty($_SESSION['user']);
		}

		/**
		 * Refresca la sesión.
		 * (Utilizar después de un save)
		 *
		 * @return type object	User
		 */
		public static function flush () {
    		if(static::isLogged()) {
    			return $_SESSION['user'] = self::get($_SESSION['user']->id);
    		}
    	}

		/**
		 * Verificacion de recuperacion de contraseña
		 *
		 * @param string $username Nombre de usuario
		 * @param string $email    Email de la cuenta
		 * @return boolean true|false  Correctos y mail enviado
		 */
		public static function recover ($username = null, $email = null) {
            $query = self::query("
                    SELECT
                        id,
                        name,
                        email
                    FROM user
                    WHERE (BINARY id = :username
                    OR BINARY email = :email)
                    ",
				array(
					':username' => trim($username),
					':email'    => trim($email)
				)
			);
			if($row = $query->fetchObject()) {
                // tenemos id, nombre, email
                // genero el token
                $token = md5(uniqid()) . '¬' . $row->email;
                self::query('UPDATE user SET token = :token WHERE id = :id', array(':id' => $row->id, ':token' => $token));

                // Obtenemos la plantilla para asunto y contenido
                $template = Template::get(6);

                // Sustituimos los datos
                $subject = $template->title;

                // En el contenido:
                $search  = array('%USERNAME%', '%USERID%', '%RECOVERURL%');
                $replace = array($row->name, $row->id, SITE_URL . '/user/recover/' . base64_encode($token));
                $content = \str_replace($search, $replace, $template->text);
                // Email de recuperacion
                $mail = new Mail();
                $mail->to = $row->email;
                $mail->toName = $row->name;
                $mail->subject = $subject;
                $mail->content = $content;
                $mail->html = true;
                $mail->template = $template->id;
                if ($mail->send($errors)) {
                    return true;
                }
			}
			return false;
		}

		/**
		 * Verificacion de darse de baja
		 *
		 * @param string $email    Email de la cuenta
		 * @return boolean true|false  Correctos y mail enviado
		 */
		public static function leaving ($email, $message = null) {
            $query = self::query("
                    SELECT
                        id,
                        name,
                        email
                    FROM user
                    WHERE BINARY email = :email
                    AND active = 1
                    AND hide = 0
                    ",
				array(
					':email'    => trim($email)
				)
			);
			if($row = $query->fetchObject()) {
                // tenemos id, nombre, email
                // genero el token
                $token = md5(uniqid()) . '¬' . $row->email;
                self::query('UPDATE user SET token = :token WHERE id = :id', array(':id' => $row->id, ':token' => $token));

                // Obtenemos la plantilla para asunto y contenido
                $template = Template::get(9);

                // Sustituimos los datos
                $subject = $template->title;

                // En el contenido:
                $search  = array('%USERNAME%', '%URL%');
                $replace = array($row->name, SITE_URL . '/user/leave/' . base64_encode($token));
                $content = \str_replace($search, $replace, $template->text);
                // Email de recuperacion
                $mail = new Mail();
                $mail->to = $row->email;
                $mail->toName = $row->name;
                $mail->subject = $subject;
                $mail->content = $content;
                $mail->html = true;
                $mail->template = $template->id;
                $mail->send($errors);
                unset($mail);

                // email a los de goteo
                $mail = new Mail();
                $mail->to = \GOTEO_MAIL;
                $mail->toName = 'Admin Goteo';
                $mail->subject = 'El usuario ' . $row->id . ' se da de baja';
                $mail->content = '<p>Han solicitado la baja para el mail <strong>'.$email.'</strong> que corresponde al usuario <strong>'.$row->name.'</strong>';
                if (!empty($message)) $mail->content .= 'y ha dejado el siguiente mensaje:</p><p> ' . $message;
                $mail->content .= '</p>';
                $mail->fromName = "{$row->name}";
                $mail->from = $row->email;
                $mail->html = true;
                $mail->template = 0;
                $mail->send($errors);
                unset($mail);

                return true;
			}
			return false;
		}

    	/**
    	 * Guarda el Token y envía un correo de confirmación.
    	 *
    	 * Usa el separador: ¬
    	 *
    	 * @param type string	$token	Formato: '<md5>¬<email>'
    	 * @return type bool
    	 */
    	private function setToken ($token) {
            if(count($tmp = explode('¬', $token)) > 1) {
                $email = $tmp[1];
                if(Check::mail($email)) {

                    // Obtenemos la plantilla para asunto y contenido
                    $template = Template::get(7);

                    // Sustituimos los datos
                    $subject = $template->title;

                    // En el contenido:
                    $search  = array('%USERNAME%', '%CHANGEURL%');
                    $replace = array($this->name, SITE_URL . '/user/changeemail/' . base64_encode($token));
                    $content = \str_replace($search, $replace, $template->text);



                    $mail = new Mail();
                    $mail->to = $email;
                    $mail->toName = $this->name;
                    $mail->subject = $subject;
                    $mail->content = $content;
                    $mail->html = true;
                    $mail->template = $template->id;
                    $mail->send();

                    return self::query('UPDATE user SET token = :token WHERE id = :id', array(':id' => $this->id, ':token' => $token));
                }
            }
    	}

    	/**
    	 * Token de confirmación.
    	 *
    	 * @return type string
    	 */
    	private function getToken () {
            $query = self::query('SELECT token FROM user WHERE id = ?', array($this->id));
            return $query->fetchColumn(0);
    	}

        /**
         * Cofinanciación.
         *
         * @return type array
         */
    	private function getSupport () {
            $query = self::query('SELECT DISTINCT(project) FROM invest WHERE user = ? AND (status = 0 OR status = 1 OR status = 3 OR status = 4)', array($this->id));
            $projects = $query->fetchAll(\PDO::FETCH_ASSOC);
            $query = self::query('SELECT SUM(amount), COUNT(id) FROM invest WHERE user = ? AND (status = 0 OR status = 1 OR status = 3 OR status = 4)', array($this->id));
            $invest = $query->fetch();
            return array('projects' => $projects, 'amount' => $invest[0], 'invests' => $invest[1]);
        }

	    /**
    	 * Nivel actual de meritocracia. (1-5)
    	 * [Recalcula y actualiza el registro en db]
    	 *
    	 * @return type int	Worth::id
    	 */
    	private function getWorth () {
            $query = self::query('SELECT id FROM worthcracy WHERE amount <= ? ORDER BY amount DESC LIMIT 1', array($this->support['amount']));
            $worth = $query->fetchColumn();
    	    $query = self::query('SELECT worth FROM user WHERE id = ?', array($this->id));
            if($worth !== $query->fetchColumn()) {
                self::query('UPDATE user SET worth = :worth WHERE id = :id', array(':id' => $this->id, ':worth' => $worth));
            }
            return $worth;
        }

        /**
         * Valores por defecto actuales para datos personales
         *
         * @return type array
         */
        public static function getPersonal ($id) {
            $query = self::query('SELECT
                                      contract_name,
                                      contract_nif,
                                      phone,
                                      address,
                                      zipcode,
                                      location,
                                      country
                                  FROM user_personal
                                  WHERE user = ?'
                , array($id));

            $data = $query->fetchObject();
            return $data;
        }

        /**
         * Actualizar los valores personales
         *
         * @params force boolean  (REPLACE data when true, only if empty when false)
         * @return type booblean
         */
        public static function setPersonal ($user, $data = array(), $force = false, &$errors = array()) {

            if ($force) {
                // actualizamos los datos
                $ins = 'REPLACE';
            } else {
                // solo si no existe el registro
                $ins = 'INSERT';
                $query = self::query('SELECT user FROM user_personal WHERE user = ?', array($user));
                if ($query->fetchColumn(0) == $user) {
                    return false;
                }
            }


            $fields = array(
                  'contract_name',
                  'contract_nif',
                  'phone',
                  'address',
                  'zipcode',
                  'location',
                  'country'
            );

            $values = array();
            $set = '';

            foreach ($data as $key=>$value) {
                if (in_array($key, $fields)) {
                    $values[":$key"] = $value;
                    if ($set != '') $set .= ', ';
                    $set .= "$key = :$key";
                }
            }

            if (!empty($values) && $set != '') {
                    $values[':user'] = $user;
                    $sql = "$ins INTO user_personal SET user = :user, " . $set;

                try {
                    self::query($sql, $values);
                    return true;

                } catch (\PDOException $e) {
                    $errors[] = "FALLO al gestionar el registro de datos personales " . $e->getMessage();
                    return false;
                }
            }


        }

        /**
         * Preferencias de notificacion
         *
         * @return type array
         */
        public static function getPreferences ($id) {
            $query = self::query('SELECT
                                      updates,
                                      threads,
                                      rounds,
                                      mailing
                                  FROM user_prefer
                                  WHERE user = ?'
                , array($id));

            $data = $query->fetchObject();
            return $data;
        }

        /**
         * Actualizar las preferencias de notificación
         *
         * @return type booblean
         */
        public static function setPreferences ($user, $data = array(), &$errors = array()) {

            $values = array();
            $set = '';

            foreach ($data as $key=>$value) {
                $values[":$key"] = $value;
                if ($set != '') $set .= ', ';
                $set .= "$key = :$key";
            }

            if (!empty($values) && $set != '') {
                    $values[':user'] = $user;
                    $sql = "REPLACE INTO user_prefer SET user = :user, " . $set;

                try {
                    self::query($sql, $values);
                    return true;

                } catch (\PDOException $e) {
                    $errors[] = "FALLO al gestionar las preferencias de notificación " . $e->getMessage();
                    return false;
                }
            }


        }

		private function getRoles () {

            $roles = array();
            
		    $query = self::query('
		    	SELECT
		    		role.id as id,
		    		role.name as name
		    	FROM role
		    	JOIN user_role ON role.id = user_role.role_id
		    	WHERE user_id = ?
		    ', array($this->id));
            foreach ($query->fetchAll(\PDO::FETCH_OBJ) as $rol) {
                $roles[$rol->id] = $rol;
            }
            return $roles;

		}


        /*
         * Lista de proyectos cofinanciados
         */
        public static function invested($user, $publicOnly = true)
        {
            $projects = array();

            $sql = "SELECT project.id
                    FROM  project
                    INNER JOIN invest
                        ON project.id = invest.project
                        AND invest.user = ?
                        AND (invest.status = 0 OR invest.status = 1)
                    WHERE project.status < 7
                    ";
            if ($publicOnly) {
                $sql .= "AND project.status >= 3
                    ";
            }
            $sql .= "GROUP BY project.id
                    ORDER BY name ASC
                    ";

            /*
             * Restriccion de que no aparecen los que cofinancio que esten en edicion
             *  solamente no sacamos los caducados
             * project.status > 1 AND
             */

            $query = self::query($sql, array($user));
            foreach ($query->fetchAll(\PDO::FETCH_CLASS) as $proj) {
                $projects[] = \Goteo\Model\Project::get($proj->id);
            }
            return $projects;
        }

        public static function calcWorth($userId) {
            $query = self::query('SELECT id FROM worthcracy WHERE amount <= (SELECT SUM(amount) FROM invest WHERE user = ? AND (status = 0 OR status = 1)) ORDER BY amount DESC LIMIT 1', array($userId));
            $worth = $query->fetchColumn();
            self::query('UPDATE user SET worth = :worth WHERE id = :id', array(':id' => $userId, ':worth' => $worth));

            return $worth;
        }

        /**
         * Metodo para cancelar la cuenta de usuario
         * Nos e borra nada, se desactiva y se oculta.
         *
         * @param string $userId
         * @return bool
         */
        public static function cancel($userId) {

            if (self::query('UPDATE user SET active = 0, hide = 1 WHERE id = :id', array(':id' => $userId))) {
                return true;
            } else {
                return false;
            }

        }


	}
}
