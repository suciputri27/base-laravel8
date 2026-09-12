<?php

namespace App\Traits;

use App\Helpers\IdEncryptionHelper;

trait EncryptableIdTrait
{
    public function getEncryptedIdAttribute(): ?string
    {
        $key = $this->getKey();

        if ($key === null) {
            return null;
        }

        return IdEncryptionHelper::encode((int) $key);
    }
}
