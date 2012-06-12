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

use Goteo\Library\Text;

$bodyClass = 'admin';

// no cache para textos
define('GOTEO_ADMIN_NOCACHE', true);

$text = new stdClass();

$text->id = $this['id'];
$text->purpose = Text::getPurpose($this['id']);
$text->text = Text::getTrans($this['id']);

?>
<div class="widget board">
    <fieldset>
        <legend>Texto en español</legend>
        <blockquote><?php echo htmlentities(utf8_decode($text->purpose)); ?></blockquote>
    </fieldset>

    <form action="/translate/texts/edit/<?php echo $text->id ?>/<?php echo $this['filter'] . '&page=' . $_GET['page'] ?>" method="post" >
        <input type="hidden" name="lang" value="<?php echo $_SESSION['translator_lang'] ?>" />
        <textarea name="text" cols="100" rows="10"><?php echo $text->text; ?></textarea><br />
        <input type="submit" name="save" value="Guardar" />

    </form>
</div>