<?php
require_once '../config/functions.php';
initSessionConfig();
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user = select("SELECT * FROM users WHERE id = ?", [$user_id], true);

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
    $profilePictureName = $user['profile_picture']; // Keep existing picture by default

    if (empty($fullName)) {
        $updateMessage = "Full name cannot be empty.";
    } else {
        // Handle profile picture upload
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
            $fileName = $_FILES['profile_picture']['name'];
            $fileSize = $_FILES['profile_picture']['size'];
            $fileType = $_FILES['profile_picture']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedFileExtensions = ['jpg', 'gif', 'png', 'jpeg'];
            if (!in_array($fileExtension, $allowedFileExtensions)) {
                $updateMessage = "Upload failed. Only JPG, JPEG, PNG, GIF files are allowed.";
            } elseif ($fileSize > 5000000) { // 5MB max size
                $updateMessage = "Upload failed. File size must be less than 5MB.";
            } else {
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $uploadFileDir = '../asset/image/users/';
                $dest_path = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    // Delete old profile picture if it exists and is not the default
                    if (!empty($user['profile_picture']) && file_exists($uploadFileDir . $user['profile_picture']) && $user['profile_picture'] !== 'av1.png') {
                        unlink($uploadFileDir . $user['profile_picture']);
                    }
                    $profilePictureName = $newFileName;
                } else {
                    $updateMessage = "There was an error moving the uploaded file.";
                }
            }
        }

        if (empty($updateMessage)) { // Proceed only if no upload errors
            $sql = "UPDATE users SET full_name = ?, phone = ?, profile_picture = ? WHERE id = ?";
            if (execute($sql, [$fullName, $phone, $profilePictureName, $user_id])) {
                // Refresh user session data
                $_SESSION['user_name'] = $fullName;
                $user = select("SELECT * FROM users WHERE id = ?", [$user_id], true); // Re-fetch user data to get new profile picture
                $updateMessage = "Profile updated successfully!";
            } else {
                $updateMessage = "Failed to update profile.";
            }
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
        if (execute($sql, [$hashedPassword, $user_id])) {
            $passwordMessage = "Password changed successfully!";
        } else {
            $passwordMessage = "Failed to change password.";
        }
    }
}

?>
<?php
$pageTitle = "My Profile"; //Set the page title
require_once 'partials/header_user.php'; //Include the header
?>

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
                    <form action="profile.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-4 text-center">
                            <?php
                            $profilePicture = !empty($user['profile_picture']) ? '../asset/image/users/' . htmlspecialchars($user['profile_picture']) : '../asset/image/av1.png'; // Default image if none
                            ?>
                            <img src="<?php echo $profilePicture; ?>" alt="Profile Picture" class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-blue-300 mb-4">
                            <label for="profile_picture" class="inline-block bg-blue-500 text-white py-2 px-4 rounded-lg cursor-pointer hover:bg-blue-600 transition">
                                Upload New Photo
                            </label>
                            <input type="file" id="profile_picture" name="profile_picture" class="hidden" accept="image/*">
                        </div>
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
<?php
require_once 'partials/footer_user.php'; // Include the footer
?>