<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Inovasi;
use App\Models\Publikasi;
use App\Models\ActivityLog;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class DashboardService
{
    public function getStats(): array
    {
        return [
            'total_berita' => $this->buildStat(
                Post::where('category_id', 1)
            ),
            'total_inovasi' => $this->buildStat(
                Inovasi::where('jenis', 1)
            ),
            'total_publikasi' => $this->buildStat(
                Publikasi::where('jenis_dokumen', 5)
            ),
            'berita_aktif' => $this->buildStat(
                Post::where(['status' => 'published', 'category_id' => 1])
            ),
            'pengunjung' => $this->getPengunjungStat(),
        ];
    }

    protected function buildStat($query): array
    {
        $total = (clone $query)->count();

        $bulanIni = (clone $query)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $bulanLalu = (clone $query)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();

        return [
            'total' => $total,
            'trend' => $this->calculateTrend($bulanIni, $bulanLalu),
        ];
    }

    protected function calculateTrend(int $current, int $previous): array
    {
        if ($previous === 0) {
            return [
                'percentage' => $current > 0 ? 100 : 0,
                'direction' => $current > 0 ? 'up' : 'flat',
            ];
        }

        $percentage = (($current - $previous) / $previous) * 100;

        return [
            'percentage' => abs(round($percentage, 1)),
            'direction' => $percentage > 0 ? 'up' : ($percentage < 0 ? 'down' : 'flat'),
        ];
    }

    public function getRecentPosts(int $limit = 5): Collection
    {
        return Post::where(['status' => 'published', 'category_id' => 1])
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getRecentActivities(int $limit = 5)
    {
        return ActivityLog::latest()->limit($limit)->get();
    }

    protected function getPengunjungStat(): array
    {
        $hariIni = Visitor::whereDate('visited_date', Carbon::today())->count();
        $kemarin = Visitor::whereDate('visited_date', Carbon::yesterday())->count();

        return [
            'total' => $hariIni,
            'trend' => $this->calculateTrend($hariIni, $kemarin),
        ];
    }

    public function getTrafficChart(): array
    {
        $labels = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->translatedFormat('D, d M');
            $data[] = Visitor::whereDate('visited_date', $date->toDateString())->count();
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    public function getCategoryDistribution(): array
    {
        $data = Post::selectRaw('categories.name, COUNT(posts.id) as total')
            ->join('categories', 'categories.id', '=', 'posts.category_id')
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->get();

        return [
            'labels' => $data->pluck('name')->toArray(),
            'data' => $data->pluck('total')->toArray(),
        ];
    }
}