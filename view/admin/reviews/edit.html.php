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

use Goteo\Library\Text;

$project = $this['project'];
$review  = $this['review'];
?>
<div class="widget board">
    <p>
        Comentario de <?php echo $project->user->name; ?>:<br />
        <?php echo $project->comment; ?>
    </p>

    <form method="post" action="/admin/reviews/<?php echo $this['action']; ?>/<?php echo $project->id; ?>/?filter=<?php echo $this['filter']; ?>">

        <input type="hidden" name="id" value="<?php echo $review->id; ?>" />
        <input type="hidden" name="project" value="<?php echo $project->id; ?>" />

        <p>
            <label for="review-to_checker">Comentario para el revisor:</label><br />
            <textarea name="to_checker" id="review-to_checker" cols="60" rows="10"><?php echo $review->to_checker; ?></textarea>
        </p>

        <p>
            <label for="review-to_owner">Comentario para el productor:</label><br />
            <textarea name="to_owner" id="review-to_owner" cols="60" rows="10"><?php echo $review->to_owner; ?></textarea>
        </p>

       <input type="submit" name="save" value="Guardar" />
    </form>
</div>