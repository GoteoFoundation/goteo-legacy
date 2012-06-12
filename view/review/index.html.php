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

$bodyClass = 'review';

$user = $_SESSION['user'];

include 'view/prologue.html.php';
include 'view/header.html.php';

/*
 *
 <img src="<?php echo $user->avatar->getLink(75, 75, true); ?>" /><br />
                    <em><?php echo $user->name; ?></em>
 *
 */

?>

        <div id="sub-header">
            <div>
                <h2>
                    <?php echo 'Mi panel de revisor / ' . $this['menu'][$this['section']]['label']; ?></h2>
            </div>
        </div>

        <?php  echo new View ('view/review/menu.html.php', $this) ?>

<?php if(isset($_SESSION['messages'])) { include 'view/header/message.html.php'; } ?>

        <div id="main">


            <?php if (!empty($this['message'])) : ?>
                <div class="widget">
                    <?php if (empty($this['section']) && empty($this['option'])) : ?>
                        <h2 class="title">Bienvenid@</h2>
                    <?php endif; ?>
                    <p><?php echo $this['message']; ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($this['errors'])) {
                echo implode(',',$this['errors']);
            } ?>

            <?php if (!empty($this['success'])) {
                echo implode(',',$this['success']);
            } ?>

            <?php if (!empty($this['section']) && !empty($this['option'])) {
                echo new View ('view/review/'.$this['section'].'/'.$this['option'].'.html.php', $this);
            } ?>

        </div>
<?php
include 'view/footer.html.php';
include 'view/epilogue.html.php';
