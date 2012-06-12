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

use Goteo\Core\View;

echo new View ('view/review/reviews/selector.html.php', $this);

$review = $this['review'];

?>

<div class="widget">
    <p>El proyecto <strong><?php echo $review->name; ?></strong> de <strong><?php echo $review->owner_name; ?></strong></p>
    <p>La edición del proyecto alcanzó el <strong><?php echo $review->progress; ?>%</strong>, la puntuación actual de la revisión es de <strong><?php echo $review->score; ?>/<?php echo $review->max; ?></strong></p>
    <p><a href="<?php echo SITE_URL . '/project/' . $review->project; ?>" target="_blank">Abrir el proyecto</a><br /><a href="<?php echo SITE_URL . '/user/' . $review->owner; ?>" target="_blank">Abrir el perfil del creador</a></p>
</div>

<div class="widget">
    Comentario del administrador:<br />
    <blockquote><?php echo $review->comment; ?></blockquote>
</div>

<div class="widget">
    <p>Tu revisión está <?php echo $review->ready == 1 ? 'Lista' : 'Pendiente'; ?><?php if ($review->ready != 1) : ?> Puedes completarla en <a href="/review/reviews/evaluate/open/<?php echo $review->id; ?>">tus revisiones</a><?php endif; ?></p>
    <?php
    if ($review->ready == 0) {
        echo '<a href="/review/reviews/summary/ready/' . $review->id . '">[Dar por terminada mi revisión]</a>';
    } else {
        echo 'Continúa con otra o espera instrucciones.';
    }
    ?>
</div>