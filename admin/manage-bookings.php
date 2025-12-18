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

$pageTitle = "Manage Bookings";
?>

<?php include 'header.php'; ?>

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

<?php include 'footer.php'; ?>
