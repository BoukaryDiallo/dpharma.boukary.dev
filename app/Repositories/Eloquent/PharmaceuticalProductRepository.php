<?php

namespace App\Repositories\Eloquent;

use App\Models\PharmaceuticalProduct;
use App\Repositories\Contracts\PharmaceuticalProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PharmaceuticalProductRepository implements PharmaceuticalProductRepositoryInterface
{
    protected $model;

    public function __construct(PharmaceuticalProduct $model)
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
        $product = $this->find($id);
        return $product->update($data);
    }

    public function delete(int $id): bool
    {
        $product = $this->find($id);
        return $product->delete();
    }
    
    public function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $query = $this->model->where('slug', $slug);
        
        // Exclure un ID spécifique de la vérification (utile pour les mises à jour)
        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }
}
