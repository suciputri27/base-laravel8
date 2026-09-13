<?php

namespace App\Repositories\Eloquent;

use App\Helpers\CursorPaginationHelper;
use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->makeModel();
    }

    abstract protected function model(): string;

    protected function makeModel(): Model
    {
        $model = app($this->model());

        if (! $model instanceof Model) {
            throw new \RuntimeException('Class ' . $this->model() . ' harus merupakan instance dari ' . Model::class);
        }

        return $this->model = $model;
    }

    public function newQuery(): Builder
    {
        return $this->model->newQuery();
    }

    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function findOrFail(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Model
    {
        $model = $this->model->newInstance();
        $model->fill($data);
        $model->forceFill($this->auditAttributes(true));
        $model->save();

        return $model;
    }

    public function update(int $id, array $data): Model
    {
        $model = $this->findOrFail($id);
        $model->fill($data);
        $model->forceFill($this->auditAttributes(false));
        $model->save();

        return $model->refresh();
    }

    public function delete(int $id): bool
    {
        $model = $this->findOrFail($id);

        if ($this->usesSoftDeletes($model)) {
            $model->forceFill(['deleted_by' => auth()->id()]);
            $model->save();
        }

        return (bool) $model->delete();
    }

    public function getTrashed(): Collection
    {
        return $this->model->onlyTrashed()->get();
    }

    public function restore(int $id): bool
    {
        return (bool) $this->model->withTrashed()->findOrFail($id)->restore();
    }

    public function forceDelete(int $id): bool
    {
        return (bool) $this->model->withTrashed()->findOrFail($id)->forceDelete();
    }

    public function getPaginated(array $options = []): array
    {
        return CursorPaginationHelper::paginate($this->newQuery(), $options);
    }

    protected function auditAttributes(bool $isCreate): array
    {
        $attributes = ['updated_by' => auth()->id()];

        if ($isCreate) {
            $attributes['created_by'] = auth()->id();
        }

        return $attributes;
    }

    protected function usesSoftDeletes(Model $model): bool
    {
        return in_array(SoftDeletes::class, class_uses_recursive($model), true);
    }
}
