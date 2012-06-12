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

$banner = $this['banner'];

// proyectos disponibles
// si tenemos ya proyecto seleccionado lo incluimos
$projects = Model\Banner::available($banner->project);
$status = Model\Project::status();

?>
<form method="post" action="/admin/banners" enctype="multipart/form-data">
    <input type="hidden" name="action" value="<?php echo $this['action'] ?>" />
    <input type="hidden" name="order" value="<?php echo $banner->order ?>" />
    <input type="hidden" name="id" value="<?php echo $banner->id; ?>" />

<p>
    <label for="banner-project">Proyecto:</label><br />
    <select id="banner-project" name="project">
        <option value="" >Seleccionar el proyecto a mostrar en el banner</option>
    <?php foreach ($projects as $project) : ?>
        <option value="<?php echo $project->id; ?>"<?php if ($banner->project == $project->id) echo' selected="selected"';?>><?php echo $project->name . ' ('. $status[$project->status] . ')'; ?></option>
    <?php endforeach; ?>
    </select>
</p>

<p>
    <label for="banner-image">Imagen de fondo: 700 x 156 (estricto)</label><br />
    <input type="file" id="banner-image" name="image" />
    <?php if (!empty($banner->image)) : ?>
        <br />
        <input type="hidden" name="prev_image" value="<?php echo $banner->image->id ?>" />
        <img src="<?php echo $banner->image->getLink(700, 156, true) ?>" title="Fondo album" alt="falla imagen"/>
    <?php endif; ?>
</p>

    <input type="submit" name="save" value="Guardar" />
</form>
