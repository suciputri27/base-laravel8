<?php

namespace App\Services\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface BaseServiceInterface
{
    public function find(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id): bool;

    public function getTrashed(): Collection;

    public function restore(int $id): bool;

    public function forceDelete(int $id): bool;

    public function paginate(array $options = []): array;
}
