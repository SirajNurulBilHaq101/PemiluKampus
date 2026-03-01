<x-layout>
    <x-aside>
        {{-- Top Navbar --}}
        <div class="navbar bg-base-100 border-b border-base-300 px-4 lg:px-6">
            <label for="sidebar-toggle" class="btn btn-ghost btn-sm btn-square lg:hidden">
                <i class="bi bi-list text-lg"></i>
            </label>
            <div class="flex-1 ml-2">
                <span class="font-bold text-lg">Pemilihan</span>
            </div>
            <span class="text-sm text-base-content/60 hidden sm:inline">Pilih event untuk memberikan suara</span>
        </div>

        {{-- Content --}}
        <div class="p-4 lg:p-6">
            @if ($events->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 text-base-content/40">
                    <i class="bi bi-calendar-x text-6xl mb-4"></i>
                    <h3 class="text-lg font-bold mb-1">Tidak Ada Event Aktif</h3>
                    <p class="text-sm">Saat ini belum ada event pemilihan yang sedang berlangsung.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($events as $event)
                        <div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow">
                            <div class="card-body">
                                <div class="flex items-start gap-3 mb-2">
                                    <div
                                        class="bg-primary/10 text-primary rounded-lg w-11 h-11 flex items-center justify-center shrink-0">
                                        <i class="bi bi-clipboard2-check text-xl"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="font-bold text-base">{{ $event->name }}</h3>
                                        <span class="badge badge-success badge-sm">Aktif</span>
                                    </div>
                                </div>

                                @if ($event->description)
                                    <p class="text-sm text-base-content/60">{{ Str::limit($event->description, 100) }}
                                    </p>
                                @endif

                                <div class="flex items-center justify-between text-xs text-base-content/50 mt-2">
                                    <span><i class="bi bi-people mr-1"></i>{{ $event->candidates_count }}
                                        Kandidat</span>
                                    <span><i
                                            class="bi bi-calendar mr-1"></i>{{ $event->end_date->format('d M Y') }}</span>
                                </div>

                                <div class="card-actions mt-3">
                                    <a href="{{ route('vote.show', $event->id) }}"
                                        class="btn btn-primary btn-sm w-full">
                                        <i class="bi bi-box-arrow-in-right"></i> Masuk & Vote
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </x-aside>
</x-layout>
