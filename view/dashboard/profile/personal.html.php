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
$personal = $this['personal'];
$this['level'] = 3;

?>
<form method="post" action="/dashboard/profile/personal" class="project" enctype="multipart/form-data">

<?php
echo new SuperForm(array(

    'level'         => $this['level'],
    'method'        => 'post',
    'hint'          => Text::get('guide-dashboard-user-personal'),
    'footer'        => array(
        'view-step-overview' => array(
            'type'  => 'submit',
            'label' => Text::get('form-apply-button'),
            'class' => 'next',
            'name'  => 'save-userPersonal'
        )
    ),
    'elements'      => array(

        'contract_name' => array(
            'type'      => 'textbox',
            'required'  => true,
            'size'      => 20,
            'title'     => Text::get('personal-field-contract_name'),
            'hint'      => Text::get('tooltip-project-contract_name'),
            'errors'    => !empty($errors['contract_name']) ? array($errors['contract_name']) : array(),
            'value'     => $personal->contract_name
        ),

        'contract_nif' => array(
            'type'      => 'textbox',
            'required'  => true,
            'title'     => Text::get('personal-field-contract_nif'),
            'size'      => 15,
            'hint'      => Text::get('tooltip-project-contract_nif'),
            'errors'    => !empty($errors['contract_nif']) ? array($errors['contract_nif']) : array(),
            'value'     => $personal->contract_nif
        ),

        'phone' => array(
            'type'  => 'textbox',
            'required'  => true,
            'title' => Text::get('personal-field-phone'),
            'dize'  => 15,
            'hint'  => Text::get('tooltip-project-phone'),
            'errors'    => !empty($errors['phone']) ? array($errors['phone']) : array(),
            'value' => $personal->phone
        ),

        'address' => array(
            'type'  => 'textbox',
            'required'  => true,
            'title' => Text::get('personal-field-address'),
            'rows'  => 6,
            'cols'  => 40,
            'hint'  => Text::get('tooltip-project-address'),
            'errors'    => !empty($errors['address']) ? array($errors['address']) : array(),
            'value' => $personal->address
        ),

        'zipcode' => array(
            'type'  => 'textbox',
            'required'  => true,
            'title' => Text::get('personal-field-zipcode'),
            'size'  => 7,
            'hint'  => Text::get('tooltip-project-zipcode'),
            'errors'    => !empty($errors['zipcode']) ? array($errors['zipcode']) : array(),
            'value' => $personal->zipcode
        ),

        'location' => array(
            'type'  => 'textbox',
            'required'  => true,
            'title' => Text::get('personal-field-location'),
            'size'  => 25,
            'hint'  => Text::get('tooltip-project-location'),
            'errors'    => !empty($errors['location']) ? array($errors['location']) : array(),
            'value' => $personal->location
        ),

        'country' => array(
            'type'  => 'textbox',
            'required'  => true,
            'title' => Text::get('personal-field-country'),
            'size'  => 25,
            'hint'  => Text::get('tooltip-project-country'),
            'errors'    => !empty($errors['country']) ? array($errors['country']) : array(),
            'value' => $personal->country
        ),

    )

));

?>
</form>
