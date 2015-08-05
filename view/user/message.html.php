<?php
/*
 *  Copyright (C) 2012 Platoniq y Fundación Goteo (see README for details)
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
    Goteo\Library\Worth,
    Goteo\Library\Text,
    Goteo\Model\User\Interest;

$bodyClass = 'user-profile';
include 'view/prologue.html.php';
include 'view/header.html.php';

$user = $this['user'];
$worthcracy = Worth::getAll();

$_SESSION['msg_token'] = uniqid(rand(), true);

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

<?php echo new View('view/user/widget/header.html.php', array('user'=>$user)) ?>

<div id="main">

    <div class="center">

    <?php if (!empty($_SESSION['user']->id)) : ?>
    <div class="widget user-message">

        <h3 class="title"><?php echo Text::get('user-message-send_personal-header'); ?></h3>

        <form method="post" action="/message/personal/<?php echo $user->id; ?>">
            <input type="hidden" name="msg_token" value="<?php echo $_SESSION['msg_token'] ; ?>" />
            
            <label for="contact-subject"><?php echo Text::get('contact-subject-field'); ?></label>
            <input id="contact-subject" type="text" name="subject" value="" placeholder="" />
            
            <label for="message"><?php echo Text::get('contact-message-field'); ?></label>
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

        <?php echo new View('view/user/widget/worth.html.php', array('worthcracy' => $worthcracy, 'level' => $user->worth)) ?>

        <?php echo new View('view/user/widget/about.html.php', array('user' => $user)) ?>

        <?php echo new View('view/user/widget/social.html.php', array('user' => $user)) ?>

    </div>
    <div class="side">
        <?php echo new View('view/user/widget/investors.html.php', $this) ?>
        <?php echo new View('view/user/widget/sharemates.html.php', $this) ?>
    </div>

</div>

<?php include 'view/footer.html.php' ?>

<?php include 'view/epilogue.html.php' ?>
