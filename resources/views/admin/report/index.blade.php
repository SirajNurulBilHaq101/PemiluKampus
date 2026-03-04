<x-layout>
    <x-aside>
        {{-- Top Navbar --}}
        <div class="navbar bg-base-100 border-b border-base-300 px-4 lg:px-6">
            <label for="sidebar-toggle" class="btn btn-ghost btn-sm btn-square lg:hidden">
                <i class="bi bi-list text-lg"></i>
            </label>
            <div class="flex-1 ml-2">
                <span class="font-bold text-lg">Laporan Hasil</span>
            </div>
            <span class="text-sm text-base-content/60 hidden sm:inline">Report</span>
        </div>

        {{-- Content --}}
        <div class="p-4 lg:p-6">

            {{-- Event Selector --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-6">
                <h2 class="text-lg font-bold flex items-center gap-2">
                    <i class="bi bi-file-earmark-bar-graph"></i>
                    Laporan Hasil Pemilu
                </h2>
                @if ($events->isNotEmpty())
                    <form method="GET" action="{{ route('admin.report.index') }}">
                        <select name="event_id" onchange="this.form.submit()" class="select select-sm w-56">
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}"
                                    {{ $selectedEventId == $event->id ? 'selected' : '' }}>
                                    {{ $event->name }} ({{ $event->votes_count }} suara)
                                </option>
                            @endforeach
                        </select>
                    </form>
                @endif
            </div>

            @if ($report)
                @php
                    $event = $report['event'];
                    $candidates = $report['candidates'];
                    $winner = $report['winner'];
                    $totalVotes = $report['totalVotes'];
                    $totalEligible = $report['totalEligible'];
                    $participationRate = $report['participationRate'];
                    $facultyStats = $report['facultyStats'];
                @endphp

                {{-- Stat Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    {{-- Total Suara --}}
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

                    {{-- Total Eligible --}}
                    <div class="card bg-base-100 shadow-sm">
                        <div class="card-body p-4">
                            <div class="flex items-center gap-3">
                                <div class="bg-info/10 text-info rounded-lg w-10 h-10 flex items-center justify-center">
                                    <i class="bi bi-people text-xl"></i>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold">{{ $totalEligible }}</div>
                                    <div class="text-xs text-base-content/50">Pemilih Terdaftar</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Partisipasi --}}
                    <div class="card bg-base-100 shadow-sm">
                        <div class="card-body p-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="bg-success/10 text-success rounded-lg w-10 h-10 flex items-center justify-center">
                                    <i class="bi bi-graph-up text-xl"></i>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold">{{ $participationRate }}%</div>
                                    <div class="text-xs text-base-content/50">Tingkat Partisipasi</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Pemenang --}}
                    <div class="card bg-base-100 shadow-sm">
                        <div class="card-body p-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="bg-warning/10 text-warning rounded-lg w-10 h-10 flex items-center justify-center">
                                    <i class="bi bi-trophy text-xl"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-lg font-bold truncate">
                                        {{ $winner ? $winner->name : '-' }}</div>
                                    <div class="text-xs text-base-content/50">
                                        {{ $winner ? 'No. ' . $winner->candidate_number . ' — ' . $winner->votes_count . ' suara (' . $winner->percentage . '%)' : 'Belum ada suara' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Event Info --}}
                <div class="card bg-base-100 shadow-sm mb-6">
                    <div class="card-body p-4">
                        <div class="flex flex-wrap items-center gap-3 text-sm">
                            <span class="badge {{ $event->status === 'active' ? 'badge-success' : 'badge-neutral' }}">
                                {{ ucfirst($event->status) }}
                            </span>
                            @if ($event->start_date && $event->end_date)
                                <span class="text-base-content/60">
                                    <i class="bi bi-calendar3"></i>
                                    {{ $event->start_date->format('d M Y') }} —
                                    {{ $event->end_date->format('d M Y') }}
                                </span>
                            @endif
                            @if ($event->description)
                                <span class="text-base-content/60">
                                    {{ $event->description }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Charts --}}
                @if ($candidates->isNotEmpty())
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
                        <div class="lg:col-span-7">
                            <div class="card bg-base-100 shadow-sm">
                                <div class="card-body">
                                    <h3 class="font-bold text-sm mb-3">Perolehan Suara</h3>
                                    <div style="position: relative; height: 300px;">
                                        <canvas id="reportBarChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="lg:col-span-5">
                            <div class="card bg-base-100 shadow-sm">
                                <div class="card-body">
                                    <h3 class="font-bold text-sm mb-3">Distribusi Suara</h3>
                                    <div style="position: relative; height: 300px;">
                                        <canvas id="reportDoughnutChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Tabel Ranking Kandidat --}}
                <div class="card bg-base-100 shadow-sm mb-6">
                    <div class="card-body p-0">
                        <div class="px-5 py-4 border-b border-base-300">
                            <h3 class="font-bold">Ranking Kandidat</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="table">
                                <thead>
                                    <tr class="bg-base-200/50">
                                        <th class="w-16">Rank</th>
                                        <th>Kandidat</th>
                                        <th class="w-20 text-center">No. Urut</th>
                                        <th class="w-28 text-right">Suara</th>
                                        <th class="w-28 text-right">Persentase</th>
                                        <th class="w-40">Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($candidates->sortByDesc('votes_count')->values() as $i => $candidate)
                                        <tr
                                            class="hover:bg-base-200/30 {{ $i === 0 && $candidate->votes_count > 0 ? 'bg-warning/5' : '' }}">
                                            <td>
                                                @if ($i === 0 && $candidate->votes_count > 0)
                                                    <span class="badge badge-warning badge-sm gap-1">
                                                        <i class="bi bi-trophy-fill"></i> 1
                                                    </span>
                                                @else
                                                    <span class="text-base-content/50">{{ $i + 1 }}</span>
                                                @endif
                                            </td>
                                            <td class="font-medium">{{ $candidate->name }}</td>
                                            <td class="text-center">
                                                <span
                                                    class="badge badge-outline badge-sm">{{ $candidate->candidate_number }}</span>
                                            </td>
                                            <td class="text-right font-bold">{{ $candidate->votes_count }}</td>
                                            <td class="text-right">{{ $candidate->percentage }}%</td>
                                            <td>
                                                <progress
                                                    class="progress {{ $i === 0 && $candidate->votes_count > 0 ? 'progress-warning' : 'progress-primary' }} w-full"
                                                    value="{{ $candidate->percentage }}" max="100"></progress>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Partisipasi per Fakultas & Prodi --}}
                @if (!empty($facultyStats))
                    <div class="card bg-base-100 shadow-sm">
                        <div class="card-body p-0">
                            <div class="px-5 py-4 border-b border-base-300">
                                <h3 class="font-bold">Partisipasi per Fakultas & Program Studi</h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="table">
                                    <thead>
                                        <tr class="bg-base-200/50">
                                            <th>Fakultas / Program Studi</th>
                                            <th class="w-28 text-right">Terdaftar</th>
                                            <th class="w-28 text-right">Memilih</th>
                                            <th class="w-28 text-right">Partisipasi</th>
                                            <th class="w-40">Progress</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($facultyStats as $faculty)
                                            {{-- Faculty Row --}}
                                            <tr class="bg-base-200/30 font-semibold">
                                                <td>
                                                    <i class="bi bi-building text-primary mr-1"></i>
                                                    {{ $faculty['name'] }}
                                                </td>
                                                <td class="text-right">{{ $faculty['totalUsers'] }}</td>
                                                <td class="text-right">{{ $faculty['totalVoted'] }}</td>
                                                <td class="text-right">{{ $faculty['percentage'] }}%</td>
                                                <td>
                                                    <progress class="progress progress-primary w-full"
                                                        value="{{ $faculty['percentage'] }}"
                                                        max="100"></progress>
                                                </td>
                                            </tr>
                                            {{-- Prodi Rows --}}
                                            @foreach ($faculty['programs'] as $prodi)
                                                <tr class="hover:bg-base-200/20">
                                                    <td class="pl-10 text-sm">
                                                        <i class="bi bi-mortarboard text-base-content/40 mr-1"></i>
                                                        {{ $prodi['name'] }}
                                                    </td>
                                                    <td class="text-right text-sm">{{ $prodi['totalUsers'] }}</td>
                                                    <td class="text-right text-sm">{{ $prodi['totalVoted'] }}</td>
                                                    <td class="text-right text-sm">{{ $prodi['percentage'] }}%</td>
                                                    <td>
                                                        <progress class="progress progress-info progress-xs w-full"
                                                            value="{{ $prodi['percentage'] }}"
                                                            max="100"></progress>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                {{-- No events with votes --}}
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body text-center py-12">
                        <i class="bi bi-clipboard-x text-4xl text-base-content/30 mb-3"></i>
                        <h3 class="font-bold text-lg">Belum Ada Data</h3>
                        <p class="text-base-content/50">Belum ada event dengan suara yang masuk untuk ditampilkan.</p>
                    </div>
                </div>
            @endif
        </div>
    </x-aside>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
        <script>
            @if ($report && $report['candidates']->isNotEmpty())
                const chartColors = [
                    '#6366f1', '#22c55e', '#f59e0b', '#ef4444', '#06b6d4',
                    '#8b5cf6', '#f97316', '#14b8a6', '#a855f7', '#ec4899'
                ];

                const labels = {!! json_encode(
                    $report['candidates']->map(fn($c) => 'No. ' . $c->candidate_number . ' - ' . $c->name)->values(),
                ) !!};
                const votes = {!! json_encode($report['candidates']->pluck('votes_count')->values()) !!};
                const colors = labels.map((_, i) => chartColors[i % chartColors.length]);

                // Bar Chart
                new Chart(document.getElementById('reportBarChart'), {
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
                                        const pct = total > 0 ? ((ctx.raw / total) * 100).toFixed(1) : 0;
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

                // Doughnut Chart
                new Chart(document.getElementById('reportDoughnutChart'), {
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
                                        const pct = total > 0 ? ((ctx.raw / total) * 100).toFixed(1) : 0;
                                        return ctx.label + ': ' + ctx.raw + ' suara (' + pct + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            @endif
        </script>
    @endpush
</x-layout>
