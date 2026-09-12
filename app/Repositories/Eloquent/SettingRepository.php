<?php

namespace App\Repositories\Eloquent;

use App\Models\Setting;
use App\Repositories\Contracts\SettingRepositoryInterface;

class SettingRepository extends BaseRepository implements SettingRepositoryInterface
{
    protected function model(): string
    {
        return Setting::class;
    }
}
