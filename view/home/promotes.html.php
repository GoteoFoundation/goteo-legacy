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

use Goteo\Core\View,
    Goteo\Library\Text;

$promotes = $this['promotes'];
// random y que solo pinte seis si hubiera más
if (count($promotes) > 6) {
	shuffle($promotes);
	$promotes = array_slice($promotes, 0, 6);
}
?>
<div class="widget projects">

    <h2 class="title"><?php echo Text::get('home-promotes-header'); ?></h2>

    <?php foreach ($promotes as $promo) : ?>

            <?php echo new View('view/project/widget/project.html.php', array(
                'project' => $promo->projectData,
                'balloon' => '<h4>' . htmlspecialchars($promo->title) . '</h4>' .
                             '<blockquote>' . $promo->description . '</blockquote>'
            )) ?>

    <?php endforeach ?>

</div>