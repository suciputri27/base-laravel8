<?php

namespace App\Repositories\Contracts;

interface BerkasPostRepositoryInterface extends BaseRepositoryInterface
{
    public function deleteByIds(array $ids, int $postId): void;
    public function getByPostId(int $postId);
}