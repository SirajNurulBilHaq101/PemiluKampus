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

            {{-- Brand --}}

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
                        <li class="menu-title mt-3 text-xs uppercase tracking-wider opacity-50">Suara Anda</li>
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

                        <li class="menu-title mt-3 text-xs uppercase tracking-wider opacity-50">Report</li>
                        <li>
                            <a href="{{ route('admin.voteLog.index') }}"
                                class="{{ request()->routeIs('admin.voteLog.*') ? 'active' : '' }}">
                                <i class="bi bi-journal-text"></i>
                                Log Suara
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.report.index') }}"
                                class="{{ request()->routeIs('admin.report.*') ? 'active' : '' }}">
                                <i class="bi bi-file-earmark-bar-graph"></i>
                                Laporan Hasil
                            </a>
                        </li>
                    @endif

                    <li class="menu-title mt-3 text-xs uppercase tracking-wider opacity-50">Akun</li>
                    <li>
                        <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'active' : '' }}">
                            <i class="bi bi-person-circle"></i>
                            Profil
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>
    </div>
</div>
