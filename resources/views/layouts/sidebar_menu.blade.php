<li class="side-menus {{ Request::is('/') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.dashboard') }}">
        <i class=" fas fa-tachometer-alt"></i><span>Dashboard</span>
    </a>
</li>
<li class="dropdown {{ Request::is('admin/manage-products*') ? 'active' : '' }}">
    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-tag"></i> <span>Produk</span></a>
    <ul class="dropdown-menu">
        <li class="{{ Request::is('admin/manage-products/products*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.products.index') }}">Produk</a></li>
        <li class="{{ Request::is('admin/manage-products/categories*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.categories.index') }}">Kategori</a></li>
        <li class="{{ Request::is('admin/manage-products/brands*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.brands.index') }}">Merk</a></li>
    </ul>
</li>
<li class="side-menus {{ Request::is('admin/manage-orders/*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.orders.index') }}">
        <i class=" fas fa-list"></i><span>Order</span>
    </a>
</li>
<li class="dropdown {{ Request::is('admin/manage-analyst/*') ? 'active' : '' }} ">
    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-comment-dots"></i> <span>Analisis</span></a>
    <ul class="dropdown-menu">
        <li class="side-menus {{ Request::is('admin/manage-analyst/sentiment-analyses*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.analyst.sentiment-analyses.index') }}">
                <span>Analisis Sentimen</span>
            </a>
        </li>
        <li class="side-menus {{ Request::is('admin/manage-analyst/data-trainings*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route("admin.analyst.data-trainings.index") }}">
                <span>Data Latih</span>
            </a>
        </li>
        <li class="side-menus {{ Request::is('admin/manage-analyst/positive-words*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.analyst.positive-words.index') }}">
                <span>Kata Dasar Positif</span>
            </a>
        </li>
        <li class="side-menus {{ Request::is('admin/manage-analyst/negative-words*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.analyst.negative-words.index') }}">
                <span>Kata Dasar Negatif</span>
            </a>
        </li>
    </ul>
</li>
<li class="dropdown {{ Request::is('admin/manage-users/*') ? 'active' : '' }} ">
    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-users"></i> <span>Pengguna</span></a>
    <ul class="dropdown-menu">
        <li class="side-menus {{ Request::is('admin/manage-users/customers*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.customers.index') }}">
                <span>Pelanggan</span>
            </a>
        </li>
        <li class="side-menus {{ Request::is('admin/manage-users/admins*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.admins.index') }}">
                <span>Petugas</span>
            </a>
        </li>
    </ul>
</li>
<li class="dropdown {{ Request::is('admin/manage-reports/*') ? 'active' : '' }} ">
    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-boxes"></i> <span>Laporan</span></a>
    <ul class="dropdown-menu">
        <li class="side-menus {{ Request::is('admin/manage-reports/orders*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.report.orders') }}">
                <span>Order</span>
            </a>
        </li>
        <li class="side-menus {{ Request::is('admin/manage-reports/products*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.report.products') }}">
                <span>Produk</span>
            </a>
        </li>
        <li class="side-menus {{ Request::is('admin/manage-reports/customers*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.report.customers') }}">
                <span>Pembeli</span>
            </a>
        </li>
        <li class="side-menus {{ Request::is('admin/manage-reports/analysis*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.report.analysis') }}">
                <span>Analisis</span>
            </a>
        </li>
    </ul>
</li>
<li class="dropdown {{ Request::is('admin/manage-settings*') ? 'active' : '' }}">
    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-wrench"></i> <span>Pengaturan</span></a>
    <ul class="dropdown-menu">
        <li class="{{ Request::is('admin/manage-settings/profiles*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.setting.profile.index') }}">Profile</a></li>
        <li class="{{ Request::is('admin/manage-settings/sliders*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.setting.sliders.index') }}">Slider</a></li>
    </ul>
</li>
