<?php

namespace App\Services;

use App\Repositories\Contracts\BaseRepositoryInterface;
use App\Services\Contracts\BaseServiceInterface;
use Illuminate\Database\Eloquent\Collection;

abstract class BaseService implements BaseServiceInterface
{
    protected $repository;

    public function __construct(BaseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function find(int $id)
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function getTrashed(): Collection
    {
        return $this->repository->getTrashed();
    }

    public function restore(int $id): bool
    {
        return $this->repository->restore($id);
    }

    public function forceDelete(int $id): bool
    {
        return $this->repository->forceDelete($id);
    }

    public function paginate(array $options = []): array
    {
        return $this->repository->getPaginated($options);
    }
}
