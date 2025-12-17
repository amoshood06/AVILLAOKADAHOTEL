<?php
$title = "Room Details";
require_once 'header.php';

if (!isset($_GET['id'])) {
    header('Location: rooms.php');
    exit;
}

$roomId = $_GET['id'];
$room = select("SELECT * FROM rooms WHERE id = ?", [$roomId], true);

if (!$room) {
    // Redirect or show a 'not found' message
    header('Location: rooms.php');
    exit;
}
?>

    <main class="py-16">
        <div class="container mx-auto px-6">
            <div class="lg:flex">
                <div class="lg:w-1/2">
                    <img src="asset/image/<?php echo htmlspecialchars($room['image']); ?>" alt="<?php echo htmlspecialchars($room['room_name']); ?>" class="w-full h-auto object-cover rounded-lg shadow-lg">
                </div>
                <div class="lg:w-1/2 lg:pl-12 mt-8 lg:mt-0">
                    <h1 class="text-4xl font-serif text-gray-800 mb-4"><?php echo htmlspecialchars($room['room_name']); ?></h1>
                    <p class="text-2xl font-semibold text-yellow-600 mb-6">₦<?php echo number_format($room['price'], 2); ?> / night</p>
                    <div class="prose max-w-none text-gray-600 mb-8">
                        <?php echo nl2br(htmlspecialchars($room['description'])); ?>
                    </div>
                    
                    <?php if ($room['status'] == 'available'): ?>
                        <a href="<?php echo isset($_SESSION['user_id']) ? 'user/book-room.php?id=' . $room['id'] : 'login.php'; ?>" 
                           class="inline-block bg-blue-600 text-white text-lg font-semibold py-3 px-8 rounded-lg hover:bg-blue-700 transition shadow-md">
                            Book Now
                        </a>
                    <?php else: ?>
                        <p class="text-lg font-semibold text-red-600 bg-red-100 p-4 rounded-lg">This room is currently booked.</p>
                    <?php endif; ?>
                    
                     <div class="mt-8">
                        <a href="rooms.php" class="text-blue-500 hover:underline">&larr; Back to all rooms</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php require_once 'footer.php'; ?>
