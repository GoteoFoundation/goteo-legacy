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
		Goteo\Core\Model,
        Goteo\Core\View;

	class Mail extends \Goteo\Core\Controller {

	    /**
	     * solo si recibe un token válido
	     */
		public function index ($token) {

            if (!empty($token) && !empty($_GET['email'])) {
                $token = base64_decode($token);
                $parts = explode('¬', $token);
                if(count($parts) > 2 && $_GET['email'] == $parts[1] && !empty($parts[2])) {
                    // cogemos el contenido de la bbdd y lo pintamos aqui tal cual
                    if ($query = Model::query('SELECT html FROM mail WHERE email = ? AND id = ?', array($parts[1], $parts[2]))) {
                        $content = $query->fetchColumn();
                        $baja = \SITE_URL . '/user/leave/?email=' . $parts[1];
                        return new View ('view/email/goteo.html.php', array('content'=>$content, 'baja' => $baja));
                    }
                }
            }

            throw new Redirection('/');
		}

    }

}