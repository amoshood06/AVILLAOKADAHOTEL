<?php
require_once '../config/functions.php';
initSessionConfig();
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$user = $_SESSION['user'];

// Handle delivery status update
if (isset($_POST['update_delivery'])) {
    $foodOrderId = $_POST['food_order_id'];
    $deliveryStatus = $_POST['delivery_status'];

    execute("UPDATE booking_foods SET delivery_status = ? WHERE id = ?", [$deliveryStatus, $foodOrderId]);
    $_SESSION['success_message'] = "Delivery status updated successfully!";
    header('Location: manage-food-orders.php');
    exit;
}

// Fetch food orders with delivery status
$foodOrders = select("
    SELECT bf.*, fm.food_name, fm.price, b.id as booking_id, r.room_name, u.full_name as customer_name
    FROM booking_foods bf
    JOIN food_menu fm ON bf.food_id = fm.id
    JOIN bookings b ON bf.booking_id = b.id
    JOIN rooms r ON b.room_id = r.id
    JOIN users u ON b.user_id = u.id
    ORDER BY bf.id DESC
", []);

$pageTitle = "Manage Food Orders - Okarahotel";
require_once 'header.php';
?>

<div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Manage Food Orders</h1>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Food Orders & Delivery Status</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Food Item</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Delivery Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if ($foodOrders && count($foodOrders) > 0): ?>
                        <?php foreach ($foodOrders as $order): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #<?php echo $order['id']; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo htmlspecialchars($order['customer_name']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo htmlspecialchars($order['room_name']); ?> (Booking #<?php echo $order['booking_id']; ?>)
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo htmlspecialchars($order['food_name']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo $order['quantity']; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ₦<?php echo number_format($order['price'] * $order['quantity'], 2); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        <?php echo $order['delivery_status'] === 'delivered' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                        <?php echo ucfirst($order['delivery_status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="food_order_id" value="<?php echo $order['id']; ?>">
                                        <select name="delivery_status" onchange="this.form.submit()" class="text-sm border rounded px-2 py-1">
                                            <option value="pending" <?php echo $order['delivery_status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="delivered" <?php echo $order['delivery_status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                        </select>
                                        <input type="hidden" name="update_delivery" value="1">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                No food orders found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>