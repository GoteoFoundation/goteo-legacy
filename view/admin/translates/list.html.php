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
$filter = "?owner={$filters['owner']}&translator={$filters['translator']}";

?>
<a href="/admin/translates/add" class="button red">Nuevo proyecto para traducir</a>

<div class="widget board">
<form id="filter-form" action="/admin/translates" method="get">
    <label for="owner-filter">Proyectos del usuario:</label>
    <select id="owner-filter" name="owner" onchange="document.getElementById('filter-form').submit();">
        <option value="">Todos los productores</option>
    <?php foreach ($this['owners'] as $ownerId=>$ownerName) : ?>
        <option value="<?php echo $ownerId; ?>"<?php if ($filters['owner'] == $ownerId) echo ' selected="selected"';?>><?php echo $ownerName; ?></option>
    <?php endforeach; ?>
    </select>

    <label for="translator-filter">Asignados a traductor:</label>
    <select id="translator-filter" name="translator" onchange="document.getElementById('filter-form').submit();">
        <option value="">Todos los traductores</option>
    <?php foreach ($this['translators'] as $translator) : ?>
        <option value="<?php echo $translator->id; ?>"<?php if ($filters['translator'] == $translator->id) echo ' selected="selected"';?>><?php echo $translator->name; ?></option>
    <?php endforeach; ?>
    </select>
</form>
</div>

<!-- proyectos con la traducción activa -->
<?php if (!empty($this['projects'])) : ?>
        <div class="widget board">
            <table>
                <thead>
                    <tr>
                        <th width="5%"><!-- Editar y asignar --></th>
                        <th width="55%">Proyecto</th> <!-- edit -->
                        <th width="30%">Creador</th>
                        <th width="10%">Idioma</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($this['projects'] as $project) : ?>
                    <tr>
                        <td><a href="/admin/translates/edit/<?php echo $project->id; ?>/<?php echo $filter; ?>">[Editar]</a></td>
                        <td><a href="/project/<?php echo $project->id; ?>" target="_blank" title="Preview"><?php echo $project->name; ?></a></td>
                        <td><?php echo $project->user->name; ?></td>
                        <td><?php echo $project->lang; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
            
        </div>
<?php else : ?>
<p>No se han encontrado registros</p>
<?php endif; ?>