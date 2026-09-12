<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class PostService extends BaseService
{
    public function __construct(PostRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function create(array $data)
    {
        if (isset($data['thumbnail']) && $data['thumbnail'] instanceof UploadedFile) {
            $data['thumbnail'] = FileUploadHelper::upload($data['thumbnail'], 'posts');
        }

        $data['slug'] = Str::slug($data['title']);
        $data['published_at'] = $this->resolvePublishedAt($data);

        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        $model = $this->repository->findOrFail($id);

        if (isset($data['thumbnail']) && $data['thumbnail'] instanceof UploadedFile) {
            if ($model->thumbnail) {
                FileUploadHelper::delete($model->thumbnail);
            }

            $data['thumbnail'] = FileUploadHelper::upload($data['thumbnail'], 'posts');
        } else {
            unset($data['thumbnail']);
        }

        $data['slug'] = Str::slug($data['title']);
        $data['published_at'] = $this->resolvePublishedAt($data, $model);

        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        $model = $this->repository->findOrFail($id);

        if ($model->thumbnail) {
            FileUploadHelper::delete($model->thumbnail);
        }

        return $this->repository->delete($id);
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
                'thumbnail' => $post->thumbnail,
                'thumbnail_url' => $post->thumbnail ? storage_url($post->thumbnail) : null,
                'status' => $post->status,
                'published_at' => $post->published_at ? indo_datetime($post->published_at) : null,
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
}
