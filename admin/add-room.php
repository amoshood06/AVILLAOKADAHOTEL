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
    $roomName = trim($_POST['room_name']);
    $roomType = trim($_POST['room_type']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);
    $status = $_POST['status'];

    if (empty($roomName)) $errors[] = "Room name is required.";
    if (empty($roomType)) $errors[] = "Room type is required.";
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
        $errors[] = "Room image is required.";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO rooms (room_name, room_type, price, description, image, status) VALUES (?, ?, ?, ?, ?, ?)";
        execute($sql, [$roomName, $roomType, $price, $description, $image, $status]);
        header('Location: manage-rooms.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Room - Okarahotel</title>
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
        </nav>
    </aside>

    <!-- Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="flex justify-between items-center p-6 bg-white border-b-2 border-gray-200">
            <h2 class="text-2xl text-gray-700 font-semibold">Add New Room</h2>
             <a href="manage-rooms.php" class="text-blue-500 hover:underline">Back to Rooms</a>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
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
                <form action="add-room.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="room_name" class="block text-gray-700 text-sm font-bold mb-2">Room Name</label>
                        <input type="text" id="room_name" name="room_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="room_type" class="block text-gray-700 text-sm font-bold mb-2">Room Type</label>
                        <input type="text" id="room_type" name="room_type" placeholder="e.g., Deluxe, Standard, Suite" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Price per Night</label>
                        <input type="number" id="price" name="price" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                        <textarea id="description" name="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                    </div>
                     <div class="mb-4">
                        <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                        <select id="status" name="status" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="available">Available</option>
                            <option value="booked">Booked</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Room Image</label>
                        <input type="file" id="image" name="image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Add Room
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

</body>
</html>
