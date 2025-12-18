<?php
require_once '../config/functions.php';
initSessionConfig();
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

if (!isset($_GET['booking_id'])) {
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
$currency = "USD";
$flutterwave_public_key = "";


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
    $currency = $settings['currency'] ?? $currency;
    $flutterwave_public_key = $settings['flutterwave_public_key'] ?? $flutterwave_public_key;
}
$bookingId = $_GET['booking_id'];

// Verify booking belongs to user and is pending
$booking = select(
    "SELECT b.*, r.room_name FROM bookings b JOIN rooms r ON b.room_id = r.id WHERE b.id = ? AND b.user_id = ? AND b.payment_status = 'pending'",
    [$bookingId, $user['id']],
    true
);

if (!$booking) {
    // Redirect if booking not found, not pending, or doesn't belong to user
    header('Location: my-bookings.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Payment - <?php echo htmlspecialchars($site_name); ?></title>
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
            <h2 class="text-2xl text-gray-700 font-semibold">Complete Your Payment</h2>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-4">Payment Summary</h3>
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Booking for:</span>
                        <span class="font-semibold"><?php echo htmlspecialchars($booking['room_name']); ?></span>
                    </div>
                     <div class="flex justify-between">
                        <span class="text-gray-600">Check-in:</span>
                        <span class="font-semibold"><?php echo date("F j, Y", strtotime($booking['check_in'])); ?></span>
                    </div>
                     <div class="flex justify-between">
                        <span class="text-gray-600">Check-out:</span>
                        <span class="font-semibold"><?php echo date("F j, Y", strtotime($booking['check_out'])); ?></span>
                    </div>
                    <div class="flex justify-between text-2xl font-bold pt-4 border-t">
                        <span class="text-gray-800">Total Amount:</span>
                        <span class="text-blue-600">$<?php echo number_format($booking['total_amount'], 2); ?></span>
                    </div>
                </div>

                <div class="text-center">
                    <form>
                        <script src="https://checkout.flutterwave.com/v3.js"></script>
                        <button type="button" onClick="makePayment()" class="w-full bg-green-500 text-white font-bold py-3 px-6 rounded-lg hover:bg-green-600 transition shadow-lg text-lg">
                            Pay with Flutterwave
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
function makePayment() {
  FlutterwaveCheckout({
    public_key: "<?php echo $flutterwave_public_key; ?>",
    tx_ref: "ref-<?php echo $bookingId . '-' . time(); ?>",
    amount: <?php echo $booking['total_amount']; ?>,
    currency: "<?php echo $currency; ?>",
    payment_options: "card,mobilemoney,ussd",
    redirect_url: "<?php echo 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/payment-callback.php'; ?>",
    meta: {
      booking_id: <?php echo $bookingId; ?>,
      user_id: <?php echo $user['id']; ?>,
    },
    customer: {
      email: "<?php echo $user['email']; ?>",
      phone_number: "<?php echo $user['phone']; ?>",
      name: "<?php echo $user['full_name']; ?>",
    },
    customizations: {
      title: "<?php echo htmlspecialchars($site_name); ?>",
      description: "Payment for Booking #<?php echo $bookingId; ?>",
      logo: "<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/asset/image/' . $logo; ?>",
    },
  });
}
</script>

</body>
</html>