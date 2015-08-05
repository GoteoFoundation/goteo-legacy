<?php
/*
 *  Copyright (C) 2012 Platoniq y Fundación Goteo (see README for details)
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

	use Goteo\Model\Invest,
        Goteo\Model\Project,
        Goteo\Core\Exception;

    class WallFriends {
		public $project = '';
		public $investors = array();
		public $avatars = array(); //listado de avatars válidos con su multiplicador de tamaño
		public $max_multiplier = 32; //màxim multiplicador de tamanys
		public $w_size = 32; //tamaño (width) de la imagen mínima en pixeles
		public $h_size = 32; //tamaño (height) de la imagen mínima en pixeles
		public $w_padding = 0;
		public $h_padding = 0;
		public $show_title = true; //enseña o no el titulo del widget (publi goteo)
		/**
         *
         * @param   type mixed  $id     Identificador
         * @return  type object         Objeto
         */
        public function __construct ($id, $all_avatars=true, $with_title = true) {
			if($this->project = Project::get($id)) {
				$this->show_title = $with_title;
				$this->investors = $this->project->agregateInvestors();

				$avatars = array();
				foreach($this->investors as $i) {
					if($i->avatar->id != 1 || $all_avatars)
						$avatars[$i->user] = $i->amount;

				}
				$this->avatars = self::pondera($avatars,$this->max_multiplier);

				//arsort($this->avatars);

				$keys = array_keys( $this->avatars );
				shuffle( $keys );
				$this->avatars = array_merge( array_flip( $keys ) , $this->avatars );
				//print_r($this->project);die;

			}
			else {
                return false;
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
		 * Retorna les imatges i contingut en html
		 *
		 * $num_icons: el numero de icones per fila del widget
		 * */
		public function html_content($num_icons = 19) {
            $URL = (NODE_ID != GOTEO_NODE) ? NODE_URL : SITE_URL;
			$ret = array();
			foreach($this->avatars as $user => $mult) {
				$style = '';
				$w = $this->w_size;
				$h = $this->h_size;

                $src = $URL . '/image/2/'."$w/$h";
                if($this->investors[$user]->avatar instanceof \Goteo\Model\Image) {
                    if ($this->investors[$user]->avatar->id == 1) {
                        $noface = \Goteo\Model\Image::get(2);
                        $src = $noface->getLink($w,$h, true);
                    } else {
                        $src = $this->investors[$user]->avatar->getLink($w,$h, true);
                    }
                }

                $img = '<a href="'.$URL.'/user/profile/'.$user.'"><img'.$style.' src="' . $src . '" alt="'.$this->investors[$user]->name.'" title="'.$this->investors[$user]->name.'" /></a>';

				for($i = 0; $i<$mult+1; $i++) {

					$ret[] = $img;
					$total = count($ret);
					//cas que es posicioni a partir de la segona columna
					if($num_icons > 14) {
						//final de 1a fila, 2a columna
						if(in_array($total , array($num_icons + 1, $num_icons * 2 - 12, $num_icons * 3 - 25))) {
							$ret[] = '<div class="c"></div>';
						}
						if(in_array($total, array($num_icons * 5 - 38, $num_icons * 6 - 49, $num_icons * 7 - 60))) {
							$ret[] = '<div class="a"></div>';
						}
						if(in_array($total, array($num_icons * 5 - 36, $num_icons * 6 - 47, $num_icons * 7 - 58))) {
							$ret[] = '<div class="b"></div>';
						}
						if(in_array($total , array($num_icons * 9 - 71,$num_icons * 10 - 84))) {
							$ret[] = '<div class="d"></div>';
						}
					}
					//es posiciona a partir de la primera columna (minim tamany possible)
					else {
						if($total == $num_icons) {
							$ret[] = '<div class="c"></div><div class="c"></div><div class="c"></div>';
						}
						if($total == $num_icons * 2 + 1) {
							$ret[] = '<div class="a"></div>';
						}
						if(in_array($total, array($num_icons * 2 + 3, $num_icons * 2 + 5))) {
							$ret[] = '<div class="b"></div><div class="a"></div>';
						}
						if($total == $num_icons * 2 + 7) {
							$ret[] = '<div class="b"></div>';
						}
						if($total == $num_icons * 3 + 8) {
							$ret[] = '<div class="d"></div><div class="d"></div>';
						}

					}
				}
			}

            return $ret;
            
            /*
			//afegim el logo al final de tot
			$final = array();
			$total = count($ret);
			$cols = floor(($total + 3*14 + 3*13 + 2*14)/$num_icons);

			if($num_icons > 14) {
				foreach($ret as $i => $v) {
					if(in_array($i, array($num_icons*($cols-1) - 103,$num_icons*$cols - 107))) {
						$final[] = '<div class="e"></div>';
					}
					$final[] = $v;
				}
			}
			else {
				foreach($ret as $i => $v) {
					if(in_array($i, array($num_icons*($cols-2) - 94,$num_icons*($cols-1) - 98))) {
					//if(in_array($i, array($num_icons*($cols-2) - 94))) {
						$final[] = '<div class="e"></div>';
					}
					$final[] = $v;
				}
			}
			return $final;
            */

		}

		/**
		 * Muestra un div con las imagenes en pantalla.
		 * @param type int	$width
		 * @param type int	$height
		 *
		*/
		public function html($width = 608, $extern = false) {
            $URL = (NODE_ID != GOTEO_NODE) ? NODE_URL : SITE_URL;

            // si es externo, abrimos en una ventana nueva
            $target = $extern ? ' target="_blank"' : '';

			//cal que siguin multiples del tamany
			$wsize = $this->w_size + $this->w_padding * 2;
			$hsize = $this->h_size + $this->h_padding * 2;
			//num icones per fila
			$num_icons = floor($width / $wsize);
			//tamany minim
			if($num_icons < 15) $num_icons = 14;
			//amplada efectiva
			$width = $wsize * $num_icons;

            // estilos estaticos
            $style = '<link rel="stylesheet" type="text/css" href="/view/css/wof.css" />';
            
            // estilos dinamicos
			$style .= '<style type="text/css">';
			$style .= "div.wof>div.ct>a>img {border:0;width:{$this->w_size}px;height:{$this->h_size}px;display:inline-block;padding:{$this->h_padding}px {$this->w_padding}px {$this->h_padding}px {$this->w_padding}px}";
			$style .= "div.wof>div.ct>div.a {display:inline-block;width:" . ($wsize * 5) . "px;height:" . $hsize . "px}";
			$style .= "div.wof>div.ct>div.b {display:inline-block;width:" . ($wsize * 8) . "px;height:" . $hsize . "px}";
			$style .= "div.wof>div.ct>div.c {display:inline-block;width:" . ($wsize * 14) . "px;height:" . $hsize . "px}";
			$style .= "div.wof>div.ct>div.d {display:inline-block;width:" . ($wsize * 14) . "px;height:" . $hsize . "px}";
			$style .= "div.wof>div.ct>div.e {display:inline-block;width:" . ($wsize * 4) . "px;height:" . $hsize . "px}";
			$style .= "div.wof>div.ct>div.i {overflow:hidden;padding:0;margin:0;position:absolute;height:" . ($hsize * 3) . "px;background:#fff;left:" . ($num_icons < 15 ? "0" : $wsize) . "px;top:" . ($hsize * 5) . "px}";
			$style .= "div.wof>div.ct>div.b.i {left:" . ($wsize * ($num_icons <15 ? 6 : 7)) . "px;top:" . ($hsize * 5) . "px}";
			$style .= "div.wof>div.ct>div.c.i {left:" . ($num_icons < 15 ? "0" : $wsize) . "px;top:" . $hsize . "px}";
			$style .= "div.wof>div.ct>div.d.i {left:" . ($num_icons < 15 ? "0" : $wsize) . "px;top:" . ($hsize * 9) . "px;height:" . ($hsize * 2) . "px;background:url(".$URL."/view/css/project/widget/wof_sup_bck.png) no-repeat}";

			$content = $this->html_content($num_icons);
			$cols = floor((count($content)  + 3*13 + 3*11 + 2*13 +2*3) / $num_icons);
            $logotop = ($hsize * ($cols-2));
            if ($logotop < 385) $logotop = 385;
			$style .= "div.wof>div.ct>div.e.i {left:" . (($num_icons - 5) * $wsize) . "px;top:" . ($logotop) . "px;height:" . ($hsize * 2) . "px;background:#fff url(".$URL."/view/css/project/widget/wof_logo.png) center no-repeat}";
			$style .= "div.wof>div.ct>div.c>div.c1 {float:left;height:" . ($wsize * 3) . "px;width:" . ($wsize * 3) . "px}";
			$style .= "div.wof>div.ct>div.c>div.c2 {float:right;height:" . ($wsize * 3) . "px;width:" . ($wsize * 11) . "px}";
			$style .= "</style>";

			$title = '<h2><a href="'.$URL.'/project/'.$this->project->id.'"'.$target.'>'.Text::get('wof-title').'</a><a href="'.$URL.'" class="right"'.$target.'>goteo.org</a></h2>';

            $info = '';
            if ($this->project->status == 3) {
                $info .= '<a class="expand" href="'.$URL.'/project/'.$this->project->id.'/invest" title="'.Text::get('wof-here').'"'.$target.'></a>';
            }

			//num finançadors
			$info .= '<div class="a i"><h3><a href="'.$URL.'/project/'.$this->project->id.'/supporters"'.$target.'>' . $this->project->num_investors . '</a></h3><p><a href="'.$URL.'/project/'.$this->project->id.'/supporters"'.$target.'>'.Text::get('project-view-metter-investors').'</a></p></div>';

			//financiacio, data
			$info .= '<div class="b i"><h3><a href="'.$URL.'/project/'.$this->project->id.'/needs"'.$target.'>' . \amount_format($this->project->invested,0,'',','). '<img src="'.$URL.'/view/css/euro/violet/yl.png" alt="&euro;"></a></h3>';
			$info .= '<p><a href="'.$URL.'/project/'.$this->project->id.'/needs">' . Text::get('project-view-metter-days') . " {$this->project->days} " . Text::get('regular-days') .'</a></p></div>';

			//impulsores, nom, desc
			$info .= '<div class="c i">';
			$info .= '<div class="c1"><p><a href="'.$URL.'/user/'.$this->project->owner.'"'.$target.'><img src="'.$URL.'/image/'.$this->project->user->avatar->id.'/56/56/1" alt="'.$this->project->user->name.'" title="'.$this->project->user->name.'"><br />' . Text::get('regular-by') . ' '  . $this->project->user->name . '</a></p></div>';
			$info .= '<div class="c2"><h3><a href="'.$URL.'/project/'.$this->project->id.'"'.$target.'>' . $this->project->name . '</a></h3><p><a href="'.$URL.'/project/'.$this->project->id.'"'.$target.'>'.$this->project->subtitle.'</a></p></div>';
			$info .= '</div>';

			//apoyar el proyecto
			$info .= '<div class="d i">';
            if ($this->project->status == 3) {
                $info .= '<p>'.Text::get('wof-join-group').'</p>';
                $info .= '<a href="'.$URL.'/project/'.$this->project->id.'/invest"'.$target.'>'.Text::get('wof-support').'</a>';
            } else {
                $info .= '<p>'.Text::get('wof-join-comunity').'</p>';
                $info .= '<a href="'.$URL.'/project/'.$this->project->id.'/updates"'.$target.'>'.Text::get('wof-follow').'</a>';
            }
			$info .= '</div>';

			//logo
			$info .= '<div class="e i">';
			$info .= '</div>';

			return $style . '<div class="wof" style="width:'.$width.'px;">' . ($this->show_title ? $title : '') . '<div class="ct">' . $info . implode("",$content).'</div></div>';
		}
    }
}
