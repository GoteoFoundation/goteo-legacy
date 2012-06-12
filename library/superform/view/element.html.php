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


use Goteo\Core\View;

$element = $this['element'];

?>
<?php if (isset($element->title)): ?>
<h<?php echo $element->level ?> class="title"><?php echo htmlspecialchars($element->title) ?></h<?php echo $element->level ?>>
<?php endif ?>

<?php if ('' !== ($innerHTML = $element->getInnerHTML())): ?>
<div class="contents">    
    <?php echo $innerHTML?>            
</div>
<?php endif ?>

<?php if (!empty($element->errors) || !empty($element->hint)): ?>
<div class="feedback" id="superform-feedback-for-<?php echo htmlspecialchars($element->id) ?>">

    <?php if (!empty($element->errors)): ?>
    <div class="error">        
        <?php foreach ($element->errors as $error): ?>
        <blockquote><?php echo $error ?></blockquote>
        <?php endforeach ?>
    </div>
    <?php endif ?>

    <?php if (isset($element->hint)): ?>
    <div class="hint">
        <blockquote><?php echo $element->hint ?></blockquote>
    </div>
    <?php endif ?>
    
</div>
<?php endif ?>

<?php if (!empty($element->children) && $element->type == 'group'): ?>
<div class="children">
    <?php echo new View('library/superform/view/elements.html.php', $element->children) ?>
</div>
<?php endif ?>
