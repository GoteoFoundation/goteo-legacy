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

use Goteo\Core\View,
    Goteo\Library\Text;

$cuantos = count($this['investors']);
?>
<?php if($cuantos > 0){	?>
<div class="widget user-supporters">
    <h3 class="supertitle"><?php echo Text::get('profile-my_investors-header') . " ($cuantos)" ?></h3>
    <div class="supporters">
        <ul>
            <?php $c=1; // limitado a 6 cofinanciadores en el lateral
            foreach ($this['investors'] as $user => $investor): ?>
            <li class="activable"><?php echo new View('view/user/widget/supporter.html.php', array('user' => $investor, 'worthcracy' => $this['worthcracy'])) ?></li>
            <?php if ($c>5) break; else $c++;
            endforeach ?>
        </ul>
    </div>
    <a class="more" href="/user/profile/<?php echo $this['user']->id ?>/investors"><?php echo Text::get('regular-see_more'); ?></a>
</div>
<?php }?>
