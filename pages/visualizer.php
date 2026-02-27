<?php include 'includes/header.php'; ?>

<!-- Main Visualizer Container -->
<div
    class="lg:h-[calc(100vh-80px)] h-auto min-h-screen flex flex-col lg:flex-row bg-white/5 overflow-x-hidden font-sans">
    <style>
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #22819A;
        }
    </style>

    <!-- Left Sidebar: Tools & Workflow -->
    <aside class="w-full lg:w-[400px] flex flex-col glass-panel-dark border-r border-white/10 z-20 shadow-xl">

        <!-- Header -->
        <div class="px-6 py-5 border-b border-white/10 glass-panel-dark flex justify-between items-center">
            <h1 class="text-lg font-semibold text-gradient mb-1 tracking-wide flex items-center gap-2">
                <i class="bi bi-stars text-primary"></i> Design Studio
            </h1>
            <span
                class="px-2 py-0.5 bg-secondary/10 text-[#FF1493] text-[10px] uppercase font-bold tracking-wider rounded border border-[#FF1493]/20">Beta</span>
        </div>

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto custom-scrollbar p-6 space-y-8">

            <!-- Step 1: Upload -->
            <section id="step-1">
                <div class="flex items-center gap-3 mb-4">
                    <div
                        class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-primary font-bold border border-white/10 shadow-sm text-sm">
                        1</div>
                    <h2 class="text-sm font-medium text-gradient mb-1">Upload Room</h2>
                </div>

                <div class="relative group">
                    <input type="file" id="imageUpload" accept="image/*"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                    <div
                        class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center group-hover:border-primary group-hover:bg-gradient-primary border-none shadow-[var(--shadow-primary)]/5 transition-all duration-300">
                        <div
                            class="w-12 h-12 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400 group-hover:text-primary transition-colors shadow-sm">
                            <i class="bi bi-cloud-arrow-up text-3xl"></i>
                        </div>
                        <p class="text-sm text-white font-medium group-hover:text-primary">Click or Drag photo</p>
                        <p class="text-xs text-gray-400 mt-1">Supports JPG, PNG</p>
                    </div>
                </div>
            </section>

            <!-- Step 2: Define Area -->
            <section id="step-2" class="opacity-40 pointer-events-none transition-all duration-300">
                <div class="flex items-center gap-3 mb-4">
                    <div
                        class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-primary font-bold border border-white/10 shadow-sm text-sm">
                        2</div>
                    <div>
                        <h2 class="text-sm font-medium text-gradient mb-1">Define Area</h2>
                        <p class="text-[10px] text-gray-400">Trace the area you want to change</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 mb-4">
                    <button id="undoBtn"
                        class="flex items-center justify-center gap-2 py-2.5 px-4 bg-white/5 hover:bg-white/10 text-white rounded-lg text-xs font-medium transition-colors border border-white/10">
                        <i class="bi bi-arrow-counterclockwise"></i> Undo Point
                    </button>
                    <button id="clearBtn"
                        class="flex items-center justify-center gap-2 py-2.5 px-4 bg-red-500/5 hover:bg-red-500/10 text-red-500 rounded-lg text-xs font-medium transition-colors border border-red-500/10">
                        <i class="bi bi-trash"></i> Reset
                    </button>
                </div>

                <div class="p-3 bg-secondary/5 border border-[#FF1493]/20 rounded-lg flex gap-3">
                    <i class="bi bi-info-circle text-[#FF1493] shrink-0 mt-0.5"></i>
                    <p class="text-xs text-[#FF1493]/80 leading-relaxed">Click corners of a wall/floor to draw shape.
                        Connect back to start to finish.</p>
                </div>
            </section>

            <!-- Step 3: Materials -->
            <section id="step-3" class="opacity-40 pointer-events-none transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-primary font-bold border border-white/10 shadow-sm text-sm">
                            3</div>
                        <h2 class="text-sm font-medium text-gradient mb-1">Apply Material</h2>
                    </div>
                    <div id="selected-material-name" class="text-xs text-primary font-medium hidden"></div>
                </div>

                <!-- Filter Tabs -->
                <div class="flex p-1 bg-white/5 rounded-lg mb-4 border border-white/10">
                    <button
                        class="filter-btn flex-1 py-1.5 text-xs font-bold rounded-md bg-gradient-primary border-none shadow-[var(--shadow-primary)] text-white shadow-sm transition-all"
                        data-filter="all">All</button>
                    <button
                        class="filter-btn flex-1 py-1.5 text-xs font-medium rounded-md text-gray-400 hover:text-white transition-all"
                        data-filter="wall">Walls</button>
                    <button
                        class="filter-btn flex-1 py-1.5 text-xs font-medium rounded-md text-gray-400 hover:text-white transition-all"
                        data-filter="floor">Floors</button>
                    <button
                        class="filter-btn flex-1 py-1.5 text-xs font-medium rounded-md text-gray-400 hover:text-white transition-all"
                        data-filter="ceiling">Ceilings</button>
                </div>

                <!-- Search -->
                <div class="relative mb-4">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="materialSearch" placeholder="Search materials..."
                        class="w-full bg-white/5 border border-white/10 rounded-lg py-2.5 pl-9 pr-4 text-xs text-white focus:outline-none focus:border-[#FF1493] transition-colors placeholder-gray-500">
                </div>

                <!-- Material Grid -->
                <div id="material-list"
                    class="grid grid-cols-3 gap-3 max-h-[300px] overflow-y-auto pr-1 custom-scrollbar">
                    <!-- Items injected here -->
                </div>
            </section>
        </div>

        <!-- Footer Action -->
        <div class="p-6 border-t border-white/10 glass-panel-dark">
            <button id="downloadBtn"
                class="w-full flex items-center justify-center gap-2 py-3.5 bg-gradient-primary border-none shadow-[var(--shadow-primary)] hover:bg-[#1b6d82] text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-primary/30 disabled:opacity-50 disabled:cursor-not-allowed transform active:scale-95"
                disabled>
                <i class="bi bi-download"></i> Download Design
            </button>
        </div>
    </aside>

    <!-- Right: Canvas Area -->
    <main class="flex-1 relative bg-white/10 flex items-center justify-center overflow-hidden p-8">

        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-[0.05]"
            style="background-image: radial-gradient(#000000 1px, transparent 1px); background-size: 24px 24px;"></div>

        <!-- Canvas Container -->
        <div id="canvas-wrapper"
            class="relative shadow-2xl rounded-lg overflow-hidden border border-white/10 glass-panel-dark max-w-full max-h-full transition-all duration-500 ease-out opacity-0 translate-y-4">

            <div id="placeholder-msg"
                class="absolute inset-0 flex flex-col items-center justify-center text-center p-8 z-10 glass-panel-dark">
                <div
                    class="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center mb-6 border border-white/10 shadow-sm">
                    <i class="bi bi-file-earmark-image text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-medium text-gradient mb-1 mb-2">Ready to Visualize?</h3>
                <p class="text-gray-400 text-sm max-w-sm">Upload a photo of your room from the sidebar to start
                    designing your dream space.</p>
            </div>

            <canvas id="mainCanvas" class="block"></canvas>

            <!-- Floating Canvas Toolbar -->
            <div id="canvas-toolbar"
                class="absolute bottom-6 left-1/2 -translate-x-1/2 glass-panel-dark/90 backdrop-blur-md border border-white/10 rounded-full px-6 py-2.5 flex gap-5 items-center shadow-lg opacity-0 translate-y-10 transition-all duration-500">
                <div class="flex items-center gap-2">
                    <span id="status-indicator" class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                    <span id="status-text" class="text-xs text-white font-medium tracking-wide">Waiting for
                        image...</span>
                </div>
            </div>
        </div>
    </main>

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Elements
        const canvasWrapper = document.getElementById('canvas-wrapper');
        const canvas = document.getElementById('mainCanvas');
        const ctx = canvas.getContext('2d');
        const placeholder = document.getElementById('placeholder-msg');
        const materialList = document.getElementById('material-list');
        const filterBtns = document.querySelectorAll('.filter-btn');
        const searchInput = document.getElementById('materialSearch');
        const statusText = document.getElementById('status-text');
        const statusInd = document.getElementById('status-indicator');
        const downloadBtn = document.getElementById('downloadBtn');
        const step2 = document.getElementById('step-2');
        const step3 = document.getElementById('step-3');
        const selectedMaterialName = document.getElementById('selected-material-name');

        const undoBtn = document.getElementById('undoBtn');
        const clearBtn = document.getElementById('clearBtn');

        // State
        let baseImage = null;
        let points = [];
        let isClosed = false;
        let currentMaterialImg = null;
        let allMaterials = [];
        let currentFilter = 'all';

        // --- Initialization ---

        // Load Materials
        fetchMaterials();

        function fetchMaterials() {
            materialList.innerHTML = '<div class="col-span-3 text-center py-8"><i class="bi bi-arrow-repeat animate-spin text-primary text-2xl"></i></div>';
            // lucide.createIcons();

            fetch(`/api/get_materials.php?category=all`)
                .then(res => res.json())
                .then(data => {
                    allMaterials = data;
                    renderMaterials();
                })
                .catch(err => {
                    materialList.innerHTML = '<p class="text-red-500 text-xs col-span-3 text-center">Failed to load materials.</p>';
                });
        }

        function renderMaterials() {
            const term = searchInput.value.toLowerCase();
            const filtered = allMaterials.filter(m => {
                const matchCat = currentFilter === 'all' || (m.category_type && m.category_type.includes(currentFilter));
                const matchSearch = m.name.toLowerCase().includes(term);
                return matchCat && matchSearch;
            });

            materialList.innerHTML = '';

            if (filtered.length === 0) {
                materialList.innerHTML = '<p class="text-gray-400 text-xs col-span-3 text-center py-4">No materials found.</p>';
                return;
            }

            filtered.forEach(m => {
                const item = document.createElement('div');
                // Check if this material is the currently selected one
                const isSelected = currentMaterialImg && currentMaterialImg.src === m.image_url;

                // Updated classes for new theme
                item.className = `cursor-pointer group relative aspect-square rounded-lg overflow-hidden border transition-all duration-200 ${isSelected ? 'border-primary ring-1 ring-primary shadow-md' : 'border-white/10 hover:border-primary'}`;
                item.innerHTML = `
                    <img src="${m.image_url}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-primary border-none shadow-[var(--shadow-primary)]/80 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity duration-200">
                        <span class="text-[10px] text-white font-medium px-2 text-center">${m.name}</span>
                    </div>
                `;

                item.onclick = () => {
                    // Update selection visual
                    document.querySelectorAll('#material-list > div').forEach(el => {
                        el.className = 'cursor-pointer group relative aspect-square rounded-lg overflow-hidden border border-white/10 hover:border-primary transition-all duration-200';
                    });
                    item.className = 'cursor-pointer group relative aspect-square rounded-lg overflow-hidden border border-primary ring-1 ring-primary shadow-md transition-all duration-200';

                    const img = new Image();
                    img.crossOrigin = "Anonymous";
                    img.onload = () => {
                        currentMaterialImg = img;
                        selectedMaterialName.textContent = m.name;
                        selectedMaterialName.classList.remove('hidden');
                        setStatus('Material Applied', 'success');
                        draw();
                    };
                    img.src = m.image_url;
                };
                materialList.appendChild(item);
            });
        }

        // Search Listener
        searchInput.addEventListener('input', renderMaterials);

        // Filter Listener
        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => {
                    b.classList.remove('bg-gradient-primary border-none shadow-[var(--shadow-primary)]', 'text-white', 'font-bold');
                    b.classList.add('text-gray-400', 'hover:text-white', 'font-medium');
                });
                btn.classList.remove('text-gray-400', 'hover:text-white', 'font-medium');
                btn.classList.add('bg-gradient-primary border-none shadow-[var(--shadow-primary)]', 'text-white', 'font-bold');

                currentFilter = btn.dataset.filter;
                renderMaterials();
            });
        });


        // --- Canvas Logic ---

        // 1. Upload
        document.getElementById('imageUpload').addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (event) => {
                const img = new Image();
                img.onload = () => {
                    baseImage = img;
                    points = [];
                    isClosed = false;
                    currentMaterialImg = null;

                    // UI Updates
                    placeholder.classList.add('hidden');
                    canvasWrapper.classList.remove('opacity-0', 'translate-y-4');

                    // Enable Step 2
                    step2.classList.remove('opacity-40', 'pointer-events-none');
                    setStatus('Draw the area to change', 'active');

                    fitCanvasToImage();
                    draw();

                    // Show toolbar
                    document.getElementById('canvas-toolbar').classList.remove('opacity-0', 'translate-y-10');
                    downloadBtn.disabled = false;
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        });

        function fitCanvasToImage() {
            if (!baseImage) return;
            // Use the main container (grandparent) to calculate available space
            // This prevents the issue where the wrapper shrinks to valid content size
            const mainContainer = canvas.parentElement.parentElement;

            // Available space - accounting for main container padding (p-8 = 32px * 2 = 64px) + extra safety
            const maxWidth = mainContainer.clientWidth - 80;
            const maxHeight = mainContainer.clientHeight - 80;

            const imgRatio = baseImage.width / baseImage.height;
            const containerRatio = maxWidth / maxHeight;

            if (imgRatio > containerRatio) {
                canvas.width = maxWidth;
                canvas.height = maxWidth / imgRatio;
            } else {
                canvas.height = maxHeight;
                canvas.width = maxHeight * imgRatio;
            }
        }

        window.addEventListener('resize', () => {
            if (baseImage) {
                fitCanvasToImage();
                draw();
            }
        });

        // 2. Interaction
        canvas.addEventListener('mousedown', (e) => {
            if (!baseImage) return;

            // If shape is already closed, reset it on new click? 
            if (isClosed) {
                if (confirm("Start a new selection area?")) {
                    points = [];
                    isClosed = false;
                    step3.classList.add('opacity-40', 'pointer-events-none');
                    setStatus('Drawing new area...', 'active');
                } else {
                    return;
                }
            }

            const rect = canvas.getBoundingClientRect();
            // Scale coordinates based on canvas display size vs internal resolution
            const scaleX = canvas.width / rect.width;
            const scaleY = canvas.height / rect.height;

            const x = (e.clientX - rect.left) * scaleX;
            const y = (e.clientY - rect.top) * scaleY;

            // Check closure
            if (points.length > 2) {
                const startX = points[0].x;
                const startY = points[0].y;
                const dx = x - startX;
                const dy = y - startY;

                // Distance check
                if (Math.sqrt(dx * dx + dy * dy) < 20 * scaleX) { // Close
                    isClosed = true;
                    // Enable Step 3
                    step3.classList.remove('opacity-40', 'pointer-events-none');
                    setStatus('Area selected! Choose a material.', 'success');
                    draw();
                    return;
                }
            }

            points.push({ x, y });
            draw();
        });

        undoBtn.addEventListener('click', () => {
            if (isClosed) {
                isClosed = false;
                step3.classList.add('opacity-40', 'pointer-events-none');
            }
            points.pop();
            draw();
        });

        clearBtn.addEventListener('click', () => {
            points = [];
            isClosed = false;
            currentMaterialImg = null;
            step3.classList.add('opacity-40', 'pointer-events-none');
            selectedMaterialName.classList.add('hidden');
            renderMaterials(); // Reset selected highlight
            setStatus('Drawing reset', 'active');
            draw();
        });

        // 3. Drawing
        function draw() {
            if (!baseImage) return;

            // Reset
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            // Draw Image
            ctx.drawImage(baseImage, 0, 0, canvas.width, canvas.height);

            // Overlay darkener if deciding area (optional, maybe too much?)
            // ctx.fillStyle = 'rgba(0,0,0,0.2)';
            // ctx.fillRect(0,0,canvas.width, canvas.height);

            if (points.length > 0) {
                // Path setup
                ctx.beginPath();
                ctx.moveTo(points[0].x, points[0].y);
                for (let i = 1; i < points.length; i++) {
                    ctx.lineTo(points[i].x, points[i].y);
                }

                if (isClosed) {
                    ctx.closePath();
                    // Draw Stroke - Updated to Deep Teal
                    ctx.strokeStyle = '#22819A';
                    ctx.lineWidth = 3;
                    ctx.stroke();

                    // Pattern Fill
                    if (currentMaterialImg) {
                        ctx.save();
                        // Clip to polygon
                        ctx.clip(); // Uses the current path

                        // Scale pattern if needed? simple repeat for now
                        const pattern = ctx.createPattern(currentMaterialImg, 'repeat');

                        ctx.fillStyle = pattern;
                        ctx.fillRect(0, 0, canvas.width, canvas.height);

                        // Blending for realism (Multiply preserves shadows!)
                        ctx.globalCompositeOperation = 'multiply';
                        ctx.drawImage(baseImage, 0, 0, canvas.width, canvas.height);

                        // Add some opacity to the pattern?
                        // Can't easily do opacity with pattern fill without another temp canvas, 
                        // but multiply usually works well enough.

                        ctx.restore();
                    } else {
                        // Highlight area waiting for material - Updated to Deep Teal with opacity
                        ctx.fillStyle = 'rgba(34, 129, 154, 0.2)';
                        ctx.fill();
                    }

                } else {
                    // Open path - Updated to Deep Teal
                    ctx.strokeStyle = '#22819A';
                    ctx.lineWidth = 2;
                    ctx.setLineDash([5, 5]);
                    ctx.stroke();
                    ctx.setLineDash([]);
                }

                // Points
                points.forEach((p, i) => {
                    // Check if this is the start point and we can close
                    if (i === 0 && points.length > 2 && !isClosed) {
                        ctx.beginPath();
                        ctx.arc(p.x, p.y, 8, 0, Math.PI * 2);
                        ctx.fillStyle = 'rgba(34, 129, 154, 0.5)';
                        ctx.fill();
                        ctx.strokeStyle = '#fff';
                        ctx.stroke();
                    }

                    ctx.beginPath();
                    ctx.arc(p.x, p.y, 4, 0, Math.PI * 2);
                    ctx.fillStyle = '#fff';
                    ctx.fill();
                });
            }
        }

        // Helper: Status
        function setStatus(text, type) {
            statusText.textContent = text;
            statusInd.className = `w-1.5 h-1.5 rounded-full transition-colors ${type === 'success' ? 'bg-green-500 animate-pulse' : 'bg-gradient-primary border-none shadow-[var(--shadow-primary)]'}`;
        }

        // Helper: Download
        downloadBtn.addEventListener('click', () => {
            const link = document.createElement('a');
            link.download = 'interior-design-view.png';
            link.href = canvas.toDataURL();
            link.click();
        });
    });
</script>

<?php include 'includes/footer.php'; ?>