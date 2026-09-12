<?php

namespace App\Services;

use App\Repositories\Contracts\SettingRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class SettingService extends BaseService
{
    public function __construct(SettingRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function getSetting(): ?Model
    {
        return $this->repository->newQuery()->first();
    }

    public function save(array $data): Model
    {
        $setting = $this->getSetting();

        if ($setting) {
            return $this->repository->update((int) $setting->id, $data);
        }

        return $this->repository->create($data);
    }
}
