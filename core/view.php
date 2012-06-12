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

    class View extends \ArrayObject implements Resource, Resource\MIME {
        
        private 
            $file;
                        
        public function __construct ($file, $vars = null) {
            
            if (!is_file($file)) {                
                throw new View\Exception("La vista no exists: `{$file}`");            
            }
            
            $this->file = $file;
            
            if (isset($vars)) {
                $this->set($vars);
            }
            
        }
        
        public function set ($var) {
            
            if (is_array($var) || is_object($var)) {
                foreach ($var as $name => $value) {
                    $this[$name] = $value;
                }
            } else if (is_string($var) && func_num_args() >= 2) {
                $this[$var] = func_get_arg(1);
            } else {
                throw new View\Exception;
            }
            
        }
        
        public function getMIME () {
            
            // @todo Adivinar por la extensión
            return 'text/html';
        }
        
        public function __toString () {
            
            ob_start();
            
            include $this->file;
            
            return ob_get_clean();
            
        }
        
        
    }    
}