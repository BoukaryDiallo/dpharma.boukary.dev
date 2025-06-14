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
        'price',
        'stock_quantity',
        'expiration_date',
        'requires_prescription',
    ];

    protected $casts = [
        'expiration_date' => 'date',
        'requires_prescription' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

