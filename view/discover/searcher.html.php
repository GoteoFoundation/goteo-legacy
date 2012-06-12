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

use Goteo\Model\Category,
    Goteo\Model\Icon,
    Goteo\Library\Location,
    Goteo\Library\Text;

$categories = Category::getList();  // categorias que se usan en proyectos
$locations = Location::getList();  //localizaciones de royectos
$rewards = Icon::getList(); // iconos que se usan en proyectos

$params = $this['params'];
?>
<div class="widget searcher">
    <form method="post" action="/discover/results">
        <div class="text-filter">
            <label for="text-query"><?php echo Text::get('discover-searcher-bycontent-header'); ?></label>
            <input type="text" id="text-query" name="query" size="48" value="<?php echo \htmlspecialchars($params['query']); ?>" />
            <br clear="all" />
        </div>

        <div class="filter">
            <label for="category"><?php echo Text::get('discover-searcher-bycategory-header'); ?></label>
                <select id="category" name="category[]" multiple size="10">
                    <option class="all" value="all"<?php if (empty($params['category'])) echo ' selected="selected"'; ?>><?php echo Text::get('discover-searcher-bycategory-all'); ?></option>
                <?php foreach ($categories as $id=>$name) : ?>
                    <option value="<?php echo $id; ?>"<?php if (in_array("'{$id}'", $params['category'])) echo ' selected="selected"'; ?>><?php echo $name; ?></option>
                <?php endforeach; ?>
                </select>
        </div>

        <div class="filter">
            <label for="location"><?php echo Text::get('discover-searcher-bylocation-header'); ?></label>
                <select id="location" name="location[]" multiple size="10">
                    <option class="all" value="all"<?php if (empty($params['location'])) echo ' selected="selected"'; ?>><?php echo Text::get('discover-searcher-bylocation-all'); ?></option>
                <?php foreach ($locations as $id=>$name) : ?>
                    <option value="<?php echo $id; ?>"<?php if (in_array("'{$id}'", $params['location'])) echo ' selected="selected"'; ?>><?php echo $name; ?></option>
                <?php endforeach; ?>
                </select>
        </div>

        <div class="filter">
            <label for="reward"><?php echo Text::get('discover-searcher-byreward-header'); ?> </label>
                <select id="reward" name="reward[]" multiple size="10">
                    <option class="all" value="all"<?php if (empty($params['reward'])) echo ' selected="selected"'; ?>><?php echo Text::get('discover-searcher-byreward-all'); ?></option>
                <?php foreach ($rewards as $id=>$reward) : ?>
                    <option value="<?php echo $id; ?>"<?php if (in_array("'{$id}'", $params['reward'])) echo ' selected="selected"'; ?>><?php echo $reward->name; ?></option>
                <?php endforeach; ?>
                </select>
        </div>

        <div style="float:left">
            <button type="submit" id="searcher" name="searcher"><?php echo Text::get('discover-searcher-button'); ?></button>
        </div>
        
        <br clear="all" />
    </form>
</div>
