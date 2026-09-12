<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{
    public function __construct(UserRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function create(array $data)
    {
        $role = $data['role'] ?? null;
        unset($data['role']);

        $data['password'] = Hash::make($data['password'] ?? 'password');

        $user = $this->repository->create($data);

        if ($role) {
            $user->syncRoles([$role]);
        }

        return $user;
    }

    public function update(int $id, array $data)
    {
        $role = $data['role'] ?? null;
        unset($data['role']);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user = $this->repository->update($id, $data);

        if ($role) {
            $user->syncRoles([$role]);
        }

        return $user;
    }

    public function paginate(array $options = []): array
    {
        $options['search_columns'] = ['name', 'email'];

        $result = parent::paginate($options);

        $result['data'] = $result['data']->map(function ($user) {
            return [
                'encrypted_id' => id_encode((int) $user->id),
                'name' => $user->name,
                'email' => $user->email,
                'role_name' => $user->getRoleNames()->first(),
                'created_at' => $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : null,
            ];
        })->values();

        return $result;
    }
}
