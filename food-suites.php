<section class="py-16">
    <h2 class="text-3xl font-serif text-center mb-12">Our Exquisite Foods Collections</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto px-4">
        <?php
        require_once('config/functions.php');
        $foods = select("SELECT * FROM `food_menu` ORDER BY `id` DESC");

        if ($foods && count($foods) > 0) {
            foreach ($foods as $food) {
        ?>
                <div class="relative h-80 bg-gray-100 group overflow-hidden">
                    <img src="asset/image/foods/<?php echo $food['image']; ?>" 
                         alt="<?php echo $food['food_name']; ?>" 
                         class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-black/40 group-hover:bg-black/60 transition duration-300"></div>
                    <div class="absolute bottom-0 left-0 p-6 text-white">
                        <h3 class="text-2xl font-serif mb-1"><?php echo $food['food_name']; ?></h3>
                        <p class="text-sm font-light">$<?php echo number_format($food['price']); ?></p>
                        <a href="food-details.php?id=<?php echo $food['id']; ?>" class="mt-3 text-sm uppercase tracking-wider border-2 border-white py-2 px-4 hover:bg-white hover:text-black transition duration-300">
                            View Details
                        </a>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<p class='text-center col-span-full'>No food items available at the moment.</p>";
        }
        ?>
    </div>
</section>