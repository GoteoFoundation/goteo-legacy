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

$filters = $_SESSION['mailing']['filters'];

?>
<div class="widget board">
    <form id="filter-form" action="/admin/mailing/edit" method="post">

        <table>
            <tr>
                <td>
                    <label for="type-filter">A los</label><br />
                    <select id="type-filter" name="type">
                    <?php foreach ($this['types'] as $typeId=>$typeName) : ?>
                        <option value="<?php echo $typeId; ?>"<?php if ($filters['type'] == $typeId) echo ' selected="selected"';?>><?php echo $typeName; ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <label for="project-filter">De proyectos que el nombre contenga</label><br />
                    <input id="project-filter" name="project" value="<?php echo $filters['project']?>" style="width:300px;" />
                </td>
                <td>
                    <label for="status-filter">En estado</label><br />
                    <select id="status-filter" name="status">
                        <option value="-1"<?php if ($filters['status'] == -1) echo ' selected="selected"';?>>Cualquier estado</option>
                    <?php foreach ($this['status'] as $statusId=>$statusName) : ?>
                        <option value="<?php echo $statusId; ?>"<?php if ($filters['status'] == $statusId) echo ' selected="selected"';?>><?php echo $statusName; ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <label for="method-filter">Aportado mediante</label><br />
                    <select id="method-filter" name="method">
                        <option value="">Cualquier metodo</option>
                    <?php foreach ($this['methods'] as $methodId=>$methodName) : ?>
                        <option value="<?php echo $methodId; ?>"<?php if ($filters['methods'] == $methodId) echo ' selected="selected"';?>><?php echo $methodName; ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="interest-filter">Interesados en fin</label><br />
                    <select id="interest-filter" name="interest">
                        <option value="">Cualquiera</option>
                    <?php foreach ($this['interests'] as $interestId=>$interestName) : ?>
                        <option value="<?php echo $interestId; ?>"<?php if ($filters['interest'] == $interestId) echo ' selected="selected"';?>><?php echo $interestName; ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <label for="name-filter">Que el nombre o email contenga</label><br />
                    <input id="name-filter" name="name" value="<?php echo $filters['name']?>" style="width:300px;" />
                </td>
                <td>
                    <label for="role-filter">Que sean</label><br />
                    <select id="role-filter" name="role">
                        <option value="">Cualquiera</option>
                    <?php foreach ($this['roles'] as $roleId=>$roleName) : ?>
                        <option value="<?php echo $roleId; ?>"<?php if ($filters['role'] == $roleId) echo ' selected="selected"';?>><?php echo $roleName; ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <label for="work-filter">Talleristas</label><br />
                    <input type="checkbox" id="work-filter" name="workshopper" value="1" <?php if (!empty($filters['workshopper'])) echo 'checked="checked"';?> />
                </td>
            </tr>
            <tr>
                <td colspan="3"><input type="submit" name="select" value="Buscar destinatarios"></td>
            </tr>
        </table>




        

    </form>
</div>