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
    Goteo\Library\Lang;

$project = $this['project'];
$langs = Lang::getAll();

$filters = $this['filters'];

//arrastramos los filtros
$filter = "?owner={$filters['owner']}&translator={$filters['translator']}";

?>
<div class="widget">
<?php if ($this['action'] == 'edit') : ?>
    <h3 class="title">Traductores para el proyecto <?php echo $project->name ?></h3>
        <!-- asignar -->
        <table>
            <tr>
                <th>Traductor</th>
                <th></th>
            </tr>
            <?php foreach ($project->translators as $userId=>$userName) : ?>
            <tr>
                <td><?php if ($userId == $project->owner) echo '(AUTOR) '; ?><?php echo $userName; ?></td>
                <td><a href="/admin/translates/unassign/<?php echo $project->id; ?>/<?php echo $filter; ?>&user=<?php echo $userId; ?>">[Desasignar]</a></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <form id="form-assign" action="/admin/translates/assign/<?php echo $project->id; ?>/<?php echo $filter; ?>" method="get">
                <td colspan="2">
                    <select name="user">
                        <option value="">Selecciona otro traductor</option>
                        <?php foreach ($this['translators'] as $user) :
                            if (in_array($user->id, array_keys($project->translators))) continue;
                            ?>
                        <option value="<?php echo $user->id; ?>"><?php if ($user->id == $project->owner) echo '(AUTOR) '; ?><?php echo $user->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td><a href="#" onclick="document.getElementById('form-assign').submit(); return false;">[Asignar]</a></td>
                </form>
            </tr>
        </table>
        <hr />
        <a href="/admin/translates/close/<?php echo $project->id; ?>" class="button red" onclick="return confirm('Seguro que deseas dar por finalizada esta traducción?')">Cerrar la traducción</a>&nbsp;&nbsp;&nbsp;
        <a href="/admin/translates/send/<?php echo $project->id; ?>" class="button green" onclick="return confirm('Se va a enviar un email?')">Avisar al autor</a>
        <hr />
<?php endif; ?>

    <form method="post" action="/admin/translates/<?php echo $this['action']; ?>/<?php echo $project->id; ?>/?filter=<?php echo $this['filter']; ?>">

        <table>
            <tr>
                <td><?php if ($this['action'] == 'add') : ?>
                    <label for="add-proj">Proyecto que habilitamos</label><br />
                    <select id="add-proj" name="project">
                        <option value="">Selecciona el proyecto</option>
                        <?php foreach ($this['availables'] as $proj) : ?>
                            <option value="<?php echo $proj->id; ?>"<?php if ($_GET['project'] == $proj->id) echo ' selected="selected"';?>><?php echo $proj->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php else : ?>
                    <input type="hidden" name="project" value="<?php echo $project->id; ?>" />
                <?php endif; ?></td>
                <td><!-- Idioma original -->
                    <label for="orig-lang">Idioma original del proyecto</label><br />
                    <select id="orig-lang" name="lang">
                        <?php foreach ($langs as $item) : ?>
                            <option value="<?php echo $item->id; ?>"<?php if ($project->lang == $item->id || (empty($project->lang) && $item->id == 'es' )) echo ' selected="selected"';?>><?php echo $item->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        </table>


       <input type="submit" name="save" value="Guardar" />
    </form>
</div>