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


namespace Goteo\Model {

    use Goteo\Library\Text;

    class Image extends \Goteo\Core\Model {

        public
			$id,
            $name,
            $type,
            $tmp,
            $error,
            $size,
            $dir_originals,
            $dir_cache;

        public static $types = array('user','project', 'post', 'glossary', 'info');

        /**
         * Constructor.
         *
         * @param type array	$file	Array $_FILES.
         */
        public function __construct ($file) {
			$this->dir_originals = GOTEO_DATA_PATH . 'images' . DIRECTORY_SEPARATOR;
			$this->dir_cache = GOTEO_DATA_PATH . 'cache' . DIRECTORY_SEPARATOR;

            if(is_array($file)) {
                $this->name = self::check_filename($file['name'], $this->dir_originals);
                $this->type = $file['type'];
                $this->tmp = $file['tmp_name'];
                $this->error = $file['error'];
                $this->size = $file['size'];
            }
            elseif(is_string($file)) {
				$this->name = self::check_filename(basename($file), $this->dir_originals);
				$this->tmp = $file;
			}
            //die($this->dir_originals);
            if(!is_dir($this->dir_originals)) {
				mkdir($this->dir_originals);
			}
            if(!is_dir($this->dir_cache)) {
				mkdir($this->dir_cache);
			}
        }

        /**
         * Sobrecarga de métodos 'getter'.
         *
         * @param type string $name
         * @return type mixed
         */
        public function __get ($name) {
            if($name == "content") {
	            return $this->getContent();
	        }
            return $this->$name;
        }

        /**
         * (non-PHPdoc)
         * @see Goteo\Core.Model::save()
         */
        public function save(&$errors = array()) {
            if($this->validate($errors)) {
                if(!empty($this->name)) {
                    $data[':name'] = $this->name;
                }

                if(!empty($this->type)) {
                    $data[':type'] = $this->type;
                }

                if(!empty($this->size)) {
                    $data[':size'] = $this->size;
                }

                if(!empty($this->tmp)) {

					//si es un archivo que se sube
					if(is_uploaded_file($this->tmp)) {
						move_uploaded_file($this->tmp,$this->dir_originals . $this->name);
					}
					//si es un archivo del sistema de archivos o en una URL
					elseif(@copy($this->tmp, $this->dir_originals . $this->name)) {
						$data[':size'] = @filesize($this->dir_originals . $this->name);
						if(function_exists("finfo_open")) {
							$finfo = finfo_open(FILEINFO_MIME_TYPE);
							$data[':type'] = finfo_file($finfo, $this->dir_originals . $this->name);
							finfo_close($finfo);
						}
						elseif(function_exists("mime_content_type")) {
							$data[':type'] = mime_content_type($this->dir_originals . $this->name);
						}
					}
					else {
						//die($this->tmp);
						return false;
					}
                }

                try {

                    // Construye SQL.
                    $query = "REPLACE INTO image (";
                    foreach($data AS $key => $row) {
                        $query .= substr($key, 1) . ", ";
                    }
                    $query = substr($query, 0, -2) . ") VALUES (";
                    foreach($data AS $key => $row) {
                        $query .= $key . ", ";
                    }
                    $query = substr($query, 0, -2) . ")";
                    // Ejecuta SQL.
                    $result = self::query($query, $data);
                    if(empty($this->id)) $this->id = self::insertId();
                    return true;
            	} catch(\PDOException $e) {
                    $errors[] = "No se ha podido guardar el archivo en la base de datos: " . $e->getMessage();
                    return false;
    			}
            }
            return false;
		}

		/**
		* Returns a secure name to store in file system, if the generated filename exists returns a non-existing one
		* @param $name original name to be changed-sanitized
		* @param $dir if specified, generated name will be changed if exists in that dir
		*/
		public static function check_filename($name='',$dir=null){
			$name = preg_replace("/[^a-z0-9_~\.-]+/","-",strtolower(self::idealiza($name)));
			if(is_dir($dir)) {
				while ( file_exists ( "$dir/$name" )) {
					$name = preg_replace ( "/^(.+?)(_?)(\d*)(\.[^.]+)?$/e", "'\$1_'.(\$3+1).'\$4'", $name );
				}
			}
			return $name;
		}

		/**
		 * (non-PHPdoc)
		 * @see Goteo\Core.Model::validate()
		 */
		public function validate(&$errors = array()) {
			if(empty($this->name)) {
                $errors['image'] = Text::get('error-image-name');
            }
			if(is_uploaded_file($this->tmp)) {
				if($this->error !== UPLOAD_ERR_OK) {
					$errors['image'] = $this->error;
				}

				if(!empty($this->type)) {
					$allowed_types = array(
						'image/gif',
						'image/jpeg',
						'image/png',
					);
					if(!in_array($this->type, $allowed_types)) {
						$errors['image'] = Text::get('error-image-type-not-allowed');
					}
				}
				else {
					$errors['image'] = Text::get('error-image-type');
				}

				if(empty($this->tmp) || $this->tmp == "none") {
					$errors['image'] = Text::get('error-image-tmp');
				}

				if(!empty($this->size)) {
					$max_upload_size = 2 * 1024 * 1024; // = 2097152 (2 megabytes)
					if($this->size > $max_upload_size) {
						$errors['image'] = Text::get('error-image-size-too-large');
					}
				}
				else {
					$errors['image'] = Text::get('error-image-size');
				}
			}
            return empty($errors);
		}

