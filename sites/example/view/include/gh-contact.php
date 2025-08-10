<?php
/**
 * @var WW\Module $this
 */
?>
<section id="contact" class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row">
            <div class="lg:w-1/2 mb-12 lg:mb-0 lg:pr-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">Make a Reservation</h2>
                <div class="w-24 h-1 bg-amber-500 mb-6"></div>
                <p class="text-gray-600 mb-8">Reserve your table online or give us a call at (555) 123-4567. For parties of 6 or more, please call to make arrangements.</p>
                
                <form class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-gray-700 font-medium mb-2">Full Name</label>
                            <input type="text" id="name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent" placeholder="Your name">
                        </div>
                        <div>
                            <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                            <input type="email" id="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent" placeholder="Your email">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="date" class="block text-gray-700 font-medium mb-2">Date</label>
                            <input type="date" id="date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="time" class="block text-gray-700 font-medium mb-2">Time</label>
                            <select id="time" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                                <option value="">Select time</option>
                                <option value="17:00">5:00 PM</option>
                                <option value="17:30">5:30 PM</option>
                                <option value="18:00">6:00 PM</option>
                                <option value="18:30">6:30 PM</option>
                                <option value="19:00">7:00 PM</option>
                                <option value="19:30">7:30 PM</option>
                                <option value="20:00">8:00 PM</option>
                                <option value="20:30">8:30 PM</option>
                                <option value="21:00">9:00 PM</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="guests" class="block text-gray-700 font-medium mb-2">Number of Guests</label>
                            <select id="guests" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                                <option value="1">1 person</option>
                                <option value="2">2 people</option>
                                <option value="3">3 people</option>
                                <option value="4">4 people</option>
                                <option value="5">5 people</option>
                                <option value="6">6 people</option>
                            </select>
                        </div>
                        <div>
                            <label for="occasion" class="block text-gray-700 font-medium mb-2">Occasion (Optional)</label>
                            <select id="occasion" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                                <option value="">None</option>
                                <option value="anniversary">Anniversary</option>
                                <option value="birthday">Birthday</option>
                                <option value="business">Business Dinner</option>
                                <option value="engagement">Engagement</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label for="special-requests" class="block text-gray-700 font-medium mb-2">Special Requests</label>
                        <textarea id="special-requests" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent" placeholder="Dietary restrictions, allergies, etc."></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white px-6 py-4 rounded-full text-lg font-medium transition duration-300">Reserve Now</button>
                </form>
            </div>
            
            <div class="lg:w-1/2 lg:pl-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">Contact Us</h2>
                <div class="w-24 h-1 bg-amber-500 mb-6"></div>
                
                <div class="space-y-6 mb-8">
                    <div class="flex items-start">
                        <div class="bg-amber-100 p-3 rounded-full mr-4">
                            <i class="fas fa-map-marker-alt text-amber-500 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold mb-1">Address</h4>
                            <p class="text-gray-600">123 Gourmet Street<br>New York, NY 10001</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-amber-100 p-3 rounded-full mr-4">
                            <i class="fas fa-phone-alt text-amber-500 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold mb-1">Phone</h4>
                            <p class="text-gray-600">(555) 123-4567<br>Reservations: (555) 123-4568</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-amber-100 p-3 rounded-full mr-4">
                            <i class="fas fa-envelope text-amber-500 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold mb-1">Email</h4>
                            <p class="text-gray-600">reservations@gourmethaven.com<br>info@gourmethaven.com</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-amber-100 p-3 rounded-full mr-4">
                            <i class="fas fa-clock text-amber-500 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold mb-1">Hours</h4>
                            <p class="text-gray-600">
                                <span class="font-medium">Dinner:</span> Tuesday - Sunday, 5:00 PM - 10:00 PM<br>
                                <span class="font-medium">Brunch:</span> Saturday & Sunday, 10:00 AM - 2:00 PM<br>
                                Closed Mondays
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-100 p-6 rounded-lg">
                    <h4 class="font-bold mb-4">Follow Us</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="bg-amber-500 hover:bg-amber-600 text-white w-10 h-10 rounded-full flex items-center justify-center transition duration-300">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="bg-amber-500 hover:bg-amber-600 text-white w-10 h-10 rounded-full flex items-center justify-center transition duration-300">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="bg-amber-500 hover:bg-amber-600 text-white w-10 h-10 rounded-full flex items-center justify-center transition duration-300">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="bg-amber-500 hover:bg-amber-600 text-white w-10 h-10 rounded-full flex items-center justify-center transition duration-300">
                            <i class="fab fa-yelp"></i>
                        </a>
                    </div>
                </div>
                
                <div class="mt-8">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.215256627966!2d-73.9878449242378!3d40.74844097138985!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c259a9b3117469%3A0xd134e199a405a163!2sEmpire%20State%20Building!5e0!3m2!1sen!2sus!4v1689871036789!5m2!1sen!2sus" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="rounded-lg shadow-md"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>
