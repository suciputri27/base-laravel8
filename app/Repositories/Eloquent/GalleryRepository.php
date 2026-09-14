<?php

namespace App\Repositories\Eloquent;

use App\Models\Gallery;
use App\Repositories\Contracts\GalleryRepositoryInterface;

class GalleryRepository extends BaseRepository implements GalleryRepositoryInterface
{
    protected function model(): string
    {
        return Gallery::class;
    }
}
