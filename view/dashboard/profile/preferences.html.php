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

define('ADMIN_NOAUTOSAVE', true);


$errors = $this['errors'];
$preferences = $this['preferences'];

$allow = array(
    array(
        'value'     => 1,
        'label'     => Text::get('regular-yes')
        ),
    array(
        'value'     => 0,
        'label'     => Text::get('regular-no')
        )
);


?>
<form method="post" action="/dashboard/profile/preferences" class="project" >

<?php
echo new SuperForm(array(

    'level'         => 3,
    'method'        => 'post',
    'hint'          => Text::get('guide-dashboard-user-preferences'),
    'footer'        => array(
        'view-step-overview' => array(
            'type'  => 'submit',
            'label' => Text::get('form-apply-button'),
            'class' => 'next',
            'name'  => 'save-userPreferences'
        )
    ),
    'elements'      => array(

        'updates' => array(
            'title'     => Text::get('user-preferences-updates'),
            'type'      => 'slider',
            'options'   => $allow,
            'class'     => 'currently cols_' . count($allow),
            'hint'      => Text::get('tooltip-preferences-updates'),
            'errors'    => array(),
            'value'     => (int) $preferences->updates
        ),
        'threads' => array(
            'title'     => Text::get('user-preferences-threads'),
            'type'      => 'slider',
            'options'   => $allow,
            'class'     => 'currently cols_' . count($allow),
            'hint'      => Text::get('tooltip-preferences-threads'),
            'errors'    => array(),
            'value'     => (int) $preferences->threads
        ),
        'rounds' => array(
            'title'     => Text::get('user-preferences-rounds'),
            'type'      => 'slider',
            'options'   => $allow,
            'class'     => 'currently cols_' . count($allow),
            'hint'      => Text::get('tooltip-preferences-rounds'),
            'errors'    => array(),
            'value'     => (int) $preferences->rounds
        ),
        'mailing' => array(
            'title'     => Text::get('user-preferences-mailing'),
            'type'      => 'slider',
            'options'   => $allow,
            'class'     => 'currently cols_' . count($allow),
            'hint'      => Text::get('tooltip-preferences-mailing'),
            'errors'    => array(),
            'value'     => (int) $preferences->mailing
        )

    )

));

?>
</form>
