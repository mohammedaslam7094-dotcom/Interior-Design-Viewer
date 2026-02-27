<?php include 'includes/header.php'; ?>

<!-- Wishlist Hero -->
<div class="relative bg-gradient-primary border-none shadow-[var(--shadow-primary)] pt-32 pb-24 overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=1920&q=80"
            alt="Wishlist Background" class="w-full h-full object-cover opacity-10 mixing-blend-overlay">
        <div class="absolute inset-0 bg-gradient-to-b from-primary/95 via-primary/95 to-primary"></div>
        <div
            class="absolute top-0 left-0 w-96 h-96 bg-secondary/10 rounded-full blur-3xl -translate-y-1/2 -translate-x-1/2">
        </div>
        <div
            class="absolute bottom-0 right-0 w-96 h-96 bg-secondary/10 rounded-full blur-3xl translate-y-1/2 translate-x-1/2">
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center" data-aos="fade-up">
        <div
            class="w-16 h-16 bg-secondary/20 rounded-2xl flex items-center justify-center mx-auto mb-6 backdrop-blur-sm border border-[#FF1493]/30">
            <i class="bi bi-heart-fill text-3xl text-[#FF1493] animate-pulse"></i>
        </div>
        <h1 class="font-serif text-4xl md:text-5xl lg:text-5xl text-gradient mb-1 font-bold mb-4">Your Curated Collection</h1>
        <div class="h-1 w-20 bg-secondary mx-auto mb-6"></div>
        <p class="text-white/80 max-w-2xl mx-auto text-lg font-light leading-relaxed mb-4">Every detail matters. Here is
            a mood board of your saved materials, ready to visualize or quote.</p>
        <div
            class="inline-flex items-center gap-2 glass-panel-dark/10 backdrop-blur px-4 py-2 rounded-full border border-white/20">
            <span class="w-2 h-2 rounded-full bg-secondary"></span>
            <span class="text-white text-sm font-medium"><span id="wishlist-hero-count">0</span> Saved Materials</span>
        </div>
    </div>
</div>

