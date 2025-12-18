<?php
require_once 'header-home.php';

$rooms = select("SELECT * FROM rooms WHERE status = 'available' ORDER BY id DESC LIMIT 3");
$foods = select("SELECT * FROM food_menu ORDER BY id DESC LIMIT 3");

?>
    <main>
        <!-- Statistics -->
        <section class="py-12 px-4 md:px-16">
            <div class="flex flex-col md:flex-row justify-between max-w-5xl mx-auto shadow-lg bg-white rounded-lg p-6 md:p-8 -mt-20 relative z-20 border border-gray-100">
                <div class="flex-1 text-center py-4 border-b md:border-b-0 md:border-r border-gray-200">
                    <p id="stats-satisfaction" class="text-5xl font-extrabold text-yellow-600">98%</p>
                    <p class="text-sm uppercase tracking-widest text-gray-500 mt-2">Guest Satisfaction</p>
                </div>
                <div class="flex-1 text-center py-4 border-b md:border-b-0 md:border-r border-gray-200">
                    <p id="stats-experience" class="text-5xl font-extrabold text-yellow-600">15+</p>
                    <p class="text-sm uppercase tracking-widest text-gray-500 mt-2">Years of Experience</p>
                </div>
                <div class="flex-1 text-center py-4">
                    <p id="stats-customers" class="text-5xl font-extrabold text-yellow-600">25K+</p>
                    <p class="text-sm uppercase tracking-widest text-gray-500 mt-2">Happy Customers</p>
                </div>
            </div>
        </section>

        <!-- Featured Rooms -->
        <section class="py-16">
            <h2 class="text-3xl font-serif text-center mb-12">Our Exquisite Rooms</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto px-4">
                <?php if ($rooms && count($rooms) > 0): ?>
                    <?php foreach ($rooms as $room): ?>
                        <div class="relative h-80 bg-gray-100 group overflow-hidden rounded-lg">
                            <img src="asset/image/<?php echo $room['image']; ?>" alt="<?php echo $room['room_name']; ?>" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-black/40 group-hover:bg-black/60 transition duration-300"></div>
                            <div class="absolute bottom-0 left-0 p-6 text-white gap-2">
                                <h3 class="text-2xl font-serif mb-1"><?php echo $room['room_name']; ?></h3>
                                <p class="text-sm font-light">₦<?php echo number_format($room['price']); ?> / night</p>
                                <a href="room-details.php?id=<?php echo $room['id']; ?>" class="mt-3 text-sm uppercase tracking-wider border-2 border-white py-2 px-4 hover:bg-white hover:text-black transition duration-300">
                                    Book Now
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class='text-center col-span-full'>No rooms available at the moment.</p>
                <?php endif; ?>
            </div>
             <div class="text-center mt-8">
                <a href="rooms.php" class="text-yellow-600 hover:underline font-semibold">View All Rooms</a>
            </div>
        </section>

        <!-- Featured Foods -->
        <section class="py-16 bg-gray-50">
            <h2 class="text-3xl font-serif text-center mb-12">Our Delicious Cuisine</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto px-4">
                <?php if ($foods && count($foods) > 0): ?>
                    <?php foreach ($foods as $food): ?>
                        <div class="relative h-80 bg-gray-100 group overflow-hidden rounded-lg">
                            <img src="asset/image/<?php echo $food['image']; ?>" alt="<?php echo $food['food_name']; ?>" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-black/40 group-hover:bg-black/60 transition duration-300"></div>
                            <div class="absolute bottom-0 left-0 p-6 text-white gap-2">
                                <h3 class="text-2xl font-serif mb-1"><?php echo $food['food_name']; ?></h3>
                                <p class="text-sm font-light mb-2">₦<?php echo number_format($food['price']); ?></p>
                                 <a href="food-menu.php" class="mt-3 text-sm uppercase tracking-wider border-2 border-white py-2 px-4 hover:bg-white hover:text-black transition duration-300">
                                    View Menu
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class='text-center col-span-full'>No food items available at the moment.</p>
                <?php endif; ?>
            </div>
            <div class="text-center mt-8">
                <a href="food-menu.php" class="text-yellow-600 hover:underline font-semibold">View Full Menu</a>
            </div>
        </section>
        <!-- Room Slide Show -->
        <section class="bg-white overflow-hidden">
            <div class="swiper-container overflow-hidden" style="width: 100%; height: 500px;">
                <div class="swiper-wrapper">
                    <?php
                    $slider_images = glob('asset/slider/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                    foreach ($slider_images as $image) {
                        echo '<div class="swiper-slide" style="position: relative;">
                                <img src="' . $image . '" alt="Slider Image" style="width: 100%; height: 100%; object-fit: cover;">
                                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%);"></div>
                              </div>';
                    }
                    ?>
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination"></div>
            </div>
        </section>
        <!-- 5 Star Rate Show -->
         <section class="bg-black text-white py-16">
            <div class="max-w-4xl mx-auto text-center py-12 px-4">
                <h2 class="text-3xl font-serif mb-6">Rated 5 Stars by Our Guests</h2>
                <p class="text-gray-600 mb-4">"Amazing experience! The rooms were clean and the staff was incredibly friendly. Highly recommend!"</p>
                <div class="text-yellow-500 text-4xl">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
            </div>
         </section>
    </main>

<?php require_once 'footer.php'; ?>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper('.swiper-container', {
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            
        });
    </script>
    <script>
        function animateValue(obj, start, end, duration, suffix = '') {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                obj.innerHTML = Math.floor(progress * (end - start) + start) + suffix;
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }

        const statsSection = document.querySelector('.py-12.px-4.md\\:px-16');
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateValue(document.getElementById('stats-satisfaction'), 0, 98, 2000, '%');
                    animateValue(document.getElementById('stats-experience'), 0, 15, 2000, '+');
                    animateValue(document.getElementById('stats-customers'), 0, 25, 2000, 'K+');
                    observer.unobserve(statsSection);
                }
            });
        });

        observer.observe(statsSection);
    </script>
</body>
</html>