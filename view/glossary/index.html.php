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

$posts = $this['posts'];
$index = $this['index'];

$letters = array();

$bodyClass = 'glossary';

$go_up = Text::get('regular-go_up');

// paginacion
require_once 'library/pagination/pagination.php';

$pagedResults = new \Paginated($posts, $this['tpp'], isset($_GET['page']) ? $_GET['page'] : 1);

include 'view/prologue.html.php';
include 'view/header.html.php'; 
?>
	<div id="sub-header-secondary">
		<div class="clearfix">
            <h2><a href="/glossary">GOTEO<span class="red"><?php echo Text::get('footer-resources-glossary');?></span></a></h2>
            <?php echo new View('view/header/share.html.php') ?>
		</div>
	</div>

	<div id="main" class="threecols">
		<div id="glossary-content">
            <h3 class="title"><?php echo Text::get('regular-header-glossary'); ?></h3>
            <?php if (!empty($posts)) : ?>
                <div class="glossary-page">
                <?php while ($post = $pagedResults->fetchPagedRow()) : ?>
                    <?php
                        $leter = $post->title[0];

                        if (!in_array($leter, $letters)) :
                            $letters[] = $leter;
                        ?>
                        <h4 class="supertitle"><?php echo $post->title[0]; ?></h4>
                    <?php endif; ?>
                    <div class="post">
                        <?php if (count($post->gallery) > 1) : ?>
                        <script type="text/javascript" >
                            $(function(){
                                $('#post-gallery<?php echo $post->id ?>').slides({
                                    container: 'post-gallery-container',
                                    paginationClass: 'slderpag',
                                    generatePagination: false,
                                    play: 0
                                });
                            });
                        </script>
                        <?php endif; ?>
                        <a name="term<?php echo $post->id  ?>"></a>
                        <h5 class="aqua"><?php echo $post->title; ?></h5>
                        <p><?php echo $post->text; ?></p>
                        <?php if (!empty($post->media->url)) :
                                $embed = $post->media->getEmbedCode();
                                if (!empty($embed))  : ?>
                            <div class="embed"><?php echo $embed; ?></div>
                        <?php endif; endif; ?>
                        <?php if (!empty($post->legend)) : ?>
                            <div class="embed-legend">
                                <?php echo $post->legend; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (count($post->gallery) > 1) : ?>
                        <div id="post-gallery<?php echo $post->id ?>" class="post-gallery">
                            <div class="post-gallery-container">
                                <?php $i = 1; foreach ($post->gallery as $image) : ?>
                                <div class="gallery-image gallery-post<?php echo $post->id ?>" id="gallery-post<?php echo $post->id ?>-<?php echo $i ?>">
                                    <img src="<?php echo $image->getLink(500, 285); ?>" alt="<?php echo $post->title; ?>" />
                                </div>
                                <?php $i++; endforeach; ?>
                            </div>
                            <!-- carrusel de imagenes si hay mas de una -->
                                <a class="prev">prev</a>
                                    <ul class="slderpag">
                                        <?php $i = 1; foreach ($post->gallery as $image) : ?>
                                        <li><a href="#" id="navi-gallery-post<?php echo $post->id ?>-<?php echo $i ?>" rel="gallery-post<?php echo $post->id ?>-<?php echo $i ?>" class="navi-gallery-post<?php echo $post->id ?>">
                                    <?php echo htmlspecialchars($image->name) ?></a>
                                        </li>
                                        <?php $i++; endforeach ?>
                                    </ul>
                                <a class="next">next</a>
                            <!-- carrusel de imagenes -->
                        </div>
                        <?php elseif (!empty($post->image)) : ?>
                            <div class="gallery-image gallery-post<?php echo $post->id ?>" id="gallery-post<?php echo $post->id ?>-<?php echo $i ?>">
                                <img src="<?php echo $post->image->getLink(500, 285); ?>" alt="<?php echo $post->title; ?>" />
                            </div>
                        <?php endif; ?>
                    </div>
                    <a class="up" href="#"><?php echo $go_up; ?></a>
                <?php endwhile; ?>
                </div>
                <ul id="pagination">
                    <?php   $pagedResults->setLayout(new DoubleBarLayout());
                            echo $pagedResults->fetchPagedNavigation(); ?>
                </ul>
            <?php endif; ?>
		</div>
		<div id="glossary-sidebar">
            <div class="widget glossary-sidebar-module">
                <?php foreach ($index as $leter=>$list) : ?>
                <h3 class="supertitle activable glossary-letter"><?php echo $leter; ?></h3>
                <ul class="glossary-letter-list">
                    <?php foreach ($list as $item) : ?>
                    <li><a href="<?php echo $item->url; ?>"><?php echo $item->title; ?></a></li>
                    <?php endforeach; ?>
                </ul>
                <?php endforeach; ?>
            </div>
		</div>

	</div>
<?php
    include 'view/footer.html.php';
	include 'view/epilogue.html.php';
