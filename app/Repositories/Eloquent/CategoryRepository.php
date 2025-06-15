<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository implements CategoryRepositoryInterface
{
    protected $model;

    public function __construct(Category $model)
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
        $category = $this->find($id);
        return $category->update($data);
    }

    public function delete(int $id): bool
    {
        $category = $this->find($id);
        return $product->delete();
    }
}
