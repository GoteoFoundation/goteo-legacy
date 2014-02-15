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
    Goteo\Library\Text,
    Goteo\Library\SuperForm;

$user = $this['user'];
$errors = $this['errors'];

$original = \Goteo\Model\User::get($user->id);

$sfid = 'sf-project-profile';
?>

<?php if (isset($this['ownprofile'])) : ?>
<div class="widget">Estas traduciendo tu perfil personal. <a href="/dashboard/translates/profile">Volver al perfil del autor del proyecto</a></div>
<?php elseif (!isset($this['noowner']) && $user->id != $_SESSION['user']->id && $_SESSION['user']->roles['translator']->id == 'translator') : ?>
<div class="widget">Estas traduciendo el perfil del autor del proyecto. <a href="/dashboard/translates/profile/own">Traducir mi perfil personal</a></div>
<?php endif; ?>

<form method="post" action="/dashboard/translates/profile/save" class="project" enctype="multipart/form-data">

<?php echo new SuperForm(array(
    'id'            => $sfid,
    'action'        => '',
    'level'         => 3,
    'method'        => 'post',
    'title'         => '',
    'hint'          => Text::get('guide-project-user-information'),
    'footer'        => array(
        'view-step-preview' => array(
            'type'  => 'submit',
            'name'  => 'save-userProfile',
            'label' => Text::get('regular-save'),
            'class' => 'next'
        )
    ),
    'elements'      => array(
        'process_userProfile' => array (
            'type' => 'hidden',
            'value' => 'userProfile'
        ),
        'id' => array (
            'type' => 'hidden',
            'value' => $user->id
        ),
        'about-orig' => array(
            'type'      => 'html',
            'title'     => Text::get('profile-field-about'),
            'html'     => nl2br($original->about)
        ),
        'about' => array(
            'type'      => 'textarea',
            'cols'      => 40,
            'rows'      => 4,
            'class'     => 'inline',
            'title'     => '',
            'hint'      => Text::get('tooltip-user-about'),
            'errors'    => array(),
            'ok'        => array(),
            'value'     => $user->about
        ),
        'keywords-orig' => array(
            'type'      => 'html',
            'title'     => Text::get('profile-field-keywords'),
            'html'     => $original->keywords
        ),
        'keywords' => array(
            'type'      => 'textbox',
            'size'      => 20,
            'class'     => 'inline',
            'title'     => '',
            'hint'      => Text::get('tooltip-user-keywords'),
            'errors'    => array(),
            'ok'        => array(),
            'value'     => $user->keywords
        ),
        'contribution-orig' => array(
            'type'      => 'html',
            'title'     => Text::get('profile-field-contribution'),
            'html'     => nl2br($original->contribution)
        ),
        'contribution' => array(
            'type'      => 'textarea',
            'cols'      => 40,
            'rows'      => 4,
            'class'     => 'inline',
            'title'     => '',
            'hint'      => Text::get('tooltip-user-contribution'),
            'errors'    => array(),
            'ok'        => array(),
            'value'     => $user->contribution
        )
    )
));
?>
</form>
