<?php
/**
 * @var WW\Cauldron $cauldron
 */
?>
<section id="about" class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row items-center">
            <div class="lg:w-1/2 mb-12 lg:mb-0 lg:pr-12">

                <img    src="<?=$cauldron->image->file->value()?>" 
                        alt="<?=$cauldron->image->caption ?>" 
                        class="rounded-lg shadow-xl w-full" />
            </div>
            <div class="lg:w-1/2">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">
                    <?=$cauldron->headline ?>
                </h2>
                <div class="w-24 h-1 bg-amber-500 mb-6"></div>
                <div class="page-body">
                    <?=$cauldron->body ?>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                    <?php foreach( $cauldron->value() as $item ): if( $item->type === 'gh-bulletpoint' ): ?>
                        <div class="flex items-start">
                            <div class="bg-amber-100 p-3 rounded-full mr-4">
                                <i class="fas <?=$item->icon_class ?> text-amber-500 text-xl"></i>
                            </div>
                            <div class="page-bulletpoint">
                                <h4 class="font-bold mb-1">
                                    <?=$item->head ?>
                                </h4>
                                <?=$item->body ?>
                            </div>
                        </div>
                    <?php endif; endforeach; ?>
                </div>
                
                <a href="#" class="inline-block bg-amber-500 hover:bg-amber-600 text-white px-8 py-3 rounded-full text-lg font-medium transition duration-300">Meet Our Team</a>
            </div>
        </div>
    </div>
</section>
