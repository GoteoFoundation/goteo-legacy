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

    use Goteo\Core\Error,
        Goteo\Model;

    class Image extends \Goteo\Core\Controller {

        public function index($id, $width = 200, $height = 200, $crop = false) {
            if ($image = Model\Image::get($id)) {
                $image->display($width, $height, $crop);
            } else {
                throw new Error(Error::NOT_FOUND);
            }
        }

        public function upload ($width = 200, $height = 200) {

            if (!empty($_FILES) && count($_FILES) === 1) {
                // Do upload
                $image = new Model\Image(current($_FILES));

                if ($image->save()) {
                    return $image->getLink($width, $height);
                }

            }

        }

    }

}
