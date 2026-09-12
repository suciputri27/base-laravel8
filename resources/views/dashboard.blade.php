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
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Buat Berita
                </button>
            </div>
        </div>

        <div class="bento-card span-3">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-eye"></i></div>
                <div>
                    <div class="stat-value">8.420</div>
                    <div class="stat-label">Pengunjung Hari Ini</div>
                    <div class="stat-trend up"><i class="fas fa-arrow-up"></i> 12,5%</div>
                </div>
            </div>
        </div>

        <div class="bento-card span-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-newspaper"></i></div>
                <div>
                    <div class="stat-value">1.248</div>
                    <div class="stat-label">Total Berita</div>
                    <div class="stat-trend up"><i class="fas fa-arrow-up"></i> 3,2%</div>
                </div>
            </div>
        </div>

        <div class="bento-card span-3">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-tags"></i></div>
                <div>
                    <div class="stat-value">24</div>
                    <div class="stat-label">Kategori Aktif</div>
                    <div class="stat-trend up"><i class="fas fa-arrow-up"></i> 1,8%</div>
                </div>
            </div>
        </div>

        <div class="bento-card span-3">
            <div class="stat-card">
                <div class="stat-icon rose"><i class="fas fa-envelope"></i></div>
                <div>
                    <div class="stat-value">156</div>
                    <div class="stat-label">Pesan Baru</div>
                    <div class="stat-trend down"><i class="fas fa-arrow-down"></i> 2,4%</div>
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
                    <p class="bento-card-subtitle">Porsi berita per kategori</p>
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
                <li>
                    <div class="news-thumb"><i class="fas fa-bullhorn"></i></div>
                    <div class="news-meta">
                        <div class="news-title">Peluncuran Produk Baru Tahun 2026</div>
                        <div class="news-date">{{ indo_date('2026-09-10') }}</div>
                    </div>
                </li>
                <li>
                    <div class="news-thumb"><i class="fas fa-chart-line"></i></div>
                    <div class="news-meta">
                        <div class="news-title">Strategi Digital Marketing untuk UMKM</div>
                        <div class="news-date">{{ indo_date('2026-09-08') }}</div>
                    </div>
                </li>
                <li>
                    <div class="news-thumb"><i class="fas fa-lightbulb"></i></div>
                    <div class="news-meta">
                        <div class="news-title">Inovasi Teknologi di Industri Kreatif</div>
                        <div class="news-date">{{ indo_date('2026-09-06') }}</div>
                    </div>
                </li>
                <li>
                    <div class="news-thumb"><i class="fas fa-handshake"></i></div>
                    <div class="news-meta">
                        <div class="news-title">Kemitraan Strategis dengan Mitra Regional</div>
                        <div class="news-date">{{ indo_date('2026-09-03') }}</div>
                    </div>
                </li>
                <li>
                    <div class="news-thumb"><i class="fas fa-award"></i></div>
                    <div class="news-meta">
                        <div class="news-title">Penghargaan Perusahaan Digital Terbaik</div>
                        <div class="news-date">{{ indo_date('2026-09-01') }}</div>
                    </div>
                </li>
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
                <li>
                    <span class="activity-dot"></span>
                    <span class="activity-text">Admin mempublikasikan berita Peluncuran Produk Baru.</span>
                </li>
                <li>
                    <span class="activity-dot"></span>
                    <span class="activity-text">Editor memperbarui kategori Teknologi.</span>
                </li>
                <li>
                    <span class="activity-dot"></span>
                    <span class="activity-text">Sistem menerima 32 pesan kontak baru.</span>
                </li>
                <li>
                    <span class="activity-dot"></span>
                    <span class="activity-text">Pengguna baru mendaftar sebagai kontributor.</span>
                </li>
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
                labels: ['Sen', 'Sel', 'Rabu', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [
                    {
                        label: 'Pengunjung',
                        data: [5200, 6100, 5800, 7400, 6900, 8100, 8420],
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.10)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#2563eb'
                    },
                    {
                        label: 'Tayangan',
                        data: [6800, 7400, 7100, 8600, 8100, 9300, 9700],
                        borderColor: '#06b6d4',
                        backgroundColor: 'rgba(6, 182, 212, 0.08)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#06b6d4'
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

        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Teknologi', 'Bisnis', 'Hiburan', 'Olahraga', 'Kesehatan'],
                datasets: [
                    {
                        data: [35, 25, 18, 12, 10],
                        backgroundColor: ['#2563eb', '#06b6d4', '#f59e0b', '#10b981', '#ef4444'],
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
