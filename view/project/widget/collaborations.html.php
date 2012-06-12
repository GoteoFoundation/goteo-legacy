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

$level = (int) $this['level'] ?: 3;

$project = $this['project'];

?>
<div class="widget project-collaborations collapsable" id="project-collaborations">
    
    <h<?php echo $level + 1?> class="supertitle"><?php echo Text::get('project-collaborations-supertitle'); ?></h<?php echo $level ?>>

    <h<?php echo $level ?> class="title"><?php echo Text::get('project-collaborations-title'); ?></h<?php echo $level ?>>
    
    <ul>
        <?php foreach ($project->supports as $support) : ?>
        
        <li class="support <?php echo htmlspecialchars($support->type) ?>">
            <strong><?php echo htmlspecialchars($support->support) ?></strong>
            <p><?php echo htmlspecialchars($support->description) ?></p>
        </li>
        <?php endforeach ?>
    </ul>
    
    <a class="more" href="/project/<?php echo $project->id; ?>/needs-non"><?php echo Text::get('regular-see_more'); ?></a>
    <a class="button green" href="/project/<?php echo $project->id; ?>/messages"><?php echo Text::get('regular-collaborate'); ?></a>
    
</div>