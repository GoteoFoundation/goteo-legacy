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
            

$project = $this['project'];
$errors = $project->errors[$this['step']] ?: array();
$okeys  = $project->okeys[$this['step']] ?: array();

$social_rewards = array();
$individual_rewards = array();

$txt_details = Text::get('regular-see_details');

foreach ($project->social_rewards as $social_reward) {
       
    // a ver si es el que estamos editando o no
    if (!empty($this["social_reward-{$social_reward->id}-edit"])) {
                
        $types = array();
                        
        foreach ($this['stypes'] as $type) {
            
            $licenses = array();

            if (!empty($type->licenses)) {
                foreach ($type->licenses as $lid => $license) {

                    if (!empty($license->url)) {
                        $url = ' <a href="'.$license->url.'" target="_blank" class="license-hint-details">'.$txt_details.'</a>';
                    } else {
                        $url = '';
                    }

                    $licenses["social_reward-{$social_reward->id}-license-{$license->id}"] = array(
                        'name'  => "social_reward-{$social_reward->id}-{$type->id}-license",
                        'label' => $license->name,
                        'value' => $license->id,
                        'type'  => 'radio',
                        'class' => 'license license_' . $license->id,
                        'hint'  => $license->description .  $url,
                        'id'    => "social_reward-{$social_reward->id}-license-{$license->id}",
                        'checked' => $license->id == $social_reward->license ? true : false
                    );

                }
            }

            if ($type->id == 'other') {
                // un campo para especificar el tipo
                $children = array(
                    "social_reward-{$social_reward->id}-other" => array(
                        'type'      => 'textbox',
                        'class'     => 'inline other',
                        'title'     => Text::get('rewards-field-social_reward-other'),
                        'value'     => $social_reward->other,
                        'name'      => "social_reward-{$social_reward->id}-{$type->id}",
                        'hint'      => Text::get('tooltip-project-social_reward-icon-other')
                    )
                );
            } elseif (!empty($licenses)) {
                $children = array(
                    "social_reward-{$social_reward->id}-license" => array(
                        'type'      => 'group',
                        'class'     => 'license',
                        'title'     => Text::get('rewards-field-social_reward-license'),
                        'children'  => $licenses,
                        'value'     => $social_reward->license,
                        'name'      => "social_reward-{$social_reward->id}-{$type->id}-license"
                    )
                );
            } else {
                $children = array(
                    "social_reward-{$social_reward->id}-license" => array(
                        'type' => 'hidden',
                        'name' => "social_reward-{$social_reward->id}-{$type->id}-license"
                    )
                );
            }


            $types["social_reward-{$social_reward->id}-icon-{$type->id}"] =  array(
                'name'  => "social_reward-{$social_reward->id}-icon",
                'value' => $type->id,
                'type'  => 'radio',
                'class' => "social_reward-type reward-type reward_{$type->id} social_{$type->id}",
                'label' => $type->name,
                'hint'  => $type->description,
                'id'    => "social_reward-{$social_reward->id}-icon-{$type->id}",
                'checked' => $type->id == $social_reward->icon ? true : false,
                'children' => $children
            );
                
        }                       
        
        // a este grupo le ponemos estilo de edicion
        $social_rewards["social_reward-{$social_reward->id}"] = array(
                'type'      => 'group',
                'class'     => 'reward social_reward editsocial_reward',
                'children'  => array(
                    "social_reward-{$social_reward->id}-edit" => array(
                        'type'      => 'hidden',
                        'value'     => '1'
                    ),
                    "social_reward-{$social_reward->id}-reward" => array(
                        'title'     => Text::get('rewards-field-social_reward-reward'),
                        'type'      => 'textbox',
                        'required'  => true,
                        'class'     => 'inline',
                        'value'     => $social_reward->reward,
                        'errors'    => !empty($errors["social_reward-{$social_reward->id}-reward"]) ? array($errors["social_reward-{$social_reward->id}-reward"]) : array(),
                        'ok'        => !empty($okeys["social_reward-{$social_reward->id}-reward"]) ? array($okeys["social_reward-{$social_reward->id}-reward"]) : array(),
                        'hint'      => Text::get('tooltip-project-social_reward-reward')
                    ),
                    "social_reward-{$social_reward->id}-description" => array(
                        'type'      => 'textarea',
                        'required'  => true,
                        'title'     => Text::get('rewards-field-social_reward-description'),
                        'cols'      => 100,
                        'rows'      => 4,
                        'class'     => 'inline reward-description',
                        'value'     => $social_reward->description,
                        'errors'    => !empty($errors["social_reward-{$social_reward->id}-description"]) ? array($errors["social_reward-{$social_reward->id}-description"]) : array(),
                        'ok'        => !empty($okeys["social_reward-{$social_reward->id}-description"]) ? array($okeys["social_reward-{$social_reward->id}-description"]) : array(),
                        'hint'      => Text::get('tooltip-project-social_reward-description')
                    ),
                    "social_reward-{$social_reward->id}-icon" => array(
                        'title'     => Text::get('rewards-field-social_reward-type'),
                        'class'     => 'inline',
                        'type'      => 'group',
                        'required'  => true,
                        'children'  => $types,
                        'value'     => $social_reward->icon,
                        'errors'    => !empty($errors["social_reward-{$social_reward->id}-icon"]) ? array($errors["social_reward-{$social_reward->id}-icon"]) : array(),
                        'ok'        => !empty($okeys["social_reward-{$social_reward->id}-icon"]) ? array($okeys["social_reward-{$social_reward->id}-icon"]) : array(),
                        'hint'      => Text::get('tooltip-project-social_reward-type')
                    ),                    
                    "social_reward-{$social_reward->id}-buttons" => array(
                        'type' => 'group',
                        'class' => 'buttons',
                        'children' => array(
                            "social_reward-{$social_reward->id}-ok" => array(
                                'type'  => 'submit',
                                'label' => Text::get('form-accept-button'),
                                'class' => 'inline ok'
                            ),
                            "social_reward-{$social_reward->id}-remove" => array(
                                'type'  => 'submit',
                                'label' => Text::get('form-remove-button'),
                                'class' => 'inline remove weak'
                            )
                        )
                    )
                )
            );
    } else {

        $social_rewards["social_reward-{$social_reward->id}"] = array(
            'class'     => 'reward social_reward',
            'view'      => 'view/project/edit/rewards/reward.html.php',
            'data'      => array('reward' => $social_reward, 'licenses' => $this['licenses'], 'types' => $this['stypes']),
        );
        
    }

}

