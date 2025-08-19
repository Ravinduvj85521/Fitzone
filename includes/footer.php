        </main>

        <!-- Footer -->
        <footer class="main-footer">
            <div class="container">
                <div class="footer-grid">
                    <div class="footer-col">
                        <div class="logo">
                            <a href="index.php">
                                <!-- <img src="assets/images/logo-white.png" alt="FitZone Logo"> -->
                                <span>FitZone</span>
                            </a>
                        </div>
                        <p>Your premier fitness destination in Kurunegala, Sri Lanka. We're committed to helping you achieve your fitness goals.</p>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                    
                    <div class="footer-col">
                        <h3>Quick Links</h3>
                        <ul>
                            <li><a href="index.php">Home</a></li>
                            <li><a href="about.php">About Us</a></li>
                            <li><a href="classes.php">Classes</a></li>
                            <li><a href="trainers.php">Trainers</a></li>
                            <li><a href="membership.php">Membership</a></li>
                            <li><a href="blog.php">Blog</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-col">
                        <h3>Contact Us</h3>
                        <ul class="contact-info">
                            <li><i class="fas fa-map-marker-alt"></i> 123 Fitness Street, Kurunegala</li>
                            <li><i class="fas fa-phone"></i> +94 76 123 4567</li>
                            <li><i class="fas fa-envelope"></i> info@fitzone.lk</li>
                            <li><i class="fas fa-clock"></i> Mon-Fri: 6AM-10PM, Sat-Sun: 8AM-8PM</li>
                        </ul>
                    </div>
                    
                    <div class="footer-col">
                        <h3>Newsletter</h3>
                        <p>Subscribe to our newsletter for fitness tips and special offers.</p>
                        <form class="newsletter-form">
                            <input type="email" placeholder="Your Email" required>
                            <button type="submit">Subscribe</button>
                        </form>
                    </div>
                </div>
                
                <div class="footer-bottom">
                    <p>&copy; <?php echo date('Y'); ?> FitZone Fitness Center. All Rights Reserved.</p>
                    <div class="legal-links">
                        <a href="privacy.php">Privacy Policy</a>
                        <a href="terms.php">Terms of Service</a>
                        <a href="sitemap.php">Sitemap</a>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Back to Top Button -->
        <a href="#" class="back-to-top" id="backToTop">
            <i class="fas fa-arrow-up"></i>
        </a>

        <!-- JavaScript -->
        <script src="assets/js/main.js"></script>
        <?php if (isset($customJS)): ?>
            <script src="assets/js/<?php echo $customJS; ?>"></script>
        <?php endif; ?>
        
        <!-- Initialize any page-specific JS -->
        <script>
            $(document).ready(function() {
                // Mobile menu toggle
                $('.mobile-menu-toggle').click(function() {
                    $('.mobile-menu').slideToggle();
                });
                
                // Back to top button
                $(window).scroll(function() {
                    if ($(this).scrollTop() > 300) {
                        $('#backToTop').fadeIn();
                    } else {
                        $('#backToTop').fadeOut();
                    }
                });
                
                $('#backToTop').click(function(e) {
                    e.preventDefault();
                    $('html, body').animate({scrollTop: 0}, '300');
                });
            });
        </script>
    </body>
</html>