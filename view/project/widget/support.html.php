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

$level = (int) $this['level'] ?: 3;

$project = $this['project'];

?>
<div class="widget project-support collapsable" id="project-support">

    <h<?php echo $level + 1 ?> class="supertitle"><?php echo Text::get('project-support-supertitle'); ?></h<?php echo $level + 1 ?>>
    
    <?php switch ($project->tagmark) {
        case 'onrun': // "en marcha"
            echo '<div class="tagmark green">' . Text::get('regular-onrun_mark') . '</div>';
            break;
        case 'keepiton': // "aun puedes"
            echo '<div class="tagmark green">' . Text::get('regular-keepiton_mark') . '</div>';
            break;
        case 'onrun-keepiton': // "en marcha" y "aun puedes"
            echo '<div class="tagmark green twolines"><span class="small"><strong>' . Text::get('regular-onrun_mark') . '</strong><br />' . Text::get('regular-keepiton_mark') . '</span></div>';
            break;
        case 'gotit': // "financiado"
            echo '<div class="tagmark violet">' . Text::get('regular-gotit_mark') . '</div>';
            break;
        case 'success': // "exitoso"
            echo '<div class="tagmark red">' . Text::get('regular-success_mark') . '</div>';
            break;
        case 'fail': // "caducado"
            echo '<div class="tagmark grey">' . Text::get('regular-fail_mark') . '</div>';
            break;
    } ?>

    <?php echo new View('view/project/meter.html.php', array('project' => $project, 'level' => $level) ) ?>
    
    <div class="buttons">
        <?php if ($project->status == 3) : // boton apoyar solo si esta en campaña ?>
        <a class="button violet supportit" href="/project/<?php echo $project->id; ?>/invest"><?php echo Text::get('regular-invest_it'); ?></a>
        <?php else : ?>
        <a class="button view" href="/project/<?php echo $project->id ?>/updates"><?php echo Text::get('regular-see_blog'); ?></a>
        <?php endif; ?>
        <a class="more" href="/project/<?php echo $project->id; ?>/needs"><?php echo Text::get('regular-see_more'); ?></a>
    </div>
    
</div>