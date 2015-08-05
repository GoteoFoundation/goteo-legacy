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
    <form id="filter-form" action="/admin/licenses" method="get">
        <label for="group-filter"><?php echo Text::_("Mostrar por grupo:"); ?></label>
        <select id="group-filter" name="group" onchange="document.getElementById('filter-form').submit();">
            <option value=""><?php echo Text::_("Todos los grupos"); ?></option>
        <?php foreach ($this['groups'] as $groupId=>$groupName) : ?>
            <option value="<?php echo $groupId; ?>"<?php if ($filters['group'] == $groupId) echo ' selected="selected"';?>><?php echo $groupName; ?></option>
        <?php endforeach; ?>
        </select>

        <label for="icon-filter"><?php echo Text::_("Mostrar por tipo de retorno:"); ?></label>
        <select id="icon-filter" name="icon" onchange="document.getElementById('filter-form').submit();">
            <option value=""><?php echo Text::_("Todos los tipos"); ?></option>
        <?php foreach ($this['icons'] as $icon) : ?>
            <option value="<?php echo $icon->id; ?>"<?php if ($filters['icon'] == $icon->id) echo ' selected="selected"';?>><?php echo $icon->name; ?></option>
        <?php endforeach; ?>
        </select>
    </form>
</div>

<div class="widget board">
    <?php if (!empty($this['licenses'])) : ?>
    <table>
        <thead>
            <tr>
                <th><!-- Edit --></th>
                <th><?php echo Text::_("Nombre"); ?></th> <!-- name -->
                <th><!-- Icon --></th>
                <th><?php echo Text::_("Tooltip"); ?></th> <!-- description -->
                <th><?php echo Text::_("Agrupación"); ?></th> <!-- group -->
                <th><?php echo Text::_("Posición"); ?></th> <!-- order -->
                <th><!-- Move up --></th>
                <th><!-- Move down --></th>
                <th><!-- Traducir--></th>
<!--                                <td> Remove </td> -->
            </tr>
        </thead>

        <tbody>
            <?php foreach ($this['licenses'] as $license) : ?>
            <tr>
                <td><a href="/admin/licenses/edit/<?php echo $license->id; ?>">[<?php echo Text::_("Editar"); ?>]</a></td>
                <td><?php echo $license->name; ?></td>
                <td><img src="/view/css/license/<?php echo $license->id; ?>.png" alt="<?php echo $license->id; ?>" title="<?php echo $license->name; ?>" /></td>
                <td><?php echo $license->description; ?></td>
                <td><?php echo !empty($license->group) ? $this['groups'][$license->group] : ''; ?></td>
                <td><?php echo $license->order; ?></td>
                <td><a href="/admin/licenses/up/<?php echo $license->id; ?>">[&uarr;]</a></td>
                <td><a href="/admin/licenses/down/<?php echo $license->id; ?>">[&darr;]</a></td>
                <?php if ($translator) : ?>
                <td><a href="/translate/license/edit/<?php echo $license->id; ?>" >[<?php echo Text::_("Traducir"); ?>]</a></td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
    <?php else : ?>
    <p>No se han encontrado registros</p>
    <?php endif; ?>
</div>