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
        unset($data['montant_total']);
        if (empty($data['date_vente'])) {
            $data['date_vente'] = now();
        }
        $data['montant_total'] = 0;

        return \DB::transaction(function () use ($data, $products) {
            $sale = Sale::create($data);
            $montant_total = 0;
            foreach ($products as $item) {
                $product = \App\Models\PharmaceuticalProduct::findOrFail($item['product_id']);
                $unit_price = $product->price ?? $product->prix_unitaire;
                if ($unit_price === null || $unit_price === '' || !is_numeric($unit_price)) {
                    throw new \Exception('Unit price not found for product ID ' . $product->id . '. Vérifiez le mapping JS <-> PHP.');
                }
                $quantity = $item['quantity'];
                // Vérification du stock suffisant
                if ($product->stock < $quantity) {
                    throw new \Exception('Stock insuffisant pour le produit ' . $product->name . '. Stock disponible : ' . $product->stock . ', demandé : ' . $quantity);
                }
                // Décrémentation du stock
                $product->stock -= $quantity;
                $product->save();

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
        });
    }

    public function update($id, array $data)
    {
        $products = $data['products'] ?? [];
        unset($data['products']);
        unset($data['montant_total']);
        
        return \DB::transaction(function () use ($id, $data, $products) {
            $sale = Sale::findOrFail($id);
            $sale->update($data);
            // Synchronisation des produits et du stock
            $syncData = [];
            $montant_total = 0;
            // Récupérer les anciennes quantités
            $oldProducts = $sale->products()->get()->keyBy('id');
            $newProductIds = collect($products)->pluck('product_id')->all();
            // Réajuster le stock pour les produits supprimés de la vente
            foreach ($oldProducts as $oldProduct) {
                if (!in_array($oldProduct->id, $newProductIds)) {
                    // Produit supprimé : restituer toute la quantité au stock
                    $pivot = $oldProduct->pivot;
                    $oldProduct->stock += $pivot->quantity;
                    $oldProduct->save();
                }
            }
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
                $oldQuantity = $oldProducts[$product->id]->pivot->quantity ?? 0;
                $diff = $quantity - $oldQuantity;
                $stockValue = is_numeric($product->stock) ? (int)$product->stock : 0;
                if ($diff > 0) {
                    // On veut vendre plus : vérifier le stock restant
                    if ($stockValue < $diff) {
                        throw new \Exception('Stock insuffisant pour le produit ' . $product->name . '. Stock disponible : ' . $stockValue . ', demandé en plus : ' . $diff);
                    }
                    $product->stock = $stockValue - $diff;
                    $product->save();
                } elseif ($diff < 0) {
                    // On restitue du stock
                    $product->stock = $stockValue + abs($diff);
                    $product->save();
                }
                // Si $diff == 0, rien à faire
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
        });
    }

    public function delete($id)
    {
        return \DB::transaction(function () use ($id) {
            $sale = Sale::findOrFail($id);
            // Restituer le stock pour chaque produit vendu
            foreach ($sale->products as $product) {
                $product->stock += $product->pivot->quantity;
                $product->save();
            }
            return $sale->delete();
        });
    }
}
