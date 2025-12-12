<?php
session_start();
require_once '../config/functions.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: manage-bookings.php');
    exit;
}

$user = $_SESSION['user'];
$bookingId = $_GET['id'];

$booking = select("SELECT b.*, u.full_name, u.email, r.room_name, r.price as room_price FROM bookings b JOIN users u ON b.user_id = u.id JOIN rooms r ON b.room_id = r.id WHERE b.id = ?", [$bookingId], true);

if (!$booking) {
    die('Booking not found.');
}

$bookingFoods = select("SELECT fm.food_name, fm.price, bf.quantity FROM booking_foods bf JOIN food_menu fm ON bf.food_id = fm.id WHERE bf.booking_id = ?", [$bookingId]);

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
            <h2 class="text-2xl text-gray-700 font-semibold">Booking Details</h2>
            <div class="flex items-center">
                <span class="text-gray-600 mr-2">Welcome, <?php echo htmlspecialchars($user['full_name']); ?></span>
                <i class="fas fa-user-circle fa-2x text-gray-500"></i>
            </div>
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
                        <h4 class="text-lg font-semibold text-gray-700 mb-2">Customer Details</h4>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($booking['full_name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($booking['email']); ?></p>
                    </div>
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
                                    <?php
                                    $foodTotal = 0;
                                    foreach ($bookingFoods as $food) {
                                        $itemTotal = $food['price'] * $food['quantity'];
                                        $foodTotal += $itemTotal;
                                        echo '<tr>';
                                        echo '<td class="py-2 px-4">' . htmlspecialchars($food['food_name']) . ' (x' . $food['quantity'] . ')</td>';
                                        echo '<td class="text-right py-2 px-4">$' . number_format($itemTotal, 2) . '</td>';
                                        echo '</tr>';
                                    }
                                    ?>
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
                 <div class="mt-8 text-right">
                    <a href="manage-bookings.php" class="text-blue-500 hover:underline">Back to All Bookings</a>
                </div>
            </div>
        </main>
    </div>
</div>

</body>
</html>
