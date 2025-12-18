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


// Fetch all food orders made by the user, grouped by booking
$userFoodOrders = select(
    "SELECT 
        b.id as booking_id,
        b.check_in,
        b.check_out,
        r.room_name,
        bf.quantity,
        fm.food_name,
        fm.price,
        (bf.quantity * fm.price) as item_total
    FROM booking_foods bf
    JOIN bookings b ON bf.booking_id = b.id
    JOIN rooms r ON b.room_id = r.id
    JOIN food_menu fm ON bf.food_id = fm.id
    WHERE b.user_id = ?
    ORDER BY b.created_at DESC, fm.food_name ASC",
    [$user_id]
);

$groupedFoodOrders = [];
foreach ($userFoodOrders as $order) {
    $booking_id = $order['booking_id'];
    if (!isset($groupedFoodOrders[$booking_id])) {
        $groupedFoodOrders[$booking_id] = [
            'booking_id' => $booking_id,
            'room_name' => $order['room_name'],
            'check_in' => $order['check_in'],
            'check_out' => $order['check_out'],
            'items' => [],
            'booking_total_food_cost' => 0
        ];
    }
    $groupedFoodOrders[$booking_id]['items'][] = $order;
    $groupedFoodOrders[$booking_id]['booking_total_food_cost'] += $order['item_total'];
}

$cart_total_items = 0;
$cart_total_amount = 0;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_total_items += $item['quantity'];
        $cart_total_amount += ($item['price'] * $item['quantity']);
    }
}

$pageTitle = "My Cart & Orders";
require_once 'partials/header_user.php';
?>

<div class="flex-1 flex flex-col overflow-hidden">
    <header class="flex justify-between items-center p-6 bg-white border-b-2 border-gray-200">
        <h2 class="text-2xl text-gray-700 font-semibold">My Cart & Orders</h2>
        <a href="../food-menu.php" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
            <i class="fas fa-plus mr-2"></i> Order More Food
        </a>
    </header>

    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
        <div class="max-w-4xl mx-auto">
            <!-- Current Cart Section -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Current Cart</h3>
                <?php if (!empty($_SESSION['cart'])): ?>
                    <div class="overflow-x-auto mb-4">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100 text-gray-600">
                                <tr>
                                    <th class="text-left py-2 px-3 uppercase font-semibold text-sm">Food Item</th>
                                    <th class="text-left py-2 px-3 uppercase font-semibold text-sm">Quantity</th>
                                    <th class="text-left py-2 px-3 uppercase font-semibold text-sm">Price</th>
                                    <th class="text-left py-2 px-3 uppercase font-semibold text-sm">Total</th>
                                    <th class="text-left py-2 px-3 uppercase font-semibold text-sm">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                <?php foreach ($_SESSION['cart'] as $food_id => $item): ?>
                                    <tr>
                                        <td class="text-left py-2 px-3"><?php echo htmlspecialchars($item['name']); ?></td>
                                        <td class="text-left py-2 px-3"><?php echo htmlspecialchars($item['quantity']); ?></td>
                                        <td class="text-left py-2 px-3">₦<?php echo number_format($item['price'], 2); ?></td>
                                        <td class="text-left py-2 px-3">₦<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                        <td class="py-2 px-3">
                                            <a href="remove-from-cart.php?food_id=<?php echo $food_id; ?>" class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="font-bold border-t border-gray-300">
                                    <td colspan="3" class="text-right py-2 px-3">Cart Total:</td>
                                    <td class="text-left py-2 px-3">₦<?php echo number_format($cart_total_amount, 2); ?></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="flex justify-end gap-4">
                        <a href="food-checkout.php" class="bg-green-500 text-white px-6 py-2 rounded-md hover:bg-green-600">
                            <i class="fas fa-money-bill-wave mr-2"></i> Proceed to Payment
                        </a>
                    </div>
                <?php else: ?>
                    <p class="text-center text-gray-500">Your cart is empty. <a href="../food-menu.php" class="text-blue-500 hover:underline">Browse our menu</a> to add items.</p>
                <?php endif; ?>
            </div>

            <!-- Past Food Orders Section -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Past Food Orders</h3>
                <?php if (!empty($groupedFoodOrders)): ?>
                    <?php foreach ($groupedFoodOrders as $booking_id => $bookingData): ?>
                        <div class="p-4 border rounded-lg mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="text-lg font-semibold text-gray-700">Booking #<?php echo htmlspecialchars($bookingData['booking_id']); ?> - Room: <?php echo htmlspecialchars($bookingData['room_name']); ?></h4>
                                <span class="text-sm text-gray-600">Check-in: <?php echo date("M j, Y", strtotime($bookingData['check_in'])); ?> | Check-out: <?php echo date("M j, Y", strtotime($bookingData['check_out'])); ?></span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead class="bg-gray-100 text-gray-600">
                                        <tr>
                                            <th class="text-left py-2 px-3 uppercase font-semibold text-sm">Food Item</th>
                                            <th class="text-left py-2 px-3 uppercase font-semibold text-sm">Quantity</th>
                                            <th class="text-left py-2 px-3 uppercase font-semibold text-sm">Price</th>
                                            <th class="text-left py-2 px-3 uppercase font-semibold text-sm">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-700">
                                        <?php foreach ($bookingData['items'] as $item): ?>
                                            <tr>
                                                <td class="text-left py-2 px-3"><?php echo htmlspecialchars($item['food_name']); ?></td>
                                                <td class="text-left py-2 px-3"><?php echo htmlspecialchars($item['quantity']); ?></td>
                                                <td class="text-left py-2 px-3">₦<?php echo number_format($item['price'], 2); ?></td>
                                                <td class="text-left py-2 px-3">₦<?php echo number_format($item['item_total'], 2); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr class="font-bold border-t border-gray-300">
                                            <td colspan="3" class="text-right py-2 px-3">Total for this Booking:</td>
                                            <td class="text-left py-2 px-3">₦<?php echo number_format($bookingData['booking_total_food_cost'], 2); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center text-gray-500">You have no past food orders. <a href="../food-menu.php" class="text-blue-500 hover:underline">Browse our menu</a>.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

<?php require_once 'partials/footer_user.php'; ?>
