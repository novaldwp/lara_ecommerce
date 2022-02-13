<!-- Footer Start -->
<div class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4">
                <div class="footer-widget">
                    <h2>Get in Touch</h2>
                    <div class="contact-info">
                        <p><i class="fa fa-map-marker"></i>
                            {{ \ProfileHelper::getProfile()->address }}, {{ \ProfileHelper::getProfile()->cities->name }}, {{ \ProfileHelper::getProfile()->provinces->name }}, {{ \ProfileHelper::getProfile()->postcode }}
                        </p>
                        <p>
                            <i class="fa fa-envelope"></i> {{ \ProfileHelper::getProfile()->email }}
                        </p>
                        <p>
                            <i class="fa fa-phone"></i>
                            {{ str_replace("0", "+(62) ", substr(chunk_split(\ProfileHelper::getProfile()->phone, 4, "-"), 0, -1)) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="footer-widget">
                    <h2>Follow Us</h2>
                    <div class="contact-info">
                        <div class="social">
                            <a href="{{ \ProfileHelper::getProfile()->facebook ?? "#" }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                            <a href="{{ \ProfileHelper::getProfile()->instagram ?? "#" }}" target="_blank"><i class="fab fa-instagram"></i></a>
                            <a href="{{ \ProfileHelper::getProfile()->twitter ?? "#" }}" target="_blank"><i class="fab fa-twitter"></i></a>
                            <a href="{{ \ProfileHelper::getProfile()->linkedin ?? "#" }} " target="_blank"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="footer-widget">
                    <h2>Company Info</h2>
                    <ul>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms & Condition</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer End -->

<!-- Back to Top -->
<a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/front/lib/easing/easing.min.js') }}"></script>
<script src="{{ asset('assets/front/lib/slick/slick.min.js') }}"></script>
<script src="{{ asset('vendor/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
@yield('scripts');
<!-- Template Javascript -->
<script src="{{ asset('assets/front/js/main.js') }}"></script>
</body>
</html>
