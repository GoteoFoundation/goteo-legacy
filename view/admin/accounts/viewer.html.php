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


use Goteo\Core\View;

$date = !empty($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$type = in_array($_GET['type'], array('invest', 'execute', 'daily', 'dopay', 'verify')) ? $_GET['type'] : 'invest';
if (!empty($_GET['date']) && !empty($_GET['type'])) {
    $showlog = true;
    if ($type == 'invest') {
        $file = GOTEO_PATH.'logs/'.str_replace('-', '', $date).'_invest.log';
    } else {
        $file = GOTEO_PATH.'logs/cron/'.str_replace('-', '', $date).'_'.$type.'.log';
    }

    if (file_exists($file)) {
        $content = file_get_contents($file);
    }

} else {
    $showlog = false;
}
?>
<div class="widget">
    <h3>Seleccionar log por tipo y fecha</h3>
    <form id="filter-form" action="/admin/accounts/viewer" method="get">
        <div style="float:left;margin:5px;">
            <label for="type-filter">Tipo de proceso:</label><br />
            <select id="type-filter" name="type">
                <option value="invest"<?php if ($type == 'invest') echo ' selected="selected"';?>>Aportes</option>
                <option value="execute"<?php if ($type == 'execute') echo ' selected="selected"';?>>Cargos</option>
                <option value="verify"<?php if ($type == 'verify') echo ' selected="selected"';?>>Verificaciones</option>
                <option value="daily"<?php if ($type == 'daily') echo ' selected="selected"';?>>Avisos</option>
                <option value="dopay"<?php if ($type == 'dopay') echo ' selected="selected"';?>>Pagos</option>
            </select>
        </div>
        <div style="float:left;margin:5px;" id="hdate">
            <label for="date-filter">Fecha del log:</label><br />
            <?php echo new View('library/superform/view/element/datebox.html.php', array('value'=>$date, 'id'=>'hdate', 'name'=>'date')); ?>
        </div>
        <div style="float:left;margin:5px;">
            <input type="submit" value="Ver" />
        </div>
    </form>
</div>

<?php if ($showlog) echo '<strong>archivo:</strong> ' . $file . '<br /><br />';
if (!empty($content)) echo nl2br($content); else echo 'No encontrado'; ?>
<br /><br /><br />