foreach ($project->individual_rewards as $individual_reward) {

    // a ver si es el que estamos editando o no
    if (!empty($this["individual_reward-{$individual_reward->id}-edit"])) {

        // lo mismo que para las licencias solamente para el texto en el tipo otro
        $types = array();

        foreach ($this['itypes'] as $type) {

            if ($type->id == 'other') {
                // un campo para especificar el tipo
                $children = array(
                        "individual_reward-{$individual_reward->id}-other" => array(
                            'type'      => 'textbox',
                            'class'     => 'inline other',
                            'title'     => Text::get('rewards-field-individual_reward-other'),
                            'value'     => $individual_reward->other,
                            'name'      => "individual_reward-{$individual_reward->id}-{$type->id}",
                            'hint'     => Text::get('tooltip-project-individual_reward-icon-other')
                        )
                    );
            } else {
                // como tener children sin tenerlos
                $children = array(
                    "individual_reward-{$individual_reward->id}-{$type->id}" => array(
                        'type'      => 'hidden',
                        'name'      => "individual_reward-{$individual_reward->id}-other"
                    )
                );
            }
            
            $types["individual_reward-{$individual_reward->id}-icon-{$type->id}"] =  array(
                'name'  => "individual_reward-{$individual_reward->id}-icon",
                'value' => $type->id,
                'type'  => 'radio',
                'class' => "reward-type reward_{$type->id} individual_{$type->id}",
                'label' => $type->name,
                'hint'  => $type->description,
                'id'    => "individual_reward-{$individual_reward->id}-icon-{$type->id}",
                'checked' => $type->id == $individual_reward->icon ? true : false,
                'children' => $children
            );
        }

        // a este grupo le ponemos estilo de edicion
        $individual_rewards["individual_reward-{$individual_reward->id}"] = array(
                'type'      => 'group',
                'class'     => 'reward individual_reward editindividual_reward',
                'children'  => array(
                    "individual_reward-{$individual_reward->id}-edit" => array(
                        'type'      => 'hidden',
                        'value'     => '1'
                    ),
                    "individual_reward-{$individual_reward->id}-reward" => array(
                        'title'     => Text::get('rewards-field-individual_reward-reward'),
                        'required'  => true,
                        'type'      => 'textbox',
                        'size'      => 100,
                        'class'     => 'inline',
                        'value'     => $individual_reward->reward,
                        'errors'    => !empty($errors["individual_reward-{$individual_reward->id}-reward"]) ? array($errors["individual_reward-{$individual_reward->id}-reward"]) : array(),
                        'ok'        => !empty($okeys["individual_reward-{$individual_reward->id}-reward"]) ? array($okeys["individual_reward-{$individual_reward->id}-reward"]) : array(),
                        'hint'      => Text::get('tooltip-project-individual_reward-reward')
                    ),
                    "individual_reward-{$individual_reward->id}-description" => array(
                        'type'      => 'textarea',
                        'required'  => true,
                        'title'     => Text::get('rewards-field-individual_reward-description'),
                        'cols'      => 100,
                        'rows'      => 4,
                        'class'     => 'inline reward-description',
                        'value'     => $individual_reward->description,
                        'errors'    => !empty($errors["individual_reward-{$individual_reward->id}-description"]) ? array($errors["individual_reward-{$individual_reward->id}-description"]) : array(),
                        'ok'        => !empty($okeys["individual_reward-{$individual_reward->id}-description"]) ? array($okeys["individual_reward-{$individual_reward->id}-description"]) : array(),
                        'hint'      => Text::get('tooltip-project-individual_reward-description')
                    ),
                    "individual_reward-{$individual_reward->id}-icon" => array(
                        'title'     => Text::get('rewards-field-individual_reward-type'),
                        'required'  => true,
                        'class'     => 'inline',
                        'type'      => 'group',
                        'children'  => $types,
                        'value'     => $individual_reward->icon,
                        'errors'    => !empty($errors["individual_reward-{$individual_reward->id}-icon"]) ? array($errors["individual_reward-{$individual_reward->id}-icon"]) : array(),
                        'ok'        => !empty($okeys["individual_reward-{$individual_reward->id}-icon"]) ? array($okeys["individual_reward-{$individual_reward->id}-icon"]) : array(),
                        'hint'      => Text::get('tooltip-project-individual_reward-type')
                    ),
                    "individual_reward-{$individual_reward->id}-amount" => array(
                        'title'     => Text::get('rewards-field-individual_reward-amount'),
                        'required'  => true,
                        'type'      => 'textbox',
                        'size'      => 5,
                        'class'     => 'inline reward-amount',
                        'value'     => $individual_reward->amount,
                        'errors'    => !empty($errors["individual_reward-{$individual_reward->id}-amount"]) ? array($errors["individual_reward-{$individual_reward->id}-amount"]) : array(),
                        'ok'        => !empty($okeys["individual_reward-{$individual_reward->id}-amount"]) ? array($okeys["individual_reward-{$individual_reward->id}-amount"]) : array(),
                        'hint'      => Text::get('tooltip-project-individual_reward-amount')
                    ),
                    "individual_reward-{$individual_reward->id}-units" => array(
                        'title'     => Text::get('rewards-field-individual_reward-units'),
                        'type'      => 'textbox',
                        'size'      => 5,
                        'class'     => 'inline reward-units',
                        'value'     => $individual_reward->units,
                        'hint'      => Text::get('tooltip-project-individual_reward-units'),
                    ),
                    "individual_reward-{$individual_reward->id}-buttons" => array(
                        'type' => 'group',
                        'class' => 'buttons',
                        'children' => array(
                            "individual_reward-{$individual_reward->id}-ok" => array(
                                'type'  => 'submit',
                                'label' => Text::get('form-accept-button'),
                                'class' => 'inline ok'
                            ),
                            "individual_reward-{$individual_reward->id}-remove" => array(
                                'type'  => 'submit',
                                'label' => Text::get('form-remove-button'),
                                'class' => 'inline remove weak'
                            )
                        )
                    )
                )
            );
        
    } else {

        $individual_rewards["individual_reward-{$individual_reward->id}"] = array(
            'class'     => 'reward individual_reward',
            'view'      => 'view/project/edit/rewards/reward.html.php',
            'data'      => array('reward' => $individual_reward, 'types' => $this['itypes']),
        );
        
    }
}

