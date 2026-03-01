<x-layout>
    <div class="d-flex">
        <x-aside />

        <div class="flex-grow-1 bg-light min-vh-100">
            {{-- Top Navbar --}}
            <nav class="navbar navbar-light bg-white border-bottom px-4 py-3">
                <div class="d-lg-none" style="width: 40px;"></div>
                <span class="navbar-text fw-semibold fs-5">Kelola Event</span>
                <span class="text-muted small d-none d-sm-inline">Master Data</span>
            </nav>

            {{-- Content --}}
            <div class="p-3 p-md-4">

                {{-- Flash Message --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Daftar Event</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createEventModal">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Event
                    </button>
                </div>

                {{-- Table --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <table id="eventTable" class="table table-hover align-middle" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Nama Event</th>
                                    <th>Mulai</th>
                                    <th>Selesai</th>
                                    <th>Status</th>
                                    <th>Dibuat Oleh</th>
                                    <th style="width: 120px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($events as $i => $event)
                                    <tr>
                                        <td class="text-muted">{{ $i + 1 }}</td>
                                        <td>
                                            <span class="fw-semibold">{{ $event->name }}</span>
                                            @if($event->description)
                                                <br><small class="text-muted">{{ Str::limit($event->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td class="small">{{ $event->start_date->format('d M Y H:i') }}</td>
                                        <td class="small">{{ $event->end_date->format('d M Y H:i') }}</td>
                                        <td>
                                            @php
                                                $statusBadge = match($event->status) {
                                                    'upcoming'  => 'bg-info',
                                                    'active'    => 'bg-success',
                                                    'completed' => 'bg-secondary',
                                                    'cancelled' => 'bg-danger',
                                                    default     => 'bg-secondary',
                                                };
                                            @endphp
                                            <span class="badge {{ $statusBadge }}">{{ ucfirst($event->status) }}</span>
                                        </td>
                                        <td class="text-muted small">{{ $event->creator->name }}</td>
                                        <td>
                                            <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editEventModal{{ $event->id }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form method="POST" action="{{ route('admin.masterData.event.destroy', $event->id) }}" class="d-inline" onsubmit="return confirm('Yakin hapus event ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>

                                    {{-- Edit Modal --}}
                                    <div class="modal fade" id="editEventModal{{ $event->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('admin.masterData.event.update', $event->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold">Edit Event</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold">Nama Event</label>
                                                            <input type="text" class="form-control" name="name" value="{{ $event->name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold">Deskripsi</label>
                                                            <textarea class="form-control" name="description" rows="3">{{ $event->description }}</textarea>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label small fw-semibold">Mulai</label>
                                                                <input type="datetime-local" class="form-control" name="start_date" value="{{ $event->start_date->format('Y-m-d\TH:i') }}" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label small fw-semibold">Selesai</label>
                                                                <input type="datetime-local" class="form-control" name="end_date" value="{{ $event->end_date->format('Y-m-d\TH:i') }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold">Status</label>
                                                            <select class="form-select" name="status" required>
                                                                @foreach (['upcoming', 'active', 'completed', 'cancelled'] as $s)
                                                                    <option value="{{ $s }}" {{ $event->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    <div class="modal fade" id="createEventModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.masterData.event.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Tambah Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Nama Event</label>
                            <input type="text" class="form-control" name="name" placeholder="Nama event..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Deskripsi</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Deskripsi event (opsional)"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-semibold">Mulai</label>
                                <input type="datetime-local" class="form-control" name="start_date" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-semibold">Selesai</label>
                                <input type="datetime-local" class="form-control" name="end_date" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Status</label>
                            <select class="form-select" name="status" required>
                                <option value="upcoming" selected>Upcoming</option>
                                <option value="active">Active</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function () {
            $('#eventTable').DataTable({
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    zeroRecords: "Tidak ada data yang cocok",
                    paginate: { next: "›", previous: "‹" }
                },
                pageLength: 10,
                order: [[0, 'asc']]
            });
        });
    </script>
    @endpush
</x-layout>
