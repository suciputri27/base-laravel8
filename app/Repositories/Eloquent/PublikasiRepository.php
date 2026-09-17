<?php

namespace App\Repositories\Eloquent;

use App\Models\Publikasi;
use App\Repositories\Contracts\PublikasiRepositoryInterface;

class PublikasiRepository extends BaseRepository implements PublikasiRepositoryInterface
{
    protected function model(): string
    {
        return Publikasi::class;
    }
}
