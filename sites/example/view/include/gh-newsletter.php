<?php
/**
 * @var WW\Cauldron $cauldron
 */
?>
<section    id="<?=$cauldron->type.$cauldron->id ?>"
            class="py-16 bg-amber-50">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
            <?=$cauldron->headline ?>
        </h2>
        <p class="text-gray-600 max-w-2xl mx-auto mb-8">
            <?=$cauldron->description ?>
        </p>
        
        <form class="max-w-md mx-auto flex">
            <input  type="email" 
                    placeholder="Your email address" 
                    class="flex-grow px-4 py-3 rounded-l-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
            <button     type="submit" 
                        class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-r-lg font-medium transition duration-300">
                Subscribe
            </button>
        </form>
    </div>
</section>
