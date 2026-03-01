<x-layout>
    <div class="d-flex">
        <x-aside />

        <div class="flex-grow-1 bg-light min-vh-100">
            {{-- Top Navbar --}}
            <nav class="navbar navbar-light bg-white border-bottom px-4 py-3">
                <div class="d-lg-none" style="width: 40px;"></div>
                <span class="navbar-text fw-semibold fs-5">Kelola Kandidat</span>
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
                    <h5 class="fw-bold mb-0">Daftar Kandidat</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createCandidateModal">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Kandidat
                    </button>
                </div>

                {{-- Table --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <table id="candidateTable" class="table table-hover align-middle" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th style="width: 70px;">Foto</th>
                                    <th>Nama</th>
                                    <th>Event</th>
                                    <th>No. Urut</th>
                                    <th style="width: 120px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($candidates as $i => $candidate)
                                    <tr>
                                        <td class="text-muted">{{ $i + 1 }}</td>
                                        <td>
                                            @if($candidate->photo)
                                                <img src="{{ asset('storage/' . $candidate->photo) }}" alt="Foto" class="rounded" style="width: 45px; height: 45px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary bg-opacity-10 rounded d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                                    <i class="bi bi-person text-secondary"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ $candidate->name }}</span>
                                            @if($candidate->vision)
                                                <br><small class="text-muted">{{ Str::limit($candidate->vision, 40) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info bg-opacity-75">{{ $candidate->event->name }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary rounded-pill">{{ $candidate->candidate_number }}</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editCandidateModal{{ $candidate->id }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form method="POST" action="{{ route('admin.masterData.candidate.destroy', $candidate->id) }}" class="d-inline" onsubmit="return confirm('Yakin hapus kandidat ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>

                                    {{-- Edit Modal --}}
                                    <div class="modal fade" id="editCandidateModal{{ $candidate->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('admin.masterData.candidate.update', $candidate->id) }}" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold">Edit Kandidat</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold">Event</label>
                                                            <select class="form-select" name="event_id" required>
                                                                @foreach ($events as $event)
                                                                    <option value="{{ $event->id }}" {{ $candidate->event_id == $event->id ? 'selected' : '' }}>{{ $event->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold">Nama Kandidat</label>
                                                            <input type="text" class="form-control" name="name" value="{{ $candidate->name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold">Nomor Urut</label>
                                                            <input type="number" class="form-control" name="candidate_number" value="{{ $candidate->candidate_number }}" min="1" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold">Foto</label>
                                                            @if($candidate->photo)
                                                                <div class="mb-2">
                                                                    <img src="{{ asset('storage/' . $candidate->photo) }}" alt="Foto saat ini" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                                                </div>
                                                            @endif
                                                            <input type="file" class="form-control" name="photo" accept="image/*">
                                                            <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold">Visi</label>
                                                            <textarea class="form-control" name="vision" rows="3">{{ $candidate->vision }}</textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold">Misi</label>
                                                            <textarea class="form-control" name="mission" rows="3">{{ $candidate->mission }}</textarea>
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
    <div class="modal fade" id="createCandidateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.masterData.candidate.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Tambah Kandidat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Event</label>
                            <select class="form-select" name="event_id" required>
                                <option value="" disabled selected>Pilih event...</option>
                                @foreach ($events as $event)
                                    <option value="{{ $event->id }}">{{ $event->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Nama Kandidat</label>
                            <input type="text" class="form-control" name="name" placeholder="Nama kandidat..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Nomor Urut</label>
                            <input type="number" class="form-control" name="candidate_number" placeholder="1" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Foto</label>
                            <input type="file" class="form-control" name="photo" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Visi</label>
                            <textarea class="form-control" name="vision" rows="3" placeholder="Visi kandidat (opsional)"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Misi</label>
                            <textarea class="form-control" name="mission" rows="3" placeholder="Misi kandidat (opsional)"></textarea>
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
            $('#candidateTable').DataTable({
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
