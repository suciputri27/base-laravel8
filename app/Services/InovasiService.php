<?php

namespace App\Services;

use App\Repositories\Eloquent\InovasiRepository;
use App\Repositories\Contracts\BerkasInovasiRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
            $files = $data['berkas'] ?? [];
            unset($data['berkas']);

            $inovasi = $this->repository->create($data);

            $this->storeBerkas($inovasi->id, $files);

            return $inovasi;
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $files = $data['berkas'] ?? [];
            $deletedIds = $data['deleted_berkas'] ?? null;
            unset($data['berkas'], $data['deleted_berkas']);

            $inovasi = $this->repository->update($id, $data);

            if (!empty($deletedIds)) {
                $ids = array_filter(explode(',', $deletedIds));
                $this->berkasRepository->deleteByIds($ids, $id);
            }

            $this->storeBerkas($id, $files);

            return $inovasi;
        });
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
        $options['search_columns'] = ['judul', 'deskripsi','jenis'];

        $result = parent::paginate($options);

        $result['data'] = $result['data']->map(function ($inovasi) {
            return [
                'encrypted_id' => id_encode((int) $inovasi->id),
                'judul' => $inovasi->judul,
                'jenis' => $inovasi->jenis,
                'deskripsi' => $inovasi->deskripsi,
                'is_active' => (bool) $inovasi->is_active,
                'berkas' => $this->berkasRepository->getByInovasiId($inovasi->id)
                ->filter(fn ($b) => !empty($b->berkas))
                ->map(fn ($b) => [
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
    
    public function delete(int $id) :bool
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
}