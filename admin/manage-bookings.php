<?php
require_once '../config/functions.php';
initSessionConfig();
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$user = $_SESSION['user'];

// Handle Delete
if (isset($_GET['delete'])) {
    $bookingId = $_GET['delete'];
    execute("DELETE FROM bookings WHERE id = ?", [$bookingId]);
    header('Location: manage-bookings.php');
    exit;
}

// Handle Status Update
if (isset($_GET['update_status'])) {
    $bookingId = $_GET['update_status'];
    $status = $_GET['status'];
    execute("UPDATE bookings SET payment_status = ? WHERE id = ?", [$status, $bookingId]);
    header('Location: manage-bookings.php');
    exit;
}


$bookings = select("SELECT b.*, u.full_name, r.room_name FROM bookings b JOIN users u ON b.user_id = u.id JOIN rooms r ON b.room_id = r.id ORDER BY b.created_at DESC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - Okarahotel</title>
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
            <a href="manage-food-orders.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-concierge-bell mr-3"></i> Food Orders
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
            <h2 class="text-2xl text-gray-700 font-semibold">Manage Bookings</h2>
            <div class="flex items-center">
                <span class="text-gray-600 mr-2">Welcome, <?php echo htmlspecialchars($user['full_name']); ?></span>
                <i class="fas fa-user-circle fa-2x text-gray-500"></i>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl text-gray-700 font-semibold mb-4">All Bookings</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">User</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Room</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Check-in</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Check-out</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Total</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Status</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <?php if (!empty($bookings)): ?>
                                <?php foreach ($bookings as $booking): ?>
                                    <tr>
                                        <td class="text-left py-3 px-4"><?php echo htmlspecialchars($booking['full_name']); ?></td>
                                        <td class="text-left py-3 px-4"><?php echo htmlspecialchars($booking['room_name']); ?></td>
                                        <td class="text-left py-3 px-4"><?php echo htmlspecialchars($booking['check_in']); ?></td>
                                        <td class="text-left py-3 px-4"><?php echo htmlspecialchars($booking['check_out']); ?></td>
                                        <td class="text-left py-3 px-4">$<?php echo number_format($booking['total_amount'], 2); ?></td>
                                        <td class="text-left py-3 px-4">
                                            <span class="px-2 py-1 font-semibold leading-tight <?php echo $booking['payment_status'] === 'paid' ? 'text-green-700 bg-green-100' : 'text-yellow-700 bg-yellow-100'; ?> rounded-sm">
                                                <?php echo ucfirst($booking['payment_status']); ?>
                                            </span>
                                        </td>
                                        <td class="text-left py-3 px-4">
                                            <a href="view-booking.php?id=<?php echo $booking['id']; ?>" class="text-blue-500 hover:text-blue-700 mr-2"><i class="fas fa-eye"></i></a>
                                            <?php if ($booking['payment_status'] !== 'paid'): ?>
                                                <a href="manage-bookings.php?update_status=<?php echo $booking['id']; ?>&status=paid" class="text-green-500 hover:text-green-700 mr-2"><i class="fas fa-check-circle"></i></a>
                                            <?php else: ?>
                                                <a href="manage-bookings.php?update_status=<?php echo $booking['id']; ?>&status=pending" class="text-yellow-500 hover:text-yellow-700 mr-2"><i class="fas fa-times-circle"></i></a>
                                            <?php endif; ?>
                                            <a href="manage-bookings.php?delete=<?php echo $booking['id']; ?>" onclick="return confirm('Are you sure you want to delete this booking?');" class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">No bookings found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

</body>
</html>
