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

$project = $this['project'];
$level = (int) $this['level'] ?: 3;

?>
<script type="text/javascript">
	// Mark DOM as javascript-enabled
	jQuery(document).ready(function ($) { 
	    //change div#preview content when textarea lost focus
		$("#message").blur(function(){
			$("#preview").html($("#message").val().replace(/\n/g, "<br />"));
		});
		
		//add fancybox on #a-preview click
		$("#a-preview").fancybox({
			'titlePosition'		: 'inside',
			'transitionIn'		: 'none',
			'transitionOut'		: 'none'
		});
	});
</script>
<?php if (!empty($_SESSION['user']->id) && $project->status >= 3) : ?>
<div class="widget project-message">
    
    <h<?php echo $level ?> class="title"><?php echo Text::get('project-messages-send_direct-header'); ?></h<?php echo $level ?>>
        
    <form method="post" action="/message/direct/<?php echo $project->id; ?>">    	
    	<div id="bocadillo"></div>
        <textarea id="message" name="message" cols="50" rows="5"></textarea>
        
        <a target="_blank" id="a-preview" href="#preview" class="preview">&middot;<?php echo Text::get('regular-preview'); ?></a>
        <div style="display:none">
        	<div id="preview" style="width:400px;height:300px;overflow:auto;">
                    
                </div>
        </div>
        
         
        
        <button class="green" type="submit"><?php echo Text::get('project-messages-send_message-button'); ?></button>
    </form>

</div>
<?php endif; ?>
