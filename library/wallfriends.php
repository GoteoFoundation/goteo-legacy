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


/*
 * Este modelo es para la
 */
namespace Goteo\Library {

	use Goteo\Model\Invest,
        Goteo\Core\Exception;

    class WallFriends {
		public $investors = array();
		public $avatars = array(); //listado de avatars válidos con su multiplicador de tamaño
		public $max_multiplier = 4; //màxim multiplicador de tamanys
		public $w_size = 30; //tamaño (width) de la imagen mínima en pixeles
		public $h_size = 30; //tamaño (height) de la imagen mínima en pixeles
		public $w_padding = 1;
		public $h_padding = 1;
		/**
         *
         * @param   type mixed  $id     Identificador
         * @return  type object         Objeto
         */
        public function __construct ($id) {
			if($this->investors = Invest::investors($id)) {
				$avatars = array();
				foreach($this->investors as $i) {
					if($i->avatar != 1) {
						$avatars[$i->avatar] = $i->amount;
					}
				}
				$this->avatars = self::pondera($avatars,$this->max_multiplier);
				arsort($this->avatars);

			}
			else {
				//quizá otro mensaje de error?
                throw new \Goteo\Core\Error('404', Text::html('fatal-error-project'));
			}

        }

        /**
         * Pondera un array amb valor minim 1 i valor maxim ?
         * */
        public static function pondera($array = array(),$max_multiplier = 4) {
			$new = array();
			$min = min($array);
			$max = max($array);

			foreach($array as $i => $n) {
				//minim 1, màxim el que toqui
				$num = $n/$min;
				//apliquem alguna funcio que "comprimeixi" els resultats
				$num = round(sqrt($num));
				if($num > $max_multiplier) $num = $max_multiplier;
				$new[$i] = $num;
			}
			return $new;
		}

		/**
		 * Muestra un div con las imagenes en pantalla.
		 * @param type int	$width
		 * @param type int	$height
		 *
		*/
		public function html($width = 200, $height = 200, $mode = 0) {
			$ret = array();
			foreach($this->avatars as $i => $mult) {
				if($mode == 0) {
					$w = $this->w_size;
					$h = $this->h_size;
				}
				else {
					$w = $this->w_size * $mult;
					$h = $this->h_size * $mult;
				}

				$img = '<img style="float:left;display:inline-block;padding:'.$this->h_padding.'px '.$this->w_padding.'px '.$this->h_padding.'px '.$this->w_padding.'px;width:'.$w.'px;height:'.$h.'px;" src="/image/'.$i.'/'.$w.'/'.$h.'/1" />';

				if($mode == 0) {
					for($i = 0; $i<$mult-1; $i++) $img .= $img;
				}

				$ret[] = $img;
			}

			//recalcular width i height
			if($this->max_multiplier * $this->w_size > $width) $width = $this->max_multiplier * $this->w_size > $width;
			//cal que siguin multiples del tamany
			$wsize = $this->w_size + $this->w_padding * 2;
			$width = $wsize * round($width / $wsize);

			return '<div style="background-color:#ccc;display:inline-block;width:'.$width.'px;height:auto;">'.implode("",$ret).'</div>';
		}
		/**
		 * Muestra la imagen en pantalla.
		 * @param type int	$width
		 * @param type int	$height
		 *
		public function display ($width, $height) {
			require_once PEAR . 'Image/Transform.php';
            $it =& \Image_Transform::factory('GD');
            if (\PEAR::isError($it)) {
                die($it->getMessage() . '<br />' . $it->getDebugInfo());
            }
		}
*/


    }
}
