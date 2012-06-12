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

	use Goteo\Core\Model,
        Goteo\Core\Exception;

	/*
	 * Clase para gestionar el contenido de las páginas institucionales
	 */
    class Page {

        public
            $id,
            $lang,
            $node,
            $name,
            $description,
            $url,
            $content,
            $pendiente; // para si esta pendiente de traduccion

        static public function get ($id, $lang = \LANG, $node = \GOTEO_NODE) {

            // buscamos la página para este nodo en este idioma
			$sql = "SELECT  page.id as id,
                            IFNULL(page_lang.name, page.name) as name,
                            IFNULL(page_lang.description, page.description) as description,
                            page.url as url,
                            IFNULL(page_node.lang, '$lang') as lang,
                            IFNULL(page_node.node, '$node') as node,
                            IFNULL(page_node.content, original.content) as content
                     FROM page
                     LEFT JOIN page_lang
                        ON  page_lang.id = page.id
                        AND page_lang.lang = :lang
                     LEFT JOIN page_node
                        ON  page_node.page = page.id
                        AND page_node.lang = :lang
                        AND page_node.node = :node
                     LEFT JOIN page_node as original
                        ON  original.page = page.id
                        AND original.node = :node
                        AND original.lang = 'es'
                     WHERE page.id = :id
                ";

			$query = Model::query($sql, array(
                                            ':id' => $id,
                                            ':lang' => $lang,
                                            ':node' => $node
                                        )
                                    );
			$page = $query->fetchObject(__CLASS__);
            return $page;
		}

		/*
		 *  Metodo para la lista de páginas
		 */
		public static function getAll($lang = \LANG, $node = \GOTEO_NODE) {
            $pages = array();

            try {

                $values = array(':lang' => $lang, ':node' => $node);

                $sql = "SELECT
                            page.id as id,
                            IFNULL(page_lang.name, page.name) as name,
                            IFNULL(page_lang.description, page.description) as description,
                            IF(page_node.content IS NULL, 1, 0) as pendiente,
                            page.url as url
                        FROM page
                        LEFT JOIN page_lang
                            ON  page_lang.id = page.id
                            AND page_lang.lang = :lang
                         LEFT JOIN page_node
                            ON  page_node.page = page.id
                            AND page_node.lang = :lang
                            AND page_node.node = :node
                        ORDER BY pendiente DESC, name ASC
                        ";

                $query = Model::query($sql, $values);
                foreach ($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $page) {
                    $pages[] = $page;
                }
                return $pages;
            } catch (\PDOException $e) {
                throw new Exception('FATAL ERROR SQL: ' . $e->getMessage() . "<br />$sql<br /><pre>" . print_r($values, 1) . "</pre>");
            }
		}

        public function validate(&$errors = array()) {

            $allok = true;

            if (empty($this->id)) {
                $errors[] = 'Registro sin id';
                $allok = false;
            }

            if (empty($this->lang)) {
                $errors[] = 'Registro sin lang';
                $allok = false;
            }

            if (empty($this->node)) {
                $errors[] = 'Registro sin node';
                $allok = false;
            }

            return $allok;
        }

		/*
		 *  Esto se usara para la gestión de contenido
		 */
		public function save(&$errors = array()) {
            if(!$this->validate($errors)) { return false; }

  			try {
                $values = array(
                    ':id' => $this->id,
                    ':name' => $this->name,
                    ':description' => $this->description
                );

				$sql = "UPDATE page
                            SET name = :name,
                                description = :description
                            WHERE id = :id
                        ";
				Model::query($sql, $values);

                $values = array(
                    ':page' => $this->id,
                    ':lang' => $this->lang,
                    ':node' => $this->node,
                    ':contenido' => $this->content
                );

				$sql = "REPLACE INTO page_node
                            (page, node, lang, content)
                        VALUES
                            (:page, :node, :lang, :contenido)
                        ";
				if (Model::query($sql, $values)) {
                    return true;
                } else {
                    $errors[] = "Ha fallado $sql con <pre>" . print_r($values, 1) . "</pre>";
                    return false;
                }
                
			} catch(\PDOException $e) {
                $errors[] = 'Error sql al grabar el contenido de la pagina. ' . $e->getMessage();
                return false;
			}

		}

        /**
         * PAra actualizar solamente el contenido
         * @param <type> $errors
         * @return <type>
         */
		public function update($id, $lang, $content, &$errors = array()) {
  			try {
                $values = array(
                    ':page' => $id,
                    ':lang' => $lang,
                    ':node' => \GOTEO_NODE,
                    ':contenido' => $content
                );

				$sql = "REPLACE INTO page_node
                            (page, node, lang, content)
                        VALUES
                            (:page, :node, :lang, :contenido)
                        ";
				if (Model::query($sql, $values)) {
                    return true;
                } else {
                    $errors[] = "Ha fallado $sql con <pre>" . print_r($values, 1) . "</pre>";
                    return false;
                }

			} catch(\PDOException $e) {
                $errors[] = 'Error sql al grabar el contenido de la pagina. ' . $e->getMessage();
                return false;
			}

		}


	}
}