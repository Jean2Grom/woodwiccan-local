<?php
/**
 * @var WW\Cauldron $cauldron
 */
?>

<section    id="<?=$cauldron->type.$cauldron->id ?>" 
            class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">
                <?=$cauldron->headline ?>
            </h2>
            <div class="w-24 h-1 bg-amber-500 mx-auto mb-6"></div>
            <p class="text-gray-600 max-w-2xl mx-auto">
                <?=$cauldron->description ?>
            </p>
        </div>
            
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php 
            $now = new \DateTime();
            foreach( $cauldron->reviews->value() as $review ): ?>
                <div class="testimonial-card bg-white p-8 rounded-lg shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="flex items-center text-amber-500 mr-2">
                            <?php for( $i=1; $i<=5; $i++ ): 
                                if( $i <= $review->rating ): ?>
                                    <i class="fas fa-star"></i>
                                <?php elseif( $i == ceil($review->rating) ): ?>
                                    <i class="fas fa-star-half-alt"></i>
                                <?php else: ?>
                                    <i class="far fa-star"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                        <span class="text-gray-500 text-sm">
                            <?php 
                            $intervall = $now->diff( $review->content('datetime')->value() );
                            if( $intervall->y > 1 ): ?>
                                <?=$intervall->y ?> years ago
                            <?php elseif( $intervall->m > 1 ): ?>
                                <?=$intervall->m ?> months ago
                            <?php elseif( $intervall->d > 1 ): ?>
                                <?=$intervall->d ?> days ago
                            <?php elseif( $intervall->h > 1 ): ?>
                                <?=$intervall->h ?> hours ago
                            <?php elseif( $intervall->i > 1 ): ?>
                                <?=$intervall->i ?> minutes ago
                            <?php else: ?>
                                Now
                            <?php endif; ?>
                        </span>
                    </div>

                    <p class="text-gray-600 mb-6">
                        "<?=$review->body ?>"
                    </p>
                    <div class="flex items-center">
                        <img    src="<?=$review->photo->file->value() ?>" 
                                alt="<?=$review->photo->caption?>" 
                                class="w-12 h-12 rounded-full mr-4" />
                        <div>
                            <h4 class="font-bold">
                                <?=$review->content('name') ?>
                            </h4>
                            <p class="text-gray-500 text-sm">
                                <?=$review->content('status') ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
