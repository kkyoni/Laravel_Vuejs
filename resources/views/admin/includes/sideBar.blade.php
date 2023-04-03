<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <img src="{{ url(\Settings::get('application_logo')) }}" alt="image" class="rounded-circle" height="60px" width="60px" style="border-radius:20%!important">
                </div>
                <div class="logo-element">
                    <img alt="image" class="rounded-circle" height="60px" width="60px" style="border-radius:20%!important" src="{{ url(\Settings::get('application_logo')) }}">
                </div>
            </li>
            <li class="@if(Request::segment('2') == 'dashboard') active @endif" data-toggle="tooltip" title="Dashboard">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fa fa-home"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </li>
            <li class="@if(Request::segment('2') == 'user') active @endif" data-toggle="tooltip" title="Users">
                <a href="{{ route('admin.user.index') }}">
                    <i class="fa fa-home"></i>
                    <span class="nav-label">Users</span>
                </a>
            </li>
            <li class="@if(Request::segment('2') == 'settings') active @endif" data-toggle="tooltip" title="Setting">
                <a href="{{ url('admin/settings') }}">
                    <i class="fa fa-cogs"></i>
                    <span class="nav-label">Setting </span>
                </a>
            </li>
        </ul>
    </div>
</nav>