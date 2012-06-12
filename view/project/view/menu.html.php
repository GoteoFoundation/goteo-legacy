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

use Goteo\Library\Text; ?>
<div class="project-menu">
    <ul>
        <?php
        foreach (array(
            'home'        => Text::get('project-menu-home'),
            'needs'       => Text::get('project-menu-needs'),
            'supporters'  => Text::get('project-menu-supporters').' <span class="digits">'.$this['supporters'].'</span>',
            'messages'    => Text::get('project-menu-messages').' <span class="digits">'.$this['messages'].'</span>',
            'updates'     => Text::get('project-menu-updates').' <span class="digits">'.$this['updates'].'</span>'
        ) as $id => $show): ?>        
        <li class="<?php echo $id ?><?php if ($this['show'] == $id) echo ' show' ?>">
        	<a href="/project/<?php echo htmlspecialchars($this['project']->id) ?>/<?php echo $id ?>"><?php echo $show ?></a>
        </li>
        <?php endforeach ?>        
    </ul>
</div>
