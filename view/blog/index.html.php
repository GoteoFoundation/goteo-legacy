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
    Goteo\Model\Blog\Post;

$blog = $this['blog'];
$posts = $blog->posts;
$tag = $this['tag'];
if (!empty($this['post'])) {
    $post = Post::get($this['post'], LANG);
}
$bodyClass = 'blog';

// paginacion
require_once 'library/pagination/pagination.php';

//recolocamos los post para la paginacion
$the_posts = array();
foreach ($posts as $i=>$p) {
    $the_posts[] = $p;
}

$pagedResults = new \Paginated($the_posts, 7, isset($_GET['page']) ? $_GET['page'] : 1);

include 'view/prologue.html.php';
include 'view/header.html.php'; 
?>
	<div id="sub-header-secondary">
		<div class="clearfix">
			<h2><a href="/blog">GOTEO<span class="red">BLOG</span></a></h2>
            <?php echo new View('view/header/share.html.php') ?>
		</div>
	</div>

<?php if(isset($_SESSION['messages'])) { include 'view/header/message.html.php'; } ?>

	<div id="main" class="threecols">
		<div id="blog-content">
			<?php if ($this['show'] == 'list') : ?>
				<?php if (!empty($posts)) : ?>
					<?php while ($post = $pagedResults->fetchPagedRow()) :

                            $share_title = $post->title;
                            $share_url = SITE_URL . '/blog/' . $post->id;
                            $facebook_url = 'http://facebook.com/sharer.php?u=' . rawurlencode($share_url . '&t=' . rawurlencode($share_title));
                            $twitter_url = 'http://twitter.com/home?status=' . rawurlencode($share_title . ': ' . $share_url . ' #Goteo');

                        ?>
						<div class="widget blog-content-module">
							<?php echo new View('view/blog/post.html.php', array('post'=>$post->id, 'show' => 'list')); ?>
                            <ul class="share-goteo">
                                <li class="sharetext"><?php echo Text::get('regular-share_this'); ?></li>
                                <li class="twitter"><a href="<?php echo htmlspecialchars($twitter_url) ?>" target="_blank"><?php echo Text::get('regular-twitter'); ?></a></li>
                                <li class="facebook"><a href="<?php echo htmlspecialchars($facebook_url) ?>" target="_blank"><?php echo Text::get('regular-facebook'); ?></a></li>
                            </ul>
                            <div class="comments-num"><a href="/blog/<?php echo $post->id; ?>"><?php echo $post->num_comments > 0 ? $post->num_comments . ' ' .Text::get('blog-comments') : Text::get('blog-no_comments'); ?></a></div>
						</div>
					<?php endwhile; ?>
                    <ul id="pagination">
                        <?php   $pagedResults->setLayout(new DoubleBarLayout());
                                echo $pagedResults->fetchPagedNavigation(); ?>
                    </ul>
				<?php else : ?>
					<p>No hay entradas</p>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ($this['show'] == 'post') : ?>
				<div class="widget post">
					<?php echo new View('view/blog/post.html.php', $this);
                        $share_title = $post->title;
                        $share_url = SITE_URL . '/blog/' . $post->id;
                        $facebook_url = 'http://facebook.com/sharer.php?u=' . rawurlencode($share_url . '&t=' . rawurlencode($share_title));
                        $twitter_url = 'http://twitter.com/home?status=' . rawurlencode($share_title . ': ' . $share_url . ' #Goteo');
                    ?>
					<ul class="share-goteo">
							<li class="sharetext"><?php echo Text::get('regular-share_this'); ?></li>
							<li class="twitter"><a href="<?php echo htmlspecialchars($twitter_url) ?>" target="_blank"><?php echo Text::get('regular-twitter'); ?></a></li>
							<li class="facebook"><a href="<?php echo htmlspecialchars($facebook_url) ?>" target="_blank"><?php echo Text::get('regular-facebook'); ?></a></li>
					</ul>
					<div class="comments-num"><a href="/blog/<?php echo $post->id; ?>"><?php echo $post->num_comments > 0 ? $post->num_comments . ' ' .Text::get('blog-comments') : Text::get('blog-no_comments'); ?></a></div>
				</div>
                <?php echo new View('view/blog/comments.html.php', $this) ?>
                <?php echo new View('view/blog/sendComment.html.php', $this) ?>
			<?php endif; ?>
		</div>
		<div id="blog-sidebar">
			<?php echo new View('view/blog/side.html.php', array('blog'=>$this['blog'], 'type'=>'posts')) ; ?>
			<?php echo new View('view/blog/side.html.php', array('blog'=>$this['blog'], 'type'=>'tags')) ; ?>
			<?php echo new View('view/blog/side.html.php', array('blog'=>$this['blog'], 'type'=>'comments')) ; ?>
		</div>

	</div>
<?php
    include 'view/footer.html.php';
	include 'view/epilogue.html.php';
