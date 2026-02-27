<?php require_once __DIR__ . '/user_auth.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interior Design Viewer | Premium Visualization</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;700&display=swap"
        rel="stylesheet">

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Global Theme Variables -->
    <link rel="stylesheet" href="/assets/css/theme.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: 'hsl(var(--color-primary) / <alpha-value>)',
                        secondary: 'hsl(var(--color-secondary) / <alpha-value>)',
                        canvas: 'hsl(var(--color-canvas) / <alpha-value>)',
                        structure: 'hsl(var(--color-structure) / <alpha-value>)',
                        dark: 'hsl(var(--color-dark) / <alpha-value>)',
                        muted: 'hsl(var(--color-muted) / <alpha-value>)',
                        accent: 'hsl(var(--color-accent) / <alpha-value>)',
                        glass: 'rgba(255, 255, 255, 0.05)',
                        'gold-dark': 'hsl(var(--color-secondary) / <alpha-value>)',
                    },
                    backgroundImage: {
                        'gradient-premium': 'var(--gradient-premium)',
                        'gradient-primary': 'var(--gradient-primary)',
                        'gradient-glass': 'var(--gradient-glass)',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    }
                }
            }
        }
    </script>

    <!-- SweetAlert2 for Professional Native Alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Override default alert with professional Toast
        const Toast = Swal.mixin({
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: {
                popup: 'colored-toast'
            },
            showCloseButton: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        window.alert = function(msg) {
            Toast.fire({
                icon: 'info',
                title: msg,
                iconColor: '#1E355F'
            });
        };
    </script>

    <!-- Custom CSS -->
    <style>
        .glass-nav {
            background: var(--gradient-glass-dark);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: var(--shadow-glass);
        }

        .nav-link {
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background: var(--gradient-premium);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }
    </style>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="overflow-x-hidden">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass-nav transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 cursor-pointer flex items-center gap-2" onclick="window.location.href='/'">
                    <img src="assets/images/logo.svg" alt="IDV Logo" class="h-12 w-auto">
                    <span class="sr-only">Interior Design Viewer</span>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-8">
                        <a href="/"
                            class="nav-link text-white/90 hover:text-secondary px-3 py-2 rounded-md text-sm font-medium transition-colors">Home</a>
                        <a href="/materials"
                            class="nav-link text-white/90 hover:text-secondary px-3 py-2 rounded-md text-sm font-medium transition-colors">Materials</a>
                        <a href="/visualizer"
                            class="nav-link text-white/90 hover:text-secondary px-3 py-2 rounded-md text-sm font-medium transition-colors">Visualizer</a>
                        <a href="/budget"
                            class="nav-link text-white/90 hover:text-secondary px-3 py-2 rounded-md text-sm font-medium transition-colors">Budget</a>
                        <a href="/wishlist"
                            class="nav-link text-white/90 hover:text-secondary px-3 py-2 rounded-md text-sm font-medium transition-colors">Wishlist</a>
                        <a href="/about"
                            class="nav-link text-white/90 hover:text-secondary px-3 py-2 rounded-md text-sm font-medium transition-colors">About</a>
                        <a href="/contact"
                            class="nav-link text-white/90 hover:text-secondary px-3 py-2 rounded-md text-sm font-medium transition-colors">Contact</a>
                    </div>
                </div>

                <!-- Action Icons -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="/wishlist" class="text-white/90 hover:text-secondary transition-colors relative">
                        <i class="bi bi-heart text-xl"></i>
                        <span id="wishlist-count"
                            class="absolute -top-2 -right-2 bg-secondary text-black text-xs font-bold rounded-full h-4 w-4 flex items-center justify-center">0</span>
                    </a>

                    <?php if (is_user_logged_in()): ?>
                        <!-- Notification Bell -->
                        <div class="relative group">
                            <button class="text-white/90 hover:text-secondary transition-colors relative">
                                <i class="bi bi-bell text-xl"></i>
                                <?php
                                $unread_notifications = get_unread_notifications(get_current_user_id());
                                $unread_count = count($unread_notifications);
                                if ($unread_count > 0):
                                    ?>
                                    <span
                                        class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-4 w-4 flex items-center justify-center animate-pulse">
                                        <?php echo $unread_count; ?>
                                    </span>
                                <?php endif; ?>
                            </button>
                            <!-- Notification Dropdown -->
                            <div
                                class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl overflow-hidden hidden group-hover:block transition-all z-50 border border-gray-100">
                                <div class="p-3 bg-gray-50 border-b border-gray-100 font-serif font-bold text-gray-800">
                                    Notifications</div>
                                <div class="max-h-64 overflow-y-auto custom-scrollbar">
                                    <?php if ($unread_count > 0): ?>
                                        <?php foreach ($unread_notifications as $notif): ?>
                                            <div class="p-3 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                                <p class="text-sm text-gray-800 font-medium">
                                                    <?php echo htmlspecialchars($notif['message']); ?>
                                                </p>
                                                <span
                                                    class="text-xs text-muted block mt-1"><?php echo date('M j, g:i a', strtotime($notif['created_at'])); ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="p-6 text-center text-sm text-muted">
                                            <i class="bi bi-bell-slash text-2xl mb-2 block opacity-50"></i>
                                            No new notifications
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- User Profile Dropdown -->
                        <!-- User Profile Dropdown -->
                        <div class="relative">
                            <button type="button" id="profile-menu-button"
                                class="flex items-center gap-2 text-white/90 hover:text-secondary transition-colors focus:outline-none">
                                <i class="bi bi-person-circle text-xl"></i>
                                <span
                                    class="text-sm font-medium hidden lg:inline-block"><?php echo htmlspecialchars(get_current_user_name()); ?></span>
                                <i class="bi bi-chevron-down text-xs ml-0.5 opacity-70"></i>
                            </button>
                            <div id="profile-menu-content"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl overflow-hidden hidden transition-all z-50 border border-gray-100">
                                <a href="/profile"
                                    class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors border-b border-gray-50">
                                    <i class="bi bi-person me-2"></i> Profile
                                </a>
                                <a href="/logout"
                                    class="block px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="/login"
                            class="text-white/90 hover:text-secondary transition-colors font-medium text-sm">Login</a>
                        <a href="/register"
                            class="bg-secondary text-dark px-6 py-2 rounded-full font-bold text-sm hover:bg-white hover:text-primary transition-all shadow-lg shadow-secondary/20">Register</a>
                    <?php endif; ?>
                </div>

                <div class="-mr-2 flex md:hidden">
                    <button type="button"
                        class="inline-flex items-center justify-center p-2 rounded-md text-white/90 hover:text-white hover:bg-white/10 focus:outline-none"
                        aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden hidden bg-primary/95 backdrop-blur-md border-t border-white/10" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="/"
                    class="text-white hover:text-secondary block px-3 py-2 rounded-md text-base font-medium">Home</a>
                <a href="/materials"
                    class="text-white hover:text-secondary block px-3 py-2 rounded-md text-base font-medium">Materials</a>
                <a href="/visualizer"
                    class="text-white hover:text-secondary block px-3 py-2 rounded-md text-base font-medium">Visualizer</a>
                <a href="/budget"
                    class="text-white hover:text-secondary block px-3 py-2 rounded-md text-base font-medium">Budget</a>
                <a href="/wishlist"
                    class="text-white hover:text-secondary block px-3 py-2 rounded-md text-base font-medium">Wishlist</a>
                <a href="/about"
                    class="text-white hover:text-secondary block px-3 py-2 rounded-md text-base font-medium">About</a>
                <a href="/contact"
                    class="text-white hover:text-secondary block px-3 py-2 rounded-md text-base font-medium">Contact</a>

                <?php if (is_user_logged_in()): ?>
                    <div class="border-t border-white/10 pt-4 pb-3">
                        <div class="flex items-center px-5">
                            <div class="flex-shrink-0">
                                <i class="bi bi-person-circle text-3xl text-gray-400"></i>
                            </div>
                            <div class="ml-3">
                                <div class="text-base font-medium leading-none text-white">
                                    <?php echo htmlspecialchars(get_current_user_name()); ?>
                                </div>
                                <div class="text-sm font-medium leading-none text-gray-400 mt-1">
                                    <?php echo $_SESSION['user_email'] ?? ''; ?>
                                </div>
                            </div>
                            <button
                                class="ml-auto flex-shrink-0 p-1 rounded-full text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white relative">
                                <span class="sr-only">View notifications</span>
                                <i class="bi bi-bell text-xl"></i>
                                <?php
                                $mob_unread = count(get_unread_notifications(get_current_user_id()));
                                if ($mob_unread > 0):
                                    ?>
                                    <span
                                        class="absolute top-0 right-0 block h-2 w-2 rounded-full ring-2 ring-gray-800 bg-red-500"></span>
                                <?php endif; ?>
                            </button>
                        </div>
                        <div class="mt-3 px-2 space-y-1">
                            <a href="/profile"
                                class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700">Your
                                Profile</a>
                            <a href="/logout"
                                class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700">Sign
                                out</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="pt-4 pb-3 border-t border-gray-700">
                        <a href="/login"
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700">Login</a>
                        <a href="/register"
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700">Register</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Mobile Menu
            const btn = document.querySelector('button[aria-controls="mobile-menu"]');
            const menu = document.getElementById('mobile-menu');

            if (btn && menu) {
                btn.addEventListener('click', function () {
                    menu.classList.toggle('hidden');
                    const isExpanded = btn.getAttribute('aria-expanded') === 'true';
                    btn.setAttribute('aria-expanded', !isExpanded);
                });
            }

            // Profile Dropdown
            const profileBtn = document.getElementById('profile-menu-button');
            const profileMenu = document.getElementById('profile-menu-content');

            if (profileBtn && profileMenu) {
                profileBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    profileMenu.classList.toggle('hidden');
                });

                document.addEventListener('click', function (e) {
                    if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
                        profileMenu.classList.add('hidden');
                    }
                });
            }
        });
    </script>
    <div class="pt-20"> <!-- Spacer for fixed nav -->