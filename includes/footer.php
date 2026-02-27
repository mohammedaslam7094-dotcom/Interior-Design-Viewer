</div> <!-- End Spacer -->

<footer class="bg-primary border-t border-white/10 pt-12 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <div class="col-span-1 md:col-span-1">
                <h2 class="font-serif text-2xl font-bold text-secondary mb-4">IDV.</h2>
                <p class="text-white/70 text-sm leading-relaxed">
                    Visualizing your dream space before you build. Premium interior design tools for the modern age.
                </p>
            </div>
            <div>
                <h3 class="text-white font-semibold mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="/" class="text-white/70 hover:text-secondary text-sm transition-colors">Home</a></li>
                    <li><a href="/materials"
                            class="text-white/70 hover:text-secondary text-sm transition-colors">Materials</a></li>
                    <li><a href="/visualizer"
                            class="text-white/70 hover:text-secondary text-sm transition-colors">Visualizer</a></li>
                    <li><a href="/about" class="text-white/70 hover:text-secondary text-sm transition-colors">About
                            Us</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-white font-semibold mb-4">Support</h3>
                <ul class="space-y-2">
                    <li><a href="/contact"
                            class="text-white/70 hover:text-secondary text-sm transition-colors">Contact</a></li>
                    <li><a href="/faq" class="text-white/70 hover:text-secondary text-sm transition-colors">FAQs</a>
                    </li>
                    <li><a href="/terms" class="text-white/70 hover:text-secondary text-sm transition-colors">Terms
                            of Service</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-white font-semibold mb-4">Connect</h3>
                <div class="flex space-x-4">
                    <a href="#" class="text-white/70 hover:text-secondary transition-colors"><i
                            class="bi bi-instagram text-xl"></i></a>
                    <a href="#" class="text-white/70 hover:text-secondary transition-colors"><i
                            class="bi bi-twitter-x text-xl"></i></a>
                    <a href="#" class="text-white/70 hover:text-secondary transition-colors"><i
                            class="bi bi-facebook text-xl"></i></a>
                </div>
            </div>
        </div>
        <div class="border-t border-white/10 pt-8 text-center">
            <p class="text-white/60 text-sm">&copy;
                <?php echo date("Y"); ?> Interior Design Viewer. All rights reserved.
            </p>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script>
    // Initialize Lucide Icons
    // lucide.createIcons(); // Removed for Bootstrap Icons

    // Initialize AOS
    AOS.init({
        duration: 800,
        once: true,
        offset: 100
    });
</script>
</body>

</html>