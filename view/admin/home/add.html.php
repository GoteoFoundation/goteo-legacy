<?php
/*
 *  Copyright (C) 2012 Platoniq y Fundación Goteo (see README for details)
 *  This file is part of Goteo.
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
    Goteo\Model\Home;

$home = $this['home'];
$availables = $this['availables'];
?>
<form method="post" action="/admin/home" >
    <input type="hidden" name="action" value="<?php echo $this['action'] ?>" />
    <input type="hidden" name="type" value="<?php echo $home->type ?>" />
    <input type="hidden" name="order" value="<?php echo $home->order ?>" />

<p>
    <label for="home-item">Elemento:</label><br />
    <select id="home-item" name="item">
    <?php foreach ($availables as $item=>$name) : ?>
        <option value="<?php echo $item; ?>"><?php echo $name; ?></option>
    <?php endforeach; ?>
    </select>
</p>

    <input type="submit" name="save" value="A&ntilde;adir" />
</form>
