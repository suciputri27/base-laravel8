<?php

namespace App\Repositories\Eloquent;

use App\Models\Pelayanan;
use App\Repositories\Contracts\PelayananRepositoryInterface;

class PelayananRepository extends BaseRepository implements PelayananRepositoryInterface
{
    protected function model(): string
    {
        return Pelayanan::class;
    }
}
