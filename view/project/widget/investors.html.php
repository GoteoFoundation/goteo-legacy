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
    Goteo\Library\Text,
    Goteo\Library\Worth;

$project = $this['project'];

$level = (int) $this['level'] ?: 3;

$supporters = count($project->investors);

$worthcracy = Worth::getAll();

?>
<div class="widget project-investors collapsable">
    
    <h<?php echo $level+1 ?> class="supertitle"><?php echo Text::get('project-side-investors-header'); ?> (<?php echo $supporters; ?>)</h<?php echo $level+1 ?>>

        <div class="investors">
        <ul>
            <?php $c=1; // limitado a 6 cofinanciadores en el lateral
            foreach ($project->investors as $investor): ?>
            <li><?php echo new View('view/user/widget/supporter.html.php', array('user' => $investor, 'worthcracy' => $worthcracy)) ?></li>
            <?php if ($c>5) break; else $c++;
            endforeach ?>
        </ul>

        <a class="more" href="/project/<?php echo $project->id; ?>/supporters"><?php echo Text::get('regular-see_more'); ?></a><br />

        </div>

    <div class="side-worthcracy">
    <?php include 'view/worth/base.html.php' ?>
    </div>
</div>