<div class="pb-24 bg-white/5 min-h-screen relative -mt-8 z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div id="wishlist-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 pt-8">
            <div class="col-span-full py-20 flex flex-col items-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mb-4"></div>
                <p class="text-gray-400 font-medium animate-pulse">Loading your favorites...</p>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const list = JSON.parse(localStorage.getItem('wishlist') || '[]');
        const grid = document.getElementById('wishlist-grid');
        const heroCount = document.getElementById('wishlist-hero-count');

        if (heroCount) heroCount.innerText = list.length;

        if (list.length === 0) {
            renderEmptyState(grid);
            return;
        }

        // Fetch all materials (not efficient for large DB but fine for college/demo)
        fetch('/api/get_materials.php?category=all')
            .then(res => res.json())
            .then(allMaterials => {
                const wishes = allMaterials.filter(m => list.includes(String(m.id)) || list.includes(Number(m.id)));

                if (wishes.length === 0) {
                    renderEmptyState(grid);
                    return;
                }

                if (heroCount) heroCount.innerText = wishes.length;
                grid.innerHTML = '';

                wishes.forEach((item, i) => {
                    const delay = (i % 4) * 100;
                    grid.innerHTML += `
                    <div id="wishlist-item-${item.id}" class="group glass-panel-dark rounded-3xl overflow-hidden border border-white/10 shadow-lg shadow-primary/5 hover:shadow-2xl hover:shadow-secondary/10 transition-all duration-500 transform hover:-translate-y-1 flex flex-col h-full relative" data-aos="fade-up" data-aos-delay="${delay}">
                         <div class="relative h-56 overflow-hidden flex-shrink-0">
                            <img src="${item.image_url}" alt="${item.name}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-dark/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <!-- Remove Button -->
                            <button onclick="removeFromWishlist(${item.id})" class="absolute top-4 right-4 w-10 h-10 glass-panel-dark/95 backdrop-blur shadow-md hover:bg-red-500 rounded-full text-red-500 hover:text-white border border-white/5 hover:border-red-500 transition-all flex items-center justify-center transform hover:scale-110 z-20 group-hover:-translate-y-1" title="Remove">
                                <i class="bi bi-trash"></i>
                            </button>
                            
                            <!-- Tag -->
                            <div class="absolute bottom-4 left-4 transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 z-20">
                                <span class="bg-gradient-primary border-none shadow-[var(--shadow-primary)]/90 text-white backdrop-blur px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider border border-white/20">${item.category_name || 'Material'}</span>
                            </div>
                        </div>
                        
                        <div class="p-6 flex flex-col flex-grow relative glass-panel-dark">
                            <h3 class="font-bold text-gradient mb-1 mb-2 text-lg group-hover:text-primary transition-colors flex-grow"><a href="/material-details?id=${item.id}" class="hover:text-primary transition-colors line-clamp-2">${item.name}</a></h3>
                            
                            <div class="flex items-center justify-between border-t border-white/10/60 pt-4 mt-4">
                                <div class="flex flex-col">
                                    <span class="text-[10px] uppercase tracking-wider text-gray-400 font-bold mb-1">Pricing</span>
                                    <span class="text-primary font-bold text-lg">₹${item.price_per_sqft}<span class="text-xs text-gray-400 font-normal"> / sq.ft</span></span>
                                </div>
                                <a href="/visualizer?material=${item.id}" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-primary group-hover:bg-gradient-primary border-none shadow-[var(--shadow-primary)] group-hover:text-white transition-colors" title="Try in Visualizer"><i class="bi bi-eye"></i></a>
                            </div>
                        </div>
                    </div>
                `;
                });
            })
            .catch(err => {
                grid.innerHTML = `
                    <div class="col-span-full py-20 flex flex-col items-center justify-center text-center glass-panel-dark rounded-3xl border border-dashed border-red-500/30 shadow-sm mt-8">
                        <div class="w-20 h-20 bg-red-500/20 rounded-full flex items-center justify-center text-red-500 shadow-inner mb-6">
                            <i class="bi bi-exclamation-triangle text-3xl"></i>
                        </div>
                        <h3 class="font-serif text-2xl font-bold text-gradient mb-1 mb-2">Error Loading Wishlist</h3>
                        <p class="text-gray-400 max-w-md">We couldn't retrieve your saved items. Please refresh the page or try again later.</p>
                    </div>
                `;
            });
    });

    function renderEmptyState(grid) {
        grid.innerHTML = `
            <div class="col-span-full py-24 flex flex-col items-center justify-center text-center glass-panel-dark rounded-[2rem] border border-dashed border-white/10 shadow-[0_20px_40px_rgba(30,53,95,0.03)] mt-8" data-aos="fade-up">
                <div class="w-24 h-24 bg-white/5 rounded-[2rem] flex items-center justify-center text-primary/50 shadow-inner mb-8 transform -rotate-12 border border-white/10/50">
                    <i class="bi bi-heart text-5xl"></i>
                </div>
                <h3 class="font-serif text-3xl font-bold text-gradient mb-1 mb-3">Your wishlist is empty</h3>
                <p class="text-gray-400 max-w-md mb-8 text-lg">Start building your dream space by exploring our curated selection of premium materials.</p>
                <a href="/materials" class="btn-premium px-8 py-4 rounded-xl font-bold inline-flex items-center gap-2 transform transition-all hover:-translate-y-1 hover:shadow-xl hover:shadow-secondary/20">
                    Explore Materials <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        `;
    }

    window.removeFromWishlist = function (id) {
        // Remove visually instantly for better UX
        const itemCard = document.getElementById('wishlist-item-' + id);
        if (itemCard) {
            itemCard.style.opacity = '0';
            itemCard.style.transform = 'scale(0.9)';
            setTimeout(() => {
                itemCard.remove();

                // Update Local Storage
                let wishes = JSON.parse(localStorage.getItem('wishlist') || '[]');
                wishes = wishes.filter(w => String(w) !== String(id));
                localStorage.setItem('wishlist', JSON.stringify(wishes));

                // Update Badge Counts
                const grid = document.getElementById('wishlist-grid');
                const heroCount = document.getElementById('wishlist-hero-count');
                const navCount = document.getElementById('wishlist-count');

                if (heroCount) heroCount.innerText = wishes.length;
                if (navCount) navCount.innerText = wishes.length;

                // Show empty state if needed
                if (wishes.length === 0) {
                    renderEmptyState(grid);
                }
            }, 300);
        }
    };
</script>

<?php include 'includes/footer.php'; ?>