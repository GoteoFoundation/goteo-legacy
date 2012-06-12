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
    Goteo\Core\View,
    Goteo\Model\Criteria;

$bodyClass = 'review';

$review     = $this['review'];
$evaluation = $this['evaluation'];

$sections = Criteria::sections();
$criteria = array();
foreach ($sections as $sectionId=>$sectionName) {
    $criteria[$sectionId] = Criteria::getAll($sectionId);
}


include 'view/prologue.html.php';
include 'view/header.html.php'; ?>

        <div id="sub-header">
            <div>
                <h2>Informe de revisión del proyecto '<?php echo $review->name; ?>' de <?php echo $review->owner_name; ?></h2>
                La edición del proyecto alcanzó el <strong><?php echo $review->progress; ?>%</strong> y la puntuación promedio de la revisión: <span id="total-score"><?php echo $review->score . '/' . $review->max; ?></span>
            </div>
        </div>

        <div id="main">
            <?php foreach ($sections as $sectionId=>$sectionName) : ?>
            <div class="widget">
                <h2 class="title"><?php echo $sectionName; ?></h2>
                <?php foreach ($review->checkers as $user=>$user_data) : ?>
                <p>
                    <strong><?php echo $user_data->name ?></strong> otorga puntos porque:<br />
                    <blockquote>
                    <?php foreach ($criteria[$sectionId] as $crit) :
                        if ($evaluation[$user]['criteria'][$crit->id] > 0) echo '· ' . $crit->title . '<br />';
                    endforeach; ?>
                    </blockquote>
                </p>
                <?php endforeach; ?>
                <?php foreach ($review->checkers as $user=>$user_data) : ?>
                <p>
                    <strong><?php echo $user_data->name ?></strong> evalua <?php echo strtolower($sectionName); ?>:<br />
                    <blockquote><?php echo $evaluation[$user][$sectionId]['evaluation']; ?></blockquote>
                </p>
                <p>
                    <strong><?php echo $user_data->name ?></strong> propone <?php echo strtolower($sectionName); ?>:<br />
                    <blockquote><?php echo $evaluation[$user][$sectionId]['recommendation']; ?></blockquote>
                </p>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
        </div>
<?php
include 'view/footer.html.php';
include 'view/epilogue.html.php';