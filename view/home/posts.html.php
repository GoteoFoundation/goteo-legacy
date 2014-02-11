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

$posts = $this['posts'];
?>
<script type="text/javascript">
    $(function(){
        $('#learn').slides({
            container: 'slder_container',
            paginationClass: 'slderpag',
            generatePagination: false,
            play: 0
        });
    });
</script>
<div id="learn" class="widget learn">
    <h2 class="title"><?php echo Text::get('home-posts-header'); ?></h2>
    <div class="slder_container"<?php if (count($posts)==1) echo ' style="display:block;"'; ?>>

        <?php $i = 1; foreach ($posts as $post) : ?>
        <div class="slder_slide">
            <div class="post" id="home-post-<?php echo $i; ?>" style="display:block;">
                <?php  if (!empty($post->media->url)) : ?>
                    <div class="embed">
                        <?php echo $post->media->getEmbedCode(); ?>
                    </div>
                <?php elseif (!empty($post->image)) : ?>
                    <div class="image">
                        <img src="<?php echo $post->image->getLink(500, 285); ?>" alt="Imagen"/>
                    </div>
                <?php endif; ?>
                <h3><?php if ($post->owner_type == 'project') { echo '<a href="/project/'.$post->owner_id.'/updates/'.$post->id.'">'.Text::get('project-menu-home').' '.$post->owner_name.'</a>: ' . $post->title; } 
                else { echo '<a href="/blog/'.$post->id.'">'.$post->title.'</a>'; } ?></h3>
                <?php if (!empty($post->author)) : ?>
                    <div class="author"><a href="/user/profile/<?php echo $post->author ?>"><?php echo Text::get('regular-by') ?> <?php echo $post->user->name ?></a></div>
                <?php endif; ?>
                <div class="description"><?php if ($post->id == 728) echo Text::recorta($post->text, 400); else echo Text::recorta($post->text, 600); ?></div>

                <div class="read_more"><a href="<?php echo ($post->owner_type == 'project') ? '/project/'.$post->owner_id.'/updates/'.$post->id : '/blog/'.$post->id; ?>"><?php echo Text::get('regular-read_more') ?></a></div>
            </div>
        </div>
        <?php $i++; endforeach; ?>
    </div>
    <a class="prev">prev</a>
    <ul class="slderpag">
        <?php $i = 1; foreach ($posts as $post) : ?>
        <li><a href="#" id="navi-home-post-<?php echo $i ?>" rel="home-post-<?php echo $i ?>" class="tipsy navi-home-post" title="<?php echo htmlspecialchars($post->title) ?>">
            <?php echo htmlspecialchars($post->title) ?></a>
        </li>
        <?php $i++; endforeach ?>
    </ul>
    <a class="next">next</a>

</div>
