<?php
require_once 'includes/db.php';
include 'includes/header.php';

if (!isset($_GET['id'])) {
    echo "<script>window.location.href='/materials';</script>";
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT m.*, GROUP_CONCAT(c.name SEPARATOR ', ') as cat_name 
                        FROM materials m 
                        LEFT JOIN material_categories mc ON m.id = mc.material_id 
                        LEFT JOIN categories c ON mc.category_id = c.id 
                        WHERE m.id = ? GROUP BY m.id");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<div class='text-center py-20 text-white'>Material not found.</div>";
    include 'includes/footer.php';
    exit;
}

$item = $result->fetch_assoc();

// Handle Review Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
    $r_name = $conn->real_escape_string($_POST['reviewer_name']);
    $r_rating = (int) $_POST['rating'];
    $r_comment = $conn->real_escape_string($_POST['comment']);

    $conn->query("INSERT INTO reviews (material_id, user_name, rating, comment) VALUES ($id, '$r_name', $r_rating, '$r_comment')");
    echo "<script>alert('Review submitted!'); window.location.href='/material-details?id=$id';</script>";
}

// Fetch Reviews
$reviews = $conn->query("SELECT * FROM reviews WHERE material_id = $id ORDER BY created_at DESC");
$avg_rating = 0;
if ($reviews->num_rows > 0) {
    $total_stars = 0;
    $all_reviews = [];
    while ($r = $reviews->fetch_assoc()) {
        $total_stars += $r['rating'];
        $all_reviews[] = $r;
    }
    $avg_rating = round($total_stars / count($all_reviews), 1);
    // Reset pointer for loop later or just use array
} else {
    $all_reviews = [];
}
?>

