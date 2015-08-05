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

$task = $this['task'];
$nodes = $this['nodes'];
?>
<div class="widget">
    <form action="/admin/tasks/<?php echo ($this['action'] == 'add') ? 'add' : 'edit/'.$task->id ?>" method="post">
        <input type="hidden" name="node" value="<?php echo \GOTEO_NODE; ?>" />
        <p>
            <label for="task-text">Explicación:</label><br />
            <textarea id="task-text" name="text" style="width:500px;height:200px;" ><?php echo $task->text ?></textarea>
        </p>
        <p>
            <label for="task-url">Url:</label><br />
            <input type="text" id="task-url" name="url" value="<?php echo $task->url ?>" style="width:500px" />
        </p>

        <p>
            <label>Estado:</label><br />
            <?php if (empty($task->done)) : ?>
            <span style="color:red;" >PENDIENTE</span>
            <?php else : ?>
            <span style="color:green;" >Realizada por:</span> <strong><?php echo $task->user->name; ?></strong><br />
            <label><input type="checkbox" name="undone" value="1" />Reabrirla</label>
            <?php endif; ?>
        </p>

        <input type="submit" name="save" value="Guardar" /><br />

    </form>
</div>