		/**
		 * Imagen.
		 *
		 * @param type int	$id
		 * @return type object	Image
		 */
	    static public function get ($id) {
            try {
                $query = static::query("
                    SELECT
                        id,
                        name,
                        type,
                        size
                    FROM image
                    WHERE id = :id
                    ", array(':id' => $id));
                $image = $query->fetchObject(__CLASS__);
                return $image;
            } catch(\PDOException $e) {
                return false;
            }
		}

        /**
         * Galeria de imágenes de un usuario / proyecto
         *
         * @param  varchar(50)  $id    user id |project id
         * @param  string       $which    'user'|'project'
         * @return mixed        false|array de instancias de Image
         */
        public static function getAll ($id, $which) {

            if (!\is_string($which) || !\in_array($which, self::$types)) {
                return false;
            }

            $gallery = array();

            try {
                $sql = "SELECT image FROM {$which}_image WHERE {$which} = ? ORDER BY image ASC";
                $query = self::query($sql, array($id));
                foreach ($query->fetchAll(\PDO::FETCH_ASSOC) as $image) {
                    $gallery[] = self::get($image['image']);
                }

                return $gallery;
            } catch(\PDOException $e) {
                return false;
            }

        }

        /**
         * Quita una imagen de la tabla de relaciones y de la tabla de imagenes
         *
         * @param  string       $which    'user'|'project'|'post'
         * @return bool        true|false
         *
         */
        public function remove($which) {

            try {
                self::query("START TRANSACTION");
                $sql = "DELETE FROM image WHERE id = ?";
                $query = self::query($sql, array($this->id));

                // para usuarios y proyectos que tienen N imagenes
                // por ahora post solo tiene 1
                if (\is_string($which) && \in_array($which, self::$types)) {
                    $sql = "DELETE FROM {$which}_image WHERE image = ?";
                    $query = self::query($sql, array($this->id));
                }
                self::query("COMMIT");

                return true;
            } catch(\PDOException $e) {
                return false;
            }
        }


		/**
		 * Para montar la url de una imagen (porque las url con parametros no se cachean bien)
		 *  - Si el thumb está creado, montamos la url de /data/cache
         *  - Sino, monamos la url de /image/
         *
		 * @param type int $id
		 * @param type int $width
		 * @param type int $height
		 * @param type int $crop
		 * @return type string
		 */
		public function getLink ($width = 200, $height = 200, $crop = false) {

            $tc = $crop ? 'c' : '';
            
            $cache = $this->dir_cache . "{$width}x{$height}{$tc}" . DIRECTORY_SEPARATOR . $this->name;

            if (\file_exists($cache)) {
                return SRC_URL . "/data/cache/{$width}x{$height}{$tc}/{$this->name}";
            } else {
                return SRC_URL . "/image/{$this->id}/{$width}/{$height}/" . $crop;
            }

		}

		/**
		 * Carga la imagen en el directorio temporal del sistema.
		 *
		 * @return type bool
		 */
		public function load () {
		    if(!empty($this->id) && !empty($this->name)) {
    		    $tmp = tempnam(sys_get_temp_dir(), 'Goteo');
                $file = fopen($tmp, "w");
                fwrite($file, $this->content);
                fclose($file);
                if(!file_exists($tmp)) {
                    throw \Goteo\Core\Exception("Error al cargar la imagen temporal.");
                }
                else {
                    $this->tmp = $tmp;
                    return true;
                }
		    }
		}

		/**
		 * Elimina la imagen temporal.
		 *
		 * @return type bool
		 */
    	public function unload () {
    	    if(!empty($this->tmp)) {
                if(!file_exists($this->tmp)) {
                    throw \Goteo\Core\Exception("Error, la imagen temporal no ha sido encontrada.");
                }
                else {
                    unlink($this->tmp);
                    unset($this->tmp);
                    return true;
                }
    	    }
    	    return false;
		}

		/**
		 * Muestra la imagen en pantalla.
		 * @param type int	$width
		 * @param type int	$height
		 */
        public function display ($width, $height, $crop) {
            require_once PEAR . 'Image/Transform.php';
            $it =& \Image_Transform::factory('GD');
            if (\PEAR::isError($it)) {
                die($it->getMessage() . '<br />' . $it->getDebugInfo());
            }

            $cache = $this->dir_cache . $width."x$height" . ($crop ? "c" : "") . DIRECTORY_SEPARATOR;
            if(!is_dir($cache)) mkdir($cache);

			$cache .= $this->name;
			//comprova si existeix  catxe
			if(!is_file($cache)) {
				$it->load($this->dir_originals . $this->name);

				if($crop) {
					if ($width > $height) {

						$f = $height / $width;
						$new_y = round($it->img_x * $f);
						//

						if($new_y < $it->img_y) {
							$at = round(( $it->img_y - $new_y ) / 2);
							$it->crop($it->img_x, $new_y, 0, $at);
							$it->img_y = $new_y;
						}

						$it->resized = false;
						$it->scaleByX($width);

					} else {

						$f = $width / $height;
						$new_x = round($it->img_y * $f);

						if($new_x < $it->img_x) {
							$at = round(( $it->img_x - $new_x ) / 2);
							$it->crop($new_x, $it->img_y, $at, 0);
							$it->img_x = $new_x;
						}

						$it->resized = false;
						$it->scaleByY($height);

					}

				}
				else $it->fit($width,$height);

				$it->save($cache);
            }

			header("Content-type: " . $this->type);
			readfile($cache);
			return true;
		}

		public function isGIF () {
		    return ($this->type == 'image/gif');
		}

    	public function isJPG () {
		    return ($this->type == 'image/jpg') || ($this->type == 'image/jpeg');
		}

    	public function isPNG () {
		    return ($this->type == 'image/png');
		}

    	public function toGIF () {
    	    $this->load();
    	    if(!$this->isGIF()) {
                list($width, $height, $type) = getimagesize($this->tmp);
                switch($type) {
                	case 1:
                		$image = imagecreatefromgif($this->tmp);
                		break;
                	default:
                	case 2:
                		$image = imagecreatefromjpeg($this->tmp);
                		break;
                	case 3:
                		$image = imagecreatefrompng($this->tmp);
                		break;
                	case 6:
                		$image = imagecreatefromwbmp($this->tmp);
                		break;
                }
                $tmp = static::replace_extension($this->tmp, 'gif');
                $this->unload();
                $this->tmp = $tmp;
           		imagegif($image, $this->tmp);
           		imagedestroy($image);
                return true;
    	    }
    	    return;
    	}

        public function toJPG () {
    	    $this->load();
    	    if(!$this->isJPG()) {
                list($width, $height, $type) = getimagesize($this->tmp);
                switch($type) {
                	case 1:
                		$image = imagecreatefromgif($this->tmp);
                		break;
                	default:
                	case 2:
                		$image = imagecreatefromjpeg($this->tmp);
                		break;
                	case 3:
                		$image = imagecreatefrompng($this->tmp);
                		break;
                	case 6:
                		$image = imagecreatefromwbmp($this->tmp);
                		break;
                }
                $tmp = static::replace_extension($this->tmp, 'gif');
                $this->unload();
                $this->tmp = $tmp;
           		imagejpeg($image, $this->tmp, 100);
           		imagedestroy($image);
                return true;
    	    }
    	    return;
    	}

    	public function toPNG () {
    	    $this->load();
    	    if(!$this->isPNG()) {
                list($width, $height, $type) = getimagesize($this->tmp);
                switch($type) {
                	case 1:
                		$image = imagecreatefromgif($this->tmp);
                		break;
                	default:
                	case 2:
                		$image = imagecreatefromjpeg($this->tmp);
                		break;
                	case 3:
                		$image = imagecreatefrompng($this->tmp);
                		break;
                	case 6:
                		$image = imagecreatefromwbmp($this->tmp);
                		break;
                }
                $tmp = static::replace_extension($this->tmp, 'gif');
                $this->unload();
                $this->tmp = $tmp;
           		imagepng($image, $this->tmp, 100);
           		imagedestroy($image);
                return true;
    	    }
    	    return;
    	}

        private function getContent () {
            return file_get_contents($this->dir_originals . $this->name);
    	}

        /*
         * Devuelve la imagen en GIF.
         *
         * @return type object	Image
         */
        static public function gif ($id) {
            $img = static::get($id);
            if(!$img->isGIF())
                $img->toGIF();
            return $img;
        }

        /*
         * Devuelve la imagen en JPG/JPEG.
         *
         * @return type object	Image
         */
        static public function jpg ($id) {
            $img = static::get($id);
            if ($img->isJPG())
                $img->toJPG();
            return $img;
        }

        /*
         * Devuelve la imagen en PNG.
         *
         * @return type object	Image
         */
        static public function png ($id) {
            $img = self::get($id);
            if ($img->isPNG())
                $img->toPNG();
            return $img;
        }

        /**
         * Reemplaza la extensión de la imagen.
         *
         * @param type string	$src
         * @param type string	$new
         * @return type string
         */
    	static private function replace_extension($src, $new) {
    	    $pathinfo = pathinfo($src);
    	    unset($pathinfo["basename"]);
    	    unset($pathinfo["extension"]);
    	    return implode(DIRECTORY_SEPARATOR, $pathinfo) . '.' . $new;
    	}

	}

}
