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
include 'view/prologue.html.php';
include 'view/header.html.php';

$oauth = $this['oauth'];
$user = $this['user'];
//print_r($user);
extract($oauth->user_data);

?>
<?php if(isset($_SESSION['messages'])) { include 'view/header/message.html.php'; } ?>


    <div id="main">

        <div class="register">
            <div>
                <h2><?php echo Text::get('oauth-confirm-user'); ?></h2>
                <p><?php echo Text::get('oauth-goteo-openid-sync-password'); ?></p>
                <div>
				<?php
					echo '<img style="padding-right:12px;float:left;" src="/image/'.$user->avatar->id.'/56/56/1" alt="Profile image" />';
					echo '<p style="padding-top:30px;">'.$user->name.''."<br />\n";
					echo '<strong>'.$email.'</strong>'."</p>\n";
				?>
				<div style="clear:both;"></div></div>
                <form action="/user/oauth_register" method="post">

					<div class="password">
                        <label><?php echo Text::get('login-access-password-field'); ?>
                        <input type="password" name="password" value="<?php echo $username?>" /></label>
                    </div>

                    <input type="submit" name="login" value="<?php echo Text::get('login-access-button'); ?>" />

					<?php

					//tokens para poder saber que es un registro automatico
					foreach($oauth->tokens as $key => $val) {
						if($val['token']) echo '<input type="hidden" name="tokens[' . $key . '][token]" value="' . htmlspecialchars($val['token']) . '" />';
						if($val['secret']) echo '<input type="hidden" name="tokens[' . $key . '][secret]" value="' . htmlspecialchars($val['secret']) . '" />';
					}

					//data extra para incluir al usuario
					foreach($oauth->user_data as $key => $val) {
						if($val && $key!='email') echo '<input type="hidden" name="' . $key . '" value="' . htmlspecialchars($val) . '" />';
					}
					//proveedor
					echo '<input type="hidden" name="provider" value="' . $oauth->original_provider . '" />';
					//email original
					echo '<input type="hidden" name="email" value="' . $email . '" />';
					echo '<input type="hidden" name="provider_email" value="' . $email . '" />';
					?>

				</form>
            </div>
        </div>

    </div>

<?php include 'view/footer.html.php' ?>
