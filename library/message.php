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

namespace Goteo\Library {

    class Message {

        public
            $type,
            $content;

        function __construct($type, $content) {
            $this->type = $type;
            $this->content = $content;
            $_SESSION['messages'][] = $this;
        }

        public static function Info($text) {
            if(is_array($text) && !empty($text)) {
                foreach($text AS $msg) {
                    new self('info', $msg);
                }
            }
            elseif(!empty($text)) {
                new self('info', $text);
            }
            return true;
        }

        public static function Error($text) {
            if(is_array($text) && !empty($text)) {
                foreach($text AS $msg) {
                    new self('error', $msg);
                }
            }
            elseif(!empty($text)) {
                new self('error', $text);
            }
            return false;
        }
    }

}