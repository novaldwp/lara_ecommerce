
                <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
                    <a href="{{ route('ecommerce.profile.index') }}" class="nav-link {{ request()->segment(2) == "dashboard" ? "active":""}}" id="dashboard-nav"><i class="fa fa-tachometer-alt"></i>Dashboard</a>
                    <a href="{{ route('ecommerce.profile.orders') }}" class="nav-link {{ request()->segment(2) == "orders" ? "active":""}}" id="orders-nav"><i class="fa fa-shopping-bag"></i>Orders</a>
                    <a href="{{ route('ecommerce.profile.account') }}" class="nav-link {{ request()->segment(2) == "account" ? "active":""}}" id="account-nav"><i class="fa fa-user"></i>Account Details</a>
                    <a href="{{ route('ecommerce.profile.address') }}" class="nav-link {{ request()->segment(2) == "address" ? "active":""}}" id="address-nav"><i class="fa fa-map-marker-alt"></i>Address</a>
                </div>
