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

$rooms = select("SELECT * FROM rooms WHERE status = 'available' ORDER BY id DESC LIMIT 3");
$foods = select("SELECT * FROM food_menu ORDER BY id DESC LIMIT 3");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($site_name); ?></title>
    <link rel="shortcut icon" href="asset/image/<?php echo htmlspecialchars($favicon); ?>" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .hero-bg {
            background-image: url('asset/image/IMG-20251129-WA0032.jpg');
            background-size: cover;
            background-position: center;
        }
        .hero-overlay {
            background-color: rgba(0, 0, 0, 0.4);
        }
    </style>
</head>
<body class="bg-white text-gray-800">

    <!-- Header -->
    <header class="h-[80vh] min-h-[500px] relative hero-bg">
        <div class="hero-overlay absolute inset-0"></div>
        <nav class="relative z-10 flex justify-between items-center py-6 px-8 md:px-16">
            <a href="index.php" class="text-xl font-serif text-white tracking-widest">
                <img src="asset/image/<?php echo htmlspecialchars($logo); ?>" alt="<?php echo htmlspecialchars($site_name); ?>" class="w-[50px]">
            </a>
            <div class="hidden md:flex space-x-8 text-sm text-white uppercase tracking-wider justify-center items-center">
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
        <div class="relative z-10 h-full flex flex-col items-center justify-center text-center p-4 pt-0 pd-[30px]">
            <h1 class="text-4xl md:text-6xl font-serif text-white mb-6"><?php echo htmlspecialchars($site_name); ?></h1>
            <p class="text-lg text-white/90 max-w-xl mb-10 hidden md:block">
                <?php echo htmlspecialchars($site_description); ?>
            </p>
            <a href="rooms.php" class="bg-yellow-600 hover:bg-yellow-700 text-black uppercase font-semibold py-3 px-8 transition duration-300 shadow-xl mb-[150px] rounded-[40px]">
                Book Your Stay
            </a>
        </div>
    </header>

    <main>
        <!-- Statistics -->
        <section class="py-12 px-4 md:px-16">
            <div class="flex flex-col md:flex-row justify-between max-w-5xl mx-auto shadow-lg bg-white rounded-lg p-6 md:p-8 -mt-20 relative z-20 border border-gray-100">
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

        <!-- Featured Rooms -->
        <section class="py-16">
            <h2 class="text-3xl font-serif text-center mb-12">Our Exquisite Rooms</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto px-4">
                <?php if ($rooms && count($rooms) > 0): ?>
                    <?php foreach ($rooms as $room): ?>
                        <div class="relative h-80 bg-gray-100 group overflow-hidden rounded-lg">
                            <img src="asset/image/<?php echo $room['image']; ?>" alt="<?php echo $room['room_name']; ?>" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-black/40 group-hover:bg-black/60 transition duration-300"></div>
                            <div class="absolute bottom-0 left-0 p-6 text-white gap-2">
                                <h3 class="text-2xl font-serif mb-1"><?php echo $room['room_name']; ?></h3>
                                <p class="text-sm font-light">₦<?php echo number_format($room['price']); ?> / night</p>
                                <a href="room-details.php?id=<?php echo $room['id']; ?>" class="mt-3 text-sm uppercase tracking-wider border-2 border-white py-2 px-4 hover:bg-white hover:text-black transition duration-300">
                                    Book Now
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class='text-center col-span-full'>No rooms available at the moment.</p>
                <?php endif; ?>
            </div>
             <div class="text-center mt-8">
                <a href="rooms.php" class="text-yellow-600 hover:underline font-semibold">View All Rooms</a>
            </div>
        </section>

        <!-- Featured Foods -->
        <section class="py-16 bg-gray-50">
            <h2 class="text-3xl font-serif text-center mb-12">Our Delicious Cuisine</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto px-4">
                <?php if ($foods && count($foods) > 0): ?>
                    <?php foreach ($foods as $food): ?>
                        <div class="relative h-80 bg-gray-100 group overflow-hidden rounded-lg">
                            <img src="asset/image/<?php echo $food['image']; ?>" alt="<?php echo $food['food_name']; ?>" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-black/40 group-hover:bg-black/60 transition duration-300"></div>
                            <div class="absolute bottom-0 left-0 p-6 text-white gap-2">
                                <h3 class="text-2xl font-serif mb-1"><?php echo $food['food_name']; ?></h3>
                                <p class="text-sm font-light mb-2">₦<?php echo number_format($food['price']); ?></p>
                                 <a href="food-menu.php" class="mt-3 text-sm uppercase tracking-wider border-2 border-white py-2 px-4 hover:bg-white hover:text-black transition duration-300">
                                    View Menu
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class='text-center col-span-full'>No food items available at the moment.</p>
                <?php endif; ?>
            </div>
            <div class="text-center mt-8">
                <a href="food-menu.php" class="text-yellow-600 hover:underline font-semibold">View Full Menu</a>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 mt-16 px-4 md:px-16">
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