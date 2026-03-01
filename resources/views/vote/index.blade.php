<x-layout>
    <div class="d-flex">
        <x-aside />

        <div class="flex-grow-1 bg-light min-vh-100">
            {{-- Top Navbar --}}
            <nav class="navbar navbar-light bg-white border-bottom px-4 py-3">
                <div class="d-lg-none" style="width: 40px;"></div>
                <span class="navbar-text fw-semibold fs-5">Pemilihan</span>
                <span class="text-muted small d-none d-sm-inline">Pilih event untuk memberikan suara</span>
            </nav>

            {{-- Content --}}
            <div class="p-3 p-md-4">

                @if($events->isEmpty())
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-calendar-x text-muted" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="fw-bold text-muted">Tidak Ada Event Aktif</h5>
                        <p class="text-muted">Saat ini belum ada event pemilihan yang sedang berlangsung.</p>
                    </div>
                @else
                    <div class="row g-3">
                        @foreach ($events as $event)
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body d-flex flex-column">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px;">
                                                <i class="bi bi-clipboard2-check fs-4"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-1">{{ $event->name }}</h6>
                                                <span class="badge bg-success">Aktif</span>
                                            </div>
                                        </div>

                                        @if($event->description)
                                            <p class="text-muted small mb-3">{{ Str::limit($event->description, 100) }}</p>
                                        @endif

                                        <div class="mt-auto">
                                            <div class="d-flex justify-content-between text-muted small mb-3">
                                                <span><i class="bi bi-people me-1"></i> {{ $event->candidates_count }} Kandidat</span>
                                                <span><i class="bi bi-calendar me-1"></i> {{ $event->end_date->format('d M Y') }}</span>
                                            </div>
                                            <a href="{{ route('vote.show', $event->id) }}" class="btn btn-primary w-100">
                                                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk & Vote
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-layout>
