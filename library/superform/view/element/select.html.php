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
?>
<select name="<?php echo htmlspecialchars($this['name']) ?>" id="<?php echo htmlspecialchars($this['name']) ?>_editor"<?php if (isset($this['class'])) echo ' class="' . htmlspecialchars($this['class']) . '"'?>>
    <?php foreach ($this['options'] as $option): ?>
    <option value="<?php echo $option['value'] ?>"<?php if ($option['value'] == $this['value']) echo ' selected="selected"' ?>><?php echo $option['label'] ?></option>
    <?php endforeach ?>
</select>
<script type="text/javascript">
<?php include __DIR__ . '/select.js.src.php' ?>
</script>