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
    Goteo\Library\Template;

//$templates = Template::getAllMini();
$templates = array(
    '11' => 'Base',
    '27' => 'Aviso a los talleristas'
);
// lista de destinatarios segun filtros recibidos, todos marcados por defecto
?>
<script type="text/javascript">
jQuery(document).ready(function ($) {

    $('#template_load').click(function () {
       if (confirm('El asunto y el contenido actual se substiruira por el que hay en la plantilla. Seguimos?')) {

           if ($('#template').val() == '0') {
            $('#mail_subject').val('');
            $('#mail_content').html('');
           }
            content = $.ajax({async: false, url: '<?php echo SITE_URL; ?>/ws/get_template_content/'+$('#template').val()}).responseText;
            var arr = content.split('#$#$#');
            $('#mail_subject').val(arr[0]);
            $('#mail_content').val(arr[1]);
        }
    });

});
</script>
<div class="widget">
    <p>Las siguientes variables se sustituir&aacute;n en el contenido:</p>
    <ul>
        <li><strong>%USERID%</strong> Para el id de acceso del destinatario</li>
        <li><strong>%USEREMAIL%</strong> Para el email del destinatario</li>
        <li><strong>%USERNAME%</strong> Para el nombre del destinatario</li>
        <li><strong>%SITEURL%</strong> Para la url de esta plataforma (<?php echo SITE_URL ?>)</li>
        <?php if ($this['filters']['type'] == 'owner' || $this['filters']['type'] == 'investor') : ?>
            <li><strong>%PROJECTID%</strong> Para el id del proyecto</li>
            <li><strong>%PROJECTNAME%</strong> Para el nombre del proyecto</li>
            <li><strong>%PROJECTURL%</strong> Para la url del proyecto</li>
        <?php endif; ?>
    </ul>
</div>
<div class="widget">
    <p><?php echo 'Vamos a comunicarnos con ' . $_SESSION['mailing']['filters_txt']; ?></p>
    <form action="/admin/mailing/send" method="post">
    <dl>
        <dt>Seleccionar plantilla:</dt>
        <dd>
            <select id="template" name="template" >
                <option value="0">Sin plantilla</option>
            <?php foreach ($templates as $templateId=>$templateName) : ?>
                <option value="<?php echo $templateId; ?>"><?php echo $templateName; ?></option>
            <?php endforeach; ?>
            </select>
            <input type="button" id="template_load" value="Cargar" />
        </dd>
    </dl>
    <dl>
        <dt>Asunto:</dt>
        <dd>
            <input id="mail_subject" name="subject" value="<?php echo $_SESSION['mailing']['subject']?>" style="width:500px;"/>
        </dd>
    </dl>
    <dl>
        <dt>Contenido: (en c&oacute;digo html; los saltos de linea deben ser con &lt;br /&gt;)</dt>
        <dd>
            <textarea id="mail_content" name="content" cols="100" rows="10"></textarea>
        </dd>
    </dl>
    <dl>
        <dt>Lista destinatarios:</dt>
        <dd>
            <ul>
                <?php foreach ($_SESSION['mailing']['receivers'] as $usrid=>$usr) : ?>
                <li>
                    <input type="checkbox"
                           name="receiver_<?php echo $usr->id; ?>"
                           id="receiver_<?php echo $usr->id; ?>"
                           value="1"
                           checked="checked" />
                    <label for="receiver_<?php echo $usr->id; ?>"><?php echo $usr->name.' ['.$usr->email.']'; if (!empty($usr->project)) echo ' Proyecto: <strong>'.$usr->project.'</strong>'; ?></label>
                </li>
                <?php endforeach; ?>
            </ul>
        </dd>
    </dl>

    <input type="submit" name="send" value="Enviar"  onclick="return confirm('Has revisado el contenido y comprobado los destinatarios?');"/>

    </form>
</div>