$sfid = 'sf-project-rewards';

echo new SuperForm(array(

    'id'            => $sfid,
    'action'        => '',
    'level'         => $this['level'],
    'method'        => 'post',
    'title'         => Text::get('rewards-main-header'),
    'hint'          => Text::get('guide-project-rewards'),    
    'class'         => 'aqua',    
    'elements'      => array(
        'process_rewards' => array (
            'type' => 'hidden',
            'value' => 'rewards'
        ),
        
        'social_rewards' => array(
            'type'      => 'group',
            'required'  => true,
            'title'     => Text::get('rewards-fields-social_reward-title'),
            'hint'      => Text::get('tooltip-project-social_rewards'),
            'class'     => 'rewards',
            'errors'    => !empty($errors["social_rewards"]) ? array($errors["social_rewards"]) : array(),
            'ok'        => !empty($okeys["social_rewards"]) ? array($okeys["social_rewards"]) : array(),
            'children'  => $social_rewards + array(
                'social_reward-add' => array(
                    'type'  => 'submit',
                    'label' => Text::get('form-add-button'),
                    'class' => 'add reward-add red',
                )
            )
        ),
        
        'individual_rewards' => array(
            'type'      => 'group',
            'required'  => true,
            'title'     => Text::get('rewards-fields-individual_reward-title'),
            'hint'      => Text::get('tooltip-project-individual_rewards'),
            'class'     => 'rewards',
            'errors'    => !empty($errors["individual_rewards"]) ? array($errors["individual_rewards"]) : array(),
            'ok'        => !empty($okeys["individual_rewards"]) ? array($okeys["individual_rewards"]) : array(),
            'children'  => $individual_rewards + array(
                'individual_reward-add' => array(
                    'type'  => 'submit',
                    'label' => Text::get('form-add-button'),
                    'class' => 'add reward-add red',
                )
            )
        ),
        
        'footer' => array(
            'type'      => 'group',
            'children'  => array(
                'errors' => array(
                    'title' => Text::get('form-footer-errors_title'),
                    'view'  => new View('view/project/edit/errors.html.php', array(
                        'project'   => $project,
                        'step'      => $this['step']
                    ))                    
                ),
                'buttons'  => array(
                    'type'  => 'group',
                    'children' => array(
                        'next' => array(
                            'type'  => 'submit',
                            'name'  => 'view-step-supports',
                            'label' => Text::get('form-next-button'),
                            'class' => 'next'
                        )
                    )
                )
            )
        )
               
    )

));
?>
<script type="text/javascript">
$(function () {

    /* social rewards buttons */
    var socials = $('div#<?php echo $sfid ?> li.element#social_rewards');

    socials.delegate('li.element.social_reward input.edit', 'click', function (event) {
        var data = {};
        data[this.name] = '1';
        Superform.update(socials, data);
        event.preventDefault();
    });

    socials.delegate('li.element.editsocial_reward input.ok', 'click', function (event) {
        var data = {};
        data[this.name.substring(0, 18) + 'edit'] = '0';
        Superform.update(socials, data);
        event.preventDefault();
    });

    socials.delegate('li.element.editsocial_reward input.remove, li.element.social_reward input.remove', 'click', function (event) {
        var data = {};
        data[this.name] = '1';
        Superform.update(socials, data);
        event.preventDefault();
    });

    socials.delegate('#social_reward-add input', 'click', function (event) {
       var data = {};
       data[this.name] = '1';
       Superform.update(socials, data);
       event.preventDefault();
    });

    /* individual_rewards buttons */
    var individuals = $('div#<?php echo $sfid ?> li.element#individual_rewards');

    individuals.delegate('li.element.individual_reward input.edit', 'click', function (event) {
        var data = {};
        data[this.name] = '1';
        Superform.update(individuals, data);
        event.preventDefault();
    });

    individuals.delegate('li.element.editindividual_reward input.ok', 'click', function (event) {
        var data = {};
        data[this.name.substring(0, 22) + 'edit'] = '0';
        Superform.update(individuals, data);
        event.preventDefault();
    });

    individuals.delegate('li.element.editindividual_reward input.remove, li.element.individual_reward input.remove', 'click', function (event) {
        var data = {};
        data[this.name] = '1';
        Superform.update(individuals, data);
        event.preventDefault();
    });

    individuals.delegate('#individual_reward-add input', 'click', function (event) {
       var data = {};
       data[this.name] = '1';
       Superform.update(individuals, data);
       event.preventDefault();
    });

});
</script>