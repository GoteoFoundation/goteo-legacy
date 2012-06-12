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
use Goteo\Library\Text,
    Goteo\Core\View;

$investors = $this['investors'];

// ordenarlos por cantidad
uasort($investors,
    function ($a, $b) {
        if ($a->amount == $b->amount) return 0;
        return ($a->amount > $b->amount) ? -1 : 1;
        }
    );


?>
<div class="widget user-supporters">
    <h3 class="supertitle"><?php echo Text::get('feed-side-top_ten') ?></h3>
    <div class="supporters">
        <ul>
            <?php $c=1; foreach ($investors as $user => $investor):
                if ($user == 'anonymous') continue; ?>
            <li class="activable"><?php echo new View('view/user/widget/supporter.html.php', array('user' => $investor)) ?></li>
            <?php if ($c>=10) break; else $c++; endforeach; ?>
        </ul>
    </div>
    
    <div class="side-worthcracy">
    <?php include 'view/worth/base.html.php' ?>
    </div>
</div>