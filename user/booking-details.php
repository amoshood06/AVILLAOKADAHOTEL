<?php
require_once '../config/functions.php';
initSessionConfig();
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

if (!isset($_GET['id'])) {
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
$bookingId = $_GET['id'];

// Ensure the booking belongs to the logged-in user
$booking = select(
    "SELECT b.*, r.room_name, r.price as room_price FROM bookings b JOIN rooms r ON b.room_id = r.id WHERE b.id = ? AND b.user_id = ?",
    [$bookingId, $user['id']],
    true
);

if (!$booking) {
    header('Location: my-bookings.php');
    exit;
}

$bookingFoods = select(
    "SELECT fm.food_name, fm.price, bf.quantity FROM booking_foods bf JOIN food_menu fm ON bf.food_id = fm.id WHERE bf.booking_id = ?",
    [$bookingId]
);

?>
<?php
$pageTitle = "Booking Details"; //Set the page title
require_once 'partials/header_user.php'; //Include the header
?>

    <!-- Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="flex justify-between items-center p-6 bg-white border-b-2 border-gray-200">
            <h2 class="text-2xl text-gray-700 font-semibold">Booking Details</h2>
             <a href="my-bookings.php" class="text-blue-500 hover:underline">&larr; Back to My Bookings</a>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            <div class="bg-white p-8 rounded-lg shadow-md max-w-4xl mx-auto">
                 <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl text-gray-800 font-bold">Booking #<?php echo $booking['id']; ?></h3>
                    <span class="px-3 py-1 font-semibold leading-tight <?php echo $booking['payment_status'] === 'paid' ? 'text-green-700 bg-green-100' : 'text-yellow-700 bg-yellow-100'; ?> rounded-full">
                        <?php echo ucfirst($booking['payment_status']); ?>
                    </span>
                </div>

                 <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-700 mb-2">Booking Information</h4>
                        <p><strong>Room:</strong> <?php echo htmlspecialchars($booking['room_name']); ?></p>
                        <p><strong>Check-in:</strong> <?php echo date("F j, Y", strtotime($booking['check_in'])); ?></p>
                        <p><strong>Check-out:</strong> <?php echo date("F j, Y", strtotime($booking['check_out'])); ?></p>
                    </div>
                </div>

                <div class="mt-8">
                    <h4 class="text-lg font-semibold text-gray-700 mb-2">Cost Breakdown</h4>
                    <div class="border rounded-lg overflow-hidden">
                        <table class="min-w-full bg-white">
                             <thead class="bg-gray-200">
                                <tr>
                                    <th class="text-left py-2 px-4">Item</th>
                                    <th class="text-right py-2 px-4">Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="py-2 px-4">Room Charge (<?php
                                        $checkin = new DateTime($booking['check_in']);
                                        $checkout = new DateTime($booking['check_out']);
                                        $nights = $checkout->diff($checkin)->days;
                                        echo $nights . ' night(s)';
                                    ?>)</td>
                                    <td class="text-right py-2 px-4">$<?php echo number_format($booking['room_price'] * $nights, 2); ?></td>
                                </tr>
                                <?php if (!empty($bookingFoods)): ?>
                                    <tr><td colspan="2" class="py-2 px-4 font-semibold text-gray-600">Food Orders</td></tr>
                                    <?php foreach ($bookingFoods as $food): ?>
                                        <tr>
                                            <td class="py-2 px-4"><?php echo htmlspecialchars($food['food_name']) . ' (x' . $food['quantity'] . ')'; ?></td>
                                            <td class="text-right py-2 px-4">$<?php echo number_format($food['price'] * $food['quantity'], 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                            <tfoot class="font-bold">
                                <tr>
                                    <td class="py-3 px-4 text-right">Total Amount</td>
                                    <td class="py-3 px-4 text-right text-xl text-blue-600">$<?php echo number_format($booking['total_amount'], 2); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <?php if ($booking['payment_status'] === 'pending'): ?>
                <div class="mt-8 text-center">
                    <a href="payment.php?booking_id=<?php echo $booking['id']; ?>" class="bg-green-500 text-white font-bold py-3 px-6 rounded-lg hover:bg-green-600 transition shadow-lg">
                        Proceed to Payment
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </main>
<?php
require_once 'partials/footer_user.php'; // Include the footer
?>