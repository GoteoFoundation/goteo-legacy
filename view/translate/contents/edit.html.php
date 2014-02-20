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

use Goteo\Library\Text,
    Goteo\Library\Content;

$bodyClass = 'admin';

$table = $this['table'];
$id = $this['id'];

$content = Content::get($table, $id, $_SESSION['translator_lang']);

$sizes = array(
    'title'       => 'cols="100" rows="2"',
    'name'        => 'cols="100" rows="1"',
    'description' => 'cols="100" rows="4"',
    'url'         => 'cols="100" rows="1"',
    'text'        => 'cols="100" rows="10"'
);

$fields = Content::_fields();

?>
<div class="widget board">
    <form action="/translate/<?php echo $table ?>/edit/<?php echo $id ?>/<?php echo $this['filter'] . '&page=' . $_GET['page'] ?>" method="post" >
        <input type="hidden" name="table" value="<?php echo $table ?>" />
        <input type="hidden" name="id" value="<?php echo $id ?>" />
        <input type="hidden" name="lang" value="<?php echo $_SESSION['translator_lang'] ?>" />


        <?php foreach ($fields[$table] as $field=>$fieldName) : ?>
        <p>
            <label for="<?php echo 'id'.$field ?>"><?php echo $fieldName ?></label><br />
            <textarea id="<?php echo 'id'.$field ?>" name="<?php echo $field ?>" <?php echo $sizes[$field] ?>><?php echo $content->$field; ?></textarea>
        </p>
        <?php endforeach;  ?>
        <input type="submit" name="save" value="Guardar" />

    </form>
</div>

<div class="widget board">
    <h3>Contenido original</h3>

    <?php foreach ($fields[$table] as $field=>$fieldName) :
        $campo = 'original_'.$field; ?>
        <label for="<?php echo 'id'.$field ?>"><?php echo $fieldName ?>:</label><br />
        <blockquote>
            <?php echo nl2br($content->$campo); ?>
        </blockquote>
        <br />
    <?php endforeach;  ?>


</div>
