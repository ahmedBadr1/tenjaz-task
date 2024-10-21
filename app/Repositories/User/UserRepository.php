<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function all()
    {
        return User::all();
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update(array $data, $id)
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    }

    public function find($id)
    {
        return User::findOrFail($id);
    }

    public function generateToken($user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }

    // Logout user and revoke tokens
    public function logout(): bool
    {
        $user = Auth::user();
        if ($user) {
            $user->tokens()->delete();
        }
        Auth::logout();
        return true;
    }
}
