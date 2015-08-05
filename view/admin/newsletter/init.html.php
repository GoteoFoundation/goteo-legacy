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

use Goteo\Library\Text;

$mailing = $this['mailing'];

$link = SITE_URL.'/mail/'.base64_encode(md5(uniqid()).'¬any¬'.$mailing->mail).'/?email=any';

// mostrar enlace de si no ves y boton para activar
?>
<div class="widget">
    <p>La newsletter está lista para enviar con <a href="<?php echo $link; ?>" target="_blank">este contenido</a> a <?php echo $mailing->receivers ?> destinatarios.</p>
    <p>Si todo está bien pulsar el botón para activar los envíos automáticos.<br /> <a href="/admin/newsletter/activate/<?php echo $mailing->id; ?>" class="button" onclick="return confirm('Se comenzará a enviar automáticamente')">ACTIVAR!</a></p>

    <h3>Lista de destinatarios</h3>
    <table>
        <tr>
            <th>Email</th>
            <th>Alias</th>
            <th>Usuario</th>
        </tr>
        <?php foreach ($this['receivers'] as $user) : ?>
        <tr>
            <?php echo "<td>$user->email</td><td>$user->name</td><td>$user->user</td>" ?>
        </tr>
        <?php endforeach; ?>
    </table>
</div>