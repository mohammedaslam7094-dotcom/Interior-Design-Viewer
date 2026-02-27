<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/../includes/db.php';

// Handle Status Update
if (isset($_POST['update_status'])) {
    $id = $_POST['quote_id'];
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE quotes SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
}

require_once __DIR__ . '/includes/header_admin.php';
?>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 border-b border-white/5 pb-6">
    <div>
        <h1 class="text-3xl font-bold text-gradient tracking-tight">Quote Requests</h1>
        <p class="text-gray-400 mt-1">Manage client inquiries and track project status.</p>
    </div>
    <div class="flex gap-3">
        <button
            class="flex items-center gap-2 bg-white/5 border border-white/10 text-white px-4 py-2 rounded-lg font-medium hover:bg-white/10 hover:border-[#FF1493] transition-all shadow-sm">
            <i class="bi bi-download"></i> Export CSV
        </button>
    </div>
</div>

<?php
$stat_total = $conn->query("SELECT COUNT(*) as cnt FROM quotes")->fetch_assoc()['cnt'];
$stat_pending = $conn->query("SELECT COUNT(*) as cnt FROM quotes WHERE status='pending'")->fetch_assoc()['cnt'];
$stat_revenue_query = $conn->query("SELECT SUM(total_price) as sum FROM quotes WHERE status='completed'");
$stat_revenue = $stat_revenue_query->fetch_assoc()['sum'] ?? 0;
?>

