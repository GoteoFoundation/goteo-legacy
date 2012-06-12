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
$filter = "?status={$filters['status']}&category={$filters['category']}&owner={$filters['owner']}&name={$filters['name']}&order={$filters['order']}";

?>
<div class="widget board">
    <form id="filter-form" action="/admin/projects" method="get">
        <table>
            <tr>
                <td>
                    <label for="owner-filter">Del autor:</label><br />
                    <select id="owner-filter" name="owner" onchange="document.getElementById('filter-form').submit();">
                        <option value="">Cualquier autor</option>
                    <?php foreach ($this['owners'] as $ownerId=>$ownerName) : ?>
                        <option value="<?php echo $ownerId; ?>"<?php if ($filters['owner'] == $ownerId) echo ' selected="selected"';?>><?php echo $ownerName; ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <label for="category-filter">De la categoría:</label><br />
                    <select id="category-filter" name="category" onchange="document.getElementById('filter-form').submit();">
                        <option value="">Cualquier categoría</option>
                    <?php foreach ($this['categories'] as $categoryId=>$categoryName) : ?>
                        <option value="<?php echo $categoryId; ?>"<?php if ($filters['category'] == $categoryId) echo ' selected="selected"';?>><?php echo $categoryName; ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="name-filter">Nombre:</label><br />
                    <input id="name-filter" name="name" value="<?php echo $filters['name']; ?>" style="width:250px"/>
                </td>
                <td>
                    <label for="status-filter">Mostrar por estado:</label><br />
                    <select id="status-filter" name="status" onchange="document.getElementById('filter-form').submit();">
                        <option value="-1"<?php if ($filters['status'] == -1) echo ' selected="selected"';?>>Todos los estados</option>
                    <?php foreach ($this['status'] as $statusId=>$statusName) : ?>
                        <option value="<?php echo $statusId; ?>"<?php if ($filters['status'] == $statusId) echo ' selected="selected"';?>><?php echo $statusName; ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <label for="order-filter">Ver por:</label><br />
                    <select id="order-filter" name="order" onchange="document.getElementById('filter-form').submit();">
                    <?php foreach ($this['orders'] as $orderId=>$orderName) : ?>
                        <option value="<?php echo $orderId; ?>"<?php if ($filters['order'] == $orderId) echo ' selected="selected"';?>><?php echo $orderName; ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" name="filter" value="Buscar">
                </td>
            </tr>
        </table>
    </form>
</div>

<div class="widget board">
    <?php if (!empty($this['projects'])) : ?>
    <table>
        <thead>
            <tr>
                <th>Proyecto</th> <!-- edit -->
                <th>Creador</th> <!-- mailto -->
                <th>Recibido</th> <!-- enviado a revision -->
                <th>Estado</th>
                <th>%</th> <!-- segun estado -->
                <th>Días</th> <!-- segun estado -->
                <th>Conseguido</th> <!-- segun estado -->
                <th>Mínimo</th> <!-- segun estado -->
            </tr>
        </thead>

        <tbody>
            <?php foreach ($this['projects'] as $project) : ?>
            <tr>
                <td><a href="/project/<?php echo $project->id; ?>" target="_blank" title="Preview"><?php echo $project->name; ?></a></td>
                <td><?php echo $project->user->name; ?></td>
                <td><?php echo date('d-m-Y', strtotime($project->updated)); ?></td>
                <td><?php echo $this['status'][$project->status]; ?></td>
                <td><?php if ($project->status < 3)  echo $project->progress; ?></td>
                <td><?php if ($project->status == 3) echo "$project->days (round {$project->round})"; ?></td>
                <td><?php echo $project->invested; ?></td>
                <td><?php echo $project->mincost; ?></td>
            </tr>
            <tr>
                <td colspan="8"> >>> Acciones:
                    <a href="/project/edit/<?php echo $project->id; ?>" target="_blank">[Editar]</a>
                    <?php if ($project->status < 2) : ?><a href="<?php echo "/admin/projects/review/{$project->id}{$filter}"; ?>">[A revisión]</a><?php endif; ?>
                    <?php if ($project->status < 3 && $project->status > 0) : ?><a href="<?php echo "/admin/projects/publish/{$project->id}{$filter}"; ?>" onclick="return confirm('El proyecto va a comenzar los 40 dias de la primera ronda de campaña, ¿comenzamos?');">[Publicar]</a><?php endif; ?>
                    <?php if ($project->status != 1) : ?><a href="<?php echo "/admin/projects/enable/{$project->id}{$filter}"; ?>" onclick="return confirm('Mucho Ojo! si el proyecto esta en campaña, ¿Reabrimos la edicion?');">[Reabrir]</a><?php endif; ?>
                    <?php if ($project->status == 4) : ?><a href="<?php echo "/admin/projects/fulfill/{$project->id}{$filter}"; ?>">[Retorno Cumplido]</a><?php endif; ?>
                    <?php if ($project->status < 3 && $project->status > 0) : ?><a href="<?php echo "/admin/projects/cancel/{$project->id}{$filter}"; ?>" onclick="return confirm('El proyecto va a desaparecer del admin, solo se podra recuperar desde la base de datos, Ok?');">[Descartar]</a><?php endif; ?>
                    <a href="<?php echo "/admin/projects/dates/{$project->id}{$filter}"; ?>">[Cambiar fechas]</a>
                    <a href="<?php echo "/admin/projects/accounts/{$project->id}{$filter}"; ?>">[Cuentas]</a>
                    <?php if ($project->translate) : ?><a href="<?php echo "/admin/translates/edit/{$project->id}"; ?>">[Ir a traducción]</a>
                    <?php else : ?><a href="<?php echo "/admin/translates/add/?project={$project->id}"; ?>">[Habilitar traducción]</a><?php endif; ?>
                    <a href="/admin/invests/report/<?php echo $project->id; ?>#detail" target="_blank">[Informe Financiacion]</a>
                </td>
            </tr>
            <tr>
                <td colspan="8"><hr /></td>
            </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
    <?php else : ?>
    <p>No se han encontrado registros</p>
    <?php endif; ?>
</div>