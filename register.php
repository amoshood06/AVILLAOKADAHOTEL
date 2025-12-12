<?php
session_start();
require_once 'config/functions.php';

$message = '';
$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- Form Data ---
    $fullName = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    // --- Validation ---
    if (empty($fullName) || empty($email) || empty($password)) {
        $message = "Please fill in all required fields.";
        $error = true;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
        $error = true;
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters long.";
        $error = true;
    } else {
        // --- Check if user already exists ---
        $existingUser = select("SELECT id FROM users WHERE email = ?", [$email], true);

        if ($existingUser) {
            $message = "An account with this email already exists.";
            $error = true;
        } else {
            // --- Hash Password and Insert User ---
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO users (full_name, email, phone, password) VALUES (?, ?, ?, ?)";
            $params = [$fullName, $email, $phone, $hashedPassword];
            
            $result = execute($sql, $params);

            if ($result) {
                $_SESSION['success_message'] = "Registration successful! Please log in.";
                header("Location: login.php");
                exit();
            } else {
                $message = "Registration failed. Please try again later.";
                $error = true;
            }
        }
    }

    // Store message in session to display after redirect
    if ($error) {
        $_SESSION['error_message'] = $message;
        header("Location: register.php");
        exit();
    }
}

// Retrieve messages from session
if (isset($_SESSION['error_message'])) {
    $message = $_SESSION['error_message'];
    $error = true;
    unset($_SESSION['error_message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avilla Okada - Create Account</title>
 <link rel="shortcut icon" href="asset/image/av1.png" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .background-image {
            /* Replace 'path/to/your/image.jpg' with the actual path to your background image */
            background-image: url('asset/image/IMG-20251129-WA0040.jpg'); 
            background-size: cover;
 background-position: center;
        }

        /* Applying the glass effect (blur) */
        .glass-panel {
            backdrop-filter: blur(10px); /* Tailwind's backdrop-blur-md or custom value */
            -webkit-backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.4); /* White with transparency */
 }
    </style>
</head>
<body class="bg-white min-h-screen flex items-center justify-center">

 <div class="flex w-full max-w-5xl h-auto min-h-[600px] shadow-2xl rounded-xl overflow-hidden">

        <div class="hidden md:flex md:w-1/2 background-image items-center justify-center p-8">
            <div class="glass-panel p-10 rounded-xl w-full h-full flex items-center justify-center">
                <img src="asset/image/av1.png" alt="" class="w-[200px]">
            </div>
        </div>

        <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
 
            <h2 class="text-3xl font-semibold mb-8 text-gray-800">
                Create Account
 </h2>
            
            <form action="register.php" method="POST" class="space-y-5">
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter your name" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400"
 required>
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">E mail</label>
                    <input type="email" id="email" name="email" placeholder="name@gmail.com" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400"
                           required>
 </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone No</label>
                    <input type="tel" id="phone" name="phone" placeholder="With Country Code" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">
                </div>
                
 <!-- <div>
                    <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                    <input type="text" id="country" name="country" placeholder="Country Name" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400"
                           required>
                </div>

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="username" name="username" placeholder="Username" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400"
                           required>
                </div>
 -->
                <div class="relative">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" placeholder="6+ characters" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 pr-10"
                           required>
                    <span class="absolute inset-y-0 right-0 top-6 pr-3 flex items-center text-gray-400">
                        &#x1F441;
                    </span>
                </div>
                
                <p class="text-xs text-gray-500">
                    By signing up you agree to <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">terms and conditions</a> at zoho.
                </p>

                <button type="submit" class="w-full py-3 bg-yellow-600 hover:bg-blue-700 text-white font-semibold rounded-md transition duration-200 shadow-md">
                    Register
                </button>
            </form>

            <div class="mt-4 text-center">
                <a href="login.php" class="text-gray-600 hover:text-blue-600 font-medium">
                    Login
                </a>
            </div>
            
        </div>
    </div>
<script>
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