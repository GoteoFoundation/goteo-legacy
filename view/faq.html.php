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
    Goteo\Core\View;

$bodyClass = 'faq';

include 'view/prologue.html.php';

include 'view/header.html.php';

$go_up = Text::get('regular-go_up');

?>
		<div id="sub-header-secondary">
            <div class="clearfix">
                <h2>GOTEO<span class="red">FAQ</span></h2>
                <?php echo new View('view/header/share.html.php') ?>
            </div>
        </div>
        <div id="main" class="threecols">
			<div id="faq-content">
				<h2><?php echo Text::get('regular-faq') ?></h2>
				<?php foreach ($this['sections'] as $sectionId=>$sectionName) :
                    if (empty($this['faqs'][$sectionId])) continue;
                    ?>
					<div class="widget faq-content-module">
						<h3><?php echo $sectionName; ?></h3>
						<ol>
							<?php foreach ($this['faqs'][$sectionId] as $question)  : ?>
								<li>
									<a name="q<?php echo $question->id; ?>" />
									<h4 style="color:<?php echo $this['colors'][$sectionId] ?>;"><?php echo $question->title; ?></h4>
                                    <p><?php echo $question->description; ?></p>
									<a class="up" href="#"><?php echo $go_up; ?></a>
								</li>
							<?php endforeach; ?>
						</ol>
					</div>
				<?php endforeach; ?>
			</div>
			<div id="faq-sidebar">
				<?php foreach ($this['sections'] as $sectionId=>$sectionName) :
                    if (empty($this['faqs'][$sectionId])) continue;
                    ?>
					<div class="widget faq-sidebar-module">
						<h3 style="border-bottom-color: <?php echo $this['colors'][$sectionId] ?>;" class="supertitle"><?php echo $sectionName; ?></h3>
						<ol>
							<?php foreach ($this['faqs'][$sectionId] as $question)  : ?>
								<li><a style="color: <?php echo $this['colors'][$sectionId] ?>;" href="#q<?php echo $question->id; ?>"><?php echo $question->title; ?></a></li>
							<?php endforeach; ?>
						</ol>
					</div>
				<?php endforeach; ?>
				<div class="widget faq-sidebar-module">
					<h3 class="supertitle ask"><?php echo Text::get('regular-faq') ?></h3>
					<p class="ask-content"><?php echo Text::get('faq-ask-question'); ?></p>
					<a class="button green btn-ask" href="/contact"><?php echo Text::get('regular-ask'); ?></a>
				</div>
			</div>
        </div>        
	<?php include 'view/footer.html.php' ?>
<?php include 'view/epilogue.html.php' ?>