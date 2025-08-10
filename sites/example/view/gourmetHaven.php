<?php /** @var WW\Module $this */ 

//$this->addJsLibFile('tailwindcss.3.4.17.js');
//$this->addCssFile('font-awesome.6.4.0.all.min.css');
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
            <a href="#" class="text-2xl font-bold text-amber-500">Gourmet<span class="text-gray-800">Haven</span></a>
        </div>
        
        <div class="hidden md:flex space-x-8">
            <a href="#home" class="nav-link text-gray-800 hover:text-amber-500">Home</a>
            <a href="#menu" class="nav-link text-gray-800 hover:text-amber-500">Menu</a>
            <a href="#about" class="nav-link text-gray-800 hover:text-amber-500">About</a>
            <a href="#testimonials" class="nav-link text-gray-800 hover:text-amber-500">Testimonials</a>
            <a href="#contact" class="nav-link text-gray-800 hover:text-amber-500">Contact</a>
        </div>
        
        <div class="flex items-center space-x-4">
            <a href="#" class="hidden md:block bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-full transition duration-300">Reservations</a>
            <button id="mobile-menu-button" class="md:hidden text-gray-800 focus:outline-none">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white w-full px-4 pb-4 shadow-lg">
        <div class="flex flex-col space-y-3">
            <a href="#home" class="nav-link text-gray-800 hover:text-amber-500 py-2">Home</a>
            <a href="#menu" class="nav-link text-gray-800 hover:text-amber-500 py-2">Menu</a>
            <a href="#about" class="nav-link text-gray-800 hover:text-amber-500 py-2">About</a>
            <a href="#testimonials" class="nav-link text-gray-800 hover:text-amber-500 py-2">Testimonials</a>
            <a href="#contact" class="nav-link text-gray-800 hover:text-amber-500 py-2">Contact</a>
            <a href="#" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-full text-center transition duration-300">Reservations</a>
        </div>
    </div>
 </nav>
 
<?php foreach( $this->witch()->daughters() as $section ): ?>
<?php $this->ww->debug( $section->cauldron()->recipe ); ?>
    <?php $this->include($section->cauldron()->recipe, [ 'cauldron' => $section->cauldron() ]); ?>
<?php endforeach; ?>
 
<!-- Footer -->
<footer class="bg-gray-900 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div>
                <h3 class="text-xl font-bold mb-4">Gourmet Haven</h3>
                <p class="text-gray-400 mb-4">Where passion meets perfection in every dish we serve.</p>

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
                    <li><a href="#home" class="text-gray-400 hover:text-amber-500 transition duration-300">Home</a></li>
                    <li><a href="#menu" class="text-gray-400 hover:text-amber-500 transition duration-300">Menu</a></li>
                    <li><a href="#about" class="text-gray-400 hover:text-amber-500 transition duration-300">About Us</a></li>
                    <li><a href="#testimonials" class="text-gray-400 hover:text-amber-500 transition duration-300">Testimonials</a></li>
                    <li><a href="#contact" class="text-gray-400 hover:text-amber-500 transition duration-300">Contact</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="text-lg font-semibold mb-4">Hours</h4>
                <ul class="space-y-2 text-gray-400">
                    <li class="flex justify-between">
                        <span>Tuesday - Thursday</span>
                        <span>5:00 PM - 9:30 PM</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Friday - Saturday</span>
                        <span>5:00 PM - 10:30 PM</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Sunday</span>
                        <span>5:00 PM - 9:00 PM</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Brunch (Sat-Sun)</span>
                        <span>10:00 AM - 2:00 PM</span>
                    </li>
                </ul>
            </div>
            
            <div>
                <h4 class="text-lg font-semibold mb-4">Contact Info</h4>
                <address class="not-italic text-gray-400 space-y-2">
                    <p>123 Gourmet Street<br>New York, NY 10001</p>
                    <p>Phone: (555) 123-4567</p>
                    <p>Email: info@gourmethaven.com</p>
                </address>
            </div>
        </div>
        
        <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
            <p class="text-gray-400 mb-4 md:mb-0">© 2023 Gourmet Haven. All rights reserved.</p>

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

