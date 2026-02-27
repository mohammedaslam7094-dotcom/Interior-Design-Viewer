<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<div class="relative min-h-[90vh] flex items-center justify-center overflow-hidden bg-canvas">
    <!-- Dark Background Glows -->
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <!-- Abstract Glowing Orbs -->
        <div
            class="absolute top-[20%] left-[10%] w-[30rem] h-[30rem] bg-[#FF1493] rounded-full mix-blend-screen filter blur-[150px] opacity-20">
        </div>
        <div
            class="absolute bottom-[10%] right-[10%] w-[30rem] h-[30rem] bg-[#FF5E3A] rounded-full mix-blend-screen filter blur-[150px] opacity-10">
        </div>
    </div>

    <!-- Hero Content -->
    <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 flex flex-col md:flex-row items-center justify-between gap-12"
        data-aos="fade-up" data-aos-duration="1000">

        <!-- Text Area (Left) -->
        <div class="w-full md:w-1/2 text-left">
            <h1 class="font-sans text-5xl md:text-7xl font-light text-white mb-6 leading-[1.1] tracking-tight">
                Discover the<br>
                <div class="relative inline-block w-full max-w-[480px]">
                    <span
                        class="text-gradient font-bold italic block border-b border-white/20 pb-1 mb-1 relative overflow-visible">
                        beauty of the
                        <!-- Decorative Star -->
                        <svg class="absolute -top-6 -right-8 w-14 h-14 text-[#FF1493] animate-pulse" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 2v20m-10-10h20m-14.14-7.07l14.14 14.14m-14.14 0l14.14-14.14" />
                        </svg>
                    </span>
                </div>
                metaverse world
            </h1>
            <p class="text-gray-300 text-base md:text-lg mb-10 max-w-xl font-light leading-relaxed">
                Experience your future home before you build. Premium interior visualization with instant pricing and
                material customization. Step into the next generation of design.
            </p>
            <div class="flex flex-col sm:flex-row items-start gap-4">
                <a href="/visualizer"
                    class="bg-gradient-premium text-white px-10 py-4 font-bold text-lg tracking-wide rounded-sm shadow-xl hover:shadow-[var(--shadow-premium)] hover:-translate-y-1 transition-all min-w-[180px] text-center">
                    Read More!
                </a>
                <a href="/materials"
                    class="bg-gradient-primary text-white px-10 py-4 font-bold text-lg tracking-wide rounded-sm shadow-xl hover:shadow-[var(--shadow-primary)] hover:-translate-y-1 transition-all min-w-[180px] text-center">
                    Play Now!
                </a>
            </div>

            <div class="mt-16 flex items-center gap-6">
                <div
                    class="w-12 h-12 border-2 border-white/30 rounded-full flex items-center justify-center hover:bg-white/10 cursor-pointer transition-colors hover:border-white">
                    <i class="bi bi-arrow-right text-white text-xl"></i>
                </div>
                <div class="flex gap-3">
                    <span class="w-3 h-3 rounded-full bg-[#FF1493] shadow-[0_0_10px_#FF1493]"></span>
                    <span class="w-3 h-3 rounded-full bg-[#FF5E3A] shadow-[0_0_10px_#FF5E3A]"></span>
                    <span class="w-3 h-3 rounded-full bg-white/20"></span>
                </div>
            </div>
        </div>

        <!-- Image Area (Right) -->
        <div class="w-full md:w-1/2 relative flex justify-center mt-16 md:mt-0">
            <!-- Circular Pink/Purple Backdrop -->
            <div
                class="absolute w-[20rem] h-[20rem] md:w-[32rem] md:h-[32rem] rounded-full bg-gradient-to-tr from-[#4A00E0] via-[#8E2DE2] to-[#F50057] top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 -z-10 shadow-2xl shadow-[#FF1493]/30">
            </div>

            <!-- Orbit Rings -->
            <div
                class="absolute w-[120%] h-[40%] border border-white/60 rounded-[100%] top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 -rotate-[25deg] pointer-events-none mix-blend-screen shadow-[0_0_15px_rgba(255,255,255,0.4)]">
                <svg class="absolute -bottom-4 left-1/4 w-8 h-8 text-white animate-pulse filter drop-shadow-[0_0_5px_white]"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 2v20m-10-10h20m-14.14-7.07l14.14 14.14m-14.14 0l14.14-14.14" />
                </svg>
            </div>

            <img src="https://images.unsplash.com/photo-1622979135225-d2ba269cf1ac?auto=format&fit=crop&w=800&q=80"
                alt="VR Meta User"
                class="relative z-10 w-[90%] md:w-full max-w-[450px] h-[550px] object-cover rounded-3xl shadow-[0_30px_60px_rgba(0,0,0,0.8)] border border-white/10 hover:scale-[1.02] transition-transform duration-500">
        </div>
    </div>
