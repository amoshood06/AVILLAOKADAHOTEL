<?php
require_once '../config/functions.php';
initSessionConfig();
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: manage-food.php');
    exit;
}

$user = $_SESSION['user'];
$foodId = $_GET['id'];
$errors = [];

$food = select("SELECT * FROM food_menu WHERE id = ?", [$foodId], true);

if (!$food) {
    header('Location: manage-food.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $foodName = trim($_POST['food_name']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);

    if (empty($foodName)) $errors[] = "Food name is required.";
    if (empty($price) || !is_numeric($price)) $errors[] = "Valid price is required.";
    if (empty($description)) $errors[] = "Description is required.";

    $image = $food['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Delete old image
        if (!empty($image) && file_exists('../asset/image/' . $image)) {
            unlink('../asset/image/' . $image);
        }
        
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageName = time() . '_' . $_FILES['image']['name'];
        $imagePath = '../asset/image/' . $imageName;

        if (move_uploaded_file($imageTmpName, $imagePath)) {
            $image = $imageName;
        } else {
            $errors[] = "Failed to upload new image.";
        }
    }

    if (empty($errors)) {
        $sql = "UPDATE food_menu SET food_name = ?, price = ?, description = ?, image = ? WHERE id = ?";
        execute($sql, [$foodName, $price, $description, $image, $foodId]);
        header('Location: manage-food.php');
        exit;
    }
}
$pageTitle = "Edit Food Item";
?>

<?php include 'header.php'; ?>

            <div class="max-w-7xl mx-auto">
            <a href="dashboard.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
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
            <div class="max-w-7xl mx-auto">
            <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow">
                <?php if (!empty($errors)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <form action="edit-food.php?id=<?php echo $foodId; ?>" method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="food_name" class="block text-gray-700 text-sm font-bold mb-2">Food Name</label>
                        <input type="text" id="food_name" name="food_name" value="<?php echo htmlspecialchars($food['food_name']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Price</label>
                        <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($food['price']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                        <textarea id="description" name="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required><?php echo htmlspecialchars($food['description']); ?></textarea>
                    </div>
                    <div class="mb-6">
                        <label for="image" class="block text-gray-700 text-sm font-bold mb-2">New Food Image (optional)</label>
                        <input type="file" id="image" name="image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <p class="text-xs text-gray-600 mt-1">Current Image:</p>
                        <img src="../asset/image/<?php echo htmlspecialchars($food['image']); ?>" alt="<?php echo htmlspecialchars($food['food_name']); ?>" class="h-24 w-24 object-cover rounded mt-2">
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Update Food Item
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<?php include 'footer.php'; ?>
