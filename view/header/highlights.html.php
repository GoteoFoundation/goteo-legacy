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
    Goteo\Model\News; 

$highlights = News::getAll(true);

$see_more = Text::get('regular-see_more');

?>
<div id="highlights">
    
    <h2><a href="/news"><?php echo Text::get('regular-news'); ?></a></h2>
    
    <ul>
        <?php foreach ($highlights as $i => $hl) : ?>
        <li><?php echo htmlspecialchars($hl->title) ?> <?php if (!empty ($hl->url)) : ?><a href="<?php echo $hl->url; ?>" target="_blank"><?php echo $see_more; ?></a><?php endif; ?></li>
        <?php endforeach ?>
    </ul>
    
    <script type="text/javascript">
        
    jQuery(document).ready(function ($) {
        
        var ul = $('#highlights > ul'),
            lis = ul.children('li'),
            li = lis.first(),
            stopped = false;
        
        setInterval(function () {            
            
            if (stopped) return;
            
            li = li.next();
            
            var m;
            
            if (!li.length) {
                li = lis.first();
                m = 0;
            } else {
                m = '-=' + Math.abs(li.position().top * -1);
            }
            
            ul.animate({
               'margin-top': m
            }, 400);
            
        }, 8000);
        
        ul.bind('mouseenter', function () {
            stopped = true;
            ul.bind('mouseleave', function () {
                stopped = false;
            });
        });
        
    });
    </script>
    
</div>