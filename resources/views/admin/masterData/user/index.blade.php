<x-layout>
    <div class="d-flex">
        <x-aside />

        <div class="flex-grow-1 bg-light min-vh-100">
            {{-- Top Navbar --}}
            <nav class="navbar navbar-light bg-white border-bottom px-4 py-3">
                <div class="d-lg-none" style="width: 40px;"></div>
                <span class="navbar-text fw-semibold fs-5">Kelola User</span>
                <span class="text-muted small d-none d-sm-inline">Master Data</span>
            </nav>

            {{-- Content --}}
            <div class="p-3 p-md-4">

                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Daftar User</h5>
                </div>

                {{-- Table --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <table id="userTable" class="table table-hover align-middle" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Dibuat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $i => $user)
                                    <tr>
                                        <td class="text-muted">{{ $i + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2 flex-shrink-0" style="width: 34px; height: 34px;">
                                                    <i class="bi bi-person-fill"></i>
                                                </div>
                                                <span class="fw-semibold">{{ $user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="text-muted">{{ $user->email }}</td>
                                        <td>
                                            @php
                                                $badgeClass = match($user->role) {
                                                    'admin'    => 'bg-danger',
                                                    'panitia'  => 'bg-warning text-dark',
                                                    'mahasiswa'=> 'bg-primary',
                                                    default    => 'bg-secondary',
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ ucfirst($user->role) }}</span>
                                        </td>
                                        <td class="text-muted small">{{ $user->created_at?->format('d M Y') ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function () {
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
                order: [[0, 'asc']]
            });
        });
    </script>
    @endpush
</x-layout>
