<x-layout>
    <x-aside>
        {{-- Top Navbar --}}
        <div class="navbar bg-base-100 border-b border-base-300 px-4 lg:px-6">
            <label for="sidebar-toggle" class="btn btn-ghost btn-sm btn-square lg:hidden">
                <i class="bi bi-list text-lg"></i>
            </label>
            <div class="flex-1 ml-2">
                <span class="font-bold text-lg">Kelola Kandidat</span>
            </div>
            <span class="text-sm text-base-content/60 hidden sm:inline">Master Data</span>
        </div>

        {{-- Content --}}
        <div class="p-4 lg:p-6">

            {{-- Flash Message --}}
            @if (session('success'))
                <div role="alert" class="alert alert-success mb-4">
                    <i class="bi bi-check-circle text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold">Daftar Kandidat</h2>
                <button class="btn btn-primary btn-sm" onclick="createCandidateModal.showModal()">
                    <i class="bi bi-plus-lg"></i> Tambah Kandidat
                </button>
            </div>

            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-0">
                    <div class="overflow-x-auto">
                        <table id="candidateTable" class="table">
                            <thead>
                                <tr class="bg-base-200/50">
                                    <th class="w-12">#</th>
                                    <th class="w-16">Foto</th>
                                    <th>Nama</th>
                                    <th>Event</th>
                                    <th>No. Urut</th>
                                    <th class="w-28">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($candidates as $i => $candidate)
                                    <tr class="hover:bg-base-200/30">
                                        <td class="text-base-content/50">{{ $i + 1 }}</td>
                                        <td>
                                            @if ($candidate->photo)
                                                <div class="avatar">
                                                    <div class="w-10 rounded">
                                                        <img src="{{ asset('storage/' . $candidate->photo) }}"
                                                            alt="Foto" />
                                                    </div>
                                                </div>
                                            @else
                                                <div class="avatar placeholder">
                                                    <div class="bg-base-300 text-base-content/30 w-10 rounded">
                                                        <i class="bi bi-person"></i>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="font-semibold text-sm">{{ $candidate->name }}</span>
                                            @if ($candidate->vision)
                                                <br><span
                                                    class="text-xs text-base-content/50">{{ Str::limit($candidate->vision, 40) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="badge badge-info badge-sm badge-outline">{{ $candidate->event->name }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge badge-primary badge-sm">{{ $candidate->candidate_number }}</span>
                                        </td>
                                        <td>
                                            <div class="flex gap-1">
                                                <button class="btn btn-warning btn-xs btn-outline"
                                                    onclick="editCandidateModal{{ $candidate->id }}.showModal()">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form method="POST"
                                                    action="{{ route('admin.masterData.candidate.destroy', $candidate->id) }}"
                                                    onsubmit="return confirm('Yakin hapus kandidat ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-error btn-xs btn-outline"><i
                                                            class="bi bi-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Edit Modal --}}
                                    <dialog id="editCandidateModal{{ $candidate->id }}" class="modal">
                                        <div class="modal-box">
                                            <form method="dialog"><button
                                                    class="btn btn-sm btn-circle btn-ghost absolute right-3 top-3">✕</button>
                                            </form>
                                            <h3 class="font-bold text-lg mb-4">Edit Kandidat</h3>
                                            <form method="POST"
                                                action="{{ route('admin.masterData.candidate.update', $candidate->id) }}"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <fieldset class="fieldset mb-3">
                                                    <legend class="fieldset-legend text-sm">Event</legend>
                                                    <select class="select w-full" name="event_id" required>
                                                        @foreach ($events as $event)
                                                            <option value="{{ $event->id }}"
                                                                {{ $candidate->event_id == $event->id ? 'selected' : '' }}>
                                                                {{ $event->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </fieldset>
                                                <fieldset class="fieldset mb-3">
                                                    <legend class="fieldset-legend text-sm">Nama Kandidat</legend>
                                                    <input type="text" class="input w-full" name="name"
                                                        value="{{ $candidate->name }}" required>
                                                </fieldset>
                                                <fieldset class="fieldset mb-3">
                                                    <legend class="fieldset-legend text-sm">Nomor Urut</legend>
                                                    <input type="number" class="input w-full" name="candidate_number"
                                                        value="{{ $candidate->candidate_number }}" min="1"
                                                        required>
                                                </fieldset>
                                                <fieldset class="fieldset mb-3">
                                                    <legend class="fieldset-legend text-sm">Foto</legend>
                                                    @if ($candidate->photo)
                                                        <div class="mb-2">
                                                            <div class="avatar">
                                                                <div class="w-16 rounded">
                                                                    <img src="{{ asset('storage/' . $candidate->photo) }}"
                                                                        alt="Foto saat ini" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <input type="file" class="file-input w-full" name="photo"
                                                        accept="image/*">
                                                    <p class="text-xs text-base-content/50 mt-1">Kosongkan jika tidak
                                                        ingin mengubah foto</p>
                                                </fieldset>
                                                <fieldset class="fieldset mb-3">
                                                    <legend class="fieldset-legend text-sm">Visi</legend>
                                                    <textarea class="textarea w-full" name="vision" rows="3">{{ $candidate->vision }}</textarea>
                                                </fieldset>
                                                <fieldset class="fieldset mb-4">
                                                    <legend class="fieldset-legend text-sm">Misi</legend>
                                                    <textarea class="textarea w-full" name="mission" rows="3">{{ $candidate->mission }}</textarea>
                                                </fieldset>
                                                <div class="flex justify-end gap-2">
                                                    <button type="button" class="btn btn-ghost btn-sm"
                                                        onclick="editCandidateModal{{ $candidate->id }}.close()">Batal</button>
                                                    <button type="submit"
                                                        class="btn btn-primary btn-sm">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                        <form method="dialog" class="modal-backdrop"><button>close</button></form>
                                    </dialog>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </x-aside>

    {{-- Create Modal --}}
    <dialog id="createCandidateModal" class="modal">
        <div class="modal-box">
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost absolute right-3 top-3">✕</button>
            </form>
            <h3 class="font-bold text-lg mb-4">Tambah Kandidat</h3>
            <form method="POST" action="{{ route('admin.masterData.candidate.store') }}"
                enctype="multipart/form-data">
                @csrf
                <fieldset class="fieldset mb-3">
                    <legend class="fieldset-legend text-sm">Event</legend>
                    <select class="select w-full" name="event_id" required>
                        <option value="" disabled selected>Pilih event...</option>
                        @foreach ($events as $event)
                            <option value="{{ $event->id }}">{{ $event->name }}</option>
                        @endforeach
                    </select>
                </fieldset>
                <fieldset class="fieldset mb-3">
                    <legend class="fieldset-legend text-sm">Nama Kandidat</legend>
                    <input type="text" class="input w-full" name="name" placeholder="Nama kandidat..."
                        required>
                </fieldset>
                <fieldset class="fieldset mb-3">
                    <legend class="fieldset-legend text-sm">Nomor Urut</legend>
                    <input type="number" class="input w-full" name="candidate_number" placeholder="1"
                        min="1" required>
                </fieldset>
                <fieldset class="fieldset mb-3">
                    <legend class="fieldset-legend text-sm">Foto</legend>
                    <input type="file" class="file-input w-full" name="photo" accept="image/*">
                </fieldset>
                <fieldset class="fieldset mb-3">
                    <legend class="fieldset-legend text-sm">Visi</legend>
                    <textarea class="textarea w-full" name="vision" rows="3" placeholder="Visi kandidat (opsional)"></textarea>
                </fieldset>
                <fieldset class="fieldset mb-4">
                    <legend class="fieldset-legend text-sm">Misi</legend>
                    <textarea class="textarea w-full" name="mission" rows="3" placeholder="Misi kandidat (opsional)"></textarea>
                </fieldset>
                <div class="flex justify-end gap-2">
                    <button type="button" class="btn btn-ghost btn-sm"
                        onclick="createCandidateModal.close()">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Tambah</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>

    <x-datatables />

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#candidateTable').DataTable({
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                        infoEmpty: "Tidak ada data",
                        infoFiltered: "(disaring dari _MAX_ total data)",
                        zeroRecords: "Tidak ada data yang cocok",
                        paginate: {
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
