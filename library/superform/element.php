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


namespace Goteo\Library\SuperForm {
    
    use Goteo\Library\SuperForm,
        Goteo\Core\View;
    
    class Element implements \ArrayAccess, \Goteo\Core\Resource {
                
        public            
            $id,
            $type,
            $title,            
            $class = '',
            $hint,
            $required = false,
            $ok = false,
            $errors = array(),
            $children = array(),
            $level = 2,
            $view,
            $data = array();
        
        public function offsetGet ($name) {
            return isset($this->$name) ? $this->name : null;
        }
        
        public function offsetSet ($name, $value) {
            $this->$name = $value;
        }
        
        public function offsetExists ($name) {
            return property_exists($this, $name);
        }
        
        public function offsetUnset ($name) {
            unset($this->$name);
        }
                        
        public function __construct ($data = array()) {
            
            foreach ($data as $k => $v) {
                switch ($k) {
                    
                    case 'children':
                        $this->children = $v; 
                        break;                    
                    
                    default:
                        if (property_exists($this, $k)) {
                            $this->$k = $v;
                        }                              
                }                                
                
            }
            
            $this->children = SuperForm::getChildren($this->children, $this->level + 1);
            
            $this->type = $this->getType();
            
            if (!isset($this->view)) {
                $this->view = $this->getView();                
            }
            
        }
        
        public function getView () {
            $viewPath = strtolower(str_replace('\\', '/', trim(substr(get_called_class(), strlen(__CLASS__)), '\\')));                
            return realpath("library/superform/view/element/{$viewPath}.html.php");            
        }
        
        public function getType () {            
            return strtolower(str_replace('\\', '-', substr(get_called_class(), strlen(__CLASS__) + 1)));
        }        
        
        public function getInnerHTML () {
            
            if ($this->view !== false) {
                if ($this->view instanceof View) {
                    return (string) $this->view;
                } else {
                    return (string) (new View($this->view, $this));
                }                
            }
            
            return '';
        }
        
        public function __toString () {                                               
            return (string) (new View('library/superform/view/element.html.php', array('element' => $this)));            
        }
        
    }
    
}