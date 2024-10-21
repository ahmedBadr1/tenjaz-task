<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function all()
    {
        return User::all();
    }

    public function create(array $data)
    {
        // Handle avatar upload if present
        if (isset($data['avatar'])) {

            $avatarPath = uploadFile($data['avatar'], 'avatars');
            if ($avatarPath) {
                $data['avatar'] = $avatarPath;
            }
        }

        // hashing password
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    public function update(array $data, $id)
    {
        $user = User::find($id);
        if ($user) {
            // Handle avatar upload if present
            if (isset($data['avatar'])) {
                $avatarPath = uploadFile($data['avatar'], 'avatars');
                if ($avatarPath) {
                    $data['avatar'] = $avatarPath;
                }
            }
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']); // Hash new password if present
            }
            $user->update($data);
        }
        return $user;
    }

    public function delete($id): bool
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return true;
        }
        return false;
    }

    public function find($id): User
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
