<?php
$title = "Our Food Menu";
require_once 'header.php';

$foods = select("SELECT * FROM food_menu ORDER BY id DESC");

?>

    <main class="py-16">
        <div class="container mx-auto px-6">
            <h1 class="text-4xl font-serif text-center mb-12">Our Delicious Menu</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <?php if ($foods && count($foods) > 0): ?>
                    <?php foreach ($foods as $food): ?>
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden group">
                            <div class="relative h-56">
                                <img src="asset/image/<?php echo $food['image']; ?>" alt="<?php echo $food['food_name']; ?>" class="w-full h-full object-cover">
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-semibold mb-2"><?php echo $food['food_name']; ?></h3>
                                <p class="text-gray-600 text-sm mb-4"><?php echo htmlspecialchars($food['description']); ?></p>
                                <p class="text-lg font-bold text-yellow-600">₦<?php echo number_format($food['price'], 2); ?></p>
                                <a href="food-details.php?id=<?php echo $food['id']; ?>" class="mt-3 text-sm uppercase tracking-wider border-2 border-white py-2 px-4 hover:bg-white hover:text-black transition duration-300">
                            View Details
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class='text-center col-span-full text-gray-500'>Our menu is currently being updated. Please check back soon!</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

<?php require_once 'footer.php'; ?>