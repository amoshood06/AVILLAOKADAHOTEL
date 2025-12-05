<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        /* Custom checkbox styles */
        .custom-checkbox:checked {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }
        /* Helper to position the label on the border */
        .floating-label {
            position: absolute;
            top: -10px;
            left: 12px;
            background-color: white;
            padding: 0 4px;
            font-size: 0.75rem; /* text-xs */
            color: #6b7280; /* text-gray-500 */
            font-weight: 500;
        }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white rounded-[40px] shadow-2xl w-full max-w-[1200px] overflow-hidden flex flex-col md:flex-row min-h-[750px]">

        <div class="hidden md:flex w-1/2 bg-gray-50 items-center justify-center p-12 relative">
            <div class="relative w-full h-full flex flex-col items-center justify-center">
                <img src="https://cdni.iconscout.com/illustration/premium/thumb/user-login-4268415-3551762.png" 
                     alt="Sign Up Illustration" 
                     class="w-full max-w-md object-contain drop-shadow-xl hover:scale-105 transition-transform duration-500">
                
                <div class="flex gap-2 mt-8 absolute bottom-4">
                    <div class="w-8 h-2 bg-blue-600 rounded-full"></div>
                    <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                    <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                </div>
            </div>
        </div>

        <div class="w-full md:w-1/2 p-8 md:p-14 relative flex flex-col justify-center">
            
            <div class="absolute top-8 right-8 md:top-10 md:right-14 flex items-center gap-2">
                <div class="text-blue-600 text-2xl">
                    <i class="fa-solid fa-umbrella"></i> </div>
                <span class="font-bold text-lg text-gray-800 tracking-tight">Your Logo</span>
            </div>

            <div class="mb-8 mt-8 md:mt-0">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Sign up</h2>
                <p class="text-gray-500 text-sm">Let's get you all set up so you can access your personal account.</p>
            </div>

            <form action="#" class="space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="relative">
                        <input type="text" id="firstname" placeholder="John" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition placeholder-gray-300">
                        <label for="firstname" class="floating-label">First Name</label>
                    </div>
                    <div class="relative">
                        <input type="text" id="lastname" placeholder="Doe" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition placeholder-gray-300">
                        <label for="lastname" class="floating-label">Last Name</label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="relative">
                        <input type="email" id="email" placeholder="john.doe@gmail.com" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition placeholder-gray-300">
                        <label for="email" class="floating-label">Email</label>
                    </div>
                    <div class="relative">
                        <input type="tel" id="phone" placeholder="123-456-7890" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition placeholder-gray-300">
                        <label for="phone" class="floating-label">Phone Number</label>
                    </div>
                </div>

                <div class="relative">
                    <input type="password" id="password" placeholder="••••••••••••••••••••••" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition placeholder-gray-300">
                    <label for="password" class="floating-label">Password</label>
                    <button type="button" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i class="fa-regular fa-eye-slash"></i>
                    </button>
                </div>

                <div class="relative">
                    <input type="password" id="confirm_password" placeholder="••••••••••••••••••••••" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition placeholder-gray-300">
                    <label for="confirm_password" class="floating-label">Confirm Password</label>
                    <button type="button" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i class="fa-regular fa-eye-slash"></i>
                    </button>
                </div>

                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="terms" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 custom-checkbox">
                    </div>
                    <label for="terms" class="ml-2 text-sm font-medium text-gray-900">
                        I agree to all the <a href="#" class="text-red-400 hover:underline">Terms</a> and <a href="#" class="text-red-400 hover:underline">Privacy Policies</a>
                    </label>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white font-medium py-3 rounded-lg hover:bg-blue-700 transition duration-200 shadow-md shadow-blue-500/30">
                    Create account
                </button>

                <div class="text-center text-sm font-medium text-gray-800">
                    Already have an account? <a href="#" class="text-red-400 hover:underline">Login</a>
                </div>

                <div class="relative flex py-2 items-center">
                    <div class="flex-grow border-t border-gray-200"></div>
                    <span class="flex-shrink-0 mx-4 text-gray-400 text-xs uppercase">Or Sign up with</span>
                    <div class="flex-grow border-t border-gray-200"></div>
                </div>

                <div class="flex gap-4 justify-between">
                    <button type="button" class="flex-1 py-2 border border-blue-400 rounded-lg text-blue-600 hover:bg-blue-50 transition flex justify-center items-center">
                        <i class="fa-brands fa-facebook-f"></i>
                    </button>
                    <button type="button" class="flex-1 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition flex justify-center items-center">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="h-5 w-5" alt="Google">
                    </button>
                    <button type="button" class="flex-1 py-2 border border-gray-300 rounded-lg text-gray-800 hover:bg-gray-50 transition flex justify-center items-center">
                        <i class="fa-brands fa-apple text-lg"></i>
                    </button>
                </div>

            </form>
        </div>
    </div>
</body>
</html>