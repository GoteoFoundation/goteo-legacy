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

$filters = $this['filters'];

//arrastramos los filtros
$filter = "?status={$filters['status']}&icon={$filters['icon']}";

$status = Goteo\Model\Project::status();

?>
<div class="widget board">
    <form id="filter-form" action="/admin/rewards" method="get">
        <label for="status-filter">Mostrar por estado:</label>
        <select id="status-filter" name="status" onchange="document.getElementById('filter-form').submit();">
            <option value="">Todos los estados</option>
        <?php foreach ($this['status'] as $statusId=>$statusName) : ?>
            <option value="<?php echo $statusId; ?>"<?php if ($filters['status'] == $statusId) echo ' selected="selected"';?>><?php echo $statusName; ?></option>
        <?php endforeach; ?>
        </select>

        <label for="icon-filter">Mostrar retornos del tipo:</label>
        <select id="icon-filter" name="icon" onchange="document.getElementById('filter-form').submit();">
            <option value="">Todos los tipos</option>
        <?php foreach ($this['icons'] as $iconId=>$iconName) : ?>
            <option value="<?php echo $iconId; ?>"<?php if ($filters['icon'] == $iconId) echo ' selected="selected"';?>><?php echo $iconName; ?></option>
        <?php endforeach; ?>
        </select>
    </form>
</div>

<div class="widget board">
    <?php if (!empty($this['projects'])) : ?>
    <?php foreach ($this['projects'] as $project) : ?>

        <?php if (empty($project->social_rewards)) continue; ?>

        <h3><?php echo $project->name; ?></h3>
        <p><span><?php echo $status[$project->status]; ?></span></p>

        <table>
            <thead>
                <tr>
                    <th>Retorno</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($project->social_rewards as $reward) : ?>
                <tr>
                    <td><?php echo $reward->reward; ?></td>
                    <td><?php echo $this['icons'][$reward->icon]; ?></td>
                    <td><?php echo $reward->fulsocial ? 'Cumplido' : 'Pendiente'; ?></td>
                    <?php if (!$reward->fulsocial) : ?>
                    <td><a href="<?php echo "/admin/rewards/fulfill/{$reward->id}{$filter}"; ?>">[Dar por cumplido]</a></td>
                    <?php else : ?>
                    <td><a href="<?php echo "/admin/rewards/unfill/{$reward->id}{$filter}"; ?>">[Dar por pendiente]</a></td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>

        </table>

        <hr />

        <?php endforeach; ?>
    <?php else : ?>
    <p>No se han encontrado registros</p>
    <?php endif; ?>
</div>