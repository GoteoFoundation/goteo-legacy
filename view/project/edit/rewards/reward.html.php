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

$reward = $this['data']['reward'];
$types = $this['data']['types'];
?>

<div class="reward <?php echo $reward->icon ?>">
    
    <div class="title"><strong><?php echo $types[$reward->icon]->name . ': ' . htmlspecialchars($reward->reward) ?></strong></div>
    
    <div class="description">
        <p><?php echo htmlspecialchars($reward->description) ?></p>
        <?php if (!empty($reward->units)) : ?>
                <?php echo "{$reward->units} u. x {$reward->amount} &euro; = " . ($reward->units * $reward->amount) ." &euro;<br />"; ?>
                <strong><?php echo Text::get('project-rewards-individual_reward-limited'); ?></strong>
                <?php $units = $reward->units;
                echo Text::html('project-rewards-individual_reward-units_left', $units); ?><br />
            <?php endif; ?>
        <div class="license license_<?php echo $reward->license ?>"><?php echo htmlspecialchars($this['data']['licenses'][$reward->license]) ?></div>
    </div>

    
    <input type="submit" class="edit" name="<?php echo $reward->type ?>_reward-<?php echo $reward->id ?>-edit" value="<?php echo Text::get('regular-edit') ?>" />
    <input type="submit" class="remove weak" name="<?php echo $reward->type ?>_reward-<?php echo $reward->id ?>-remove" value="<?php echo Text::get('form-remove-button') ?>" />
</div>

    

    