</div>

<!-- Features Grid -->
<section class="py-20 bg-canvas">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="font-serif text-3xl md:text-4xl text-dark mb-4">Why Choose Us?</h2>
            <div class="h-1 w-20 bg-secondary mx-auto"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-white p-8 rounded-2xl border border-structure hover:border-secondary transition-all group shadow-sm hover:shadow-md"
                data-aos="fade-up" data-aos-delay="100">
                <div
                    class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center mb-6 group-hover:bg-primary transition-colors">
                    <i class="bi bi-layers-fill text-2xl text-primary group-hover:text-white transition-colors"></i>
                </div>
                <h3 class="text-xl font-bold text-dark mb-3">Material Library</h3>
                <p class="text-muted">Explore hundreds of premium wall, floor, and ceiling materials with real-time
                    textures.</p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-white p-8 rounded-2xl border border-structure hover:border-secondary transition-all group shadow-sm hover:shadow-md"
                data-aos="fade-up" data-aos-delay="200">
                <div
                    class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center mb-6 group-hover:bg-primary transition-colors">
                    <i class="bi bi-display text-2xl text-primary group-hover:text-white transition-colors"></i>
                </div>
                <h3 class="text-xl font-bold text-dark mb-3">Live Visualizer</h3>
                <p class="text-muted">Upload your room photo and apply materials instantly to see how they look in
                    your space.</p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-white p-8 rounded-2xl border border-structure hover:border-secondary transition-all group shadow-sm hover:shadow-md"
                data-aos="fade-up" data-aos-delay="300">
                <div
                    class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center mb-6 group-hover:bg-primary transition-colors">
                    <i
                        class="bi bi-file-earmark-text text-2xl text-primary group-hover:text-white transition-colors"></i>
                </div>
                <h3 class="text-xl font-bold text-dark mb-3">Instant Quotes</h3>
                <p class="text-muted">Get accurate pricing estimates based on your room dimensions and selected
                    premium materials.</p>
            </div>
        </div>
    </div>
</section>

<!-- Trending Materials Section -->
<section class="py-20 bg-[#F8FAFC] border-y border-structure/50 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12" data-aos="fade-up">
            <div class="max-w-2xl">
                <span class="text-gradient font-bold tracking-wider uppercase text-sm mb-2 block">Our Collection</span>
                <h2 class="font-serif text-3xl md:text-5xl text-dark mb-4">Trending Materials</h2>
                <div class="h-1 w-20 bg-primary mb-6"></div>
                <p class="text-muted text-lg">Browse our hand-picked selection of premium textures and finishes to
                    elevate your design.</p>
            </div>
            <div class="hidden md:block mb-2">
                <a href="/materials"
                    class="group flex items-center gap-2 text-primary font-semibold hover:text-secondary transition-colors">
                    Explore Library <i
                        class="bi bi-arrow-right transform group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>

        <!-- Material Grid -->
        <div id="home-material-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 relative z-10">
            <!-- Loading State -->
            <div class="col-span-1 sm:col-span-2 lg:col-span-4 text-center text-muted py-10">
                <div class="animate-pulse space-y-4">
                    <div class="h-12 w-12 bg-structure rounded-full mx-auto"></div>
                    <div class="text-sm">Curating premium materials...</div>
                </div>
            </div>
        </div>

        <div class="mt-10 text-center md:hidden" data-aos="fade-up">
            <a href="/materials" class="btn-premium px-8 py-3 rounded-full font-bold inline-flex items-center gap-2">
                Explore Library <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const homeGrid = document.getElementById('home-material-grid');
        if (homeGrid) {
            fetch('/api/get_materials.php?category=all')
                .then(res => res.json())
                .then(data => {
                    homeGrid.innerHTML = '';
                    if (!data || data.length === 0) {
                        homeGrid.innerHTML = '<div class="col-span-1 sm:col-span-2 lg:col-span-4 text-center text-muted py-10">No materials available.</div>';
                        return;
                    }

                    // Take only first 4 items
                    const featured = data.slice(0, 4);

                    featured.forEach((item, i) => {
                        const delay = i * 100;
                        const card = `
                        <div class="group bg-white rounded-2xl overflow-hidden border border-structure hover:border-secondary hover:shadow-xl transition-all duration-500 shadow-sm" data-aos="fade-up" data-aos-delay="${delay}">
                            <div class="relative h-56 overflow-hidden">
                                <img src="${item.image_url}" alt="${item.name}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                <div class="absolute inset-0 bg-primary/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-4 backdrop-blur-[2px]">
                                    <a href="/visualizer?material=${item.id}" class="p-3 bg-white rounded-full text-primary hover:bg-secondary hover:text-white transition-colors shadow-lg" title="Try in Visualizer"><i class="bi bi-eye"></i></a>
                                </div>
                                <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-lg text-xs text-primary font-bold uppercase tracking-wider shadow-sm border border-white">${item.category_name}</div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-dark mb-1 group-hover:text-primary transition-colors truncate">${item.name}</h3>
                                <div class="flex items-center justify-between border-t border-structure/50 pt-4 mt-4">
                                    <span class="text-primary font-bold text-lg">₹${item.price_per_sqft}<span class="text-xs text-muted font-normal"> / sq.ft</span></span>
                                    <a href="/material-details?id=${item.id}" class="text-sm text-primary font-medium hover:text-secondary flex items-center gap-1 transition-colors">Details <i class="bi bi-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        `;
                        homeGrid.innerHTML += card;
                    });
                })
                .catch(err => {
                    homeGrid.innerHTML = '<div class="col-span-1 sm:col-span-2 lg:col-span-4 text-center text-red-500 py-10">Error loading materials.</div>';
                });
        }
    });
