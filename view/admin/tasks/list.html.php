<?php
/*
 *  Copyright (C) 2012 Platoniq y Fundación Goteo (see README for details)
 *  This file is part of Goteo.
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
?>
<a href="/admin/tasks/add" class="button">Nueva Tarea</a>

<div class="widget board">
    <form id="filter-form" action="/admin/tasks" method="get">
        <table>
            <tr>
                <td>
                    <label for="status-filter">Mostrar por estado:</label><br />
                    <select id="status-filter" name="done" onchange="document.getElementById('filter-form').submit();">
                        <option value="">Cualquier estado</option>
                    <?php foreach ($this['status'] as $statusId=>$statusName) : ?>
                        <option value="<?php echo $statusId; ?>"<?php if ($filters['done'] == $statusId) echo ' selected="selected"';?>><?php echo $statusName; ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <label for="user-filter">Realizadas por:</label><br />
                    <select id="user-filter" name="user" onchange="document.getElementById('filter-form').submit();">
                        <option value="">Cualquier admin</option>
                    <?php foreach ($this['admins'] as $adminId=>$adminName) : ?>
                        <option value="<?php echo $adminId; ?>"<?php if ($filters['user'] == $adminId) echo ' selected="selected"';?>><?php echo $adminName; ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        </table>

    </form>
</div>

<div class="widget board">
<?php if (!empty($this['tasks'])) : ?>
    <table>
        <thead>
            <tr>
                <th></th> <!-- edit -->
                <th>Nodo</th>
                <th>Tarea</th>
                <th>Estado</th>
                <th></th> <!-- remove -->
            </tr>
        </thead>

        <tbody>
            <?php foreach ($this['tasks'] as $task) : ?>
            <tr>
                <td><a href="/admin/tasks/edit/<?php echo $task->id; ?>" title="Editar">[Editar]</a></td>
                <td><strong><?php echo $this['nodes'][$task->node]; ?></strong></td>
                <td><?php echo substr($task->text, 0, 150); ?></td>
                <td><?php echo (empty($task->done)) ? 'Pendiente' : 'Realizada ('.$task->user->name.')';?></td>
                <td><a href="/admin/tasks/remove/<?php echo $task->id; ?>" title="Eliminar" onclick="return confirm('La tarea se eliminará irreversiblemente, ok?')">[Eliminar]</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
    <?php else : ?>
    <p>No se han encontrado registros</p>
    <?php endif; ?>
</div>