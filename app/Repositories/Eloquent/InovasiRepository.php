<?php

namespace App\Repositories\Eloquent;

use App\Models\Inovasi;
use App\Repositories\Contracts\InovasiRepositoryInterface;

class InovasiRepository extends BaseRepository implements InovasiRepositoryInterface
{
    protected function model(): string
    {
        return Inovasi::class;
    }
}
