<x-layout>
    <x-aside>
        {{-- Top Navbar --}}
        <div class="navbar bg-base-100 border-b border-base-300 px-4 lg:px-6">
            <label for="sidebar-toggle" class="btn btn-ghost btn-sm btn-square lg:hidden">
                <i class="bi bi-list text-lg"></i>
            </label>
            <div class="flex-1 ml-2">
                <span class="font-bold text-lg">Kelola Event</span>
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
                <h2 class="text-lg font-bold">Daftar Event</h2>
                <button class="btn btn-primary btn-sm" onclick="createEventModal.showModal()">
                    <i class="bi bi-plus-lg"></i> Tambah Event
                </button>
            </div>

            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-0">
                    <div class="overflow-x-auto">
                        <table id="eventTable" class="table">
                            <thead>
                                <tr class="bg-base-200/50">
                                    <th class="w-12">#</th>
                                    <th>Nama Event</th>
                                    <th>Mulai</th>
                                    <th>Selesai</th>
                                    <th>Status</th>
                                    <th>Dibuat Oleh</th>
                                    <th class="w-28">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($events as $i => $event)
                                    <tr class="hover:bg-base-200/30">
                                        <td class="text-base-content/50">{{ $i + 1 }}</td>
                                        <td>
                                            <span class="font-semibold text-sm">{{ $event->name }}</span>
                                            @if ($event->description)
                                                <br><span
                                                    class="text-xs text-base-content/50">{{ Str::limit($event->description, 50) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-sm">{{ $event->start_date->format('d M Y H:i') }}</td>
                                        <td class="text-sm">{{ $event->end_date->format('d M Y H:i') }}</td>
                                        <td>
                                            @php
                                                $statusBadge = match ($event->status) {
                                                    'upcoming' => 'badge-info',
                                                    'active' => 'badge-success',
                                                    'completed' => 'badge-ghost',
                                                    'cancelled' => 'badge-error',
                                                    default => 'badge-ghost',
                                                };
                                            @endphp
                                            <span
                                                class="badge {{ $statusBadge }} badge-sm">{{ ucfirst($event->status) }}</span>
                                        </td>
                                        <td class="text-base-content/50 text-sm">{{ $event->creator->name }}</td>
                                        <td>
                                            <div class="flex gap-1">
                                                <button class="btn btn-warning btn-xs btn-outline"
                                                    onclick="editEventModal{{ $event->id }}.showModal()">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form method="POST"
                                                    action="{{ route('admin.masterData.event.destroy', $event->id) }}"
                                                    onsubmit="return confirm('Yakin hapus event ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-error btn-xs btn-outline"><i
                                                            class="bi bi-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Edit Modal --}}
                                    <dialog id="editEventModal{{ $event->id }}" class="modal">
                                        <div class="modal-box">
                                            <form method="dialog"><button
                                                    class="btn btn-sm btn-circle btn-ghost absolute right-3 top-3">✕</button>
                                            </form>
                                            <h3 class="font-bold text-lg mb-4">Edit Event</h3>
                                            <form method="POST"
                                                action="{{ route('admin.masterData.event.update', $event->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <fieldset class="fieldset mb-3">
                                                    <legend class="fieldset-legend text-sm">Nama Event</legend>
                                                    <input type="text" class="input w-full" name="name"
                                                        value="{{ $event->name }}" required>
                                                </fieldset>
                                                <fieldset class="fieldset mb-3">
                                                    <legend class="fieldset-legend text-sm">Deskripsi</legend>
                                                    <textarea class="textarea w-full" name="description" rows="3">{{ $event->description }}</textarea>
                                                </fieldset>
                                                <div class="grid grid-cols-2 gap-3">
                                                    <fieldset class="fieldset mb-3">
                                                        <legend class="fieldset-legend text-sm">Mulai</legend>
                                                        <input type="datetime-local" class="input w-full"
                                                            name="start_date"
                                                            value="{{ $event->start_date->format('Y-m-d\TH:i') }}"
                                                            required>
                                                    </fieldset>
                                                    <fieldset class="fieldset mb-3">
                                                        <legend class="fieldset-legend text-sm">Selesai</legend>
                                                        <input type="datetime-local" class="input w-full"
                                                            name="end_date"
                                                            value="{{ $event->end_date->format('Y-m-d\TH:i') }}"
                                                            required>
                                                    </fieldset>
                                                </div>
                                                <fieldset class="fieldset mb-4">
                                                    <legend class="fieldset-legend text-sm">Status</legend>
                                                    <select class="select w-full" name="status" required>
                                                        @foreach (['upcoming', 'active', 'completed', 'cancelled'] as $s)
                                                            <option value="{{ $s }}"
                                                                {{ $event->status === $s ? 'selected' : '' }}>
                                                                {{ ucfirst($s) }}</option>
                                                        @endforeach
                                                    </select>
                                                </fieldset>
                                                <div class="flex justify-end gap-2">
                                                    <button type="button" class="btn btn-ghost btn-sm"
                                                        onclick="editEventModal{{ $event->id }}.close()">Batal</button>
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
    <dialog id="createEventModal" class="modal">
        <div class="modal-box">
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost absolute right-3 top-3">✕</button>
            </form>
            <h3 class="font-bold text-lg mb-4">Tambah Event</h3>
            <form method="POST" action="{{ route('admin.masterData.event.store') }}">
                @csrf
                <fieldset class="fieldset mb-3">
                    <legend class="fieldset-legend text-sm">Nama Event</legend>
                    <input type="text" class="input w-full" name="name" placeholder="Nama event..." required>
                </fieldset>
                <fieldset class="fieldset mb-3">
                    <legend class="fieldset-legend text-sm">Deskripsi</legend>
                    <textarea class="textarea w-full" name="description" rows="3" placeholder="Deskripsi event (opsional)"></textarea>
                </fieldset>
                <div class="grid grid-cols-2 gap-3">
                    <fieldset class="fieldset mb-3">
                        <legend class="fieldset-legend text-sm">Mulai</legend>
                        <input type="datetime-local" class="input w-full" name="start_date" required>
                    </fieldset>
                    <fieldset class="fieldset mb-3">
                        <legend class="fieldset-legend text-sm">Selesai</legend>
                        <input type="datetime-local" class="input w-full" name="end_date" required>
                    </fieldset>
                </div>
                <fieldset class="fieldset mb-4">
                    <legend class="fieldset-legend text-sm">Status</legend>
                    <select class="select w-full" name="status" required>
                        <option value="upcoming" selected>Upcoming</option>
                        <option value="active">Active</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </fieldset>
                <div class="flex justify-end gap-2">
                    <button type="button" class="btn btn-ghost btn-sm"
                        onclick="createEventModal.close()">Batal</button>
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
                $('#eventTable').DataTable({
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
