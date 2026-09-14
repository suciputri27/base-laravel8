<?php

namespace App\Repositories\Eloquent;

use App\Models\Struktur_organisasi;
use App\Repositories\Contracts\StrukturOrganisasiRepositoryInterface;

class StrukturOrganisasiRepository extends BaseRepository implements StrukturOrganisasiRepositoryInterface
{
    protected function model(): string
    {
        return Struktur_organisasi::class;
    }
}
