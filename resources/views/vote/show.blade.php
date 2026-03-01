<x-layout>
    <x-aside>
        {{-- Top Navbar --}}
        <div class="navbar bg-base-100 border-b border-base-300 px-4 lg:px-6">
            <label for="sidebar-toggle" class="btn btn-ghost btn-sm btn-square lg:hidden">
                <i class="bi bi-list text-lg"></i>
            </label>
            <div class="flex-1 ml-2">
                <span class="font-bold text-lg">{{ $event->name }}</span>
            </div>
            <a href="{{ route('vote.index') }}" class="btn btn-ghost btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        {{-- Content --}}
        <div class="p-4 lg:p-6">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div role="alert" class="alert alert-success alert-vertical sm:alert-horizontal mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        class="stroke-current h-6 w-6 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                    <button class="btn btn-sm btn-ghost" onclick="this.parentElement.remove()">✕</button>
                </div>
            @endif
            @if (session('error'))
                <div role="alert" class="alert alert-error alert-vertical sm:alert-horizontal mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        class="stroke-current h-6 w-6 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    <span>{{ session('error') }}</span>
                    <button class="btn btn-sm btn-ghost" onclick="this.parentElement.remove()">✕</button>
                </div>
            @endif

            {{-- Already Voted --}}
            @if ($hasVoted)
                <div class="card bg-base-100 shadow-sm mb-6">
                    <div class="card-body items-center text-center py-8">
                        <div class="w-16 h-16 rounded-full bg-success/10 flex items-center justify-center mb-3">
                            <i class="bi bi-check-circle-fill text-success text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-success">Anda Sudah Memilih!</h3>
                        <p class="text-base-content/60 text-sm">Terima kasih telah berpartisipasi dalam pemilihan ini.
                        </p>
                    </div>
                </div>

                {{-- Results --}}
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <i class="bi bi-bar-chart"></i> Hasil Sementara
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                    @php $totalVotes = $results->sum('votes_count'); @endphp
                    @foreach ($results as $candidate)
                        @php
                            $percentage = $totalVotes > 0 ? round(($candidate->votes_count / $totalVotes) * 100, 1) : 0;
                            $isUserChoice = $userVote && $userVote->candidate_id === $candidate->id;
                        @endphp
                        <div class="card bg-base-100 shadow-sm {{ $isUserChoice ? 'border-l-4 border-primary' : '' }}">
                            <div class="card-body p-4">
                                <div class="flex items-center gap-3 mb-3">
                                    @if ($candidate->photo)
                                        <div class="avatar">
                                            <div class="w-11 rounded-full">
                                                <img src="{{ asset('storage/' . $candidate->photo) }}" alt="Foto" />
                                            </div>
                                        </div>
                                    @else
                                        <div class="avatar placeholder">
                                            <div class="bg-base-300 text-base-content/40 w-11 rounded-full">
                                                <i class="bi bi-person text-xl"></i>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <div class="font-bold text-sm flex items-center gap-1">
                                            {{ $candidate->name }}
                                            @if ($isUserChoice)
                                                <i class="bi bi-star-fill text-warning text-xs"
                                                    title="Pilihan Anda"></i>
                                            @endif
                                        </div>
                                        <div class="text-xs text-base-content/50">No. Urut
                                            {{ $candidate->candidate_number }}</div>
                                    </div>
                                </div>
                                <progress
                                    class="progress {{ $isUserChoice ? 'progress-primary' : 'progress-accent' }} w-full"
                                    value="{{ $percentage }}" max="100"></progress>
                                <div class="flex justify-between text-xs text-base-content/50 mt-1">
                                    <span>{{ $candidate->votes_count }} suara</span>
                                    <span class="font-semibold">{{ $percentage }}%</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Voting Area --}}
                <div class="mb-5">
                    <h3 class="text-lg font-bold mb-1">Pilih Kandidat</h3>
                    <p class="text-sm text-base-content/60">Klik tombol <strong>Vote</strong> pada kandidat pilihan
                        Anda. Suara tidak dapat diubah setelah dikirim.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($event->candidates as $candidate)
                        <div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow h-full">
                            {{-- Photo --}}
                            @if ($candidate->photo)
                                <figure class="bg-base-200 h-56 overflow-hidden">
                                    <img src="{{ asset('storage/' . $candidate->photo) }}"
                                        alt="Foto {{ $candidate->name }}"
                                        class="w-full h-full object-cover object-center" />
                                </figure>
                            @else
                                <figure class="bg-base-200 h-56 flex items-center justify-center">
                                    <i class="bi bi-person text-base-content/20 text-7xl"></i>
                                </figure>
                            @endif

                            <div class="card-body p-4 flex flex-col">
                                <div class="flex items-center gap-2 mb-1">
                                    <span
                                        class="badge badge-primary font-bold">{{ $candidate->candidate_number }}</span>
                                    <h3 class="font-bold">{{ $candidate->name }}</h3>
                                </div>

                                @if ($candidate->vision || $candidate->mission)
                                    <div class="space-y-2 my-2">
                                        @if ($candidate->vision)
                                            <div>
                                                <div
                                                    class="text-xs font-semibold text-base-content/50 uppercase tracking-wide mb-0.5">
                                                    Visi</div>
                                                <p class="text-sm whitespace-pre-line line-clamp-3">
                                                    {{ $candidate->vision }}</p>
                                            </div>
                                        @endif
                                        @if ($candidate->mission)
                                            <div>
                                                <div
                                                    class="text-xs font-semibold text-base-content/50 uppercase tracking-wide mb-0.5">
                                                    Misi</div>
                                                <p class="text-sm whitespace-pre-line line-clamp-4">
                                                    {{ $candidate->mission }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <div class="card-actions mt-auto pt-2 flex gap-2">
                                    <a href="{{ route('vote.candidate', [$event->id, $candidate->id]) }}"
                                        class="btn btn-ghost btn-sm flex-1">
                                        <i class="bi bi-info-circle"></i> Detail
                                    </a>
                                    <form method="POST" action="{{ route('vote.store', $event->id) }}"
                                        onsubmit="event.preventDefault(); showConfirm(this, 'Apakah Anda yakin memilih {{ $candidate->name }} (No. {{ $candidate->candidate_number }})? Pilihan tidak dapat diubah.', 'Konfirmasi Vote')"
                                        class="flex-1">
                                        @csrf
                                        <input type="hidden" name="candidate_id" value="{{ $candidate->id }}">
                                        <button type="submit" class="btn btn-primary btn-sm w-full">
                                            <i class="bi bi-check2-circle"></i> Vote
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </x-aside>
</x-layout>
