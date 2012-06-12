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
    Goteo\Model\Blog\Post;

$post = Post::get($this['post'], LANG);
$level = (int) $this['level'] ?: 3;
//@TODO: Si el usuario es el dueño del blog o tiene permiso para moderar, boton de borrar comentario
?>
<h<?php echo $level ?> class="title"><?php echo Text::get('blog-coments-header'); ?></h<?php echo $level ?>>
<div class="widget post-comments">

<?php if (!empty($post->comments)): ?>

    <div id="post-comments">

    <?php foreach ($post->comments as $comment) : ?>

        <div class="message<?php if ($comment->user->id == $this['owner']) echo ' owner'; ?>">
           <div class="arrow-up"></div>
           <span class="avatar">
               <a href="/user/profile/<?php echo htmlspecialchars($comment->user->id)?>" target="_blank">
                   <img src="<?php echo $comment->user->avatar->getLink(50, 50, true); ?>" alt="<?php echo $comment->user->name; ?>" />
               </a>
           </span>
           <h<?php echo $level ?> class="user">
    		   <a href="/user/profile/<?php echo htmlspecialchars($comment->user->id)?>" target="_blank"><?php echo htmlspecialchars($comment->user->name) ?></a>
           </h<?php echo $level ?>>
           <div class="date"><span>Hace <?php echo $comment->timeago ?></span></div>
           <a name="comment<?php echo $comment->id; ?>" ></a>
           <blockquote><?php echo $comment->text; ?></blockquote>
       </div>

    <?php endforeach; ?>

    </div>

<?php else : ?>

    <p><?php echo Text::get('blog-comments_no_comments'); ?></p>

<?php endif; ?>

</div>
