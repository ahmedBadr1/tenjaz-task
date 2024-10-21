<?php

namespace App\Repositories\User;

use App\Repositories\CrudRepositoryInterface;

interface UserRepositoryInterface extends CrudRepositoryInterface
{
    public function generateToken($user): string;

    public function logout():bool;
}
