<?php
$title = "About Us";
require_once 'header.php';
?>

    <main>
        <!-- Page Header -->
        <section class="page-header h-64 flex items-center justify-center text-white relative">
            <div class="absolute inset-0 bg-black opacity-50"></div>
            <div class="text-center z-10">
                <h1 class="text-5xl font-serif">About Us</h1>
                <p class="mt-2 text-lg">Discover our story and commitment to excellence.</p>
            </div>
        </section>

        <!-- About Content -->
        <section class="py-20">
            <div class="container mx-auto px-6 lg:px-8">
                <div class="flex flex-wrap -mx-4 items-center">
                    <div class="w-full lg:w-1/2 px-4">
                        <h2 class="text-3xl font-semibold text-gray-800 mb-4"><?php echo $site_name; ?></h2>
                        <div class="prose max-w-none text-gray-600">
                             <p><?php echo nl2br(htmlspecialchars($site_description)); ?></p>
                             <p>Founded on the principles of luxury, comfort, and unparalleled service, our hotel has been a premier destination for travelers for over a decade. We are dedicated to providing a memorable experience for every guest, from our elegantly designed rooms to our world-class amenities and dining options.</p>
                        </div>
                    </div>
                    <div class="w-full lg:w-1/2 px-4 mt-8 lg:mt-0">
                        <img src="asset/image/IMG-20251129-WA0032.jpg" alt="About <?php echo $site_name; ?>" class="rounded-lg shadow-xl w-full">
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Statistics -->
        <section class="bg-white py-12 px-4 md:px-16">
            <div class="flex flex-col md:flex-row justify-between max-w-5xl mx-auto shadow-lg rounded-lg p-6 md:p-8 border border-gray-100">
                <div class="flex-1 text-center py-4 border-b md:border-b-0 md:border-r border-gray-200">
                    <p class="text-5xl font-extrabold text-yellow-600">98%</p>
                    <p class="text-sm uppercase tracking-widest text-gray-500 mt-2">Guest Satisfaction</p>
                </div>
                <div class="flex-1 text-center py-4 border-b md:border-b-0 md:border-r border-gray-200">
                    <p class="text-5xl font-extrabold text-yellow-600">15+</p>
                    <p class="text-sm uppercase tracking-widest text-gray-500 mt-2">Years of Experience</p>
                </div>
                <div class="flex-1 text-center py-4">
                    <p class="text-5xl font-extrabold text-yellow-600">25K+</p>
                    <p class="text-sm uppercase tracking-widest text-gray-500 mt-2">Happy Customers</p>
                </div>
            </div>
        </section>

    </main>

<?php require_once 'footer.php'; ?>