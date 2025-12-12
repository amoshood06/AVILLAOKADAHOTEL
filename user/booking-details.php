<?php
session_start();
require_once '../config/functions.php';

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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details - <?php echo htmlspecialchars($site_name); ?></title>
    <link rel="shortcut icon" href="../asset/image/<?php echo htmlspecialchars($favicon); ?>" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">

<div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md">
        <div class="h-20 flex items-center justify-center">
            <h1 class="text-2xl font-bold text-blue-600"><?php echo htmlspecialchars($site_name); ?></h1>
        </div>
        <nav class="mt-5">
            <a href="dashboard.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-th-large mr-3"></i> Dashboard
            </a>
            <a href="my-bookings.php" class="flex items-center mt-4 py-2 px-6 bg-gray-200 text-gray-700">
                <i class="fas fa-calendar-alt mr-3"></i> My Bookings
            </a>
            <a href="profile.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-user mr-3"></i> Profile
            </a>
             <a href="reward-points.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-gift mr-3"></i> Reward Points
            </a>
            <a href="../logout.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-sign-out-alt mr-3"></i> Logout
            </a>
        </nav>
    </aside>

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
    </div>
</div>

</body>
</html>