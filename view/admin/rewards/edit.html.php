<?php

use Goteo\Library\Text,
    Goteo\Library\NormalForm;

$invest = $this['invest'];
$project = $this['project'];
$user = $this['user'];

$rewards = array();
foreach ($invest->rewards as $key => $data) {
    $rewards[$data->id] = $data->id;
}
?>
<div class="widget">
    <p>
        <strong>Proyecto:</strong> <?php echo $project->name ?> (<?php echo $this['status'][$project->status] ?>)<br />
        <strong>Usuario: </strong><?php echo $user->name ?><br />
        <strong>Cantidad aportada: </strong><?php echo $invest->amount ?> &euro; <br />
    </p>
</div>

<form method="post" action="/admin/rewards/edit/<?php echo  $invest->id; ?>" >
    <h3>Recompensa</h3>
    <ul style="list-style: none;">

        <li>
            <label>
                <input class="individual_reward" type="checkbox" id="resign" name="resign" value="1" <?php if ($invest->resign) echo ' checked="checked"'; ?>/>
                Donativo
            </label>
        </li>
        <li><hr /></li>
        <li>
            <label>
                <input class="individual_reward" type="radio" id="no_reward" name="selected_reward" value="0" amount="0" <?php if (empty($ewards)) echo ' checked="checked"'; ?>/>
                Ninguna recompensa.
            </label>
        </li>
        <!-- <span class="chkbox"></span> -->
    <?php foreach ($project->individual_rewards as $individual) : ?>
    <li class="<?php echo $individual->icon ?><?php if ($individual->none) echo ' disabled' ?>">

        <label>
            <input type="radio" name="selected_reward" id="reward_<?php echo $individual->id; ?>" value="<?php echo $individual->id; ?>" amount="<?php echo $individual->amount; ?>" class="individual_reward" title="<?php echo htmlspecialchars($individual->reward) ?>" <?php if ($individual->none) echo 'disabled="disabled"' ?>  <?php if (isset($rewards[$individual->id])) echo ' checked="checked"'; ?>/>
            <?php echo htmlspecialchars($individual->reward) . ' <strong>' .$individual->amount . ' &euro; </strong>' ?>
        </label>

    </li>
    <?php endforeach ?>
    </ul>


<?php
echo new NormalForm(array(

    'level'         => 3,
    'method'        => 'post',
    'footer'        => array(
        'view-step-overview' => array(
            'type'  => 'submit',
            'label' => Text::get('form-apply-button'),
            'class' => 'next',
            'name'  => 'update'
        )
    ),
    'elements'      => array(

        'name' => array(
            'type'      => 'textbox',
            'size'      => 40,
            'title'     => Text::get('personal-field-contract_name'),
            'value'     => $invest->address->name
        ),

        'nif' => array(
            'type'      => 'textbox',
            'title'     => Text::get('personal-field-contract_nif'),
            'size'      => 15,
            'value'     => $invest->address->nif
        ),

        'address' => array(
            'type'  => 'textbox',
            'title' => Text::get('personal-field-address'),
            'size'  => 55,
            'value' => $invest->address->address
        ),

        'location' => array(
            'type'  => 'textbox',
            'title' => Text::get('personal-field-location'),
            'size'  => 55,
            'value' => $invest->address->location
        ),

        'zipcode' => array(
            'type'  => 'textbox',
            'title' => Text::get('personal-field-zipcode'),
            'size'  => 7,
            'value' => $invest->address->zipcode
        ),

        'country' => array(
            'type'  => 'textbox',
            'title' => Text::get('personal-field-country'),
            'size'  => 55,
            'value' => $invest->address->country
        ),

    )

));

?>
</form>