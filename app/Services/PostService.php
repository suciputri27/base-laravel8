<?php

namespace App\Services;

use App\Repositories\Contracts\BerkasPostRepositoryInterface;
use App\Repositories\Eloquent\PostRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostService extends BaseService
{
    protected BerkasPostRepositoryInterface $berkasRepository;

    public function __construct(
        PostRepository $repository,
        BerkasPostRepositoryInterface $berkasRepository
    ) {
        parent::__construct($repository);
        $this->berkasRepository = $berkasRepository;
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['slug'] = Str::slug($data['title']);
            $data['published_at'] = $this->resolvePublishedAt($data);

            $files = $data['berkas'] ?? [];
            unset($data['berkas']);

            $post = $this->repository->create($data);

            $this->storeBerkas($post->id, $files);

            return $post;
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $model = $this->repository->findOrFail($id);

            $data['slug'] = $this->generateUniqueSlug($data['title'], $id);
            $data['published_at'] = $this->resolvePublishedAt($data, $model);

            $files = $data['berkas'] ?? [];
            $deletedIds = $data['deleted_berkas'] ?? null;
            unset($data['berkas'], $data['deleted_berkas']);

            $post = $this->repository->update($id, $data);

            if (!empty($deletedIds)) {
                $ids = array_filter(explode(',', $deletedIds));
                $this->berkasRepository->deleteByIds($ids, $id);
            }

            $this->storeBerkas($id, $files);

            return $post;
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
                ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
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

            $filePath = $file->store('post', 'public');

            if (!$filePath) {
                continue; // skip kalau gagal simpan file
            }

            $this->berkasRepository->create([
                'posts_id' => $inovasiId,
                'berkas' => $filePath,
            ]);
        }
    }

    public function paginate(array $options = []): array
    {
        $options['search_columns'] = ['title', 'slug', 'excerpt'];

        $result = parent::paginate($options);

        $result['data'] = $result['data']->map(function ($post) {
            return [
                'encrypted_id' => id_encode((int) $post->id),
                'title' => $post->title,
                'slug' => $post->slug,
                'category_name' => $post->category ? $post->category->name : null,
                'status' => $post->status,
                'published_at' => $post->published_at ? indo_datetime($post->published_at) : null,
                'berkas' => $this->berkasRepository->getByPostId($post->id)
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

    protected function resolvePublishedAt(array $data, $existing = null)
    {
        if (($data['status'] ?? 'draft') === 'published') {
            if (! empty($data['published_at'])) {
                return $data['published_at'];
            }

            return $existing ? $existing->published_at : now();
        }

        return null;
    }
    
    public function delete(int $id) :bool
    {
        return DB::transaction(function () use ($id) {
            $berkasList = $this->berkasRepository->getByPostId($id);

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