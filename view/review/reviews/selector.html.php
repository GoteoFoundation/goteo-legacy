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
<div class="widget reviews">
    <div id="review-selector">
        <form id="selector-form" name="selector_form" action="<?php echo '/review/'.$this['section'].'/'.$this['option'].'/select'; ?>" method="post">
        <label for="selector">Revisión:</label>
        <select id="selector" name="review" onchange="document.getElementById('selector-form').submit();">
        <?php foreach ($this['reviews'] as $review) : ?>
            <option value="<?php echo $review->id; ?>"<?php if ($review->id == $_SESSION['review']->id) echo ' selected="selected"'; ?>><?php echo $review->name; ?></option>
        <?php endforeach; ?>
        </select>
        <!-- un boton para seleccionar si no tiene javascript -->
        </form>
    </div>
</div>
