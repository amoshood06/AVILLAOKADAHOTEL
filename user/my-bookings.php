<?php
require_once '../config/functions.php';
initSessionConfig();
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
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

$bookings = select(
    "SELECT b.*, r.room_name FROM bookings b JOIN rooms r ON b.room_id = r.id WHERE b.user_id = ? ORDER BY b.created_at DESC",
    [$user_id]
);

?>
<?php
$pageTitle = "My Bookings"; //Set the page title
require_once 'partials/header_user.php'; //Include the header
?>

    <!-- Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="flex justify-between items-center p-6 bg-white border-b-2 border-gray-200">
            <h2 class="text-2xl text-gray-700 font-semibold">My Bookings</h2>
             <a href="../rooms.php" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                <i class="fas fa-plus mr-2"></i> New Booking
            </a>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            <?php if (isset($_SESSION['booking_success'])): ?>
                <div class="mb-4 p-3 text-sm bg-green-100 text-green-700 rounded-lg">
                    <?php echo $_SESSION['booking_success']; ?>
                </div>
                <?php unset($_SESSION['booking_success']); ?>
            <?php endif; ?>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Room</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Dates</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Total</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Status</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <?php if (!empty($bookings)): ?>
                                <?php foreach ($bookings as $booking): ?>
                                    <tr>
                                        <td class="text-left py-3 px-4"><?php echo htmlspecialchars($booking['room_name']); ?></td>
                                        <td class="text-left py-3 px-4">
                                            <?php echo date("M j, Y", strtotime($booking['check_in'])); ?> - 
                                            <?php echo date("M j, Y", strtotime($booking['check_out'])); ?>
                                        </td>
                                        <td class="text-left py-3 px-4">$<?php echo number_format($booking['total_amount'], 2); ?></td>
                                        <td class="text-left py-3 px-4">
                                            <span class="px-2 py-1 font-semibold leading-tight <?php echo $booking['payment_status'] === 'paid' ? 'text-green-700 bg-green-100' : 'text-yellow-700 bg-yellow-100'; ?> rounded-sm">
                                                <?php echo ucfirst($booking['payment_status']); ?>
                                            </span>
                                        </td>
                                        <td class="text-left py-3 px-4">
                                            <a href="booking-details.php?id=<?php echo $booking['id']; ?>" class="text-blue-500 hover:text-blue-700 mr-4">View</a>
                                            <?php if ($booking['payment_status'] === 'pending'): ?>
                                                <a href="payment.php?booking_id=<?php echo $booking['id']; ?>" class="bg-green-500 text-white px-3 py-1 rounded-md hover:bg-green-600 text-sm">Pay Now</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">You have no bookings.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
<?php
require_once 'partials/footer_user.php'; // Include the footer
?>