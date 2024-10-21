<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface CrudRepositoryInterface
{
    public function all(): ?Collection;

    public function create(array $data): Model;

    public function update(array $data, $id): Model;

    public function delete($id): bool;

    public function find($id): Model;
}
