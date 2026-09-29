@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="bento-grid">
        <div class="bento-card span-12">
            <div class="greeting">
                <div>
                    <h2>Selamat datang, {{ auth()->user()->name }}</h2>
                    <p>{{ indo_date(now()) }}. Berikut ringkasan performa website Anda hari ini.</p>
                </div>
            </div>
        </div>

        <div class="bento-card span-3">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-eye"></i></div>
                <div>
                    <div class="stat-value">{{ number_format($stats['pengunjung']['total'], 0, ',', '.') }}</div>
                    <div class="stat-label">Pengunjung Hari Ini</div>
                    <div class="stat-trend {{ $stats['pengunjung']['trend']['direction'] }}">
                        @if($stats['pengunjung']['trend']['direction'] !== 'flat')
                            <i class="fas fa-arrow-{{ $stats['pengunjung']['trend']['direction'] }}"></i>
                        @endif
                        {{ $stats['pengunjung']['trend']['percentage'] }}%
                    </div>
                </div>
            </div>
        </div>

        <div class="bento-card span-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-newspaper"></i></div>
                <div>
                    <div class="stat-value">{{ number_format($stats['berita_aktif']['total'], 0, ',', '.') }}</div>
                    <div class="stat-label">Total Berita Publish</div>
                    <div class="stat-trend {{ $stats['total_berita']['trend']['direction'] }}">
                        @if($stats['total_berita']['trend']['direction'] !== 'flat')
                            <i class="fas fa-arrow-{{ $stats['total_berita']['trend']['direction'] }}"></i>
                        @endif
                        {{ $stats['total_berita']['trend']['percentage'] }}%
                    </div>
                </div>
            </div>
        </div>

        <div class="bento-card span-3">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-tags"></i></div>
                <div>
                <div class="stat-value">{{ number_format($stats['total_inovasi']['total'], 0, ',', '.') }}</div>
                    <div class="stat-label">Total Inovasi</div>
                    <div class="stat-trend {{ $stats['total_inovasi']['trend']['direction'] }}">
                        @if($stats['total_inovasi']['trend']['direction'] !== 'flat')
                            <i class="fas fa-arrow-{{ $stats['total_inovasi']['trend']['direction'] }}"></i>
                        @endif
                        {{ $stats['total_inovasi']['trend']['percentage'] }}%
                    </div>
                </div>
            </div>
        </div>

        <div class="bento-card span-3">
            <div class="stat-card">
                <div class="stat-icon rose"><i class="fa fa-bullhorn"></i></div>
                <div>
                <div class="stat-value">{{ number_format($stats['total_publikasi']['total'], 0, ',', '.') }}</div>
                    <div class="stat-label">Total Publikasi</div>
                    <div class="stat-trend {{ $stats['total_inovasi']['trend']['direction'] }}">
                        @if($stats['total_publikasi']['trend']['direction'] !== 'flat')
                            <i class="fas fa-arrow-{{ $stats['total_publikasi']['trend']['direction'] }}"></i>
                        @endif
                        {{ $stats['total_publikasi']['trend']['percentage'] }}%
                    </div>
                </div>
            </div>
        </div>

        <div class="bento-card span-8">
            <div class="bento-card-header">
                <div>
                    <h3 class="bento-card-title">Trafik Pengunjung</h3>
                    <p class="bento-card-subtitle">Jumlah kunjungan dalam 7 hari terakhir</p>
                </div>
                <span class="badge badge-secondary">7 Hari</span>
            </div>
            <div class="chart-wrap">
                <canvas id="trafficChart"></canvas>
            </div>
        </div>

        <div class="bento-card span-4">
            <div class="bento-card-header">
                <div>
                    <h3 class="bento-card-title">Distribusi Kategori</h3>
                    <p class="bento-card-subtitle">Porsi informasi per kategori</p>
                </div>
            </div>
            <div class="chart-wrap">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <div class="bento-card span-6">
            <div class="bento-card-header">
                <div>
                    <h3 class="bento-card-title">Berita Terbaru</h3>
                    <p class="bento-card-subtitle">5 berita terakhir yang dipublikasikan</p>
                </div>
            </div>
            <ul class="news-list">
                @forelse($recentPosts as $post)
                    <li>
                        <div class="news-thumb"><i class="fas fa-newspaper"></i></div>
                        <div class="news-meta">
                            <div class="news-title">{{ $post->title }}</div>
                            <div class="news-date">{{ indo_date($post->published_at) }}</div>
                        </div>
                    </li>
                @empty
                    <li>
                        <div class="news-meta">
                            <div class="news-title text-muted">Belum ada berita yang dipublikasikan.</div>
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>

        <div class="bento-card span-6">
            <div class="bento-card-header">
                <div>
                    <h3 class="bento-card-title">Aktivitas Terkini</h3>
                    <p class="bento-card-subtitle">Catatan aktivitas tim</p>
                </div>
            </div>
            <ul class="activity-list">
            @forelse($recentActivities as $activity)
                <li>
                    <span class="activity-dot"></span>
                    <span class="activity-text">{{ $activity->description }}</span>
                </li>
            @empty
                <li>
                    <span class="activity-text text-muted">Belum ada aktivitas tercatat.</span>
                </li>
            @endforelse
    </ul>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var trafficCtx = document.getElementById('trafficChart').getContext('2d');

        new Chart(trafficCtx, {
            type: 'line',
            data: {
                labels: @json($traffic['labels']),
                datasets: [
                    {
                        label: 'Pengunjung',
                        data: @json($traffic['data']),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.10)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#2563eb'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#eef2f7'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        var categoryCtx = document.getElementById('categoryChart').getContext('2d');

        var categoryColors = ['#2563eb', '#06b6d4', '#f59e0b', '#10b981', '#ef4444', '#8b5cf6', '#ec4899']; 

        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: @json($categoryDistribution['labels']),
                datasets: [
                    {
                        data: @json($categoryDistribution['data']),
                        backgroundColor: categoryColors,
                        borderWidth: 0
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
@endpush
