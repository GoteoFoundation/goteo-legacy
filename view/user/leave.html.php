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

$error = $this['error'];
$message = $this['message'];
extract($_POST);
if (!isset($_POST['email']) && isset($_GET['email'])) {
    $email = $_GET['email'];
}
?>
<div id="sub-header">
	<div class="clearfix">
		<div class="subhead-banner">
			<h2 class="message"><?php echo Text::html('login-banner-header'); ?></h2>
		</div>
		<div class="mod-pojctopen"><?php echo Text::html('open-banner-header', $fbCode); ?></div>
	</div>
</div>
    <div id="main">

        <div class="login">

            <div>

                <h2><?php echo Text::get('login-leave-header'); ?></h2>

                <?php if (!empty($error)): ?>
                <p class="error"><?php echo $error; ?></p>
                <?php endif ?>
                <?php if (!empty($message)): ?>
                <p><?php echo $message; ?></p>
                <?php endif ?>

                <form action="/user/leave" method="post">
                    <div class="email">
                        <label><?php echo Text::get('login-recover-email-field'); ?>
                        <input type="text" name="email" value="<?php echo $email?>" /></label>
                    </div>

                    <div class="message">
                        <label for="leave-message"><?php echo Text::get('login-leave-message'); ?></label>
                        <textarea id="leave-message" name="reason" cols="50" rows="5"><?php echo $reason ?></textarea>
                    </div>

                    <input type="submit" name="leaving" value="<?php echo Text::get('login-leave-button'); ?>" />

                </form>

            </div>
        </div>

    </div>

<?php include 'view/footer.html.php' ?>