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

$bodyClass = 'user-login';
// para que el prologue ponga el c�digo js para bot�n facebook en el bannerside
$fbCode = Text::widget(Text::get('social-account-facebook'), 'fb');
include 'view/prologue.html.php';
include 'view/header.html.php';

$errors = $this['errors'];
extract($_POST);
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
    $("#register_accept").click(function (event) {
        if (this.checked) {
            $("#register_continue").removeClass('disabled').addClass('aqua');
            $("#register_continue").removeAttr('disabled');
        } else {
            $("#register_continue").removeClass('aqua').addClass('disabled');
            $("#register_continue").attr('disabled', 'disabled');
        }
    });

    //openid
    $('.sign-in-with li.openid input').focus(function(){
		$(this).addClass('focus');
		if($(this).val() == '<?php echo Text::get('login-signin-openid'); ?>') $(this).val('');
	});
    $('.sign-in-with li.openid input').blur(function(){
		$(this).removeClass('focus');
		if($(this).val().trim() == '') $(this).val('<?php echo Text::get('login-signin-openid'); ?>');
	});
	$('.sign-in-with li.openid a').click(function(){
		$(this).attr('href',$(this).attr('href') + '?provider=' + $('.sign-in-with li.openid input').val());
		return true;
	});
	$('.sign-in-with li.openid input').keypress(function(event) {
		if ( event.which == 13 ) {
			event.preventDefault();
			location = $('.sign-in-with li.openid a').attr('href') + '?provider=' + $(this).val();
		}
	});

	//view more
	$('.sign-in-with li.more a').click(function(){
		$(this).parent().remove();
		$('.sign-in-with li:hidden').slideDown();
		return false;
	});
});
</script>

<div id="sub-header">
	<div class="clearfix">
		<div class="subhead-banner">
			<h2 class="message"><?php echo Text::html('login-banner-header'); ?></h2>
		</div>
		<div class="mod-pojctopen"><?php echo Text::html('open-banner-header', $fbCode); ?></div>
	</div>
</div>

