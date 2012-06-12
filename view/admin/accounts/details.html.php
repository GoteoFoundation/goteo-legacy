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
    Goteo\Library\Paypal,
    Goteo\Library\Tpv;

$invest = $this['invest'];
$project = $this['project'];
$campaign = $this['campaign'];
$user = $this['user'];

?>
<div class="widget">
    <p>
        <strong>Proyecto:</strong> <?php echo $project->name ?> (<?php echo $this['status'][$project->status] ?>)
        <strong>Usuario: </strong><?php echo $user->name ?> [<?php echo $user->email ?>]
    </p>
    <?php /* if ($invest->status == 1) : ?>
    <h3>Operaciones</h3>
    <p>
            <a href="/admin/invests/return/<?php echo $invest->id ?>"
                onclick="return confirm('¿Estás seguro de querer echar atrás toda la transacción?');"
                class="button red">Devolver el dinero</a>
    </p>
    <?php endif; */ ?>
    <h3>Detalles de la transaccion</h3>
    <dl>
        <dt>Cantidad aportada:</dt>
        <dd><?php echo $invest->amount ?> &euro;
            <?php
                if (!empty($invest->campaign))
                    echo 'Campaña: ' . $campaign->name;
            ?>
        </dd>
    </dl>

    <dl>
        <dt>Estado:</dt>
        <dd><?php echo $this['investStatus'][$invest->status]; if ($invest->status < 0) echo ' <span style="font-weight:bold; color:red;">OJO! que este aporte no fue confirmado.<span>'; ?></dd>
    </dl>

    <dl>
        <dt>Fecha del aporte:</dt>
        <dd><?php echo $invest->invested . '  '; ?>
            <?php
                if (!empty($invest->charged))
                    echo 'Cargo ejecutado el: ' . $invest->charged;

                if (!empty($invest->returned))
                    echo 'Dinero devuelto el: ' . $invest->returned;
            ?>
        </dd>
    </dl>

    <dl>
        <dt>Método de pago:</dt>
        <dd><?php echo $invest->method; ?></dd>
    </dl>

    <dl>
        <dt>Códigos de seguimiento: <a href="/admin/invests/details/<?php echo $invest->id ?>">Ir al aporte</a></dt>
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

    <?php if ($invest->method == 'paypal') : ?>
        <?php if (!empty($invest->preapproval)) :
            $details = Paypal::preapprovalDetails($invest->preapproval);
            ?>
        <dl>
            <dt><strong>Detalles del preapproval:</strong></dt>
            <dd><?php echo \trace($details); ?></dd>
        </dl>
        <?php endif ?>

        <?php if (!empty($invest->payment)) :
            $details = Paypal::paymentDetails($invest->payment);
            ?>
        <dl>
            <dt><strong>Detalles del cargo:</strong></dt>
            <dd><?php echo \trace($details); ?></dd>
        </dl>
        <?php endif ?>

        <?php if (!empty($invest->transaction)) : ?>
        <dl>
            <dt><strong>Detalles de la devolución:</strong></dt>
            <dd>Hay que ir al panel de paypal para ver los detalles de una devolución</dd>
        </dl>
        <?php endif ?>
    <?php elseif ($invest->method == 'tpv') : ?>
        <p>Hay que ir al panel del banco para ver los detalles de los aportes mediante TPV.</p>
    <?php else : ?>
        <p>No hay nada que hacer con los aportes manuales.</p>
    <?php endif ?>

</div>
