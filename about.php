<?php
session_start();
require_once 'config/functions.php';

$settings = getSiteSettings();
// Initialize with default values
$site_name = "My Hotel";
$favicon = "favicon.ico";
$logo = "logo.png";
$site_description = "Welcome to our hotel.";
$address = "123 Hotel St, City";
$phone = "123-456-7890";
$email = "info@hotel.com";
$facebook_link = "";
$instagram_link = "";
$twitter_link = "";
$whatsapp_link = "";


if (is_array($settings)) {
    $site_name = $settings['site_name'] ?? $site_name;
    $favicon = $settings['favicon'] ?? $favicon;
    $logo = $settings['logo'] ?? $logo;
    $site_description = $settings['site_description'] ?? $site_description;
    $address = $settings['address'] ?? $address;
    $phone = $settings['phone'] ?? $phone;
    $email = $settings['email'] ?? $email;
    $facebook_link = $settings['facebook_link'] ?? $facebook_link;
    $instagram_link = $settings['instagram_link'] ?? $instagram_link;
    $twitter_link = $settings['twitter_link'] ?? $twitter_link;
    $whatsapp_link = $settings['whatsapp_link'] ?? $whatsapp_link;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - <?php echo htmlspecialchars($site_name); ?></title>
    <link rel="shortcut icon" href="asset/image/<?php echo htmlspecialchars($favicon); ?>" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
     <style>
        .page-header {
            background-image: url('asset/image/IMG-20251129-WA0040.jpg'); 
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Header -->
    <header class="relative bg-gray-800 text-white py-6 px-8 md:px-16">
        <nav class="relative z-10 flex justify-between items-center">
            <a href="index.php" class="text-xl font-serif tracking-widest">
                <img src="asset/image/<?php echo htmlspecialchars($logo); ?>" alt="<?php echo htmlspecialchars($site_name); ?>" class="w-[50px]">
            </a>
            <div class="hidden md:flex space-x-8 text-sm uppercase tracking-wider justify-center items-center">
                <a href="index.php" class="hover:text-gray-300">Home</a>
                <a href="rooms.php" class="hover:text-gray-300">Rooms</a>
                <a href="food-menu.php" class="hover:text-gray-300">Dining</a>
                <a href="contact.php" class="hover:text-gray-300">Contact</a>
                 <?php if (isset($_SESSION['user'])): ?>
                    <a href="<?php echo $_SESSION['user']['role'] === 'admin' ? 'admin/dashboard.php' : 'user/dashboard.php'; ?>" class="hover:text-gray-300 bg-yellow-600 rounded-[10px] pt-[10px] pb-[10px] pr-[20px] pl-[20px]">Dashboard</a>
                    <a href="logout.php" class="hover:text-gray-300 rounded-[20px] border-2 border-yellow-600 pt-[10px] pb-[10px] pr-[20px] pl-[20px]">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="hover:text-gray-300 bg-yellow-600 rounded-[10px] pt-[10px] pb-[10px] pr-[20px] pl-[20px]">Login</a>
                    <a href="register.php" class="hover:text-gray-300 rounded-[20px] border-2 border-yellow-600 pt-[10px] pb-[10px] pr-[20px] pl-[20px]">Register</a>
                <?php endif; ?>
            </div>
            <button class="text-white md:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </button>
        </nav>
    </header>

    <main>
        <!-- Page Header -->
        <section class="page-header h-64 flex items-center justify-center text-white relative">
            <div class="absolute inset-0 bg-black opacity-50"></div>
            <div class="text-center z-10">
                <h1 class="text-5xl font-serif">About Us</h1>
                <p class="mt-2 text-lg">Discover our story and commitment to excellence.</p>
            </div>
        </section>

        <!-- About Content -->
        <section class="py-20">
            <div class="container mx-auto px-6 lg:px-8">
                <div class="flex flex-wrap -mx-4 items-center">
                    <div class="w-full lg:w-1/2 px-4">
                        <h2 class="text-3xl font-semibold text-gray-800 mb-4"><?php echo $site_name; ?></h2>
                        <div class="prose max-w-none text-gray-600">
                             <p><?php echo nl2br(htmlspecialchars($site_description)); ?></p>
                             <p>Founded on the principles of luxury, comfort, and unparalleled service, our hotel has been a premier destination for travelers for over a decade. We are dedicated to providing a memorable experience for every guest, from our elegantly designed rooms to our world-class amenities and dining options.</p>
                        </div>
                    </div>
                    <div class="w-full lg:w-1/2 px-4 mt-8 lg:mt-0">
                        <img src="asset/image/IMG-20251129-WA0032.jpg" alt="About <?php echo $site_name; ?>" class="rounded-lg shadow-xl w-full">
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Statistics -->
        <section class="bg-white py-12 px-4 md:px-16">
            <div class="flex flex-col md:flex-row justify-between max-w-5xl mx-auto shadow-lg rounded-lg p-6 md:p-8 border border-gray-100">
                <div class="flex-1 text-center py-4 border-b md:border-b-0 md:border-r border-gray-200">
                    <p class="text-5xl font-extrabold text-yellow-600">98%</p>
                    <p class="text-sm uppercase tracking-widest text-gray-500 mt-2">Guest Satisfaction</p>
                </div>
                <div class="flex-1 text-center py-4 border-b md:border-b-0 md:border-r border-gray-200">
                    <p class="text-5xl font-extrabold text-yellow-600">15+</p>
                    <p class="text-sm uppercase tracking-widest text-gray-500 mt-2">Years of Experience</p>
                </div>
                <div class="flex-1 text-center py-4">
                    <p class="text-5xl font-extrabold text-yellow-600">25K+</p>
                    <p class="text-sm uppercase tracking-widest text-gray-500 mt-2">Happy Customers</p>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 px-4 md:px-16">
        <div class="max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8">
            <div>
                <h4 class="text-lg font-serif mb-4"><img src="asset/image/<?php echo htmlspecialchars($logo); ?>" alt="<?php echo htmlspecialchars($site_name); ?>" class="w-[50px]"></h4>
                <p class="text-sm text-gray-400"><?php echo htmlspecialchars($site_description); ?></p>
            </div>
            <div>
                <h4 class="text-lg font-serif mb-4">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="about.php" class="text-gray-400 hover:text-white transition">About Us</a></li>
                    <li><a href="rooms.php" class="text-gray-400 hover:text-white transition">Rooms</a></li>
                    <li><a href="food-menu.php" class="text-gray-400 hover:text-white transition">Dining</a></li>
                    <li><a href="contact.php" class="text-gray-400 hover:text-white transition">Contact</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-serif mb-4">Contact</h4>
                <ul class="space-y-2 text-sm">
                    <li class="text-gray-400"><?php echo htmlspecialchars($address); ?></li>
                    <li class="text-gray-400">Phone: <?php echo htmlspecialchars($phone); ?></li>
                    <li><a href="mailto:<?php echo htmlspecialchars($email); ?>" class="text-gray-400 hover:text-white transition"><?php echo htmlspecialchars($email); ?></a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-serif mb-4">Follow Us</h4>
                <div class="flex space-x-3 text-xl text-gray-400">
                    <?php if (!empty($facebook_link)): ?>
                        <a href="<?php echo htmlspecialchars($facebook_link); ?>" target="_blank" class="hover:text-white"><i class="fab fa-facebook-f"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($instagram_link)): ?>
                        <a href="<?php echo htmlspecialchars($instagram_link); ?>" target="_blank" class="hover:text-white"><i class="fab fa-instagram"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($twitter_link)): ?>
                        <a href="<?php echo htmlspecialchars($twitter_link); ?>" target="_blank" class="hover:text-white"><i class="fab fa-twitter"></i></a>
                    <?php endif; ?>
                     <?php if (!empty($whatsapp_link)): ?>
                        <a href="<?php echo htmlspecialchars($whatsapp_link); ?>" target="_blank" class="hover:text-white"><i class="fab fa-whatsapp"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="mt-10 pt-6 border-t border-gray-800 text-center">
            <p class="text-xs text-gray-500">&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($site_name); ?>. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>