<?php if(isset($_SESSION['messages'])) { include 'view/header/message.html.php'; } ?>


    <div id="main">
        <div class="login">
            <div>
                <h2><?php echo Text::get('login-access-header'); ?></h2>

                <form action="/user/login" method="post">
                    <input type="hidden" name="return" value="<?php echo $_GET['return']; ?>" />
                    <div class="username">
                        <label><?php echo Text::get('login-access-username-field'); ?>
                        <input type="text" name="username" value="<?php echo $username?>" /></label>
                    </div>

                    <div class="password">
                        <label><?php echo Text::get('login-access-password-field'); ?>
                        <input type="password" name="password" value="<?php echo $username?>" /></label>
                    </div>

                    <input type="submit" name="login" value="<?php echo Text::get('login-access-button'); ?>" />

                </form>

                <a href="/user/recover"><?php echo Text::get('login-recover-link'); ?></a>

            </div>
        </div>

        <div class="external-login">
            <div>
                <h2><?php echo Text::get('login-oneclick-header'); ?></h2>
                <ul class="sign-in-with">
                <?php

				//posarem primer l'ultim servei utilitzat
				//de manera que si l'ultima vegada t'has autentificat correctament amb google, el tindras el primer de la llista

				//la cookie serveix per saber si ja ens hem autentificat algun cop amb "un sol click"
				$openid = $_COOKIE['goteo_oauth_provider'];

				//l'ordre que es vulgui...
                $logins = array(
					'facebook' => '<a href="/user/oauth?provider=facebook">' . Text::get('login-signin-facebook') . '</a>',
					'twitter' => '<a href="/user/oauth?provider=twitter">' . Text::get('login-signin-twitter') . '</a>',
					'Google' => '<a href="/user/oauth?provider=Google">' . Text::get('login-signin-google') . '</a>',
					'Yahoo' => '<a href="/user/oauth?provider=Yahoo">' . Text::get('login-signin-yahoo') . '</a>',
					'myOpenid' => '<a href="/user/oauth?provider=myOpenid">' . Text::get('login-signin-myopenid') . '</a>',
					'linkedin' => '<a href="/user/oauth?provider=linkedin">' . Text::get('login-signin-linkedin') . '</a>',
					'openid' => ''
                );
                $is_openid = !array_key_exists($openid,$logins);
                $logins['openid'] = '<form><input type="text"'.($is_openid ? ' class="used"' : '').' name="openid" value="' . htmlspecialchars( $is_openid ? $openid : Text::get('login-signin-openid')) . '" /><a href="/user/oauth" class="button">' . Text::get('login-signin-openid-go') . '&rarr;</a></form>';
                //si se ha guardado la preferencia, lo ponemos primero
                $key = '';
                if($openid) {
					$key = array_key_exists($openid,$logins) ? $openid : 'openid';
					echo '<li class="'.strtolower($key).'">'.$logins[$key].'</li>';
					echo '<li class="more">&rarr;<a href="#">'.Text::get('login-signin-view-more').'</a></li>';

				}
                foreach($logins as $k => $v) {
					if($key != $k) echo '<li class="'.strtolower($k) .'"'. ( $openid ? ' style="display:none"' :'') .'>'.$v.'</li>';
				}
                ?>

                </ul>
            </div>
        </div>

        <div class="register">
            <div>
                <h2><?php echo Text::get('login-register-header'); ?></h2>
                <form action="/user/register" method="post">

                    <div class="userid">
                        <label for="RegisterUserid"><?php echo Text::get('login-register-userid-field'); ?></label>
                        <input type="text" id="RegisterUserid" name="userid" value="<?php echo htmlspecialchars($userid) ?>"/>
                    <?php if(isset($errors['userid'])) { ?><em><?php echo $errors['userid']?></em><?php } ?>
                    </div>

                    <div class="username">
                        <label for="RegisterUsername"><?php echo Text::get('login-register-username-field'); ?></label>
                        <input type="text" id="RegisterUsername" name="username" value="<?php echo htmlspecialchars($username) ?>"/>
                    <?php if(isset($errors['username'])) { ?><em><?php echo $errors['username']?></em><?php } ?>
                    </div>

                    <div class="email">
                        <label for="RegisterEmail"><?php echo Text::get('login-register-email-field'); ?></label>
                        <input type="text" id="RegisterEmail" name="email" value="<?php echo htmlspecialchars($email) ?>"/>
                    <?php if(isset($errors['email'])) { ?><em><?php echo $errors['email']?></em><?php } ?>
                    </div>

                    <div class="remail">
                        <label for="RegisterREmail"><?php echo Text::get('login-register-confirm-field'); ?></label>
                        <input type="text" id="RegisterREmail" name="remail" value="<?php echo htmlspecialchars($remail) ?>"/>
                    <?php if(isset($errors['remail'])) { ?><em><?php echo $errors['remail']?></em><?php } ?>
                    </div>


                    <div class="password">
                        <label for="RegisterPassword"><?php echo Text::get('login-register-password-field'); ?></label> <?php if (strlen($password) < 6) echo '<em>'.Text::get('login-register-password-minlength').'</em>'; ?>
                        <input type="password" id="RegisterPassword" name="password" value="<?php echo htmlspecialchars($password) ?>"/>
                    <?php if(isset($errors['password'])) { ?><em><?php echo $errors['password']?></em><?php } ?>
                    </div>

                     <div class="rpassword">
                        <label for="RegisterRPassword"><?php echo Text::get('login-register-confirm_password-field'); ?></label>
                        <input type="password" id="RegisterRPassword" name="rpassword" value="<?php echo htmlspecialchars($rpassword) ?>"/>
                    <?php if(isset($errors['rpassword'])) { ?><em><?php echo $errors['rpassword']?></em><?php } ?>
                    </div>


                    <input class="checkbox" id="register_accept" name="confirm" type="checkbox" value="true" />
                    <label class="conditions" for="register_accept"><?php echo Text::html('login-register-conditions'); ?></label><br />

                    <button class="disabled" disabled="disabled" id="register_continue" name="register" type="submit" value="register"><?php echo Text::get('login-register-button'); ?></button>

            </form>
            </div>
        </div>

    </div>

<?php include 'view/footer.html.php' ?>