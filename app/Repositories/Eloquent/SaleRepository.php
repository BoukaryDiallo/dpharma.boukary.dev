<?php

namespace App\Repositories\Eloquent;

use App\Models\Sale;
use App\Repositories\Contracts\SaleRepositoryInterface;

class SaleRepository implements SaleRepositoryInterface
{
    public function all()
    {
        return Sale::with(['client', 'user', 'products'])->orderByDesc('date_vente')->get();
    }

    public function find($id)
    {
        return Sale::with(['client', 'user'])->findOrFail($id);
    }

    public function create(array $data)
    {
        $products = $data['products'] ?? [];
        unset($data['products']);
        // On ne prend pas montant_total du formulaire
        unset($data['montant_total']);
        if (empty($data['date_vente'])) {
            $data['date_vente'] = now();
        }
        $data['montant_total'] = 0; // Pour éviter l'erreur NOT NULL
        $sale = Sale::create($data);
        $montant_total = 0;
        foreach ($products as $item) {
    $product = \App\Models\PharmaceuticalProduct::findOrFail($item['product_id']);
    // Correction : tente 'price' (JS/frontend) puis 'prix_unitaire' (backend)
    $unit_price = $product->price ?? $product->prix_unitaire;
    if ($unit_price === null || $unit_price === '' || !is_numeric($unit_price)) {
        throw new \Exception('Unit price not found for product ID ' . $product->id . '. Vérifiez le mapping JS <-> PHP.');
    }
    $quantity = $item['quantity'];
    $total = $unit_price * $quantity;
    $sale->products()->attach($product->id, [
        'quantity' => $quantity,
        'unit_price' => $unit_price,
        'total' => $total
    ]);
    $montant_total += $total;
}
        $sale->montant_total = $montant_total;
        $sale->save();
        return $sale->load(['client', 'user', 'products']);
    }

    public function update($id, array $data)
    {
        $products = $data['products'] ?? [];
        unset($data['products']);
        unset($data['montant_total']);
        $sale = Sale::findOrFail($id);
        $sale->update($data);
        // Synchronisation des produits
        $syncData = [];
        $montant_total = 0;
        foreach ($products as $item) {
    if (!isset($item['product_id'])) {
        throw new \Exception('Missing product_id in product item during sale update.');
    }
    if (!isset($item['quantity'])) {
        throw new \Exception('Missing quantity in product item during sale update.');
    }
    $product = \App\Models\PharmaceuticalProduct::findOrFail($item['product_id']);
    $unit_price = $product->price ?? $product->prix_unitaire;
    if ($unit_price === null || $unit_price === '' || !is_numeric($unit_price)) {
        throw new \Exception('Unit price not found for product ID ' . $product->id . ' during update.');
    }
    $quantity = $item['quantity'];
    $total = $unit_price * $quantity;
    $syncData[$product->id] = [
        'quantity' => $quantity,
        'unit_price' => $unit_price,
        'total' => $total
    ];
    $montant_total += $total;
}
        $sale->products()->sync($syncData);
        $sale->montant_total = $montant_total;
        $sale->save();
        return $sale->load(['client', 'user', 'products']);
    }

    public function delete($id)
    {
        $sale = Sale::findOrFail($id);
        return $sale->delete();
    }
}
