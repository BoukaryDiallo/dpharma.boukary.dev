<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PharmaceuticalProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'batch_number',
        'manufacturer',
        'description',
        'composition',
        'form',
        'dosage',
        'prix_unitaire',
        'stock_quantity',
        'expiration_date',
        'requires_prescription',
    ];

    protected $casts = [
        'expiration_date' => 'date',
        'requires_prescription' => 'boolean',
        'prix_unitaire' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sales()
    {
        return $this->belongsToMany(Sale::class, 'sale_product', 'product_id', 'sale_id')
            ->withPivot(['quantity', 'unit_price', 'total'])
            ->withTimestamps();
    }
}
