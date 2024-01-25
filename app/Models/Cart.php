<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'product_id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'cart_products', 'cart_id', 'product_id')->withPivot(['size_id', 'quantity'])
            ->with(['category', 'brand', 'images', 'getReview', 'sizes']);
    }
}
