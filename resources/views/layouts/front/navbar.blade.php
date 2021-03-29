<!-- Nav Bar Start -->
<div class="nav">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-md bg-dark navbar-dark">
            <a href="#" class="navbar-brand">MENU</a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                <div class="navbar-nav mr-auto">
                    <a href="{{ route('ecommerce.index') }}" class="nav-item nav-link {{ Request::is('/') ? 'active' : '' }}">Home</a>
                    <a href="{{ route('ecommerce.product.index') }}" class="nav-item nav-link {{ Request::is('products*') ? 'active' : '' }}">Products</a>
                    <a href="checkout.html" class="nav-item nav-link">Checkout</a>
                    <a href="checkout.html" class="nav-item nav-link">Contact Us</a>
                </div>
                <div class="navbar-nav ml-auto">
                    @auth('members')
                        <a href="{{ route('ecommerce.profile.index') }}" class="nav-item nav-link">My Account</a>
                        <a href="{{ route('ecommerce.logout') }}" class="nav-item nav-link">Logout</a>
                    @else
                        <a href="{{ route('ecommerce.login.index') }}" class="nav-item nav-link {{ Request::is('login') ? 'active' : '' }}">Login</a>
                        <a href="{{ route('ecommerce.register.index') }}" class="nav-item nav-link {{ Request::is('register') ? 'active' : '' }}">Register</a>
                    @endauth
                </div>
            </div>
        </nav>
    </div>
</div>
<!-- Nav Bar End -->

<!-- Bottom Bar Start -->
<div class="bottom-bar">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-3">
                <div class="logo">
                    <a href="index.html">
                        <img src="{{ asset('assets/front/img/logo.png') }}" alt="Logo">
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="search">
                    <input type="text" placeholder="Search">
                    <button><i class="fa fa-search"></i></button>
                </div>
            </div>
            @auth('members')
                <div class="col-md-3">
                    <div class="user">
                        <a href="wishlist.html" class="btn wishlist">
                            <i class="fa fa-heart"></i>
                            <span>(0)</span>
                        </a>
                        <a href="{{ route('ecommerce.cart.index') }}" class="btn cart">
                            <i class="fa fa-shopping-cart"></i>
                            <span>({{ \NavbarHelper::getCartCount(auth()->guard('members')->user()->id) ?? 0 }})</span>
                        </a>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</div>
<!-- Bottom Bar End -->
