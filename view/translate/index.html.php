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
    Goteo\Library\i18n\Lang;

$langs = Lang::getAll();

// hay que elegir un idioma al que traducir, no se puede traducir a español, español es el idioma original
if ($_SESSION['translator_lang'] == 'es') {
    unset($_SESSION['translator_lang']);
    unset($this['section']);
    unset($this['action']);
}


$bodyClass = 'admin';

include 'view/prologue.html.php';
include 'view/header.html.php'; ?>

        <div id="sub-header">
            <div>
                <h2>Panel principal de traducción</h2>
                <?php if (defined('ADMIN_BCPATH')) : ?>
                <blockquote><?php echo ADMIN_BCPATH; ?></blockquote>
                <?php endif; ?>
            </div>
        </div>

<?php if(isset($_SESSION['messages'])) { include 'view/header/message.html.php'; } ?>

        <div id="main">
            
            <div class="widget">
                <?php echo new View ('view/translate/langs/selector.html.php', $this); ?>
            </div>

            <?php if (!empty($this['errors'])) : ?>
                <div class="widget">
                    <p>
                        <?php echo implode('<br />', $this['errors']); ?>
                    </p>
                </div>
            <?php endif; ?>

            <?php
            if (!empty($this['section']) && !empty($this['action'])) :
                echo new View ('view/translate/'.$this['section'].'/'.$this['action'].'.html.php', $this);
            else :
                foreach ($this['menu'] as $sCode=>$section) : ?>
                    <a name="<?php echo $sCode ?>"></a>
                    <div class="widget board collapse">
                        <h3 class="title"><?php echo $section['label'] ?></h3>
                        <ul>
                            <?php foreach ($section['options'] as $oCode=>$option) :
                                echo '<li><a href="/translate/'.$oCode.'">'.$option['label'].'</a></li>
                                    ';
                            endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach;

            endif; ?>

        </div>
<?php
include 'view/footer.html.php';
include 'view/epilogue.html.php';