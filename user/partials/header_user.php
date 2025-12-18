<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user = select("SELECT * FROM users WHERE id = ?", [$user_id], true); // Fetch user data

// Fetch site settings
$settings = getSiteSettings();
$site_name = $settings['site_name'] ?? 'Hotel Booking System';
$favicon = $settings['favicon'] ?? 'favicon.ico';
$logo = $settings['logo'] ?? 'logo.png'; // Assuming logo is also in settings

// Define page title, can be overridden by individual pages
if (!isset($pageTitle)) {
    $pageTitle = "User Panel";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle) . " - " . htmlspecialchars($site_name); ?></title>
    <link rel="shortcut icon" href="../asset/image/<?php echo htmlspecialchars($favicon); ?>" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Add any custom styles needed for user pages */
        /* Ensure sidebar scrolling works properly on mobile */
        #sidebar {
            max-height: 100vh;
        }
        #sidebar nav {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e0 #f7fafc;
        }
        #sidebar nav::-webkit-scrollbar {
            width: 6px;
        }
        #sidebar nav::-webkit-scrollbar-track {
            background: #f7fafc;
        }
        #sidebar nav::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 3px;
        }
        #sidebar nav::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }
    </style>
</head>
<body class="bg-gray-100 flex">

    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-white shadow-md fixed inset-y-0 left-0 z-30 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out flex flex-col">
        <!-- Fixed Header Section -->
        <div class="flex-shrink-0">
            <div class="h-20 flex items-center justify-center relative">
                <?php if (!empty($logo) && file_exists('../asset/image/' . $logo)): ?>
                    <img src="../asset/image/<?php echo htmlspecialchars($logo); ?>" alt="<?php echo htmlspecialchars($site_name); ?>" class="h-12 w-auto">
                <?php else: ?>
                    <h1 class="text-2xl font-bold text-blue-600"><?php echo htmlspecialchars($site_name); ?></h1>
                <?php endif; ?>
                <!-- Close button for mobile -->
                <button id="sidebar-close" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 md:hidden">
                    <i class="fas fa-times fa-lg"></i>
                </button>
            </div>
            <div class="p-4 text-center border-b border-gray-200">
                <?php
                $profilePicture = !empty($user['profile_picture']) ? '../asset/image/users/' . htmlspecialchars($user['profile_picture']) : '../asset/image/av1.png'; // Default image if none
                ?>
                <img src="<?php echo $profilePicture; ?>" alt="User Avatar" class="w-24 h-24 rounded-full mx-auto object-cover border-2 border-blue-400">
                <p class="mt-2 text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($user['full_name'] ?? 'Guest'); ?></p>
                <p class="text-sm text-gray-500"><?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
            </div>
        </div>

        <!-- Scrollable Navigation Section -->
        <nav class="flex-1 overflow-y-auto mt-5 px-2">
            <a href="dashboard.php" class="flex items-center py-3 px-4 text-gray-600 hover:bg-gray-200 rounded-lg transition duration-200">
                <i class="fas fa-th-large mr-3"></i> Dashboard
            </a>
            <a href="my-bookings.php" class="flex items-center py-3 px-4 text-gray-600 hover:bg-gray-200 rounded-lg transition duration-200">
                <i class="fas fa-calendar-alt mr-3"></i> My Bookings
            </a>
            <a href="profile.php" class="flex items-center py-3 px-4 text-gray-600 hover:bg-gray-200 rounded-lg transition duration-200">
                <i class="fas fa-user mr-3"></i> Profile
            </a>
            <a href="add-to-cart.php" class="flex items-center py-3 px-4 text-gray-600 hover:bg-gray-200 rounded-lg transition duration-200">
                <i class="fas fa-cart-plus mr-3"></i> Add to Cart
            </a>
            <a href="book-room.php" class="flex items-center py-3 px-4 text-gray-600 hover:bg-gray-200 rounded-lg transition duration-200">
                <i class="fas fa-bed mr-3"></i> Book Room
            </a>
            <a href="booking-details.php" class="flex items-center py-3 px-4 text-gray-600 hover:bg-gray-200 rounded-lg transition duration-200">
                <i class="fas fa-book-open mr-3"></i> Booking Details
            </a>
            <a href="my-foods.php" class="flex items-center py-3 px-4 text-gray-600 hover:bg-gray-200 rounded-lg transition duration-200">
                <i class="fas fa-hamburger mr-3"></i> Order Foods
            </a>
            <a href="payment.php" class="flex items-center py-3 px-4 text-gray-600 hover:bg-gray-200 rounded-lg transition duration-200">
                <i class="fas fa-credit-card mr-3"></i> Payment
            </a>
            <a href="../logout.php" class="flex items-center py-3 px-4 text-gray-600 hover:bg-gray-200 rounded-lg transition duration-200">
                <i class="fas fa-sign-out-alt mr-3"></i> Logout
            </a>
        </nav>
    </aside>

    <!-- Mobile overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden md:hidden" onclick="closeSidebar()"></div>

    <!-- Content Wrapper -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="flex justify-between items-center p-6 bg-white border-b-2 border-gray-200">
            <div class="flex items-center">
                <button id="sidebar-toggle" class="text-gray-500 focus:outline-none">
                    <i class="fas fa-bars fa-2x"></i>
                </button>
                <h2 class="text-2xl text-gray-700 font-semibold ml-4"><?php echo htmlspecialchars($pageTitle); ?></h2>
            </div>
            <!-- You can add user-specific header elements here, e.g., welcome message, notifications -->
        </header>
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            <div class="max-w-7xl mx-auto">
