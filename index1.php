<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staycation. - Next Vacation</title>
    <!-- Include Tailwind CSS (using CDN for simplicity) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styles for the pink color and the blue button to match the image */
        .pink-badge {
            background-color: #F8C3D2; /* Approx pink from image */
            color: #C02D58; /* Darker pink/red for text */
        }
        .text-pink-dark {
            color: #C02D58;
        }
        .btn-blue {
            background-color: #4C72F5; /* Main blue button color */
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: background-color 0.2s;
        }
        .btn-blue:hover {
            background-color: #385de0;
        }
        .footer-link {
            @apply text-gray-500 hover:text-blue-500 transition duration-150;
        }
        /* Custom card style to match the image's listing cards */
        .listing-card {
            @apply bg-white rounded-xl overflow-hidden shadow-lg transform transition duration-300 hover:shadow-xl hover:-translate-y-1;
        }
        /* The main container width looks a bit less than max-w-7xl */
        .custom-container {
            @apply max-w-6xl mx-auto px-4 sm:px-6 lg:px-8;
        }
        /* Styling for the mobile menu (hidden by default) */
        #mobile-menu {
            transition: all 0.3s ease-in-out;
            transform: translateX(100%);
        }
        #mobile-menu.open {
            transform: translateX(0);
        }
    </style>
