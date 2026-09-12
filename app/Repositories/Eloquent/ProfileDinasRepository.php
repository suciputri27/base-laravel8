<?php

namespace App\Repositories\Eloquent;

use App\Helpers\CursorPaginationHelper;
use App\Models\ProfileDinas;
use App\Repositories\Contracts\ProfileDinasRepositoryInterface;

class ProfileDinasRepository extends BaseRepository implements ProfileDinasRepositoryInterface
{
    protected function model(): string
    {
        return ProfileDinas::class;
    }

    // public function getPaginated(array $options = []): array
    // {
    //     return CursorPaginationHelper::paginate($this->model->with('ProfileDinas'), $options);
    // }
}
