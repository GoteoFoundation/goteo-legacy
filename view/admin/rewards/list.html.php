<?php
use Goteo\Library\Text,
    Goteo\Model\Invest;

$filters = $this['filters'];

$emails = Invest::emails(true);
?>
<div class="widget board">
    <h3 class="title">Filtros</h3>
    <form id="filter-form" action="/admin/rewards" method="get">
        <div style="float:left;margin:5px;">
            <label for="projects-filter">Proyecto</label><br />
            <select id="projects-filter" name="project" onchange="document.getElementById('filter-form').submit();">
                <option value="">Todos los proyectos</option>
            <?php foreach ($this['projects'] as $itemId=>$itemName) : ?>
                <option value="<?php echo $itemId; ?>"<?php if ($filters['project'] === (string) $itemId) echo ' selected="selected"';?>><?php echo $itemName; ?></option>
            <?php endforeach; ?>
            </select>
        </div>
        
        <div style="float:left;margin:5px;">
            <label for="name-filter">Alias/Email del usuario:</label><br />
            <input type="text" id ="name-filter" name="name" value ="<?php echo $filters['name']?>" />
        </div>

        <div style="float:left;margin:5px;">
            <label for="status-filter">Mostrar por estado de recompensa:</label><br />
            <select id="status-filter" name="status" >
                <option value="">Todos</option>
            <?php foreach ($this['status'] as $statusId=>$statusName) : ?>
                <option value="<?php echo $statusId; ?>"<?php if ($filters['status'] == $statusId) echo ' selected="selected"';?>><?php echo $statusName; ?></option>
            <?php endforeach; ?>
            </select>
        </div>

        <br clear="both" />

        <div style="float:left;margin:5px;">
            <input type="submit" value="filtrar" />
        </div>
    </form>
    <br clear="both" />
    <a href="/admin/rewards/?reset=filters">Quitar filtros</a>
</div>

<div class="widget board">
<?php if ($filters['filtered'] != 'yes') : ?>
    <p>Es necesario poner algun filtro, hay demasiados registros!</p>
<?php elseif (!empty($this['list'])) : ?>
    <table width="100%">
        <thead>
            <tr>
                <th></th>
                <th>Cofinanciador</th>
                <th>Proyecto</th>
                <th>Recompensa</th>
                <th>Estado</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($this['list'] as $reward) : ?>
            <tr>
                <td><a href="/admin/rewards/edit/<?php echo $reward->invest ?>" >[Modificar]</a></td>
                <td><a href="/admin/users/manage/<?php echo $reward->user ?>" target="_blank" title="<?php echo $reward->name; ?>"><?php echo $reward->email; ?></a></td>
                <td><a href="/admin/projects/?name=<?php echo $this['projects'][$reward->project] ?>" target="_blank"><?php echo Text::recorta($this['projects'][$reward->project], 20); if (!empty($invest->campaign)) echo '<br />('.$this['calls'][$invest->campaign].')'; ?></a></td>
                <td><?php echo $reward->reward_name ?></td>
                <?php if (!$reward->fulfilled) : ?>
                    <td style="color: red;" >Pendiente</td>
                    <td><a href="<?php echo "/admin/rewards/fulfill/{$reward->invest}"; ?>">[Marcar cumplida]</a></td>
                <?php else : ?>
                    <td style="color: green;" >Cumplido</td>
                    <td><a href="<?php echo "/admin/rewards/unfill/{$reward->invest}"; ?>">[Marcar pendiente]</a></td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
<?php else : ?>
    <p>No hay aportes que cumplan con los filtros.</p>
<?php endif;?>
</div>