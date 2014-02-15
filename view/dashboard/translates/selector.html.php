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

?>
<script type="text/javascript">
function item_select(type) {
    document.getElementById('selector-type').value = type;
    document.getElementById('selector-form').submit();
}
</script>
<div id="project-selector">
    <form id="selector-form" name="selector_form" action="<?php echo '/dashboard/translates/overview/select'; ?>" method="post">
        <input type="hidden" id="selector-type" name="type" value="profile" />
        
    <?php if (!empty($this['projects'])) : ?>
        <label for="pselector"><?php echo Text::get('project-menu-home') ?></label>
        <select id="pselector" name="project" onchange="item_select('project');">
            <option value=""><?php echo Text::get('dashboard-translate-select_project') ?></option>
        <?php foreach ($this['projects'] as $project) : ?>
            <option value="<?php echo $project->id; ?>"<?php if ($project->id == $_SESSION['translate_project']->id) echo ' selected="selected"'; ?>><?php echo $project->name; ?></option>
        <?php endforeach; ?>
        </select><br />
    <?php endif; ?>
        
    </form>

    <form id="lang-form" name="lang_form" action="<?php echo '/dashboard/'.$this['section'].'/'.$this['option'].'/lang'; ?>" method="post">
        <label for="selang"><?php echo Text::get('regular-lang') ?></label>
        <select id="selang" name="lang" onchange="document.getElementById('lang-form').submit();" style="width:150px;">
        <?php foreach ($this['langs'] as $lng) : ?>
            <option value="<?php echo $lng->id; ?>"<?php if ($lng->id == $_SESSION['translate_lang']) echo ' selected="selected"'; ?>><?php echo $lng->name; ?></option>
        <?php endforeach; ?>
        </select>
    </form>

    <?php if ($_SESSION['translate_type'] == 'project' && !empty($_SESSION['translate_project'])) : ?>
    <p><?php echo Text::html('dashboard-translate-doing_project', $_SESSION['translate_project']->name, $this['project']->lang_name) ?></p>
    <?php endif; ?>

    <?php if (!empty($_SESSION['translate_type']) && $_SESSION['translate_type'] != 'profile') : ?>
        <a href="#" name="profile" class="button aqua" onclick="item_select('profile');"><?php echo Text::get('dashboard-translate-select_profile') ?></a>
    <?php else : ?>
        <p><?php echo Text::get('dashboard-translate-doing_profile') ?></p>
    <?php endif; ?>

</div>
