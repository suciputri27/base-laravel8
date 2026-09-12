<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface MenuRepositoryInterface extends BaseRepositoryInterface
{
    public function getActiveMenus(): Collection;

    public function getTree(): Collection;
}
