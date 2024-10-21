<?php

namespace App\Repositories\Product;

use App\Repositories\CrudRepositoryInterface;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface extends CrudRepositoryInterface
{
    public function getAllProductsByUserType(string $userType): Collection;
    
}
