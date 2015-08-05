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
	 * Clase para gestionar la traducción de registros de tablas de contenido
     *
     * Ojo, todos los campos de traduccion son texto (a ver como sabemos si corto o largo...)
     *
	 */
    class Content {

        public static function _tables() {
         return array(
                'promote'   => Text::_('Proyectos destacados'),
                'patron'    => Text::_('Proyectos apadrinados'),
                'icon'      => Text::_('Tipos de retorno/recompensa'),
                'license'   => Text::_('Licencias'),
                'category'  => Text::_('Categorías'),
                'news'      => Text::_('Noticias'),
                'faq'       => Text::_('Faq'),
                'post'      => Text::_('Blog'),
                'banner'    => Text::_('Banners'),
                'tag'       => Text::_('Tags'),
                'criteria'  => Text::_('Criterios de evaluación'),
                'worthcracy'=> Text::_('Meritocrácia'),
                'template'  => Text::_('Plantillas emails automáticos'),
                'glossary'  => Text::_('Glosario de términos'),
                'info'      => Text::_('Ideas de about')
            );
        }

        public static function _fields() {

            return array(
                'banner' => array (
                    'title' => Text::_('Título'),
                    'description' => Text::_('Descripción')
                ),
                'promote' => array (
                    'title' => Text::_('Título'),
                    'description' => Text::_('Descripción')
                ),
                'patron' => array (
                    'title' => Text::_('Título'),
                    'description' => Text::_('Descripción')
                ),
                'icon' => array (
                    'name' => Text::_('Nombre'),
                    'description' => Text::_('Descripción')
                ),
                'license' => array (
                    'name' => Text::_('Nombre'),
                    'description' => Text::_('Descripción'),
                    'url' => 'Enlace'
                ),
                'category' => array (
                    'name' => Text::_('Nombre'),
                    'description' => Text::_('Descripción')
                ),
                'news' => array (
                    'title' => Text::_('Título'),
                    'description' => Text::_('Entradilla')
                ),
                'faq' => array (
                    'title' => Text::_('Título'),
                    'description' => Text::_('Descripción')
                ),
                'post' => array (
                    'title' => Text::_('Título'),
                    'text' => Text::_('Texto entrada'),
                    'legend' => Text::_('Leyenda media')
                ),
                'tag' => array (
                    'name' => Text::_('Nombre')
                ),
                'criteria' => array (
                    'title' => Text::_('Título')
                ),
                'worthcracy' => array (
                    'name' => Text::_('Nombre')
                ),
                'template' => array (
                    'title' => Text::_('Título'),
                    'text' => Text::_('Contenido')
                ),
                'glossary' => array (
                    'title' => Text::_('Título'),
                    'text' => Text::_('Contenido'),
                    'legend' => Text::_('Leyenda media')
                ),
                'info' => array (
                    'title' => Text::_('Título'),
                    'text' => Text::_('Contenido'),
                    'legend' => Text::_('Leyenda media')
                )
            );
        }

        public static function _types() {
            return array(
                'description' => Text::_('Descripción'),
                'url'         => Text::_('Enlace'),
                'name'        => Text::_('Nombre'),
                'text'        => Text::_('Texto extenso'),
                'legend'      => Text::_('Leyenda'),
                'title'       => Text::_('Título')
            );
        }

        /*
         * Para sacar un registro
         */
        static public function get ($table, $id, $lang = 'original') {

            $fields = static::_fields();

            // buscamos el contenido para este registro de esta tabla
			$sql = "SELECT  
                        {$table}.id as id,
                        ";

            foreach ($fields[$table] as $field=>$fieldName) {
                $sql .= "IFNULL({$table}_lang.$field, {$table}.$field) as $field,
                         {$table}.$field as original_$field,
                        ";
            }

            $sql .= "IFNULL({$table}_lang.lang, '$lang') as lang
                     FROM {$table}
                     LEFT JOIN {$table}_lang
                        ON {$table}_lang.id = {$table}.id
                        AND {$table}_lang.lang = :lang
                     WHERE {$table}.id = :id
                ";

			$query = Model::query($sql, array(
                                            ':id' => $id,
                                            ':lang' => $lang
                                        )
                                    );
			$content = $query->fetchObject(__CLASS__);
            $content->table = $table;
            
            return $content;
		}

		/*
		 *  Metodo para la lista de registros de las tablas de contenidos
		 */
		public static function getAll($filters = array(), $lang = 'original') {

            $tables = static::_tables();
            $fields = static::_fields();

            $contents = array(
                'ready' => array(),
                'pending' => array()
            );

            /// filters:  type  //tipo de campo
            //          , table //tabla o modelo o concepto
            //          , text //cadena de texto

            // si hay filtro de tabla solo sacamos de una tabla

            // si hay filtro de tipo, solo las tablas que tengan ese tipo y solo ese tipo en los resultados

            // si hay filtro de texto es para todas las sentencias

            // y todos los campos sacan el contenido "purpose" si no tienen del suyo

            try {

                \asort($tables);
                
                foreach ($tables as $table=>$tableName) {
                    if (!self::checkLangTable($table)) continue;
                    if (!empty($filters['type']) && !isset($fields[$table][$filters['type']])) continue;
                    if (!empty($filters['table']) && $table != $filters['table']) continue;

                    $sql = "";
                    $primercampo = "";
                    $values = array();

                    $sql .= "SELECT
                                {$table}.id as id,
                                ";

                    foreach ($fields[$table] as $field=>$fieldName) {
                        $sql .= "IFNULL({$table}_lang.$field, {$table}.$field) as $field,
                                IF({$table}_lang.$field IS NULL, 0, 1) as {$field}ready,
                                ";
                        $primercampo = ($primercampo == '') ?: "{$field}ready";
                    }

                    $sql .= "CONCAT('{$table}') as `table`
                            ";

                    $sql .= "FROM {$table}
                             LEFT JOIN {$table}_lang
                                ON {$table}_lang.id = {$table}.id
                                AND {$table}_lang.lang = '$lang'
                             WHERE {$table}.id IS NOT NULL
                        ";

                        // solo entradas de goteo en esta gestión
                        if ($table == 'post') {
                            $sql .= "AND post.blog = 1
                                ";
                        }
                        if ($table == 'info') {
                            $sql .= "AND info.node = '".\GOTEO_NODE."'
                                ";
                        }

                    // para cada campo
                        $and = "AND";
                    if (!empty($filters['text'])) {
                        foreach ($fields[$table] as $field=>$fieldName) {
                            $sql .= " $and ( {$table}_lang.{$field} LIKE :text{$field} OR ({$table}_lang.{$field} IS NULL AND {$table}.{$field} LIKE :text{$field} ))
                                ";
                            $values[":text{$field}"] = "%{$filters['text']}%";
                            $and = "OR";
                        }
                    }

                    // ojo originales vacios
                    foreach ($fields[$table] as $field=>$fieldName) {
                        $sql .= " AND {$table}.{$field} IS NOT NULL
                            ";
                    }

                    // pendientes de traducir
                    if (!empty($filters['pending'])) {
                        $sql .= " HAVING $primercampo = 0";
                    }

                    $sql .= " ORDER BY id ASC";

                    /*
                    echo $sql . '<br /><br />';
                    var_dump($values);
                    echo '<br /><br />';
                     *
                     */
                    
                    $query = Model::query($sql, $values);
                    foreach ($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $content) {

                        foreach ($fields[$table] as $field=>$fieldName) {
                            if (!empty($filters['type']) && $field != $filters['type']) continue;

                            $data = array(
                                'table' => $table,
                                'tableName' => $tableName,
                                'id' => $content->id,
                                'field' => $field,
                                'fieldName' => $fieldName,
                                'value' => $content->$field
                            );

                            $campoready = $field . 'ready';

                            $group = $content->$campoready == 1 ? 'ready' : 'pending';

                            $contents[$group][] = (object) $data;

                        }

                    }

                }

                return $contents;
            } catch (\PDOException $e) {
                throw new Exception('FATAL ERROR SQL: ' . $e->getMessage() . "<br />$sql<br /><pre>" . print_r($values, 1) . "</pre>");
            }
		}

        public function validate(&$errors = array()) {
            return true;
        }

		/*
		 *  Esto se usa para actualizar datos en cualquier tabla de contenido
		 */
		public static function save($data, &$errors = array()) {

            $fields = static::_fields();

            if (empty($data)) {
                $errors[] = "Sin datos";
                return false;
            }
            if (empty($data['lang']) || $data['lang'] == 'original') {
                $errors[] = "No se peude traducir el contenido original, seleccionar un idioma para traducir";
                return false;
            }

  			try {
                // tenemos el id en $this->id  (el campo id siempre se llama id)
                // tenemos el lang en $this->lang
                // tenemos el nombre de la tabla en $this->table
                // tenemos los campos en $fields[$table] y el contenido de cada uno en $this->$field

                $set = '`id` = :id, `lang` = :lang ';
                $values = array(
                    ':id' => $data['id'],
                    ':lang' => $data['lang']
                );

                foreach ($fields[$data['table']] as $field=>$fieldDesc) {
                    if ($set != '') $set .= ", ";
                    $set .= "`$field` = :$field ";
                    $values[":$field"] = $data[$field];
                }

				$sql = "REPLACE INTO {$data['table']}_lang SET $set";
				if (Model::query($sql, $values)) {
                    return true;
                } else {
                    $errors[] = "Ha fallado $sql con <pre>" . print_r($values, 1) . "</pre>";
                    return false;
                }
                
			} catch(\PDOException $e) {
                $errors[] = 'Error sql al grabar el contenido multiidioma. ' . $e->getMessage();
                return false;
			}

		}


        public static function checkLangTable($table) {
            //assume yes
            return true;
        }

	}
}