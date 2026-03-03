<x-layout>
    <x-aside>
        {{-- Top Navbar --}}
        <div class="navbar bg-base-100 border-b border-base-300 px-4 lg:px-6">
            <label for="sidebar-toggle" class="btn btn-ghost btn-sm btn-square lg:hidden">
                <i class="bi bi-list text-lg"></i>
            </label>
            <div class="flex-1 ml-2">
                <span class="font-bold text-lg">Log Suara</span>
            </div>
            <span class="text-sm text-base-content/60 hidden sm:inline">Report</span>
        </div>

        {{-- Content --}}
        <div class="p-4 lg:p-6">

            {{-- Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body p-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="bg-primary/10 text-primary rounded-lg w-10 h-10 flex items-center justify-center">
                                <i class="bi bi-check2-all text-xl"></i>
                            </div>
                            <div>
                                <div class="text-2xl font-bold">{{ $totalVotes }}</div>
                                <div class="text-xs text-base-content/50">Total Suara Masuk</div>
                            </div>
                        </div>
                    </div>
                </div>
                @foreach ($votesPerEvent as $ep)
                    <div class="card bg-base-100 shadow-sm">
                        <div class="card-body p-4">
                            <div class="flex items-center gap-3">
                                <div class="bg-info/10 text-info rounded-lg w-10 h-10 flex items-center justify-center">
                                    <i class="bi bi-bar-chart text-xl"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-2xl font-bold">{{ $ep->votes_count }}</div>
                                    <div class="text-xs text-base-content/50 truncate">{{ $ep->name }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Filter --}}
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold">Riwayat Suara</h2>
                <form method="GET" action="{{ route('admin.voteLog.index') }}">
                    <select name="event_id" onchange="this.form.submit()" class="select select-sm w-52">
                        <option value="">Semua Event</option>
                        @foreach ($events as $event)
                            <option value="{{ $event->id }}" {{ $selectedEvent == $event->id ? 'selected' : '' }}>
                                {{ $event->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            {{-- Table --}}
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-0">
                    <div class="overflow-x-auto">
                        <table id="voteLogTable" class="table">
                            <thead>
                                <tr class="bg-base-200/50">
                                    <th class="w-12">#</th>
                                    <th>Waktu</th>
                                    <th>Event</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($logs as $i => $log)
                                    <tr class="hover:bg-base-200/30">
                                        <td class="text-base-content/50">{{ $i + 1 }}</td>
                                        <td class="text-sm">
                                            {{ $log->created_at->format('d M Y H:i:s') }}
                                        </td>
                                        <td>
                                            <span
                                                class="badge badge-info badge-sm badge-outline">{{ $log->event->name }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-base-content/40 py-8">
                                            Belum ada suara yang masuk.
                                        </td>
                                    </tr>
                                @endforelse
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
                $('#voteLogTable').DataTable({
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
                    pageLength: 25,
                    order: [
                        [1, 'desc']
                    ]
                });
            });
        </script>
    @endpush
</x-layout>
