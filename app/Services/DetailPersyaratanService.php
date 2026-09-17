<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\Persyaratan;
use App\Repositories\Eloquent\DetailPersyaratanRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class DetailPersyaratanService extends BaseService
{
    public function __construct(DetailPersyaratanRepository $repository)
    {
        parent::__construct($repository);
    }

    public function checklist(int $pelayananId): array
    {
        $aktif = $this->repository->activeByPelayanan($pelayananId)->keyBy('persyaratan_id');
    
        return Persyaratan::query()
            ->where('is_active', true)
            ->orWhereIn('id', $aktif->keys())
            ->orderBy('nama_persyaratan')
            ->get()
            ->map(function ($persyaratan) use ($aktif) {
                $detail = $aktif->get($persyaratan->id);
    
                return [
                    'persyaratan_id' =>  id_encode((int) $persyaratan->id),
                    'nama_persyaratan' => $persyaratan->nama_persyaratan,
                    'master_aktif' => (bool) $persyaratan->is_active,
                    'cekdokumen' => (bool) $persyaratan->cekdokumen,
                    'checked' => (bool) $detail,
                    'berkas' => $detail ? $detail->berkas : null,
                    'berkas_url' => ($detail && $detail->berkas) ? storage_url($detail->berkas) : null,
                    'detail_encrypted_id' => $detail ? id_encode((int) $detail->id) : null,
                ];
            })
            ->values()
            ->toArray();
    }

    public function sync(int $pelayananId, array $checkedIds, array $files = []): void
    {
        DB::transaction(function () use ($pelayananId, $checkedIds, $files) {
            $persyaratanIds = Persyaratan::pluck('id');

            foreach ($persyaratanIds as $persyaratanId) {
                $isChecked = in_array($persyaratanId, $checkedIds);
                $existing = $this->repository->findActive($pelayananId, $persyaratanId);
                $file = isset($files[$persyaratanId]) ? $files[$persyaratanId] : null;

                if ($isChecked && !$existing) {
                    $this->repository->create([
                        'pelayanan_id' => $pelayananId,
                        'persyaratan_id' => $persyaratanId,
                        'berkas' => $file instanceof UploadedFile
                            ? FileUploadHelper::upload($file, 'detail_persyaratan')
                            : null,
                        'is_active' => true,
                    ]);
                } elseif (!$isChecked && $existing) {
                    $this->repository->update((int) $existing->id, ['is_active' => false]);
                } elseif ($isChecked && $existing && $file instanceof UploadedFile) {
                    $this->gantiTemplate((int) $existing->id, $file);
                }
            }
        });
    }

    public function gantiTemplate(int $id, UploadedFile $file)
    {
        return DB::transaction(function () use ($id, $file) {
            $current = $this->repository->findOrFail($id);

            $this->repository->update($id, ['is_active' => false]);

            return $this->repository->create([
                'pelayanan_id' => $current->pelayanan_id,
                'persyaratan_id' => $current->persyaratan_id,
                'berkas' => FileUploadHelper::upload($file, 'detail_persyaratan'),
                'is_active' => true,
            ]);
        });
    }

    public function history(int $pelayananId, int $persyaratanId): array
    {
        return $this->repository->historyOf($pelayananId, $persyaratanId)
            ->map(function ($item) {
                return [
                    'encrypted_id' => id_encode((int) $item->id),
                    'berkas_url' => $item->berkas ? storage_url($item->berkas) : null,
                    'is_active' => (bool) $item->is_active,
                    'created_at' => $item->created_at ? $item->created_at->translatedFormat('d F Y H:i') : null,
                    'created_by' => $item->createdBy ? $item->createdBy->name : null,
                ];
            })
            ->values()
            ->toArray();
    }
}