<!-- Overview Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div
        class="glass-panel-dark p-6 rounded-2xl border border-white/5 relative overflow-hidden group hover:border-[#FF1493]/30 transition-colors">
        <div
            class="absolute top-0 right-0 w-32 h-32 bg-[#FF1493]/10 rounded-full blur-2xl -mt-10 -mr-10 translate-x-4 group-hover:-translate-x-4 transition-transform duration-700">
        </div>
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-gray-400 text-sm font-semibold uppercase tracking-wider">Total Quotes</p>
                <h3 class="text-3xl font-bold text-white mt-1"><?php echo $stat_total; ?></h3>
            </div>
            <div
                class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center text-[#FF1493] text-xl border border-white/10">
                <i class="bi bi-file-earmark-text"></i>
            </div>
        </div>
        <p class="text-xs text-gray-500"><span class="text-green-400"><i class="bi bi-arrow-up-short"></i> All
                time</span> volume</p>
    </div>

    <div
        class="glass-panel-dark p-6 rounded-2xl border border-white/5 relative overflow-hidden group hover:border-[#FF5E3A]/30 transition-colors">
        <div
            class="absolute top-0 right-0 w-32 h-32 bg-[#FF5E3A]/10 rounded-full blur-2xl -mt-10 -mr-10 translate-x-4 group-hover:-translate-x-4 transition-transform duration-700">
        </div>
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-gray-400 text-sm font-semibold uppercase tracking-wider">Pending Action</p>
                <h3 class="text-3xl font-bold text-white mt-1"><?php echo $stat_pending; ?></h3>
            </div>
            <div
                class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center text-[#FF5E3A] text-xl border border-white/10">
                <i class="bi bi-clock-history"></i>
            </div>
        </div>
        <p class="text-xs text-gray-500"><span class="text-orange-400"><i class="bi bi-exclamation-circle"></i>
                Requires</span> attention</p>
    </div>

    <div
        class="glass-panel-dark p-6 rounded-2xl border border-white/5 relative overflow-hidden group hover:border-purple-500/30 transition-colors">
        <div
            class="absolute top-0 right-0 w-32 h-32 bg-purple-500/10 rounded-full blur-2xl -mt-10 -mr-10 translate-x-4 group-hover:-translate-x-4 transition-transform duration-700">
        </div>
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-gray-400 text-sm font-semibold uppercase tracking-wider">Completed Est.</p>
                <h3 class="text-3xl font-bold text-white mt-1 font-mono">₹<?php echo number_format($stat_revenue); ?>
                </h3>
            </div>
            <div
                class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center text-purple-400 text-xl border border-white/10">
                <i class="bi bi-cash-stack"></i>
            </div>
        </div>
        <p class="text-xs text-gray-500"><span class="text-green-400"><i class="bi bi-check2-circle"></i>
                Successfully</span> finalized</p>
    </div>
</div>

<div class="glass-panel-dark border border-white/5 rounded-2xl overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-white/5 border-b border-white/10 text-gray-400 uppercase text-xs font-bold tracking-wider">
                <tr>
                    <th class="px-6 py-5">Client Details</th>
                    <th class="px-6 py-5">Project Scope</th>
                    <th class="px-6 py-5">Est. Cost</th>
                    <th class="px-6 py-5">Current Status</th>
                    <th class="px-6 py-5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                <?php
                $quotes = $conn->query("SELECT q.*, m.name as m_name, m.price_per_sqft, m.image_url FROM quotes q JOIN materials m ON q.material_id = m.id ORDER BY q.created_at DESC");
                while ($row = $quotes->fetch_assoc()):
                    ?>
                    <tr class="hover:bg-white/5 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-gradient-premium flex items-center justify-center text-sm font-bold text-white shadow-[var(--shadow-premium)]">
                                    <?php echo substr($row['user_name'], 0, 1); ?>
                                </div>
                                <div>
                                    <div class="text-white font-bold text-sm group-hover:text-[#FF1493] transition-colors">
                                        <?php echo $row['user_name']; ?>
                                    </div>
                                    <div class="text-xs flex items-center gap-1.5 text-gray-400 mt-0.5">
                                        <i class="bi bi-envelope"></i> <?php echo $row['user_email']; ?>
                                    </div>
                                    <div class="text-xs flex items-center gap-1.5 text-gray-400 mt-0.5">
                                        <i class="bi bi-phone"></i> <?php echo $row['user_phone']; ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="<?php echo $row['image_url']; ?>"
                                    class="w-10 h-10 rounded object-cover border border-white/20 shadow-sm shadow-[#FF1493]/30">
                                <div>
                                    <div class="text-sm text-white font-medium">
                                        <?php echo $row['m_name']; ?>
                                    </div>
                                    <div class="text-xs text-gray-400 mt-0.5">
                                        <?php echo $row['area_sqft']; ?> sq.ft <span class="text-gray-600">•</span>
                                        ₹<?php echo $row['price_per_sqft']; ?>/sq.ft
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="text-[#FF5E3A] font-bold tracking-tight text-lg font-mono drop-shadow-[0_0_8px_rgba(255,94,58,0.5)]">
                                ₹<?php echo number_format($row['total_price'], 2); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <?php
                            $statusColors = [
                                'pending' => 'bg-orange-500/20 text-orange-400 border-orange-500/30',
                                'contacted' => 'bg-[#FF1493]/20 text-[#FF1493] border-[#FF1493]/30',
                                'completed' => 'bg-green-500/20 text-green-400 border-green-500/30'
                            ];
                            $colorClass = $statusColors[$row['status']] ?? 'bg-white/10 text-gray-400 border-white/20';
                            ?>
                            <span
                                class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider border <?php echo $colorClass; ?>">
                                <?php echo ucfirst($row['status']); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form method="POST" class="flex items-center justify-end gap-2">
                                <input type="hidden" name="quote_id" value="<?php echo $row['id']; ?>">
                                <div class="relative">
                                    <select name="status"
                                        class="bg-white/5 border border-white/10 text-xs font-medium rounded-lg pl-3 pr-8 py-2 text-white focus:border-[#FF1493] focus:ring-1 focus:ring-[#FF1493] outline-none transition-all appearance-none cursor-pointer hover:border-white/30 shadow-sm">
                                        <option class="bg-gray-900" value="pending" <?php if ($row['status'] == 'pending')
                                            echo 'selected'; ?>>Pending</option>
                                        <option class="bg-gray-900" value="contacted" <?php if ($row['status'] == 'contacted')
                                            echo 'selected'; ?>>Contacted</option>
                                        <option class="bg-gray-900" value="completed" <?php if ($row['status'] == 'completed')
                                            echo 'selected'; ?>>Completed</option>
                                    </select>
                                    <i
                                        class="bi bi-chevron-down absolute right-2 top-2.5 text-gray-400 pointer-events-none"></i>
                                </div>
                                <button type="submit" name="update_status"
                                    class="bg-gradient-primary hover:bg-gradient-premium text-white p-2 rounded-lg transition-colors shadow-[var(--shadow-premium)]"
                                    title="Update Status">
                                    <i class="bi bi-arrow-repeat"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <?php if ($quotes->num_rows == 0): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <i class="bi bi-inbox text-4xl mb-3 opacity-50"></i>
                                <p class="text-lg font-medium">No quotes found</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // lucide.createIcons();
</script>
</body>

</html>