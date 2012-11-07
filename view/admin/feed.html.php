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
    Goteo\Core\ACL,
    Goteo\Library\Feed;

$bodyClass = 'admin';

$feed = empty($_GET['feed']) ? 'all' : $_GET['feed'];

$items = Feed::getAll($feed, 'admin');

include 'view/prologue.html.php';

    include 'view/header.html.php'; ?>

        <div id="sub-header">
            <div>
                <h2><?= _("Panel principal de administración"); ?></h2>
                <?php if (defined('ADMIN_BCPATH')) : ?>
                <blockquote><?php echo ADMIN_BCPATH; ?></blockquote>
                <?php endif; ?>
            </div>
        </div>

        <div id="main">

            <div class="widget feed">
                <script type="text/javascript">
                    jQuery(document).ready(function($) {
                        $('.scroll-pane').jScrollPane({showArrows: true});

                        $('.hov').hover(
                          function () {
                            $(this).addClass($(this).attr('rel'));
                          },
                          function () {
                            $(this).removeClass($(this).attr('rel'));
                          }
                        );

                    });
                    </script>
                    <h3 class="title">actividad reciente</h3>
                    Ver Feeds por:

                    <p class="categories">
                        <?php foreach (Feed::$admin_types as $id=>$cat) : ?>
                        <a href="/admin/feed/?feed=<?php echo $id ?>" <?php echo ($feed == $id) ? 'class="'.$cat['color'].'"': 'class="hov" rel="'.$cat['color'].'"' ?>><?php echo $cat['label'] ?></a>
                        <?php endforeach; ?>
                    </p>

                    <?php echo new View('view/admin/feed/list.html.php', array('items' => $items)); ?>

            </div>

        </div>

<?php
    include 'view/footer.html.php';
include 'view/epilogue.html.php';
