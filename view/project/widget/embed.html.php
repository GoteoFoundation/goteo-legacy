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

	$url = SITE_URL . '/widget/project/' . $this['project']->id;
    if (LANG != 'es')
        $url .= '?lang=' . LANG;

    if (isset($this['investor']) && is_object($this['investor'])) {
        $url .= '/invested/'.$this['investor']->id;
    }

	$widget_code = Text::widget($url);
?>
<script type="text/javascript">
	// Mark DOM as javascript-enabled
	jQuery(document).ready(function ($) { 
		/*$("#code").click(function(){
			$("#code").focus();
			$("#code").select();					
		});*/
		
	});
</script>
<div id="project-code">
    <?php
        // el proyecto de trabajo
        echo new View('view/project/widget/project.html.php', $this);
    ?>
</div>
<div id="widget-code">
    <div class="wc-embed" onclick="$('#code').focus();$('#code').select()"><?php echo Text::get('project-spread-embed_code'); ?></div>
    <textarea id="code" onclick="this.focus();this.select()" readonly="readonly"><?php echo htmlentities($widget_code); ?></textarea>
</div>
