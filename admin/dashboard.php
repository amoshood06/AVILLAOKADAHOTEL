<?php
require_once '../config/functions.php';
initSessionConfig();
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$user = $_SESSION['user'];

// Fetch statistics
$totalUsers = select("SELECT COUNT(*) as total FROM users", [], true)['total'] ?? 0;
$totalBookings = select("SELECT COUNT(*) as total FROM bookings", [], true)['total'] ?? 0;
$totalRooms = select("SELECT COUNT(*) as total FROM rooms", [], true)['total'] ?? 0;
$totalRevenueResult = select("SELECT SUM(total_amount) as total FROM bookings WHERE payment_status = 'paid'", [], true);
$totalRevenue = $totalRevenueResult['total'] ?? 0;


// Fetch recent bookings
$recentBookings = select("SELECT b.*, u.full_name, r.room_name FROM bookings b JOIN users u ON b.user_id = u.id JOIN rooms r ON b.room_id = r.id ORDER BY b.created_at DESC LIMIT 5") ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Okarahotel</title>
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
            <a href="dashboard.php" class="flex items-center mt-4 py-2 px-6 bg-gray-200 text-gray-700">
                <i class="fas fa-th-large mr-3"></i> Dashboard
            </a>
            <a href="manage-bookings.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
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
            <div class="flex items-center">
                <h2 class="text-2xl text-gray-700 font-semibold">Dashboard</h2>
            </div>
            <div class="flex items-center">
                <span class="text-gray-600 mr-2">Welcome, <?php echo htmlspecialchars($user['full_name']); ?></span>
                <i class="fas fa-user-circle fa-2x text-gray-500"></i>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <i class="fas fa-users fa-3x text-blue-500"></i>
                        <div class="ml-4">
                            <p class="text-lg text-gray-600">Total Users</p>
                            <p class="text-2xl font-bold text-gray-800"><?php echo $totalUsers; ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <i class="fas fa-calendar-check fa-3x text-green-500"></i>
                        <div class="ml-4">
                            <p class="text-lg text-gray-600">Total Bookings</p>
                            <p class="text-2xl font-bold text-gray-800"><?php echo $totalBookings; ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <i class="fas fa-bed fa-3x text-yellow-500"></i>
                        <div class="ml-4">
                            <p class="text-lg text-gray-600">Total Rooms</p>
                            <p class="text-2xl font-bold text-gray-800"><?php echo $totalRooms; ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <i class="fas fa-dollar-sign fa-3x text-red-500"></i>
                        <div class="ml-4">
                            <p class="text-lg text-gray-600">Total Revenue</p>
                            <p class="text-2xl font-bold text-gray-800">$<?php echo number_format($totalRevenue, 2); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl text-gray-700 font-semibold mb-4">Recent Bookings</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="w-1/4 text-left py-3 px-4 uppercase font-semibold text-sm">User</th>
                                <th class="w-1/4 text-left py-3 px-4 uppercase font-semibold text-sm">Room</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Check-in</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Check-out</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <?php if (!empty($recentBookings)): ?>
                                <?php foreach ($recentBookings as $booking): ?>
                                    <tr>
                                        <td class="text-left py-3 px-4"><?php echo htmlspecialchars($booking['full_name']); ?></td>
                                        <td class="text-left py-3 px-4"><?php echo htmlspecialchars($booking['room_name']); ?></td>
                                        <td class="text-left py-3 px-4"><?php echo htmlspecialchars($booking['check_in']); ?></td>
                                        <td class="text-left py-3 px-4"><?php echo htmlspecialchars($booking['check_out']); ?></td>
                                        <td class="text-left py-3 px-4">
                                            <span class="px-2 py-1 font-semibold leading-tight <?php echo $booking['payment_status'] === 'paid' ? 'text-green-700 bg-green-100' : 'text-yellow-700 bg-yellow-100'; ?> rounded-sm">
                                                <?php echo ucfirst($booking['payment_status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">No recent bookings found.</td>
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
