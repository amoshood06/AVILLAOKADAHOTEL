<?php
session_start();
require_once '../config/functions.php';

if (!isset($_SESSION['user']) || !isset($_SESSION['success_booking_id'])) {
    header('Location: my-bookings.php');
    exit;
}

$user = $_SESSION['user'];
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
$bookingId = $_SESSION['success_booking_id'];

// Unset the session variable to prevent revisiting this page
unset($_SESSION['success_booking_id']);
?>
<?php
$pageTitle = "Payment Successful"; //Set the page title
require_once 'partials/header_user.php'; //Include the header
?>

    <!-- Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="flex justify-between items-center p-6 bg-white border-b-2 border-gray-200">
            <h2 class="text-2xl text-gray-700 font-semibold">Payment Confirmation</h2>
        </header>

        <main class="flex-1 flex items-center justify-center bg-gray-100 p-6">
            <div class="max-w-lg mx-auto bg-white p-10 rounded-lg shadow-xl text-center">
                <div class="text-green-500 mb-4">
                    <i class="fas fa-check-circle fa-5x"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Payment Successful!</h1>
                <p class="text-gray-600 mb-6">
                    Thank you for your booking! A confirmation email has been sent to you.
                </p>
                <div class="space-y-4">
                    <a href="booking-details.php?id=<?php echo $bookingId; ?>" class="w-full inline-block bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 transition">
                        View Booking Details
                    </a>
                    <a href="dashboard.php" class="w-full inline-block bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-lg hover:bg-gray-300 transition">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </main>
    </div>
</div>

</body>
</html>