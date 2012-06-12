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

$project = $this['project'];
$types   = $this['types'];
$level = (int) $this['level'] ?: 3;

$minimum    = $project->mincost;
$optimum    = $project->maxcost;

// separar los costes por tipo
$items = array();

foreach ($project->supports as $item) {
    
    $items[$item->type][] = (object) array(
        'name' => $item->support,
        'description' => $item->description
    );
}


?>
<div class="widget project-needs">
        
    <h<?php echo $level ?> class="title"><?php echo Text::get('project-collaborations-title'); ?></h<?php echo $level ?>>
           
    <table width="100%">
        
        <?php foreach ($items as $type => $list): ?>
        
        <thead class="<?php echo htmlspecialchars($type)?>">
            <tr>
                <th class="summary"><?php echo htmlspecialchars($types[$type]) ?></th>
            </tr>            
        </thead>
        
        <tbody>            
            <?php foreach ($list as $item): ?>
            <tr class="noreq">
                <th class="summary"><strong><?php echo htmlspecialchars($item->name) ?></strong>
                <blockquote style="font-weight:normal;"><?php echo $item->description ?></blockquote>
                <a class="button green" href="/project/<?php echo $project->id; ?>/messages"><?php echo Text::get('regular-collaborate'); ?></a>
                </th>
            </tr>            
            <?php endforeach ?>
        </tbody>
        
        <?php endforeach ?>
                                        
    </table>
    
</div>