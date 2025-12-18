<?php
require_once 'config/functions.php';
initSessionConfig();
session_start();

// Check if user is already logged in, then redirect
if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['role'] === 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: user/dashboard.php");
    }
    exit();
}

$message = '';
$messageType = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $message = "Please enter your email address.";
        $messageType = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
        $messageType = "error";
    } else {
        // Check if email exists in database
        $user = select("SELECT id, full_name FROM users WHERE email = ?", [$email], true);

        if ($user) {
            // Generate reset token
            $resetToken = bin2hex(random_bytes(32));
            $expiryTime = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token expires in 1 hour

            // Store reset token in database
            execute("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?",
                   [$resetToken, $expiryTime, $email]);

            // For now, we'll show the reset link directly (in production, this would be emailed)
            $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/reset-password.php?token=" . $resetToken;

            $message = "Password reset link has been generated. In a real application, this would be sent to your email. For now, use this link: <br><br><a href='" . $resetLink . "' class='text-blue-600 underline'>" . $resetLink . "</a>";
            $messageType = "success";
        } else {
            $message = "No account found with this email address.";
            $messageType = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Okarahotel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">

    <div class="max-w-md w-full space-y-8">
        <!-- Logo/Brand -->
        <div class="text-center">
            <img src="asset/image/newlogo.png" alt="" class="w-[200px] mx-auto">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Forgot Your Password?
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Enter your email address and we'll send you a link to reset your password.
            </p>
        </div>

        <!-- Message Display -->
        <?php if (!empty($message)): ?>
            <div class="bg-<?php echo $messageType === 'success' ? 'green' : 'red'; ?>-100 border border-<?php echo $messageType === 'success' ? 'green' : 'red'; ?>-400 text-<?php echo $messageType === 'success' ? 'green' : 'red'; ?>-700 px-4 py-3 rounded-md text-center">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Forgot Password Form -->
        <div class="bg-white py-8 px-6 shadow-xl rounded-lg">
            <form method="POST" action="forgot-password.php" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address
                    </label>
                    <div class="relative">
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 pr-10"
                               placeholder="Enter your email address" required>
                        <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400">
                            <i class="fas fa-envelope"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md transition duration-200 shadow-lg">
                    Send Reset Link
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="login.php" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                    ← Back to Login
                </a>
            </div>
        </div>
    </div>

</body>
</html>