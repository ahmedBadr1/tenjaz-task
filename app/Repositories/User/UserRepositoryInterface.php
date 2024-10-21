<?php

namespace App\Repositories\User;

use App\Repositories\CrudRepositoryInterface;

interface UserRepositoryInterface extends CrudRepositoryInterface
{
    public function generateToken();

    public function logout();
}
