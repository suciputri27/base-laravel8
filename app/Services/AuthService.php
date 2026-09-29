<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(array $credentials): bool
    {
        $remember = filter_var($credentials['remember'] ?? false, FILTER_VALIDATE_BOOLEAN);

        $success = Auth::attempt(
            [
                'email'    => $credentials['email'],
                'password' => $credentials['password'],
            ],
            $remember
        );
    
        if ($success) {
            ActivityLogger::log(Auth::user()->name . ' login ke sistem.');
        } else {
            ActivityLogger::log('Percobaan login gagal untuk email "' . $credentials['email'] . '".');
        }
    
        return $success;
    }

    public function register(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->create($data);
        Auth::login($user);

        return $user;
    }

    public function logout(): void
    {
        ActivityLogger::log(Auth::user()->name . ' logout dari sistem.');
        
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}
