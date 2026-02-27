<?php include 'includes/header.php'; ?>

<!-- Materials Hero -->
<div class="relative bg-gradient-primary border-none shadow-[var(--shadow-primary)] pt-32 pb-32 overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&w=1920&q=80"
            alt="Materials Background" class="w-full h-full object-cover opacity-20 mixing-blend-overlay">
        <div class="absolute inset-0 bg-gradient-to-b from-primary/95 via-primary/90 to-primary"></div>
        <div
            class="absolute top-0 right-0 w-96 h-96 bg-secondary/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2">
        </div>
        <div
            class="absolute bottom-0 left-0 w-96 h-96 bg-secondary/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2">
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center" data-aos="fade-up">
        <span class="text-[#FF1493] font-bold tracking-wider uppercase text-sm mb-4 block">The Collection</span>
        <h1 class="font-serif text-4xl md:text-5xl lg:text-6xl text-gradient mb-1 font-bold mb-6">Premium Materials</h1>
        <div class="h-1 w-20 bg-secondary mx-auto mb-6"></div>
        <p class="text-white/80 max-w-2xl mx-auto text-lg font-light leading-relaxed">Explore our curated library of
            high-end textures, surfaces, and finishes designed to bring your architectural vision to life.</p>
    </div>
</div>

<div class="pb-24 bg-white/5 min-h-screen relative -mt-16 z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Search & Filters Container -->
        <div class="glass-panel-dark rounded-3xl p-6 shadow-2xl shadow-primary/5 border border-white/10/50 mb-12"
            data-aos="fade-up" data-aos-delay="100">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <!-- Search Bar -->
                <div class="relative w-full md:w-2/5">
                    <input type="text" id="searchInput" placeholder="Search materials by name..."
                        class="w-full bg-white/5 text-white border-none rounded-full py-4 px-6 pl-14 focus:ring-2 focus:ring-[#FF1493]/50 focus:glass-panel-dark outline-none transition-all shadow-inner">
                    <i class="bi bi-search absolute left-6 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                </div>

                <!-- Categories -->
                <div class="flex flex-wrap justify-center gap-3 w-full md:w-auto">
                    <button
                        class="filter-btn active px-6 py-3 rounded-full border border-primary bg-gradient-primary border-none shadow-[var(--shadow-primary)] text-white font-medium transition-all shadow-lg shadow-primary/20 flex items-center gap-2"
                        data-filter="all"><i class="bi bi-grid-fill"></i> All</button>
                    <button
                        class="filter-btn px-6 py-3 rounded-full border border-white/10 glass-panel-dark text-gray-400 hover:border-primary hover:text-primary transition-all flex items-center gap-2"
                        data-filter="wall"><i class="bi bi-intersect"></i> Walls</button>
                    <button
                        class="filter-btn px-6 py-3 rounded-full border border-white/10 glass-panel-dark text-gray-400 hover:border-primary hover:text-primary transition-all flex items-center gap-2"
                        data-filter="floor"><i class="bi bi-grid-3x3-gap-fill"></i> Floors</button>
                    <button
                        class="filter-btn px-6 py-3 rounded-full border border-white/10 glass-panel-dark text-gray-400 hover:border-primary hover:text-primary transition-all flex items-center gap-2"
                        data-filter="ceiling"><i class="bi bi-cloud-fill"></i> Ceilings</button>
                </div>
            </div>
        </div>

        <!-- Material count header -->
        <div class="flex justify-between items-end mb-8 px-2" data-aos="fade-up" data-aos-delay="150">
            <div>
                <h2 class="font-serif text-2xl font-bold text-gradient mb-1">Material Catalog</h2>
                <p class="text-sm text-gray-400 mt-1" id="results-count">Loading materials...</p>
            </div>
        </div>

        <!-- Material Grid -->
        <div id="material-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <!-- Items loaded via JS -->
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const grid = document.getElementById('material-grid');
        const bts = document.querySelectorAll('.filter-btn');
        const searchInput = document.getElementById('searchInput');
        const resultsCount = document.getElementById('results-count');

        let currentCategory = 'all';

        function loadMaterials(cat = 'all', search = '') {
            grid.innerHTML = `
                <div class="col-span-full py-24 flex flex-col items-center justify-center text-gray-400">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mb-4"></div>
                    <p class="font-medium animate-pulse">Curating premium materials...</p>
                </div>
            `;
            resultsCount.innerText = 'Searching catalogue...';

            fetch(`/api/get_materials.php?category=${cat}&search=${search}`)
                .then(res => res.json())
                .then(data => {
                    grid.innerHTML = '';

                    if (!data || data.length === 0) {
                        grid.innerHTML = `
                        <div class="col-span-full py-20 flex flex-col items-center justify-center text-center glass-panel-dark rounded-3xl border border-dashed border-white/10">
                            <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center text-primary shadow-inner mb-6">
                                <i class="bi bi-search text-3xl"></i>
                            </div>
                            <h3 class="font-serif text-2xl font-bold text-gradient mb-1 mb-2">No materials found</h3>
                            <p class="text-gray-400 max-w-md">We couldn't find any materials matching your criteria. Try adjusting your filters or search term.</p>
                            <button onclick="document.querySelector('[data-filter=all]').click()" class="mt-6 text-primary font-bold hover:text-[#FF1493] transition-colors underline decoration-2 underline-offset-4">Reset Filters</button>
                        </div>`;
                        resultsCount.innerText = '0 materials available';
                        return;
                    }

                    resultsCount.innerHTML = `<span class="text-primary font-bold">${data.length}</span> Premium Material${data.length !== 1 ? 's' : ''}`;

                    data.forEach((item, i) => {
                        const delay = (i % 4) * 100;
                        const card = `
                        <div class="group glass-panel-dark rounded-[1.5rem] overflow-hidden border border-white/10 hover:border-primary/30 hover:shadow-[0_20px_40px_rgba(30,53,95,0.08)] transition-all duration-500 flex flex-col h-full" data-aos="fade-up" data-aos-delay="${delay}">
                            <div class="relative h-64 overflow-hidden flex-shrink-0">
                                <img src="${item.image_url}" alt="${item.name}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                
                                <!-- Hover Overlay Options -->
                                <div class="absolute inset-0 bg-gradient-primary border-none shadow-[var(--shadow-primary)]/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-4 backdrop-blur-sm z-10">
                                    <a href="/visualizer?material=${item.id}" class="w-12 h-12 glass-panel-dark rounded-full text-primary hover:bg-secondary hover:text-white hover:scale-110 transition-all shadow-xl flex items-center justify-center transform translate-y-4 group-hover:translate-y-0 duration-300" title="Try in Visualizer"><i class="bi bi-eye text-xl"></i></a>
                                    <button onclick="toggleWishlist(${item.id})" class="w-12 h-12 glass-panel-dark rounded-full text-primary hover:bg-red-500 hover:text-white hover:scale-110 transition-all shadow-xl flex items-center justify-center transform translate-y-4 group-hover:translate-y-0 duration-300 delay-75" title="Add to Wishlist"><i class="bi bi-heart text-xl"></i></button>
                                </div>
                                
                                <!-- Tags -->
                                <div class="absolute top-4 left-4 z-0 group-hover:z-20 transition-all">
                                    <span class="glass-panel-dark/95 backdrop-blur-md px-3 py-1.5 rounded-xl text-[10px] text-primary font-bold uppercase tracking-wider shadow-sm border border-white inline-flex items-center gap-1 backdrop-saturate-150"><i class="bi bi-stars text-[#FF1493]"></i> ${item.category_name}</span>
                                </div>
                            </div>
                            
                            <div class="p-6 flex flex-col flex-grow relative">
                                <h3 class="text-lg font-bold text-gradient mb-1 mb-2 group-hover:text-primary transition-colors line-clamp-1">${item.name}</h3>
                                <p class="text-gray-400 text-sm leading-relaxed mb-6 line-clamp-2 flex-grow">${item.description || 'Superior finish material curated for high-end interior spaces.'}</p>
                                
                                <div class="flex items-center justify-between border-t border-white/10/60 pt-5 mt-auto">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] uppercase tracking-wider text-gray-400 font-bold mb-1">Pricing</span>
                                        <span class="text-white font-bold text-xl">₹${item.price_per_sqft}<span class="text-sm font-normal text-gray-400"> / sq.ft</span></span>
                                    </div>
                                    <a href="/material-details?id=${item.id}" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-primary group-hover:bg-gradient-primary border-none shadow-[var(--shadow-primary)] group-hover:text-white transition-colors"><i class="bi bi-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        `;
                        grid.innerHTML += card;
                    });
                })
                .catch(err => {
                    grid.innerHTML = `
                    <div class="col-span-full text-center py-10 bg-red-500/20 rounded-2xl border border-red-100">
                        <i class="bi bi-exclamation-triangle text-3xl text-red-500 mb-2 block"></i>
                        <p class="text-red-700 font-medium">Error loading catalog. Please try again later.</p>
                    </div>`;
                    resultsCount.innerText = 'Error loading results';
                });
        }

        // Initial Load
        loadMaterials();

        // Search Filter Click Logic
        bts.forEach(btn => {
            btn.addEventListener('click', () => {
                // Reset all
                bts.forEach(b => {
                    b.classList.remove('bg-gradient-primary border-none shadow-[var(--shadow-primary)]', 'text-white', 'border-primary', 'shadow-lg', 'shadow-primary/20');
                    b.classList.add('glass-panel-dark', 'text-gray-400', 'border-white/10');
                });
                // Activate clicked
                btn.classList.remove('glass-panel-dark', 'text-gray-400', 'border-white/10');
                btn.classList.add('bg-gradient-primary border-none shadow-[var(--shadow-primary)]', 'text-white', 'border-primary', 'shadow-lg', 'shadow-primary/20');

                currentCategory = btn.dataset.filter;
                loadMaterials(currentCategory, searchInput.value);
            });
        });

        // Search Listener with Debounce
        let timeout = null;
        searchInput.addEventListener('input', (e) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                loadMaterials(currentCategory, e.target.value);
            }, 300);
        });
    });

    function toggleWishlist(id) {
        let wishes = JSON.parse(localStorage.getItem('wishlist') || '[]');
        if (wishes.includes(id)) {
            wishes = wishes.filter(w => w !== id);
            alert('Removed from wishlist');
        } else {
            wishes.push(id);
            alert('Added to wishlist');
        }
        localStorage.setItem('wishlist', JSON.stringify(wishes));
        updateWishlistCount();
    }

    function updateWishlistCount() {
        const wishes = JSON.parse(localStorage.getItem('wishlist') || '[]');
        const counter = document.getElementById('wishlist-count');
        if (counter) counter.innerText = wishes.length;
    }

    // Run on load
    updateWishlistCount();
</script>

<?php include 'includes/footer.php'; ?>