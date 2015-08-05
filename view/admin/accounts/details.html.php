<?php
/*
 *  Copyright (C) 2012 Platoniq y Fundación Goteo (see README for details)
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
    Goteo\Library\Paypal,
    Goteo\Library\Tpv;

$invest = $this['invest'];
$project = $this['project'];
$calls = $this['calls'];
$droped = $this['droped'];
$user = $this['user'];

$rewards = $invest->rewards;
array_walk($rewards, function (&$reward) { $reward = $reward->reward; });
?>
<a href="/admin/accounts/update/<?php echo $invest->id ?>" onclick="return confirm('Seguro que deseas cambiarle el estado a este aporte?, esto es delicado')" class="button">Cambiarle el estado</a>
&nbsp;&nbsp;&nbsp;
<a href="/admin/rewards/edit/<?php echo $invest->id ?>" class="button">Gestionar recompensa / dirección</a>
<?php if ($invest->issue) : ?>
&nbsp;&nbsp;&nbsp;
<a href="/admin/accounts/solve/<?php echo $invest->id ?>" onclick="return confirm('Esta incidencia se dará por resuelta: se va a cancelar el preaproval, el aporte pasará a ser de tipo Cash y en estado Cobrado por goteo, seguimos?')" class="button">Nos han hecho la transferencia</a>
<?php endif; ?>
<div class="widget">
    <p>
        <strong><?php echo Text::_("Proyecto"); ?>:</strong> <?php echo $project->name ?> (<?php echo $this['status'][$project->status] ?>)
        <strong><?php echo Text::_("Usuario"); ?>: </strong><?php echo $user->name ?> [<?php echo $user->email ?>]
    </p>
    <p>
        <?php if ($invest->status < 1 || ($invest->method == 'tpv' && $invest->status < 2) ||($invest->method == 'cash' && $invest->status < 2)) : ?>
        <a href="/admin/accounts/cancel/<?php echo $invest->id ?>"
            onclick="return confirm('¿Estás seguro de querer cancelar este aporte y su preapproval?');"
            class="button">Cancelar este aporte</a>&nbsp;&nbsp;&nbsp;
        <?php endif; ?>

        <?php if ($invest->method == 'paypal' && $invest->status == 0) : ?>
        <a href="/admin/accounts/execute/<?php echo $invest->id ?>"
            onclick="return confirm('¿Seguro que quieres ejecutar ahora el cargo del preapproval?');"
            class="button">Ejecutar cargo ahora</a>
        <?php endif; ?>

        <?php if ($invest->method != 'paypal' && $invest->status == 1) : ?>
        <a href="/admin/accounts/move/<?php echo $invest->id ?>" class="button">Reubicar este aporte</a>
        <?php endif; ?>

        <?php if (!$invest->resign && $invest->status == 1 && $invest->status == 3) : ?>
        <a href="/admin/accounts/resign/<?php echo $invest->id ?>/?token=<?php echo md5('resign'); ?>" class="button">Es donativo</a>
        <?php endif; ?>
    </p>
    
    <h3><?php echo Text::_("Detalles de la transaccion"); ?></h3>
    <dl>
        <dt><?php echo Text::_("Cantidad aportada"); ?>:</dt>
        <dd><?php echo $invest->amount ?> &euro;
            <?php
                if (!empty($invest->campaign))
                    echo Text::_("Campaña: ") . $campaign->name;
            ?>
        </dd>
    </dl>

    <dl>
        <dt><?php echo Text::_("Estado"); ?>:</dt>
        <dd><?php echo $this['investStatus'][$invest->status]; if ($invest->status < 0) echo ' <span style="font-weight:bold; color:red;">OJO! que este aporte no fue confirmado.<span>'; if ($invest->issue) echo ' <span style="font-weight:bold; color:red;">INCIDENCIA!<span>'; ?></dd>
    </dl>

    <dl>
        <dt><?php echo Text::_("Fecha del aporte"); ?>:</dt>
        <dd><?php echo $invest->invested . '  '; ?>
            <?php
                if (!empty($invest->charged))
                    echo Text::_("Cargo ejecutado el: ") . $invest->charged;

                if (!empty($invest->returned))
                    echo Text::_("Dinero devuelto el: ") . $invest->returned;
            ?>
        </dd>
    </dl>

    <dl>
        <dt><?php echo Text::_("Donativo"); ?>:</dt>
        <dd>
            <?php echo ($invest->resign) ? Text::_('SI') : Text::_('NO'); ?>
        </dd>
    </dl>

    <dl>
        <dt><?php echo Text::_("Método de pago"); ?>:</dt>
        <dd><?php echo $invest->method . '   '; ?>
            <?php
                if (!empty($invest->campaign))
                    echo '<br />'.Text::_('Capital riego');

                if (!empty($invest->anonymous))
                    echo '<br />'.Text::_(Aporte anónimo');

                if (!empty($invest->resign))
                    echo "<br />".Text::_('Donativo de').": {$invest->address->name} [{$invest->address->nif}]";

                if (!empty($invest->admin))
                    echo '<br />'.Text::_('Manual generado por admin').': '.$invest->admin;
            ?>
        </dd>
    </dl>

    <dl>
        <dt><?php echo Text::_("Códigos de seguimiento"); ?>: <a href="/admin/invests/details/<?php echo $invest->id ?>"><?php echo Text::_("Ir al aporte"); ?></a></dt>
        <dd><?php
                if (!empty($invest->preapproval)) {
                    echo 'Preapproval: '.$invest->preapproval . '   ';
                }

                if (!empty($invest->payment)) {
                    echo 'Cargo: '.$invest->payment . '   ';
                }
            ?>
        </dd>
    </dl>

    <?php if (!empty($invest->rewards)) : ?>
    <dl>
        <dt><?php echo Text::_("Recompensas elegidas"); ?>:</dt>
        <dd>
            <?php echo implode(', ', $rewards); ?>
        </dd>
    </dl>
    <?php endif; ?>

    <dl>
        <dt><?php echo Text::_("Dirección"); ?>:</dt>
        <dd>
            <?php echo $invest->address->address; ?>,
            <?php echo $invest->address->location; ?>,
            <?php echo $invest->address->zipcode; ?>
            <?php echo $invest->address->country; ?>
        </dd>
    </dl>

    <?php if ($invest->method == 'paypal') : ?>
        <?php if (!isset($_GET['full'])) : ?>
        <p>
            <a href="/admin/accounts/details/<?php echo $invest->id; ?>/?full=show"><?php echo Text::_("Mostrar detalles técnicos"); ?></a>
        </p>
        <?php endif; ?>

        <?php if (!empty($invest->transaction)) : ?>
        <dl>
            <dt><strong><?php echo Text::_("Detalles de la devolución"); ?>:</strong></dt>
            <dd><?php echo Text::_("Hay que ir al panel de paypal para ver los detalles de una devolución"); ?></dd>
        </dl>
        <?php endif ?>
    <?php elseif ($invest->method == 'tpv') : ?>
        <p><?php echo Text::_("Hay que ir al panel del banco para ver los detalles de los aportes mediante TPV."); ?></p>
    <?php else : ?>
        <p><?php echo Text::_("No hay nada que hacer con los aportes manuales."); ?></p>
    <?php endif ?>

    <?php if (!empty($droped)) : ?>
    <h3><?php echo Text::_("Capital riego asociado"); ?></h3>
    <dl>
        <dt><?php echo Text::_("Convocatoria"); ?>:</dt>
        <dd><?php echo $calls[$droped->call] ?></dd>
    </dl>
    <a href="/admin/invests/details/<?php echo $droped->id ?>" target="_blank"><?php echo Text::_("Ver aporte completo de riego"); ?></a>
    <?php endif; ?>

</div>

<div class="widget">
    <h3>Log</h3>
    <?php foreach (\Goteo\Model\Invest::getDetails($invest->id) as $log)  {
        echo "{$log->date} : {$log->log} ({$log->type})<br />";
    } ?>
</div>

<?php if (isset($_GET['full']) && $_GET['full'] == 'show') : ?>
<div class="widget">
    <h3><?php echo Text::_("Detalles técnicos de la transaccion"); ?></h3>
    <?php if (!empty($invest->preapproval)) :
        $details = Paypal::preapprovalDetails($invest->preapproval);
        ?>
    <dl>
        <dt><strong><?php echo Text::_("Detalles del preapproval"); ?>:</strong></dt>
        <dd><?php echo \trace($details); ?></dd>
    </dl>
    <?php endif ?>

    <?php if (!empty($invest->payment)) :
        $details = Paypal::paymentDetails($invest->payment);
        ?>
    <dl>
        <dt><strong><?php echo Text::_("Detalles del cargo"); ?>:</strong></dt>
        <dd><?php echo \trace($details); ?></dd>
    </dl>
    <?php endif; ?>
</div>
<?php endif; ?>

