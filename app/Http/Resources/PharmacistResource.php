<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PharmacistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'role_name' => $this->getRoleName(),
            'is_active' => (bool) $this->is_active,
            'status' => $this->is_active ? 'Actif' : 'Inactif',
            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at->format('d/m/Y H:i'),
            'can' => [
                'update' => $request->user()?->can('update', $this->resource) ?? false,
                'delete' => $request->user()?->can('delete', $this->resource) ?? false,
            ],
        ];
    }
    
    /**
     * Get the human-readable role name
     *
     * @return string
     */
    protected function getRoleName(): string
    {
        return match($this->role) {
            'admin' => 'Administrateur',
            'manager' => 'Gestionnaire',
            'pharmacist' => 'Pharmacien',
            default => ucfirst($this->role),
        };
    }
}
