<?php
// Set timezone
date_default_timezone_set('UTC');

// Calculate launch date (3 days from now at midnight)
$launchDate = strtotime('+3 days midnight');
$launchDateFormatted = date('l, F j, Y', $launchDate);

// Calculate time remaining
$now = time();
$timeRemaining = $launchDate - $now;

// Calculate days, hours, minutes, seconds
$days = floor($timeRemaining / 86400);
$hours = floor(($timeRemaining % 86400) / 3600);
$minutes = floor(($timeRemaining % 3600) / 60);
$seconds = $timeRemaining % 60;

// Handle form submission
$formMessage = '';
$formSuccess = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formSuccess = true;
        $formMessage = "Thanks! We'll notify you at launch.";
    } else {
        $formMessage = "Please enter a valid email address.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AVILLAOKADAHOTEL - Coming Soon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        background: '#030712',
                        foreground: '#f9fafb',
                        muted: '#111827',
                        'muted-foreground': '#9ca3af',
                        border: '#1f2937',
                        accent: '#6366f1',
                        'accent-light': '#818cf8',
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'glow': 'glow 2s ease-in-out infinite alternate',
                    }
                }
            }
        }
    </script>
    <!-- Added Google Fonts for better typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Space Grotesk', sans-serif; }
        
        /* Added custom animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        @keyframes glow {
            from { box-shadow: 0 0 20px rgba(99, 102, 241, 0.2); }
            to { box-shadow: 0 0 40px rgba(99, 102, 241, 0.4); }
        }
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes flip {
            0% { transform: rotateX(0deg); }
            50% { transform: rotateX(-90deg); }
            100% { transform: rotateX(0deg); }
        }
        
        .animate-gradient {
            background-size: 200% 200%;
            animation: gradient 8s ease infinite;
        }
        .countdown-card {
            perspective: 1000px;
        }
        .countdown-card .flip {
            animation: flip 0.6s ease-in-out;
        }
        
        /* Added particle background */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
            z-index: 0;
        }
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(99, 102, 241, 0.3);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-background text-foreground min-h-screen flex flex-col relative overflow-x-hidden">
    <!-- Added animated particle background -->
    <div class="particles" id="particles"></div>
    
    <!-- Added gradient orbs for visual depth -->
    <div class="fixed top-1/4 -left-32 w-96 h-96 bg-accent/20 rounded-full blur-3xl opacity-30 animate-pulse-slow"></div>
    <div class="fixed bottom-1/4 -right-32 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl opacity-30 animate-pulse-slow" style="animation-delay: 1.5s;"></div>

    <!-- Header -->
    <header class="relative z-10 flex items-center justify-between px-6 md:px-12 py-6 border-b border-border/30 backdrop-blur-sm">
        <div class="font-display font-semibold tracking-tight text-xl flex items-center gap-2">
            <!-- Added logo icon -->
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-accent to-purple-500 flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            LAUNCH<span class="text-accent">AVILLAOKADAHOTEL</span>
        </div>
        <nav class="hidden md:flex items-center gap-8 text-sm text-muted-foreground">
            <a href="#" class="hover:text-foreground transition-colors duration-300">About</a>
            <a href="#" class="hover:text-foreground transition-colors duration-300">Features</a>
            <a href="#" class="hover:text-foreground transition-colors duration-300">Contact</a>
        </nav>
        <!-- Enhanced coming soon badge -->
        <div class="text-xs text-accent uppercase tracking-widest px-3 py-1 border border-accent/30 rounded-full bg-accent/10">
            Coming Soon
        </div>
    </header>

    <!-- Main Content -->
    <main class="relative z-10 flex-1 flex flex-col items-center justify-center px-6 py-12 md:py-24">
        <!-- Added animated pre-headline badge -->
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-muted/50 border border-border rounded-full mb-8 backdrop-blur-sm">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-accent"></span>
            </span>
            <span class="text-muted-foreground text-sm uppercase tracking-[0.2em]">Something Big is Coming</span>
        </div>

        <!-- Enhanced main headline with gradient text -->
        <h1 class="font-display text-4xl md:text-6xl lg:text-8xl font-bold text-center max-w-5xl leading-tight mb-6">
            The Future of<br>
            <span class="bg-gradient-to-r from-accent via-purple-400 to-pink-400 bg-clip-text text-transparent animate-gradient">Digital Experience</span>
        </h1>

        <!-- Subheadline -->
        <p class="text-muted-foreground text-lg md:text-xl text-center max-w-2xl mb-16">
            We're crafting something extraordinary. Be the first to experience it when we launch on <span class="text-foreground font-medium"><?php echo $launchDateFormatted; ?></span>
        </p>

        <!-- Enhanced Countdown Timer with better styling -->
        <div class="flex items-center justify-center gap-3 md:gap-6 mb-16">
            <!-- Days -->
            <div class="countdown-card flex flex-col items-center group">
                <div class="relative w-20 h-24 md:w-32 md:h-36 bg-gradient-to-b from-muted to-muted/50 border border-border rounded-2xl flex items-center justify-center mb-3 overflow-hidden group-hover:border-accent/50 transition-all duration-500" style="animation: glow 2s ease-in-out infinite alternate;">
                    <div class="absolute inset-x-0 top-1/2 h-px bg-border/50"></div>
                    <span id="days" class="font-display text-4xl md:text-6xl font-bold bg-gradient-to-b from-foreground to-foreground/70 bg-clip-text text-transparent"><?php echo str_pad($days, 2, '0', STR_PAD_LEFT); ?></span>
                </div>
                <span class="text-muted-foreground text-xs uppercase tracking-[0.2em] group-hover:text-accent transition-colors duration-300">Days</span>
            </div>

            <span class="text-3xl md:text-5xl text-accent font-light animate-pulse">:</span>

            <!-- Hours -->
            <div class="countdown-card flex flex-col items-center group">
                <div class="relative w-20 h-24 md:w-32 md:h-36 bg-gradient-to-b from-muted to-muted/50 border border-border rounded-2xl flex items-center justify-center mb-3 overflow-hidden group-hover:border-accent/50 transition-all duration-500" style="animation: glow 2s ease-in-out infinite alternate; animation-delay: 0.5s;">
                    <div class="absolute inset-x-0 top-1/2 h-px bg-border/50"></div>
                    <span id="hours" class="font-display text-4xl md:text-6xl font-bold bg-gradient-to-b from-foreground to-foreground/70 bg-clip-text text-transparent"><?php echo str_pad($hours, 2, '0', STR_PAD_LEFT); ?></span>
                </div>
                <span class="text-muted-foreground text-xs uppercase tracking-[0.2em] group-hover:text-accent transition-colors duration-300">Hours</span>
            </div>

            <span class="text-3xl md:text-5xl text-accent font-light animate-pulse">:</span>

            <!-- Minutes -->
            <div class="countdown-card flex flex-col items-center group">
                <div class="relative w-20 h-24 md:w-32 md:h-36 bg-gradient-to-b from-muted to-muted/50 border border-border rounded-2xl flex items-center justify-center mb-3 overflow-hidden group-hover:border-accent/50 transition-all duration-500" style="animation: glow 2s ease-in-out infinite alternate; animation-delay: 1s;">
                    <div class="absolute inset-x-0 top-1/2 h-px bg-border/50"></div>
                    <span id="minutes" class="font-display text-4xl md:text-6xl font-bold bg-gradient-to-b from-foreground to-foreground/70 bg-clip-text text-transparent"><?php echo str_pad($minutes, 2, '0', STR_PAD_LEFT); ?></span>
                </div>
                <span class="text-muted-foreground text-xs uppercase tracking-[0.2em] group-hover:text-accent transition-colors duration-300">Minutes</span>
            </div>

            <span class="text-3xl md:text-5xl text-accent font-light animate-pulse">:</span>

            <!-- Seconds -->
            <div class="countdown-card flex flex-col items-center group">
                <div class="relative w-20 h-24 md:w-32 md:h-36 bg-gradient-to-b from-muted to-muted/50 border border-border rounded-2xl flex items-center justify-center mb-3 overflow-hidden group-hover:border-accent/50 transition-all duration-500" style="animation: glow 2s ease-in-out infinite alternate; animation-delay: 1.5s;">
                    <div class="absolute inset-x-0 top-1/2 h-px bg-border/50"></div>
                    <span id="seconds" class="font-display text-4xl md:text-6xl font-bold bg-gradient-to-b from-foreground to-foreground/70 bg-clip-text text-transparent"><?php echo str_pad($seconds, 2, '0', STR_PAD_LEFT); ?></span>
                </div>
                <span class="text-muted-foreground text-xs uppercase tracking-[0.2em] group-hover:text-accent transition-colors duration-300">Seconds</span>
            </div>
        </div>

        <!-- Enhanced Notify Form with better styling -->
        <div class="w-full max-w-lg">
            <?php if ($formSuccess): ?>
                <div class="text-center p-6 bg-gradient-to-r from-accent/10 to-purple-500/10 border border-accent/30 rounded-2xl backdrop-blur-sm">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-accent/20 flex items-center justify-center">
                        <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="text-foreground text-lg font-medium"><?php echo $formMessage; ?></p>
                    <p class="text-muted-foreground text-sm mt-2">We'll keep you updated on our progress.</p>
                </div>
            <?php else: ?>
                <form method="POST" class="relative" id="notify-form">
                    <div class="flex flex-col sm:flex-row gap-3 p-2 bg-muted/50 border border-border rounded-2xl backdrop-blur-sm">
                        <div class="relative flex-1">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <input 
                                type="email" 
                                name="email" 
                                id="email-input"
                                placeholder="Enter your email address" 
                                required
                                class="w-full pl-12 pr-4 py-4 bg-transparent text-foreground placeholder:text-muted-foreground focus:outline-none text-base"
                            >
                        </div>
                        <button 
                            type="submit"
                            id="submit-btn"
                            class="px-8 py-4 bg-gradient-to-r from-accent to-purple-500 text-white font-semibold rounded-xl hover:opacity-90 transition-all duration-300 hover:shadow-lg hover:shadow-accent/25 flex items-center justify-center gap-2 group"
                        >
                            <span>Notify Me</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                    <?php if ($formMessage): ?>
                        <p class="text-red-400 text-sm mt-3 text-center flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <?php echo $formMessage; ?>
                        </p>
                    <?php endif; ?>
                </form>
                <p class="text-muted-foreground text-xs text-center mt-4">Join 2,500+ others waiting for launch. No spam, we promise.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- Enhanced Footer with social links -->
    <footer class="relative z-10 border-t border-border/30 px-6 md:px-12 py-8 backdrop-blur-sm">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <p class="text-muted-foreground text-sm">&copy; 2025 LaunchHQ. All rights reserved.</p>
            <div class="flex items-center gap-4">
                <a href="#" class="w-10 h-10 rounded-full bg-muted border border-border flex items-center justify-center text-muted-foreground hover:text-accent hover:border-accent/50 transition-all duration-300">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                <a href="#" class="w-10 h-10 rounded-full bg-muted border border-border flex items-center justify-center text-muted-foreground hover:text-accent hover:border-accent/50 transition-all duration-300">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                </a>
                <a href="#" class="w-10 h-10 rounded-full bg-muted border border-border flex items-center justify-center text-muted-foreground hover:text-accent hover:border-accent/50 transition-all duration-300">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                </a>
            </div>
        </div>
    </footer>

    <!-- Enhanced JavaScript with animations and particle effects -->
    <script>
        // Launch date from PHP (Unix timestamp in milliseconds)
        const launchDate = <?php echo $launchDate * 1000; ?>;
        
        // Previous values for flip animation
        let prevValues = {
            days: document.getElementById('days').textContent,
            hours: document.getElementById('hours').textContent,
            minutes: document.getElementById('minutes').textContent,
            seconds: document.getElementById('seconds').textContent
        };

        function updateCountdown() {
            const now = Date.now();
            let timeRemaining = Math.max(0, Math.floor((launchDate - now) / 1000));

            const days = String(Math.floor(timeRemaining / 86400)).padStart(2, '0');
            const hours = String(Math.floor((timeRemaining % 86400) / 3600)).padStart(2, '0');
            const minutes = String(Math.floor((timeRemaining % 3600) / 60)).padStart(2, '0');
            const seconds = String(timeRemaining % 60).padStart(2, '0');

            // Update with flip animation
            updateWithFlip('days', days);
            updateWithFlip('hours', hours);
            updateWithFlip('minutes', minutes);
            updateWithFlip('seconds', seconds);
        }
        
        function updateWithFlip(id, newValue) {
            const element = document.getElementById(id);
            if (prevValues[id] !== newValue) {
                element.style.transform = 'scale(1.1)';
                element.style.transition = 'transform 0.15s ease-out';
                setTimeout(() => {
                    element.textContent = newValue;
                    element.style.transform = 'scale(1)';
                }, 150);
                prevValues[id] = newValue;
            }
        }

        // Update every second
        setInterval(updateCountdown, 1000);
        
        // Create floating particles
        function createParticles() {
            const container = document.getElementById('particles');
            const particleCount = 30;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 8 + 's';
                particle.style.animationDuration = (Math.random() * 4 + 6) + 's';
                particle.style.opacity = Math.random() * 0.5 + 0.2;
                particle.style.width = (Math.random() * 4 + 2) + 'px';
                particle.style.height = particle.style.width;
                container.appendChild(particle);
            }
        }
        
        createParticles();
        
        // Form interaction enhancements
        const form = document.getElementById('notify-form');
        const emailInput = document.getElementById('email-input');
        const submitBtn = document.getElementById('submit-btn');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                submitBtn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Submitting...</span>';
            });
            
            emailInput.addEventListener('focus', function() {
                this.parentElement.parentElement.classList.add('ring-2', 'ring-accent/30');
            });
            
            emailInput.addEventListener('blur', function() {
                this.parentElement.parentElement.classList.remove('ring-2', 'ring-accent/30');
            });
        }
    </script>
</body>
</html>
