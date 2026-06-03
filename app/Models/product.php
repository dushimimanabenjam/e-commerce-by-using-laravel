<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'stock',
        'category_id',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(category::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(order_detail::class);
    }
}
