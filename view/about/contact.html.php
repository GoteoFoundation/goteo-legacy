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


use Goteo\Library\Page,
    Goteo\Library\Text;

$bodyClass = 'about';

$page = Page::get('contact');

include 'view/prologue.html.php';
include 'view/header.html.php';
?>
    <div id="sub-header">
        <div>
            <h2><?php echo $page->description; ?></h2>
        </div>
    </div>

    <div id="main">

        <div class="widget">
            <h3 class="title"><?php echo $page->name; ?></h3>
            <?php echo $page->content; ?>

            <?php if (!empty($this['errors']) || !empty($this['message'])) : ?>
                <p>
                    <?php echo implode(', ', $this['errors']); ?>
                    <?php echo $this['message']; ?>
                </p>
            <?php endif; ?>

        </div>

        <div class="widget contact-message">

            <h3 class="title"><?php echo Text::get('contact-send_message-header'); ?></h3>

            <form method="post" action="/contact">
                <div class="field">
                    <label for="email"><?php echo Text::get('contact-email-field'); ?></label><br />
                    <input type="text" id="email" name="email" value="<?php echo $this['data']['email'] ?>"/>
                </div>

                <div class="field">
                    <label for="subject"><?php echo Text::get('contact-subject-field'); ?></label><br />
                    <input type="text" id="subject" name="subject" value="<?php echo $this['data']['subject'] ?>"/>
                </div>

                <div class="field">
                    <label for="message"><?php echo Text::get('contact-message-field'); ?></label><br />
                    <textarea id="message" name="message" cols="50" rows="5"><?php echo $this['data']['message'] ?></textarea>
                </div>

                <button class="green" type="submit" name="send"><?php echo Text::get('contact-send_message-button'); ?></button>
            </form>

        </div>

    </div>

<?php include 'view/footer.html.php' ?>

<?php include 'view/epilogue.html.php' ?>