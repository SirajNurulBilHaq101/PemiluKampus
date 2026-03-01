<x-layout>
    <div class="d-flex">
        <x-aside />

        <div class="flex-grow-1 bg-light min-vh-100">
            {{-- Top Navbar --}}
            <nav class="navbar navbar-light bg-white border-bottom px-4 py-3">
                <div class="d-lg-none" style="width: 40px;"></div>
                <span class="navbar-text fw-semibold fs-5">{{ $event->name }}</span>
                <a href="{{ route('vote.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </nav>

            {{-- Content --}}
            <div class="p-3 p-md-4">

                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Already Voted --}}
                @if($hasVoted)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body text-center py-4">
                            <div class="mb-3">
                                <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 72px; height: 72px;">
                                    <i class="bi bi-check-circle-fill text-success" style="font-size: 2.5rem;"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold text-success">Anda Sudah Memilih!</h5>
                            <p class="text-muted mb-0">Terima kasih telah berpartisipasi dalam pemilihan ini.</p>
                        </div>
                    </div>

                    {{-- Results --}}
                    <h5 class="fw-bold mb-3"><i class="bi bi-bar-chart me-2"></i>Hasil Sementara</h5>
                    <div class="row g-3 mb-4">
                        @php
                            $totalVotes = $results->sum('votes_count');
                        @endphp
                        @foreach ($results as $candidate)
                            @php
                                $percentage = $totalVotes > 0 ? round(($candidate->votes_count / $totalVotes) * 100, 1) : 0;
                                $isUserChoice = $userVote && $userVote->candidate_id === $candidate->id;
                            @endphp
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card border-0 shadow-sm h-100 {{ $isUserChoice ? 'border-start border-4 border-primary' : '' }}">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            @if($candidate->photo)
                                                <img src="{{ asset('storage/' . $candidate->photo) }}" alt="Foto" class="rounded-circle me-3" style="width: 48px; height: 48px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                                    <i class="bi bi-person text-secondary fs-4"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="fw-bold mb-0">
                                                    {{ $candidate->name }}
                                                    @if($isUserChoice)
                                                        <i class="bi bi-star-fill text-warning ms-1" title="Pilihan Anda"></i>
                                                    @endif
                                                </h6>
                                                <small class="text-muted">No. Urut {{ $candidate->candidate_number }}</small>
                                            </div>
                                        </div>
                                        <div class="progress mb-2" style="height: 8px;">
                                            <div class="progress-bar {{ $isUserChoice ? 'bg-primary' : 'bg-secondary' }}" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <div class="d-flex justify-content-between small text-muted">
                                            <span>{{ $candidate->votes_count }} suara</span>
                                            <span class="fw-semibold">{{ $percentage }}%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                @else
                    {{-- Voting Area --}}
                    <div class="mb-4">
                        <h5 class="fw-bold mb-1">Pilih Kandidat</h5>
                        <p class="text-muted">Klik tombol <strong>Vote</strong> pada kandidat pilihan Anda. Suara tidak dapat diubah setelah dikirim.</p>
                    </div>

                    <div class="row g-3">
                        @foreach ($event->candidates as $candidate)
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card border-0 shadow-sm h-100">
                                    {{-- Photo --}}
                                    @if($candidate->photo)
                                        <img src="{{ asset('storage/' . $candidate->photo) }}" class="card-img-top" alt="Foto {{ $candidate->name }}" style="height: 220px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center" style="height: 220px;">
                                            <i class="bi bi-person text-secondary" style="font-size: 4rem;"></i>
                                        </div>
                                    @endif

                                    <div class="card-body d-flex flex-column">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-primary rounded-pill me-2 fs-6">{{ $candidate->candidate_number }}</span>
                                            <h5 class="fw-bold mb-0">{{ $candidate->name }}</h5>
                                        </div>

                                        {{-- Vision & Mission --}}
                                        @if($candidate->vision || $candidate->mission)
                                            <div class="mb-3">
                                                @if($candidate->vision)
                                                    <div class="mb-2">
                                                        <strong class="small text-muted d-block mb-1">Visi</strong>
                                                        <p class="small mb-0">{{ $candidate->vision }}</p>
                                                    </div>
                                                @endif
                                                @if($candidate->mission)
                                                    <div>
                                                        <strong class="small text-muted d-block mb-1">Misi</strong>
                                                        <p class="small mb-0">{{ $candidate->mission }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        {{-- Vote Button --}}
                                        <div class="mt-auto">
                                            <form method="POST" action="{{ route('vote.store', $event->id) }}" onsubmit="return confirm('Apakah Anda yakin memilih {{ $candidate->name }} (No. {{ $candidate->candidate_number }})? Pilihan tidak dapat diubah.')">
                                                @csrf
                                                <input type="hidden" name="candidate_id" value="{{ $candidate->id }}">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="bi bi-check2-circle me-1"></i> Vote
                                                </button>
                                            </form>
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
