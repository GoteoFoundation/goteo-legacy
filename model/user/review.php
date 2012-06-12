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


namespace Goteo\Model\User {

    use Goteo\Model;

    class Review extends \Goteo\Core\Model {

        public
            $id,
            $user,
            $name,
            $ready;


        /**
         * Get the reviews for a user
         * @param varcahr(50) $id  user identifier
         * @return array of reviews identifiers
         */
	 	public static function get ($id) {
            $array = array ();
            try {
                $query = static::query("SELECT review FROM user_review WHERE user = ?", array($id));
                $reviews = $query->fetchAll();
                foreach ($reviews as $int) {
                    $array[$int[0]] = $int[0];
                }

                return $array;
            } catch(\PDOException $e) {
				throw new \Goteo\Core\Exception($e->getMessage());
            }
		}

		public function validate(&$errors = array()) {
            // Estos son errores que no permiten continuar
            if (empty($this->id))
                $errors[] = 'No hay una revision para asignar';
                //Text::get('validate-review-noid');

            if (empty($this->user))
                $errors[] = 'No hay ningun usuario al que asignar';
                //Text::get('validate-review-nouser');

            //cualquiera de estos errores hace fallar la validación
            if (!empty($errors))
                return false;
            else
                return true;
        }

		public function save (&$errors = array()) {
            if (!$this->validate($errors)) return false;

            $values = array(':user'=>$this->user, ':review'=>$this->id);

			try {
	            $sql = "REPLACE INTO user_review (user, review) VALUES(:user, :review)";
				self::query($sql, $values);
				return true;
			} catch(\PDOException $e) {
				$errors[] = "La revisión {$this->id} no se ha asignado correctamente. Por favor, revise el metodo User\Review->save." . $e->getMessage();
				return false;
			}

		}

		/**
		 * Quitarle una revision al usuario
		 *
		 * @param varchar(50) $user id del usuario
		 * @param INT(12) $id  identificador de la tabla review
		 * @param array $errors 
		 * @return boolean
		 */
		public function remove (&$errors = array()) {
			$values = array (
				':user'=>$this->user,
				':review'=>$this->id,
			);

            try {
                self::query("DELETE FROM user_review WHERE review = :review AND user = :user", $values);
				return true;
			} catch(\PDOException $e) {
                $errors[] = 'No se ha podido desasignar la revision ' . $this->id . ' del usuario ' . $this->user . ' ' . $e->getMessage();
                //Text::get('remove-review-fail');
                return false;
			}
		}

        /*
         * Dar por lista una revision
         */
		public function ready (&$errors = array()) {
			$values = array (
				':user'=>$this->user,
				':review'=>$this->id,
			);

            try {
                self::query("UPDATE user_review SET ready = 1 WHERE review = :review AND user = :user", $values);

                // recalcular puntuacion global de la revision
                Model\Review::recount($this->id, $errors);

				return true;
			} catch(\PDOException $e) {
                $errors[] = 'No se ha podido marcar la revision ' . $this->id . ' del usuario ' . $this->user . ' como lista. ' . $e->getMessage();
                //Text::get('review-set_ready-fail');
                return false;
			}
		}

        /*
         * Reabrir una revision
         */
		public function unready (&$errors = array()) {
			$values = array (
				':user'=>$this->user,
				':review'=>$this->id,
			);

            try {
                self::query("UPDATE user_review SET ready = 0 WHERE review = :review AND user = :user", $values);

                // recalcular puntuacion global de la revision
                Model\Review::recount($this->id, $errors);

				return true;
			} catch(\PDOException $e) {
                $errors[] = 'No se ha podido reabrir la revision ' . $this->id . ' del usuario ' . $this->user . '. ' . $e->getMessage();
                //Text::get('review-set_unready-fail');
                return false;
			}
		}

