<?php
require_once __DIR__ . '/../includes/user_auth.php';

if (is_user_logged_in()) {
    header("Location: /profile");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (login_user($email, $password)) {
        header("Location: /profile");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Interior Design Viewer</title>
    <!-- Global Theme Variables -->
    <link rel="stylesheet" href="/assets/css/theme.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;700&display=swap"
        rel="stylesheet">
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
</head>

<body class="bg-canvas text-dark min-h-screen flex items-center justify-center relative overflow-hidden">

    <!-- Background Elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
        <div class="absolute -top-20 -left-20 w-96 h-96 bg-secondary/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-primary/10 rounded-full blur-3xl"></div>
    </div>

    <div class="w-full max-w-md p-8 glass-panel-dark rounded-2xl relative z-10">
        <div class="text-center mb-8">
            <h1 class="font-serif text-3xl font-bold text-gradient mb-2">Welcome Back</h1>
            <p class="text-gray-300 font-light">Sign in to continue your journey</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-50/80 border border-red-500/20 text-red-600 px-4 py-3 rounded-xl mb-6 flex items-center gap-3 shadow-sm backdrop-blur-sm"
                role="alert">
                <svg xmlns="http://www.w3.org/-2000/svg" width="20" height="20" fill="currentColor"
                    class="bi bi-exclamation-triangle-fill shrink-0" viewBox="0 0 16 16">
                    <path
                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                </svg>
                <span class="block sm:inline font-medium text-sm">
                    <?php echo htmlspecialchars($error); ?>
                </span>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email Address</label>
                <input type="email" id="email" name="email" required
                    class="w-full px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-[#FF1493] focus:ring-1 focus:ring-[#FF1493] outline-none transition-all duration-300"
                    placeholder="you@example.com">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-[#FF1493] focus:ring-1 focus:ring-[#FF1493] outline-none transition-all duration-300"
                    placeholder="••••••••">
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center text-sm text-gray-400">
                    <input type="checkbox"
                        class="mr-2 rounded border-white/20 bg-white/5 text-[#FF5E3A] focus:ring-[#FF5E3A] focus:ring-offset-0">
                    Remember me
                </label>
                <a href="#" class="text-sm text-[#FF5E3A] hover:text-white font-medium transition-colors">Forgot
                    password?</a>
            </div>

            <button type="submit"
                class="w-full py-3 px-4 bg-gradient-primary hover:bg-gradient-premium text-white font-semibold rounded-lg shadow-[var(--shadow-premium)] hover:-translate-y-0.5 transition-all duration-300 border-none">
                Sign In
            </button>
        </form>

        <div class="mt-8 text-center text-sm text-gray-400">
            <p>Don't have an account?
                <a href="/register" class="font-semibold text-gradient hover:opacity-80 transition-opacity">Create
                    Account</a>
            </p>
        </div>
    </div>
</body>

</html>