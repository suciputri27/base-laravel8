<?php

namespace App\Repositories\Contracts;

interface BerkasInovasiRepositoryInterface extends BaseRepositoryInterface
{
    public function deleteByIds(array $ids, int $inovasiId): void;
    public function getByInovasiId(int $inovasiId);
}