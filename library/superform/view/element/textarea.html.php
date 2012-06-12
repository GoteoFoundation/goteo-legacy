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
<textarea <?php if ($this['cols'] > 0) echo ' cols="' . ((int) $this['cols']) . '"' ?><?php if ($this['rows'] > 0) echo ' rows="' . ((int) $this['rows']) . '"' ?>name="<?php echo htmlspecialchars($this['name']) ?>" id="<?php echo htmlspecialchars($this['name']) ?>_editor"<?php if (isset($this['class'])) echo ' class="' . htmlspecialchars($this['class']) . '"'?>><?php if (isset($this['value'])) echo $this['value'] ?></textarea>
<script type="text/javascript">
<?php include __DIR__ . '/textarea.js.src.php' ?>
</script>