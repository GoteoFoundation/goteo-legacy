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
    Goteo\Model;

// sacar los posts publicados y el actual
$posts = Model\Blog\Post::getAll(1);

?>
<div class="widget board">
    <form method="post" action="/admin/footer">

        <input type="hidden" name="action" value="<?php echo $this['action']; ?>" />
        <input type="hidden" name="order" value="<?php echo $this['post']->order; ?>" />
        <input type="hidden" name="footer" value="1" />

        <p>
            <label for="home-post">Entrada:</label><br />
            <select id="home-post" name="post">
                <option value="" >Seleccionar la entrada a publicar en el footer</option>
            <?php foreach ($posts as $post) : ?>
                <option value="<?php echo $post->id; ?>"<?php if ($this['post']->post == $post->id) echo' selected="selected"';?>><?php echo $post->title . ' ['. $post->date . ']'; ?></option>
            <?php endforeach; ?>
            </select>
        </p>

        <p>Solo se está asignando al footer una entrada ya publicada. Para gestionar las entradas ir a la <a href="/admin/blog" target="_blank">gestión de blog</a></p>

        <input type="submit" name="save" value="Guardar" />
    </form>
</div>