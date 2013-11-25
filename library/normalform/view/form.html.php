<?php use Goteo\Core\View ?>

<div class="superform<?php if (isset($this['class'])) echo ' '. htmlspecialchars($this['class']) ?>"<?php if (isset($this['id'])) echo 'id="'. htmlspecialchars($this['id']) . '"' ?>>
    
    <?php echo new View('library/normalform/view/elements.html.php', $this['elements']) ?>
    
    <?php if(!empty($this['footer'])): ?>
    <div class="footer">
        <div class="elements">
            <?php foreach ($this['footer'] as $element): ?>
            <div class="element">
                <?php echo $element->getInnerHTML() ?>
            </div>
            <?php endforeach ?>
        </div>
    </div>
    <?php endif ?>
    
    
    
</div>