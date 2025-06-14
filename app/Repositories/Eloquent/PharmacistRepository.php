<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\PharmacistRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PharmacistRepository implements PharmacistRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function paginate(int $perPage = 10, array $relations = []): LengthAwarePaginator
    {
        $query = $this->model->query();

        // Charger les relations si spécifiées
        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->latest()->paginate($perPage);
    }

    public function find(int $id, array $relations = [])
    {
        $query = $this->model->query();

        // Charger les relations si spécifiées
        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $pharmacist = $this->find($id);
        return $pharmacist->update($data);
    }

    public function delete(int $id): bool
    {
        $product = $this->find($id);
        return $product->delete();
    }
}
