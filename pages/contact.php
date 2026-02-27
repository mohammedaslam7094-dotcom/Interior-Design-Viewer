<?php include 'includes/header.php';
require_once 'includes/db.php';

$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO feedback (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        $msg = "<div class='bg-green-500/10 border border-green-500/20 text-green-400 p-4 rounded-xl mb-6 flex items-center gap-3 shadow-sm'><i class='bi bi-check-circle-fill text-xl'></i> Your message was sent successfully. We will get back to you shortly.</div>";
    } else {
        $msg = "<div class='bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-xl mb-6 flex items-center gap-3 shadow-sm'><i class='bi bi-exclamation-triangle-fill text-xl'></i> Error sending message. Please try again.</div>";
    }
}
?>

<!-- Contact Hero -->
<div class="relative bg-gradient-primary border-none shadow-[var(--shadow-primary)] pt-32 pb-40 overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1920&q=80"
            alt="Contact Us" class="w-full h-full object-cover opacity-10 mixing-blend-overlay">
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
            <i class="bi bi-envelope-paper text-3xl text-[#FF1493]"></i>
        </div>
        <h1 class="font-serif text-4xl md:text-5xl lg:text-6xl text-gradient mb-1 font-bold mb-6">Let's Connect</h1>
        <div class="h-1 w-20 bg-secondary mx-auto mb-6"></div>
        <p class="text-white/80 max-w-2xl mx-auto text-lg font-light leading-relaxed">Whether you have a question about
            our materials, need a custom quote, or want to discuss a large project, our expert team is here to help.</p>
    </div>
</div>

