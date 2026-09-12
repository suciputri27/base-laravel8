<aside class="app-sidebar" id="appSidebar">
    <div class="brand">
        <span class="brand-mark"><i class="fas fa-layer-group"></i></span>
        <span>Base App</span>
    </div>

    <button type="button" class="sidebar-close" id="sidebarClose" aria-label="Tutup menu">
        <i class="fas fa-times"></i>
    </button>

    <nav class="sidebar-nav">
        @foreach($sidebarMenus as $menu)
            @if($menu->route_or_url)
                <a class="nav-link {{ menu_is_active($menu->route_or_url) ? 'active' : '' }}" href="{{ menu_url($menu->route_or_url) }}">
                    <i class="{{ $menu->icon }}"></i>
                    <span>{{ $menu->name }}</span>
                </a>
            @else
                @php
                    $activeChild = isset($menu->children) && $menu->children->contains(function ($child) {
                        return menu_is_active($child->route_or_url);
                    });
                @endphp

                <button type="button" class="nav-link nav-toggle {{ $activeChild ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#submenu-{{ $menu->id }}" aria-expanded="{{ $activeChild ? 'true' : 'false' }}">
                    <i class="{{ $menu->icon }}"></i>
                    <span>{{ $menu->name }}</span>
                    <i class="fas fa-chevron-down chevron ms-auto"></i>
                </button>

                <div class="collapse submenu {{ $activeChild ? 'show' : '' }}" id="submenu-{{ $menu->id }}">
                    @if(isset($menu->children))
                        @foreach($menu->children as $child)
                            <a class="nav-link menu-child {{ menu_is_active($child->route_or_url) ? 'active' : '' }}" href="{{ menu_url($child->route_or_url) }}">
                                <i class="{{ $child->icon }}"></i>
                                <span>{{ $child->name }}</span>
                            </a>
                        @endforeach
                    @endif
                </div>
            @endif
        @endforeach
    </nav>
</aside>
