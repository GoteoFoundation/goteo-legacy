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

$blog = $this['blog'];

$list = array();

switch ($this['type']) {
    case 'posts':
        $title = Text::get('blog-side-last_posts');
        $items = Post::getAll($blog->id, 7);
        // enlace a la entrada
        foreach ($items as $item) {
            $list[] = '<a href="/blog/'.$item->id.'"> '.Text::recorta($item->title, 100).'</a>';
        }
        break;
    case 'tags':
        $title = Text::get('blog-side-tags');
        $items = Post\Tag::getList($blog->id);
        // enlace a la lista de entradas con filtro tag
        foreach ($items as $item) {
            if ($item->used > 0) {
                $list[] = '<a href="/blog/?tag='.$item->id.'">'.$item->name.'</a>';
            }
        }
        break;
    case 'comments':
        $title = Text::get('blog-side-last_comments');
        $items = Post\Comment::getList($blog->id);
        // enlace a la entrada en la que ha comentado
        foreach ($items as $item) {
            $text = Text::recorta($item->text, 200);
            $list[] = "
				<div>
					<!--span class='avatar'><img src='/image/$item->user->avatar->id/50/50/1' alt='' /></span-->
					<span class='date'>{$item->date}</span>
					<div class='high-comment'>
						<strong><a href=\"/blog/{$item->post}\">{$item->user->name}</a></strong>
						<p>{$text}</p>
					</div>
				</div>";
            }
        break;
}

if (!empty($list)) : ?>
<div class="widget blog-sidebar-module">
    <h3 class="supertitle"><?php echo $title; ?></h3>
    <ul id="blog-side-<?php echo $this['type']; ?>">
        <?php foreach ($list as $item) : ?>
        <li><?php echo $item; ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>
