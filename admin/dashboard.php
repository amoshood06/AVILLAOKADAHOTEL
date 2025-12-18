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
$pageTitle = "Dashboard";
?>

<?php include 'header.php'; ?>

            <div class="max-w-7xl mx-auto">
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

<?php include 'footer.php'; ?>
