<?php
session_start();
require_once '../config/functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user = select("SELECT * FROM users WHERE id = ?", [$user_id], true);
$user_name = $user['full_name']; // Use full_name from the fetched user data

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

// Fetch user statistics
$totalBookings = select("SELECT COUNT(*) as total FROM bookings WHERE user_id = ?", [$user_id], true)['total'];
$totalFoodOrders = select("SELECT COUNT(DISTINCT bf.booking_id) as total FROM booking_foods bf JOIN bookings b ON bf.booking_id = b.id WHERE b.user_id = ?", [$user_id], true)['total'];
$rewardPoints = $user['reward_points'];
$recentBooking = select(
    "SELECT b.*, r.room_name FROM bookings b JOIN rooms r ON b.room_id = r.id WHERE b.user_id = ? ORDER BY b.created_at DESC LIMIT 1",
    [$user_id],
    true
);

?>
<?php
$pageTitle = "My Dashboard"; //Set the page title
require_once 'partials/header_user.php'; //Include the header
?>

    <!-- Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="flex justify-between items-center p-6 bg-white border-b-2 border-gray-200">
            <h2 class="text-2xl text-gray-700 font-semibold">Dashboard</h2>
            <div class="flex items-center">
                <span class="text-gray-600 mr-2">Welcome, <?php echo htmlspecialchars($user_name); ?></span>
                <i class="fas fa-user-circle fa-2x text-gray-500"></i>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <i class="fas fa-calendar-check fa-3x text-green-500"></i>
                        <div class="ml-4">
                            <p class="text-lg text-gray-600">Total Bookings</p>
                            <p class="text-2xl font-bold text-gray-800"><?php echo $totalBookings; ?></p>
                            <a href="my-bookings.php" class="text-blue-500 text-sm hover:underline">View All Bookings</a>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <i class="fas fa-hamburger fa-3x text-red-500"></i>
                        <div class="ml-4">
                            <p class="text-lg text-gray-600">Total Food Orders</p>
                            <p class="text-2xl font-bold text-gray-800"><?php echo $totalFoodOrders; ?></p>
                            <a href="my-foods.php" class="text-blue-500 text-sm hover:underline">View All Orders</a>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <i class="fas fa-gift fa-3x text-yellow-500"></i>
                        <div class="ml-4">
                            <p class="text-lg text-gray-600">Reward Points</p>
                            <p class="text-2xl font-bold text-gray-800"><?php echo $rewardPoints; ?></p>
                            <a href="reward-points.php" class="text-blue-500 text-sm hover:underline">Manage Points</a>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <i class="fas fa-user fa-3x text-blue-500"></i>
                        <div class="ml-4">
                            <p class="text-lg text-gray-600">My Profile</p>
                            <p class="text-2xl font-bold text-gray-800">Edit Details</p>
                            <a href="profile.php" class="text-blue-500 text-sm hover:underline">Update Profile</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Booking -->
            <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl text-gray-700 font-semibold mb-4">Most Recent Booking</h3>
                <?php if ($recentBooking): ?>
                    <div class="border rounded-lg p-4">
                        <h4 class="text-lg font-semibold"><?php echo htmlspecialchars($recentBooking['room_name']); ?></h4>
                        <p class="text-gray-600">
                            Check-in: <?php echo date("M j, Y", strtotime($recentBooking['check_in'])); ?> | 
                            Check-out: <?php echo date("M j, Y", strtotime($recentBooking['check_out'])); ?>
                        </p>
                        <p class="mt-2">
                            Total: <span class="font-bold">$<?php echo number_format($recentBooking['total_amount'], 2); ?></span>
                        </p>
                        <p>
                            Status: 
                            <span class="px-2 py-1 text-sm font-semibold leading-tight <?php echo $recentBooking['payment_status'] === 'paid' ? 'text-green-700 bg-green-100' : 'text-yellow-700 bg-yellow-100'; ?> rounded-sm">
                                <?php echo ucfirst($recentBooking['payment_status']); ?>
                            </span>
                        </p>
                        <div class="mt-4">
                            <a href="booking-details.php?id=<?php echo $recentBooking['id']; ?>" class="text-blue-500 hover:underline">View Details</a>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-center text-gray-500">You have no bookings yet. <a href="../rooms.php" class="text-blue-500 hover:underline">Make a booking</a>.</p>
                <?php endif; ?>
            </div>
        </main>
<?php
require_once 'partials/footer_user.php'; // Include the footer
?>