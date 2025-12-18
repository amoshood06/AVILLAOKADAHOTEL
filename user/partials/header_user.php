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
    </style>
</head>
<body class="bg-gray-100 flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out z-20">
        <div class="h-20 flex items-center justify-center">
            <h1 class="text-2xl font-bold text-blue-600"><?php echo htmlspecialchars($site_name); ?></h1>
        </div>
        <div class="p-4 text-center border-b border-gray-200">
            <?php
            $profilePicture = !empty($user['profile_picture']) ? '../asset/image/users/' . htmlspecialchars($user['profile_picture']) : '../asset/image/av1.png'; // Default image if none
            ?>
            <img src="<?php echo $profilePicture; ?>" alt="User Avatar" class="w-24 h-24 rounded-full mx-auto object-cover border-2 border-blue-400">
            <p class="mt-2 text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($user['full_name'] ?? 'Guest'); ?></p>
            <p class="text-sm text-gray-500"><?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
        </div>
        <nav class="mt-5">
            <a href="dashboard.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-th-large mr-3"></i> Dashboard
            </a>
            <a href="my-bookings.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-calendar-alt mr-3"></i> My Bookings
            </a>
            <a href="profile.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-user mr-3"></i> Profile
            </a>

            <a href="add-to-cart.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-cart-plus mr-3"></i> Add to Cart
            </a>
            <a href="book-room.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-bed mr-3"></i> Book Room
            </a>
            <a href="booking-details.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-book-open mr-3"></i> Booking Details
            </a>
            <a href="my-foods.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-hamburger mr-3"></i> Order Foods
            </a>
            <a href="payment.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-credit-card mr-3"></i> Payment
            </a>
            <a href="../logout.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-sign-out-alt mr-3"></i> Logout
            </a>
        </nav>
    </aside>

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
