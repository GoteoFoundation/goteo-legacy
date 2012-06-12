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
    Goteo\Library\Worth,
    Goteo\Model\Invest,
    Goteo\Library\Text,
    Goteo\Model\License;

$project = $this['project'];
$personal = $this['personal'];
if (!empty($_GET['amount'])) {
    $amount = $_GET['amount'];
}

$level = (int) $this['level'] ?: 3;

$worthcracy = Worth::getAll();

$licenses = array();

foreach (License::getAll() as $l) {
    $licenses[$l->id] = $l;
}

$action = '/invest/' . $project->id;


?>
<div class="widget project-invest project-invest-amount">
    <h<?php echo $level ?> class="title"><?php echo Text::get('invest-amount') ?></h<?php echo $level ?>>
    
    <form method="post" action="<?php echo $action; ?>">

    <label><input type="text" id="amount" name="amount" class="amount" value="<?php echo $amount ?>" /><?php echo Text::get('invest-amount-tooltip') ?></label>
</div>

    
<div class="widget project-invest project-invest-individual_rewards">
    <h<?php echo $level ?> class="beak"><?php echo Text::get('invest-individual-header') ?></h<?php echo $level ?>>
    
    <div class="individual">
        <h<?php echo $level+1 ?> class="title"><?php echo Text::get('project-rewards-individual_reward-title'); ?></h<?php echo $level+1 ?>>
        <ul>
            <li><label class="resign"><input class="resign" type="checkbox" name="resign" value="1" /><span class="chkbox"></span><?php echo Text::get('invest-resign') ?></label></li>
        <?php foreach ($project->individual_rewards as $individual) : ?>
        <li class="<?php echo $individual->icon ?><?php if ($individual->none) echo ' disabled' ?>">
        <?php if ($individual->none) : ?>
            <label class="amount">
                <span style="color:red;"><?php echo Text::get('invest-reward-none') ?></span>
                <h<?php echo $level + 2 ?> class="name"><?php echo htmlspecialchars($individual->reward) ?></h<?php echo $level + 2 ?>>
                <p><?php echo htmlspecialchars($individual->description)?></p>
            </label>
        <?php else : ?>
            <label class="amount" for="reward_<?php echo $individual->id; ?>">
                <input type="checkbox"<?php if ($individual->none) echo ' disabled="disabled"';?> name="reward_<?php echo $individual->id; ?>" id="reward_<?php echo $individual->id; ?>" value="<?php echo $individual->amount; ?>" class="individual_reward" title="<?php echo htmlspecialchars($individual->reward) ?>" />
                <span class="chkbox"></span><?php echo $individual->amount; ?> &euro;
        	<h<?php echo $level + 2 ?> class="name"><?php echo htmlspecialchars($individual->reward) ?></h<?php echo $level + 2 ?>>
            <p><?php echo htmlspecialchars($individual->description)?></p>
            </label>
        <?php endif; ?>
            
        </li>
        <?php endforeach ?>
        </ul>
    </div>

</div>

<div class="widget project-invest address">
    <h<?php echo $level ?> class="beak" id="address-header"><?php echo Text::get('invest-address-header') ?></h<?php echo $level ?>>
    <table>
        <tr id="donation-data" style="display:none;">
            <td><label for="fullname"><?php echo Text::get('invest-address-name-field') ?></label></td>
            <td colspan="3"><input type="text" id="fullname" name="fullname" value="<?php echo $personal->contract_name; ?>" /></td>
            <td><label for="nif"><?php echo Text::get('invest-address-nif-field') ?></label></td>
            <td><input type="text" id="nif" name="nif" value="<?php echo $personal->contract_nif; ?>" /></td>
        </tr>
        <tr>
            <td><label for="address"><?php echo Text::get('invest-address-address-field') ?></label></td>
            <td colspan="3"><input type="text" id="address" name="address" value="<?php echo $personal->address; ?>" /></td>
            <td><label for="zipcode"><?php echo Text::get('invest-address-zipcode-field') ?></label></td>
            <td><input type="text" id="zipcode" name="zipcode" value="<?php echo $personal->zipcode; ?>" /></td>
        </tr>
        <tr>
            <td><label for="location"><?php echo Text::get('invest-address-location-field') ?></label></td>
            <td><input type="text" id="location" name="location" value="<?php echo $personal->location; ?>" /></td>
            <td><label for="country"><?php echo Text::get('invest-address-country-field') ?></label></td>
            <td><input type="text" id="country" name="country" value="<?php echo $personal->country; ?>" /></td>
            <td colspan="2"></td>
        </tr>
    </table>

    <p>
        <label><input type="checkbox" name="anonymous" value="1" /><span class="chkbox"></span><?php echo Text::get('invest-anonymous') ?></label>
    </p>
</div>


<div class="widget project-invest">
    <h<?php echo $level ?> class="beak"><?php echo Text::get('project-invest-continue') ?></h<?php echo $level ?>>
            
<input type="hidden" id="paymethod"  />

<p><button type="submit" class="process pay-tpv" name="method"  value="tpv">TPV</button></p>
<p><button type="submit" class="process pay-paypal" name="method"  value="paypal">PAYPAL</button></p>


</form>
</div>

<?php echo new View('view/project/widget/worth.html.php', array('worthcracy' => $worthcracy, 'level' => $_SESSION['user']->worth)) ?>


