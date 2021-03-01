<li class="side-menus {{ Request::is('/') ? 'active' : '' }}">
    <a class="nav-link" href="/">
        <i class=" fas fa-building"></i><span>Dashboard</span>
    </a>
</li>
<li class="dropdown {{ Request::is('admin/manage-products*') ? 'active' : '' }}">
    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Manage Products</span></a>
    <ul class="dropdown-menu">
      <li class="{{ Request::is('admin/manage-products/categories*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('categories.index') }}">Categories</a></li>
      <li class="{{ Request::is('admin/manage-products/brands*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('brands.index') }}">Brands</a></li>
      <li class="{{ Request::is('admin/manage-products/options*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('options.index') }}">Options</a></li>
      <li class="{{ Request::is('admin/manage-products/option-values*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('option-values.index') }}">Option Values</a></li>
    </ul>
  </li>
