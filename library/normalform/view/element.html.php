<?php 

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

<?php if (!empty($element->children) && $element->type == 'group'): ?>
<div class="children">
    <?php echo new View('library/superform/view/elements.html.php', $element->children) ?>
</div>
<?php endif ?>
