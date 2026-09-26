<?php
// includes/footer.php
$phone_primary = $site_settings['phone_primary'] ?? '';
$email_address = $site_settings['email_address'] ?? '';
$address = $site_settings['address'] ?? '';
$fb_link = $site_settings['facebook_link'] ?? '#';
$tw_link = $site_settings['twitter_link'] ?? '#';
$ig_link = $site_settings['instagram_link'] ?? '#';
?>
        <!--================ start footer Area  =================-->    
        <footer class="footer-area section_gap">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="single-footer-widget">
                            <h6 class="footer_title">About Parijat Resort</h6>
                            <p>Located in the serene surroundings of Bolpur, Parijat Resort offers the perfect blend of luxury, comfort, and nature. A perfect getaway for families and couples.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="single-footer-widget">
                            <h6 class="footer_title">Quick Links</h6>
                            <div class="row">
                                <div class="col-6">
                                    <ul class="list_style">
                                        <li><a href="./">Home</a></li>
                                        <li><a href="accomodation">Rooms</a></li>
                                        <li><a href="dining">Dining</a></li>
                                        <li><a href="gallery">Gallery</a></li>
                                    </ul>
                                </div>
                                <div class="col-6">
                                    <ul class="list_style">
                                        <li><a href="banquet">Banquet &amp; Conference</a></li>
                                        <li><a href="rules">Rules &amp; Policies</a></li>
                                        <li><a href="contact">Contact Us</a></li>
                                        <li><a href="booking">Book Now</a></li>
                                    </ul>
                                </div>                                      
                            </div>                          
                        </div>
                    </div>                          
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="single-footer-widget">
                            <h6 class="footer_title">Contact Info</h6>
                            <p>
                                <i class="fa fa-map-marker mr-2"></i> <?php echo htmlspecialchars($address); ?><br>
                                <i class="fa fa-phone mr-2"></i> <?php echo htmlspecialchars($phone_primary); ?><br>
                                <i class="fa fa-envelope mr-2"></i> <?php echo htmlspecialchars($email_address); ?>
                            </p>        
                        </div>
                    </div>                      
                </div>
                <div class="border_line"></div>
                <div class="row footer-bottom d-flex justify-content-between align-items-center">
                    <p class="col-lg-8 col-sm-12 footer-text m-0">
                        Copyright &copy;<?php echo date('Y'); ?> All rights reserved | Parijat Resort Bolpur
                    </p>
                    <div class="col-lg-4 col-sm-12 footer-social">
                        <a href="<?php echo htmlspecialchars($fb_link); ?>" target="_blank"><i class="fa fa-facebook"></i></a>
                        <a href="<?php echo htmlspecialchars($tw_link); ?>" target="_blank"><i class="fa fa-twitter"></i></a>
                        <a href="<?php echo htmlspecialchars($ig_link); ?>" target="_blank"><i class="fa fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </footer>
        <!--================ End footer Area  =================-->
        
        <!-- Optional JavaScript -->
        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/popper.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="vendors/owl-carousel/owl.carousel.min.js"></script>
        <script src="js/jquery.ajaxchimp.min.js"></script>
        <script src="js/mail-script.js"></script>
        <script src="vendors/bootstrap-datepicker/bootstrap-datetimepicker.min.js"></script>
        <!-- Flatpickr Modern Calendar JS -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="vendors/nice-select/js/jquery.nice-select.js"></script>
        <script src="js/stellar.js"></script>
        <script src="vendors/lightbox/simpleLightbox.min.js"></script>
        <script src="js/custom.js"></script>
        <script src="assets/js/theme.js"></script>
        
        <!-- AOS Animation JS -->
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                AOS.init({
                    duration: 800, // values from 0 to 3000, with step 50ms
                    easing: 'ease-in-out', // default easing for AOS animations
                    once: true, // whether animation should happen only once - while scrolling down
                    offset: 100 // offset (in px) from the original trigger point
                });
            });
        </script>
    </body>
</html>
