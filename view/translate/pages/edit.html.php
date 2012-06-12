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
    Goteo\Library\Page;

$page = Page::get($this['id'], $_SESSION['translator_lang']);
$original = Page::get($this['id'], \GOTEO_DEFAULT_LANG);

$bodyClass = 'admin';

?>
<script type="text/javascript" src="/view/js/ckeditor/ckeditor.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	// Lanza wysiwyg contenido
	CKEDITOR.replace('richtext_content', {
		toolbar: 'Full',
		toolbar_Full: [
				['Source','-'],
				['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
				['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
				'/',
				['Bold','Italic','Underline','Strike'],
				['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
				['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
				['Link','Unlink','Anchor'],
                ['Image','Format','FontSize'],
			  ],
		skin: 'kama',
		language: 'es',
		height: '300px',
		width: '800px'
	});
});
</script>

<div class="widget board">
    <h3 class="title"><?php echo $page->name; ?></h3>

    <fieldset>
        <legend>Descripción</legend>
        <blockquote><?php echo $page->description; ?></blockquote>
    </fieldset>

    <form method="post" action="/translate/pages/edit/<?php echo $page->id; ?>">
        <input type="hidden" name="lang" value="<?php echo $_SESSION['translator_lang'] ?>" />
        <textarea id="richtext_content" name="content" cols="100" rows="20"><?php echo $page->content; ?></textarea>
        <input type="submit" name="save" value="Guardar" />
    </form>
</div>

<div class="widget board">
    <h3>Contenido original</h3>

    <?php echo $original->content; ?>
</div>
