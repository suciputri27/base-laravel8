<?php

namespace App\Helpers;

use Vinkla\Hashids\Facades\Hashids;

class IdEncryptionHelper
{
    public static function encode(int $id): string
    {
        return Hashids::encode($id);
    }

    public static function decode(string $hash): ?int
    {
        $decoded = Hashids::decode($hash);

        if (empty($decoded)) {
            return null;
        }

        return (int) $decoded[0];
    }
}
