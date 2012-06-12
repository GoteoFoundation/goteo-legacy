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
    
    use Goteo\Core\View;
    
    class SuperForm implements \Goteo\Core\Resource, \Goteo\Core\Resource\MIME {
        
        public
            $title,
            $hint,
            $action = '',
            $method = 'post',
            $class,
            $id,
            $elements = array(),
            $footer = array(),
            $level = 1;
        
        public static function uniqId ($prefix) {
            return $prefix . substr(md5(uniqid($prefix, true)), 0, 5);
        }                   
        
        public static function getChildren ($children, $level) {
            
            $elements = array();
            
            if (is_array($children)) {

                foreach ($children as $k => $element) {

                    if (!($element instanceof SuperForm\Element)) {

                        if (!is_array($element)) {
                            throw new SuperForm\Exception;
                        }
                        
                         if (empty($element['type'])) {
                             $element['type'] = '';
                         }

                        $cls = __NAMESPACE__ . rtrim("\\SuperForm\\Element\\{$element['type']}", '\\');

                        if (!class_exists($cls)) {
                            throw new SuperForm\Exception;
                        }
                        
                        if (!isset($element['id'])) {
                            $element['id'] = $k;
                        }
                        
                        $element['level'] = $level;
                        
                        $element = new $cls($element);
                    }

                    $elements[] = $element;
                }
            } else {
                throw new SuperForm\Exception;
            }
            
            return $elements;
            
        }                
        
        public function __construct ($data = array()) {
            
            if (is_array($data) || is_object($data)) {
                
                foreach ($data as $k => $v) {
                    
                    switch ($k) {
                        
                        case 'elements':
                            $this->elements = $v;
                            break;
                        
                        case 'footer':                            
                            $this->footer = $v;
                            break;
                        
                        default:
                            
                            if (property_exists($this, $k)) {
                                $this->$k = $v;
                            }
                            break;
                        
                    }
                    
                }
                
                $this->elements = static::getChildren($this->elements, $this->level + 1);
                $this->footer = static::getChildren($this->footer, $this->level + 1);                
                
            }
            
            if (!isset($this->id)) {
                $this->id = static::uniqId('superform-');                
            }
            
        }
        
        public function __toString () {
            
            return (string) (new View('library/superform/view/superform.html.php', $this));
            
        }        
        
        public function getMIME () {
            return 'text/html';
        }
        
    }
    
    
}