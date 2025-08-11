<?php
/**
 * @var WW\Cauldron $cauldron
 * @var ?WW\Witch $witch
 */
$menu = $witch ?? $cauldron->witches()[0] ?? $this->witch();
?>

<section id="menu" class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">
                <?=$cauldron->content('headline')?->value()?>
            </h2>
            <div class="w-24 h-1 bg-amber-500 mx-auto mb-6"></div>
            <p class="text-gray-600 max-w-2xl mx-auto">
                <?=$cauldron->content('description')?->value()?>
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach( $menu->daughters() as $dishWitch ): 
                $dish = $dishWitch->cauldron();
                //$this->ww->debug( $dish->list() );
                if( $dish->recipe()->name === 'gh-dish' ): ?>
                    <div class="menu-item bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition duration-300">
                        <div class="h-48 overflow-hidden">
                            <img    src="<?=$dish->photo->file->value()?>" 
                                    alt="<?=$dish->photo->caption?>" 
                                    class="menu-img w-full h-full object-cover" />
                        </div>

                        <div class="p-6">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-xl font-bold">
                                    <?=$dish->title ?>
                                </h3>
                                <span class="text-amber-500 font-bold">
                                    $<?=$dish->price ?>
                                </span>
                            </div>
                            <p class="text-gray-600 mb-4">
                                <?=$dish->content('description')->value() ?>
                            </p>
                            <?php if( $dish->rate ): ?>
                                <div class="flex items-center text-amber-500">
                                    <?php for( $i=1; $i<=5; $i++ ): 
                                        if( $i <= $dish->rate ): ?>
                                            <i class="fas fa-star"></i>
                                        <?php elseif( $i == ceil($dish->rate) ): ?>
                                            <i class="fas fa-star-half-alt"></i>
                                        <?php else: ?>
                                            <i class="far fa-star"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    
                                    <span class="text-gray-600 ml-2">
                                        (<?=$dish->evals_quantity ?>)
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif;
            endforeach; ?>
        </div>
    
        <div class="text-center mt-12">
            <a href="#" class="inline-block bg-amber-500 hover:bg-amber-600 text-white px-8 py-3 rounded-full text-lg font-medium transition duration-300">View Full Menu</a>
        </div>
    </div>
</section>