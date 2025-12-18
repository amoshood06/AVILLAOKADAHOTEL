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

            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl text-gray-700 font-semibold">All Food Items</h3>
                <a href="add-food.php" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                    <i class="fas fa-plus mr-2"></i> Add Food Item
                </a>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
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

<?php include 'footer.php'; ?>
