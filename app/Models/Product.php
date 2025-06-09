<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

     /**
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'name',
        'price',
        'quantity',
        'expiry_date',
    ];

    /**

     * @var array<string, string> */

    protected $cast = [
        'price' => 'decimal:2',
        'expiry_date' => 'date',
   ];
}
