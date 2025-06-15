<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface PharmaceuticalProductRepositoryInterface
{
    public function all(): Collection;
    public function paginate(int $perPage = 10, array $relations = []): LengthAwarePaginator;
    public function find(int $id, array $relations = []);
    public function create(array $data);
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    /**
     * Vérifie si un slug existe déjà, en excluant éventuellement un ID spécifique
     *
     * @param string $slug Le slug à vérifier
     * @param int|null $excludeId L'ID à exclure de la vérification (utile pour la mise à jour)
     * @return bool
     */
    public function slugExists(string $slug, ?int $excludeId = null): bool;
}
