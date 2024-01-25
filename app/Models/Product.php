<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cart;

class Product extends Model
{


    protected $fillable = ['title', 'summary', 'description', 'cat_id', 'brand_id', 'discount', 'status', 'is_featured', 'condition','is_popular','is_banner'];
    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_sizes')->withPivot('quantity', 'price', 'discount');
    }
    public function category()
    {
        return $this->hasOne('App\Models\Category', 'id', 'cat_id');
    }
    public function brand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class,'product_id');
    }
    public static function getAllProduct()
    {
        return Product::with(['category', 'brand', 'sizes'])->orderBy('id', 'desc')->paginate(10);
    }
    public function rel_prods()
    {
        return $this->hasMany('App\Models\Product', 'cat_id', 'cat_id')->where('status', 'active')->orderBy('id', 'DESC')->limit(8);
    }
    public function getReview()
    {
        return $this->hasMany('App\Models\ProductReview', 'product_id', 'id')->with('user_info')->orderBy('id', 'DESC');
    }
    public static function countActiveProduct()
    {
        $data = Product::where('status', 'active')->count();
        if ($data) {
            return $data;
        }
        return 0;
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'cart_products', 'product_id', 'cart_id');
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_products', 'product_id', 'order_id');
    }
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class)->whereNotNull('cart_id');
    }
}
