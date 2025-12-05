<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        /* Custom checkbox style to match design */
        .custom-checkbox:checked {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white rounded-[30px] shadow-2xl w-full max-w-6xl overflow-hidden flex flex-col md:flex-row min-h-[700px]">

        <div class="w-full md:w-1/2 p-8 md:p-16 flex flex-col justify-center relative">
            
            <div class="flex items-center gap-2 mb-10 text-gray-800">
                <div class="text-blue-600 text-2xl">
                    <i class="fa-solid fa-umbrella"></i> </div>
                <span class="font-bold text-xl tracking-tight">Your Logo</span>
            </div>

            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Login</h2>
                <p class="text-gray-500 text-sm">Login to access your travelwise account</p>
            </div>

            <form action="#" class="space-y-5">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" placeholder="john.doe@gmail.com" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        value="john.doe@gmail.com">
                </div>

                <div class="relative">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" placeholder="••••••••••••••••••••••" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <button type="button" class="absolute right-4 top-[38px] text-gray-400 hover:text-gray-600">
                        <i class="fa-regular fa-eye-slash"></i>
                    </button>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <div class="flex items-center">
                        <input id="remember-me" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded custom-checkbox">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-700">Remember me</label>
                    </div>
                    <a href="#" class="text-sm font-medium text-red-400 hover:text-red-500">Forgot Password</a>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white font-medium py-3 rounded-lg hover:bg-blue-700 transition duration-200 shadow-md shadow-blue-500/30">
                    Login
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-gray-600">
                Don't have an account? <a href="#" class="font-medium text-red-400 hover:text-red-500">Sign up</a>
            </div>

            <div class="relative flex py-8 items-center">
                <div class="flex-grow border-t border-gray-200"></div>
                <span class="flex-shrink-0 mx-4 text-gray-400 text-sm">Or login with</span>
                <div class="flex-grow border-t border-gray-200"></div>
            </div>

            <div class="flex gap-4 justify-between">
                <button class="flex-1 py-2 border border-blue-400 rounded-lg text-blue-600 hover:bg-blue-50 transition flex justify-center items-center">
                    <i class="fa-brands fa-facebook-f"></i>
                </button>
                <button class="flex-1 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition flex justify-center items-center">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="h-5 w-5" alt="Google">
                </button>
                <button class="flex-1 py-2 border border-gray-300 rounded-lg text-gray-800 hover:bg-gray-50 transition flex justify-center items-center">
                    <i class="fa-brands fa-apple text-lg"></i>
                </button>
            </div>
        </div>

        <div class="hidden md:flex w-1/2 bg-gray-50 items-center justify-center p-12 relative rounded-r-[30px]">
            <div class="relative w-full h-full flex flex-col items-center justify-center">
                
                <img src="https://cdni.iconscout.com/illustration/premium/thumb/login-page-4468581-3783954.png" 
                     alt="Security Illustration" 
                     class="w-full max-w-md object-contain drop-shadow-xl animate-float">

                <div class="flex gap-2 mt-8 absolute bottom-10">
                    <div class="w-8 h-2 bg-blue-600 rounded-full"></div>
                    <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                    <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                </div>
             </div>
        </div>

    </div>

</body>
</html>