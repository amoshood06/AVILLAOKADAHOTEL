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
$updateMessage = '';
$passwordMessage = '';

// Handle profile update
if (isset($_POST['update_profile'])) {
    $fullName = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);

    if (empty($fullName)) {
        $updateMessage = "Full name cannot be empty.";
    } else {
        $sql = "UPDATE users SET full_name = ?, phone = ? WHERE id = ?";
        if (execute($sql, [$fullName, $phone, $user['id']])) {
            // Refresh user session data
            $_SESSION['user']['full_name'] = $fullName;
            $_SESSION['user']['phone'] = $phone;
            $user = $_SESSION['user']; //
            $updateMessage = "Profile updated successfully!";
        } else {
            $updateMessage = "Failed to update profile.";
        }
    }
}

// Handle password change
if (isset($_POST['change_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $passwordMessage = "Please fill in all password fields.";
    } elseif (!password_verify($currentPassword, $user['password'])) {
        $passwordMessage = "Incorrect current password.";
    } elseif (strlen($newPassword) < 6) {
        $passwordMessage = "New password must be at least 6 characters long.";
    } elseif ($newPassword !== $confirmPassword) {
        $passwordMessage = "New passwords do not match.";
    } else {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        if (execute($sql, [$hashedPassword, $user['id']])) {
            $passwordMessage = "Password changed successfully!";
        } else {
            $passwordMessage = "Failed to change password.";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - <?php echo htmlspecialchars($site_name); ?></title>
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
            <a href="profile.php" class="flex items-center mt-4 py-2 px-6 bg-gray-200 text-gray-700">
                <i class="fas fa-user mr-3"></i> Profile
            </a>
             <a href="reward-points.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
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
            <h2 class="text-2xl text-gray-700 font-semibold">My Profile</h2>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Profile Information -->
                <div class="bg-white p-8 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold mb-6">Profile Information</h3>
                    <?php if ($updateMessage): ?>
                        <div class="mb-4 p-3 text-sm <?php echo strpos($updateMessage, 'success') !== false ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?> rounded-lg">
                            <?php echo $updateMessage; ?>
                        </div>
                    <?php endif; ?>
                    <form action="profile.php" method="POST">
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 font-medium mb-2">Email Address</label>
                            <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="w-full px-4 py-2 bg-gray-200 border rounded-lg" disabled>
                        </div>
                        <div class="mb-4">
                            <label for="full_name" class="block text-gray-700 font-medium mb-2">Full Name</label>
                            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="mb-6">
                            <label for="phone" class="block text-gray-700 font-medium mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <button type="submit" name="update_profile" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition font-semibold">Update Profile</button>
                    </form>
                </div>

                <!-- Change Password -->
                <div class="bg-white p-8 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold mb-6">Change Password</h3>
                    <?php if ($passwordMessage): ?>
                        <div class="mb-4 p-3 text-sm <?php echo strpos($passwordMessage, 'success') !== false ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?> rounded-lg">
                            <?php echo $passwordMessage; ?>
                        </div>
                    <?php endif; ?>
                    <form action="profile.php" method="POST">
                        <div class="mb-4">
                            <label for="current_password" class="block text-gray-700 font-medium mb-2">Current Password</label>
                            <input type="password" id="current_password" name="current_password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label for="new_password" class="block text-gray-700 font-medium mb-2">New Password</label>
                            <input type="password" id="new_password" name="new_password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="mb-6">
                            <label for="confirm_password" class="block text-gray-700 font-medium mb-2">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <button type="submit" name="change_password" class="w-full bg-gray-700 text-white py-2 rounded-lg hover:bg-gray-800 transition font-semibold">Change Password</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

</body>
</html>