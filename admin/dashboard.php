<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/../includes/db.php';

// Fetch stats
$materials_count = $conn->query("SELECT COUNT(*) as count FROM materials")->fetch_assoc()['count'];
$quotes_count = $conn->query("SELECT COUNT(*) as count FROM quotes")->fetch_assoc()['count'];
$pending_quotes = $conn->query("SELECT COUNT(*) as count FROM quotes WHERE status='pending'")->fetch_assoc()['count'];

require_once __DIR__ . '/includes/header_admin.php';
?>

<!-- Welcome Banner -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gradient mb-1 tracking-tight">Dashboard Overview</h1>
        <p class="text-gray-400 mt-1">Welcome back, here's what's happening with your store today.</p>
    </div>
    <div class="flex items-center gap-3">
        <button class="flex items-center gap-2 glass-panel-dark hover:bg-white/5 border border-white/10 text-white px-4 py-2 rounded-lg transition-colors text-sm font-medium shadow-sm">
            <i class="bi bi-download"></i> Export Report
        </button>
        <a href="/admin/materials" class="flex items-center gap-2 bg-gradient-primary border-none shadow-[var(--shadow-primary)] hover:bg-[#1b6d82] text-white px-4 py-2 rounded-lg transition-colors text-sm font-bold shadow-md shadow-primary/20">
            <i class="bi bi-plus-lg"></i> Add Material
        </a>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat 1 -->
    <div class="glass-panel-dark border border-white/10 p-6 rounded-2xl relative overflow-hidden group hover:border-primary/30 transition-all shadow-sm hover:shadow-md">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Total Materials</p>
                <h3 class="text-3xl font-bold text-gradient mb-1 mt-1"><?php echo $materials_count; ?></h3>
            </div>
            <div class="p-3 bg-secondary/10 rounded-xl text-[#FF1493] group-hover:bg-secondary group-hover:text-white transition-colors">
                <i class="bi bi-box-seam text-xl"></i>
            </div>
        </div>
        <div class="flex items-center gap-2 text-xs">
            <span class="text-green-400 flex items-center gap-1 font-medium bg-green-500/20 px-1.5 py-0.5 rounded border border-green-500/20">
                <i class="bi bi-graph-up-arrow"></i> +12%
            </span>
            <span class="text-gray-400">vs last month</span>
        </div>
    </div>

    <!-- Stat 2 -->
    <div class="glass-panel-dark border border-white/10 p-6 rounded-2xl relative overflow-hidden group hover:border-blue-500/30 transition-all shadow-sm hover:shadow-md">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Total Quotes</p>
                <h3 class="text-3xl font-bold text-gradient mb-1 mt-1"><?php echo $quotes_count; ?></h3>
            </div>
            <div class="p-3 bg-blue-500/20 rounded-xl text-blue-500 group-hover:bg-blue-500 group-hover:text-white transition-colors">
                <i class="bi bi-file-earmark-check text-xl"></i>
            </div>
        </div>
        <div class="flex items-center gap-2 text-xs">
            <span class="text-green-400 flex items-center gap-1 font-medium bg-green-500/20 px-1.5 py-0.5 rounded border border-green-500/20">
                <i class="bi bi-graph-up-arrow"></i> +5%
            </span>
            <span class="text-gray-400">vs last month</span>
        </div>
    </div>

    <!-- Stat 3 -->
    <div class="glass-panel-dark border border-white/10 p-6 rounded-2xl relative overflow-hidden group hover:border-orange-500/30 transition-all shadow-sm hover:shadow-md">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Pending Requests</p>
                <h3 class="text-3xl font-bold text-gradient mb-1 mt-1"><?php echo $pending_quotes; ?></h3>
            </div>
            <div class="p-3 bg-orange-500/20 rounded-xl text-[#FF5E3A] group-hover:bg-orange-500 group-hover:text-white transition-colors">
                <i class="bi bi-clock-history text-xl"></i>
            </div>
        </div>
        <div class="flex items-center gap-2 text-xs">
            <?php if ($pending_quotes > 0): ?>
                <span class="text-[#FF5E3A] flex items-center gap-1 font-medium bg-orange-500/20 px-1.5 py-0.5 rounded border border-orange-100 animate-pulse">
                    Action Required
                </span>
            <?php else: ?>
                 <span class="text-gray-400">All caught up</span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Stat 4 -->
    <div class="glass-panel-dark border border-white/10 p-6 rounded-2xl relative overflow-hidden group hover:border-purple-500/30 transition-all shadow-sm hover:shadow-md">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Avg. Quote Value</p>
                <h3 class="text-3xl font-bold text-gradient mb-1 mt-1">₹12.5k</h3>
            </div>
            <div class="p-3 bg-purple-500/20 rounded-xl text-purple-500 group-hover:bg-purple-500 group-hover:text-white transition-colors">
                <i class="bi bi-currency-rupee text-xl"></i>
            </div>
        </div>
        <div class="flex items-center gap-2 text-xs">
            <span class="text-gray-400">Based on recent data</span>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Left Column: Table -->
    <div class="lg:col-span-2 space-y-8">
        <div class="glass-panel-dark border border-white/10 rounded-2xl overflow-hidden shadow-sm">
            <div class="p-6 border-b border-white/10 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gradient mb-1">Recent Quote Requests</h3>
                    <p class="text-gray-400 text-xs mt-1">Latest inquiries from potential clients</p>
                </div>
                <a href="/admin/quotes" class="text-xs font-bold text-primary hover:text-primary-dark uppercase tracking-wider flex items-center gap-1 transition-colors">
                    View All <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-white/5 text-xs uppercase text-gray-400 font-semibold tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Client</th>
                            <th class="px-6 py-4">Material</th>
                            <th class="px-6 py-4">Amount</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-structure">
                        <?php
                        $recent_quotes = $conn->query("SELECT q.*, m.name as material_name, m.image_url FROM quotes q JOIN materials m ON q.material_id = m.id ORDER BY q.created_at DESC LIMIT 5");
                        while ($row = $recent_quotes->fetch_assoc()):
                        ?>
                        <tr class="hover:bg-white/5/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-secondary/10 flex items-center justify-center text-xs font-bold text-[#FF1493]">
                                        <?php echo substr($row['user_name'], 0, 1); ?>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-white group-hover:text-primary transition-colors"><?php echo $row['user_name']; ?></p>
                                        <p class="text-xs text-gray-400"><?php echo date('M d', strtotime($row['created_at'])); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="<?php echo $row['image_url']; ?>" class="w-8 h-8 rounded object-cover border border-white/10 shadow-sm"> 
                                    <span class="text-sm text-gray-400"><?php echo $row['material_name']; ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-white">
                                ₹<?php echo number_format($row['total_price']); ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php 
                                $statusColors = [
                                    'pending' => 'bg-orange-500/20 text-[#FF5E3A] border-orange-500/30',
                                    'contacted' => 'bg-blue-500/20 text-gray-300 border-blue-500/30',
                                    'completed' => 'bg-green-500/20 text-green-400 border-green-500/30'
                                ];
                                $colorClass = $statusColors[$row['status']] ?? 'bg-white/5 text-gray-600 border-white/10';
                                ?>
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium border <?php echo $colorClass; ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                         <?php if ($recent_quotes->num_rows == 0): ?>
                            <tr><td colspan="4" class="p-8 text-center text-gray-400">No quotes received yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Quick Actions & System -->
    <div class="space-y-6">
        
        <!-- Quick Actions -->
        <div class="glass-panel-dark border border-white/10 rounded-2xl p-6 shadow-sm">
            <h3 class="text-lg font-bold text-gradient mb-1 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 gap-3">
                <a href="/admin/materials" class="p-4 bg-white/5 hover:bg-white/5 border border-white/10 rounded-xl flex flex-col items-center justify-center gap-2 transition-all hover:-translate-y-1 hover:border-primary/30 group shadow-sm">
                    <div class="p-2 bg-secondary/10 rounded-lg text-[#FF1493] group-hover:scale-110 transition-transform">
                        <i class="bi bi-plus-lg text-xl"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-400 group-hover:text-white">Add Material</span>
                </a>
                <a href="/visualizer" target="_blank" class="p-4 bg-white/5 hover:bg-white/5 border border-white/10 rounded-xl flex flex-col items-center justify-center gap-2 transition-all hover:-translate-y-1 hover:border-blue-500/30 group shadow-sm">
                    <div class="p-2 bg-blue-500/20 rounded-lg text-blue-500 group-hover:scale-110 transition-transform">
                        <i class="bi bi-eye text-xl"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-400 group-hover:text-white">Visualizer</span>
                </a>
                <button class="p-4 bg-white/5 hover:bg-white/5 border border-white/10 rounded-xl flex flex-col items-center justify-center gap-2 transition-all hover:-translate-y-1 hover:border-purple-500/30 group shadow-sm">
                    <div class="p-2 bg-purple-500/20 rounded-lg text-purple-500 group-hover:scale-110 transition-transform">
                        <i class="bi bi-gear text-xl"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-400 group-hover:text-white">Settings</span>
                </button>
                 <button class="p-4 bg-white/5 hover:bg-white/5 border border-white/10 rounded-xl flex flex-col items-center justify-center gap-2 transition-all hover:-translate-y-1 hover:border-green-500/30 group shadow-sm">
                    <div class="p-2 bg-green-500/20 rounded-lg text-green-500 group-hover:scale-110 transition-transform">
                        <i class="bi bi-bar-chart-line text-xl"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-400 group-hover:text-white">Reports</span>
                </button>
            </div>
        </div>

        <!-- System Status -->
         <div class="glass-panel-dark border border-white/10 rounded-2xl p-6 shadow-sm">
            <h3 class="text-lg font-bold text-gradient mb-1 mb-4">System Status</h3>
             <div class="space-y-4">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-400">Database</span>
                    <span class="flex items-center gap-2 text-green-400"><span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Online</span>
                </div>
                 <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-400">Server Usage</span>
                    <span class="text-white font-medium">12%</span>
                </div>
                <div class="w-full bg-white/5 rounded-full h-1.5 border border-white/10">
                    <div class="bg-gradient-primary border-none shadow-[var(--shadow-primary)] h-1.5 rounded-full" style="width: 12%"></div>
                </div>
                <div class="pt-2 text-xs text-gray-400">
                    Last backup: 2 hours ago
                </div>
             </div>
         </div>
    </div>
</div>

<script>
    // lucide.createIcons();
</script>
</body>
</html>