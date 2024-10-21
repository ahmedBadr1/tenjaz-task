<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\User\ProductRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;

class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $repository
    ) {
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->repository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }

    public function all()
    {
        return $this->repository->all();
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }
}
