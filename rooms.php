<?php
session_start();
require_once 'config/functions.php';

$rooms = []; // Refuse to show rooms

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
$rooms = select("SELECT * FROM rooms WHERE status = 'available' ORDER BY id DESC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Rooms - <?php echo htmlspecialchars($site_name); ?></title>
    <link rel="shortcut icon" href="asset/image/<?php echo htmlspecialchars($favicon); ?>" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-white text-gray-800">

    <!-- Header -->
    <header class="relative bg-gray-800 text-white py-6 px-8 md:px-16">
        <nav class="relative z-10 flex justify-between items-center">
            <a href="index.php" class="text-xl font-serif tracking-widest">
                <img src="asset/image/<?php echo htmlspecialchars($logo); ?>" alt="<?php echo htmlspecialchars($site_name); ?>" class="w-[50px]">
            </a>
            <div class="hidden md:flex space-x-8 text-sm uppercase tracking-wider justify-center items-center">
                <a href="index.php" class="hover:text-gray-300">Home</a>
                <a href="rooms.php" class="text-yellow-400">Rooms</a>
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

    <main class="py-16">
        <div class="container mx-auto px-6">
            <h1 class="text-4xl font-serif text-center mb-12">Explore Our Rooms</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php if ($rooms && count($rooms) > 0): ?>
                    <?php foreach ($rooms as $room): ?>
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden group">
                            <div class="relative h-64">
                                <img src="asset/image/<?php echo $room['image']; ?>" alt="<?php echo $room['room_name']; ?>" class="w-full h-full object-cover">
                            </div>
                            <div class="p-6">
                                <h3 class="text-2xl font-serif mb-2"><?php echo $room['room_name']; ?></h3>
                                <p class="text-lg font-semibold text-yellow-600 mb-4">₦<?php echo number_format($room['price']); ?> / night</p>
                                <p class="text-gray-600 text-sm mb-4"><?php echo substr($room['description'], 0, 100) . '...'; ?></p>
                                <a href="room-details.php?id=<?php echo $room['id']; ?>" class="inline-block bg-blue-600 text-white py-2 px-5 rounded-md hover:bg-blue-700 transition">
                                    View Details
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class='text-center col-span-full text-gray-500'>No rooms are available at the moment. Please check back later.</p>
                <?php endif; ?>
            </div>
        </div>
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