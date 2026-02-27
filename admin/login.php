<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin_users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $row['id'];
            header("Location: /admin/dashboard");
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Login | IDV</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Outfit', sans-serif;
        }

        .animated-bg {
            background: radial-gradient(circle at 50% 50%, #1E355F 0%, #0F172A 100%);
            position: relative;
            overflow: hidden;
        }

        .animated-bg::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23D4AF37' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            animation: moveBackground 60s linear infinite;
        }

        @keyframes moveBackground {
            0% {
                transform: translate(0, 0);
            }

            100% {
                transform: translate(50px, 50px);
            }
        }

        .glass-card {
            background: rgba(30, 53, 95, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .input-group:focus-within label {
            color: #FBCF84;
        }

        .input-group:focus-within i {
            color: #FBCF84;
        }
    </style>
</head>

<body class="h-screen flex items-center justify-center animated-bg px-4">

    <!-- Glowing Orbs -->
    <div
        class="fixed top-1/4 left-1/4 w-96 h-96 bg-[#1E355F] rounded-full filter blur-[120px] opacity-[0.2] animate-pulse">
    </div>
    <div class="fixed bottom-1/4 right-1/4 w-64 h-64 bg-[#FBCF84] rounded-full filter blur-[100px] opacity-[0.1]">
    </div>

    <div class="w-full max-w-[400px] glass-card p-8 rounded-3xl relative z-10 animate-[fadeIn_0.5s_ease-out]">
        <!-- Header -->
        <div class="text-center mb-8">
            <div
                class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-[#DDB26A] to-[#FBCF84] flex items-center justify-center text-[#1E355F] font-bold text-3xl shadow-[0_0_20px_rgba(221,178,106,0.4)] mx-auto mb-4 transform hover:rotate-3 transition-transform duration-300">
                I
            </div>
            <h2 class="text-2xl font-bold text-white tracking-tight">Admin Portal</h2>
            <p class="text-gray-500 text-sm mt-1">Sign in to manage your application</p>
        </div>

        <?php if ($error): ?>
            <div
                class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2 animate-[shake_0.4s_ease-in-out]">
                <i class="bi bi-exclamation-circle text-lg"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-5">
            <div class="input-group">
                <label
                    class="block text-gray-500 text-xs font-bold uppercase tracking-wider mb-2 ml-1 transition-colors">Username</label>
                <div class="relative">
                    <span class="absolute left-4 top-3.5"><i
                            class="bi bi-person text-xl text-gray-600 transition-colors"></i></span>
                    <input type="text" name="username" placeholder="Enter username"
                        class="w-full bg-[#0F172A]/50 border border-[#1E355F] text-white rounded-xl py-3 pl-12 pr-4 focus:outline-none focus:border-[#FBCF84] focus:ring-1 focus:ring-[#FBCF84] transition-all placeholder-gray-400"
                        required autocomplete="off">
                </div>
            </div>

            <div class="input-group">
                <label
                    class="block text-gray-500 text-xs font-bold uppercase tracking-wider mb-2 ml-1 transition-colors">Password</label>
                <div class="relative">
                    <span class="absolute left-4 top-3.5"><i
                            class="bi bi-lock text-xl text-gray-600 transition-colors"></i></span>
                    <input type="password" name="password" placeholder="••••••••"
                        class="w-full bg-[#0F172A]/50 border border-[#1E355F] text-white rounded-xl py-3 pl-12 pr-4 focus:outline-none focus:border-[#FBCF84] focus:ring-1 focus:ring-[#FBCF84] transition-all placeholder-gray-400 font-mono tracking-widest"
                        required>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-[#DDB26A] text-[#1E355F] font-bold py-3.5 rounded-xl hover:bg-[#FBCF84] transition-all transform hover:-translate-y-0.5 active:scale-95 shadow-lg shadow-[#DDB26A]/20 flex items-center justify-center gap-2 mt-2">
                Sign In <i class="bi bi-arrow-right"></i>
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-[#222] text-center">
            <a href="/"
                class="text-gray-500 hover:text-white text-xs font-medium transition-colors flex items-center justify-center gap-1 group">
                <i class="bi bi-arrow-left group-hover:-translate-x-1 transition-transform"></i> Back to
                Homepage
            </a>
        </div>
    </div>

    <script>
        // lucide.createIcons();
    </script>
</body>

</html>