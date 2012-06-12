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
$errors = $project->errors['supports'] ?: array();
$okeys  = $project->okeys['supports'] ?: array();
$errors = $this['errors'];
$this['level'] = 3;

$support_types = array();

foreach ($this['types'] as $id => $type) {
    $support_types[] = array(
        'value' => $id,
        'class' => "support_{$id}",
        'label' => $type
    );
}

$supports = array();

foreach ($project->supports as $support) {

    $ch = array();

    // a ver si es el que estamos editando o no
    if (!empty($this["support-{$support->id}-edit"])) {


        $support_types = array();

        foreach ($this['types'] as $id => $type) {
            $support_types["support-{$support->id}-type-{$id}"] = array(
                'name'  => "support-{$support->id}-type",
                'value' => $id,
                'type'  => 'radio',
                'class' => "support-type support_{$id}",
                'hint'  => Text::get('tooltip-project-support-type-'.$id),
                'label' => $type,
                'checked' => $id == $support->type  ? true : false
            );
        }


        // a este grupo le ponemos estilo de edicion
    $supports["support-{$support->id}"] = array(
            'type'      => 'group',
            'class'     => 'support editsupport',
            'children'  => array(
                "support-{$support->id}-support" => array(
                    'title'     => Text::get('supports-field-support'),
                    'type'      => 'textbox',
                    'required'  => true,
                    'size'      => 100,
                    'class'     => 'inline',
                    'value'     => $support->support,
                    'errors'    => !empty($errors["support-{$support->id}-support"]) ? array($errors["support-{$support->id}-support"]) : array(),
                    'ok'        => !empty($okeys["support-{$support->id}-support"]) ? array($okeys["support-{$support->id}-support"]) : array(),
                    'hint'      => Text::get('tooltip-project-support-support'),
                ),
                "support-{$support->id}-type" => array(
                    'title'     => Text::get('supports-field-type'),
                    'required'  => true,
                        'class'     => 'inline',
                        'type'      => 'group',
                        'value'     => $support->type,
                        'children'  => $support_types,
                    'errors'    => !empty($errors["support-{$support->id}-type"]) ? array($errors["support-{$support->id}-type"]) : array(),
                    'ok'        => !empty($okeys["support-{$support->id}-type"]) ? array($okeys["support-{$support->id}-type"]) : array(),
                    'hint'      => Text::get('tooltip-project-support-type'),
                ),
                "support-{$support->id}-description" => array(
                    'type'      => 'textarea',
                    'required'  => true,
                    'title'     => Text::get('supports-field-description'),
                    'cols'      => 100,
                    'rows'      => 4,
                    'class'     => 'inline support-description',
                    'value'     => $support->description,
                    'errors'    => !empty($errors["support-{$support->id}-description"]) ? array($errors["support-{$support->id}-description"]) : array(),
                    'ok'        => !empty($okeys["support-{$support->id}-description"]) ? array($okeys["support-{$support->id}-description"]) : array(),
                    'hint'      => Text::get('tooltip-project-support-description')
                ),
                "support-{$support->id}-buttons" => array(
                    'type' => 'group',
                    'class' => 'buttons',
                    'children' => array(
                        "support-{$support->id}-ok" => array(
                            'type'  => 'submit',
                            'label' => Text::get('form-accept-button'),
                            'class' => 'inline ok'
                        ),
                        "support-{$support->id}-remove" => array(
                            'type'  => 'submit',
                            'label' => Text::get('form-remove-button'),
                                'class' => 'inline remove red'
                            )
                        )
                    )
                )
            );

    } else {

        $supports["support-{$support->id}"] = array(
            'class'     => 'support',
            'view'      => 'view/project/edit/supports/support.html.php',
            'data'      => array('support' => $support),
        );
    }


}


$sfid = 'sf-project-supports';

?>

<form method="post" action="<?php echo SITE_URL ?>/dashboard/projects/supports/save" class="project" enctype="multipart/form-data">

<?php echo new SuperForm(array(

    'id'            => $sfid,

    'action'        => '',
    'level'         => $this['level'],
    'method'        => 'post',
    'title'         => '',
    'hint'          => Text::get('guide-project-supports'),
    'class'         => 'aqua',
    'footer'        => array(
        'view-step-preview' => array(
            'type'  => 'submit',
            'name'  => 'save-supports',
            'label' => Text::get('regular-save'),
            'class' => 'next'
        )
    ),
    'elements'      => array(
        'process_supports' => array (
            'type' => 'hidden',
            'value' => 'supports'
        ),
        'supports' => array(
            'type'      => 'group',
            'title'     => Text::get('supports-fields-support-title'),
            'hint'      => Text::get('tooltip-project-supports'),
            'children'  => $supports + array(
                'support-add' => array(
                    'type'  => 'submit',
                    'label' => Text::get('form-add-button'),
                    'class' => 'add support-add red',
                )
            )
        )
    )

));
?>
</form>
<script type="text/javascript">
$(function () {

    var supports = $('div#<?php echo $sfid ?> li.element#supports');

    supports.delegate('li.element.support input.edit', 'click', function (event) {
        var data = {};
        data[this.name] = '1';
        Superform.update(supports, data);
        event.preventDefault();
    });

    supports.delegate('li.element.editsupport input.ok', 'click', function (event) {
        var data = {};
        data[this.name.substring(0, 12) + 'edit'] = '0';
        Superform.update(supports, data);
        event.preventDefault();
    });

    supports.delegate('li.element.editsupport input.remove, li.element.support input.remove', 'click', function (event) {
        var data = {};
        data[this.name] = '1';
        Superform.update(supports, data);
        event.preventDefault();
    });

    supports.delegate('#support-add input', 'click', function (event) {
       var data = {};
       data[this.name] = '1';
       Superform.update(supports, data);
       event.preventDefault();
    });

});
</script>