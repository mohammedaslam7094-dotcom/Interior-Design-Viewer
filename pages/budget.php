<?php include 'includes/header.php';
require_once 'includes/db.php';

// Fetch materials for dropdowns
$walls = $conn->query("SELECT DISTINCT m.* FROM materials m JOIN material_categories mc ON m.id = mc.material_id JOIN categories c ON mc.category_id = c.id WHERE c.type='wall' OR c.slug='modern-wall'");
$floors = $conn->query("SELECT DISTINCT m.* FROM materials m JOIN material_categories mc ON m.id = mc.material_id JOIN categories c ON mc.category_id = c.id WHERE c.type='floor' OR c.slug='classic-floor'");
$ceilings = $conn->query("SELECT DISTINCT m.* FROM materials m JOIN material_categories mc ON m.id = mc.material_id JOIN categories c ON mc.category_id = c.id WHERE c.type='ceiling' OR c.slug='false-ceiling'");

// Helper to render options
function render_options($result)
{
    if ($result->num_rows > 0) {
        // Reset pointer
        $result->data_seek(0);
        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['id']}' data-price='{$row['price_per_sqft']}'>{$row['name']} - ₹ {$row['price_per_sqft']}/sq.ft</option>";
        }
    }
}
?>

<div class="relative bg-gradient-primary border-none shadow-[var(--shadow-primary)] pt-32 pb-32 overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1545042746-ec9e5a59b359?auto=format&fit=crop&w=1920&q=80"
            alt="Budget Background" class="w-full h-full object-cover opacity-10 mixing-blend-overlay">
        <div class="absolute inset-0 bg-gradient-to-b from-primary/95 via-primary/95 to-primary"></div>
        <div
            class="absolute top-0 right-0 w-96 h-96 bg-secondary/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2">
        </div>
        <div
            class="absolute bottom-0 left-0 w-96 h-96 bg-secondary/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2">
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center" data-aos="fade-up">
        <div
            class="w-16 h-16 bg-secondary/20 rounded-2xl flex items-center justify-center mx-auto mb-6 backdrop-blur-sm border border-[#FF1493]/30">
            <i class="bi bi-calculator text-3xl text-[#FF1493]"></i>
        </div>
        <h1 class="font-serif text-4xl md:text-5xl lg:text-6xl text-gradient mb-1 font-bold mb-6">Project Budget Planner</h1>
        <div class="h-1 w-20 bg-secondary mx-auto mb-6"></div>
        <p class="text-white/80 max-w-2xl mx-auto text-lg font-light leading-relaxed">Calculate instant, accurate cost
            estimates for your upcoming renovation by selecting from our premium material library.</p>
    </div>
</div>

