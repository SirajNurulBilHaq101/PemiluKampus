<x-layout>
    <x-aside>
        {{-- Top Navbar --}}
        <div class="navbar bg-base-100 border-b border-base-300 px-4 lg:px-6">
            <label for="sidebar-toggle" class="btn btn-ghost btn-sm btn-square lg:hidden">
                <i class="bi bi-list text-lg"></i>
            </label>
            <div class="flex-1 ml-2">
                <span class="font-bold text-lg">Dashboard</span>
            </div>
            <span class="text-sm text-base-content/60 hidden sm:inline">Selamat datang, {{ Auth::user()->name }}!</span>
        </div>

        {{-- Content --}}
        <div class="p-4 lg:p-6">
            @if ($activeEvents->isNotEmpty())
                <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <i class="bi bi-bar-chart-line"></i>
                    Quick Count — Event Berlangsung
                </h2>

                @foreach ($quickCounts as $eventId => $data)
                    @php
                        $event = $data['event'];
                        $results = $data['results'];
                        $totalVotes = $data['totalVotes'];
                        $hasVoted = $data['hasVoted'];
                    @endphp

                    <div class="card bg-base-100 shadow-sm mb-5">
                        <div class="card-body p-0">
                            {{-- Header --}}
                            <div class="flex items-center justify-between px-5 py-4 border-b border-base-300">
                                <div class="min-w-0">
                                    <h3 class="font-bold text-base">{{ $event->name }}</h3>
                                    @if ($event->description)
                                        <p class="text-sm text-base-content/50 truncate">
                                            {{ Str::limit($event->description, 80) }}</p>
                                    @endif
                                </div>
                                <div class="text-right shrink-0 ml-4">
                                    <div class="text-xl font-bold text-primary">{{ $totalVotes }}</div>
                                    <div class="text-xs text-base-content/50">Total Suara</div>
                                </div>
                            </div>

                            {{-- Charts --}}
                            <div class="p-5">
                                @if ($results->isEmpty())
                                    <p class="text-center text-base-content/50 py-4">Belum ada kandidat untuk event ini.
                                    </p>
                                @else
                                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                                        <div class="lg:col-span-7">
                                            <canvas id="barChart{{ $eventId }}" height="250"></canvas>
                                        </div>
                                        <div class="lg:col-span-5">
                                            <canvas id="doughnutChart{{ $eventId }}" height="250"></canvas>
                                        </div>
                                    </div>
                                @endif

                                {{-- Action (Mahasiswa only) --}}
                                @if (Auth::user()->role === 'mahasiswa')
                                    <div class="flex items-center justify-end gap-2 mt-4 pt-4 border-t border-base-300">
                                        @if ($hasVoted)
                                            <span class="badge badge-success gap-1">
                                                <i class="bi bi-check-circle"></i> Sudah Memilih
                                            </span>
                                        @endif
                                        <a href="{{ route('vote.show', $event->id) }}"
                                            class="btn btn-primary btn-sm btn-outline">
                                            <i class="bi bi-box-arrow-in-right"></i>
                                            {{ $hasVoted ? 'Lihat Detail' : 'Vote Sekarang' }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                {{-- No active events --}}
                <div class="card bg-base-100 shadow-sm mt-4">
                    <div class="card-body">
                        <h3 class="card-title gap-2">
                            <i class="bi bi-emoji-smile"></i>
                            Halo, {{ Auth::user()->name }}!
                        </h3>
                        <p class="text-base-content/60">
                            Anda login sebagai <strong>{{ ucfirst(Auth::user()->role) }}</strong>. Saat ini belum ada
                            event pemilihan yang sedang berlangsung.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </x-aside>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
        <script>
            const chartColors = [
                '#6366f1', '#22c55e', '#f59e0b', '#ef4444', '#06b6d4',
                '#8b5cf6', '#f97316', '#14b8a6', '#a855f7', '#ec4899'
            ];

            @foreach ($quickCounts as $eventId => $data)
                @if ($data['results']->isNotEmpty())
                    (function() {
                        const labels = {!! json_encode($data['results']->map(fn($c) => 'No. ' . $c->candidate_number . ' - ' . $c->name)->values()) !!};
                        const votes = {!! json_encode($data['results']->pluck('votes_count')->values()) !!};
                        const colors = labels.map((_, i) => chartColors[i % chartColors.length]);

                        new Chart(document.getElementById('barChart{{ $eventId }}'), {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Jumlah Suara',
                                    data: votes,
                                    backgroundColor: colors,
                                    borderColor: colors,
                                    borderWidth: 1,
                                    borderRadius: 6,
                                    barPercentage: 0.6,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(ctx) {
                                                const total = votes.reduce((a, b) => a + b, 0);
                                                const pct = total > 0 ? ((ctx.raw / total) * 100).toFixed(1) :
                                                0;
                                                return ctx.raw + ' suara (' + pct + '%)';
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            precision: 0
                                        },
                                        grid: {
                                            color: 'rgba(0,0,0,0.04)'
                                        }
                                    },
                                    x: {
                                        grid: {
                                            display: false
                                        },
                                        ticks: {
                                            font: {
                                                size: 11
                                            },
                                            maxRotation: 30
                                        }
                                    }
                                }
                            }
                        });

                        new Chart(document.getElementById('doughnutChart{{ $eventId }}'), {
                            type: 'doughnut',
                            data: {
                                labels: labels,
                                datasets: [{
                                    data: votes,
                                    backgroundColor: colors,
                                    borderWidth: 2,
                                    hoverOffset: 8,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            padding: 12,
                                            usePointStyle: true,
                                            pointStyle: 'circle',
                                            font: {
                                                size: 11
                                            }
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(ctx) {
                                                const total = votes.reduce((a, b) => a + b, 0);
                                                const pct = total > 0 ? ((ctx.raw / total) * 100).toFixed(1) :
                                                0;
                                                return ctx.label + ': ' + ctx.raw + ' suara (' + pct + '%)';
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    })();
                @endif
            @endforeach
        </script>
    @endpush
</x-layout>
