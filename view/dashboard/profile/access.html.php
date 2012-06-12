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

use Goteo\Library\Text,
    Goteo\Library\SuperForm;

$user = $this['user'];
$errors = $this['errors'];
$this['level'] = 3;

$message = $this['action'] == 'recover' ? $this['message'] : '';

/*
if ($_SESSION['recovering'] == $_SESSION['user']->id) {
    $old_pass = array(
                    'type'  => 'hidden',
                    'errors'=> array(),
                    'value' => $user->id
                );
} else {
    $old_pass = array(
                    'type'  => 'password',
                    'class' => 'inline',
                    'title' => Text::get('user-changepass-old'),
                    'hint'  => Text::get('tooltip-dashboard-user-user_password'),
                    'errors'=> !empty($errors['password']) ? array($errors['password']) : array(),
                    'value' => $user_password
                );
}
*/

extract($_POST);
?>
<form action="/dashboard/profile/access" method="post" enctype="multipart/form-data">

<?php
echo new SuperForm(array(

    'level'         => $this['level'],
    'method'        => 'post',
    'hint'          => Text::get('guide-dashboard-user-access'),
    'elements'      => array(

        'action' => array(
            'type' => 'hidden',
            'value' => $this['action']
        ),

        'data' => array(
            'type'  => 'html',
            'title' => Text::get('dashboard-menu-profile-access'),
            'hint'  => Text::get('tooltip-dashboard-user-access_data'),
            'html'  => '<strong>'.Text::get('login-access-username-field').': </strong>'.$user->id.'<br /><strong>'.Text::get('login-register-email-field').': </strong>'.$user->email
        ),

        'change_email' => array(
            'type'      => 'group',
            'title'     => Text::get('user-changeemail-title'),
            'hint'      => Text::get('tooltip-dashboard-user-change_email'),
            'children'  => array(
                'user_nemail' => array(
                    'type'  => 'textbox',
                    'class' => 'inline',
                    'title' => Text::get('login-register-email-field'),
                    'hint'  => Text::get('tooltip-dashboard-user-new_email'),
                    'errors'=> !empty($errors['email']) ? array($errors['email']) : array(),
                    'value' => $user_nemail
                ),
                'user_remail' => array(
                    'type'  => 'textbox',
                    'class' => 'inline',
                    'title' => Text::get('login-register-confirm-field'),
                    'hint'  => Text::get('tooltip-dashboard-user-confirm_email'),
                    'errors'=> !empty($errors['email_retry']) ? array($errors['email_retry']) : array(),
                    'value' => $user_remail
                ),
                'change_email' => array(
                    'type'      => 'submit',
                    'label'     => Text::get('form-apply-button'),
                    'class'     => 'save'
                )

            )
        ),

        'change_password' => array(
            'type'      => 'group',
            'title'     => Text::get('user-changepass-title'),
            'hint'      => Text::get('tooltip-dashboard-user-change_password'),
            'children'  => array(
                /* Ya no requerimos la pasword actual.
                'user_password' => $old_pass ,
                 */
                'pass_anchor' => array(
                    'type'  => 'html',
                    'class' => 'inline',
                    'html'  => '<a name="password"></a>' . $messge
                ),
                'user_npassword' => array(
                    'type'  => 'password',
                    'class' => 'inline',
                    'title' => Text::get('user-changepass-new'),
                    'hint'  => Text::get('tooltip-dashboard-user-new_password'),
                    'errors'=> !empty($errors['password_new']) ? array($errors['password_new']) : array(),
                    'value' => $user_npassword
                ),
                'user_rpassword' => array(
                    'type'  => 'password',
                    'class' => 'inline',
                    'title' => Text::get('user-changepass-confirm'),
                    'hint'  => Text::get('tooltip-dashboard-user-confirm_password'),
                    'errors'=> !empty($errors['password_retry']) ? array($errors['password_retry']) : array(),
                    'value' => $user_rpassword
                ),
                'change_password' => array(
                    'type'      => 'submit',
                    'label'     => Text::get('form-apply-button'),
                    'class'     => 'save'
                )

            )
        ),




    )

));

?>

</form>
<hr />
<a class="button red" href="<?php echo SITE_URL ?>/user/leave?email=<?php echo $user->email ?>"><?php echo Text::get('login-leave-header'); ?></a>