<div class="pb-24 bg-white/5 min-h-screen relative -mt-16 z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            <!-- Left: Inputs -->
            <div class="lg:col-span-2 space-y-8">

                <!-- 1. Room Dimensions -->
                <div class="glass-panel-dark p-8 rounded-[2rem] border border-white/10 shadow-xl shadow-primary/5 relative overflow-hidden"
                    data-aos="fade-up">
                    <div
                        class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 z-0">
                    </div>

                    <div class="relative z-10 mb-8 border-b border-white/10/50 pb-6 flex items-center gap-4">
                        <div
                            class="w-12 h-12 bg-gradient-primary border-none shadow-[var(--shadow-primary)] text-white rounded-xl flex items-center justify-center text-xl font-bold shadow-md shadow-primary/20">
                            1</div>
                        <div>
                            <h3 class="text-2xl font-bold text-gradient mb-1">Room Dimensions</h3>
                            <p class="text-sm text-gray-400 mt-1">Enter your space measurements accurately.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative z-10">
                        <div class="space-y-2">
                            <label
                                class="block text-gray-400 text-xs font-bold uppercase tracking-wider pl-4 border-l-2 border-[#FF1493]">Length
                                (ft)</label>
                            <div class="relative">
                                <input type="number" id="len" value="12" min="1"
                                    class="w-full bg-white/5 text-white text-xl font-medium border-none rounded-xl py-4 pl-6 pr-12 focus:ring-2 focus:ring-primary/20 focus:glass-panel-dark outline-none transition-all shadow-inner">
                                <span
                                    class="absolute right-6 top-1/2 transform -translate-y-1/2 text-gray-400 font-bold text-sm">ft</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label
                                class="block text-gray-400 text-xs font-bold uppercase tracking-wider pl-4 border-l-2 border-[#FF1493]">Width
                                (ft)</label>
                            <div class="relative">
                                <input type="number" id="wid" value="10" min="1"
                                    class="w-full bg-white/5 text-white text-xl font-medium border-none rounded-xl py-4 pl-6 pr-12 focus:ring-2 focus:ring-primary/20 focus:glass-panel-dark outline-none transition-all shadow-inner">
                                <span
                                    class="absolute right-6 top-1/2 transform -translate-y-1/2 text-gray-400 font-bold text-sm">ft</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label
                                class="block text-gray-400 text-xs font-bold uppercase tracking-wider pl-4 border-l-2 border-[#FF1493]">Height
                                (ft)</label>
                            <div class="relative">
                                <input type="number" id="hgt" value="10" min="1"
                                    class="w-full bg-white/5 text-white text-xl font-medium border-none rounded-xl py-4 pl-6 pr-12 focus:ring-2 focus:ring-primary/20 focus:glass-panel-dark outline-none transition-all shadow-inner">
                                <span
                                    class="absolute right-6 top-1/2 transform -translate-y-1/2 text-gray-400 font-bold text-sm">ft</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col md:flex-row gap-4 relative z-10">
                        <div
                            class="flex-1 bg-gradient-to-br from-primary to-[#2a4a82] rounded-xl p-5 text-white flex items-center justify-between shadow-lg shadow-primary/20 border border-primary">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-grid-3x3-gap glass-panel-dark/20 p-2 rounded-lg text-xl"></i>
                                <span class="font-medium">Total Floor Area</span>
                            </div>
                            <div class="text-right">
                                <span id="floorArea" class="text-3xl font-bold text-[#FF1493]">120</span> <span
                                    class="text-xs text-white/70 uppercase tracking-widest">sq.ft</span>
                            </div>
                        </div>
                        <div
                            class="flex-1 glass-panel-dark border border-white/10 rounded-xl p-5 flex items-center justify-between shadow-sm">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-intersect text-primary bg-gradient-primary border-none shadow-[var(--shadow-primary)]/5 p-2 rounded-lg text-xl"></i>
                                <span class="font-medium text-white">Total Wall Area</span>
                            </div>
                            <div class="text-right">
                                <span id="wallArea" class="text-3xl font-bold text-primary">440</span> <span
                                    class="text-xs text-gray-400 uppercase tracking-widest">sq.ft</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Material Selection -->
                <div class="glass-panel-dark p-8 rounded-[2rem] border border-white/10 shadow-xl shadow-primary/5 relative overflow-hidden"
                    data-aos="fade-up" data-aos-delay="100">
                    <div
                        class="absolute bottom-0 left-0 w-64 h-64 bg-secondary/5 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2 z-0">
                    </div>

                    <div class="relative z-10 mb-8 border-b border-white/10/50 pb-6 flex items-center gap-4">
                        <div
                            class="w-12 h-12 bg-gradient-primary border-none shadow-[var(--shadow-primary)] text-white rounded-xl flex items-center justify-center text-xl font-bold shadow-md shadow-primary/20">
                            2</div>
                        <div>
                            <h3 class="text-2xl font-bold text-gradient mb-1">Material Selection</h3>
                            <p class="text-sm text-gray-400 mt-1">Choose premium finishes for your surfaces.</p>
                        </div>
                    </div>

                    <div class="space-y-6 relative z-10">
                        <!-- Flooring -->
                        <div class="group">
                            <label
                                class="block text-gray-400 text-xs font-bold uppercase tracking-wider mb-2 flex items-center gap-2"><i
                                    class="bi bi-square-half text-primary"></i> Flooring Material</label>
                            <div class="relative">
                                <select id="floorMat"
                                    class="w-full bg-white/5 border border-transparent rounded-xl p-4 pl-5 text-white font-medium appearance-none focus:border-primary/30 focus:glass-panel-dark outline-none transition-all cursor-pointer hover:border-white/10 shadow-inner hover:shadow-none group-hover:glass-panel-dark">
                                    <option value="" data-price="0">-- Select Floor Finish --</option>
                                    <?php render_options($floors); ?>
                                </select>
                                <i
                                    class="bi bi-chevron-down absolute right-5 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>

                        <!-- Walls -->
                        <div class="group">
                            <label
                                class="block text-gray-400 text-xs font-bold uppercase tracking-wider mb-2 flex items-center gap-2"><i
                                    class="bi bi-intersect text-primary"></i> Wall Finish</label>
                            <div class="relative">
                                <select id="wallMat"
                                    class="w-full bg-white/5 border border-transparent rounded-xl p-4 pl-5 text-white font-medium appearance-none focus:border-primary/30 focus:glass-panel-dark outline-none transition-all cursor-pointer hover:border-white/10 shadow-inner hover:shadow-none group-hover:glass-panel-dark">
                                    <option value="" data-price="0">-- Select Wall Finish --</option>
                                    <?php render_options($walls); ?>
                                </select>
                                <i
                                    class="bi bi-chevron-down absolute right-5 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>

                        <!-- Ceiling -->
                        <div class="group">
                            <label
                                class="block text-gray-400 text-xs font-bold uppercase tracking-wider mb-2 flex items-center gap-2"><i
                                    class="bi bi-cloud text-primary"></i> Ceiling Detail</label>
                            <div class="relative">
                                <select id="ceilMat"
                                    class="w-full bg-white/5 border border-transparent rounded-xl p-4 pl-5 text-white font-medium appearance-none focus:border-primary/30 focus:glass-panel-dark outline-none transition-all cursor-pointer hover:border-white/10 shadow-inner hover:shadow-none group-hover:glass-panel-dark">
                                    <option value="" data-price="0">-- Select Ceiling Finish --</option>
                                    <?php render_options($ceilings); ?>
                                </select>
                                <i
                                    class="bi bi-chevron-down absolute right-5 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right: Summary -->
            <div class="lg:col-span-1" data-aos="fade-left" data-aos-delay="200">
                <div
                    class="bg-gradient-primary border-none shadow-[var(--shadow-primary)] p-8 rounded-[2rem] shadow-2xl shadow-primary/30 sticky top-28 overflow-hidden relative">
                    <!-- Overlay noise/texture for premium feel -->
                    <div class="absolute inset-0 opacity-20 pointer-events-none"
                        style="background-image: url('data:image/svg+xml,%3Csvg viewBox=\'0 0 200 200\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cfilter id=\'noiseFilter\'%3E%3CfeTurbulence type=\'fractalNoise\' baseFrequency=\'0.65\' numOctaves=\'3\' stitchTiles=\'stitch\'/%3E%3C/filter%3E%3Crect width=\'100%25\' height=\'100%25\' filter=\'url(%23noiseFilter)\'/%3E%3C/svg%3E');">
                    </div>

                    <div class="absolute -top-20 -right-20 w-48 h-48 bg-secondary/20 rounded-full blur-3xl z-0"></div>

                    <div class="relative z-10 flex justify-between items-end mb-8 border-b border-white/10 pb-4">
                        <h3 class="text-xl font-serif font-bold text-gradient mb-1">Cost Summary</h3>
                        <i class="bi bi-receipt text-2xl text-[#FF1493]"></i>
                    </div>

                    <!-- Chart -->
                    <div
                        class="relative w-48 h-48 mx-auto mb-8 glass-panel-dark/5 rounded-full p-2 border border-white/10 backdrop-blur-sm shadow-inner">
                        <canvas id="budgetChart"></canvas>
                        <div class="absolute inset-0 items-center justify-center flex flex-col pointer-events-none">
                            <span class="text-white/50 text-[10px] uppercase font-bold tracking-widest">Total
                                Cost</span>
                        </div>
                    </div>

                    <!-- Breakdown -->
                    <div class="space-y-4 mb-8">
                        <div
                            class="flex justify-between items-center glass-panel-dark/5 rounded-xl p-3 border border-white/5 backdrop-blur-sm">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-secondary"></div>
                                <span class="text-white/80 text-sm">Flooring</span>
                            </div>
                            <span class="text-white font-medium" id="floorCost">₹0.00</span>
                        </div>
                        <div
                            class="flex justify-between items-center glass-panel-dark/5 rounded-xl p-3 border border-white/5 backdrop-blur-sm">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full glass-panel-dark"></div>
                                <span class="text-white/80 text-sm">Walls</span>
                            </div>
                            <span class="text-white font-medium" id="wallCost">₹0.00</span>
                        </div>
                        <div
                            class="flex justify-between items-center glass-panel-dark/5 rounded-xl p-3 border border-white/5 backdrop-blur-sm">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-[#4A6D9B]"></div>
                                <span class="text-white/80 text-sm">Ceiling</span>
                            </div>
                            <span class="text-white font-medium" id="ceilCost">₹0.00</span>
                        </div>
                    </div>

                    <!-- Total -->
                    <div
                        class="glass-panel-dark/10 backdrop-blur p-5 rounded-xl border border-white/20 mb-8 relative overflow-hidden">
                        <div
                            class="absolute top-0 right-[-10%] w-full h-full bg-gradient-to-r from-transparent to-secondary/10 transform skew-x-12 pointer-events-none">
                        </div>
                        <span class="block text-xs text-white/70 uppercase tracking-widest font-bold mb-1">Final
                            Estimate</span>
                        <div class="flex justify-between items-baseline border-t border-white/20 pt-2 border-dashed">
                            <span class="text-[#FF1493] font-bold text-3xl" id="totalCost">₹0.00</span>
                        </div>
                    </div>

                    <div class="space-y-3 relative z-10">
                        <button id="downloadBoq"
                            class="w-full btn-premium hover:shadow-secondary/30 text-white font-bold py-4 rounded-xl transition-all flex justify-center items-center gap-2 transform active:scale-[0.98]">
                            <i class="bi bi-download"></i> Download Details
                        </button>
                        <p class="text-[10px] text-center text-white/50 uppercase tracking-wider px-4">Estimates are
                            indicative. Installation & labor costs not included.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // 1. Elements
        const inputs = ['len', 'wid', 'hgt'].map(id => document.getElementById(id));
        const selects = ['floorMat', 'wallMat', 'ceilMat'].map(id => document.getElementById(id));

        // Areas
        const floorAreaEl = document.getElementById('floorArea');
        const wallAreaEl = document.getElementById('wallArea');

        // Costs
        const costsEl = {
            floor: document.getElementById('floorCost'),
            wall: document.getElementById('wallCost'),
            ceil: document.getElementById('ceilCost'),
            total: document.getElementById('totalCost')
        };

        // Chart
        const ctx = document.getElementById('budgetChart').getContext('2d');

        let chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Flooring', 'Walls', 'Ceiling'],
                datasets: [{
                    data: [0, 0, 0],
                    backgroundColor: ['#FBCF84', '#FFFFFF', '#4A6D9B'], // Secondary, White, Muted Blue
                    borderWidth: 2,
                    borderColor: '#1E355F',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                        titleColor: '#0F172A',
                        bodyColor: '#1E355F',
                        bodyFont: { weight: 'bold' },
                        borderColor: '#E2E8F0',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 8,
                        boxPadding: 6,
                        callbacks: {
                            label: function (context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(context.parsed);
                                }
                                return label;
                            }
                        }
                    }
                },
                cutout: '75%',
                animation: {
                    animateScale: true,
                    animateRotate: true,
                    duration: 800,
                    easing: 'easeOutQuart'
                }
            }
        });

        // Format Currency Helper
        function formatCurrency(num) {
            return new Intl.NumberFormat('en-IN', {
                style: 'currency',
                currency: 'INR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(num);
        }

        // 2. Logic
        function calculate() {
            const l = parseFloat(inputs[0].value) || 0;
            const w = parseFloat(inputs[1].value) || 0;
            const h = parseFloat(inputs[2].value) || 0;

            const floorArea = l * w;
            const wallArea = (2 * l * h) + (2 * w * h); // Simplified (no windows/doors deduction)

            // Update Area Display smoothly
            animateValue(floorAreaEl, parseInt(floorAreaEl.innerText) || 0, floorArea, 500);
            animateValue(wallAreaEl, parseInt(wallAreaEl.innerText) || 0, wallArea, 500);

            // Get Prices
            const pFloor = parseFloat(selects[0].selectedOptions[0]?.dataset?.price) || 0;
            const pWall = parseFloat(selects[1].selectedOptions[0]?.dataset?.price) || 0;
            const pCeil = parseFloat(selects[2].selectedOptions[0]?.dataset?.price) || 0;

            // Calculate Costs
            const cFloor = floorArea * pFloor;
            const cWall = wallArea * pWall;
            const cCeil = floorArea * pCeil; // Ceiling area usually same as floor
            const total = cFloor + cWall + cCeil;

            // Update UI
            costsEl.floor.innerText = formatCurrency(cFloor);
            costsEl.wall.innerText = formatCurrency(cWall);
            costsEl.ceil.innerText = formatCurrency(cCeil);
            costsEl.total.innerText = formatCurrency(total);

            // Update Chart
            chart.data.datasets[0].data = [cFloor, cWall, cCeil];
            // If all zero, put grayscale placeholder
            if (total === 0) {
                chart.data.datasets[0].data = [1, 1, 1];
                chart.data.datasets[0].backgroundColor = ['#ffffff1a', '#ffffff1a', '#ffffff1a'];
                chart.options.plugins.tooltip.enabled = false;
            } else {
                chart.data.datasets[0].backgroundColor = ['#FBCF84', '#FFFFFF', '#4A6D9B'];
                chart.options.plugins.tooltip.enabled = true;
            }
            chart.update();
        }

        // Animated Number Counter
        function animateValue(obj, start, end, duration) {
            // Basic fallback handler
            obj.innerHTML = end;
        }

        // Listeners
        inputs.forEach(i => i.addEventListener('input', calculate));
        selects.forEach(s => s.addEventListener('change', calculate));

        // Init
        calculate();

        // 3. PDF Gen
        document.getElementById('downloadBoq').addEventListener('click', () => {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Get current values
            const l = inputs[0].value;
            const w = inputs[1].value;
            const h = inputs[2].value;
            const total = costsEl.total.innerText;

            // Header
            doc.setFillColor(21, 21, 21);
            doc.rect(0, 0, 210, 40, 'F');
            doc.setTextColor(212, 175, 55);
            doc.setFontSize(22);
            doc.text("IDV Project Planner", 105, 20, null, null, "center");
            doc.setTextColor(255, 255, 255);
            doc.setFontSize(10);
            doc.text("Bill of Quantities (Estimate)", 105, 30, null, null, "center");

            // Info
            doc.setTextColor(0, 0, 0);
            doc.setFontSize(12);
            doc.text(`Room Size: ${l}ft x ${w}ft (Height: ${h}ft)`, 20, 60);
            doc.text(`Date of Estimate: ${new Date().toLocaleDateString()}`, 20, 70);

            // Table Header
            let y = 90;
            doc.setFillColor(200, 200, 200);
            doc.rect(20, y - 8, 170, 10, 'F');
            doc.setFont(undefined, 'bold');
            doc.text("Item", 25, y);
            doc.text("Material", 80, y);
            doc.text("Area", 130, y);
            doc.text("Cost", 170, y);
            doc.setFont(undefined, 'normal');

            y += 15;

            // Data Rows
            const items = [
                { type: 'Flooring', mat: selects[0], area: floorAreaEl.innerText, cost: costsEl.floor.innerText },
                { type: 'Walls', mat: selects[1], area: wallAreaEl.innerText, cost: costsEl.wall.innerText },
                { type: 'Ceiling', mat: selects[2], area: floorAreaEl.innerText, cost: costsEl.ceil.innerText },
            ];

            items.forEach(item => {
                const matName = item.mat.selectedOptions[0].text.split('-')[0] || "None Selected";
                doc.text(item.type, 25, y);
                doc.text(matName.substring(0, 25), 80, y); // Truncate
                doc.text(item.area + " sq.ft", 130, y);
                doc.text(item.cost, 170, y);
                y += 10;
            });

            // Total
            y += 10;
            doc.setLineWidth(0.5);
            doc.line(20, y, 190, y);
            y += 10;
            doc.setFont(undefined, 'bold');
            doc.text("ESTIMATED TOTAL", 130, y);
            doc.setTextColor(184, 134, 11);
            doc.text(total, 170, y);

            doc.save("IDV_Project_Budget.pdf");
        });
    });
</script>

<?php include 'includes/footer.php'; ?>