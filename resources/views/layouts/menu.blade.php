<li class="side-menus {{ Request::is('/') ? 'active' : '' }}">
    <a class="nav-link" href="/">
        <i class=" fas fa-building"></i><span>Dashboard</span>
    </a>
</li>
<li class="dropdown {{ Request::is('admin/category*') ? 'active' : '' }}">
    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Category</span></a>
    <ul class="dropdown-menu">
      <li class="{{ Request::is('admin/category') ? 'active' : '' }}"><a class="nav-link" href="{{ route('category.index') }}">Manage Category</a></li>
      <li class="{{ Request::is('admin/category/create') ? 'active' : '' }}"><a class="nav-link" href="{{ route('category.create') }}">Add Category</a></li>
    </ul>
  </li>
{{-- <li class="side-menus {{ Request::is('admin/category/*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('category.index') }}">
        <i class=" fas fa-building"></i><span>Category</span>
    </a>
</li> --}}
