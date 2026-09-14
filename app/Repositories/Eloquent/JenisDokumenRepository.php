<?php

namespace App\Repositories\Eloquent;

use App\Models\Jenis_dokumen;
use App\Repositories\Contracts\JenisDokumenRepositoryInterface;

class JenisDokumenRepository extends BaseRepository implements JenisDokumenRepositoryInterface
{
    protected function model(): string
    {
        return Jenis_dokumen::class;
    }
}
