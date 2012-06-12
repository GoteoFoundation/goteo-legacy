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

if (count($this) > 0): ?>
<div class="elements">   
    <ol>
        <?php foreach ($this as $element): ?>
        <!-- SFEL#<?php echo $element->id ?> -->
        <li class="element<?php echo rtrim(' ' . htmlspecialchars($element->type)) .  rtrim(' ' . htmlspecialchars($element->class)) ?><?php if ($element->required) echo ' required' ?><?php if ($element->ok) echo ' ok' ?><?php if (!empty($element->errors)) echo ' error' ?>" id="<?php echo htmlspecialchars($element->id) ?>" name="<?php echo htmlspecialchars($element->id) ?>">
            <?php echo (string) $element ?>           
        </li>
        <!-- /SFEL#<?php echo $element->id ?> -->
        <?php endforeach ?>
    </ol>
</div>
<?php endif ?>