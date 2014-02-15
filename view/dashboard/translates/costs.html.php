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
$errors = $this['errors'];

$costs = array();

if (!empty($project->costs)) {

    foreach ($project->costs as $cost) {

        $req_class = $cost->required ? 'required_cost-yes' : 'required_cost-no';

        $ch = array();

        if (!empty($this["cost-{$cost->id}-edit"])) {

            $original = \Goteo\Model\Project\Cost::get($cost->id);

            $costs["cost-{$cost->id}"] = array(
                'type'      => 'group',
                'class'     => 'cost editcost '.$req_class,
                'children'  => array(
                    "cost-{$cost->id}-cost-orig" => array(
                        'title'     => Text::get('costs-field-cost'),
                        'type'      => 'html',
                        'html'      => $original->cost
                    ),
                    "cost-{$cost->id}-cost" => array(
                        'title'     => '',
                        'type'      => 'textbox',
                        'size'      => 100,
                        'class'     => 'inline',
                        'value'     => $cost->cost,
                        'errors'    => array(),
                        'ok'        => array()
                    ),
                    "cost-{$cost->id}-description-orig" => array(
                        'type'      => 'html',
                        'title'     => Text::get('costs-field-description'),
                        'html'      => nl2br($original->description)
                    ),
                    "cost-{$cost->id}-description" => array(
                        'type'      => 'textarea',
                        'title'     => '',
                        'cols'      => 100,
                        'rows'      => 4,
                        'class'     => 'inline cost-description',
                        'hint'      => Text::get('tooltip-project-cost-description'),
                        'errors'    => array(),
                        'ok'        => array(),
                        'value'     => $cost->description
                    ),
                    "cost-{$cost->id}-buttons" => array(
                        'type' => 'group',
                        'class' => 'buttons',
                        'children' => array(
                            "cost-{$cost->id}-ok" => array(
                                'type'  => 'submit',
                                'label' => Text::get('form-accept-button'),
                                'class' => 'inline ok'
                            )
                        )
                    )
                )
            );

        } else {
            $costs["cost-{$cost->id}"] = array(
                'class'     => 'cost ' . $req_class,
                'view'      => 'view/dashboard/translates/costs/cost.html.php',
                'data'      => array('cost' => $cost),
            );

        }


    }
}

$sfid = 'sf-project-costs';
?>

<form method="post" action="/dashboard/translates/costs/save" class="project" enctype="multipart/form-data">

<?php echo new SuperForm(array(

    'id'            => $sfid,

    'action'        => '',
    'level'         => 3,
    'method'        => 'post',
    'title'         => '',
    'hint'          => Text::get('guide-project-supports'),
    'class'         => 'aqua',
    'footer'        => array(
        'view-step-preview' => array(
            'type'  => 'submit',
            'name'  => 'save-costs',
            'label' => Text::get('regular-save'),
            'class' => 'next'
        )
    ),
    'elements'      => array(
        'process_costs' => array (
            'type' => 'hidden',
            'value' => 'costs'
        ),

        'costs' => array(
            'type'      => 'group',
            'title'     => Text::get('costs-fields-main-title'),
            'hint'      => Text::get('tooltip-project-costs'),
            'errors'    => array(),
            'ok'        => array(),
            'children'  => $costs
        )
    )

));
?>
</form>
<script type="text/javascript">
$(function () {

    var costs = $('div#<?php echo $sfid ?> li.element#costs');

    costs.delegate('li.element.cost input.edit', 'click', function (event) {
        var data = {};
        data[this.name] = '1';
        Superform.update(costs, data);
        event.preventDefault();
    });

    costs.delegate('li.element.editcost input.ok', 'click', function (event) {
        var data = {};
        data[this.name.substring(0, this.name.length-2) + 'edit'] = '0';
        Superform.update(costs, data);
        event.preventDefault();
    });

});
</script>
