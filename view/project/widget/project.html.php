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
    Goteo\Model\Project\Category,
    Goteo\Model\Invest,
    Goteo\Model\Image;

$project = $this['project'];
$level = $this['level'] ?: 3;

if ($this['global'] === true) {
    $blank = ' target="_blank"';
} else {
    $blank = '';
}

$categories = Category::getNames($project->id, 2);

//si llega $this['investor'] sacamos el total aportado para poner en "mi aporte"
if (isset($this['investor']) && is_object($this['investor'])) {
    $investor = $this['investor'];
    $invest = Invest::supported($investor->id, $project->id);
}
?>

<div class="widget project activable<?php if (isset($this['balloon'])) echo ' balloon' ?>">
	<a href="<?php echo SITE_URL ?>/project/<?php echo $project->id ?>" class="expand"<?php echo $blank; ?>></a>
    <?php if (isset($this['balloon'])): ?>
    <div class="balloon"><?php echo $this['balloon'] ?></div>
    <?php endif ?>

    <div class="image">
        <?php switch ($project->tagmark) {
            case 'onrun': // "en marcha"
                echo '<div class="tagmark green">' . Text::get('regular-onrun_mark') . '</div>';
                break;
            case 'keepiton': // "aun puedes"
                echo '<div class="tagmark green">' . Text::get('regular-keepiton_mark') . '</div>';
                break;
            case 'onrun-keepiton': // "en marcha" y "aun puedes"
//                echo '<div class="tagmark green">' . Text::get('regular-onrun_mark') . '</div>';
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

        <?php if (isset($this['investor'])) : ?>
            <div class="investor"><img src="<?php echo $investor->avatar->getLink(43, 43, 1) ?>" alt="<?php echo $investor->name ?>" /><div class="invest">Mi aporte<br /><span class="amount"><?php echo $invest->total ?></span></div></div>
        <?php endif; ?>

        <?php if (!empty($project->gallery) && (current($project->gallery) instanceof Image)): ?>
        <a href="<?php echo SITE_URL ?>/project/<?php echo $project->id ?>"<?php echo $blank; ?>><img alt="<?php echo $project->name ?>" src="<?php echo current($project->gallery)->getLink(255, 130, true) ?>" /></a>
        <?php endif ?>
        <?php if (!empty($categories)): ?>
        <div class="categories">
        <?php $sep = ''; foreach ($categories as $key=>$value) :
            echo $sep.htmlspecialchars($value);
        $sep = ', '; endforeach; ?>
        </div>
        <?php endif ?>
    </div>

    <h<?php echo $level ?> class="title"><a href="<?php echo SITE_URL ?>/project/<?php echo $project->id ?>"<?php echo $blank; ?>><?php echo htmlspecialchars(Text::recorta($project->name,50)) ?></a></h<?php echo $level ?>>

    <h<?php echo $level + 1 ?> class="author"><?php echo Text::get('regular-by')?> <a href="<?php echo SITE_URL ?>/user/profile/<?php echo htmlspecialchars($project->user->id) ?>"<?php echo $blank; ?>><?php echo htmlspecialchars(Text::recorta($project->user->name,40)) ?></a></h<?php echo $level + 1?>>

    <div class="description"><?php echo Text::recorta($project->description, 100); ?></div>

    <?php echo new View('view/project/meter_hor.html.php', array('project' => $project)) ?>

    <div class="rewards">
        <h<?php echo $level + 1 ?>><?php echo Text::get('project-rewards-header'); ?></h<?php echo $level + 1?>>

        <ul>
           <?php $q = 1; foreach ($project->social_rewards as $social): ?>
            <li class="<?php echo $social->icon ?> activable">
                <a href="<?php echo SITE_URL ?>/project/<?php echo $project->id ?>/rewards" title="<?php echo htmlspecialchars("{$social->icon_name}: {$social->reward}") ?>" class="tipsy"<?php echo $blank; ?>><?php echo htmlspecialchars($social->reward) ?></a>
            </li>
           <?php if ($q >= 5) break; $q++;
               endforeach ?>
           <?php if ($q < 5) foreach ($project->individual_rewards as $individual): ?>
            <li class="<?php echo $individual->icon ?> activable">
                <a href="<?php echo SITE_URL ?>/project/<?php echo $project->id ?>/rewards" title="<?php echo htmlspecialchars("{$individual->icon_name}: {$individual->reward} {$individual->amount}") ?> &euro;" class="tipsy"<?php echo $blank; ?>><?php echo htmlspecialchars($individual->reward) ?></a>
            </li>
           <?php if ($q >= 5) break; $q++;
           endforeach ?>
        </ul>


    </div>

    <?php
    /*
     * quitamos los botones
     *
    if ($this['dashboard'] === true) : // si estamos en el dashboard no hay (apoyar y el ver se abre en una ventana nueva) ?>
    <div class="buttons">
        <?php if ($this['own'] === true) : // si es propio puede ir a editarlo ?>
        <a class="button red suportit" href="<?php echo SITE_URL ?>/project/edit/<?php echo $project->id ?>"><?php echo Text::get('regular-edit'); ?></a>
        <?php endif; ?>
        <a class="button view" href="<?php echo SITE_URL ?>/project/<?php echo $project->id ?>" target="_blank"><?php echo Text::get('regular-view_project'); ?></a>
    </div>
    <?php else : // normal ?>
    <div class="buttons">
        <?php if ($project->status == 3) : // si esta en campa�a se puede aportar ?>
        <a class="button violet supportit" href="<?php echo SITE_URL ?>/project/<?php echo $project->id ?>/invest"<?php echo $blank; ?>><?php echo Text::get('regular-invest_it'); ?></a>
        <?php else : ?>
        <a class="button view" href="<?php echo SITE_URL ?>/project/<?php echo $project->id ?>/updates"<?php echo $blank; ?>><?php echo Text::get('regular-see_blog'); ?></a>
        <?php endif; ?>
        <a class="button view" href="<?php echo SITE_URL ?>/project/<?php echo $project->id ?>"<?php echo $blank; ?>><?php echo Text::get('regular-view_project'); ?></a>
    </div>
    <?php endif;
     *
     */
    ?>
</div>
