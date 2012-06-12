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

$reward = $this['data']['reward'] ?>

<div class="reward <?php echo $reward->icon ?>">
    
    <div class="title"><strong><?php echo htmlspecialchars($reward->icon_name) . ': ' . htmlspecialchars($reward->reward) ?></strong></div>
    
    <div class="description">
        <p><?php echo htmlspecialchars($reward->description) ?></p>
    </div>

    
    <input type="submit" class="edit" name="<?php echo $reward->type ?>_reward-<?php echo $reward->id ?>-edit" value="<?php echo Text::get('regular-edit') ?>" />
</div>

    

    