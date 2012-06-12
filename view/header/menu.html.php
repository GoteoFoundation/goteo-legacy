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

 use Goteo\Core\ACL,
    Goteo\Library\Text;
?>
    <div id="menu">
        
        <h2><?php echo Text::get('regular-menu'); ?></h2>
        
        <ul>
            <li class="home"><a href="/"><?php echo Text::get('regular-home'); ?></a></li>
            <li class="explore"><a class="button red" href="/discover"><?php echo Text::get('regular-discover'); ?></a></li>
            <li class="create"><a class="button aqua" href="/project/create"><?php echo Text::get('regular-create'); ?></a></li>
            <li class="search">
                <form method="get" action="/discover/results">
                    <fieldset>
                        <legend><?php echo Text::get('regular-search'); ?></legend>
                        <input type="text" name="query"  />
                        <input type="submit" value="Buscar" >
                    </fieldset>
                </form>
            </li>
            <?php if (!empty($_SESSION['user'])): ?>
            <li class="community"><a href="/community"><span><?php echo Text::get('community-menu-main'); ?></span></a>
                <div>
                    <ul>
                        <li><a href="/community/activity"><span><?php echo Text::get('community-menu-activity'); ?></span></a></li>
                        <li><a href="/community/sharemates"><span><?php echo Text::get('community-menu-sharemates'); ?></span></a></li>
                    </ul>
                </div>
            </li>
            <?php else: ?>
            <li class="login">
                <a href="/community"><span><?php echo Text::get('community-menu-main'); ?></span></a>
            </li>
            <?php endif ?>

            <?php if (!empty($_SESSION['user'])): ?>            
            <li class="dashboard"><a href="/dashboard"><span><?php echo Text::get('dashboard-menu-main'); ?></span><img src="<?php echo $_SESSION['user']->avatar->getLink(28, 28, true); ?>" /></a>
                <div>
                    <ul>
                        <li><a href="/dashboard/activity"><span><?php echo Text::get('dashboard-menu-activity'); ?></span></a></li>
                        <li><a href="/dashboard/profile"><span><?php echo Text::get('dashboard-menu-profile'); ?></span></a></li>
                        <li><a href="/dashboard/projects"><span><?php echo Text::get('dashboard-menu-projects'); ?></span></a></li>
                        <?php if (ACL::check('/translate')) : ?>
                        <li><a href="/translate"><span><?php echo Text::get('regular-translate_board'); ?></span></a></li>
                        <?php endif; ?>
                        <?php if (ACL::check('/review')) : ?>
                        <li><a href="/review"><span><?php echo Text::get('regular-review_board'); ?></span></a></li>
                        <?php endif; ?>
                        <?php if (ACL::check('/admin')) : ?>
                        <li><a href="/admin"><span><?php echo Text::get('regular-admin_board'); ?></span></a></li>
                        <?php endif; ?>
                        <li class="logout"><a href="/user/logout"><span><?php echo Text::get('regular-logout'); ?></span></a></li>
                    </ul>
                </div>
            </li>            
            <?php else: ?>            
            <li class="login">
                <a href="/user/login"><?php echo Text::get('regular-login'); ?></a>
            </li>
            
            <?php endif ?>
        </ul>
    </div>