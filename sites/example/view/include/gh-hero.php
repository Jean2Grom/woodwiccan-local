<?php
/**
 * @var WW\Cauldron $cauldron
 */
?>
<section    id="<?=$cauldron->type.$cauldron->id ?>" 
            class="hero flex items-center justify-center text-center text-white pt-20"
            style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('<?=$cauldron->background->file->value() ?>');">
    <div class="container mx-auto px-4 animate-fade-in">
        <h1 class="text-4xl md:text-6xl font-bold mb-6">
            <?=$cauldron->content('headline')?->value()?>
        </h1>
        <p class="text-xl md:text-2xl mb-8 max-w-2xl mx-auto">
            <?=$cauldron->content('description')?->value()?>
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <?php foreach( $cauldron->actions?->value() ?? [] as $key => $action ): 
                if( $action->recipe()->name === 'link' ):
                    $class = !($key % 2)? 
                                "bg-amber-500 hover:bg-amber-600 text-white":
                                "bg-transparent hover:bg-white hover:text-gray-800 text-white border-2 border-white";
                ?>
                <a  href="<?=$action->content('href')->value()?>" 
                    class="<?=$class ?> px-8 py-3 rounded-full text-lg font-medium transition duration-300">
                    <?=$action->content('text')->value()?>
                </a>
            <?php endif; endforeach; ?>
        </div>
    </div>
</section>