        /*
         * Lista de usuarios que tienen asignada cierta revision
         */
        public static function checkers ($review) {
             $array = array ();
            try {
               $sql = "SELECT 
                            DISTINCT(user_review.user) as id,
                            user_review.ready as ready
                        FROM user_review
                        WHERE user_review.review = :id
                        ";
                $query = static::query($sql, array(':id'=>$review));
                foreach ($query->fetchAll(\PDO::FETCH_ASSOC) as $share) {

                    // nombre i avatar
                    $user = \Goteo\Model\User::getMini($share['id']);

                    $array[$share['id']] = (object) array(
                        'user'   => $share['id'],
                        'avatar' => $user->avatar,
                        'name'   => $user->name,
                        'ready'  => $user->ready
                    );
                }

                return $array;
            } catch(\PDOException $e) {
				throw new \Goteo\Core\Exception($e->getMessage());
            }
        }

        /*
         * Devuelve true o false si es legal que este usuario haga algo con esta revision
         */
        public static function is_legal ($user, $review) {
            $sql = "SELECT user, review FROM user_review WHERE user = :user AND review = :review";
            $values = array(
                ':user' => $user,
                ':review' => $review
            );
            $query = static::query($sql, $values);
            $legal = $query->fetchObject();
            if ($legal->user == $user && $legal->review == $review) {
                return true;
            } else {
                return false;
            }
        }

        /*
         * Graba un comentario para una sección
         */
         public function setComment ($section, $field, $text) {

             if (empty($this->user) || empty($this->id)) {
                 return false;
             }

             // primero comprobbar si ya hay registro,
            $sql = "SELECT COUNT(*) as cuantos FROM review_comment WHERE user = :user AND review = :review AND section = :section";
             $values = array(
                 ':user'    => $this->user,
                 ':review'  => $this->id,
                 ':section' => $section
             );

            $query = static::query($sql, $values);
            $exist = $query->fetchObject();

            if ($exist->cuantos == 1) {
                // si lo hay, update de este campo y texto
                 $sql = "UPDATE review_comment SET
                            `$field` = :text
                         WHERE review = :review
                         AND user = :user
                         AND section = :section
                         ";
            } else {
                // si no lo hay lo Insertamos con este campo y texto
                 $sql = "INSERT INTO review_comment SET
                            `$field` = :text,
                            review = :review,
                            user = :user,
                            section = :section
                         ";
            }

             $values[':text'] = $text;

             if (self::query($sql, $values)) {
                 return true;
             } else {
                 return false;
             }

         }

        /*
         * Graba la puntuacion para un criterio
         */
         public function setScore ($criteria, $score) {

             if (empty($this->user) || empty($this->id)) {
                 return false;
             }

             if ($score == true) {
                 $sql = "REPLACE INTO review_score SET
                            score = '1',
                            review = :review,
                            user = :user,
                            criteria = :criteria
                        ";
             } else {
                $sql = "DELETE FROM review_score
                            WHERE review = :review
                            AND user = :user
                            AND criteria = :criteria
                        ";
             }

             $values = array(
                 ':user'     => $this->user,
                 ':review'   => $this->id,
                 ':criteria' => $criteria

             );
             if (self::query($sql, $values)) {
                 return true;
             } else {
                 return false;
             }

         }

        /*
         * Metodo para contar la puntuacion dada por este revisor
         *
         * score es la puntuacion total
         * max es el maximo depuntuacio que podria haber obtenido
         *
         */
        public function recount (&$errors = array()) {
            try {
                $score = 0;
                $max   = 0;

                $sql = "SELECT
                            COUNT(criteria.id) as `max`,
                            COUNT(review_score.score) as score
                        FROM criteria
                        LEFT JOIN review_score
                            ON review_score.criteria = criteria.id
                            AND review_score.review = :review
                            AND review_score.user = :user
                        ";

                $query = static::query($sql, array(
                    ':review' => $this->id,
                    ':user'  => $this->user
                ));

                return $query->fetchObject();

            } catch(\PDOException $e) {
                $errors[] = "No se ha aplicado la puntuacion. " . $e->getMessage();
                return false;
            }
        }

	}
    
}