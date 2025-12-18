<?php
require_once '../config/functions.php';
initSessionConfig();
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: manage-rooms.php');
    exit;
}

$user = $_SESSION['user'];
$roomId = $_GET['id'];
$errors = [];

$room = select("SELECT * FROM rooms WHERE id = ?", [$roomId], true);

if (!$room) {
    header('Location: manage-rooms.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roomName = trim($_POST['room_name']);
    $roomType = trim($_POST['room_type']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);
    $status = $_POST['status'];

    if (empty($roomName)) $errors[] = "Room name is required.";
    if (empty($roomType)) $errors[] = "Room type is required.";
    if (empty($price) || !is_numeric($price)) $errors[] = "Valid price is required.";
    if (empty($description)) $errors[] = "Description is required.";

    $image = $room['image'];
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
        $sql = "UPDATE rooms SET room_name = ?, room_type = ?, price = ?, description = ?, image = ?, status = ? WHERE id = ?";
        execute($sql, [$roomName, $roomType, $price, $description, $image, $status, $roomId]);
        header('Location: manage-rooms.php');
        exit;
    }
}
$pageTitle = "Edit Room";
?>

<?php include 'header.php'; ?>

            <div class="max-w-7xl mx-auto">
            <a href="dashboard.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-th-large mr-3"></i> Dashboard
            </a>
            <a href="manage-bookings.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-calendar-alt mr-3"></i> Bookings
            </a>
            <a href="manage-rooms.php" class="flex items-center mt-4 py-2 px-6 bg-gray-200 text-gray-700">
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
                <form action="edit-room.php?id=<?php echo $roomId; ?>" method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="room_name" class="block text-gray-700 text-sm font-bold mb-2">Room Name</label>
                        <input type="text" id="room_name" name="room_name" value="<?php echo htmlspecialchars($room['room_name']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="room_type" class="block text-gray-700 text-sm font-bold mb-2">Room Type</label>
                        <input type="text" id="room_type" name="room_type" value="<?php echo htmlspecialchars($room['room_type']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Price per Night</label>
                        <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($room['price']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                        <textarea id="description" name="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required><?php echo htmlspecialchars($room['description']); ?></textarea>
                    </div>
                     <div class="mb-4">
                        <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                        <select id="status" name="status" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="available" <?php if ($room['status'] === 'available') echo 'selected'; ?>>Available</option>
                            <option value="booked" <?php if ($room['status'] === 'booked') echo 'selected'; ?>>Booked</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label for="image" class="block text-gray-700 text-sm font-bold mb-2">New Room Image (optional)</label>
                        <input type="file" id="image" name="image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <p class="text-xs text-gray-600 mt-1">Current Image:</p>
                        <img src="../asset/image/<?php echo htmlspecialchars($room['image']); ?>" alt="<?php echo htmlspecialchars($room['room_name']); ?>" class="h-24 w-24 object-cover rounded mt-2">
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Update Room
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<?php include 'footer.php'; ?>
