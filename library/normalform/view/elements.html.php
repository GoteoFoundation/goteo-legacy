<?php if (count($this) > 0): ?>
<div class="elements">   
    <ol>
        <?php foreach ($this as $element): ?>
        <li class="element<?php echo rtrim(' ' . htmlspecialchars($element->type)) .  rtrim(' ' . htmlspecialchars($element->class)) ?>" id="<?php echo htmlspecialchars($element->id) ?>" name="<?php echo htmlspecialchars($element->id) ?>">
            <?php echo (string) $element ?>           
        </li>
        <?php endforeach ?>
    </ol>
</div>
<?php endif ?>