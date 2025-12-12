

<section class="py-16">
    <h2 class="text-3xl font-serif text-center mb-12">Our Exquisite Rooms Collections</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto px-4">
        <?php
        require('config/functions.php');
        $rooms = select("SELECT * FROM `rooms` WHERE `status`=? ORDER BY `id` DESC", ['available']);

        if ($rooms && count($rooms) > 0) {
            foreach ($rooms as $room) {
        ?>
                <div class="relative h-80 bg-gray-100 group overflow-hidden">
                    <img src="asset/image/rooms/<?php echo $room['image']; ?>" 
                         alt="<?php echo $room['room_name']; ?>" 
                         class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-black/40 group-hover:bg-black/60 transition duration-300"></div>
                    <div class="absolute bottom-0 left-0 p-6 text-white">
                        <h3 class="text-2xl font-serif mb-1"><?php echo $room['room_name']; ?></h3>
                        <p class="text-sm font-light">₦<?php echo number_format($room['price']); ?> / night</p>
                        <a href="room-details.php?id=<?php echo $room['id']; ?>" class="mt-3 text-sm uppercase tracking-wider border-2 border-white py-2 px-4 hover:bg-white hover:text-black transition duration-300">
                            Book Now
                        </a>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<p class='text-center col-span-full'>No rooms available at the moment.</p>";
        }
        ?>
    </div>
</section>


