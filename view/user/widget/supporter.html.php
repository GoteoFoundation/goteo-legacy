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
    Goteo\Library\Text,
    Goteo\Library\Worth;

$user = $this['user'];

$worthcracy = Worth::getAll();
?>
<div class="supporterContainer">
	<?php if ($user->user != 'anonymous') { ?>
	<a class="expand" href="/user/<?php echo htmlspecialchars($user->user) ?>">&nbsp;</a>
	<?php } ?>
	<div class="supporter">
		<span class="avatar"><img src="<?php echo $user->avatar->getLink(43, 43, true); ?>" /></span>
	    <?php if ($user->user != 'anonymous') : ?>
	    <h4><?php echo $user->name; ?></h4>
	    <?php else : ?>
	    <h4 class="aqua"><?php echo Text::recorta($user->name,40); ?></h4>
	    <?php endif; ?>
	    <dl>
	        <?php  if (isset($user->projects))  : ?>
	        <dt class="projects"><?php echo Text::get('profile-invest_on-title'); ?></dt>
	        <dd class="projects"><strong><?php echo $user->projects ?></strong> <?php echo Text::get('regular-projects'); ?></dd>
	        <?php endif; ?>
	
	        <dt class="worthcracy"><?php echo Text::get('profile-worthcracy-title'); ?></dt>
	        <dd class="worthcracy">
	            <?php if (isset($user->worth)) echo new View('view/worth/base.html.php', array('worthcracy' => $worthcracy, 'level' => $user->worth)) ?>
	        </dd>
	
	        <dt class="amount"><?php echo Text::get('profile-worth-title'); ?></dt>
	        <dd class="amount"><strong><?php echo \amount_format($user->amount) ?></strong> <span class="euro">&euro;</span></dd>
	
	        <dt class="date"><?php echo Text::get('profile-last_worth-title'); ?></dt>
	        <dd class="date"><?php echo $user->date; ?></dd>
	    </dl>
	</div>
</div>
