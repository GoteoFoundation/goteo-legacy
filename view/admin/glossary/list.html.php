<?php
/*
 *  Copyright (C) 2012 Platoniq y Fundación Goteo (see README for details)
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
    Goteo\Core\ACL;

$translator = ACL::check('/translate') ? true : false;
?>
<a href="/admin/glossary/add" class="button"><?php echo Text::_("Nuevo término"); ?></a>

<div class="widget board">
    <?php if (!empty($this['posts'])) : ?>
    <table>
        <thead>
            <tr>
                <td><!-- <?php echo Text::_("Edit"); ?> --></td>
                <th><?php echo Text::_("Título"); ?></th> <!-- title -->
                <th><!-- <?php echo Text::_("Traducir"); ?>--></th>
                <td><!-- Remove --></td>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($this['posts'] as $post) : ?>
            <tr>
                <td><a href="/admin/glossary/edit/<?php echo $post->id; ?>">[<?php echo Text::_("Editar"); ?>]</a></td>
                <td><?php echo $post->title; ?></td>
                <?php if ($translator) : ?>
                <td><a href="/translate/glossary/edit/<?php echo $post->id; ?>" >[<?php echo Text::_("Traducir"); ?>]</a></td>
                <?php endif; ?>
                <td><a href="/admin/glossary/remove/<?php echo $post->id; ?>" onclick="return confirm('<?php echo Text::_("Seguro que deseas eliminar este registro?"); ?>');">[<?php echo Text::_("Quitar"); ?>]</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
    <?php else : ?>
    <p><?php echo Text::_("No se han encontrado registros"); ?></p>
    <?php endif; ?>
</div>