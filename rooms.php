<?php
$title = "Our Rooms";
require_once 'header.php';

$rooms = select("SELECT * FROM rooms WHERE status = 'available' ORDER BY id DESC");

?>

    <main class="py-16">
        <div class="container mx-auto px-6">
            <h1 class="text-4xl font-serif text-center mb-12">Explore Our Rooms</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php if ($rooms && count($rooms) > 0): ?>
                    <?php foreach ($rooms as $room): ?>
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden group">
                            <div class="relative h-64">
                                <img src="asset/image/<?php echo $room['image']; ?>" alt="<?php echo $room['room_name']; ?>" class="w-full h-full object-cover">
                            </div>
                            <div class="p-6">
                                <h3 class="text-2xl font-serif mb-2"><?php echo $room['room_name']; ?></h3>
                                <p class="text-lg font-semibold text-yellow-600 mb-4">₦<?php echo number_format($room['price']); ?> / night</p>
                                <p class="text-gray-600 text-sm mb-4"><?php echo substr($room['description'], 0, 100) . '...'; ?></p>
                                <a href="room-details.php?id=<?php echo $room['id']; ?>" class="inline-block bg-blue-600 text-white py-2 px-5 rounded-md hover:bg-blue-700 transition">
                                    View Details
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class='text-center col-span-full text-gray-500'>No rooms are available at the moment. Please check back later.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

<?php require_once 'footer.php'; ?>