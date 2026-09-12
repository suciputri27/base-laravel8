<?php

namespace App\Services;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Support\Str;

class CategoryService extends BaseService
{
    public function __construct(CategoryRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function create(array $data)
    {
        $data['slug'] = Str::slug($data['name']);

        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        $data['slug'] = Str::slug($data['name']);

        return $this->repository->update($id, $data);
    }

    public function paginate(array $options = []): array
    {
        $options['search_columns'] = ['name', 'slug', 'description'];

        $result = parent::paginate($options);

        $result['data'] = $result['data']->map(function ($category) {
            return [
                'encrypted_id' => id_encode((int) $category->id),
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'is_active' => (bool) $category->is_active,
            ];
        })->values();

        return $result;
    }

    public function all()
    {
        return $this->repository->newQuery()->where('is_active', true)->orderBy('name')->get();
    }
}