<div class="pt-10 pb-20 bg-white/5 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumbs -->
        <div class="text-sm text-gray-400 mb-8 font-medium">
            <a href="/" class="hover:text-primary transition-colors">Home</a> <span class="mx-2 text-structure">/</span>
            <a href="/materials" class="hover:text-primary transition-colors">Materials</a> <span
                class="mx-2 text-structure">/</span>
            <span class="text-white font-bold">
                <?php echo $item['name']; ?>
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Image Section -->
            <div data-aos="fade-right">
                <div
                    class="relative h-96 lg:h-[500px] rounded-2xl overflow-hidden border border-white/10 shadow-xl hover:shadow-2xl transition-shadow glass-panel-dark">
                    <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>"
                        class="w-full h-full object-cover">
                    <div
                        class="absolute top-4 right-4 glass-panel-dark/90 backdrop-blur px-4 py-2 rounded-full text-primary font-bold border border-primary/20 shadow-sm">
                        Top Rated
                    </div>
                </div>
            </div>

            <!-- Details Section -->
            <div data-aos="fade-left">
                <h1 class="font-serif text-4xl font-bold text-gradient mb-1 mb-2">
                    <?php echo $item['name']; ?>
                </h1>
                <p class="text-[#FF1493] text-lg mb-6 font-medium flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-secondary"></span>
                    <?php echo $item['cat_name']; ?> Collection
                </p>

                <div class="flex items-end gap-2 mb-8">
                    <span class="text-4xl font-bold text-primary">₹
                        <?php echo $item['price_per_sqft']; ?>
                    </span>
                    <span class="text-gray-400 mb-1 font-medium">/ per sq.ft</span>
                </div>

                <div class="glass-panel-dark p-6 rounded-xl border border-white/10 mb-8 shadow-sm">
                    <h3 class="text-lg font-bold text-gradient mb-1 mb-2">Description</h3>
                    <p class="text-gray-400 leading-relaxed">
                        <?php echo $item['description']; ?>
                    </p>
                </div>

                <!-- Calculator & Quote -->
                <div class="glass-panel-dark p-8 rounded-xl border border-white/10 shadow-lg relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1 h-full bg-gradient-primary border-none shadow-[var(--shadow-primary)]"></div>
                    <h3 class="text-lg font-bold text-gradient mb-1 mb-6 flex items-center gap-2">
                        <i class="bi bi-calculator text-primary"></i> Estimate & Quote
                    </h3>

                    <form id="quoteForm" class="space-y-4">
                        <input type="hidden" name="material_id" value="<?php echo $item['id']; ?>">

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-400 text-xs font-bold uppercase tracking-wide mb-1.5">Area
                                    (sq.ft)</label>
                                <input type="number" id="areaInput" name="area"
                                    class="w-full bg-white/5 border border-white/10 rounded-lg p-3 text-white focus:border-[#FF1493] focus:ring-1 focus:ring-[#FF1493] outline-none transition-all placeholder-gray-500"
                                    placeholder="e.g. 150">
                            </div>
                            <div>
                                <label class="block text-gray-400 text-xs font-bold uppercase tracking-wide mb-1.5">Total
                                    Cost Estimate</label>
                                <input type="text" id="totalCost" name="total" readonly
                                    class="w-full bg-gradient-primary border-none shadow-[var(--shadow-primary)]/5 border border-primary/20 rounded-lg p-3 text-primary font-bold cursor-not-allowed"
                                    value="₹0.00">
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-400 text-xs font-bold uppercase tracking-wide mb-1.5">Your
                                Name</label>
                            <input type="text" name="name" required
                                class="w-full bg-white/5 border border-white/10 rounded-lg p-3 text-white focus:border-[#FF1493] focus:ring-1 focus:ring-[#FF1493] outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-gray-400 text-xs font-bold uppercase tracking-wide mb-1.5">Phone
                                Number</label>
                            <input type="tel" name="phone" required
                                class="w-full bg-white/5 border border-white/10 rounded-lg p-3 text-white focus:border-[#FF1493] focus:ring-1 focus:ring-[#FF1493] outline-none transition-all">
                        </div>

                        <div class="flex gap-4 mt-6">
                            <button type="submit"
                                class="flex-1 bg-gradient-primary border-none shadow-[var(--shadow-primary)] hover:bg-[#1b6d82] text-white font-bold py-3.5 rounded-lg transition-all shadow-lg hover:shadow-primary/30 transform active:scale-95">
                                Request Quote
                            </button>
                            <button type="button" id="downloadBtn"
                                class="flex-1 glass-panel-dark border border-white/10 hover:border-[#FF1493] text-gray-400 hover:text-[#FF1493] font-bold py-3.5 rounded-lg transition-all flex items-center justify-center gap-2 hover:bg-white/5">
                                <i class="bi bi-file-earmark-pdf"></i> Download PDF
                            </button>
                        </div>
                    </form>
                    <div id="msg" class="text-center mt-4 text-sm hidden"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="mt-20 border-t border-white/10 pt-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-4 mb-8" data-aos="fade-up">
            <h2 class="text-3xl font-serif font-bold text-gradient mb-1">Reviews</h2>
            <div class="flex items-center gap-2 bg-secondary/10 px-3 py-1 rounded-full border border-[#FF1493]/20">
                <div class="flex text-[#FF1493]">
                    <?php
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= round($avg_rating))
                            echo '<i class="bi bi-star-fill text-[#FF1493] px-0.5"></i>';
                        else
                            echo '<i class="bi bi-star text-structure px-0.5"></i>';
                    }
                    ?>
                </div>
                <span class="text-[#FF1493] font-bold">
                    <?php echo $avg_rating; ?>
                </span>
                <span class="text-gray-400 text-sm">(
                    <?php echo count($all_reviews); ?>)
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <!-- Write Review -->
            <div class="md:col-span-1" data-aos="fade-up" data-aos-delay="100">
                <div class="glass-panel-dark p-6 rounded-xl border border-white/10 shadow-sm">
                    <h3 class="text-xl font-bold text-gradient mb-1 mb-4">Write a Review</h3>
                    <form method="POST">
                        <div class="mb-4">
                            <label class="block text-gray-400 text-xs font-bold uppercase tracking-wide mb-1.5">Your
                                Name</label>
                            <input type="text" name="reviewer_name" required
                                class="w-full bg-white/5 border border-white/10 rounded-lg p-3 text-white focus:border-[#FF1493] focus:ring-1 focus:ring-[#FF1493] outline-none transition-all">
                        </div>
                        <div class="mb-4">
                            <label
                                class="block text-gray-400 text-xs font-bold uppercase tracking-wide mb-1.5">Rating</label>
                            <div class="flex gap-2">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="rating" value="<?php echo $i; ?>" class="peer hidden"
                                            required>
                                        <i
                                            class="bi bi-star text-2xl text-structure peer-checked:text-[#FF1493] peer-checked:bi-star-fill hover:text-[#FF1493] transition-colors"></i>
                                    </label>
                                <?php endfor; ?>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Select stars</p>
                        </div>
                        <div class="mb-4">
                            <label
                                class="block text-gray-400 text-xs font-bold uppercase tracking-wide mb-1.5">Comment</label>
                            <textarea name="comment" required rows="3"
                                class="w-full bg-white/5 border border-white/10 rounded-lg p-3 text-white focus:border-[#FF1493] focus:ring-1 focus:ring-[#FF1493] outline-none transition-all"></textarea>
                        </div>
                        <button type="submit" name="submit_review"
                            class="w-full bg-dark text-white font-bold py-3 rounded-lg hover:bg-black transition-colors shadow-lg">
                            Submit Review
                        </button>
                    </form>
                </div>
            </div>

            <!-- Review List -->
            <div class="md:col-span-2 space-y-6" data-aos="fade-up" data-aos-delay="200">
                <?php if (count($all_reviews) > 0): ?>
                    <?php foreach ($all_reviews as $rev): ?>
                        <div
                            class="glass-panel-dark p-6 rounded-xl border border-white/10 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h4 class="text-white font-bold text-lg">
                                        <?php echo htmlspecialchars($rev['user_name']); ?>
                                    </h4>
                                    <div class="flex text-[#FF1493] mt-1">
                                        <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $rev['rating'])
                                                echo '<i class="bi bi-star-fill text-xs px-0.5"></i>';
                                            else
                                                echo '<i class="bi bi-star text-xs text-structure px-0.5"></i>';
                                        }
                                        ?>
                                    </div>
                                </div>
                                <span
                                    class="text-xs text-gray-400 font-medium bg-white/5 px-2 py-1 rounded-full border border-white/10">
                                    <?php echo date('M d, Y', strtotime($rev['created_at'])); ?>
                                </span>
                            </div>
                            <p class="text-gray-400 text-sm leading-relaxed mt-3">
                                "<?php echo htmlspecialchars($rev['comment']); ?>"
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-10 border-2 border-dashed border-white/10 rounded-xl bg-white/5/50">
                        <i class="bi bi-chat-square-text text-4xl text-structure mx-auto mb-2 block"></i>
                        <p class="text-gray-400">No reviews yet. Be the first to share your thoughts!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    const pricePerSqft = <?php echo $item['price_per_sqft']; ?>;
    const materialName = "<?php echo $item['name']; ?>";
    const areaInput = document.getElementById('areaInput');
    const totalInput = document.getElementById('totalCost');

    areaInput.addEventListener('input', (e) => {
        const area = parseFloat(e.target.value) || 0;
        const total = area * pricePerSqft;
        totalInput.value = '₹' + total.toFixed(2);
    });

    // PDF Download Logic
    document.getElementById('downloadBtn').addEventListener('click', () => {
        const area = areaInput.value;
        const total = totalInput.value;
        const name = document.querySelector('input[name="name"]').value;

        if (!area || !name) {
            alert('Please fill in Name and Area to download PDF.');
            return;
        }

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Header Style
        doc.setFillColor(212, 175, 55); // Gold color
        doc.rect(0, 0, 210, 40, 'F');
        doc.setTextColor(0, 0, 0);
        doc.setFontSize(22);
        doc.text("Interior Design Viewer", 105, 20, null, null, "center");
        doc.setFontSize(12);
        doc.text("Official Price Estimate", 105, 30, null, null, "center");

        // Content
        doc.setTextColor(0, 0, 0);
        doc.setFontSize(14);
        doc.text(`Client Name: ${name}`, 20, 60);
        doc.text(`Date: ${new Date().toLocaleDateString()}`, 150, 60);

        doc.setLineWidth(0.5);
        doc.line(20, 65, 190, 65);

        doc.text("Material Details:", 20, 80);
        doc.setFontSize(12);
        doc.text(`Material: ${materialName}`, 30, 90);
        doc.text(`Price per Sq.ft: ₹${pricePerSqft}`, 30, 100);
        doc.text(`Est. Area: ${area} sq.ft`, 30, 110);

        doc.setFontSize(16);
        doc.setTextColor(184, 134, 11); // Dark gold text
        doc.text(`Total Estimate: ${total}`, 190, 130, null, null, "right");

        doc.setFontSize(10);
        doc.setTextColor(100);
        doc.text("Note: This is an estimated price. Final quotation may vary based on site conditions.", 20, 150);

        doc.save(`Quote_${materialName.replace(/\s+/g, '_')}.pdf`);
    });


    document.getElementById('quoteForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);

        // Remove ₹ stuff for API
        data.total = parseFloat(totalInput.value.replace('₹', ''));

        fetch('/api/submit_quote.php', {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'Content-Type': 'application/json' }
        })
            .then(res => res.json())
            .then(res => {
                const msg = document.getElementById('msg');
                msg.textContent = res.message;
                msg.className = 'text-center mt-4 text-sm ' + (res.success ? 'text-green-500' : 'text-red-500');
                msg.classList.remove('hidden');
                if (res.success) this.reset();
            });
    });
</script>

<?php include 'includes/footer.php'; ?>