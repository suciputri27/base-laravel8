<?php

namespace App\Services;

use App\Repositories\Eloquent\PersyaratanRepository;
use App\Services\ActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PersyaratanService extends BaseService
{
    public function __construct(PersyaratanRepository $repository)
    {
        parent::__construct($repository);
    }

    public function create(array $data)
    {
        $persyaratan = $this->repository->create($data);

        ActivityLogger::log('Admin menambahkan Persyaratan "' . $persyaratan->nama_persyaratan . '".', $persyaratan);

        return $persyaratan;
    }

    public function update(int $id, array $data)
    {
        $persyaratan = $this->repository->update($id, $data);

        ActivityLogger::log('Admin mengubah Persyaratan "' . $persyaratan->nama_persyaratan . '".', $persyaratan);

        return $persyaratan;
    }

    public function paginate(array $options = []): array
    {
        $options['search_columns'] = ['nama_persyaratan'];

        $result = parent::paginate($options);

        $result['data'] = $result['data']->map(function ($persyaratan) {
            return [
                'encrypted_id' => id_encode((int) $persyaratan->id),
                'nama_persyaratan' => $persyaratan->nama_persyaratan,
                'cekdokumen' => (bool) $persyaratan->cekdokumen,
                'is_active' => (bool) $persyaratan->is_active
            ];
        })->values();

        return $result;
    }

    public function all()
    {
        return $this->repository->newQuery()->where('is_active', true)->orderBy('nama_persyaratan')->get();
    }

    public function formulir_data(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 6);
        $search  = trim((string) $request->input('search', ''));

        $query = $this->repository->newQuery()
            ->where('cekdokumen', 1)
            ->with('detail_persyaratan');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $formulir = $query->latest()->paginate($perPage);

        return response()->json([
            'success'      => true,
            'data'         => $formulir->items(),
            'current_page' => $formulir->currentPage(),
            'last_page'    => $formulir->lastPage(),
            'total'        => $formulir->total(),
        ]);
    }
}
