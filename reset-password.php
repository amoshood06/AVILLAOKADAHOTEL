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
$tokenValid = false;
$user = null;

$token = $_GET['token'] ?? '';

if (!empty($token)) {
    // Validate token
    $user = select("SELECT id, full_name, email, reset_token_expiry FROM users WHERE reset_token = ?", [$token], true);

    if ($user) {
        $currentTime = new DateTime();
        $expiryTime = new DateTime($user['reset_token_expiry']);

        if ($currentTime <= $expiryTime) {
            $tokenValid = true;
        } else {
            $message = "This password reset link has expired. Please request a new one.";
            $messageType = "error";
        }
    } else {
        $message = "Invalid password reset link.";
        $messageType = "error";
    }
} else {
    $message = "No reset token provided.";
    $messageType = "error";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $tokenValid) {
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($password)) {
        $message = "Please enter a new password.";
        $messageType = "error";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters long.";
        $messageType = "error";
    } elseif ($password !== $confirmPassword) {
        $message = "Passwords do not match.";
        $messageType = "error";
    } else {
        // Hash the new password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Update password and clear reset token
        $result = execute("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?",
                         [$hashedPassword, $user['id']]);

        if ($result !== false) {
            $message = "Your password has been successfully reset! You can now <a href='login.php' class='text-blue-600 underline'>login</a> with your new password.";
            $messageType = "success";
            $tokenValid = false; // Prevent form from showing again
        } else {
            $message = "Failed to reset password. Please try again.";
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
    <title>Reset Password - Okarahotel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">

    <div class="max-w-md w-full space-y-8">
        <!-- Logo/Brand -->
        <div class="text-center">
            <img src="asset/image/newlogo.png" alt="" class="w-[200px] mx-auto">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Reset Your Password
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Enter your new password below.
            </p>
        </div>

        <!-- Message Display -->
        <?php if (!empty($message)): ?>
            <div class="bg-<?php echo $messageType === 'success' ? 'green' : 'red'; ?>-100 border border-<?php echo $messageType === 'success' ? 'green' : 'red'; ?>-400 text-<?php echo $messageType === 'success' ? 'green' : 'red'; ?>-700 px-4 py-3 rounded-md text-center">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Reset Password Form -->
        <?php if ($tokenValid): ?>
            <div class="bg-white py-8 px-6 shadow-xl rounded-lg">
                <form method="POST" action="reset-password.php?token=<?php echo htmlspecialchars($token); ?>" class="space-y-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            New Password
                        </label>
                        <div class="relative">
                            <input type="password" id="password" name="password"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 pr-10"
                                   placeholder="Enter new password" required>
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 cursor-pointer" id="toggle-password">
                                <i class="fas fa-eye-slash"></i>
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Password must be at least 6 characters long.</p>
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm New Password
                        </label>
                        <div class="relative">
                            <input type="password" id="confirm_password" name="confirm_password"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 pr-10"
                                   placeholder="Confirm new password" required>
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 cursor-pointer" id="toggle-confirm-password">
                                <i class="fas fa-eye-slash"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md transition duration-200 shadow-lg">
                        Reset Password
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="login.php" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                        ← Back to Login
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-white py-8 px-6 shadow-xl rounded-lg text-center">
                <div class="mb-4">
                    <i class="fas fa-key fa-3x text-gray-400"></i>
                </div>
                <p class="text-gray-600 mb-4">This password reset link is no longer valid.</p>
                <a href="forgot-password.php" class="inline-block py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md transition duration-200">
                    Request New Reset Link
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Password visibility toggle for new password
        const togglePassword = document.querySelector('#toggle-password');
        const password = document.querySelector('#password');

        if (togglePassword && password) {
            togglePassword.addEventListener('click', function (e) {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        }

        // Password visibility toggle for confirm password
        const toggleConfirmPassword = document.querySelector('#toggle-confirm-password');
        const confirmPassword = document.querySelector('#confirm_password');

        if (toggleConfirmPassword && confirmPassword) {
            toggleConfirmPassword.addEventListener('click', function (e) {
                const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmPassword.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        }
    </script>

</body>
</html>