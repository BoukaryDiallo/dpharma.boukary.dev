<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface PharmacistRepositoryInterface
{
    public function all(): Collection;
    public function paginate(int $perPage = 10, array $relations = []): LengthAwarePaginator;
    public function find(int $id, array $relations = []);
    public function create(array $data);
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;

}
