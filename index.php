<?php
// Main page controller (optional: add data fetching here)
$page_title = "LankoStay Clone - Start Next Vacation";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styles for the overlay effect on cards */
        .card-overlay {
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0) 50%);
        }
    </style>
</head>
<body class="font-sans text-gray-800">

    <?php include 'header.php'; ?>

    <main class="container mx-auto px-4 pt-12 pb-24">
        <div class="grid md:grid-cols-2 gap-12 items-start">
            <div>
                <h1 class="text-5xl font-bold leading-tight mb-4">
                    Forget Busy Work, <br> **Start Next Vacation**
                </h1>
                <p class="text-gray-600 mb-8 max-w-md">
                    We provide what you need to enjoy your holiday with family. Time to make another memorable moment.
                </p>
                <button class="bg-blue-600 text-white px-8 py-3 rounded-xl font-medium hover:bg-blue-700 transition duration-150 mb-12">
                    Show More
                </button>

                <div class="flex space-x-8 text-center mt-8">
                    <div>
                        <p class="text-2xl font-bold text-pink-500">3500</p>
                        <p class="text-gray-500 text-sm">Clients</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-pink-500">200</p>
                        <p class="text-gray-500 text-sm">Resorts</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-pink-500">100</p>
                        <p class="text-gray-500 text-sm">Cities</p>
                    </div>
                </div>
            </div>

            <div class="relative rounded-3xl overflow-hidden shadow-2xl">
                
                <img src="placeholder_hero_image.jpg" alt="Hotel room with a view" class="w-full h-full object-cover">
            </div>
        </div>

        <?php include 'search_bar.php'; ?>

    </main>

    <section class="container mx-auto px-4 pt-40 pb-16">

        <?php include 'most_picked.php'; ?>

        <?php include 'listings_grid.php'; ?>

    </section>

    <?php include 'footer.php'; ?>