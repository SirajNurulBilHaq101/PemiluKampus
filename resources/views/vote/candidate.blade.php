<x-layout>
    <x-aside>
        {{-- Top Navbar --}}
        <div class="navbar bg-base-100 border-b border-base-300 px-4 lg:px-6">
            <label for="sidebar-toggle" class="btn btn-ghost btn-sm btn-square lg:hidden">
                <i class="bi bi-list text-lg"></i>
            </label>
            <div class="flex-1 ml-2">
                <span class="font-bold text-lg">Detail Kandidat</span>
            </div>
            <a href="{{ route('vote.show', $event->id) }}" class="btn btn-ghost btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        {{-- Content --}}
        <div class="p-4 lg:p-6">
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-0">
                    <div class="flex flex-col lg:flex-row">

                        {{-- Left: Photo --}}
                        <div
                            class="lg:w-2/5 bg-base-200 flex items-center justify-center rounded-t-2xl lg:rounded-t-none lg:rounded-l-2xl overflow-hidden">
                            @if ($candidate->photo)
                                <img src="{{ asset('storage/' . $candidate->photo) }}" alt="Foto {{ $candidate->name }}"
                                    class="w-full h-72 lg:h-full object-cover object-center" />
                            @else
                                <div class="py-20">
                                    <i class="bi bi-person text-base-content/15 text-9xl"></i>
                                </div>
                            @endif
                        </div>

                        {{-- Right: Info --}}
                        <div class="lg:w-3/5 p-6 flex flex-col">
                            {{-- Header --}}
                            <div class="flex items-center gap-3 mb-4">
                                <span
                                    class="badge badge-primary badge-lg font-bold text-lg">{{ $candidate->candidate_number }}</span>
                                <div>
                                    <h2 class="text-xl font-bold">{{ $candidate->name }}</h2>
                                    <span class="text-sm text-base-content/50">{{ $event->name }}</span>
                                </div>
                            </div>

                            <div class="divider my-0"></div>

                            {{-- Visi --}}
                            @if ($candidate->vision)
                                <div class="my-4">
                                    <h3 class="font-bold text-base mb-2 flex items-center gap-2">
                                        Visi
                                    </h3>
                                    <p class="text-sm whitespace-pre-line leading-relaxed">{{ $candidate->vision }}</p>
                                </div>
                            @endif

                            {{-- Misi --}}
                            @if ($candidate->mission)
                                <div class="my-4">
                                    <h3 class="font-bold text-base mb-2 flex items-center gap-2">
                                        Misi
                                    </h3>
                                    <p class="text-sm whitespace-pre-line leading-relaxed">{{ $candidate->mission }}</p>
                                </div>
                            @endif

                            {{-- Action --}}
                            @if (!$hasVoted)
                                <div class="divider my-0"></div>
                                <div class="mt-4">
                                    <form method="POST" action="{{ route('vote.store', $event->id) }}"
                                        onsubmit="event.preventDefault(); showConfirm(this, 'Apakah Anda yakin memilih {{ $candidate->name }} (No. {{ $candidate->candidate_number }})? Pilihan tidak dapat diubah.', 'Konfirmasi Vote')">
                                        @csrf
                                        <input type="hidden" name="candidate_id" value="{{ $candidate->id }}">
                                        <button type="submit" class="btn btn-primary w-full lg:w-auto">
                                            <i class="bi bi-check2-circle"></i> Vote {{ $candidate->name }}
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </x-aside>
</x-layout>
