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

use Goteo\Library\Text,
    Goteo\Core\ACL;

$translator = ACL::check('/translate') ? true : false;
$filters = $this['filters'];
?>
<a href="/admin/faq/add/?filter=" class="button red"><?php echo Text::_("Añadir pregunta");?></a>

<div class="widget board">
    <form id="sectionfilter-form" action="/admin/faq" method="get">
        <label for="section-filter"><?php echo Text::_("Mostrar las preguntas de:");?></label>
        <select id="section-filter" name="section" onchange="document.getElementById('sectionfilter-form').submit();">
        <?php foreach ($this['sections'] as $sectionId=>$sectionName) : ?>
            <option value="<?php echo $sectionId; ?>"<?php if ($filters['section'] == $sectionId) echo ' selected="selected"';?>><?php echo $sectionName; ?></option>
        <?php endforeach; ?>
        </select>
    </form>
</div>

<div class="widget board">
    <?php if (!empty($this['faqs'])) : ?>
    <table>
        <thead>
            <tr>
                <td><!-- Edit --></td>
                <th><?php echo Text::_("Título");?></th> <!-- title -->
                <th><?php echo Text::_("Posición");?></th> <!-- order -->
                <td><!-- Move up --></td>
                <td><!-- Move down --></td>
                <td><!-- Traducir--></td>
                <td><!-- Remove --></td>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($this['faqs'] as $faq) : ?>
            <tr>
                <td><a href="/admin/faq/edit/<?php echo $faq->id; ?>">[Editar]</a></td>
                <td><?php echo $faq->title; ?></td>
                <td><?php echo $faq->order; ?></td>
                <td><a href="/admin/faq/up/<?php echo $faq->id; ?>">[&uarr;]</a></td>
                <td><a href="/admin/faq/down/<?php echo $faq->id; ?>">[&darr;]</a></td>
                <?php if ($translator) : ?>
                <td><a href="/translate/faq/edit/<?php echo $faq->id; ?>" >[Traducir]</a></td>
                <?php endif; ?>
                <td><a href="/admin/faq/remove/<?php echo $faq->id; ?>" onclick="return confirm('Seguro que deseas eliminar este registro?');">[Quitar]</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
    <?php else : ?>
    <p>No se han encontrado registros</p>
    <?php endif; ?>
</div>