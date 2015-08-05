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

	use Goteo\Core\Model,
        Goteo\Core\Exception;

	/*
	 * Clase para gestionar las plantillas de los emails automáticos
	 */
    class Template {

        public
            $id,
            $lang,
            $name,
            $purpose,
            $title,
            $text;

        static public function get ($id, $lang = \LANG) {

            // buscamos la página para este nodo en este idioma
			$sql = "SELECT  template.id as id,
                            template.name as name,
                            template.group as `group`,
                            template.purpose as purpose,
                            IFNULL(template_lang.title, template.title) as title,
                            IFNULL(template_lang.text, template.text) as text
                     FROM template
                     LEFT JOIN template_lang
                        ON  template_lang.id = template.id
                        AND template_lang.lang = :lang
                     WHERE template.id = :id
                ";

			$query = Model::query($sql, array(
                                            ':id' => $id,
                                            ':lang' => $lang
                                        )
                                    );
			$template = $query->fetchObject(__CLASS__);
            return $template;
		}

		/*
		 *  Metodo para la lista de páginas
		 */
		public static function getAll($filters = array()) {
            $templates = array();

            try {

                $values = array(':lang' => \LANG);
                $sqlFilter = '';
                $and = "WHERE";
                if (!empty($filters['group'])) {
                    $sqlFilter .= " $and template.`group` = :group";
                    $and = "AND";
                    $values[':group'] = "{$filters['group']}";
                }
                if (!empty($filters['name'])) {
                    $sqlFilter .= " $and (template.`name` LIKE :name OR template.`purpose` LIKE :name OR template.`title` LIKE :name)";
                    $and = "AND";
                    $values[':name'] = "%{$filters['name']}%";
                }
                
                $sql = "SELECT
                            template.id as id,
                            template.name as name,
                            template.purpose as purpose,
                            IFNULL(template_lang.title, template.title) as title,
                            IFNULL(template_lang.text, template.text) as text
                        FROM template
                        LEFT JOIN template_lang
                            ON  template_lang.id = template.id
                            AND template_lang.lang = :lang
                        $sqlFilter
                        ORDER BY name ASC
                        ";

                $query = Model::query($sql, $values);
                foreach ($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $template) {
                    $templates[] = $template;
                }
                return $templates;
            } catch (\PDOException $e) {
                throw new Exception(Text::_('No se ha grabado correctamente. ') . $e->getMessage() . "<br />$sql<br /><pre>" . print_r($values, 1) . "</pre>");
            }
		}

		/*
		 *  Lista de plantillas para filtro
		 */
		public static function getAllMini() {
            $templates = array();

            try {
                $sql = "SELECT
                            template.id as id,
                            template.name as name
                        FROM template
                        ORDER BY name ASC
                        ";

                $query = Model::query($sql);
                foreach ($query->fetchAll(\PDO::FETCH_OBJ) as $template) {
                    $templates[$template->id] = $template->name;
                }
                return $templates;
            } catch (\PDOException $e) {
                throw new Exception(Text::_('No se ha grabado correctamente. ') . $e->getMessage() );
            }
		}

        public function validate(&$errors = array()) {

            $allok = true;

            if (empty($this->id)) {
                $errors[] = Text::_('Registro sin id');
                $allok = false;
            }

            if (empty($this->title)) {
                $errors[] = Text::_('Registro sin titulo');
                $allok = false;
            }

            if (empty($this->text)) {
                $errors[] = Text::_('Registro sin contenido');
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
                    ':template' => $this->id,
                    ':name' => $this->name,
                    ':group' => $this->group,
                    ':purpose' => $this->purpose,
                    ':title' => $this->title,
                    ':text' => $this->text
                );

				$sql = "REPLACE INTO template
                            (id, name, purpose, title, text, `group`)
                        VALUES
                            (:template, :name, :purpose, :title, :text, :group)
                        ";
				if (Model::query($sql, $values)) {
                    return true;
                } else {
                    $errors[] = "Ha fallado $sql con <pre>" . print_r($values, 1) . "</pre>";
                    return false;
                }
                
			} catch(\PDOException $e) {
                $errors[] = 'Error sql al grabar el contenido de la palntilla. ' . $e->getMessage();
                return false;
			}

		}

        /*
         * Grupos de plantillas
         */
        static public function groups()
        {
            $groups = array(
                'general' => Text::_('Propósito general'),
                'access'  => Text::_('Registro y acceso usuario'),
                'project' => Text::_('Actividad proyecto'),
                'tips'    => Text::_('Auto-tips difusión'),
                'invest'  => Text::_('Proceso aporte'),
                'contact' => Text::_('Comunicación'),
                'advice'  => Text::_('Avisos al autor')
            );

            \asort($groups);

            return $groups;
        }

	}
}