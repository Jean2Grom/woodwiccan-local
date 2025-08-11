<?php /** @var WW\Module $this */ 

$this->addCssFile('gourmet-haven.css');
$this->addJsFile('gourmet-haven.js');
$this->addContextVar('title', "Gourmet Haven | Fine Dining Experience");
$this->addContextVar('bodyClass', "font-sans text-gray-800");
?>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


<!-- Navigation -->
<nav class="fixed w-full bg-white shadow-md z-50 transition-all duration-300">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">
        <div class="flex items-center">
            <a href="#top" class="text-2xl font-bold text-amber-500">Gourmet<span class="text-gray-800">Haven</span></a>
        </div>
        
        <div class="hidden md:flex space-x-8">
            <?php foreach( $menu as $link ): ?>
                <a  href="<?=$link['href'] ?>" 
                    class="nav-link text-gray-800 hover:text-amber-500">
                    <?=$link['text'] ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="flex items-center space-x-4">
            <a  href="<?=$callToAction->href ?>" 
                class="hidden md:block bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-full transition duration-300">
                <?=$callToAction->text ?>
            </a>
            <button id="mobile-menu-button" 
                    class="md:hidden text-gray-800 focus:outline-none">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div    id="mobile-menu" 
            class="hidden md:hidden bg-white w-full px-4 pb-4 shadow-lg">
        <div class="flex flex-col space-y-3">
            <?php foreach( $menu as $link ): ?>
                <a  href="<?=$link['href'] ?>" 
                    class="nav-link text-gray-800 hover:text-amber-500 py-2">
                    <?=$link['text'] ?>
                </a>
            <?php endforeach; ?>
            <a  href="<?=$callToAction->href ?>" 
                class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-full text-center transition duration-300">
                <?=$callToAction->text ?>
            </a>
        </div>
    </div>
</nav>

<div id="top"></div>

<?php foreach( $this->witch()->daughters() as $section ): ?>
    <?php $this->include($section->cauldron()->recipe, [ 'cauldron' => $section->cauldron() ]); ?>
<?php endforeach; ?>
 
<!-- Footer -->
<footer class="bg-gray-900 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div>
                <h3 class="text-xl font-bold mb-4">
                    <?=$footer->headline ?>
                </h3>

                <div class="text-gray-400 mb-4">
                    <?=$footer->description ?>
                </div>

                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-amber-500 transition duration-300">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-amber-500 transition duration-300">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-amber-500 transition duration-300">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-amber-500 transition duration-300">
                        <i class="fab fa-yelp"></i>
                    </a>
                </div>
            </div>
            
            <div>
                <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-2">
                    <?php foreach( $menu as $link ): ?>
                        <li>
                            <a  href="<?=$link['href'] ?>" 
                                class="text-gray-400 hover:text-amber-500 transition duration-300">
                                <?=$link['text'] ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <div>
                <h4 class="text-lg font-semibold mb-4">Hours</h4>
                <ul class="space-y-2 text-gray-400">
                    <?php foreach( $footer->hours->value() as $hourSection ): ?>
                        <li class="flex justify-between">
                            <?=$hourSection ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <div>
                <h4 class="text-lg font-semibold mb-4">Contact Info</h4>
                <address class="not-italic text-gray-400 space-y-2">
                    <?=$footer->content('contact-info') ?>
                </address>
            </div>
        </div>
        
        <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
            <p class="text-gray-400 mb-4 md:mb-0">© 2025 Gourmet Haven. All rights reserved.</p>

            <div class="flex space-x-6">
                <a href="#" class="text-gray-400 hover:text-amber-500 transition duration-300">Privacy Policy</a>
                <a href="#" class="text-gray-400 hover:text-amber-500 transition duration-300">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
 
<!-- Back to Top Button -->
<button id="back-to-top" class="fixed bottom-8 right-8 bg-amber-500 hover:bg-amber-600 text-white w-12 h-12 rounded-full flex items-center justify-center shadow-lg transition duration-300 opacity-0 invisible">
    <i class="fas fa-arrow-up"></i>
</button>

