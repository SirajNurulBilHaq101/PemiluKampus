@php
    $isAdmin = Auth::user()->role === 'admin';
    $isMahasiswa = Auth::user()->role === 'mahasiswa';
@endphp

{{-- Drawer wrapper — used by all pages that include aside --}}
<div class="drawer lg:drawer-open">
    <input id="sidebar-toggle" type="checkbox" class="drawer-toggle" />

    {{-- Page content (slot rendered by parent) --}}
    <div class="drawer-content flex flex-col min-h-screen">
        {{ $slot }}
    </div>

    {{-- Sidebar --}}
    <div class="drawer-side z-40">
        <label for="sidebar-toggle" class="drawer-overlay"></label>
        <aside class="bg-base-100 border-r border-base-300 w-64 min-h-screen flex flex-col">

            {{-- User Info --}}
            <div class="px-5 py-4 border-b border-base-300">
                <div class="font-semibold truncate">{{ Auth::user()->name }}</div>
                <div class="font-semibold truncates text-sm">{{ ucfirst(Auth::user()->role) }}</div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-3 py-4">
                <ul class="menu gap-1">
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2"></i>
                            Dashboard
                        </a>
                    </li>

                    @if ($isMahasiswa)
                        <li>
                            <a href="{{ route('vote.index') }}"
                                class="{{ request()->routeIs('vote.*') ? 'active' : '' }}">
                                <i class="bi bi-clipboard2-check"></i>
                                Pemilihan
                            </a>
                        </li>
                    @endif

                    @if ($isAdmin)
                        <li class="menu-title mt-3 text-xs uppercase tracking-wider opacity-50">Master Data</li>
                        <li>
                            <a href="{{ route('admin.masterData.user.index') }}"
                                class="{{ request()->routeIs('admin.masterData.user.*') ? 'active' : '' }}">
                                <i class="bi bi-people"></i>
                                Kelola User
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.masterData.event.index') }}"
                                class="{{ request()->routeIs('admin.masterData.event.*') ? 'active' : '' }}">
                                <i class="bi bi-calendar-event"></i>
                                Kelola Event
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.masterData.candidate.index') }}"
                                class="{{ request()->routeIs('admin.masterData.candidate.*') ? 'active' : '' }}">
                                <i class="bi bi-person-badge"></i>
                                Kelola Kandidat
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>

            {{-- Logout --}}
            <div class="px-3 py-4 border-t border-base-300">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-ghost btn-sm w-full justify-start gap-2">
                        <i class="bi bi-box-arrow-left"></i>
                        Logout
                    </button>
                </form>
            </div>
        </aside>
    </div>
</div>
