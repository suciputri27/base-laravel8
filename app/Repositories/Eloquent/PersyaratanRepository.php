<?php

namespace App\Repositories\Eloquent;

use App\Models\Persyaratan;
use App\Repositories\Contracts\PersyaratanRepositoryInterface;

class PersyaratanRepository extends BaseRepository implements PersyaratanRepositoryInterface
{
    protected function model(): string
    {
        return Persyaratan::class;
    }
}
