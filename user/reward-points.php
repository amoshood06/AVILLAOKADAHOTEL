<?php
session_start();
require_once '../config/functions.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

$user = $_SESSION['user'];
$settings = getSiteSettings();
// Initialize with default values
$site_name = "My Hotel";
$favicon = "favicon.ico";
$logo = "logo.png";
$site_description = "Welcome to our hotel.";
$address = "123 Hotel St, City";
$phone = "123-456-7890";
$email = "info@hotel.com";
$facebook_link = "";
$instagram_link = "";
$twitter_link = "";
$whatsapp_link = "";


if (is_array($settings)) {
    $site_name = $settings['site_name'] ?? $site_name;
    $favicon = $settings['favicon'] ?? $favicon;
    $logo = $settings['logo'] ?? $logo;
    $site_description = $settings['site_description'] ?? $site_description;
    $address = $settings['address'] ?? $address;
    $phone = $settings['phone'] ?? $phone;
    $email = $settings['email'] ?? $email;
    $facebook_link = $settings['facebook_link'] ?? $facebook_link;
    $instagram_link = $settings['instagram_link'] ?? $instagram_link;
    $twitter_link = $settings['twitter_link'] ?? $twitter_link;
    $whatsapp_link = $settings['whatsapp_link'] ?? $whatsapp_link;
}
$rewardPoints = $user['reward_points'];

// NOTE: The database schema does not currently support a history of reward points.
// For a real application, you would create a `reward_point_history` table
// and query it here to show the user how they earned/spent their points.
$rewardsHistory = []; // Placeholder

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reward Points - <?php echo htmlspecialchars($site_name); ?></title>
    <link rel="shortcut icon" href="../asset/image/<?php echo htmlspecialchars($favicon); ?>" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">

<div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
     <aside class="w-64 bg-white shadow-md">
        <div class="h-20 flex items-center justify-center">
            <h1 class="text-2xl font-bold text-blue-600"><?php echo htmlspecialchars($site_name); ?></h1>
        </div>
        <nav class="mt-5">
            <a href="dashboard.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-th-large mr-3"></i> Dashboard
            </a>
            <a href="my-bookings.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-calendar-alt mr-3"></i> My Bookings
            </a>
            <a href="profile.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-user mr-3"></i> Profile
            </a>
             <a href="reward-points.php" class="flex items-center mt-4 py-2 px-6 bg-gray-200 text-gray-700">
                <i class="fas fa-gift mr-3"></i> Reward Points
            </a>
            <a href="../logout.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-sign-out-alt mr-3"></i> Logout
            </a>
        </nav>
    </aside>

    <!-- Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="flex justify-between items-center p-6 bg-white border-b-2 border-gray-200">
            <h2 class="text-2xl text-gray-700 font-semibold">Reward Points</h2>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            <div class="max-w-4xl mx-auto">
                <!-- Total Points Card -->
                <div class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white p-8 rounded-lg shadow-lg text-center mb-8">
                    <p class="text-lg">Your Total Reward Points</p>
                    <p class="text-5xl font-bold my-2"><?php echo $rewardPoints; ?></p>
                    <p class="text-sm">Use your points for discounts on future bookings!</p>
                </div>

                <!-- Rewards History -->
                <div class="bg-white p-8 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold mb-6">Points History</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-800 text-white">
                                <tr>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Date</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Description</th>
                                    <th class="text-right py-3 px-4 uppercase font-semibold text-sm">Points</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                <?php if (!empty($rewardsHistory)): ?>
                                    <?php foreach ($rewardsHistory as $entry): ?>
                                        <tr>
                                            <td class="text-left py-3 px-4"><?php echo htmlspecialchars($entry['date']); ?></td>
                                            <td class="text-left py-3 px-4"><?php echo htmlspecialchars($entry['description']); ?></td>
                                            <td class="text-right py-3 px-4 font-bold <?php echo $entry['points'] > 0 ? 'text-green-500' : 'text-red-500'; ?>">
                                                <?php echo ($entry['points'] > 0 ? '+' : '') . $entry['points']; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-10 text-gray-500">
                                            <p>You have no reward point history yet.</p>
                                            <p class="text-sm mt-1">Points will appear here as you earn and spend them.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

</body>
</html>