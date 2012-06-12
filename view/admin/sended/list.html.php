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

// paginacion
require_once 'library/pagination/pagination.php';

$filters = $this['filters'];
$templates = $this['templates'];

//arrastramos los filtros
$filter = "?user={$filters['user']}&template={$filters['template']}";

$sended = $this['sended'];

$pagedResults = new \Paginated($sended, 20, isset($_GET['page']) ? $_GET['page'] : 1);


?>
<div class="widget board">
    <form id="filter-form" action="/admin/sended" method="get">

        <table>
            <tr>
                <td>
                    <label for="user-filter">ID, nombre o email del destinatario</label><br />
                    <input id="user-filter" name="user" value="<?php echo $filters['user']; ?>" style="width:300px;"/>
                </td>
                <td>
                    <label for="template-filter">Plantilla</label><br />
                    <select id="template-filter" name="template" >
                        <option value="">Todas las plantillas</option>
                    <?php foreach ($templates as $templateId=>$templateName) : ?>
                        <option value="<?php echo $templateId; ?>"<?php if ($filters['template'] == $templateId) echo ' selected="selected"';?>><?php echo $templateName; ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="filter" value="Filtrar"></td>
            </tr>
        </table>
    </form>
</div>

<?php if (!empty($sended)) : ?>
<div class="widget board">
    <table>
        <thead>
            <tr>
                <th width="5%"><!-- Si no ves --></th>
                <th width="45%">Destinatario</th>
                <th width="35%">Plantilla</th>
                <th width="15%">Fecha</th>
                <th><!-- reenviar --></th>
            </tr>
        </thead>
        <tbody>
            <?php while ($send = $pagedResults->fetchPagedRow()) : 
                $link = SITE_URL.'/mail/'.base64_encode(md5(uniqid()).'¬'.$send->email.'¬'.$send->id).'/?email='.$send->email;
                ?>
            <tr>
                <td><a href="<?php echo $link; ?>" target="_blank">[Enlace]</a></td>
                <td><?php echo $send->user . " [ {$send->email} ]"; ?></td>
                <td><?php echo $templates[$send->template]; ?></td>
                <td><?php echo $send->date; ?></td>
                <td><!-- <a href="#" target="_blank">[Reenviar]</a> --></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<ul id="pagination">
<?php   $pagedResults->setLayout(new DoubleBarLayout());
        echo $pagedResults->fetchPagedNavigation(str_replace('?', '&', $filter)); ?>
</ul>
<?php else : ?>
<p>No se han encontrado registros</p>
<?php endif; ?>