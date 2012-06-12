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

use Goteo\Library\Worth;

$worthcracy = isset($this['worthcracy']) ? $this['worthcracy'] : Worth::getAll();

if (!isset($this['level'])) $this['level'] = 9999;

// level: nivel que hay que resaltar con el "soy"
// , en este caso el resto de niveles por encima del destacado son grises

?>
<ul class="worthcracy">
<?php foreach ($worthcracy as $level => $worth): ?>
<li class="worth-<?php echo $level ?><?php if ($level <= $this['level']) echo ' done' ?>">
    <span class="threshold">+ de <strong><?php echo $worth->amount ?></strong></span>        
    <?php if ($level == $this['level']) : ?>
    <strong class="name"><?php echo htmlspecialchars($worth->name) ?></strong>
    <?php else: ?>
    <em class="name"><?php echo htmlspecialchars($worth->name) ?></em>        
    <?php endif; ?>
</li>
<?php endforeach ?>
</ul>