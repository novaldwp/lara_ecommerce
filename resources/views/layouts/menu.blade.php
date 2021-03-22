<li class="side-menus {{ Request::is('/') ? 'active' : '' }}">
    <a class="nav-link" href="/">
        <i class=" fas fa-building"></i><span>Dashboard</span>
    </a>
</li>
<li class="dropdown {{ Request::is('admin/manage-products*') ? 'active' : '' }}">
    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Manage Products</span></a>
    <ul class="dropdown-menu">
        <li class="{{ Request::is('admin/manage-products/products*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('products.index') }}">Products</a></li>
        <li class="{{ Request::is('admin/manage-products/categories*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('categories.index') }}">Categories</a></li>
        <li class="{{ Request::is('admin/manage-products/brands*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('brands.index') }}">Brands</a></li>
        <li class="{{ Request::is('admin/manage-products/warranties*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('warranties.index') }}">Warranties</a></li>
    </ul>
</li>
<li class="dropdown {{ Request::is('admin/manage-payment*') ? 'active' : '' }}">
    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Manage Payments</span></a>
    <ul class="dropdown-menu">
        <li class="{{ Request::is('admin/manage-payment/banks*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('banks.index') }}">Banks</a></li>
        <li class="{{ Request::is('admin/manage-payment/payments*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('payments.index') }}">Payments</a></li>
        <li class="{{ Request::is('admin/manage-payment/payment-methods*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('payment-methods.index') }}">Payment Methods</a></li>
    </ul>
</li>
