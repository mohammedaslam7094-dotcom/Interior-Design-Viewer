<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/../includes/db.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id=$id");
    header("Location: users.php?success=deleted");
    exit();
}

// Handle Add/Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['user_id'] ?? '';
    $username = $_POST['username'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    if ($id) {
        // Update
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET username=?, email=?, password=?, full_name=?, phone=? WHERE id=?");
            $stmt->bind_param("sssssi", $username, $email, $hashed_password, $full_name, $phone, $id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET username=?, email=?, full_name=?, phone=? WHERE id=?");
            $stmt->bind_param("ssssi", $username, $email, $full_name, $phone, $id);
        }
        $stmt->execute();
    } else {
        // Insert
        // Check if email exists
        $check = $conn->query("SELECT id FROM users WHERE email='$email'");
        if ($check->num_rows == 0) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, phone) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $username, $email, $hashed_password, $full_name, $phone);
            $stmt->execute();
        } else {
            $error = "Email already exists!";
        }
    }
    if (!isset($error)) {
        header("Location: users.php?success=1");
        exit();
    }
}

// Fetch Users
$result = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
?>

<?php include 'includes/header_admin.php'; ?>

<main class="flex-1 overflow-y-auto p-4 md:p-8 scroll-smooth custom-scrollbar bg-canvas">
    <div class="w-full space-y-8">

        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-white/5 pb-6">
            <div>
                <h2 class="text-3xl font-serif font-bold text-gradient mb-1">User Management</h2>
                <p class="text-gray-400">Manage registered users</p>
            </div>
            <button onclick="openModal()"
                class="bg-gradient-primary hover:bg-gradient-premium text-white border-none px-6 py-3 rounded-full font-medium shadow-[var(--shadow-premium)] hover:-translate-y-1 transition-all flex items-center gap-2">
                <i class="bi bi-plus-lg text-lg"></i> Add New User
            </button>
        </div>

        <?php
        $stat_total_users = $conn->query("SELECT COUNT(*) as cnt FROM users")->fetch_assoc()['cnt'] ?? 0;
        $stat_new_users = $conn->query("SELECT COUNT(*) as cnt FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetch_assoc()['cnt'] ?? 0;
        $stat_quotes = $conn->query("SELECT COUNT(*) as cnt FROM quotes")->fetch_assoc()['cnt'] ?? 0;
        ?>

        <!-- Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 mt-6">
            <div
                class="glass-panel-dark p-6 rounded-2xl border border-white/5 relative overflow-hidden group hover:border-[#FF1493]/30 transition-colors">
                <div
                    class="absolute top-0 right-0 w-32 h-32 bg-[#FF1493]/10 rounded-full blur-2xl -mt-10 -mr-10 translate-x-4 group-hover:-translate-x-4 transition-transform duration-700">
                </div>
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-gray-400 text-sm font-semibold uppercase tracking-wider">Total Users</p>
                        <h3 class="text-3xl font-bold text-white mt-1"><?php echo $stat_total_users; ?></h3>
                    </div>
                    <div
                        class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center text-[#FF1493] text-xl border border-white/10">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500"><span class="text-green-400"><i class="bi bi-arrow-up-short"></i> All
                        time</span> registrations</p>
            </div>

            <div
                class="glass-panel-dark p-6 rounded-2xl border border-white/5 relative overflow-hidden group hover:border-[#FF5E3A]/30 transition-colors">
                <div
                    class="absolute top-0 right-0 w-32 h-32 bg-[#FF5E3A]/10 rounded-full blur-2xl -mt-10 -mr-10 translate-x-4 group-hover:-translate-x-4 transition-transform duration-700">
                </div>
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-gray-400 text-sm font-semibold uppercase tracking-wider">New This Month</p>
                        <h3 class="text-3xl font-bold text-white mt-1"><?php echo $stat_new_users; ?></h3>
                    </div>
                    <div
                        class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center text-[#FF5E3A] text-xl border border-white/10">
                        <i class="bi bi-person-plus"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500"><span class="text-orange-400"><i class="bi bi-graph-up"></i>
                        Recent</span> growth</p>
            </div>

            <div
                class="glass-panel-dark p-6 rounded-2xl border border-white/5 relative overflow-hidden group hover:border-purple-500/30 transition-colors">
                <div
                    class="absolute top-0 right-0 w-32 h-32 bg-purple-500/10 rounded-full blur-2xl -mt-10 -mr-10 translate-x-4 group-hover:-translate-x-4 transition-transform duration-700">
                </div>
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-gray-400 text-sm font-semibold uppercase tracking-wider">Avg Insights</p>
                        <h3 class="text-3xl font-bold text-white mt-1 font-mono">
                            <?php echo $stat_total_users > 0 ? round($stat_quotes / $stat_total_users, 1) : 0; ?>
                        </h3>
                    </div>
                    <div
                        class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center text-purple-400 text-xl border border-white/10">
                        <i class="bi bi-activity"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500"><span class="text-green-400"><i class="bi bi-check2-circle"></i>
                        Quotes</span> per user average</p>
            </div>
            <!-- Cards end -->
        </div>

        <?php if (isset($error)): ?>
            <div class="bg-red-50/80 border border-red-500/20 text-red-600 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm backdrop-blur-sm"
                role="alert">
                <svg xmlns="http://www.w3.org/-2000/svg" width="20" height="20" fill="currentColor"
                    class="bi bi-exclamation-triangle-fill shrink-0" viewBox="0 0 16 16">
                    <path
                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                </svg>
                <span class="block sm:inline font-medium text-sm"><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <!-- Users Table -->
        <div class="glass-panel-dark rounded-2xl border border-white/5 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-white/5 border-b border-white/10 text-xs uppercase text-gray-400 font-semibold tracking-wider">
                            <th class="p-6">User</th>
                            <th class="p-6">Contact</th>
                            <th class="p-6">Registered</th>
                            <th class="p-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="p-6">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-premium flex items-center justify-center text-white font-bold shadow-[var(--shadow-premium)]">
                                            <?php echo strtoupper(substr($row['username'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <p class="font-bold text-white">
                                                <?php echo htmlspecialchars($row['full_name']); ?>
                                            </p>
                                            <p class="text-xs text-[#FF1493]">
                                                @<?php echo htmlspecialchars($row['username']); ?>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-6">
                                    <p class="text-sm text-gray-300"><?php echo htmlspecialchars($row['email']); ?></p>
                                    <p class="text-xs text-gray-500"><?php echo htmlspecialchars($row['phone']); ?></p>
                                </td>
                                <td class="p-6 text-sm text-gray-400">
                                    <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                                </td>
                                <td class="p-6 text-right">
                                    <div
                                        class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button onclick='editUser(<?php echo json_encode($row); ?>)'
                                            class="p-2 text-[#FF5E3A] hover:bg-[#FF5E3A]/10 rounded-lg transition-colors"
                                            title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <a href="users.php?delete=<?php echo $row['id']; ?>"
                                            onclick="return confirm('Are you sure? This cannot be undone.')"
                                            class="p-2 text-[#FF1493] hover:bg-[#FF1493]/10 rounded-lg transition-colors"
                                            title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                        <?php if ($result->num_rows == 0): ?>
                            <tr>
                                <td colspan="4" class="p-8 text-center text-gray-500">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Modal -->
<div id="userModal" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal()"></div>

    <!-- Modal Content -->
    <div
        class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-lg glass-panel-dark rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up border border-white/10">
        <div class="p-6 border-b border-white/10 flex justify-between items-center bg-white/5">
            <h3 class="font-serif text-xl font-bold text-gradient" id="modalTitle">Add New User</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-white transition-colors">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        <form method="POST" action="" class="p-6 space-y-4">
            <input type="hidden" id="user_id" name="user_id">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Full Name</label>
                    <input type="text" name="full_name" id="full_name" required
                        class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-4 py-3 text-sm focus:border-[#FF1493] focus:ring-1 focus:ring-[#FF1493] outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Username</label>
                    <input type="text" name="username" id="username" required
                        class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-4 py-3 text-sm focus:border-[#FF1493] focus:ring-1 focus:ring-[#FF1493] outline-none transition-all">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Email</label>
                <input type="email" name="email" id="email" required
                    class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-4 py-3 text-sm focus:border-[#FF1493] focus:ring-1 focus:ring-[#FF1493] outline-none transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Phone</label>
                <input type="text" name="phone" id="phone"
                    class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-4 py-3 text-sm focus:border-[#FF1493] focus:ring-1 focus:ring-[#FF1493] outline-none transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Password <span
                        id="pwd-hint" class="text-xs font-normal normal-case text-gray-500 ml-1"></span></label>
                <input type="password" name="password" id="password"
                    class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-4 py-3 text-sm focus:border-[#FF1493] focus:ring-1 focus:ring-[#FF1493] outline-none transition-all">
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-white/10 mt-6 md:mt-4 pt-6">
                <button type="button" onclick="closeModal()"
                    class="px-6 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/10 hover:text-white transition-colors">Cancel</button>
                <button type="submit"
                    class="px-6 py-2.5 rounded-lg border-none text-sm font-medium bg-gradient-primary text-white hover:bg-gradient-premium shadow-[var(--shadow-premium)] hover:shadow-xl transition-all">Save
                    User</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('userModal').classList.remove('hidden');
        document.getElementById('modalTitle').textContent = 'Add New User';
        document.getElementById('user_id').value = '';
        document.getElementById('full_name').value = '';
        document.getElementById('username').value = '';
        document.getElementById('email').value = '';
        document.getElementById('phone').value = '';
        document.getElementById('password').value = '';
        document.getElementById('password').required = true;
        document.getElementById('pwd-hint').textContent = '';
    }

    function editUser(user) {
        document.getElementById('userModal').classList.remove('hidden');
        document.getElementById('modalTitle').textContent = 'Edit User';
        document.getElementById('user_id').value = user.id;
        document.getElementById('full_name').value = user.full_name;
        document.getElementById('username').value = user.username;
        document.getElementById('email').value = user.email;
        document.getElementById('phone').value = user.phone;
        document.getElementById('password').value = '';
        document.getElementById('password').required = false;
        document.getElementById('pwd-hint').textContent = '(Leave blank to keep current)';
    }

    function closeModal() {
        document.getElementById('userModal').classList.add('hidden');
    }
</script>

<!-- Close HTML tags from header -->
</div>
</main>
</body>

</html>