<?php
require_once '../config/functions.php';
initSessionConfig();
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$user = $_SESSION['user'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $foodName = trim($_POST['food_name']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);

    if (empty($foodName)) $errors[] = "Food name is required.";
    if (empty($price) || !is_numeric($price)) $errors[] = "Valid price is required.";
    if (empty($description)) $errors[] = "Description is required.";

    // Image upload handling
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageName = time() . '_' . $_FILES['image']['name'];
        $imagePath = '../asset/image/' . $imageName;

        if (move_uploaded_file($imageTmpName, $imagePath)) {
            $image = $imageName;
        } else {
            $errors[] = "Failed to upload image.";
        }
    } else {
        $errors[] = "Food image is required.";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO food_menu (food_name, price, description, image) VALUES (?, ?, ?, ?)";
        execute($sql, [$foodName, $price, $description, $image]);
        header('Location: manage-food.php');
        exit;
    }
}
$pageTitle = "Add New Food Item";
?>

<?php include 'header.php'; ?>

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
                <form action="add-food.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="food_name" class="block text-gray-700 text-sm font-bold mb-2">Food Name</label>
                        <input type="text" id="food_name" name="food_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Price</label>
                        <input type="number" id="price" name="price" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                        <textarea id="description" name="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                    </div>
                    <div class="mb-6">
                        <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Food Image</label>
                        <input type="file" id="image" name="image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Add Food Item
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<?php include 'footer.php'; ?>
