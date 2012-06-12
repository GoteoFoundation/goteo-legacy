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

$bodyClass = 'admin';

// paginacion
require_once 'library/pagination/pagination.php';

// no cache para textos
define('GOTEO_ADMIN_NOCACHE', true);

$filter = $this['filter'];

$data = Text::getAll($this['filters'], $_SESSION['translator_lang']);

$pagedResults = new \Paginated($data, 20, isset($_GET['page']) ? $_GET['page'] : 1);

// valores de filtro
$idfilters = Text::filters();
$groups    = Text::groups();

// metemos el todos
\array_unshift($idfilters, 'Todos los textos');
\array_unshift($groups, 'Todas las agrupaciones');


$filters = array(
            'idfilter' => array(
                    'label'   => 'Filtrar por tipo:',
                    'type'    => 'select',
                    'options' => $idfilters,
                    'value'   => $this['filters']['idfilter']
                ),
            'group' => array(
                    'label'   => 'Filtrar por agrupación:',
                    'type'    => 'select',
                    'options' => $groups,
                    'value'   => $this['filters']['group']
                ),
            'text' => array(
                    'label'   => 'Buscar texto:',
                    'type'    => 'input',
                    'options' => null,
                    'value'   => $this['filters']['text']
                )
        );

?>
<!-- Filtro -->
<?php if (!empty($filters)) : ?>
<div class="widget board">
    <form id="filter-form" action="/translate/texts/list/<?php echo $filter ?>" method="get">
        <?php foreach ($filters as $id=>$fil) : ?>
        <?php if ($fil['type'] == 'select') : ?>
            <label for="filter-<?php echo $id; ?>"><?php echo $fil['label']; ?></label>
            <select id="filter-<?php echo $id; ?>" name="<?php echo $id; ?>" onchange="document.getElementById('filter-form').submit();">
            <?php foreach ($fil['options'] as $val=>$opt) : ?>
                <option value="<?php echo $val; ?>"<?php if ($fil['value'] == $val) echo ' selected="selected"';?>><?php echo $opt; ?></option>
            <?php endforeach; ?>
            </select>
        <?php endif; ?>
        <?php if ($fil['type'] == 'input') : ?>
            <br />
            <label for="filter-<?php echo $id; ?>"><?php echo $fil['label']; ?></label>
            <input name="<?php echo $id; ?>" value="<?php echo (string) $fil['value']; ?>" />
            <input type="submit" name="filter" value="Buscar">
        <?php endif; ?>
        <?php endforeach; ?>
    </form>
</div>
<?php endif; ?>

<?php if (!empty($data)) : ?>
<!-- lista -->
<div class="widget board">
    <table>
        <thead>
            <tr>
                <th></th>
                <th>Texto</th>
                <th>Agrupación</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
        <?php while ($item = $pagedResults->fetchPagedRow()) : ?>
            <tr>
                <td width="5%"><a title="Registro <?php echo $item->id ?>" href='/translate/texts/edit/<?php echo $item->id . $filter . '&page=' . $_GET['page']?>'>[Edit]</a></td>
                <td width="70%"><?php if ($item->pendiente == 1) echo '* '; ?><?php echo $item->text ?></td>
                <td width="25%"><?php echo $groups[$item->group] ?></td>
                <td></td>
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
