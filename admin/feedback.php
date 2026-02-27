<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/../includes/db.php';

// Handle Delete
if (isset($_POST['delete_id'])) {
    $conn->query("DELETE FROM feedback WHERE id=" . $_POST['delete_id']);
}

require_once __DIR__ . '/includes/header_admin.php';
?>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gradient mb-1 tracking-tight">User Feedback</h1>
        <p class="text-gray-400 mt-1">Review messages and inquiries submitted via the contact form.</p>
    </div>
    <div class="flex gap-3">
        <button
            class="flex items-center gap-2 glass-panel-dark border border-white/10 text-white px-4 py-2 rounded-lg font-medium hover:bg-white/5 hover:text-primary transition-all hover:border-[#FF1493] shadow-sm">
            <i class="bi bi-funnel"></i> Filter
        </button>
    </div>
</div>

<div class="glass-panel-dark border border-white/10 rounded-2xl overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left font-sans">
            <thead class="bg-white/5 border-b border-white/10 text-gray-400 uppercase text-xs font-bold tracking-wider">
                <tr>
                    <th class="px-6 py-5">User Details</th>
                    <th class="px-6 py-5">Message Content</th>
                    <th class="px-6 py-5">Date Received</th>
                    <th class="px-6 py-5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-structure">
                <?php
                if (isset($conn)) {
                    $msgs = $conn->query("SELECT * FROM feedback ORDER BY created_at DESC");
                    while ($row = $msgs->fetch_assoc()):
                        ?>
                        <tr class="hover:bg-white/5/50 transition-colors group">
                            <td class="px-6 py-5 align-top w-1/4">
                                <div class="flex items-start gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-secondary/10 flex-shrink-0 flex items-center justify-center text-sm font-bold text-[#FF1493] border border-[#FF1493]/20">
                                        <?php echo substr(htmlspecialchars($row['name']), 0, 1); ?>
                                    </div>
                                    <div>
                                        <div
                                            class="text-white font-bold text-sm group-hover:text-primary transition-colors pb-0.5">
                                            <?php echo htmlspecialchars($row['name']); ?>
                                        </div>
                                        <div class="flex items-center gap-1.5 text-xs text-gray-400 mb-1">
                                            <i class="bi bi-envelope"></i>
                                            <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>"
                                                class="hover:text-white transition-colors">
                                                <?php echo htmlspecialchars($row['email']); ?>
                                            </a>
                                        </div>
                                        <?php if (!empty($row['phone'])): ?>
                                            <div class="flex items-center gap-1.5 text-xs text-gray-400">
                                                <i class="bi bi-phone"></i>
                                                <?php echo htmlspecialchars($row['phone']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 align-top">
                                <div
                                    class="bg-white/5 p-3 rounded-xl border border-white/10 text-sm text-white leading-relaxed font-normal">
                                    <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                                </div>
                            </td>
                            <td class="px-6 py-5 align-top w-48">
                                <div class="flex items-center gap-2 text-xs text-gray-400 font-medium">
                                    <i class="bi bi-calendar"></i>
                                    <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-gray-400/70 mt-1 pl-5.5">
                                    <?php echo date('h:i A', strtotime($row['created_at'])); ?>
                                </div>
                            </td>
                            <td class="px-6 py-5 align-top text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>"
                                        class="p-2 text-gray-400 hover:text-white hover:bg-white/10 rounded-lg transition-colors border border-transparent hover:border-white/10"
                                        title="Reply via Email">
                                        <i class="bi bi-reply"></i>
                                    </a>
                                    <form method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this message?');"
                                        class="inline-block">
                                        <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit"
                                            class="p-2 text-red-500/70 hover:text-red-500 hover:bg-red-500/20 rounded-lg transition-colors border border-transparent hover:border-red-100"
                                            title="Delete Message">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if ($msgs->num_rows == 0): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="bi bi-inbox text-4xl mb-3 opacity-50"></i>
                                    <p class="text-lg font-medium">No feedback messages found</p>
                                    <p class="text-sm">Messages from the contact form will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif;
                } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // lucide.createIcons();
</script>
</body>

</html>