</script>

<!-- More to Explore Section -->
<section class="py-20 bg-white relative overflow-hidden">
    <!-- Decorative background elements -->
    <div
        class="absolute top-0 right-0 w-64 h-64 bg-secondary/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2">
    </div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-primary/5 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2">
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row justify-between items-end mb-16" data-aos="fade-up">
            <div class="max-w-2xl">
                <span class="text-gradient font-bold tracking-wider uppercase text-sm mb-2 block">Discover More</span>
                <h2 class="font-serif text-3xl md:text-5xl text-dark mb-4">Elevate Your Design Journey</h2>
                <div class="h-1 w-20 bg-primary mb-6"></div>
                <p class="text-muted text-lg">Beyond visualization, we provide the tools you need to bring your dream
                    space to life, from budgeting to expert advice.</p>
            </div>
            <div class="hidden md:block">
                <a href="/materials"
                    class="group flex items-center gap-2 text-primary font-semibold hover:text-secondary transition-colors">
                    View All Features <i
                        class="bi bi-arrow-right transform group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Card 1: Budget Calculator -->
            <div class="group bg-canvas rounded-3xl overflow-hidden border border-structure hover:border-secondary/50 transition-all duration-500 hover:shadow-2xl hover:shadow-secondary/20"
                data-aos="fade-up" data-aos-delay="100">
                <div class="h-64 overflow-hidden relative">
                    <div
                        class="absolute inset-0 bg-primary/20 group-hover:bg-transparent transition-colors duration-500 z-10">
                    </div>
                    <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=800&q=80"
                        alt="Budget Planning"
                        class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                    <div
                        class="absolute top-4 right-4 z-20 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-primary shadow-sm">
                        Smart Tool</div>
                </div>
                <div class="p-8 relative bg-white">
                    <div
                        class="w-16 h-16 bg-gradient-premium rounded-2xl flex items-center justify-center absolute -top-8 right-8 shadow-[var(--shadow-premium)] transform group-hover:-translate-y-2 transition-transform duration-500">
                        <i class="bi bi-calculator text-2xl text-secondary"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-dark mb-3">Project Budgeting</h3>
                    <p class="text-muted mb-6 line-clamp-2">Calculate your interior design costs instantly. Get detailed
                        breakdowns based on room dimensions and materials.</p>
                    <a href="/budget"
                        class="inline-flex items-center text-primary font-bold group-hover:text-secondary transition-colors">
                        Calculate Now <i
                            class="bi bi-arrow-right ml-2 transform group-hover:translate-x-2 transition-transform"></i>
                    </a>
                </div>
            </div>

            <!-- Card 2: Wishlist & Inspiration -->
            <div class="group bg-canvas rounded-3xl overflow-hidden border border-structure hover:border-secondary/50 transition-all duration-500 hover:shadow-2xl hover:shadow-secondary/20"
                data-aos="fade-up" data-aos-delay="200">
                <div class="h-64 overflow-hidden relative">
                    <div
                        class="absolute inset-0 bg-primary/20 group-hover:bg-transparent transition-colors duration-500 z-10">
                    </div>
                    <img src="https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=800&q=80"
                        alt="Wishlist"
                        class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                    <div
                        class="absolute top-4 right-4 z-20 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-primary shadow-sm">
                        Personal</div>
                </div>
                <div class="p-8 relative bg-white">
                    <div
                        class="w-16 h-16 bg-gradient-premium rounded-2xl flex items-center justify-center absolute -top-8 right-8 shadow-[var(--shadow-premium)] transform group-hover:-translate-y-2 transition-transform duration-500">
                        <i class="bi bi-heart text-2xl text-secondary"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-dark mb-3">Curated Wishlist</h3>
                    <p class="text-muted mb-6 line-clamp-2">Save your favorite textures, colors, and setups. Build a
                        personalized mood board to guide your transformation.</p>
                    <a href="/wishlist"
                        class="inline-flex items-center text-primary font-bold group-hover:text-secondary transition-colors">
                        View Favorites <i
                            class="bi bi-arrow-right ml-2 transform group-hover:translate-x-2 transition-transform"></i>
                    </a>
                </div>
            </div>

            <!-- Card 3: Expert Consultation -->
            <div class="group bg-canvas rounded-3xl overflow-hidden border border-structure hover:border-secondary/50 transition-all duration-500 hover:shadow-2xl hover:shadow-secondary/20 md:last:col-span-2 lg:last:col-span-1"
                data-aos="fade-up" data-aos-delay="300">
                <div class="h-64 overflow-hidden relative">
                    <div
                        class="absolute inset-0 bg-primary/20 group-hover:bg-transparent transition-colors duration-500 z-10">
                    </div>
                    <img src="https://images.unsplash.com/photo-1600607686527-6fb886090705?auto=format&fit=crop&w=800&q=80"
                        alt="Expert Advice"
                        class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 object-top">
                    <div
                        class="absolute top-4 right-4 z-20 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-primary shadow-sm">
                        Support</div>
                </div>
                <div class="p-8 relative bg-white h-full">
                    <div
                        class="w-16 h-16 bg-gradient-premium rounded-2xl flex items-center justify-center absolute -top-8 right-8 shadow-[var(--shadow-premium)] transform group-hover:-translate-y-2 transition-transform duration-500">
                        <i class="bi bi-headset text-2xl text-secondary"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-dark mb-3">Expert Consultation</h3>
                    <p class="text-muted mb-6 line-clamp-2">Need a second opinion? Our interior specialists are ready to
                        help you perfect your vision and select materials.</p>
                    <a href="/contact"
                        class="inline-flex items-center text-primary font-bold group-hover:text-secondary transition-colors">
                        Get in Touch <i
                            class="bi bi-arrow-right ml-2 transform group-hover:translate-x-2 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile Link View All -->
        <div class="mt-10 text-center md:hidden" data-aos="fade-up">
            <a href="/materials" class="btn-premium px-8 py-3 rounded-full font-bold inline-flex items-center gap-2">
                Explore All <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-20 relative overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=1920&q=80"
            alt="Interior Detail" class="w-full h-full object-cover opacity-20">
        <div class="absolute inset-0 bg-gradient-to-r from-black via-black/90 to-transparent"></div>
    </div>

    <div
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 flex flex-col md:flex-row items-center justify-between">
        <div class="md:w-1/2 mb-8 md:mb-0" data-aos="fade-right">
            <h2 class="font-serif text-3xl md:text-5xl text-white font-bold mb-6">Ready to Transform Your Space?</h2>
            <p class="text-gray-400 text-lg mb-8">Join thousands of homeowners and designers who trust IDV for their
                renovation projects.</p>
            <a href="/visualizer"
                class="btn-premium px-8 py-4 rounded-full font-bold text-lg inline-flex items-center gap-2">
                Launch Visualizer <i class="bi bi-arrow-right text-xl"></i>
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>