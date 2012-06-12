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

//tratamos los saltos de linea y los links en las descripciones del proyecto
$project->description = nl2br(Text::urlink($project->description));
$project->about       = nl2br(Text::urlink($project->about));
$project->motivation  = nl2br(Text::urlink($project->motivation));
$project->goal        = nl2br(Text::urlink($project->goal));
$project->related     = nl2br(Text::urlink($project->related));

$level = (int) $this['level'] ?: 3;
?>
    <?php  if (count($project->gallery) > 1) : ?>
		<script type="text/javascript" >
			$(function(){
				$('#prjct-gallery').slides({
					container: 'prjct-gallery-container',
					paginationClass: 'slderpag',
					generatePagination: false,
					play: 0
				});
			});
		</script>
    <?php endif; ?>
<div class="widget project-summary">
    
    <h<?php echo $level ?>><?php echo htmlspecialchars($project->name) ?></h<?php echo $level ?>>
        
    <?php if (!empty($project->description)): ?>
    <div class="description">
<!--        <h<?php echo $level + 1?>><?php # echo Text::get('overview-field-description'); ?></h<?php echo $level + 1?>>         -->
        <?php echo $project->description; ?>
    </div>    
    <?php endif ?>

    <?php if (count($project->gallery) > 1): ?>
	<div id="prjct-gallery">
		<div class="prjct-gallery-container">
			<?php $i = 1; foreach ($project->gallery as $image) : ?>
			<div class="gallery-image" id="gallery-image-<?php echo $i ?>">
				<img src="<?php echo $image->getLink(580, 580); ?>" alt="<?php echo $project->name; ?>" />
			</div>
			<?php $i++; endforeach; ?>
		</div>
		<!-- carrusel de imagenes si hay mas de una -->
        <a class="prev">prev</a>
            <ul class="slderpag">
                <?php $i = 1; foreach ($project->gallery as $image) : ?>
                <li><a href="#" id="navi-gallery-image-<?php echo $i ?>" rel="gallery-image-<?php echo $i ?>" class="navi-gallery-image">
                <?php echo htmlspecialchars($image->name) ?></a>
                </li>
                <?php $i++; endforeach ?>
            </ul>
        <a class="next">next</a>
		<!-- carrusel de imagenes -->
	</div>
    <?php elseif (!empty($project->gallery)) : ?>
        <div class="gallery-image" id="gallery-image-<?php echo $i ?>"style="display:block;">
            <img src="<?php echo $project->gallery[0]->getLink(580, 580); ?>" alt="<?php echo $project->name; ?>" />
        </div>
    <?php endif; ?>



    <?php if (!empty($project->about)): ?>
    <div class="about">
        <h<?php echo $level + 1?>><?php echo Text::get('overview-field-about'); ?></h<?php echo $level + 1?>>
        <?php echo $project->about; ?>
    </div>    
    <?php endif ?>
    
    <?php if (!empty($project->motivation)): ?>
    <div class="motivation">
        <h<?php echo $level + 1?>><?php echo Text::get('overview-field-motivation'); ?></h<?php echo $level + 1?>>
        <?php echo $project->motivation; ?>
    </div>
    <?php endif ?>
    <?php if (!empty($project->video->url)):  // video bajo motivaci�n ?>
    <div class="project-motivation-video">
        <a name="motivideo"></a>
        <?php echo $project->video->getEmbedCode($project->video_usubs); ?>
    </div>
    <br />
    <?php endif ?>

    <?php if (!empty($project->goal)): ?>
    <div class="goal">
        <h<?php echo $level + 1?>><?php echo Text::get('overview-field-goal'); ?></h<?php echo $level + 1?>>
        <?php echo $project->goal; ?>
    </div>    
    <?php endif ?>
    
    <?php if (!empty($project->related)): ?>
    <div class="related">
        <h<?php echo $level + 1?>><?php echo Text::get('overview-field-related'); ?></h<?php echo $level + 1?>>
        <?php echo $project->related ?>
    </div>
    <?php endif ?>

    
</div>