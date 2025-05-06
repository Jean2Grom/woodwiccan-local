<?php
/**
 * @var string $name
 * @var array $require
 * @var array $possibleTypes
 */
?>
<ul class="restriction-settings">
    <li>
        <div>Accepted contents</div>
        <select class="content-add-trigger" data-target="<?=$name?>-accepted">
            <option value="0">Select content type</option>
            <?php foreach( $possibleTypes as $possibleType => $label ): ?>
                <option value="<?=$possibleType?>">
                    <?=$label?>
                </option>
            <?php endforeach; ?>                
        </select>
    </li>
    <li>
        <div class="accepted-contents-container">
            <?php foreach( $require['accept'] ?? [] as $acceptedItem ): ?>
                <a class="remove-content">
                    <input type="hidden" name="<?=$name?>-accepted[]" value="<?=$acceptedItem?>" />
                    <?=$possibleTypes[ $acceptedItem ] ?? $acceptedItem ?>
                    <i class="fa fa-times"></i>
                </a>
            <?php endforeach; ?> 
            </div>
    </li>
    <li>
        <div>Refused contents</div>
        <select class="content-add-trigger" data-target="<?=$name?>-refused">
            <option value="0">Select content type</option>
            <?php foreach( $possibleTypes as $possibleType => $label ): ?>
                <option value="<?=$possibleType?>"><?=$label?></option>
            <?php endforeach; ?>                
        </select>
    </li>
    <li>
        <div class="refused-contents-container">
            <?php foreach( $require['refuse'] ?? [] as $refusedItem ): ?>
                <a class="remove-content">
                    <input type="hidden" name="<?=$name?>-refused[]" value="<?=$refusedItem?>" />
                    <?=$possibleTypes[ $refusedItem ] ?? $refusedItem ?>
                    <i class="fa fa-times"></i>
                </a>
            <?php endforeach; ?> 
        </div>
    </li>
    <li>
        <div>Minimum required</div>
        <input  name="<?=$name?>-min" 
                value="<?=$require['min'] ?? 0?>" 
                type="number" min="0" />
    </li>
    <li>
        <div>Maximum allowed</div>
        <input  name="<?=$name?>-max" 
                value="<?=$require['max'] ?? -1?>"
                type="number" min="-1" />
    </li>
</ul>
