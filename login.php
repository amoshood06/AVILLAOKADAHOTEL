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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $_SESSION['error_message'] = "Email and password are required.";
        header("Location: login.php");
        exit();
    }

    $user = select("SELECT * FROM users WHERE email = ?", [$email], true);

    if ($user && password_verify($password, $user['password'])) {
        // Password is correct, start session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user'] = $user; // Set the user array for admin compatibility

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: user/dashboard.php");
        }
        exit();
    } else {
        // Invalid credentials
        $_SESSION['error_message'] = "Invalid login credentials. Please try again.";
        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avilla Okada - Login Account</title>
    <link rel="shortcut icon" href="asset/image/av1.png" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .background-image {
            background-image: url('asset/image/IMG-20251129-WA0040.jpg'); 
            background-size: cover;
            background-position: center;
        }
        .glass-panel {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.4);
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">

    <div class="flex w-full max-w-5xl h-auto min-h-[500px] shadow-2xl rounded-xl overflow-hidden bg-white">

        <div class="hidden md:flex md:w-1/2 background-image items-center justify-center p-8">
            <div class="glass-panel p-10 rounded-xl w-full h-full flex items-center justify-center">
                <img src="asset/image/newlogo.png" alt="" class="w-[200px]">
            </div>
        </div>

        <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
            
            <h2 class="text-3xl font-semibold mb-10 text-gray-800">
                Login Account
            </h2>

            <form action="login.php" method="POST" class="space-y-6">
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" placeholder="your@email.com" 
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400"
                           required>
                </div>
                
                <div class="relative">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" placeholder="6+ characters" 
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 pr-10"
                           required>
                    <span class="absolute inset-y-0 right-0 top-6 pr-3 flex items-center text-gray-400 cursor-pointer" id="toggle-password">
                        <i class="fas fa-eye-slash"></i> </span>
                </div>
                
                <p class="text-xs text-gray-500 pt-2">
                    By signing in you agree to our <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">terms and conditions</a>.
                </p>

                <button type="submit" class="w-full py-3 bg-yellow-600 hover:bg-blue-700 text-white font-semibold rounded-md transition duration-200 shadow-lg mt-4">
                    Login
                </button>
            </form>

            <div class="mt-4 text-center">
                <a href="forgot-password.php" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                    Forgot Password?
                </a>
            </div>

            <div class="mt-2 text-center">
                <a href="register.php" class="text-gray-600 hover:text-blue-600 font-medium">
                    Create Account
                </a>
            </div>
            
        </div>
    </div>

<script>
    const togglePassword = document.querySelector('#toggle-password');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function (e) {
        // toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        // toggle the eye slash icon
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
    function showToast(message, type = 'info') {
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            document.body.appendChild(toastContainer);

            const style = document.createElement('style');
            style.textContent = `
                #toast-container {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    z-index: 9999;
                    display: flex;
                    flex-direction: column;
                    gap: 10px;
                }
                .toast {
                    padding: 15px 20px;
                    border-radius: 8px;
                    color: #fff;
                    font-family: sans-serif;
                    font-size: 16px;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    opacity: 0;
                    transform: translateX(100%);
                    transition: all 0.5s cubic-bezier(0.68, -0.55, 0.27, 1.55);
                }
                .toast.visible {
                    opacity: 1;
                    transform: translateX(0);
                }
                .toast-success { background-color: #28a745; }
                .toast-error { background-color: #dc3545; }
                .toast-info { background-color: #17a2b8; }
                .toast-warning { background-color: #ffc107; }
            `;
            document.head.appendChild(style);
        }

        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        
        const icons = { success: '✔', error: '✖', info: 'ℹ', warning: '⚠' };
        toast.innerHTML = `<strong>${icons[type] || ''}</strong> ${message}`;

        toastContainer.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('visible');
        }, 100);

        setTimeout(() => {
            toast.classList.remove('visible');
            toast.addEventListener('transitionend', () => toast.remove());
        }, 5000);
    }
</script>

<?php
if (isset($_SESSION['success_message'])) {
    echo "<script>showToast('" . addslashes($_SESSION['success_message']) . "', 'success');</script>";
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    echo "<script>showToast('" . addslashes($_SESSION['error_message']) . "', 'error');</script>";
    unset($_SESSION['error_message']);
}
?>
</body>
</html>