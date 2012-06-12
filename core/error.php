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
    
    class Error extends Exception {
        
        const
            BAD_REQUEST     = 400,
            UNAUTHORIZED    = 401,
            FORBIDDEN       = 403,
            NOT_FOUND       = 404,
            NOT_ACEPTABLE   = 406,
            CONFLICT        = 409,
            INTERNAL        = 500,
            UNAVAILABLE     = 503;
        
         protected static 
            $messages = array(
                100 => 'Continue',
                101 => 'Switching Protocols',
                102 => 'Processing', // Webdav
                200 => 'OK',
                201 => 'Created',
                202 => 'Accepted',
                203 => 'Non-Authoritative Information',
                204 => 'No Content',
                205 => 'Reset Content',
                206 => 'Partial Content',
                207 => 'Multi-Status', // Webdav
                300 => 'Multiple Choices',
                301 => 'Moved Permanently',
                302 => 'Found',
                303 => 'See Other',
                304 => 'Not Modified',
                305 => 'Use Proxy',
                306 => 'Switch Proxy',
                307 => 'Temporary Redirect',
                400 => 'Bad Request',
                401 => 'Unauthorized',
                402 => 'Payment Required',
                403 => 'Forbidden',
                404 => 'Not Found',
                405 => 'Method Not Allowed',
                406 => 'Not Acceptable',
                407 => 'Proxy Authentication Required',
                408 => 'Request Timeout',
                409 => 'Conflict',
                410 => 'Gone',
                411 => 'Length Required',
                412 => 'Precondition Failed',
                413 => 'Request Entity Too Large',
                414 => 'Request-URI Too Long',
                415 => 'Unsupported Media Type',
                416 => 'Requested Range Not Satisfiable',
                417 => 'Expectation Failed',
                418 => "I'm a teapot", // RFC 2324
                422 => 'Unprocessable Entity', // WebDAV
                423 => 'Locked', // WebDAV
                424 => 'Failed Dependency', // WebDAV
                425 => 'Unordered Collection', // WebDAV
                426 => 'Upgrade Required', // RFC 2817
                500 => 'Internal Server Error',
                501 => 'Not Implemented',
                502 => 'Bad Gateway',
                503 => 'Service Unavailable',
                504 => 'Gateway Timeout',
                505 => 'HTTP Version Not Supported',
                506 => 'Variant Also Negotiates',
                507 => 'Insufficient Storage',
                509 => 'Bandwidth Limit Exceeded', // Apache
                510 => 'Not Extended'
            );
        
        public function __construct ($code = self::INTERNAL, $message = null) {
            
            if ($message === null && isset(static::$messages[$code])) {
                $message = static::$messages[$code];
            }
            
            parent::__construct($message, $code);
            
        }
        
        
    }
    
}