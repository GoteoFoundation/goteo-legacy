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


namespace Goteo\Core {

    use Goteo\Model\User;

    class ACL {
        protected $resources = array();

        public static function check ($url = \GOTEO_REQUEST_URI, $user = null) {
            $url = static::fixURL($url);

            if(is_null($user)) {
                if(!User::isLogged()) {
                    // @FIXME: Ajuste para permitir un perfil público sin usuario registrado.
                    // (Es provisional hasta que se decida lo contrario)
                    $user = new User();
                    $user->id = '*';
                    $user->roles = array((object) array('id' => 'public', 'name' => 'Perfil público'));
                    $id = $user->id;
                } else {
                    $user = $_SESSION['user'];
                    $id = $user->id;
                }
            } elseif($user instanceof User) {
                $id = $user->id;
            } else if($user = Model\User::get($user)) {
                $id = $user->id;
            }
            $roles = $user->roles;
            array_walk($roles, function (&$role) { $role = $role->id; });
            $query = Model::query("
                SELECT
                    acl.allow
                FROM acl
                WHERE (:node LIKE REPLACE(acl.node_id, '*', '%'))
                AND (:roles REGEXP REPLACE(acl.role_id, '*', '.'))
                AND (:user LIKE REPLACE(acl.user_id, '*', '%'))
                AND (:url LIKE REPLACE(acl.url, '*', '%'))
                ORDER BY acl.id DESC
                LIMIT 1
                ",
                array(
                    ':node'   => \GOTEO_NODE,
                    ':roles'  => implode(', ', $roles),
                    ':user'   => $id,
                    ':url'    => $url
                )
            );
            return (bool) $query->fetchColumn();
        }

        static protected function fixURL ($url) {

            return '/' . trim($url, "/\\ \t\n\r\0\x0B"). '/';
        }

        protected function addperms ($url, $node = \GOTEO_NODE, $role = '*', $user = '*', $allow = true) {

            $url = static::fixURL($url);

            if($user instanceof User) {
                $user = $user->id;
            }

            $sql = "
            INSERT INTO			acl
            					(node_id, role_id, user_id, url, allow)
            VALUES				(:node, :role, :user, :url, :allow)
            ";

            $query = Model::query($sql, array(
                ':node'  => $node,
            	':role'  => $role,
                ':user'	 => $user,
                ':url'	 => $url,
                ':allow' => $allow

            ));

            return (bool) $query->rowCount();

        }

        public static function allow($url, $node = \GOTEO_NODE, $role = '*', $user = '*') {
            return static::addperms($url, $node, $role, $user, true);

        }

        public static function deny($url, $node = \GOTEO_NODE, $role = '*', $user = '*') {
            return static::addperms($url, $node, $role, $user, false);
        }

    }
}