<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadHelper
{
    protected const ALLOWED_MIME = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];

    public static function upload(UploadedFile $file, string $directory = 'uploads', int $maxKb = 2048): string
    {
        if (! $file->isValid()) {
            throw new \RuntimeException('File tidak valid.');
        }

        if ($file->getSize() > $maxKb * 1024) {
            throw new \RuntimeException('Ukuran file maksimal ' . $maxKb . ' KB.');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file->getPathname());

        if (! isset(self::ALLOWED_MIME[$mime])) {
            throw new \RuntimeException('Tipe file tidak diizinkan.');
        }

        $extension = self::ALLOWED_MIME[$mime];
        $filename = Str::random(40) . '.' . $extension;

        return Storage::disk('public')->putFileAs($directory, $file, $filename, 'public');
    }

    public static function delete(string $path): bool
    {
        if (! $path) {
            return false;
        }

        return Storage::disk('public')->delete($path);
    }

    public static function url(string $path): string
    {
        return Storage::disk('public')->url($path);
    }
}
