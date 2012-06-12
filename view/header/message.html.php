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

 $messages = $_SESSION['messages'];
unset($_SESSION['messages']);
?>
<script type="text/javascript">
	  jQuery(document).ready(function ($) { 
		   $(".message-close").click(function (event) {
					$("#message").fadeOut(2000);
           });
	  });
</script>
    <div id="message">
    	<div id="message-content">
        	<input type="button" class="message-close" >
            <ul>
    <?php foreach($messages as $message): ?>
                <li>
                    <span class="ui-icon ui-icon-<?php echo $message->type ?>">&nbsp;</span>
                    <span><?php echo nl2br($message->content) ?></span>
                </li>
    <?php endforeach; ?>
            </ul>
		</div>
    </div>
