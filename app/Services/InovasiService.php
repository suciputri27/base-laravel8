<?php

namespace App\Services;

use App\Repositories\Eloquent\InovasiRepository;
use App\Repositories\Contracts\BerkasInovasiRepositoryInterface;
use App\Services\ActivityLogger;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InovasiService extends BaseService
{
    protected BerkasInovasiRepositoryInterface $berkasRepository;

    public function __construct(
        InovasiRepository $repository,
        BerkasInovasiRepositoryInterface $berkasRepository
    ) {
        parent::__construct($repository);
        $this->berkasRepository = $berkasRepository;
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['slug'] = $this->generateUniqueSlug($data['judul']);
            $files = $data['berkas'] ?? [];
            unset($data['berkas']);

            $inovasi = $this->repository->create($data);

            $this->storeBerkas($inovasi->id, $files);
            ActivityLogger::log('Admin Menambahkan Inovasi "' . $inovasi->judul . '".', $inovasi);

            return $inovasi;
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $data['slug'] = $this->generateUniqueSlug($data['judul'], $id);
            $files = $data['berkas'] ?? [];
            $deletedIds = $data['deleted_berkas'] ?? null;
            unset($data['berkas'], $data['deleted_berkas']);

            $inovasi = $this->repository->update($id, $data);

            if (!empty($deletedIds)) {
                $ids = array_filter(explode(',', $deletedIds));
                $this->berkasRepository->deleteByIds($ids, $id);
            }

            $this->storeBerkas($id, $files);
            ActivityLogger::log('Admin memperbarui  Inovasi "' . $inovasi->judul . '".', $inovasi);

            return $inovasi;
        });
    }

    protected function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $count = 1;

        while (
            $this->repository->newQuery()
            ->where('slug', $slug)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists()
        ) {
            $slug = $original . '-' . $count;
            $count++;
        }

        return $slug;
    }

    protected function storeBerkas(int $inovasiId, array $files): void
    {
        foreach ($files as $file) {
            if (!$file instanceof UploadedFile) {
                continue;
            }

            $filePath = $file->store('inovasi', 'public');

            if (!$filePath) {
                continue; // skip kalau gagal simpan file
            }

            $this->berkasRepository->create([
                'inovasi_id' => $inovasiId,
                'berkas' => $filePath,
            ]);
        }
    }

    public function paginate(array $options = []): array
    {
        $options['search_columns'] = ['judul', 'deskripsi', 'jenis'];

        $result = parent::paginate($options);

        $result['data'] = $result['data']->map(function ($inovasi) {
            return [
                'encrypted_id' => id_encode((int) $inovasi->id),
                'judul' => $inovasi->judul,
                'jenis' => $inovasi->jenis,
                'deskripsi' => $inovasi->deskripsi,
                'is_active' => (bool) $inovasi->is_active,
                'berkas' => $this->berkasRepository->getByInovasiId($inovasi->id)
                    ->filter(fn($b) => !empty($b->berkas))
                    ->map(fn($b) => [
                        'encrypted_id' => id_encode((int) $b->id),
                        'url' => storage_url($b->berkas),
                    ])
                    ->values(),
            ];
        })->values();

        return $result;
    }

    public function all()
    {
        return $this->repository->newQuery()->where('is_active', true)->orderBy('judul')->get();
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $berkasList = $this->berkasRepository->getByInovasiId($id);

            foreach ($berkasList as $berkas) {
                if ($berkas->berkas) {
                    Storage::disk('public')->delete($berkas->berkas);
                }
            }

            // row berkas_inovasi otomatis ikut terhapus lewat cascadeOnDelete
            return $this->repository->delete($id);
        });
    }

    public function getPublishedRecent(int $limit = 3)
    {
        return $this->repository->newQuery()
            ->with(['berkas'])
            ->where('is_active', 1)
            ->where('jenis', 1)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getBySlug(string $slug)
    {
        return $this->repository->newQuery()
            ->with(['berkas'])
            ->where('is_active', 1)
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function getInovasi()
    {
        return $this->repository->newQuery()
            ->with(['berkas'])
            ->where('is_active', true)
            ->where('jenis', 1)
            ->get();
    }

    public function getJemputBola()
    {
        return $this->repository->newQuery()
            ->with(['berkas'])
            ->where('is_active', true)
            ->where('jenis', 2)
            ->get();
    }
}
