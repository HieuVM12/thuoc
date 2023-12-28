<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{url('admin/dashboard')}}">
                <i class="mdi mdi-home menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('userIndex')}}">
                <i class="mdi mdi-account menu-icon"></i>
                <span class="menu-title">Quản lý người dùng</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('admin/customer/')}}">
                <i class="mdi mdi-account menu-icon"></i>
                <span class="menu-title">Quản lý đại lý</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false"
                aria-controls="ui-basic">
                <i class="mdi mdi-circle-outline menu-icon"></i>
                <span class="menu-title">Danh mục chung</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{url('admin/category/')}}">Nhóm thuốc</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{url('admin/producer/')}}">Nhà sản xuất</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{url('admin/ingredient/')}}">Hoạt chất</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{url('admin/hashtag/')}}">Hashtag</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('admin/product/')}}">
                <i class="mdi mdi-hospital menu-icon"></i>
                <span class="menu-title">Quản lý sản phẩm thuốc</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('admin/voucher/')}}">
                <i class="mdi mdi-hospital menu-icon"></i>
                <span class="menu-title">Quản lý mã giảm giá</span>
            </a>
        </li>
    </ul>
</nav>
