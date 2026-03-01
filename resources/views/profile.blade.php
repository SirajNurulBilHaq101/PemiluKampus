<x-layout>
    <x-aside>
        {{-- Top Navbar --}}
        <div class="navbar bg-base-100 border-b border-base-300 px-4 lg:px-6">
            <label for="sidebar-toggle" class="btn btn-ghost btn-sm btn-square lg:hidden">
                <i class="bi bi-list text-lg"></i>
            </label>
            <div class="flex-1 ml-2">
                <span class="font-bold text-lg">Profil Saya</span>
            </div>
        </div>

        {{-- Content --}}
        <div class="p-4 lg:p-6">
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body">
                    {{-- Avatar + Name --}}
                    <div class="flex items-center gap-4 mb-4">
                        <div class="avatar placeholder">
                            <div class="bg-primary text-primary-content w-16 rounded-full">
                                <span
                                    class="text-2xl font-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">{{ Auth::user()->name }}</h2>
                            <span class="badge badge-outline badge-sm">{{ ucfirst(Auth::user()->role) }}</span>
                        </div>
                    </div>

                    <div class="divider my-0"></div>

                    {{-- Info --}}
                    <div class="space-y-3 my-4">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-envelope text-base-content/40"></i>
                            <div>
                                <div class="text-xs text-base-content/50">Email</div>
                                <div class="text-sm font-medium">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="bi bi-shield-check text-base-content/40"></i>
                            <div>
                                <div class="text-xs text-base-content/50">Role</div>
                                <div class="text-sm font-medium">{{ ucfirst(Auth::user()->role) }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="bi bi-calendar3 text-base-content/40"></i>
                            <div>
                                <div class="text-xs text-base-content/50">Bergabung Sejak</div>
                                <div class="text-sm font-medium">{{ Auth::user()->created_at->format('d M Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Logout --}}
            <div class="mt-4">
                <form method="POST" action="{{ route('logout') }}"
                    onsubmit="event.preventDefault(); showConfirm(this, 'Apakah Anda yakin ingin keluar?', 'Logout')">
                    @csrf
                    <button type="submit" class="btn btn-error w-full">
                        <i class="bi bi-box-arrow-left"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </x-aside>
</x-layout>
