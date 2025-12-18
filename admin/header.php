<?php
require_once '../config/functions.php';
initSessionConfig();
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$user = $_SESSION['user'];
$currentPage = basename($_SERVER['PHP_SELF']);

// Get site settings for logo
$settings = getSiteSettings();
$site_name = $settings['site_name'] ?? 'Okarahotel';
$logo = $settings['logo'] ?? 'logo.png';
?>
<script src="https://cdn.tailwindcss.com"></script>
<div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-white shadow-md fixed inset-y-0 left-0 z-50 transform -translate-x-full md:translate-x-0 md:static md:inset-0 transition-transform duration-300 ease-in-out">
        <div class="h-20 flex items-center justify-between px-4">
            <div class="flex-1 flex justify-center">
                <?php if (!empty($logo) && file_exists('../asset/image/' . $logo)): ?>
                    <img src="../asset/image/<?php echo htmlspecialchars($logo); ?>" alt="<?php echo htmlspecialchars($site_name); ?>" class="h-12 w-auto">
                <?php else: ?>
                    <h1 class="text-2xl font-bold text-blue-600"><?php echo htmlspecialchars($site_name); ?></h1>
                <?php endif; ?>
            </div>
            <button onclick="closeSidebar()" class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                <i class="fas fa-times fa-lg"></i>
            </button>
        </div>
        <nav class="mt-5">
            <a href="dashboard.php" class="flex items-center mt-4 py-2 px-6 <?php echo $currentPage === 'dashboard.php' ? 'bg-gray-200 text-gray-700' : 'text-gray-600 hover:bg-gray-200'; ?>">
                <i class="fas fa-th-large mr-3"></i> Dashboard
            </a>
            <a href="manage-bookings.php" class="flex items-center mt-4 py-2 px-6 <?php echo $currentPage === 'manage-bookings.php' ? 'bg-gray-200 text-gray-700' : 'text-gray-600 hover:bg-gray-200'; ?>">
                <i class="fas fa-calendar-alt mr-3"></i> Bookings
            </a>
            <a href="manage-rooms.php" class="flex items-center mt-4 py-2 px-6 <?php echo $currentPage === 'manage-rooms.php' ? 'bg-gray-200 text-gray-700' : 'text-gray-600 hover:bg-gray-200'; ?>">
                <i class="fas fa-bed mr-3"></i> Rooms
            </a>
            <a href="manage-food.php" class="flex items-center mt-4 py-2 px-6 <?php echo $currentPage === 'manage-food.php' ? 'bg-gray-200 text-gray-700' : 'text-gray-600 hover:bg-gray-200'; ?>">
                <i class="fas fa-utensils mr-3"></i> Food Menu
            </a>
            <a href="manage-food-orders.php" class="flex items-center mt-4 py-2 px-6 <?php echo $currentPage === 'manage-food-orders.php' ? 'bg-gray-200 text-gray-700' : 'text-gray-600 hover:bg-gray-200'; ?>">
                <i class="fas fa-concierge-bell mr-3"></i> Food Orders
            </a>
            <a href="manage-users.php" class="flex items-center mt-4 py-2 px-6 <?php echo $currentPage === 'manage-users.php' ? 'bg-gray-200 text-gray-700' : 'text-gray-600 hover:bg-gray-200'; ?>">
                <i class="fas fa-users mr-3"></i> Users
            </a>
            <a href="site-setting.php" class="flex items-center mt-4 py-2 px-6 <?php echo $currentPage === 'site-setting.php' ? 'bg-gray-200 text-gray-700' : 'text-gray-600 hover:bg-gray-200'; ?>">
                <i class="fas fa-cog mr-3"></i> Settings
            </a>
            <a href="../logout.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-sign-out-alt mr-3"></i> Logout
            </a>
        </nav>
    </aside>

    <!-- Mobile overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden" onclick="closeSidebar()"></div>

    <!-- Content -->
    <div class="flex-1 flex flex-col overflow-hidden md:ml-0">
        <header class="flex justify-between items-center p-6 bg-white border-b-2 border-gray-200">
            <div class="flex items-center">
                <button onclick="toggleSidebar()" class="md:hidden text-gray-500 focus:outline-none mr-4">
                    <i class="fas fa-bars fa-2x"></i>
                </button>
                <h2 class="text-2xl text-gray-700 font-semibold"><?php echo htmlspecialchars($pageTitle ?? 'Admin Panel'); ?></h2>
            </div>
            <div class="flex items-center">
                <span class="text-gray-600 mr-2">Welcome, <?php echo htmlspecialchars($user['full_name']); ?></span>
                <i class="fas fa-user-circle fa-2x text-gray-500"></i>
            </div>
        </header>
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">