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

$original = $this['original'];
$user     = $this['user'];
$project  = $this['project'];


// Lastima que no me sirve ni el getAll ni el getList ni el Published
$projects = array();
$query = Model\Project::query("
    SELECT
        project.id as id,
        project.name as name
    FROM    project
    WHERE status = 3
    ORDER BY project.name ASC
    ");

foreach ($query->fetchAll(\PDO::FETCH_CLASS) as $item) {
    $projects[$item->id] = $item->name;
}


?>
<div class="widget">
    <p>Movemos el aporte de <strong><?php echo $user->name ?></strong> al proyecto <strong><?php echo $project->name; ?></strong> de <strong><?php echo $original->amount; ?> &euro;</strong> mediante <strong><?php echo $original->method; ?></strong> del d&iacute;a <strong><?php echo date('d-m-Y', strtotime($original->invested)); ?></strong>.</p>
    <form id="filter-form" action="/admin/invests/move/<?php echo $original->id ?>" method="post">
        <p>
            <label for="invest-project">Al proyecto:</label><br />
            <select id="invest-project" name="project">
                <option value="">Seleccionar el proyecto al que se mueve el aporte</option>
            <?php foreach ($projects as $projectId=>$projectName) :
                if ($projectId == $original->project) continue; ?>
                <option value="<?php echo $projectId; ?>"><?php echo $projectName; ?></option>
            <?php endforeach; ?>
            </select>
        </p>

        <input type="submit" name="move" value="Reubicar"
            onclick="return confirm('El aporte original va a desaparecer de los cofinanciadores y no se va a tratar automaticamente al final de ronda, ¿Seguimos?');" />

    </form>
</div>