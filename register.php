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

            <form action="#" method="POST" class="space-y-5">
                
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
                
                <div>
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

</body>
</html>