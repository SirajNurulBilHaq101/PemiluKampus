<x-layout>
    <div class="d-flex">
        {{-- Sidebar --}}
        <x-aside />

        {{-- Main Content --}}
        <div class="flex-grow-1 bg-light min-vh-100">
            {{-- Top Navbar --}}
            <nav class="navbar navbar-light bg-white border-bottom px-4 py-3">
                <div class="d-lg-none" style="width: 40px;"></div>
                <span class="navbar-text fw-semibold fs-5">Dashboard</span>
                <span class="text-muted small d-none d-sm-inline">Selamat datang, {{ Auth::user()->name }}!</span>
            </nav>

            {{-- Content --}}
            <div class="p-3 p-md-4">
                {{-- Quick Count Section --}}
                @if($activeEvents->isNotEmpty())
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-bar-chart-line me-2"></i>Quick Count — Event Berlangsung
                    </h5>

                    @foreach ($quickCounts as $eventId => $data)
                        @php
                            $event = $data['event'];
                            $results = $data['results'];
                            $totalVotes = $data['totalVotes'];
                            $hasVoted = $data['hasVoted'];
                        @endphp

                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <h6 class="fw-bold mb-0">{{ $event->name }}</h6>
                                    @if($event->description)
                                        <small class="text-muted">{{ Str::limit($event->description, 80) }}</small>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary fs-5">{{ $totalVotes }}</div>
                                    <small class="text-muted">Total Suara</small>
                                </div>
                            </div>
                            <div class="card-body">
                                @if($results->isEmpty())
                                    <p class="text-muted text-center mb-0">Belum ada kandidat untuk event ini.</p>
                                @else
                                    <div class="row align-items-center">
                                        {{-- Bar Chart --}}
                                        <div class="col-12 col-lg-7 mb-3 mb-lg-0">
                                            <canvas id="barChart{{ $eventId }}" height="250"></canvas>
                                        </div>
                                        {{-- Doughnut Chart --}}
                                        <div class="col-12 col-lg-5">
                                            <canvas id="doughnutChart{{ $eventId }}" height="250"></canvas>
                                        </div>
                                    </div>
                                @endif

                                {{-- Action --}}
                                <div class="text-end mt-3 pt-2 border-top">
                                    @if($hasVoted)
                                        <span class="badge bg-success me-2"><i class="bi bi-check-circle me-1"></i>Sudah Memilih</span>
                                    @endif
                                    <a href="{{ route('vote.show', $event->id) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-box-arrow-in-right me-1"></i> {{ $hasVoted ? 'Lihat Detail' : 'Vote Sekarang' }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Welcome Card (no active events) --}}
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold">
                                <i class="bi bi-emoji-smile me-2"></i>Halo, {{ Auth::user()->name }}!
                            </h5>
                            <p class="card-text text-muted mb-0">
                                Anda login sebagai <strong>{{ ucfirst(Auth::user()->role) }}</strong>. Saat ini belum ada event pemilihan yang sedang berlangsung.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        const chartColors = [
            '#4e73df', '#1cc88a', '#f6c23e', '#e74a3b', '#36b9cc',
            '#6f42c1', '#fd7e14', '#20c997', '#6610f2', '#e83e8c'
        ];

        @foreach ($quickCounts as $eventId => $data)
            @if($data['results']->isNotEmpty())
                (function() {
                    const labels = {!! json_encode($data['results']->map(fn($c) => 'No. ' . $c->candidate_number . ' - ' . $c->name)->values()) !!};
                    const votes  = {!! json_encode($data['results']->pluck('votes_count')->values()) !!};
                    const colors = labels.map((_, i) => chartColors[i % chartColors.length]);

                    // Bar Chart
                    new Chart(document.getElementById('barChart{{ $eventId }}'), {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Jumlah Suara',
                                data: votes,
                                backgroundColor: colors,
                                borderColor: colors.map(c => c),
                                borderWidth: 1,
                                borderRadius: 6,
                                barPercentage: 0.6,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
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
                                    ticks: { precision: 0 },
                                    grid: { color: 'rgba(0,0,0,0.05)' }
                                },
                                x: {
                                    grid: { display: false },
                                    ticks: {
                                        font: { size: 11 },
                                        maxRotation: 30,
                                    }
                                }
                            }
                        }
                    });

                    // Doughnut Chart
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
                                        font: { size: 11 }
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
                })();
            @endif
        @endforeach
    </script>
    @endpush
</x-layout>
