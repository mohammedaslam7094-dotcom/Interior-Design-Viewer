<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/../includes/db.php';

// Create uploads directory if not exists
if (!file_exists(__DIR__ . '/../uploads')) {
    mkdir(__DIR__ . '/../uploads', 0777, true);
}

// Detect if POST was too large (post_max_size exceeded)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) && $_SERVER['CONTENT_LENGTH'] > 0) {
    $upload_error = "The file is too large for the current server configuration. Please ensure you have restarted the server after recent changes.";
}

// Handle Add/Update/Delete
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {

    // SAVE MATERIAL (Add or Update)
    if (isset($_POST['save_material'])) {
        $id = $_POST['material_id'] ?? '';
        $name = $_POST['name'];
        $price = $_POST['price'];
        $desc = $_POST['description'];
        $crispy_words = $_POST['crispy_words'] ?? '';
        $category_ids = $_POST['category_ids'] ?? [];
        $primary_category_id = $category_ids[0] ?? null;

        $image_url = null;
        $texture_url = null;

        // Handle File Upload
        if (!empty($_FILES['texture_image']['name'])) {
            if ($_FILES['texture_image']['error'] === UPLOAD_ERR_OK) {
                $filename = time() . '_' . basename($_FILES["texture_image"]["name"]);
                $target_file = __DIR__ . '/../uploads/' . $filename;

                if (move_uploaded_file($_FILES["texture_image"]["tmp_name"], $target_file)) {
                    if (extension_loaded('gd')) {
                        $info = getimagesize($target_file);
                        if ($info) {
                            $mime = $info['mime'];
                            $width = $info[0];
                            $height = $info[1];

                            $maxWidth = 1920;
                            if ($width > $maxWidth || filesize($target_file) > 20 * 1024 * 1024) {
                                $newWidth = ($width > $maxWidth) ? $maxWidth : $width;
                                $newHeight = floor($height * ($newWidth / $width));

                                $image = null;
                                if ($mime == 'image/jpeg' && function_exists('imagecreatefromjpeg'))
                                    $image = imagecreatefromjpeg($target_file);
                                elseif ($mime == 'image/png' && function_exists('imagecreatefrompng'))
                                    $image = imagecreatefrompng($target_file);
                                elseif ($mime == 'image/webp' && function_exists('imagecreatefromwebp'))
                                    $image = imagecreatefromwebp($target_file);

                                if ($image) {
                                    $virtual_image = imagecreatetruecolor($newWidth, $newHeight);
                                    if ($mime == 'image/png' || $mime == 'image/webp') {
                                        imagealphablending($virtual_image, false);
                                        imagesavealpha($virtual_image, true);
                                    }
                                    imagecopyresampled($virtual_image, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                                    if ($mime == 'image/jpeg')
                                        imagejpeg($virtual_image, $target_file, 80);
                                    elseif ($mime == 'image/png')
                                        imagepng($virtual_image, $target_file, 8);
                                    elseif ($mime == 'image/webp')
                                        imagewebp($virtual_image, $target_file, 80);

                                    // Create Thumbnail
                                    $thumbWidth = 150;
                                    $thumbHeight = floor($height * ($thumbWidth / $width));
                                    $thumb_image = imagecreatetruecolor($thumbWidth, $thumbHeight);
                                    if ($mime == 'image/png' || $mime == 'image/webp') {
                                        imagealphablending($thumb_image, false);
                                        imagesavealpha($thumb_image, true);
                                    }
                                    imagecopyresampled($thumb_image, $image, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);
                                    $thumb_file = str_replace(basename($target_file), 'thumb_' . basename($target_file), $target_file);
                                    if ($mime == 'image/jpeg')
                                        imagejpeg($thumb_image, $thumb_file, 70);
                                    elseif ($mime == 'image/png')
                                        imagepng($thumb_image, $thumb_file, 9);
                                    elseif ($mime == 'image/webp')
                                        imagewebp($thumb_image, $thumb_file, 70);

                                    // imagedestroy removed (deprecated in PHP 8.0+)
                                    unset($image);
                                    unset($virtual_image);
                                    unset($thumb_image);
                                }
                            }
                        }
                    } else {
                        $upload_warning = "Note: Image optimization is pending server restart. Once you restart the server, images will be automatically reduced for better quality and speed.";
                    }
                    // End Compression Logic

                    $image_url = '/uploads/' . $filename;
                    $texture_url = $image_url;
                } else {
                    $upload_error = "Server Error: Could not save the uploaded file. Please check folder permissions.";
                }
            } else {
                $error_code = $_FILES['texture_image']['error'];
                $errors = [
                    UPLOAD_ERR_INI_SIZE => "File exceeds upload_max_filesize in php.ini",
                    UPLOAD_ERR_FORM_SIZE => "File exceeds MAX_FILE_SIZE in HTML form",
                    UPLOAD_ERR_PARTIAL => "File was only partially uploaded",
                    UPLOAD_ERR_NO_FILE => "No file was uploaded",
                    UPLOAD_ERR_NO_TMP_DIR => "Missing a temporary folder",
                    UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk",
                    UPLOAD_ERR_EXTENSION => "A PHP extension stopped the file upload"
                ];
                $upload_error = $errors[$error_code] ?? "Unknown upload error: $error_code";
            }
        }

        if ($id) {
            // UPDATE EXISTING
            if ($image_url) {
                // Update with new image
                $stmt = $conn->prepare("UPDATE materials SET name=?, price_per_sqft=?, description=?, crispy_words=?, image_url=?, texture_url=? WHERE id=?");
                $stmt->bind_param("sdssssi", $name, $price, $desc, $crispy_words, $image_url, $texture_url, $id);
            } else {
                // Update without changing image
                $stmt = $conn->prepare("UPDATE materials SET name=?, price_per_sqft=?, description=?, crispy_words=? WHERE id=?");
                $stmt->bind_param("sdssi", $name, $price, $desc, $crispy_words, $id);
            }
            if ($stmt->execute()) {
                // Update categories
                $conn->query("DELETE FROM material_categories WHERE material_id=$id");
                foreach ($category_ids as $cid) {
                    $stmt_cat = $conn->prepare("INSERT INTO material_categories (material_id, category_id) VALUES (?, ?)");
                    $stmt_cat->bind_param("ii", $id, $cid);
                    $stmt_cat->execute();
                }
                header("Location: materials.php?success=1");
                exit();
            }
        } else {
            // INSERT NEW
            if ($image_url) {
                $stmt = $conn->prepare("INSERT INTO materials (category_id, name, description, price_per_sqft, image_url, texture_url, crispy_words) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("issdsss", $primary_category_id, $name, $desc, $price, $image_url, $texture_url, $crispy_words);

                if ($stmt->execute()) {
                    $material_id = $conn->insert_id;

                    // Trigger Notification
                    if (!empty($crispy_words)) {
                        $notification_msg = "New Material: " . $name . " - " . $crispy_words;
                    } else {
                        $notification_msg = "New Material Added: " . $name;
                    }

                    // Get all users
                    $users_result = $conn->query("SELECT id FROM users");
                    if ($users_result) {
                        $notif_stmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
                        while ($user = $users_result->fetch_assoc()) {
                            $notif_stmt->bind_param("is", $user['id'], $notification_msg);
                            $notif_stmt->execute();
                        }
                    }

                    // Save categories
                    foreach ($category_ids as $cid) {
                        $stmt_cat = $conn->prepare("INSERT INTO material_categories (material_id, category_id) VALUES (?, ?)");
                        $stmt_cat->bind_param("ii", $material_id, $cid);
                        $stmt_cat->execute();
                    }
                }
                header("Location: materials.php?success=1");
                exit();
            }
        }
    }

    // DELETE MATERIAL
    if (isset($_POST['delete_id'])) {
        $conn->query("DELETE FROM materials WHERE id=" . $_POST['delete_id']);
    }

    // ADD CATEGORY
    if (isset($_POST['add_category'])) {
        $cat_name = $_POST['cat_name'];
        $cat_slug = strtolower(str_replace(' ', '-', $cat_name));
        $cat_type = $_POST['cat_type'];

        $check = $conn->query("SELECT id FROM categories WHERE slug = '$cat_slug'");
        if ($check->num_rows == 0) {
            $stmt = $conn->prepare("INSERT INTO categories (name, slug, type) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $cat_name, $cat_slug, $cat_type);
            $stmt->execute();
        }
    }
}

require_once __DIR__ . '/includes/header_admin.php';

// Pagination Logic
$limit = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$total_res = $conn->query("SELECT COUNT(*) as total FROM materials");
$total_mats = $total_res->fetch_assoc()['total'];
$total_pages = ceil($total_mats / $limit);
?>

<style>
    .material-row {
        content-visibility: auto;
        contain-intrinsic-size: 0 100px;
    }
</style>

<?php if (isset($upload_error)): ?>
    <div
        class="mb-6 p-4 bg-red-500/20 border border-red-500/30 rounded-xl flex items-center gap-3 text-red-400 animate-in slide-in-from-top-4 duration-300 shadow-sm">
        <i class="bi bi-exclamation-circle flex-shrink-0 text-xl"></i>
        <div>
            <p class="font-bold text-sm">Upload Error</p>
            <p class="text-xs opacity-80"><?php echo $upload_error; ?></p>
        </div>
    </div>
<?php endif; ?>

<?php if (isset($upload_warning)): ?>
    <div
        class="mb-6 p-4 bg-yellow-500/20 border border-yellow-500/30 rounded-xl flex items-center gap-3 text-yellow-400 animate-in slide-in-from-top-4 duration-300 shadow-sm">
        <i class="bi bi-info-circle flex-shrink-0 text-xl"></i>
        <p class="text-xs font-bold"><?php echo $upload_warning; ?></p>
    </div>
<?php endif; ?>

<?php if (isset($_GET['success'])): ?>
    <div class="mb-6 p-4 bg-green-500/20 border border-green-500/30 rounded-xl flex items-center gap-3 text-green-400 animate-in slide-in-from-top-4 duration-300 shadow-sm"
        id="successAlert">
        <i class="bi bi-check-circle flex-shrink-0 text-xl"></i>
        <p class="text-xs font-bold">Operation completed successfully!</p>
    </div>
    <script>setTimeout(() => document.getElementById('successAlert').style.display = 'none', 3000);</script>
<?php endif; ?>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gradient mb-1 tracking-tight">Materials Catalog</h1>
        <p class="text-gray-400 mt-1">Manage your product inventory (Page <?php echo $page; ?> of
            <?php echo $total_pages; ?>)
        </p>
    </div>
    <div class="flex gap-3">
        <button onclick="document.getElementById('categoryModal').classList.remove('hidden')"
            class="flex items-center gap-2 glass-panel-dark border border-white/10 text-white px-4 py-2 rounded-lg font-medium hover:bg-white/5 hover:text-primary transition-all hover:border-[#FF1493] shadow-sm">
            <i class="bi bi-tags"></i> Manage Categories
        </button>
        <button onclick="openAddModal()"
            class="flex items-center gap-2 bg-gradient-primary border-none shadow-[var(--shadow-primary)] text-white px-4 py-2 rounded-lg font-bold hover:bg-[#1b6d82] transition-all shadow-md shadow-primary/20 hover:-translate-y-0.5">
            <i class="bi bi-plus-lg"></i> Add Material
        </button>
    </div>
</div>

<?php
$stat_total_cats = $conn->query("SELECT COUNT(*) as cnt FROM categories")->fetch_assoc()['cnt'] ?? 0;
$stat_avg_price = $conn->query("SELECT AVG(price_per_sqft) as avg FROM materials")->fetch_assoc()['avg'] ?? 0;
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
                <p class="text-gray-400 text-sm font-semibold uppercase tracking-wider">Total Materials</p>
                <h3 class="text-3xl font-bold text-white mt-1"><?php echo $total_mats; ?></h3>
            </div>
            <div
                class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center text-[#FF1493] text-xl border border-white/10">
                <i class="bi bi-box-seam"></i>
            </div>
        </div>
        <p class="text-xs text-gray-500"><span class="text-green-400"><i class="bi bi-arrow-up-short"></i> Active</span>
            inventory</p>
    </div>

    <div
        class="glass-panel-dark p-6 rounded-2xl border border-white/5 relative overflow-hidden group hover:border-[#FF5E3A]/30 transition-colors">
        <div
            class="absolute top-0 right-0 w-32 h-32 bg-[#FF5E3A]/10 rounded-full blur-2xl -mt-10 -mr-10 translate-x-4 group-hover:-translate-x-4 transition-transform duration-700">
        </div>
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-gray-400 text-sm font-semibold uppercase tracking-wider">Categories</p>
                <h3 class="text-3xl font-bold text-white mt-1"><?php echo $stat_total_cats; ?></h3>
            </div>
            <div
                class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center text-[#FF5E3A] text-xl border border-white/10">
                <i class="bi bi-tags"></i>
            </div>
        </div>
        <p class="text-xs text-gray-500"><span class="text-orange-400"><i class="bi bi-collection"></i> Product</span>
            collections</p>
    </div>

    <div
        class="glass-panel-dark p-6 rounded-2xl border border-white/5 relative overflow-hidden group hover:border-purple-500/30 transition-colors">
        <div
            class="absolute top-0 right-0 w-32 h-32 bg-purple-500/10 rounded-full blur-2xl -mt-10 -mr-10 translate-x-4 group-hover:-translate-x-4 transition-transform duration-700">
        </div>
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-gray-400 text-sm font-semibold uppercase tracking-wider">Avg. SQ.FT Price</p>
                <h3 class="text-3xl font-bold text-white mt-1 font-mono">
                    ₹<?php echo number_format($stat_avg_price, 2); ?></h3>
            </div>
            <div
                class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center text-purple-400 text-xl border border-white/10">
                <i class="bi bi-currency-rupee"></i>
            </div>
        </div>
        <p class="text-xs text-gray-500"><span class="text-green-400"><i class="bi bi-graph-up"></i> Market</span>
            average</p>
    </div>
</div>

<!-- Materials Table -->
<div class="glass-panel-dark border border-white/10 rounded-2xl overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-white/5 border-b border-white/10 text-gray-400 uppercase text-xs font-bold tracking-wider">
                <tr>
                    <th class="px-6 py-5">Image</th>
                    <th class="px-6 py-5">Product Info</th>
                    <th class="px-6 py-5">Categories</th>
                    <th class="px-6 py-5">Price / Sq.ft</th>
                    <th class="px-6 py-5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-structure">
                <?php
                $mats = $conn->query("SELECT m.*, 
                                      GROUP_CONCAT(c.name SEPARATOR ', ') as cat_names,
                                      GROUP_CONCAT(mc.category_id) as cat_ids 
                                      FROM materials m 
                                      LEFT JOIN material_categories mc ON m.id = mc.material_id 
                                      LEFT JOIN categories c ON mc.category_id = c.id 
                                      GROUP BY m.id 
                                      ORDER BY m.id DESC
                                      LIMIT $offset, $limit");
                while ($row = $mats->fetch_assoc()):
                    $rowJson = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                    ?>
                    <tr class="hover:bg-white/5/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div
                                class="w-16 h-16 rounded-lg overflow-hidden border border-white/10 group-hover:border-primary/50 transition-colors shadow-sm relative bg-white/5">
                                <?php
                                $thumb_url = str_replace(basename($row['image_url']), 'thumb_' . basename($row['image_url']), $row['image_url']);
                                $thumb_path = __DIR__ . '/..' . $thumb_url;
                                $display_url = (file_exists($thumb_path)) ? $thumb_url : $row['image_url'];
                                ?>
                                <img src="<?php echo $display_url; ?>" class="w-full h-full object-cover" loading="lazy"
                                    decoding="async" alt="<?php echo htmlspecialchars($row['name']); ?>">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors"></div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-white text-base group-hover:text-primary transition-colors">
                                <?php echo htmlspecialchars($row['name']); ?>
                            </p>
                            <p class="text-xs text-gray-400 mt-1 truncate max-w-xs">
                                <?php echo htmlspecialchars(substr($row['description'], 0, 50)); ?>...
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1.5">
                                <?php
                                $cats = explode(', ', $row['cat_names'] ?? '');
                                foreach ($cats as $cat) {
                                    if (empty($cat))
                                        continue;
                                    echo "<span class='bg-secondary/10 text-[#FF1493] text-[10px] uppercase font-bold px-2 py-1 rounded border border-[#FF1493]/20 tracking-wider'>" . htmlspecialchars($cat) . "</span>";
                                }
                                ?>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="text-primary font-bold text-lg font-mono tracking-tight">₹<?php echo number_format($row['price_per_sqft'], 2); ?></span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" onclick="openEditModal(this)" data-material='<?php echo $rowJson; ?>'
                                    class="p-2 text-gray-400 hover:text-white hover:bg-white/10 rounded-lg transition-colors">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this material? This action cannot be undone.');"
                                    class="inline-block">
                                    <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit"
                                        class="p-2 text-red-500/70 hover:text-red-500 hover:bg-red-500/20 rounded-lg transition-colors border border-transparent hover:border-red-100">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <?php if ($mats->num_rows == 0): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <i class="bi bi-box-seam text-4xl mb-3 opacity-50"></i>
                                <p class="text-lg font-medium">No materials found</p>
                                <p class="text-sm">Get started by creating your first material.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Navigation -->
    <?php if ($total_pages > 1): ?>
        <div class="px-6 py-4 glass-panel-dark border-t border-white/10 flex items-center justify-between">
            <p class="text-xs text-gray-400 font-medium tracking-wide Uppercase">
                Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $limit, $total_mats); ?> of
                <?php echo $total_mats; ?> materials
            </p>
            <div class="flex gap-2">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>"
                        class="px-3 py-1.5 rounded-lg glass-panel-dark border border-white/10 text-gray-400 hover:text-white hover:bg-white/5 hover:border-white/10-dark transition-all text-xs font-bold flex items-center gap-1">
                        <i class="bi bi-chevron-left"></i> Previous
                    </a>
                <?php endif; ?>

                <div class="flex gap-1">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>"
                            class="w-8 h-8 flex items-center justify-center rounded-lg border <?php echo $i == $page ? 'bg-gradient-primary border-none shadow-[var(--shadow-primary)] border-primary text-white font-bold' : 'glass-panel-dark border-white/10 text-gray-400 hover:text-white hover:bg-white/5 transition-all'; ?> text-xs">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                </div>

                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>"
                        class="px-3 py-1.5 rounded-lg glass-panel-dark border border-white/10 text-gray-400 hover:text-white hover:bg-white/5 hover:border-white/10-dark transition-all text-xs font-bold flex items-center gap-1">
                        Next <i class="bi bi-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Add/Edit Category Modal -->
<div id="categoryModal"
    class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 animate-in fade-in duration-200">
    <div onclick="event.stopPropagation()"
        class="glass-panel-dark rounded-2xl max-w-md w-full p-0 border border-white/10 shadow-2xl transform scale-100 transition-transform">
        <div class="p-6 border-b border-white/10 flex justify-between items-center bg-white/5 rounded-t-2xl">
            <h3 class="text-lg font-bold text-gradient mb-1">Manage Categories</h3>
            <button onclick="document.getElementById('categoryModal').classList.add('hidden')"
                class="text-gray-400 hover:text-white transition-colors">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>
        <div class="p-6 space-y-6">
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-gray-400 mb-1.5 text-xs font-bold uppercase tracking-wider">New Category
                        Name</label>
                    <input type="text" name="cat_name" required placeholder="e.g. Marble Tiles"
                        class="w-full bg-white/5 border border-white/10 text-white rounded-xl px-4 py-3 focus:border-[#FF1493] focus:ring-1 focus:ring-[#FF1493] outline-none transition-all placeholder-muted">
                </div>
                <div>
                    <label class="block text-gray-400 mb-1.5 text-xs font-bold uppercase tracking-wider">Type</label>
                    <div class="relative">
                        <select name="cat_type"
                            class="w-full bg-white/5 border border-white/10 text-white rounded-xl px-4 py-3 appearance-none focus:border-[#FF1493] focus:ring-1 focus:ring-[#FF1493] outline-none transition-all cursor-pointer">
                            <option value="wall">Wall</option>
                            <option value="floor">Floor</option>
                            <option value="ceiling">Ceiling</option>
                        </select>
                        <i class="bi bi-chevron-down absolute right-4 top-3.5 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>
                <button type="submit" name="add_category"
                    class="w-full bg-gradient-primary border-none shadow-[var(--shadow-primary)] text-white font-bold py-3 rounded-xl hover:bg-[#1b6d82] transition-all transform active:scale-95 shadow-md shadow-primary/20">
                    Create Category
                </button>
            </form>
            <div class="pt-4 border-t border-white/10">
                <h4 class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-3">Existing Categories</h4>
                <div class="flex flex-wrap gap-2 max-h-32 overflow-y-auto custom-scrollbar pr-2">
                    <?php
                    $all_cats = $conn->query("SELECT * FROM categories ORDER BY type");
                    while ($ac = $all_cats->fetch_assoc()) {
                        $typeColor = $ac['type'] == 'wall' ? 'text-gray-300 border-blue-500/30 bg-blue-500/20' : ($ac['type'] == 'floor' ? 'text-green-400 border-green-500/30 bg-green-500/20' : 'text-purple-600 border-purple-500/30 bg-purple-500/20');
                        echo "<div class='px-3 py-1.5 rounded-lg glass-panel-dark border border-white/10 text-xs text-white flex items-center gap-2 group hover:border-primary/50 transition-colors cursor-default'>
                                {$ac['name']} 
                                <span class='text-[10px] uppercase font-bold $typeColor border px-1.5 py-0.5 rounded'>{$ac['type']}</span>
                              </div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Material Modal -->
<div id="addModal"
    class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 animate-in fade-in duration-200">
    <div
        class="glass-panel-dark rounded-2xl max-w-2xl w-full border border-white/10 shadow-2xl flex flex-col max-h-[90vh]">
        <div
            class="p-6 border-b border-white/10 flex justify-between items-center bg-white/5 rounded-t-2xl flex-shrink-0">
            <div>
                <h3 id="modalTitle" class="text-xl font-bold text-gradient mb-1">Add New Material</h3>
                <p class="text-gray-400 text-xs mt-0.5">Fill in the details below.</p>
            </div>
            <button onclick="document.getElementById('addModal').classList.add('hidden')"
                class="text-gray-400 hover:text-white bg-white/10 hover:bg-gray-200 p-2 rounded-full transition-all">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        <form method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto p-6 custom-scrollbar">
            <input type="hidden" name="save_material" value="1">
            <input type="hidden" name="material_id" id="materialIdInput">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Name -->
                <div>
                    <label class="block text-gray-400 mb-2 text-xs font-bold uppercase tracking-wider">Material
                        Name</label>
                    <input type="text" name="name" id="nameInput" required placeholder="Ex: Italian Marble"
                        class="w-full bg-white/5 border border-white/10 text-white rounded-xl px-4 py-3 focus:border-[#FF1493] focus:ring-1 focus:ring-[#FF1493] outline-none transition-all placeholder-muted">
                </div>
                <!-- Price -->
                <div>
                    <label class="block text-gray-400 mb-2 text-xs font-bold uppercase tracking-wider">Price / Sq.ft
                        (₹)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3.5 text-gray-400 font-bold">₹</span>
                        <input type="number" step="0.01" name="price" id="priceInput" required placeholder="0.00"
                            class="w-full bg-white/5 border border-white/10 text-white rounded-xl pl-8 pr-4 py-3 focus:border-[#FF1493] focus:ring-1 focus:ring-[#FF1493] outline-none transition-all placeholder-muted font-mono">
                    </div>
                </div>
            </div>

            <!-- Image Upload -->
            <div class="mb-6">
                <label class="block text-gray-400 mb-2 text-xs font-bold uppercase tracking-wider">Texture Image</label>
                <div class="relative group">
                    <input type="file" name="texture_image" accept="image/*" id="fileInput" class="hidden"
                        onchange="updateFileName(this)">
                    <label for="fileInput"
                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-white/10 rounded-xl cursor-pointer bg-white/5 hover:bg-white/5 hover:border-primary transition-all group">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <i
                                class="bi bi-cloud-arrow-up text-3xl mb-3 text-gray-400 group-hover:text-primary transition-colors"></i>
                            <p id="fileLabel" class="mb-1 text-sm text-gray-400"><span
                                    class="font-bold text-white">Click to
                                    upload</span> or drag and drop</p>
                            <p id="fileSizeLimitText" class="text-xs text-gray-400/70">SVG, PNG, JPG or GIF (MAX. 50MB)
                            </p>
                        </div>
                    </label>
                </div>
                <p id="fileError" class="text-xs text-red-500 mt-2 hidden">File size exceeds 50MB limit.</p>
                <p id="currentFileText" class="text-xs text-primary mt-2 hidden">Current file: <span
                        id="fileNameDisplay"></span></p>
            </div>

            <!-- Categories Multi-Select -->
            <div class="mb-6 relative" id="multiSelectContainer">
                <label class="block text-gray-400 mb-2 text-xs font-bold uppercase tracking-wider">Categories</label>
                <!-- Helper for PHP submission -->
                <select name="category_ids[]" id="realSelect" multiple class="hidden">
                    <?php
                    $cats = $conn->query("SELECT * FROM categories ORDER BY type, name");
                    while ($c = $cats->fetch_assoc())
                        echo "<option value='{$c['id']}' data-name='{$c['name']} ({$c['type']})'>{$c['name']} ({$c['type']})</option>";
                    ?>
                </select>
                <!-- Custom Trigger -->
                <button type="button" id="dropdownTrigger"
                    class="w-full bg-white/5 border border-white/10 text-white rounded-xl px-4 py-3 text-left flex justify-between items-center hover:border-primary focus:border-[#FF1493] outline-none transition-all group">
                    <span id="triggerText" class="text-gray-400">Select applicable categories...</span>
                    <i class="bi bi-chevron-down text-gray-400 group-hover:text-white transition-colors"
                        id="dropdownArrow"></i>
                </button>
                <!-- Custom Options List -->
                <div id="dropdownOptions"
                    class="hidden absolute left-0 right-0 top-full mt-2 glass-panel-dark border border-white/10 rounded-xl shadow-xl z-50 max-h-60 overflow-y-auto p-2 space-y-1 custom-scrollbar ring-1 ring-black/5">
                    <!-- Checkboxes populated by JS -->
                </div>
                <!-- Selected Tags Area -->
                <div id="selectedTags" class="flex flex-wrap gap-2 mt-3"></div>
            </div>

            <!-- Crispy Words (Notification Message) -->
            <div class="mb-6">
                <label class="block text-gray-400 mb-2 text-xs font-bold uppercase tracking-wider">Crispy Words
                    (Notification Message)</label>
                <input type="text" name="crispy_words" id="crispyWordsInput"
                    placeholder="Ex: Elegant and durable, perfect for..."
                    class="w-full bg-white/5 border border-white/10 text-white rounded-xl px-4 py-3 focus:border-[#FF1493] focus:ring-1 focus:ring-[#FF1493] outline-none transition-all placeholder-muted">
                <p class="text-xs text-gray-400 mt-1">This text will be sent in the notification to all users.</p>
            </div>

            <!-- Description -->
            <div class="mb-8">
                <label class="block text-gray-400 mb-2 text-xs font-bold uppercase tracking-wider">Description</label>
                <textarea name="description" id="descInput" rows="3"
                    class="w-full bg-white/5 border border-white/10 text-white rounded-xl px-4 py-3 focus:border-[#FF1493] focus:ring-1 focus:ring-[#FF1493] outline-none transition-all placeholder-muted"></textarea>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 pt-4 border-t border-white/10">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')"
                    class="px-6 py-2.5 text-gray-400 hover:text-white font-medium bg-white/10 hover:bg-gray-200 rounded-lg transition-colors">Cancel</button>
                <button type="submit" id="submitBtn"
                    class="bg-gradient-primary border-none shadow-[var(--shadow-primary)] text-white px-8 py-2.5 rounded-lg font-bold hover:bg-[#1b6d82] transition-all transform active:scale-95 shadow-lg shadow-primary/20">
                    Save Product
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // lucide.createIcons();

    function updateFileName(input) {
        const fileError = document.getElementById('fileError');
        const fileLabel = document.getElementById('fileLabel');
        const maxSize = 50 * 1024 * 1024; // 50MB in bytes

        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (file.size > maxSize) {
                fileError.classList.remove('hidden');
                fileLabel.innerHTML = '<span class="font-bold text-red-400">File too large</span> (' + (file.size / (1024 * 1024)).toFixed(2) + 'MB)';
                input.value = ''; // Clear the input
            } else {
                fileError.classList.add('hidden');
                fileLabel.innerHTML = '<span class="font-bold text-white">' + file.name + '</span> selected';
            }
        }
    }

    // Modal Control Functions
    function openAddModal() {
        const modal = document.getElementById('addModal');
        document.getElementById('modalTitle').textContent = 'Add New Material';
        document.getElementById('submitBtn').textContent = 'Save Product';
        document.getElementById('materialIdInput').value = '';

        // Reset Inputs
        document.getElementById('nameInput').value = '';
        document.getElementById('priceInput').value = '';
        document.getElementById('descInput').value = '';
        document.getElementById('nameInput').value = '';
        document.getElementById('priceInput').value = '';
        document.getElementById('descInput').value = '';
        document.getElementById('crispyWordsInput').value = '';
        document.getElementById('fileInput').value = '';
        document.getElementById('fileInput').required = true;
        document.getElementById('fileLabel').innerHTML = '<span class="font-bold text-gray-300">Click to upload</span> or drag and drop';
        document.getElementById('currentFileText').classList.add('hidden');

        // Reset Multi-Select
        if (window.multiSelect && window.multiSelect.reset) {
            window.multiSelect.reset();
        }

        modal.classList.remove('hidden');
    }

    function openEditModal(btn) {
        const data = JSON.parse(btn.dataset.material);
        const modal = document.getElementById('addModal');

        document.getElementById('modalTitle').textContent = 'Edit Material';
        document.getElementById('submitBtn').textContent = 'Update Product';
        document.getElementById('materialIdInput').value = data.id;

        // Fill Inputs
        document.getElementById('nameInput').value = data.name;
        document.getElementById('priceInput').value = data.price_per_sqft;
        document.getElementById('priceInput').value = data.price_per_sqft;
        document.getElementById('descInput').value = data.description;
        document.getElementById('crispyWordsInput').value = data.crispy_words || '';

        // Handle File Input (Not required for update)
        document.getElementById('fileInput').required = false;
        document.getElementById('fileLabel').innerHTML = '<span class="font-bold text-gray-300">Click to change</span> or drag and drop';

        // Show current file name (extracted from URL)
        if (data.image_url) {
            const fileName = data.image_url.split('/').pop();
            document.getElementById('fileNameDisplay').textContent = fileName;
            document.getElementById('currentFileText').classList.remove('hidden');
        }

        // Set Multi-Select
        if (window.multiSelect && window.multiSelect.set) {
            const ids = data.cat_ids ? data.cat_ids.split(',') : [];
            window.multiSelect.set(ids);
        }

        modal.classList.remove('hidden');
    }

    // Custom Multi-Select Logic (Refactored global access)
    document.addEventListener('DOMContentLoaded', () => {
        const realSelect = document.getElementById('realSelect');
        const trigger = document.getElementById('dropdownTrigger');
        const optionsList = document.getElementById('dropdownOptions');
        const tagsContainer = document.getElementById('selectedTags');
        const arrow = document.getElementById('dropdownArrow');
        const triggerText = document.getElementById('triggerText');

        // Populate Custom Options
        Array.from(realSelect.options).forEach(opt => {
            const div = document.createElement('div');
            div.className = 'flex items-center gap-3 p-2.5 rounded-lg hover:bg-[#222] cursor-pointer transition-colors group';
            div.innerHTML = `
                <div class="w-5 h-5 border border-[#444] rounded flex items-center justify-center transition-all ${opt.selected ? 'bg-[#D4AF37] border-[#D4AF37]' : 'group-hover:border-gray-400'}" data-checkbox>
                     <!-- WRAPPER for icon to persist through Lucide transforms -->
                    <span data-icon-wrapper class="${opt.selected ? '' : 'hidden'}">
                        <i class="bi bi-check-lg text-black"></i>
                    </span>
                </div>
                <span class="text-gray-300 text-sm select-none font-medium">${opt.getAttribute('data-name')}</span>
            `;

            div.onclick = (e) => {
                e.stopPropagation();
                opt.selected = !opt.selected;
                updateUI();
                // lucide.createIcons();
            };
            optionsList.appendChild(div);
        });

        trigger.onclick = () => {
            const isHidden = optionsList.classList.contains('hidden');
            if (isHidden) {
                optionsList.classList.remove('hidden');
                arrow.classList.add('rotate-180');
            } else {
                optionsList.classList.add('hidden');
                arrow.classList.remove('rotate-180');
            }
        };

        document.addEventListener('click', (e) => {
            if (!trigger.contains(e.target) && !optionsList.contains(e.target)) {
                optionsList.classList.add('hidden');
                arrow.classList.remove('rotate-180');
            }
        });

        function updateUI() {
            tagsContainer.innerHTML = '';
            let count = 0;

            Array.from(realSelect.options).forEach((opt, idx) => {
                const child = optionsList.children[idx];
                if (!child) return;

                const checkboxDiv = child.querySelector('[data-checkbox]');
                const iconWrapper = checkboxDiv.querySelector('[data-icon-wrapper]');
                const textSpan = child.lastElementChild;

                if (opt.selected) {
                    count++;
                    checkboxDiv.classList.remove('border-[#444]', 'group-hover:border-gray-400');
                    checkboxDiv.classList.add('bg-[#D4AF37]', 'border-[#D4AF37]');
                    iconWrapper.classList.remove('hidden');
                    textSpan.classList.add('text-white');

                    const tag = document.createElement('div');
                    tag.className = 'flex items-center gap-1.5 bg-[#222] text-gray-200 text-xs font-medium px-2.5 py-1.5 rounded-md border border-[#333] animate-in fade-in zoom-in duration-200';
                    tag.innerHTML = `
                        ${opt.getAttribute('data-name').split('(')[0]} 
                        <button type="button" class="hover:text-red-400 ml-1 text-gray-500 transition-colors"><i class="bi bi-x-lg"></i></button>
                    `;
                    tag.querySelector('button').onclick = () => {
                        opt.selected = false;
                        updateUI();
                        // lucide.createIcons();
                    };
                    tagsContainer.appendChild(tag);
                } else {
                    checkboxDiv.classList.add('border-[#444]');
                    checkboxDiv.classList.remove('bg-[#D4AF37]', 'border-[#D4AF37]');
                    iconWrapper.classList.add('hidden');
                    textSpan.classList.remove('text-white');
                }
            });

            if (count > 0) {
                triggerText.textContent = `${count} selected`;
                triggerText.classList.remove('text-gray-500');
                triggerText.classList.add('text-white');
                trigger.classList.add('border-[#D4AF37]/50', 'bg-[#D4AF37]/5');
            } else {
                triggerText.textContent = 'Select applicable categories...';
                triggerText.classList.add('text-gray-500');
                triggerText.classList.remove('text-white');
                trigger.classList.remove('border-[#D4AF37]/50', 'bg-[#D4AF37]/5');
            }
        }

        // Expose to Global Scope for Edit Function
        window.multiSelect = {
            update: updateUI,
            reset: () => {
                Array.from(realSelect.options).forEach(opt => opt.selected = false);
                updateUI();
                // lucide.createIcons();
            },
            set: (ids) => {
                // Ensure IDs are strings for comparison
                ids = ids.map(String);
                Array.from(realSelect.options).forEach(opt => {
                    opt.selected = ids.includes(opt.value);
                });
                updateUI();
                // lucide.createIcons();
            }
        };

        // Init
        updateUI();
        // lucide.createIcons();

        // Validation
        const form = document.querySelector('#addModal form');
        form.addEventListener('submit', (e) => {
            if (realSelect.selectedOptions.length === 0) {
                e.preventDefault();
                alert('Please select at least one category.');
                trigger.classList.add('border-red-500', 'animate-pulse');
                setTimeout(() => trigger.classList.remove('border-red-500', 'animate-pulse'), 2000);
            }
        });
    });
</script>
</body>

</html>