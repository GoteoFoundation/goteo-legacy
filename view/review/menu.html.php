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
?>
        <div id="review-menu">
            <ul>
            <?php foreach ($this['menu'] as $section=>$item) : ?>
                <li class="section<?php if ($section == $this['section']) echo ' current'; ?>">
                    <a href="/review/<?php echo $section; ?>"><?php echo $item['label']; ?></a>
                    <ul>
                    <?php foreach ($item['options'] as $option=>$label) : ?>
                        <li class="option<?php if ($section == $this['section'] && $option == $this['option']) echo ' current'; ?>">
                            <a href="/review/<?php echo $section; ?>/<?php echo $option; ?>"><?php echo $label; ?></a>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>

