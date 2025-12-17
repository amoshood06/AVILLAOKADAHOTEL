<?php
session_start();
require_once '../config/functions.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

if (!isset($_SESSION['food_cart']) || empty($_SESSION['food_cart'])) {
    $_SESSION['error_message'] = "Your food cart is empty.";
    header('Location: ../food-menu.php'); // Redirect to food menu if cart is empty
    exit;
}

$user = $_SESSION['user'];
$settings = getSiteSettings();

// Initialize with default values for site settings
$site_name = "My Hotel";
$favicon = "favicon.ico";
$logo = "logo.png";
$currency = "USD";
$flutterwave_public_key = "";

if (is_array($settings)) {
    $site_name = $settings['site_name'] ?? $site_name;
    $favicon = $settings['favicon'] ?? $favicon;
    $logo = $settings['logo'] ?? $logo;
    $currency = $settings['currency'] ?? $currency;
    $flutterwave_public_key = $settings['flutterwave_public_key'] ?? $flutterwave_public_key;
}

$total_food_amount = 0;
foreach ($_SESSION['food_cart'] as $item) {
    $total_food_amount += ($item['price'] * $item['quantity']);
}

// Fetch room name for the selected booking ID
$booking_details = [];
$first_item_booking_id = reset($_SESSION['food_cart'])['booking_id']; // Assuming all items in cart are for the same booking

if ($first_item_booking_id) {
    $booking_details = select("SELECT r.room_name, b.id FROM bookings b JOIN rooms r ON b.room_id = r.id WHERE b.id = ?", [$first_item_booking_id], true);
}


$pageTitle = "Food Order Checkout - " . htmlspecialchars($site_name);
require_once 'partials/header_user.php';
?>

<div class="flex-1 flex flex-col overflow-hidden">
    <header class="flex justify-between items-center p-6 bg-white border-b-2 border-gray-200">
        <h2 class="text-2xl text-gray-700 font-semibold">Complete Food Order Payment</h2>
    </header>

    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
        <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold mb-4">Food Order Summary</h3>
            
            <div class="space-y-3 mb-6">
                <?php if ($booking_details): ?>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Deliver to Room:</span>
                        <span class="font-semibold"><?php echo htmlspecialchars($booking_details['room_name']); ?> (Booking #<?php echo $booking_details['id']; ?>)</span>
                    </div>
                <?php endif; ?>

                <div class="border-t pt-3 mt-3">
                    <h4 class="text-lg font-semibold mb-2">Items:</h4>
                    <?php foreach ($_SESSION['food_cart'] as $item): ?>
                        <div class="flex justify-between text-gray-700">
                            <span><?php echo htmlspecialchars($item['food_name']); ?> (x<?php echo $item['quantity']; ?>)</span>
                            <span>₦<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="flex justify-between text-2xl font-bold pt-4 border-t">
                    <span class="text-gray-800">Total Food Amount:</span>
                    <span class="text-blue-600">₦<?php echo number_format($total_food_amount, 2); ?></span>
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

<script>
function makePayment() {
  FlutterwaveCheckout({
    public_key: "<?php echo $flutterwave_public_key; ?>",
    tx_ref: "FOOD-<?php echo $user['id'] . '-' . time(); ?>", // Unique reference for food order
    amount: <?php echo $total_food_amount; ?>,
    currency: "<?php echo $currency; ?>",
    payment_options: "card,mobilemoney,ussd",
    redirect_url: "<?php echo 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/payment-callback.php'; ?>",
    meta: {
      user_id: <?php echo $user['id']; ?>,
      order_type: 'food', // Differentiate between room and food payments
      booking_id: <?php echo $first_item_booking_id; ?> // Pass the booking ID for food delivery
    },
    customer: {
      email: "<?php echo $user['email']; ?>",
      phone_number: "<?php echo $user['phone']; ?>",
      name: "<?php echo $user['full_name']; ?>",
    },
    customizations: {
      title: "<?php echo htmlspecialchars($site_name); ?>",
      description: "Payment for Food Order",
      logo: "<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/asset/image/' . $logo; ?>",
    },
  });
}
</script>

<?php require_once 'partials/footer_user.php'; ?>