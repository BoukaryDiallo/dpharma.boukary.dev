<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'user_id',
        'montant_total',
        'date_vente',
    ];

    public function products()
    {
        return $this->belongsToMany(
            PharmaceuticalProduct::class,
            'sale_product',
            'sale_id',
            'product_id' // clé étrangère correcte !
        )
        ->withPivot(['quantity', 'unit_price', 'total'])
        ->withTimestamps();
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
