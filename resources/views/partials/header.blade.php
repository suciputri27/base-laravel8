<header class="app-header">
    <div class="header-left">
        <button type="button" class="menu-toggle-btn" id="sidebarToggle" aria-label="Buka menu">
            <i class="fas fa-bars"></i>
        </button>

        <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
    </div>

    <div class="header-right">
        <div class="user-menu">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div class="user-info">
                <span class="user-name">{{ auth()->user()->name }}</span>
                <span class="user-role">{{ auth()->user()->getRoleNames()->first() ?? 'User' }}</span>
            </div>
        </div>

        <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>

        <button type="button" class="btn btn-logout btn-sm" onclick="App.submit(document.getElementById('logoutForm'), { onSuccess: function (r) { App.redirect(r.redirect); } });">
            <i class="fas fa-sign-out-alt"></i>
            <span class="d-none d-sm-inline">Logout</span>
        </button>
    </div>
</header>
