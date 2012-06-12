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

use Goteo\Library\Page;

$pages = Page::getAll($_SESSION['translator_lang']);
?>
<div class="widget board">
    <table>
        <thead>
            <tr>
                <th><!-- Editar --></th>
                <th>Página</th>
                <th>Descripción</th>
                <!-- <th>Previsualizar</th> -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pages as $page) : ?>
            <tr>
                <td><a href="/translate/pages/edit/<?php echo $page->id; ?>">[Edit]</a></td>
                <td><?php echo $page->name; ?></td>
                <td><?php echo $page->description; ?></td>
<!--                <td><a href="<?php echo $page->url; ?>" target="_blank">[Preview]</a></td> -->
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
