{{-- Mobile Toggle Button --}}
<button class="btn btn-dark d-lg-none position-fixed top-0 start-0 m-3 z-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
    <i class="bi bi-list fs-5"></i>
</button>

{{-- Sidebar --}}
<div class="offcanvas-lg offcanvas-start bg-dark text-white" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel" style="width: 250px;">
    <div class="offcanvas-header bg-dark border-bottom border-secondary d-lg-none">
        <h5 class="offcanvas-title text-white" id="sidebarMenuLabel">
            <i class="bi bi-clipboard2-data me-2"></i>Pemilu
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column p-3" style="min-height: 100vh;">
        {{-- User Info --}}
        <div class="d-flex align-items-center mb-2 px-2 mt-2">
            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                <i class="bi bi-person-fill text-white"></i>
            </div>
            <div>
                <div class="fw-semibold small">{{ Auth::user()->name }}</div>
                <span class="badge bg-primary bg-opacity-75" style="font-size: 0.7rem;">{{ ucfirst(Auth::user()->role) }}</span>
            </div>
        </div>
        <hr>

        {{-- Navigation --}}
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('vote.index') }}" class="nav-link text-white {{ request()->routeIs('vote.*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard2-check me-2"></i> Pemilihan
                </a>
            </li>

            @if(Auth::user()->role === 'admin')
                <li class="mt-3 mb-1 px-3">
                    <span class="text-uppercase small text-secondary fw-bold" style="font-size: 0.7rem;">Master Data</span>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.masterData.user.index') }}" class="nav-link text-white {{ request()->routeIs('admin.masterData.user.*') ? 'active' : '' }}">
                        <i class="bi bi-people me-2"></i> Kelola User
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.masterData.event.index') }}" class="nav-link text-white {{ request()->routeIs('admin.masterData.event.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-event me-2"></i> Kelola Event
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.masterData.candidate.index') }}" class="nav-link text-white {{ request()->routeIs('admin.masterData.candidate.*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge me-2"></i> Kelola Kandidat
                    </a>
                </li>
            @endif
        </ul>

        {{-- Logout --}}
        <hr>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-light w-100 d-flex align-items-center justify-content-center">
                <i class="bi bi-box-arrow-left me-2"></i> Logout
            </button>
        </form>
    </div>
</div>