<div class="widget project-invest">
    <h<?php echo $level ?> class="beak"><?php echo Text::get('invest-social-header') ?></h<?php echo $level ?>>

    <div class="social">
        <h<?php echo $level + 1 ?> class="title"><?php echo Text::get('project-rewards-social_reward-title'); ?></h<?php echo $level + 1 ?>>
        <ul>
        <?php foreach ($project->social_rewards as $social) : ?>
            <li class="<?php echo $social->icon ?>">
                <h<?php echo $level + 2 ?> class="name"><?php echo htmlspecialchars($social->reward) ?></h<?php echo $level + 2 ?>
                <p><?php echo htmlspecialchars($social->description)?></p>
                <?php if (!empty($social->license) && array_key_exists($social->license, $licenses)): ?>
                <div class="license <?php echo htmlspecialchars($social->license) ?>">
                    <h<?php echo $level + 3 ?>><?php echo Text::get('regular-license'); ?></h<?php echo $level + 3 ?>>
                    <a href="<?php echo htmlspecialchars($licenses[$social->license]->url) ?>" target="_blank">
                        <strong><?php echo htmlspecialchars($licenses[$social->license]->name) ?></strong>

                    <?php if (!empty($licenses[$social->license]->description)): ?>
                    <p><?php echo htmlspecialchars($licenses[$social->license]->description) ?></p>
                    <?php endif ?>
                    </a>
                </div>
                <?php endif ?>
            </li>
        <?php endforeach; ?>
        </ul>
    </div>
</div>

<script type="text/javascript">
    
    $(function () {
        
        var input = $('div.widget.project-invest-amount input.amount'),
            lastVal = {},
            updating = null;
            
        var update = function () {
            
            try {
            
                var val = input.val();

                if (val !== lastVal) {
                    clearTimeout(updating);
                    lastVal = val;      
                    updating = setTimeout(function () {
                        var euros = parseFloat(val);
                        if (isNaN(euros)) {
                            euros = 0;
                        }
                        input.val(euros);

                        var resign = $('div.widget.project-invest-individual_rewards input.resign:checked').length > 0;
                        
                        $('div.widget.project-invest-individual_rewards input.individual_reward').each(function (i, cb) {
                           var $cb = $(cb);
                           var rval = parseFloat($cb.val());
                           if (!resign && (rval > 0 && rval <= euros)) {
                               $cb.removeAttr('disabled');
                               $cb.closest('li').removeClass('disabled');
                               if (!$cb.attr('checked')) {
                                   $cb.click();
                               }
                           } else {
                               $cb.attr('disabled', 'disabled');
                               $cb.closest('li').addClass('disabled');
                           }
                        });
                    });                  
                } 
            } catch (e) {
                clearTimeout(updating);
            }
            
        };    
        
        $('div.widget.project-invest-individual_rewards input.resign').change(function () {
            // Force update
            lastVal = {};
            update();
            var resign = $('div.widget.project-invest-individual_rewards input.resign:checked').length > 0;
            if (!resign) {
                $("#address-header").html('<?php echo Text::get('invest-address-header') ?>');
                $("#donation-data").hide();
            } else {
                $("#address-header").html('<?php echo Text::get('invest-donation-header') ?>');
                $("#donation-data").show();
            }
        });

        input.keydown(function () {        
            clearTimeout(updating);
            updating = setTimeout(
                function () {
                    update();
                }, 
                150);                
        });

        input.bind('paste', function () {             
            update();
        });

        input.focus(function () {
            updating = null;
            input.one('blur', function () {               
                updating = update();
            });
        });
        
        update();

        $('button.process').click(function () {

            var input = $('div.widget.project-invest-amount input.amount');
            var amount = input.val();

            if (amount <= 0) {
                alert('<?php echo Text::get('invest-amount-error') ?>');
                input.focus();
                return false;
            }

            /* Renuncias pero no has puesto tu NIF para desgravar el donativo */
            if ($('input.resign').attr('checked') == 'checked') {
                if ($('#nif').val() == '' && !confirm('<?php echo Text::get('invest-alert-renounce') ?>')) {
                    $('#nif').focus();
                    return false;
                }
            } else {
                var rewards = '';
                /* No has marcado ninguna recompensa, renuncias? */
                var noreward = true;
                $('input.individual_reward').each(function (i, cb) {
                   if ($(this).attr('checked') == 'checked' && $(this).attr('disabled') != 'disabled') {
                       rewards += $(this).attr('title') + ', ';
                       noreward = false;
                   }
                });

                if (noreward) {
                    if (confirm('<?php echo Text::get('invest-alert-noreward') ?>')) {
                        if (confirm('<?php echo Text::get('invest-alert-noreward_renounce') ?>')) {
                            $("#address-header").html('<?php echo Text::get('invest-donation-header') ?>');
                            $("#donation-data").show();
                            $('input.resign').click();
                            $('#nif').focus();
                            return false;
                        }
                    } else {
                        $('#nif').focus();
                        return false;
                    }
                } else {
                    /* Has elegido las siguientes recompensas */
                    if (!confirm('<?php echo Text::get('invest-alert-rewards') ?> '+rewards+' ok?')) {
                        return false;
                    }
                }
            }

            return confirm('<?php echo Text::get('invest-alert-investing') ?> '+amount+' EUR');
        });

    });    
    
</script>
