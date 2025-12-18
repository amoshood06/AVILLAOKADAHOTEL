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
    $roomId = $_GET['delete'];
    // First, delete the image file if it exists
    $room = select("SELECT image FROM rooms WHERE id = ?", [$roomId], true);
    if ($room && !empty($room['image']) && file_exists("../asset/image/" . $room['image'])) {
        unlink("../asset/image/" . $room['image']);
    }
    execute("DELETE FROM rooms WHERE id = ?", [$roomId]);
    header('Location: manage-rooms.php');
    exit;
}

$rooms = select("SELECT * FROM rooms ORDER BY id DESC");

$pageTitle = "Manage Rooms";
?>

<?php include 'header.php'; ?>

            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl text-gray-700 font-semibold">All Rooms</h3>
                <a href="add-room.php" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                    <i class="fas fa-plus mr-2"></i> Add Room
                </a>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Image</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Room Name</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Type</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Price</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Status</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <?php if (!empty($rooms)): ?>
                                <?php foreach ($rooms as $room): ?>
                                    <tr>
                                        <td class="py-3 px-4">
                                            <img src="../asset/image/<?php echo htmlspecialchars($room['image']); ?>" alt="<?php echo htmlspecialchars($room['room_name']); ?>" class="h-16 w-16 object-cover rounded">
                                        </td>
                                        <td class="text-left py-3 px-4"><?php echo htmlspecialchars($room['room_name']); ?></td>
                                        <td class="text-left py-3 px-4"><?php echo htmlspecialchars($room['room_type']); ?></td>
                                        <td class="text-left py-3 px-4">$<?php echo number_format($room['price'], 2); ?></td>
                                        <td class="text-left py-3 px-4">
                                            <span class="px-2 py-1 font-semibold leading-tight <?php echo $room['status'] === 'available' ? 'text-green-700 bg-green-100' : 'text-red-700 bg-red-100'; ?> rounded-sm">
                                                <?php echo ucfirst($room['status']); ?>
                                            </span>
                                        </td>
                                        <td class="text-left py-3 px-4">
                                            <a href="edit-room.php?id=<?php echo $room['id']; ?>" class="text-blue-500 hover:text-blue-700 mr-2"><i class="fas fa-edit"></i></a>
                                            <a href="manage-rooms.php?delete=<?php echo $room['id']; ?>" onclick="return confirm('Are you sure you want to delete this room?');" class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">No rooms found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

<?php include 'footer.php'; ?>
