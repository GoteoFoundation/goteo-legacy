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
    Goteo\Model\Criteria;

echo new View ('view/review/reviews/selector.html.php', $this);

$review   = $this['review'];
$evaluation = $this['evaluation'];

$sections = Criteria::sections();
$criteria = array();
foreach ($sections as $sectionId=>$sectionName) {
    $criteria[$sectionId] = Criteria::getAll($sectionId);
}

?>
<div class="widget">
    Puntuación de tu revisión: <span id="total-score"><?php echo $evaluation['score'] . '/' . $evaluation['max']; ?></span>
</div>
<?php foreach ($sections as $sectionId=>$sectionName) : ?>
<div class="widget">
    <h2 class="title"><?php echo $sectionName; ?></h2>
    <p>
        Otrogas puntos porque:<br />
        <blockquote>
        <?php foreach ($criteria[$sectionId] as $crit) :
            if ($evaluation['criteria'][$crit->id] > 0) echo '· ' . $crit->title . '<br />';
        endforeach; ?>
        </blockquote>
    </p>
    <p>
        Tu evaluación <?php echo strtolower($sectionName); ?>:<br />
        <blockquote><?php echo nl2br($evaluation[$sectionId]['evaluation']); ?></blockquote>
    </p>
    <p>
        Las mejoras que harías <?php echo strtolower($sectionName); ?>:<br />
        <blockquote><?php echo nl2br($evaluation[$sectionId]['recommendation']); ?></blockquote>
    </p>
</div>
<?php endforeach; ?>