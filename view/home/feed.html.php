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

use Goteo\Library\Feed,
    Goteo\Library\Text;

$feed = $this['feed'];

?>
<div class="widget feed">
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $('.scroll-pane').jScrollPane({showArrows: true});
    });
    </script>
    <h3 class="title"><?php echo Text::get('feed-header'); ?></h3>

    <div style="height:auto;overflow:auto;margin-left:15px">

        <div class="block goteo">
           <h4><?php echo Text::get('feed-head-goteo'); ?></h4>
           <div class="item scroll-pane" style="height:350px;">
               <?php foreach ($feed['goteo'] as $item) :
                   echo Feed::subItem($item);
                endforeach; ?>
           </div>
        </div>

        <div class="block projects">
            <h4><?php echo Text::get('feed-head-projects'); ?></h4>
            <div class="item scroll-pane" style="height:350px;">
               <?php foreach ($feed['projects'] as $item) :
                   echo Feed::subItem($item);
                endforeach; ?>
           </div>
        </div>
        <div class="block community last">
            <h4><?php echo Text::get('feed-head-community'); ?></h4>
            <div class="item scroll-pane" style="height:350px;">
               <?php foreach ($feed['community'] as $item) :
                   echo Feed::subItem($item);
                endforeach; ?>
           </div>
        </div>
    </div>

    <div class="see_more"><a href="/community/activity"><?php echo Text::get('regular-see_more') ?></a></div>
</div>