<div class="pb-24 bg-white/5 min-h-screen relative z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-24">

        <div
            class="grid grid-cols-1 lg:grid-cols-5 gap-12 glass-panel-dark rounded-[2.5rem] p-8 md:p-12 shadow-2xl shadow-primary/10 border border-white/10/50">

            <!-- Contact Info -->
            <div class="lg:col-span-2 space-y-10" data-aos="fade-right">
                <div>
                    <h2 class="font-serif text-3xl text-gradient mb-1 font-bold mb-4">Contact Information</h2>
                    <p class="text-gray-400 leading-relaxed">Fill out the form and our team will get back to you within 24
                        hours.</p>
                </div>

                <div class="space-y-8">
                    <div class="flex items-start gap-5 group">
                        <div
                            class="w-14 h-14 bg-gradient-primary border-none shadow-[var(--shadow-primary)] rounded-2xl flex items-center justify-center shadow-lg shadow-primary/20 flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <i class="bi bi-geo-alt-fill text-2xl text-[#FF1493]"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-lg mb-1 group-hover:text-primary transition-colors">Our
                                Headquarters</h4>
                            <p class="text-gray-400 leading-relaxed">123 Design Avenue,<br>Creative City, State 12345</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-5 group">
                        <div
                            class="w-14 h-14 bg-gradient-primary border-none shadow-[var(--shadow-primary)] rounded-2xl flex items-center justify-center shadow-lg shadow-primary/20 flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <i class="bi bi-envelope-fill text-2xl text-[#FF1493]"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-lg mb-1 group-hover:text-primary transition-colors">
                                Email Us</h4>
                            <a href="mailto:hello@interiordesignviewer.com"
                                class="text-gray-400 leading-relaxed hover:text-[#FF1493] hover:underline transition-all">hello@interiordesignviewer.com</a>
                            <p class="text-sm text-gray-400/70 mt-1">For general inquiries and support.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-5 group">
                        <div
                            class="w-14 h-14 bg-gradient-primary border-none shadow-[var(--shadow-primary)] rounded-2xl flex items-center justify-center shadow-lg shadow-primary/20 flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <i class="bi bi-telephone-fill text-2xl text-[#FF1493]"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-lg mb-1 group-hover:text-primary transition-colors">Call
                                Us</h4>
                            <a href="tel:+15551234567"
                                class="text-gray-400 leading-relaxed hover:text-[#FF1493] hover:underline transition-all">+1
                                (555) 123-4567</a>
                            <p class="text-sm text-gray-400/70 mt-1">Mon-Fri from 8am to 5pm.</p>
                        </div>
                    </div>
                </div>

                <!-- Socials -->
                <div class="pt-8 border-t border-white/10">
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Follow Our Work</h4>
                    <div class="flex gap-4">
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-primary hover:bg-gradient-primary border-none shadow-[var(--shadow-primary)] hover:text-[#FF1493] transition-colors"><i
                                class="bi bi-instagram"></i></a>
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-primary hover:bg-gradient-primary border-none shadow-[var(--shadow-primary)] hover:text-[#FF1493] transition-colors"><i
                                class="bi bi-linkedin"></i></a>
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-primary hover:bg-gradient-primary border-none shadow-[var(--shadow-primary)] hover:text-[#FF1493] transition-colors"><i
                                class="bi bi-pinterest"></i></a>
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-primary hover:bg-gradient-primary border-none shadow-[var(--shadow-primary)] hover:text-[#FF1493] transition-colors"><i
                                class="bi bi-twitter-x"></i></a>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-3 bg-white/5/30 p-8 rounded-[2rem] border border-white/10/50" data-aos="fade-left"
                data-aos-delay="100">
                <?php echo $msg; ?>

                <form method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label
                                class="block text-primary text-xs font-bold uppercase tracking-wider pl-4 border-l-2 border-[#FF1493]">First
                                & Last Name</label>
                            <div class="relative">
                                <i
                                    class="bi bi-person absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg pointer-events-none"></i>
                                <input type="text" name="name" required placeholder="John Doe"
                                    class="w-full glass-panel-dark text-white placeholder:text-gray-400/50 font-medium border-transparent rounded-xl py-4 pl-12 pr-4 focus:ring-2 focus:ring-primary/20 focus:border-primary/20 outline-none transition-all shadow-sm hover:shadow-md">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label
                                class="block text-primary text-xs font-bold uppercase tracking-wider pl-4 border-l-2 border-[#FF1493]">Email
                                Address</label>
                            <div class="relative">
                                <i
                                    class="bi bi-envelope absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg pointer-events-none"></i>
                                <input type="email" name="email" required placeholder="john@example.com"
                                    class="w-full glass-panel-dark text-white placeholder:text-gray-400/50 font-medium border-transparent rounded-xl py-4 pl-12 pr-4 focus:ring-2 focus:ring-primary/20 focus:border-primary/20 outline-none transition-all shadow-sm hover:shadow-md">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label
                            class="block text-primary text-xs font-bold uppercase tracking-wider pl-4 border-l-2 border-[#FF1493]">Your
                            Message</label>
                        <textarea name="message" rows="5" required
                            placeholder="Tell us about your project, ask for a quote, or leave feedback..."
                            class="w-full glass-panel-dark text-white placeholder:text-gray-400/50 font-medium border-transparent rounded-xl p-5 focus:ring-2 focus:ring-primary/20 focus:border-primary/20 outline-none transition-all shadow-sm hover:shadow-md resize-none"></textarea>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="w-full btn-premium hover:shadow-secondary/30 text-white font-bold py-4 rounded-xl transition-all flex justify-center items-center gap-3 transform active:scale-[0.98] text-lg">
                            <span>Send Message</span> <i class="bi bi-send-fill text-primary"></i>
                        </button>
                    </div>

                    <p class="text-xs text-center text-gray-400 mt-4 font-medium uppercase tracking-wider"><i
                            class="bi bi-shield-lock text-primary mr-1"></i> Your information is secure and encrypted.
                    </p>
                </form>
            </div>

        </div>

        <!-- Optional Map Section -->
        <div class="mt-20 rounded-[2.5rem] overflow-hidden border border-white/10 shadow-lg h-96 relative group"
            data-aos="fade-up">
            <div
                class="absolute inset-0 bg-gradient-primary border-none shadow-[var(--shadow-primary)]/10 group-hover:bg-transparent transition-colors duration-500 pointer-events-none z-10">
            </div>
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d113063.95358088265!2d-84.45339678385287!3d33.91697203672522!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88f5045d6993098d%3A0x66fede2f990b630b!2sAtlanta%2C%20GA!5e0!3m2!1sen!2sus!4v1714420365775!5m2!1sen!2sus"
                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                class="grayscale group-hover:grayscale-0 transition-all duration-700"></iframe>
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>