<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | IDV</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;700&display=swap"
        rel="stylesheet">
    <!-- Global Theme Variables -->
    <link rel="stylesheet" href="/assets/css/theme.css">

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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
            showCloseButton: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        window.alert = function (msg) {
            Toast.fire({
                icon: 'info',
                title: msg,
                iconColor: '#1E355F'
            });
        };
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Playfair Display', serif;
        }

        /* Sidebar Polish */
        .sidebar-link {
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border-left-color: rgba(255, 255, 255, 0.5);
        }

        .active-link {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            border-left-color: #ffffff;
            font-weight: 600;
        }

        /* Glass Components */
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(205, 212, 221, 0.5);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #22819A;
        }
    </style>
</head>

<body class="bg-canvas text-dark antialiased font-sans overflow-hidden">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside id="sidebar"
            class="w-64 bg-primary text-white flex flex-col shadow-2xl z-20 transition-all duration-300 relative group/sidebar">
            <button onclick="toggleSidebar()"
                class="absolute -right-3 top-8 bg-[#FF1493] text-white w-6 h-6 rounded-full flex items-center justify-center opacity-0 group-hover/sidebar:opacity-100 transition-opacity z-50 shadow-[0_0_10px_#FF1493]">
                <i class="bi bi-chevron-left text-xs" id="sidebar-icon"></i>
            </button>
            <div class="px-6 py-8 border-b border-white/10">
                <a href="/admin/dashboard.php" class="flex items-center gap-3 group">
                    <div
                        class="bg-secondary p-2 rounded-lg group-hover:rotate-12 transition-transform duration-300 shrink-0">
                        <i class="bi bi-layers-fill text-primary text-xl"></i>
                    </div>
                    <span
                        class="text-xl font-serif font-bold tracking-wide sidebar-text whitespace-nowrap overflow-hidden">ID
                        Viewer</span>
                </a>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto custom-scrollbar overflow-x-hidden">
                <p
                    class="px-4 text-xs font-bold text-blue-200/50 uppercase tracking-widest mb-4 sidebar-text whitespace-nowrap overflow-hidden">
                    Core</p>

                <a href="/admin/dashboard"
                    class="sidebar-link flex items-center px-4 py-3.5 rounded-xl text-sm font-medium transition-all duration-200 group <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/dashboard') !== false ? 'active-link' : 'text-blue-100/70 hover:text-white'; ?>">
                    <i
                        class="bi bi-speedometer2 text-lg mr-3 shrink-0 group-hover:scale-110 transition-transform duration-200 <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/dashboard') !== false ? 'text-white' : 'text-blue-300 group-hover:text-white'; ?>"></i>
                    <span class="sidebar-text whitespace-nowrap overflow-hidden">Dashboard</span>
                </a>

                <a href="/admin/materials"
                    class="sidebar-link flex items-center px-4 py-3.5 rounded-xl text-sm font-medium transition-all duration-200 group <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/materials') !== false ? 'active-link' : 'text-blue-100/70 hover:text-white'; ?>">
                    <i
                        class="bi bi-box-seam text-lg mr-3 shrink-0 group-hover:scale-110 transition-transform duration-200 <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/materials') !== false ? 'text-white' : 'text-blue-300 group-hover:text-white'; ?>"></i>
                    <span class="sidebar-text whitespace-nowrap overflow-hidden">Materials</span>
                </a>

                <a href="/admin/users"
                    class="sidebar-link flex items-center px-4 py-3.5 rounded-xl text-sm font-medium transition-all duration-200 group <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/users') !== false ? 'active-link' : 'text-blue-100/70 hover:text-white'; ?>">
                    <i
                        class="bi bi-people text-lg mr-3 shrink-0 group-hover:scale-110 transition-transform duration-200 <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/users') !== false ? 'text-white' : 'text-blue-300 group-hover:text-white'; ?>"></i>
                    <span class="sidebar-text whitespace-nowrap overflow-hidden">Users</span>
                </a>

                <p
                    class="px-4 text-xs font-bold text-blue-200/50 uppercase tracking-widest mt-8 mb-4 sidebar-text whitespace-nowrap overflow-hidden">
                    Management</p>

                <a href="/admin/quotes"
                    class="sidebar-link flex items-center px-4 py-3.5 rounded-xl text-sm font-medium transition-all duration-200 group <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/quotes') !== false ? 'active-link' : 'text-blue-100/70 hover:text-white'; ?>">
                    <i
                        class="bi bi-file-earmark-text text-lg mr-3 shrink-0 group-hover:scale-110 transition-transform duration-200 <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/quotes') !== false ? 'text-white' : 'text-blue-300 group-hover:text-white'; ?>"></i>
                    <span class="sidebar-text whitespace-nowrap overflow-hidden">Quotes</span>
                </a>

                <a href="#"
                    class="sidebar-link flex items-center px-4 py-3.5 rounded-xl text-sm font-medium transition-all duration-200 group text-blue-100/70 hover:text-white">
                    <i
                        class="bi bi-gear text-lg mr-3 shrink-0 group-hover:scale-110 transition-transform duration-200 text-blue-300 group-hover:text-white"></i>
                    <span class="sidebar-text whitespace-nowrap overflow-hidden">Settings</span>
                </a>
            </nav>

            <div class="px-6 py-6 border-t border-white/10 bg-black/10">
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name=Admin+User&background=D4AF37&color=fff" alt="Admin"
                        class="w-10 h-10 rounded-lg shadow-md">
                    <div class="flex-1 min-w-0 sidebar-text overflow-hidden transition-all duration-300">
                        <p class="text-sm font-bold text-white truncate">Admin User</p>
                        <p class="text-xs text-blue-300 truncate">admin@example.com</p>
                    </div>
                    <a href="/admin/logout.php" class="text-white/50 hover:text-red-400 transition-colors">
                        <i class="bi bi-box-arrow-right text-lg"></i>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Content Area -->
        <div class="flex-1 flex flex-col min-w-0 bg-canvas relative">

            <!-- Top Header -->
            <header
                class="h-20 glass-panel-dark border-b border-white/5 flex items-center justify-between px-8 sticky top-0 z-20 shadow-sm">
                <!-- Mobile Toggle -->
                <button onclick="toggleSidebar()"
                    class="text-gray-400 hover:text-white mr-4 transition-colors p-2 hover:bg-white/5 rounded-lg">
                    <i class="bi bi-layout-sidebar text-xl"></i>
                </button>

                <!-- Top Bar Right -->
                <div class="flex items-center gap-6 ml-auto">
                    <div class="relative hidden md:block">
                        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" placeholder="Search..."
                            class="pl-11 pr-4 py-2.5 bg-white/5 border border-white/10 rounded-full text-sm w-64 text-white placeholder-gray-500 focus:outline-none focus:border-[#FF1493] focus:ring-1 focus:ring-[#FF1493] transition-all">
                    </div>

                    <button class="relative text-gray-400 hover:text-gradient transition-colors">
                        <i class="bi bi-bell text-xl"></i>
                        <span
                            class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-[#FF5E3A] rounded-full border-2 border-transparent shadow-[0_0_5px_#FF5E3A]"></span>
                    </button>

                    <div class="w-px h-8 bg-white/10"></div>

                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-white">Admin User</p>
                            <p class="text-xs text-gradient">Super Admin</p>
                        </div>
                        <i class="bi bi-person-circle text-3xl text-gray-400"></i>
                    </div>
                </div>
            </header>

            <!-- Scrollable Main -->
            <main class="flex-1 overflow-y-auto p-4 md:p-8 scroll-smooth custom-scrollbar bg-canvas relative">
                <div class="w-full space-y-8 z-10 relative">
                    <script>
                        function toggleSidebar() {
                            const sidebar = document.getElementById('sidebar');
                            const texts = document.querySelectorAll('.sidebar-text');
                            const icon = document.getElementById('sidebar-icon');

                            if (sidebar.classList.contains('w-64')) {
                                sidebar.classList.remove('w-64');
                                sidebar.classList.add('w-[84px]');
                                texts.forEach(t => {
                                    t.style.opacity = '0';
                                    setTimeout(() => t.style.display = 'none', 200);
                                });
                                if (icon) {
                                    icon.classList.remove('bi-chevron-left');
                                    icon.classList.add('bi-chevron-right');
                                }
                            } else {
                                sidebar.classList.remove('w-[84px]');
                                sidebar.classList.add('w-64');
                                texts.forEach(t => {
                                    t.style.display = 'block';
                                    setTimeout(() => t.style.opacity = '1', 50);
                                });
                                if (icon) {
                                    icon.classList.remove('bi-chevron-right');
                                    icon.classList.add('bi-chevron-left');
                                }
                            }
                        }
                    </script>