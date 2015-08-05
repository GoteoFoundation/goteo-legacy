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


use Goteo\Library\Text;

?>
<div class="widget">
    <form id="filter-form" action="/admin/invests/add" method="post">
        <p>
            <label for="invest-amount"><?php echo Text::_("Importe:"); ?></label><br />
            <input type="text" id="invest-amount" name="amount" value="" />
        </p>
        <p>
            <label for="invest-user"><?php echo Text::_("Usuario"); ?>:</label><br />
            <select id="invest-user" name="user">
                <option value=""><?php echo Text::_("Seleccionar usuario que hace el aporte"); ?></option>
            <?php foreach ($this['users'] as $userId=>$userName) : ?>
                <option value="<?php echo $userId; ?>"><?php echo $userName; ?></option>
            <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="invest-project"><?php echo Text::_("Proyecto:"); ?></label><br />
            <select id="invest-project" name="project">
                <option value=""><?php echo Text::_("Seleccionar el proyecto al que se aporta"); ?></option>
            <?php foreach ($this['projects'] as $projectId=>$projectName) : ?>
                <option value="<?php echo $projectId; ?>"><?php echo $projectName; ?></option>
            <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="invest-anonymous"><?php echo Text::_("Aporte anónimo:"); ?></label><br />
            <input id="invest-anonymous" type="checkbox" name="anonymous" value="1">
        </p>

        <input type="submit" name="add" value="<?php echo Text::_("Generar aporte"); ?>" />

    </form>
</div>