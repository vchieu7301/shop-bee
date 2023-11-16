<?php

namespace App\Models;

use Database\Factories\ProductsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'product_name',
        'price',
        'product_description',
        'quantity',
        'category_id',
        'images'
    ];

    protected static function newFactory()
    {
        return new ProductsFactory();
    }
}
