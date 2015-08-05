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
    Goteo\Model,
    Goteo\Core\Redirection,
    Goteo\Library\Message;

$project = $this['project'];

if (!$project instanceof Model\Project) {
    Message::Error('Instancia de proyecto corrupta');
    throw new Redirection('/admin/projects');
}

$accounts = $this['accounts'];
?>
<div class="widget">
    <p>Es necesario que un proyecto tenga una cuenta PayPal para ejecutar los cargos. La cuenta bancaria es solamente para tener toda la información en el mismo entorno pero no se utiliza en ningún proceso de este sistema.</p>

    <form method="post" action="/admin/projects" >
        <input type="hidden" name="id" value="<?php echo $project->id ?>" />

    <p>
        <label for="account-bank">Cuenta bancaria:</label><br />
        <input type="text" id="account-bank" name="bank" value="<?php echo $accounts->bank; ?>" style="width: 475px;"/>
    </p>

    <p>
        <label for="account-bank_owner">Titular de la cuenta bancaria:</label><br />
        <input type="text" id="account-bank_owner" name="bank_owner" value="<?php echo $accounts->bank_owner; ?>" style="width: 475px;"/>
    </p>

    <p>
        <label for="account-paypal">Cuenta PayPal:</label><br />
        <input type="text" id="account-paypal" name="paypal" value="<?php echo $accounts->paypal; ?>" style="width: 475px;"/>
    </p>

    <p>
        <label for="account-paypal_owner">Titular de la cuenta PayPal:</label><br />
        <input type="text" id="account-paypal_owner" name="paypal_owner" value="<?php echo $accounts->paypal_owner; ?>" style="width: 475px;"/>
    </p>

    <p>
        <label for="account-allowpp">Permite uso de PayPal: </label>
        <input type="checkbox" id="account-allowpp" name="allowpp" value="1"<?php if ($accounts->allowpp) echo ' checked="checked"'; ?>/>
    </p>

        <input type="submit" name="save-accounts" value="Guardar" />
        
    </form>
</div>