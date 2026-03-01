<x-layout>
    <x-aside>
        {{-- Top Navbar --}}
        <div class="navbar bg-base-100 border-b border-base-300 px-4 lg:px-6">
            <label for="sidebar-toggle" class="btn btn-ghost btn-sm btn-square lg:hidden">
                <i class="bi bi-list text-lg"></i>
            </label>
            <div class="flex-1 ml-2">
                <span class="font-bold text-lg">Kelola User</span>
            </div>
            <span class="text-sm text-base-content/60 hidden sm:inline">Master Data</span>
        </div>

        {{-- Content --}}
        <div class="p-4 lg:p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold">Daftar User</h2>
            </div>

            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-0">
                    <div class="overflow-x-auto">
                        <table id="userTable" class="table">
                            <thead>
                                <tr class="bg-base-200/50">
                                    <th class="w-12">#</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Dibuat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $i => $user)
                                    <tr class="hover:bg-base-200/30">
                                        <td class="text-base-content/50">{{ $i + 1 }}</td>
                                        <td>
                                            <span class="font-semibold text-sm">{{ $user->name }}</span>
                                        </td>
                                        <td class="text-base-content/60 text-sm">{{ $user->email }}</td>
                                        <td>
                                            @php
                                                $badgeClass = match ($user->role) {
                                                    'admin' => 'badge-error',
                                                    'panitia' => 'badge-warning',
                                                    'mahasiswa' => 'badge-primary',
                                                    default => 'badge-ghost',
                                                };
                                            @endphp
                                            <span
                                                class="badge {{ $badgeClass }} badge-sm">{{ ucfirst($user->role) }}</span>
                                        </td>
                                        <td class="text-base-content/50 text-sm">
                                            {{ $user->created_at?->format('d M Y') ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </x-aside>

    <x-datatables />

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#userTable').DataTable({
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                        infoEmpty: "Tidak ada data",
                        infoFiltered: "(disaring dari _MAX_ total data)",
                        zeroRecords: "Tidak ada data yang cocok",
                        paginate: {
                            first: "Pertama",
                            last: "Terakhir",
                            next: "›",
                            previous: "‹"
                        }
                    },
                    pageLength: 10,
                    order: [
                        [0, 'asc']
                    ]
                });
            });
        </script>
    @endpush
</x-layout>