</head>
<body class="bg-white font-sans text-gray-800">

    <!-- 1. Header/Navigation (Two-Tier Structure) -->
    <header class="relative z-50 shadow-lg">
        <!-- Top Bar (Dark) -->
        <div class="bg-gray-800 text-white py-3">
            <div class="custom-container flex justify-between items-center">
                <!-- Location -->
                <a href="#" class="flex items-center space-x-1 text-sm hover:text-gray-300 transition duration-150">
                    <!-- Location Icon -->
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.727A8 8 0 016.343 3.273L12 12l-5.657 9.53a8 8 0 0111.314-14.14zM12 12a3 3 0 100-6 3 3 0 000 6z" /></svg>
                    <span class="hidden sm:inline">Location</span>
                </a>
                <!-- LOGO (Centered) -->
                <div class="text-2xl font-extrabold tracking-widest">LOGO</div>
                
                <!-- My Account / Mobile Menu Button -->
                <div class="flex items-center space-x-2">
                    <!-- My Account (Desktop/Tablet) -->
                    <a href="#" class="hidden sm:flex items-center space-x-1 text-sm hover:text-gray-300 transition duration-150">
                        <!-- User Icon -->
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        <span>My Account</span>
                    </a>

                    <!-- Mobile Menu Button (Hamburger) -->
                    <button id="menu-button" class="p-2 rounded-lg hover:bg-gray-700 focus:outline-none md:hidden text-white" onclick="toggleMenu()">
                        <!-- Hamburger Icon (Menu) -->
                        <svg id="menu-icon-open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <!-- Close Icon (X) -->
                        <svg id="menu-icon-close" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Bottom Bar (Grey Navigation - Desktop Only) -->
        <div class="bg-gray-400 py-3 hidden md:block shadow-md">
            <div class="custom-container">
                <nav class="flex justify-center space-x-10 text-gray-900 font-medium">
                    <a href="#" class="hover:text-white transition duration-150">Home</a>
                    <a href="#" class="flex items-center space-x-1 hover:text-white transition duration-150">
                        <span>Services</span>
                        <!-- Down Arrow Icon -->
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </a>
                    <a href="#" class="hover:text-white transition duration-150">Blog</a>
                    <a href="#" class="hover:text-white transition duration-150">Help Center</a>
                    <a href="#" class="hover:text-white transition duration-150">About</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- 2. Mobile Off-Canvas Menu -->
    <!-- Initially hidden and positioned off-screen to the right. Z-40 to cover main content. -->
    <div id="mobile-menu" class="fixed inset-0 bg-white z-40 md:hidden flex flex-col pt-24 space-y-6 px-8 shadow-xl">
        <a href="#" class="text-2xl font-semibold text-gray-900 hover:text-blue-500 py-2 border-b border-gray-100" onclick="toggleMenu()">Home</a>
        <a href="#" class="text-2xl font-semibold text-gray-900 hover:text-blue-500 py-2 border-b border-gray-100" onclick="toggleMenu()">Services</a>
        <a href="#" class="text-2xl font-semibold text-gray-900 hover:text-blue-500 py-2 border-b border-gray-100" onclick="toggleMenu()">Blog</a>
        <a href="#" class="text-2xl font-semibold text-gray-900 hover:text-blue-500 py-2 border-b border-gray-100" onclick="toggleMenu()">Help Center</a>
        <a href="#" class="text-2xl font-semibold text-gray-900 hover:text-blue-500 py-2 border-b border-gray-100" onclick="toggleMenu()">About</a>
        <!-- The items below are from the top bar for completeness on mobile -->
        <a href="#" class="text-2xl font-semibold text-gray-900 hover:text-blue-500 py-2 border-t border-gray-100 mt-4 pt-4" onclick="toggleMenu()">My Account</a>
        <a href="#" class="text-2xl font-semibold text-gray-900 hover:text-blue-500 py-2" onclick="toggleMenu()">Location</a>
    </div>

    <!-- Main Content Sections -->
    <main class="custom-container relative z-0">

        <!-- Hero Section -->
        <section class="grid md:grid-cols-2 gap-8 py-12 items-center">
            <!-- Left Content -->
            <div>
                <h1 class="text-5xl font-extrabold mb-4 leading-tight">
                    Forget Busy Work, <br>
                    Start Next <span class="text-pink-dark">Vacation</span>
                </h1>
                <p class="text-gray-500 mb-8 max-w-md">
                    We provide what you need to enjoy your holiday with family. Time to make another unforgettable memory.
                </p>
                <a href="#" class="btn-blue inline-block">Show All Now</a>
                
                <!-- Feature Statistics -->
                <div class="flex space-x-8 mt-12 text-center">
                    <div>
                        <span class="text-2xl font-bold text-gray-900">80.409</span>
                        <p class="text-gray-500 text-sm mt-1">Travelers</p>
                    </div>
                    <div>
                        <span class="text-2xl font-bold text-gray-900">1,219</span>
                        <p class="text-gray-500 text-sm mt-1">Houses</p>
                    </div>
                    <div>
                        <span class="text-2xl font-bold text-gray-900">1,219</span>
                        <p class="text-gray-500 text-sm mt-1">Cities</p>
                    </div>
                </div>
            </div>

            <!-- Right Image -->
            <div class="relative hidden md:block">
                <div class="rounded-xl overflow-hidden shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1542476092-23c31671f46d?q=80&w=2670&auto=format&fit=crop" alt="Modern Glass House" class="w-full h-96 object-cover">
                </div>
            </div>
        </section>
        
        <hr class="my-10">

        <!-- Most Picked Listings -->
        <section class="py-10">
            <h2 class="text-2xl font-bold mb-6">Most Picked</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                
                <!-- Card 1 (Large - spans 2 columns on md/lg) -->
                <!-- The structure is made responsive. On mobile (col-span-1), then on md/lg it adjusts for the staggered grid. -->
                <div class="listing-card sm:col-span-2 lg:col-span-2 relative">
                    <span class="absolute top-0 right-0 m-4 px-3 py-1 rounded-full text-xs font-semibold pink-badge">$50 per night</span>
                    <img src="https://images.unsplash.com/photo-1599696349673-812328468b13?q=80&w=2670&auto=format&fit=crop" alt="Blue House" class="w-full h-64 object-cover">
                    <div class="p-4">
                        <h3 class="text-lg font-bold">Blue Origin Fams</h3>
                        <p class="text-sm text-gray-500">Jakarta, Indonesia</p>
                    </div>
                </div>
                
                <!-- Card 2 (Medium - spans 2 rows on the right column on large screens) -->
                <div class="listing-card relative hidden lg:block lg:row-span-2">
                    <span class="absolute top-0 right-0 m-4 px-3 py-1 rounded-full text-xs font-semibold pink-badge">$220 per night</span>
                    <img src="https://images.unsplash.com/photo-1551800293-9c869e5d6d84?q=80&w=2574&auto=format&fit=crop" alt="Luxury Villa" class="w-full h-80 object-cover">
                    <div class="p-4">
                        <h3 class="text-lg font-bold">Wooden Pit</h3>
                        <p class="text-sm text-gray-500">Tucson, Arizona</p>
                    </div>
                </div>

                <!-- Card 3 (Small) -->
                <div class="listing-card relative">
                    <span class="absolute top-0 right-0 m-4 px-3 py-1 rounded-full text-xs font-semibold pink-badge">$32 per night</span>
                    <img src="https://images.unsplash.com/photo-1502672023488-70e25813f809?q=80&w=2564&auto=format&fit=crop" alt="White Apartment" class="w-full h-40 object-cover">
                    <div class="p-4">
                        <h3 class="text-lg font-bold">Ocean Land</h3>
                        <p class="text-sm text-gray-500">California, USA</p>
                    </div>
                </div>

                <!-- Card 4 (Small) -->
                <div class="listing-card relative">
                    <span class="absolute top-0 right-0 m-4 px-3 py-1 rounded-full text-xs font-semibold pink-badge">$28 per night</span>
                    <img src="https://images.unsplash.com/photo-1582268611958-ab508017efdc?q=80&w=2670&auto=format&fit=crop" alt="Cabin" class="w-full h-40 object-cover">
                    <div class="p-4">
                        <h3 class="text-lg font-bold">Green Park</h3>
                        <p class="text-sm text-gray-500">Toulouse, France</p>
                    </div>
                </div>

            </div>
        </section>

        <!-- Houses with Beauty Backyard -->
        <section class="py-10">
            <h2 class="text-2xl font-bold mb-6">Houses with beauty backyard</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <!-- House Card Component (Repeated 4 times) -->
                <div class="listing-card relative">
                    <span class="absolute top-0 left-0 m-2 px-3 py-1 rounded text-xs font-semibold text-white bg-pink-500/80">Popular</span>
                    <img src="https://images.unsplash.com/photo-1560518883-ef6a43b177d5?q=80&w=2532&auto=format&fit=crop" alt="Tropical Town" class="w-full h-40 object-cover">
                    <div class="p-3">
                        <h3 class="text-base font-bold">Tropical Town</h3>
                        <p class="text-xs text-gray-500">Bogor, Indonesia</p>
                    </div>
                </div>
                <div class="listing-card relative">
                    <img src="https://images.unsplash.com/photo-1540518625296-6085a53e5e48?q=80&w=2670&auto=format&fit=crop" alt="Apartment Anggana" class="w-full h-40 object-cover">
                    <div class="p-3">
                        <h3 class="text-base font-bold">Anggana</h3>
                        <p class="text-xs text-gray-500">Bali, Indonesia</p>
                    </div>
                </div>
                <div class="listing-card relative">
                    <img src="https://images.unsplash.com/photo-1580587771525-78b9dba3825e?q=80&w=2670&auto=format&fit=crop" alt="Seattle Room" class="w-full h-40 object-cover">
                    <div class="p-3">
                        <h3 class="text-base font-bold">Seattle Room</h3>
                        <p class="text-xs text-gray-500">Seattle, USA</p>
                    </div>
                </div>
                <div class="listing-card relative">
                    <img src="https://images.unsplash.com/photo-1582046808791-fd308c3507d4?q=80&w=2670&auto=format&fit=crop" alt="Waterpark Villa" class="w-full h-40 object-cover">
                    <div class="p-3">
                        <h3 class="text-base font-bold">Waterpark Villa</h3>
                        <p class="text-xs text-gray-500">Warsaw, Poland</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Hotels with Large Living Room -->
        <section class="py-10">
            <h2 class="text-2xl font-bold mb-6">Hotels with large living room</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <!-- Hotel Card Component (Repeated 4 times) -->
                <div class="listing-card relative">
                    <img src="https://images.unsplash.com/photo-1578683010236-d71685c3d420?q=80&w=2670&auto=format&fit=crop" alt="Green Point" class="w-full h-40 object-cover">
                    <div class="p-3">
                        <h3 class="text-base font-bold">Green Point</h3>
                        <p class="text-xs text-gray-500">Tangerang, Indonesia</p>
                    </div>
                </div>
                <div class="listing-card relative">
                    <img src="https://images.unsplash.com/photo-1615873968403-15e8c1862b08?q=80&w=2670&auto=format&fit=crop" alt="Patio Wide" class="w-full h-40 object-cover">
                    <div class="p-3">
                        <h3 class="text-base font-bold">Patio Wide</h3>
                        <p class="text-xs text-gray-500">Bogor, Indonesia</p>
                    </div>
                </div>
                <div class="listing-card relative">
                    <img src="https://images.unsplash.com/photo-1518733054363-2279144d3755?q=80&w=2670&auto=format&fit=crop" alt="Silver Rain" class="w-full h-40 object-cover">
                    <div class="p-3">
                        <h3 class="text-base font-bold">Silver Rain</h3>
                        <p class="text-xs text-gray-500">Jakarta, Indonesia</p>
                    </div>
                </div>
                <div class="listing-card relative">
                    <span class="absolute top-0 left-0 m-2 px-3 py-1 rounded text-xs font-semibold text-white bg-blue-500/80">New Added</span>
                    <img src="https://images.unsplash.com/photo-1594914109315-181059f6350d?q=80&w=2670&auto=format&fit=crop" alt="Codville" class="w-full h-40 object-cover">
                    <div class="p-3">
                        <h3 class="text-base font-bold">Codville</h3>
                        <p class="text-xs text-gray-500">Bandung, Indonesia</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Apartments with Kitchen Set -->
        <section class="py-10">
            <h2 class="text-2xl font-bold mb-6">Apartments with kitchen set</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <!-- Apartment Card Component (Repeated 4 times) -->
                <div class="listing-card relative">
                    <img src="https://images.unsplash.com/photo-1588854337236-6889d631faa8?q=80&w=2670&auto=format&fit=crop" alt="PS Wood" class="w-full h-40 object-cover">
                    <div class="p-3">
                        <h3 class="text-base font-bold">PS Wood</h3>
                        <p class="text-xs text-gray-500">Pringsewu, Indonesia</p>
                    </div>
                </div>
                <div class="listing-card relative">
                    <img src="https://images.unsplash.com/photo-1556912170-008271e1933a?q=80&w=2670&auto=format&fit=crop" alt="One Park" class="w-full h-40 object-cover">
                    <div class="p-3">
                        <h3 class="text-base font-bold">One Park</h3>
                        <p class="text-xs text-gray-500">Jakarta, Indonesia</p>
                    </div>
                </div>
                <div class="listing-card relative">
                    <span class="absolute top-0 left-0 m-2 px-3 py-1 rounded text-xs font-semibold text-white bg-blue-500/80">New Added</span>
                    <img src="https://images.unsplash.com/photo-1556740774-a63e792c9035?q=80&w=2670&auto=format&fit=crop" alt="Minimal" class="w-full h-40 object-cover">
                    <div class="p-3">
                        <h3 class="text-base font-bold">Minimal</h3>
                        <p class="text-xs text-gray-500">Bandung, Indonesia</p>
                    </div>
                </div>
                <div class="listing-card relative">
                    <img src="https://images.unsplash.com/photo-1621298413247-f378a598c255?q=80&w=2670&auto=format&fit=crop" alt="Stay Loma" class="w-full h-40 object-cover">
                    <div class="p-3">
                        <h3 class="text-base font-bold">Stay Loma</h3>
                        <p class="text-xs text-gray-500">Surabaya, Indonesia</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Review Section (Happy Family) -->
        <section class="py-16">
            <div class="grid md:grid-cols-2 gap-12 items-center bg-gray-50 p-8 rounded-xl shadow-inner">
                <!-- Left Image/Family Photo -->
                <div class="rounded-lg overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1543888741-f7678518e11b?q=80&w=2671&auto=format&fit=crop" alt="Happy Family Review" class="w-full h-96 object-cover object-center">
                </div>

                <!-- Right Content/Quote -->
                <div class="p-4">
                    <h2 class="text-2xl font-bold mb-4">Happy Family 😊</h2>
                    <blockquote class="text-xl italic text-gray-600 mb-6 border-l-4 border-blue-500 pl-4">
                        "What a great trip with my family and I should try again next time soon..."
                    </blockquote>
                    <p class="text-lg font-semibold text-gray-900">Angga Restuaji</p>
                    <p class="text-sm text-gray-500 mb-6">Product Designer</p>
                    <a href="#" class="btn-blue inline-block">Read Their Story</a>
                </div>
            </div>
        </section>

    </main>
    
    <hr class="mt-16">

    <!-- Footer -->
    <footer class="custom-container py-12">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <!-- Col 1: Logo & Tagline -->
            <div>
                <div class="text-xl font-bold text-blue-900 mb-4">Staycation.</div>
                <p class="text-sm text-gray-500">We help people find their next vacation easily.</p>
            </div>
            
            <!-- Col 2: For Beginners -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-4">For Beginners</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="footer-link">New Account</a></li>
                    <li><a href="#" class="footer-link">Start Booking a Room</a></li>
                    <li><a href="#" class="footer-link">Use Payments</a></li>
                </ul>
            </div>

            <!-- Col 3: Explore Us -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-4">Explore Us</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="footer-link">Our Careers</a></li>
                    <li><a href="#" class="footer-link">Privacy Policy</a></li>
                    <li><a href="#" class="footer-link">Terms & Conditions</a></li>
                </ul>
            </div>

            <!-- Col 4: Contact Us -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-4">Connect Us</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="mailto:support@staycation.id" class="footer-link">support@staycation.id</a></li>
                    <li><a href="tel:02122081996" class="footer-link">021 - 2208 - 1996</a></li>
                    <li><p class="text-gray-500">Staycation Kemang, Jakarta</p></li>
                </ul>
            </div>
        </div>

        <div class="text-center text-xs text-gray-500 mt-12">
            Copyright 2024 • All Rights Reserved Staycation.
        </div>
    </footer>
    <!-- End Footer -->

    <script>
        function toggleMenu() {
            const menu = document.getElementById('mobile-menu');
            const openIcon = document.getElementById('menu-icon-open');
            const closeIcon = document.getElementById('menu-icon-close');
            
            // Toggle the 'open' class for the slide-in/out effect
            menu.classList.toggle('open');
            
            // Toggle visibility of the hamburger/close icons
            openIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        }
    </script>

</body>
</html>