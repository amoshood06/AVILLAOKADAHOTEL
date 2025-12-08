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
            /* Replace 'path/to/your/image.jpg' with the actual path to your background image */
            /* Using the image from the register/login pages for consistency */
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">

    <div class="flex w-full max-w-5xl h-auto min-h-[500px] shadow-2xl rounded-xl overflow-hidden bg-white">

        <div class="hidden md:flex md:w-1/2 background-image items-center justify-center p-8">
            <div class="glass-panel p-10 rounded-xl w-full h-full flex items-center justify-center">
                <img src="asset/image/av1.png" alt="" class="w-[200px]">
            </div>
        </div>

        <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
            
            <h2 class="text-3xl font-semibold mb-10 text-gray-800">
                Login Account
            </h2>

            <form action="#" method="POST" class="space-y-6">
                
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="username" name="username" placeholder="Username" 
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400"
                           required>
                </div>
                
                <div class="relative">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" placeholder="6+ characters" 
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 pr-10"
                           required>
                    <span class="absolute inset-y-0 right-0 top-6 pr-3 flex items-center text-gray-400 cursor-pointer">
                        <i class="fas fa-eye-slash"></i> </span>
                </div>
                
                <p class="text-xs text-gray-500 pt-2">
                    By signing up you agree to <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">terms and conditions</a> at zoho.
                </p>

                <button type="submit" class="w-full py-3 bg-yellow-600 hover:bg-blue-700 text-white font-semibold rounded-md transition duration-200 shadow-lg mt-4">
                    Login
                </button>
            </form>

            <div class="mt-4 text-center">
                <a href="register.php" class="text-gray-600 hover:text-blue-600 font-medium">
                    Create Account
                </a>
            </div>
            
        </div>
    </div>

</body>
</html>