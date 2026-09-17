<?php

namespace App\Repositories\Contracts;

interface DetailPersyaratanRepositoryInterface extends BaseRepositoryInterface
{
    public function activeByPelayanan(int $pelayananId);

    public function findActive(int $pelayananId, int $persyaratanId);

    public function historyOf(int $pelayananId, int $persyaratanId);
}