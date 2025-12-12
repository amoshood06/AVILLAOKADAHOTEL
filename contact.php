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
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // A simple mail function, for a real application use a library like PHPMailer
    $to = $email;
    $from = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $name = htmlspecialchars($_POST['name']);
    $subject = "Contact Form Submission from " . $name;
    $body = htmlspecialchars($_POST['message']);
    $headers = "From: " . $from;

    if (mail($to, $subject, $body, $headers)) {
        $message = "Your message has been sent successfully!";
    } else {
        $message = "Failed to send message. Please try again later.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - <?php echo htmlspecialchars($site_name); ?></title>
    <link rel="shortcut icon" href="asset/image/<?php echo htmlspecialchars($favicon); ?>" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                <a href="contact.php" class="text-yellow-400">Contact</a>
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
            <div class="text-center mb-12">
                <h1 class="text-4xl font-serif">Get in Touch</h1>
                <p class="text-gray-600 mt-2">We'd love to hear from you. Here's how you can reach us.</p>
            </div>

            <?php if ($message): ?>
                <div class="mb-8 p-4 text-center <?php echo strpos($message, 'success') !== false ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?> rounded-lg">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <div class="flex flex-wrap -mx-4">
                <div class="w-full lg:w-1/2 px-4 mb-8 lg:mb-0">
                    <div class="bg-white p-8 rounded-lg shadow-lg">
                        <h2 class="text-2xl font-semibold mb-6">Contact Form</h2>
                        <form action="contact.php" method="POST">
                            <div class="mb-4">
                                <label for="name" class="block text-gray-700 font-medium mb-2">Your Name</label>
                                <input type="text" id="name" name="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div class="mb-4">
                                <label for="email" class="block text-gray-700 font-medium mb-2">Your Email</label>
                                <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div class="mb-6">
                                <label for="message" class="block text-gray-700 font-medium mb-2">Message</label>
                                <textarea id="message" name="message" rows="5" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                            </div>
                            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition font-semibold">Send Message</button>
                        </form>
                    </div>
                </div>
                <div class="w-full lg:w-1/2 px-4">
                     <div class="bg-white p-8 rounded-lg shadow-lg h-full">
                        <h2 class="text-2xl font-semibold mb-6">Contact Information</h2>
                        <ul class="space-y-4 text-gray-700">
                            <li class="flex items-start">
                                <i class="fas fa-map-marker-alt fa-fw text-blue-500 mt-1 mr-3"></i>
                                <span><?php echo htmlspecialchars($address); ?></span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-phone-alt fa-fw text-blue-500 mr-3"></i>
                                <span><?php echo htmlspecialchars($phone); ?></span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-envelope fa-fw text-blue-500 mr-3"></i>
                                <a href="mailto:<?php echo htmlspecialchars($email); ?>" class="hover:text-blue-600"><?php echo htmlspecialchars($email); ?></a>
                            </li>
                        </ul>
                         <div class="mt-8">
                             <h3 class="text-lg font-semibold mb-4">Follow Us</h3>
                             <div class="flex space-x-4 text-2xl text-gray-600">
                                 <?php if (!empty($facebook_link)): ?>
                                    <a href="<?php echo htmlspecialchars($facebook_link); ?>" target="_blank" class="hover:text-blue-600"><i class="fab fa-facebook-f"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($instagram_link)): ?>
                                    <a href="<?php echo htmlspecialchars($instagram_link); ?>" target="_blank" class="hover:text-pink-600"><i class="fab fa-instagram"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($twitter_link)): ?>
                                    <a href="<?php echo htmlspecialchars($twitter_link); ?>" target="_blank" class="hover:text-sky-500"><i class="fab fa-twitter"></i></a>
                                <?php endif; ?>
                                 <?php if (!empty($whatsapp_link)): ?>
                                    <a href="<?php echo htmlspecialchars($whatsapp_link); ?>" target="_blank" class="hover:text-green-500"><i class="fab fa-whatsapp"></i></a>
                                <?php endif; ?>
                             </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
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