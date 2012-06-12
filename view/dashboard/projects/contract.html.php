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
    Goteo\Model\Project\Account;

$project = $this['project'];

if (!$project instanceof  Goteo\Model\Project) {
    return;
}

$accounts = Account::get($project->id);

?>
<!--
<div class="widget projects">
    <h2 class="title">Acuerdo</h2>
</div>
-->

<div class="widget projects">
    <h2 class="title">Cuentas bancarias del proyecto</h2>
<form method="post" action="/dashboard/projects/contract/save" >
    <input type="hidden" name="project" value="<?php echo $project->id; ?>" />
<p>
    <label for="bank-account">Cuenta bancaria:</label><br />
    <input type="text" id="bank-account" name="bank" value="<?php echo $accounts->bank; ?>" style="width:350px;" />
</p>

<p>
    <label for="paypal-account">Cuenta PayPal:</label><br />
    <input type="text" id="paypal-account" name="paypal" value="<?php echo $accounts->paypal; ?>" style="width:350px;" />
</p>

<input type="submit" name="save" value="<?php echo Text::get('form-apply-button') ?>" />
</form>
</div>
