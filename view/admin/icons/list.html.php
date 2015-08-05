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
$filters = $this['filters'];
?>
<div class="widget board">
    <form id="groupfilter-form" action="/admin/icons" method="get">
        <label for="group-filter"> <?php echo Text::_("Mostrar los tipos para:"); ?></label>
        <select id="group-filter" name="group" onchange="document.getElementById('groupfilter-form').submit();">
            <option value=""><?php echo Text::_("Todo"); ?></option>
        <?php foreach ($this['groups'] as $groupId=>$groupName) : ?>
            <option value="<?php echo $groupId; ?>"<?php if ($filters['group'] == $groupId) echo ' selected="selected"';?>><?php echo $groupName; ?></option>
        <?php endforeach; ?>
        </select>
    </form>
</div>

<div class="widget board">
    <?php if (!empty($this['icons'])) : ?>
    <table>
        <thead>
            <tr>
                <th><!-- Editar --></th>
                <th> <?php echo Text::_("Nombre"); ?></th> <!-- name -->
                <th>Tooltip</th> <!-- descripcion -->
                <th><?php echo Text::_("Agrupación"); ?></th> <!-- group -->
                <th><!-- Traducir--></th>
<!--                        <th> Remove </th>  -->
            </tr>
        </thead>

        <tbody>
            <?php foreach ($this['icons'] as $icon) : ?>
            <tr>
                <td><a href="/admin/icons/edit/<?php echo $icon->id; ?>"><?php echo Text::_("[Editar]"); ?></a></td>
                <td><?php echo $icon->name; ?></td>
                <td><?php echo $icon->description; ?></td>
                <td><?php echo !empty($icon->group) ? $this['groups'][$icon->group] : Text::_('Ambas'); ?></td>
                <?php if ($translator) : ?>
                <td><a href="/translate/icon/edit/<?php echo $icon->id; ?>" ><?php echo Text::_("[Traducir]"); ?></a></td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
    <?php else : ?>
    <p><?php echo Text::_("No se han encontrado registros"); ?></p>
    <?php endif; ?>
</div>