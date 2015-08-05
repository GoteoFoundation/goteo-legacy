<?php
/*
 *  Copyright (C) 2012 Platoniq y Fundación Goteo (see README for details)
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
    Goteo\Core\View;

$current = $this['current'];

$bodyClass = 'faq';

include 'view/prologue.html.php';
include 'view/header.html.php';

$go_up = Text::get('regular-go_up');

?>
<script type="text/javascript">
    $(function(){
        $(".faq-question").click(function (event) {
            event.preventDefault();

            if ($($(this).attr('href')).is(":visible")) {
                $($(this).attr('href')).hide();
            } else {
                $($(this).attr('href')).show();
            }

        });

        var hash = document.location.hash;
        if (hash != '') {
            $(hash).show();
        }
    });


</script>
//@NODESYS
        <div id="main">
			<div id="faq-content">
				<div class="faq-page-title"><?php echo Text::get('regular-faq') ?>
                    <span class="line"></span>
                </div>
                
                <div class="goask"><?php echo Text::get('faq-ask-question'); ?></div>
                <div class="goask-button"><a class="button green" href="/contact"><?php echo Text::get('regular-ask'); ?></a></div>

                <br clear="both" />

                <ul id="faq-sections">
                <?php foreach ($this['sections'] as $sectionId=>$sectionName) : ?>
                    <li><a href="/faq/<?php echo ($sectionId == 'node') ? '' : $sectionId; ?>"<?php if ($sectionId == $current) echo ' class="current"'; ?> style="color: <?php echo $this['colors'][$sectionId] ?>;"><?php echo preg_replace('/\s/', '<br />', $sectionName, 1); ?></a></li>
                <?php endforeach; ?>
                </ul>

                <br clear="both" />

                <h3 style="color: <?php echo $this['colors'][$current] ?>;" ><?php echo $this['sections'][$current]; ?></h3>
                <ol>
                    <?php foreach ($this['faqs'][$current] as $question)  : 
                        if (empty($question->title)) continue;
                        ?>
                        <li>
                            <h4><a href="#q<?php echo $question->id; ?>" class="faq-question" style="color:<?php echo $this['colors'][$current] ?>;"><?php echo $question->title; ?></a></h4>
                            <div id="q<?php echo $question->id; ?>" style="<?php echo ($this['show'] == $question->id) ? 'display:block;' : 'display:none;' ?>"><?php echo $question->description; ?></div>
                        </li>
                    <?php endforeach; ?>
                </ol>

                <a class="up" href="#"><?php echo $go_up; ?></a>

			</div>
        </div>
	<?php include 'view/footer.html.php' ?>
<?php include 'view/epilogue.html.php' ?>