<?php
require_once __DIR__ . '/../includes/user_auth.php';

require_login();

$user_id = get_current_user_id();
$conn = $GLOBALS['conn']; // Get connection from db.php included in user_auth

// Fetch latest user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Fetch Notifications (All, newest first)
$notif_stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 50");
$notif_stmt->bind_param("i", $user_id);
$notif_stmt->execute();
$notifications = $notif_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Mark all as read when visiting profile (optional, or specific action)
// mark_notifications_read($user_id); 
// Let's keep them unread until dismissed or maybe just mark them read here? 
// For now, let's just leave them as is, or maybe add a "Mark all read" button. 
// Actually, usually visiting the notification center marks them as read. 
// Let's mark them as read for simplicity in this MVP.
mark_notifications_read($user_id);

?>
<?php include __DIR__ . '/../includes/header.php'; ?>

<!-- Hero Section -->
<div class="relative bg-gradient-primary border-none shadow-[var(--shadow-primary)] pt-32 pb-20 overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <svg class="h-full w-full text-white" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none">
            <path d="M0 100 C 20 0 50 0 100 100 Z"></path>
        </svg>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h1 class="font-serif text-4xl md:text-5xl font-bold text-gradient mb-1 mb-4">Your Profile</h1>
        <p class="text-blue-100 text-lg">Manage your account and view notifications</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20 pb-20">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- User Info Card -->
        <div class="glass-panel-dark rounded-2xl shadow-xl p-8 border border-white/5 h-fit">
            <div class="flex flex-col items-center text-center">
                <div
                    class="w-24 h-24 bg-secondary/20 rounded-full flex items-center justify-center text-[#FF1493] text-3xl font-bold mb-4">
                    <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                </div>
                <h2 class="text-2xl font-serif font-bold text-gradient mb-1">
                    <?php echo htmlspecialchars($user['full_name']); ?>
                </h2>
                <p class="text-gray-400">@
                    <?php echo htmlspecialchars($user['username']); ?>
                </p>
            </div>

            <div class="mt-8 space-y-4">
                <div class="flex items-center gap-3 p-3 bg-white/5 rounded-lg">
                    <div class="p-2 glass-panel-dark rounded-md shadow-sm text-primary">
                        <i class="bi bi-envelope text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold">Email</p>
                        <p class="text-sm text-white font-medium">
                            <?php echo htmlspecialchars($user['email']); ?>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 bg-white/5 rounded-lg">
                    <div class="p-2 glass-panel-dark rounded-md shadow-sm text-primary">
                        <i class="bi bi-phone text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold">Phone</p>
                        <p class="text-sm text-white font-medium">
                            <?php echo htmlspecialchars($user['phone']); ?>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 bg-white/5 rounded-lg">
                    <div class="p-2 glass-panel-dark rounded-md shadow-sm text-primary">
                        <i class="bi bi-calendar text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold">Member Since</p>
                        <p class="text-sm text-white font-medium">
                            <?php echo date('M d, Y', strtotime($user['created_at'])); ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-white/5">
                <a href="/logout"
                    class="w-full flex items-center justify-center gap-2 bg-red-500/20 text-red-400 py-3 rounded-lg font-medium hover:bg-red-100 transition-colors">
                    <i class="bi bi-box-arrow-right text-xl"></i> Logout
                </a>
            </div>
        </div>

        <!-- Notifications Area -->
        <div class="lg:col-span-2">
            <div class="glass-panel-dark rounded-2xl shadow-xl border border-white/5 overflow-hidden">
                <div class="p-6 border-b border-white/5 flex items-center justify-between bg-white/5/50">
                    <h3 class="font-serif text-xl font-bold text-gradient mb-1 flex items-center gap-2">
                        <i class="bi bi-bell text-xl text-primary"></i> Notifications
                    </h3>
                    <span class="bg-gradient-primary border-none shadow-[var(--shadow-primary)]/10 text-primary text-xs font-bold px-3 py-1 rounded-full">
                        <?php echo count($notifications); ?> Messages
                    </span>
                </div>

                <div class="divide-y divide-gray-100 max-h-[600px] overflow-y-auto custom-scrollbar">
                    <?php if (empty($notifications)): ?>
                        <div class="p-12 text-center text-gray-400">
                            <i class="bi bi-inbox text-4xl mx-auto mb-4 opacity-50 block"></i>
                            <p>No notifications yet.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($notifications as $notif): ?>
                            <div class="p-6 hover:bg-white/5 transition-colors group relative">
                                <!-- Status Indicator -->
                                <?php if (!$notif['is_read']): ?>
                                    <div class="absolute top-6 left-3 w-1.5 h-1.5 bg-red-500 rounded-full"></div>
                                <?php endif; ?>

                                <div class="flex gap-4">
                                    <div class="flex-shrink-0 mt-1">
                                        <div
                                            class="w-10 h-10 bg-secondary/10 text-[#FF1493] rounded-full flex items-center justify-center">
                                            <i class="bi bi-info-circle text-xl"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-white leading-relaxed">
                                            <?php echo htmlspecialchars($notif['message']); ?>
                                        </p>
                                        <p class="text-xs text-gray-400 mt-2 font-medium">
                                            <?php echo date('F j, Y \a\t g:i a', strtotime($notif['created_at'])); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>