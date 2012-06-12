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

?>
<div class="widget board">
    <form method="post" action="/admin/licenses?filter=<?php echo serialize($filters); ?>">

        <input type="hidden" name="action" value="<?php echo $this['action']; ?>" />
        <input type="hidden" name="id" value="<?php echo $this['license']->id; ?>" />
        <input type="hidden" name="order" value="<?php echo $this['license']->order; ?>" />

        <label for="license-group">Grupo:</label><br />
        <select id="license-group" name="group">
            <option value="">Ninguno</option>
            <?php foreach ($this['groups'] as $id=>$name) : ?>
            <option value="<?php echo $id; ?>"<?php if ($id == $this['license']->group) echo ' selected="selected"'; ?>><?php echo $name; ?></option>
            <?php endforeach; ?>
        </select>
<br />
        <label for="license-name">Nombre:</label><br />
        <input type="text" name="name" id="license-name" value="<?php echo $this['license']->name; ?>" />
<br />
        <label for="license-description">Texto tooltip:</label><br />
        <textarea name="description" id="license-description" cols="60" rows="10"><?php echo $this['license']->description; ?></textarea>
<br />
        <label for="license-url">Url:</label><br />
        <input type="text" name="url" id="license-url" value="<?php echo $this['license']->url; ?>" />
<br />
        <label for="license-icons">Tipos:</label><br />
        <select id="license-icons" name="icons[]" multiple size="6">
            <?php foreach ($this['icons'] as $icon) : ?>
            <option value="<?php echo $icon->id; ?>"<?php if (in_array($icon->id, $this['license']->icons)) echo ' selected="selected"'; ?>><?php echo $icon->name; ?></option>
            <?php endforeach; ?>
        </select>


        <input type="submit" name="save" value="Guardar" />
    </form>

</div>