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
    $foodId = $_GET['delete'];
    // First, delete the image file if it exists
    $food = select("SELECT image FROM food_menu WHERE id = ?", [$foodId], true);
    if ($food && !empty($food['image']) && file_exists("../asset/image/" . $food['image'])) {
        unlink("../asset/image/" . $food['image']);
    }
    execute("DELETE FROM food_menu WHERE id = ?", [$foodId]);
    header('Location: manage-food.php');
    exit;
}

$foods = select("SELECT * FROM food_menu ORDER BY id DESC");

$pageTitle = "Manage Food Menu";
?>

<?php include 'header.php'; ?>

            <div class="max-w-7xl mx-auto">
        <nav class="mt-5">
            <a href="dashboard.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-th-large mr-3"></i> Dashboard
            </a>
            <a href="manage-bookings.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-calendar-alt mr-3"></i> Bookings
            </a>
            <a href="manage-rooms.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-bed mr-3"></i> Rooms
            </a>
            <a href="manage-food.php" class="flex items-center mt-4 py-2 px-6 bg-gray-200 text-gray-700">
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
            <h2 class="text-2xl text-gray-700 font-semibold">Manage Food Menu</h2>
             <a href="add-food.php" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                <i class="fas fa-plus mr-2"></i> Add Food Item
            </a>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl text-gray-700 font-semibold mb-4">All Food Items</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Image</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Food Name</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Price</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <?php if (!empty($foods)): ?>
                                <?php foreach ($foods as $food): ?>
                                    <tr>
                                        <td class="py-3 px-4">
                                            <img src="../asset/image/<?php echo htmlspecialchars($food['image']); ?>" alt="<?php echo htmlspecialchars($food['food_name']); ?>" class="h-16 w-16 object-cover rounded">
                                        </td>
                                        <td class="text-left py-3 px-4"><?php echo htmlspecialchars($food['food_name']); ?></td>
                                        <td class="text-left py-3 px-4">$<?php echo number_format($food['price'], 2); ?></td>
                                        <td class="text-left py-3 px-4">
                                            <a href="edit-food.php?id=<?php echo $food['id']; ?>" class="text-blue-500 hover:text-blue-700 mr-2"><i class="fas fa-edit"></i></a>
                                            <a href="manage-food.php?delete=<?php echo $food['id']; ?>" onclick="return confirm('Are you sure you want to delete this food item?');" class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4">No food items found.</td>
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
