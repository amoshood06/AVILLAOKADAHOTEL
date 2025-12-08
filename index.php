<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avilla Okada</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styles for the hero background overlay and image */
        .hero-bg {
            background-image: url('asset/image/IMG-20251129-WA0032.jpg'); /* Replace with your actual image path */
            background-size: cover;
            background-position: center;
        }
        .hero-overlay {
            background-color: rgba(0, 0, 0, 0.4); /* Dark overlay */
        }
    </style>
</head>
<body class="bg-white text-gray-800">

    <?php include 'header.php'; // Section 2.1 ?>
    <main>
        <?php include 'statistics.php'; // Section 2.2 ?>
        <?php include 'rooms-suites.php'; // Section 2.3 ?>
        <?php // ... add other sections like 'Our Recommendations' and 'FAQ' here ... ?>
    </main>
    <?php include 'footer.php'; // Section 2.4 ?>

</body>
</html>