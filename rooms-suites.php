<section class="py-16">
    <h2 class="text-3xl font-serif text-center mb-12">Our Exquisite Rooms Collections</h2>
    
    <div class="flex overflow-x-auto space-x-6 px-4 md:px-16 pb-6 scrollbar-hide">
        <?php for ($i = 1; $i <= 4; $i++): // Loop for room cards ?>
            <div class="flex-shrink-0 w-72 h-80 bg-gray-100 relative group overflow-hidden">
                <img src="path/to/room-<?= $i ?>.jpg" alt="Room <?= $i ?>" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                <div class="absolute inset-0 bg-black/30 group-hover:bg-black/50 transition duration-300"></div>
                <div class="absolute bottom-0 left-0 p-4 text-white">
                    <h3 class="text-xl font-semibold">The Standard Room</h3>
                    <p class="text-sm">$250 / night</p>
                    <button class="mt-2 text-xs uppercase tracking-wider border border-white py-1 px-3 hover:bg-white hover:text-black transition duration-300">View Details</button>
                </div>
            </div>
        <?php endfor; ?>
    </div>
    
    <div class="mt-16">
        <h2 class="text-3xl font-serif text-center mb-12">Explore Items and Suites</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-6xl mx-auto px-4">
            <?php 
            $suites = [
                ['name' => 'The Executive Suite', 'price' => '$550'],
                ['name' => 'The Presidential Suite', 'price' => '$900'],
                ['name' => 'Garden View Double', 'price' => '$320'],
                ['name' => 'Loft Studio', 'price' => '$400'],
            ];
            foreach ($suites as $suite):
            ?>
                <div class="relative h-80 bg-gray-100 group overflow-hidden">
                    <img src="path/to/suite-<?= strtolower(str_replace(' ', '-', $suite['name'])) ?>.jpg" 
                         alt="<?= $suite['name'] ?>" 
                         class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-black/40 group-hover:bg-black/60 transition duration-300"></div>
                    <div class="absolute bottom-0 left-0 p-6 text-white">
                        <h3 class="text-2xl font-serif mb-1"><?= $suite['name'] ?></h3>
                        <p class="text-sm font-light"><?= $suite['price'] ?> / night</p>
                        <button class="mt-3 text-sm uppercase tracking-wider border-2 border-white py-2 px-4 hover:bg-white hover:text-black transition duration-300">Book Now</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>