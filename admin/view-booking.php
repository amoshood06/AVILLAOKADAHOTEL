<?php
session_start();
require_once '../config/functions.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$user = $_SESSION['user'];

if (!isset($_GET['id'])) {
    header('Location: manage-bookings.php');
    exit;
}

$bookingId = $_GET['id'];

// Fetch booking details
$booking = select(
    "SELECT b.*, u.full_name, u.email, u.phone, r.room_name, r.price as room_price, r.image as room_image
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    JOIN rooms r ON b.room_id = r.id
    WHERE b.id = ?",
    [$bookingId],
    true
);

if (!$booking) {
    header('Location: manage-bookings.php');
    exit;
}

// Fetch food orders for this booking
$foodOrders = select(
    "SELECT bf.quantity, fm.food_name, fm.price, (bf.quantity * fm.price) as total_item_price
    FROM booking_foods bf
    JOIN food_menu fm ON bf.food_id = fm.id
    WHERE bf.booking_id = ?",
    [$bookingId]
);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Booking - Okarahotel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">

<div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md">
        <div class="h-20 flex items-center justify-center">
            <h1 class="text-2xl font-bold text-blue-600">Okarahotel</h1>
        </div>
        <nav class="mt-5">
            <a href="dashboard.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-th-large mr-3"></i> Dashboard
            </a>
            <a href="manage-bookings.php" class="flex items-center mt-4 py-2 px-6 bg-gray-200 text-gray-700">
                <i class="fas fa-calendar-alt mr-3"></i> Bookings
            </a>
            <a href="manage-rooms.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-bed mr-3"></i> Rooms
            </a>
            <a href="manage-food.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-utensils mr-3"></i> Food Menu
            </a>
            <a href="manage-users.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-users mr-3"></i> Users
            </a>
            <a href="site-setting.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-cog mr-3"></i> Settings
            </a>
            <a href="../logout.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-sign-out-alt mr-3"></i> Logout
            </a>
        </nav>
    </aside>

    <!-- Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="flex justify-between items-center p-6 bg-white border-b-2 border-gray-200">
            <h2 class="text-2xl text-gray-700 font-semibold">Booking Details (ID: <?php echo htmlspecialchars($booking['id']); ?>)</h2>
            <div class="flex items-center">
                <span class="text-gray-600 mr-2">Welcome, <?php echo htmlspecialchars($user['full_name']); ?></span>
                <i class="fas fa-user-circle fa-2x text-gray-500"></i>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Booking Information -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl text-gray-700 font-semibold mb-4">Booking Information</h3>
                    <p class="mb-2"><strong>Room:</strong> <?php echo htmlspecialchars($booking['room_name']); ?></p>
                    <p class="mb-2"><strong>Check-in:</strong> <?php echo date("M j, Y", strtotime($booking['check_in'])); ?></p>
                    <p class="mb-2"><strong>Check-out:</strong> <?php echo date("M j, Y", strtotime($booking['check_out'])); ?></p>
                    <p class="mb-2"><strong>Total Amount:</strong> ₦<?php echo number_format($booking['total_amount'], 2); ?></p>
                    <p class="mb-2">
                        <strong>Payment Status:</strong> 
                        <span class="px-2 py-1 font-semibold leading-tight <?php echo $booking['payment_status'] === 'paid' ? 'text-green-700 bg-green-100' : 'text-yellow-700 bg-yellow-100'; ?> rounded-sm">
                            <?php echo ucfirst($booking['payment_status']); ?>
                        </span>
                    </p>
                    <p class="mb-2"><strong>Booked On:</strong> <?php echo date("M j, Y H:i", strtotime($booking['created_at'])); ?></p>
                </div>

                <!-- User Information -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl text-gray-700 font-semibold mb-4">Guest Information</h3>
                    <p class="mb-2"><strong>Name:</strong> <?php echo htmlspecialchars($booking['full_name']); ?></p>
                    <p class="mb-2"><strong>Email:</strong> <?php echo htmlspecialchars($booking['email']); ?></p>
                    <p class="mb-2"><strong>Phone:</strong> <?php echo htmlspecialchars($booking['phone']); ?></p>
                </div>
            </div>

            <!-- Food Orders for this Booking -->
            <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl text-gray-700 font-semibold mb-4">Food Orders for this Room (Room: <?php echo htmlspecialchars($booking['room_name']); ?>)</h3>
                <?php if (!empty($foodOrders)): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-800 text-white">
                                <tr>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Food Item</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Quantity</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Price per item</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Total</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                <?php $grandTotalFood = 0; ?>
                                <?php foreach ($foodOrders as $item): ?>
                                    <tr>
                                        <td class="text-left py-3 px-4"><?php echo htmlspecialchars($item['food_name']); ?></td>
                                        <td class="text-left py-3 px-4"><?php echo htmlspecialchars($item['quantity']); ?></td>
                                        <td class="text-left py-3 px-4">₦<?php echo number_format($item['price'], 2); ?></td>
                                        <td class="text-left py-3 px-4">₦<?php echo number_format($item['total_item_price'], 2); ?></td>
                                    </tr>
                                    <?php $grandTotalFood += $item['total_item_price']; ?>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="3" class="text-right py-3 px-4 font-semibold">Grand Total Food:</td>
                                    <td class="text-left py-3 px-4 font-semibold">₦<?php echo number_format($grandTotalFood, 2); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-center text-gray-500">No food orders for this booking yet.</p>
                <?php endif; ?>
            </div>

            <div class="mt-8 text-center">
                <a href="manage-bookings.php" class="inline-block bg-gray-600 text-white py-2 px-5 rounded-lg hover:bg-gray-700 transition">
                    &larr; Back to Manage Bookings
                </a>
            </div>
        </main>
    </div>
</div>

</body>
</html>