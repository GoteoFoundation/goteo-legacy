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

$cost = $this['data']['cost'] ?>

<div class="cost <?php echo $cost->type ?>">
    
    
    <div class="title"><strong><?php echo htmlspecialchars($cost->cost) ?></strong></div>
    <input type="submit" class="edit" name="cost-<?php echo $cost->id ?>-edit" value="<?php echo Text::get('regular-edit') ?>" />
    <input type="submit" class="remove weak" name="cost-<?php echo $cost->id ?>-remove" value="<?php echo Text::get('form-remove-button') ?>" />
    
    <div class="description">
        <?php echo htmlspecialchars($cost->description) ?>
        <p><?php echo (int) $cost->amount ?> &euro;
            <strong><?php echo $cost->required ? Text::get('costs-field-required_cost-yes') : Text::get('costs-field-required_cost-no') ?></strong>
        </p>

    </div>
    
    
</div>

    

    