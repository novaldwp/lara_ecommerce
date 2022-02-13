<!-- Nav Bar Start -->
<div class="nav">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-md bg-dark navbar-dark">
            <a href="" class="navbar-brand">MENU</a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                <div class="navbar-nav mr-auto">
                    <a href="{{ route('ecommerce.index') }}" class="nav-item nav-link {{ Request::is('/') ? 'active' : '' }}">Beranda</a>
                    <a href="{{ route('ecommerce.product.index') }}" class="nav-item nav-link {{ Request::is('products*') ? 'active' : '' }}">Produk </a>
                    <a href="checkout.html" class="nav-item nav-link">Hubungi Kami</a>
                </div>
                <div class="navbar-nav ml-auto">
                        @auth
                        @role('customer')
                            <a href="{{ route('ecommerce.profile.index') }}" class="nav-item nav-link">Akun Saya</a>
                        @endrole
                        <form action="{{ route('auth.logout') }}" class="form-logout" method="post">
                            @csrf
                            <input type="submit" class="logoutButton" value="Logout" hidden>
                        </form>
                        <a href="#" class="nav-item nav-link fakeLogoutButton">Logout</a>
                        @else
                            <a href="{{ route('auth.login') }}" class="nav-item nav-link {{ Request::is('login') ? 'active' : '' }}">Login</a>
                            <a href="{{ route('auth.register') }}" class="nav-item nav-link {{ Request::is('register') ? 'active' : '' }}">Daftar</a>
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
                    <a href="/">
                        <img src="{{ asset('uploads/images/profiles/'. \ProfileHelper::getProfile()->image) }}" alt="Logo">
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <form action="{{ route('ecommerce.product.search') }}" method="GET">
                    <div class="search">
                        <input type="text" name="search" placeholder="Search">
                        <button><i class="fa fa-search"></i></button>
                    </div>
                </form>
            </div>
            @role('customer')
                <div class="col-md-3">
                    <div class="user">
                        <a href="wishlist.html" class="btn wishlist">
                            <i class="fa fa-heart"></i>
                            <span>(0)</span>
                        </a>
                        <a href="{{ route('ecommerce.cart.index') }}" class="btn cart">
                            <i class="fa fa-shopping-cart"></i>
                            <span id="countShoppingCart">({{ \NavbarHelper::getCartCount(auth()->user()->id) ?? 0 }})</span>
                        </a>
                    </div>
                </div>
            @endrole
        </div>
    </div>
</div>
<!-- Bottom Bar End -->
