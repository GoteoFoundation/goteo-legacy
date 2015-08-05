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
    Goteo\Core\View,
    Goteo\Core\ACL,
    Goteo\Library\Feed,

    Goteo\Controller\Admin;
if (!isset($_SESSION['admin_menu'])) {
    $_SESSION['admin_menu'] = Admin::menu();
}

$bodyClass = 'admin';

// funcionalidades con autocomplete
$jsreq_autocomplete = $this['autocomplete'];


include 'view/prologue.html.php';
include 'view/header.html.php'; 
?>

        <div id="sub-header" style="margin-bottom: 10px;">
            <div class="breadcrumbs"><?php echo ADMIN_BCPATH; ?></div>
        </div>

<?php if(isset($_SESSION['messages'])) { include 'view/header/message.html.php'; } ?>

        <div id="main">

            <div class="admin-center">

            <div class="admin-menu">
                <?php foreach ($_SESSION['admin_menu'] as $sCode=>$section) : ?>
                <fieldset>
                    <legend><?php echo $section['label'] ?></legend>
                    <ul>
                    <?php foreach ($section['options'] as $oCode=>$option) :
                        echo '<li><a href="/admin/'.$oCode.'">'.$option['label'].'</a></li>';
                    endforeach; ?>
                    </ul>
                </fieldset>
                <?php endforeach; ?>
            </div>

            <?php if (isset($_SESSION['user']->roles['superadmin'])) : ?>
            <div class="widget board">
                <ul>
                    <li><a href="/admin/projects"><?php echo Text::_("Proyectos"); ?></a></li>
                    <li><a href="/admin/users"><?php echo Text::_("Usuarios"); ?></a></li>
                    <li><a href="/admin/accounts"><?php echo Text::_("Aportes"); ?></a></li>
                    <li><a href="/admin/texts"><?php echo Text::_("Textos"); ?></a></li>
                    <li><a href="/admin/tasks"><?php echo Text::_("Tareas"); ?></a></li>
                    <li><a href="/admin/newsletter"><?php echo Text::_("Mailings"); ?></a></li>
                </ul>
            </div>
            <?php endif; ?>


<?php if (!empty($this['folder']) && !empty($this['file'])) : 
        if ($this['folder'] == 'base') {
            $path = 'view/admin/'.$this['file'].'.html.php';
        } else {
            $path = 'view/admin/'.$this['folder'].'/'.$this['file'].'.html.php';
        }

            echo new View ($path, $this);
       else :
           
            /* PORTADA ADMIN */
            $node = isset($_SESSION['admin_node']) ? $_SESSION['admin_node'] : \GOTEO_NODE;

            $feed = empty($_GET['feed']) ? 'all' : $_GET['feed'];
    $items = Feed::getAll($feed, 'admin', 50);

        // Central pendientes
    ?>
        <div class="widget admin-home">
            <h3 class="title"><?php echo Text::_("Tareas pendientes"); ?></h3>
            <?php if (!empty($this['tasks'])) : ?>
            <table>
                <?php foreach ($this['tasks'] as $task) : ?>
                <tr>
                    <td><?php if (!empty($task->url)) { echo ' <a href="'.$task->url.'">[IR]</a>';} ?></td>
                    <td><?php echo $task->text; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php else : ?>
            <p><?php echo Text::_("No tienes tareas pendientes"); ?></p>
            <?php endif; ?>
        </div>
    <?php
        // Lateral de acctividad reciente
    ?>
            <div class="admin-side">
                <a name="feed"></a>
                <div class="widget feed">
					<script type="text/javascript">
                    jQuery(document).ready(function($) {
                        $('.scroll-pane').jScrollPane({showArrows: true});

                        $('.hov').hover(
                          function () {
                            $(this).addClass($(this).attr('rel'));
                          },
                          function () {
                            $(this).removeClass($(this).attr('rel'));
                          }
                        );

                    });
                    </script>
                    <h3><?php echo Text::_("actividad reciente"); ?></h3>
                    <?php echo Text::_("Ver Feeds por:"); ?>

                    <p class="categories">
                        <?php foreach (Feed::_admin_types() as $id=>$cat) : ?>
                        <a href="/admin/recent/?feed=<?php echo $id ?>#feed" <?php echo ($feed == $id) ? 'class="'.$cat['color'].'"': 'class="hov" rel="'.$cat['color'].'"' ?>><?php echo $cat['label'] ?></a>
                        <?php endforeach; ?>
                    </p>

                    <div class="scroll-pane">
                        <?php foreach ($items as $item) :
                            $odd = !$odd ? true : false;
                            ?>
                        <div class="subitem<?php if ($odd) echo ' odd';?>">
                           <span class="datepub"><?php echo Text::get('feed-timeago', $item->timeago); ?></span>
                           <div class="content-pub"><?php echo $item->html; ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <a href="/admin/recent/<?php echo isset($_GET['feed']) ? '?feed='.$_GET['feed'] : ''; ?>" style="margin-top:10px;float:right;text-transform:uppercase">Ver más</a>
                    
                </div>
            </div>


        <?php endif; ?>

            </div> <!-- fin center -->

        </div> <!-- fin main -->

<?php
    include 'view/footer.html.php';
include 'view/epilogue.html.php';
